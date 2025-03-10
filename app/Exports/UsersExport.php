<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Fluent;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * @implements WithMapping<User>
 */
final class UsersExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * @param  Fluent<string, mixed>  $data
     */
    public function __construct(protected Fluent $data) {}

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
     * @return Collection<int, User>
     */
    public function collection(): Collection
    {
        $data = $this->data;

        $query = User::filter($data);

        if ($data->get('type_export') === 'current_page') {
            $query = $query->forPage(
                $data->integer('page', 1),
                $data->integer('per_page', 15),
            );
        }

        return $query->get()->sortBy('id');
    }
}
