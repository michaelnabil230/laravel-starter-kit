<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use App\Traits\HasProfilePhoto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Fluent;
use Propaganistas\LaravelPhone\Casts\RawPhoneNumberCast;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * @property \Propaganistas\LaravelPhone\PhoneNumber $phone
 * @property bool $is_logged_in
 * @property bool $is_tester
 * @property Gender $gender
 * @property Carbon $birth_date
 * @property ?Carbon $email_verified_at
 * @property ?Carbon $last_login_at
 */
final class User extends Authenticatable implements Searchable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasProfilePhoto, HasUuids, Notifiable, Prunable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'phone_country',
        'fcm_token',
        'api_token',
        'gender',
        'photo',
        'last_login_at',
        'birth_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    /**
     * Get the prunable model query.
     *
     * @return Builder<self>
     */
    public function prunable(): Builder
    {
        return self::query()
            ->where('deleted_at', '<=', now()->subDays(30));
    }

    /**
     * Specifies the user's FCM token
     */
    public function routeNotificationForFcm(): ?string
    {
        return $this->fcm_token;
    }

    /**
     * Get the search result for the model.
     */
    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->name,
            route('dashboard.users.show', $this->id),
        );
    }

    /**
     * Filter the employees.
     *
     * @param  Builder<self>  $builder
     * @param  Fluent<string, mixed>  $data
     * @return Builder<self>
     */
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function filter(Builder $builder, Fluent $data): Builder
    {
        return $builder
            ->when(
                $data->string('search')->value(),
                fn (Builder $builder, string $search): Builder => $builder->whereAny(['name', 'email'], 'Like', "%$search%")
            )
            ->when(
                $data->string('gender')->value(),
                fn (Builder $builder, string $gender): Builder => $builder->where('gender', $gender)
            )
            ->when($data->get('trashed'), function (Builder $builder, string $trashed): Builder {
                if ($trashed === 'with') {
                    return $builder->withTrashed();
                }

                if ($trashed === 'only') {
                    return $builder->onlyTrashed();
                }

                return $builder->withoutTrashed();
            })
            ->orderBy(
                $data->get('order_by', 'created_at'),
                $data->get('order_by_direction', 'desc'),
            );
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'gender' => Gender::class,
            'phone' => RawPhoneNumberCast::class,
        ];
    }

    /**
     * Prepare the model for pruning.
     */
    protected function pruning(): void
    {
        $this->removeProfilePhotoFromDisk();
    }

    /**
     * Get the user's is_logged_in.
     *
     * @return Attribute<bool, never>
     */
    protected function isLoggedIn(): Attribute
    {
        return Attribute::get(fn (): bool => is_null($this->last_login_at));
    }

    /**
     * Get the user's is_tester.
     *
     * @return Attribute<bool, never>
     */
    protected function isTester(): Attribute
    {
        return Attribute::get(fn (): bool => $this->email === 'tester@app.com');
    }

    /**
     * Get the user's age.
     *
     * @return Attribute<int, never>
     */
    protected function age(): Attribute
    {
        return Attribute::get(fn () => Carbon::parse($this->birth_date)->age);
    }

    /**
     * Get the user's birth date format in humans.
     *
     * @return Attribute<string, never>
     */
    protected function birthDateHumans(): Attribute
    {
        return Attribute::get(fn (): string => Carbon::parse(now()->format('Y').$this->birth_date->format('-m-d'))->diffForHumans());
    }
}
