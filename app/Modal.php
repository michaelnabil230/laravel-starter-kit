<?php

declare(strict_types=1);

namespace App;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Response as ResponseFactory;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class Modal implements Responsable
{
    protected Request $request;

    protected string $baseURL;

    /** @param array<string, mixed> $props */
    public function __construct(
        protected string $component,
        protected array $props = []
    ) {
        $this->request = app('request');
    }

    public function baseRoute(string $route): static
    {
        $this->baseURL = $route;

        return $this;
    }

    /** @param array<string, mixed> $props */
    public function with(array $props): static
    {
        $this->props = $props;

        return $this;
    }

    public function render(): mixed
    {
        /** @phpstan-ignore-next-line */
        inertia()->share(['modal' => $this->component()]);

        // render background component on first visit
        if ($this->request->header('X-Inertia') && $this->request->header('X-Inertia-Partial-Component')) {
            /** @phpstan-ignore-next-line */
            return inertia()->render($this->request->header('X-Inertia-Partial-Component'));
        }

        $request = $this->prepareRequestForRendering();

        app()->instance('request', $request);

        return $this->handleRoute($request);
    }

    public function toResponse(mixed $request): mixed
    {
        $response = $this->render();

        return match (true) {
            $response instanceof JsonResponse => $response,
            $response instanceof IlluminateResponse => $this->toViewResponse($response),
            default => $response,
        };
    }

    protected function toViewResponse(IlluminateResponse $response): IlluminateResponse
    {
        $originalContent = $response->getOriginalContent();

        if (! $originalContent instanceof View) {
            return $response;
        }

        return ResponseFactory::view($originalContent->getName(), $originalContent->getData());
    }

    protected function handleRoute(Request $request): mixed
    {
        /** @var \Illuminate\Routing\Router */
        $router = app('router');

        $middleware = new SubstituteBindings($router);

        $route = app('router')->getRoutes()->match($request);

        return $middleware->handle(
            $request,
            fn () => $route->run()
        );
    }

    /** @return array<string, mixed> */
    protected function component(): array
    {
        return [
            'component' => $this->component,
            'baseURL' => $this->baseURL,
            'redirectURL' => $this->determineRedirectURL(),
            'props' => $this->props,
            'key' => $this->request->header('X-Inertia-Modal-Key', Str::uuid()->toString()),
            'nonce' => Str::uuid()->toString(),
        ];
    }

    protected function determineRedirectURL(): string
    {
        if ($redirect = $this->request->header('X-Inertia-Modal-Redirect')) {
            return $redirect;
        }

        $referer = $this->request->header('referer');

        if ($this->request->header('X-Inertia') && $referer && $referer !== url()->current()) {
            return $referer;
        }

        return $this->baseURL;
    }

    protected function prepareRequestForRendering(): Request
    {
        /** @var Request $originalRequest */
        $originalRequest = app('request');

        $request = Request::create(
            $this->determineRedirectURL(),
            Request::METHOD_GET,
            $originalRequest->query->all(),
            $originalRequest->cookies->all(),
            $originalRequest->files->all(),
            $originalRequest->server->all(),
            $originalRequest->getContent()
        );

        $request->headers->replace($originalRequest->headers->all());
        $request
            ->setJson($originalRequest->json())
            ->setUserResolver(fn () => $originalRequest->getUserResolver())
            ->setRouteResolver(fn () => $originalRequest->route())
            ->setLaravelSession($originalRequest->session());

        return $request;
    }
}
