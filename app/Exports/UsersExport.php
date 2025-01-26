<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Fluent;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * @implements WithMapping<User>
 */
final class UsersExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * @param  ?Builder<User>  $query
     * @param  ?Fluent<string, mixed>  $filter
     */
    public function __construct(
        protected ?Builder $query = null,
        protected ?Fluent $filter = null,
    ) {}

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Phone',
            'Gender',
            'Birth date',
        ];
    }

    /**
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        return [
            $row->id,
            $row->name,
            $row->email,
            $row->phone,
            $row->gender->value,
            $row->birth_date,
        ];
    }

    /**
     * Get the query to export.
     *
     * @return Builder<User>
     */
    public function query(): Builder
    {
        $query = $this->query ?? User::query();

        return $query
            ->when(
                $this->filter,
                fn (Builder $builder, Fluent $filter) => $builder
                    ->filter($filter)
                    ->when(
                        $filter->get('type_export') === 'current_page',
                        fn (Builder $builder) => $builder->forPage(
                            $filter->integer('page', 1),
                            $filter->integer('per_page', 15),
                        )
                    ),
            );
    }
}
