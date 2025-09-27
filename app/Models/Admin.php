<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AdminRole;
use App\Enums\Gender;
use App\Traits\HasProfilePhoto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Propaganistas\LaravelPhone\Casts\RawPhoneNumberCast;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * @property \Propaganistas\LaravelPhone\PhoneNumber $phone
 * @property AdminRole $role
 */
final class Admin extends Authenticatable implements Searchable
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory, HasProfilePhoto, HasUuids, Notifiable, Prunable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'phone_country',
        'password',
        'role',
        'photo',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
     * Get the search result for the model.
     */
    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->name,
            route('dashboard.admins.edit', $this->id),
        );
    }

    /**
     * Prepare the model for pruning.
     */
    protected function pruning(): void
    {
        $this->removeProfilePhotoFromDisk();
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
            'password' => 'hashed',
            'gender' => Gender::class,
            'role' => AdminRole::class,
            'phone' => RawPhoneNumberCast::class,
        ];
    }
}
