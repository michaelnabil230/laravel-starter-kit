<?php

declare(strict_types=1);

namespace App\Support\Modal;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response as ResponseFactory;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class Modal implements Responsable
{
    public const string HEADER_MODAL = 'X-Inertia-Modal';

    public const string HEADER_BASE_URL = 'X-Inertia-Modal-Base-Url';

    protected ?string $baseUrl = null;

    protected ModalType $type = ModalType::Modal;

    protected string $size = '2xl';

    protected ?ModalPosition $position = null;

    /**
     * @var array<int, callable> Callbacks to run before the base URL is rerendered.
     */
    protected static array $beforeBaseRerenderCallbacks = [];

    /**
     * @var array<string> Middleware to exclude when dispatching the base URL request.
     */
    protected static array $excludeMiddlewareOnBaseUrl = [];

    /** @param array<array-key, mixed>|Arrayable<array-key, mixed> $props */
    public function __construct(
        protected string $component,
        protected array|Arrayable $props = []
    ) {}

    /**
     * Register a callback to run before the base URL is rerendered.
     */
    public static function beforeBaseRerender(callable $callback): void
    {
        self::$beforeBaseRerenderCallbacks[] = $callback;
    }

    /**
     * Register middleware to exclude when dispatching the base URL request.
     *
     * @param  string[]  $middleware
     */
    public static function excludeMiddlewareOnBaseUrl(array|string $middleware): void
    {
        self::$excludeMiddlewareOnBaseUrl = array_merge(self::$excludeMiddlewareOnBaseUrl, Arr::wrap($middleware));
    }

    /**
     * Get the middleware to exclude when dispatching the base URL request.
     *
     * @return string[]
     */
    public static function getMiddlewareToExcludeOnBaseUrl(): array
    {
        return self::$excludeMiddlewareOnBaseUrl;
    }

    /**
     * Set the type to Slideover.
     */
    public function slideover(): static
    {
        $this->type = ModalType::Slideover;

        return $this;
    }

    /**
     * Set the type to Modal.
     */
    public function modal(): static
    {
        $this->type = ModalType::Modal;

        return $this;
    }

    /**
     * Set the maximum width using Tailwind conventions.
     */
    public function size(string $size): static
    {
        throw_unless(
            in_array($size, ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'], true),
            InvalidArgumentException::class,
            'Invalid max width provided. Please use a value between sm and 7xl.'
        );

        $this->size = $size;

        return $this;
    }

    /**
     * Set the position of the modal on the screen.
     */
    public function position(ModalPosition $position): static
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Position the modal at the bottom of the screen.
     */
    public function bottom(): static
    {
        return $this->position(ModalPosition::Bottom);
    }

    /**
     * Position the slideover at the left side of the screen.
     */
    public function left(): static
    {
        return $this->position(ModalPosition::Left);
    }

    /**
     * Position the slideover at the right side of the screen.
     */
    public function right(): static
    {
        return $this->position(ModalPosition::Right);
    }

    /**
     * Position the modal at the top of the screen.
     */
    public function top(): static
    {
        return $this->position(ModalPosition::Top);
    }

    /**
     * Set the base URL for the modal using an absolute URL.
     */
    public function baseUrl(string $url): static
    {
        $this->baseUrl = $url;

        return $this;
    }

    /**
     * Set the base URL for the modal using a route name.
     */
    public function baseRoute(string $name, mixed $parameters = [], bool $absolute = true): static
    {
        $this->baseUrl = route($name, $parameters, $absolute);

        return $this;
    }

    /**
     * Create an HTTP response with either the modal or the modal's base URL with the modal data.
     */
    public function toResponse(mixed $request): mixed
    {
        /** @var InertiaResponse $modal */
        $modal = inertia()->render($this->component, $this->getProps());

        $baseUrl = $this->resolveBaseUrl($request);

        if ($request->hasHeader(self::HEADER_MODAL) || blank($baseUrl)) {
            return $this->extractMeta($modal->toResponse($request));
        }

        $modalData = $modal->toArray();

        Inertia::share('modal', [
            ...$modalData,
            'baseUrl' => $baseUrl,
            'config' => $this->getConfig(),
        ]);

        $response = resolve(DispatchBaseUrlRequest::class)($request, $baseUrl);

        return match (true) {
            $response instanceof JsonResponse => $this->toJsonResponse($response, $modalData['url']),
            $response instanceof IlluminateResponse => $this->toViewResponse($request, $response, $modalData['url']),
            default => $response,
        };
    }

    /**
     * Get the component name for the modal.
     */
    public function getComponent(): string
    {
        return $this->component;
    }

    /**
     * Get the props for the modal.
     *
     * @return array<array-key, mixed>
     */
    public function getProps(): array
    {
        return $this->props instanceof Arrayable ? $this->props->toArray() : $this->props;
    }

    /**
     * Get the modal configuration as an array.
     *
     * @return array{type: string, size: string|null, position: string|null}
     */
    protected function getConfig(): array
    {
        return [
            'type' => $this->type->value,
            'size' => $this->size,
            'position' => $this->position?->value,
        ];
    }

    /**
     * Resolve the base URL for the modal.
     *
     * Used to render the 'background' page as well as where to redirect after closing the modal.
     */
    protected function resolveBaseUrl(Request $request): ?string
    {
        $candidates = [
            $request->header(self::HEADER_BASE_URL),
            $request->header('referer'),
            $this->baseUrl,
        ];

        foreach ($candidates as $baseUrl) {
            if ($baseUrl !== null && ! $request->is($this->extractPath($baseUrl))) {
                return $baseUrl;
            }
        }

        return null;
    }

    /**
     * Extract and normalize the path from a URL for comparison.
     */
    protected function extractPath(string $url): string
    {
        $decoded = rawurldecode(mb_trim((string) parse_url($url, PHP_URL_PATH), '/'));

        return $decoded !== '' ? $decoded : '/';
    }

    /**
     * Move modal-only Inertia metadata under a meta key for direct modal responses.
     */
    protected function extractMeta(SymfonyResponse $response): SymfonyResponse
    {
        if (! $response instanceof JsonResponse) {
            return $response;
        }

        $data = $response->getData(true);
        $data['meta'] = [];

        foreach (['mergeProps', 'deferredProps', 'cache'] as $key) {
            if (! array_key_exists($key, $data)) {
                continue;
            }

            $data['meta'][$key] = $data[$key];
            unset($data[$key]);
        }

        if ($data['meta'] === []) {
            $data['meta'] = (object) [];
        }

        return $response->setData($data);
    }

    /**
     * Replace the URL in the JSON response with the modal's URL so the
     * Inertia front-end library won't redirect back to the base URL.
     */
    protected function toJsonResponse(JsonResponse $response, string $url): JsonResponse
    {
        return $response->setData([
            ...$response->getData(true),
            'url' => $url,
        ]);
    }

    /**
     * Replace the URL in the View Response with the modal's URL so the
     * Inertia front-end library won't redirect back to the base URL.
     */
    protected function toViewResponse(Request $request, IlluminateResponse $response, string $url): IlluminateResponse
    {
        $originalContent = $response->getOriginalContent();

        if (! $originalContent instanceof View) {
            return $response;
        }

        $viewData = $originalContent->getData();
        $viewData['page']['url'] = $url;

        foreach (self::$beforeBaseRerenderCallbacks as $callback) {
            $callback($request, $response);
        }

        return ResponseFactory::view($originalContent->getName(), $viewData);
    }
}
