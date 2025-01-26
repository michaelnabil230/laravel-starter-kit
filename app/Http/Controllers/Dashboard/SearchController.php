<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Searchable\Search;

final class SearchController
{
    /**
     * @var array<class-string, list<string>>
     */
    protected array $models = [
        \App\Models\Admin::class => [
            'id',
            'email',
            'name',
        ],
        \App\Models\User::class => [
            'id',
            'email',
            'name',
        ],
    ];

    public function __invoke(Request $request): JsonResponse
    {
        $search = $request->string('search');

        if ($search->isEmpty()) {
            return response()->json(Collection::make());
        }

        $searchResults = new Search;

        foreach ($this->models as $model => $attributes) {
            $searchResults = $searchResults->registerModel($model, $attributes);
        }

        $data = $searchResults
            ->limitAspectResults(15)
            ->search($search->value())
            ->groupByType();

        return response()->json($data);
    }
}
