<?php

declare(strict_types=1);

namespace App\Imports;

use App\Enums\Gender;
use App\Models\User;
use App\Notifications\Welcome;
use App\Rules\Rule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

final class UsersImport implements ShouldQueue, SkipsEmptyRows, ToCollection, WithChunkReading, WithHeadingRow, WithValidation
{
    use Queueable, SerializesModels;

    /**
     * For get the imported data.
     *
     * @param  Collection<array-key, Collection<array-key, string>>  $rows
     */
    public function collection(Collection $rows): void
    {
        $rows->each($this->model(...));
    }

    /**
     * Get the validation rules that apply to the file.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'email' => ['required', Rule::email()->default(), Rule::unique(User::class)],
            'phone' => ['required', 'phone', Rule::unique(User::class)],
            'phone_country' => Rule::isPhoneCountry(),
            'birth_date' => ['required', Rule::birthDate()],
            'gender' => ['required', Rule::enum(Gender::class)],
        ];
    }

    /**
     * Get the chunk size for reading.
     */
    public function chunkSize(): int
    {
        return 50;
    }

    /**
     * To create a new model.
     *
     * @param  Collection<array-key, string>  $row
     */
    protected function model(Collection $row): User
    {
        $user = User::create([
            'name' => $row->get('name'),
            'email' => $row->get('email'),
            'password' => $row->get('password'),
            'phone' => $row->get('phone'),
            'birth_date' => Carbon::parse($row->get('birth_date')),
            'gender' => Gender::from($row->get('gender', 'male')),
        ]);

        $user->notify(new Welcome);

        return $user;
    }
}
