<?php

declare(strict_types=1);

namespace App\Support\Modal;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Response as ResponseFactory;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Tighten\Ziggy\BladeRouteGenerator;

final class Modal implements Responsable
{
    protected string $baseUrl;

    /** @param array<string, mixed> $props */
    public function __construct(
        protected string $component,
        protected array $props = []
    ) {}

    public function baseRoute(string $route): static
    {
        $this->baseUrl = $route;

        return $this;
    }

    public function toResponse(mixed $request): mixed
    {
        // @phpstan-ignore-next-line
        inertia()->share('modal', $this->component($request));

        $response = app(DispatchBaseUrlRequest::class)($request, $this->baseUrl);

        return match (true) {
            $response instanceof JsonResponse => $this->toJsonResponse($response),
            $response instanceof IlluminateResponse => $this->toViewResponse($response),
            default => $response,
        };
    }

    protected function toJsonResponse(JsonResponse $response): JsonResponse
    {
        $data = $response->getData(true);

        return $response->setData([
            ...$data,
            'url' => $data['props']['modal']['url'],
        ]);
    }

    protected function toViewResponse(IlluminateResponse $response): IlluminateResponse
    {
        $originalContent = $response->getOriginalContent();

        if (! $originalContent instanceof View) {
            return $response;
        }

        if (class_exists(BladeRouteGenerator::class)) {
            BladeRouteGenerator::$generated = false;
        }

        $viewData = $originalContent->getData();
        $viewData['page']['url'] = $viewData['page']['props']['modal']['url'];

        return ResponseFactory::view($originalContent->getName(), $viewData);
    }

    /** @return array<string, mixed> */
    protected function component(Request $request): array
    {
        return [
            'component' => $this->component,
            'url' => Str::start(Str::after($request->fullUrl(), $request->getSchemeAndHttpHost()), '/'),
            'baseURL' => $this->baseUrl,
            'redirectURL' => $this->determineRedirectURL($request),
            'props' => $this->props,
            'key' => $request->header('X-Inertia-Modal-Key', Str::uuid()->toString()),
            'nonce' => Str::uuid()->toString(),
        ];
    }

    protected function determineRedirectURL(Request $request): string
    {
        if ($redirect = $request->header('X-Inertia-Modal-Redirect')) {
            return $redirect;
        }

        $referer = $request->header('referer');

        if ($request->header('X-Inertia') && $referer && $referer !== url()->current()) {
            return $referer;
        }

        return $this->baseUrl;
    }
}
