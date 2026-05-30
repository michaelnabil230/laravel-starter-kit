<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DeviceType;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;

/**
 * @property bool $is_logged_in
 * @property bool $is_tester
 * @property Gender $gender
 * @property ?Carbon $birth_date
 * @property ?Carbon $last_login_at
 * @property ?DeviceType $device_type
 * @property ?string $otp_code
 * @property ?Carbon $otp_expires_at
 * @property ?Carbon $otp_last_sent_at
 * @property ?Carbon $phone_verified_at
 * @property bool $is_phone_verified
 */
final class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Prunable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'otp_code',
        'otp_expires_at',
        'otp_last_sent_at',
        'phone_verified_at',
        'fcm_token',
        'api_token',
        'gender',
        'photo',
        'last_login_at',
        'birth_date',
        'device_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'otp_code',
        'remember_token',
        'api_token',
    ];

    /**
     * OTP expiration time in minutes.
     */
    protected int $otpExpiryMinutes = 5;

    /**
     * Cooldown period before OTP can be resent (seconds).
     */
    protected int $resendCooldown = 120;

    /**
     * Generate a new OTP for the user.
     */
    public function generateOtp(): int
    {
        $otp = app()->isProduction() ? random_int(1111, 9999) : 1234;

        $this->update([
            'otp_code' => Hash::make((string) $otp),
            'otp_expires_at' => now()->addMinutes($this->otpExpiryMinutes),
            'otp_last_sent_at' => now(),
        ]);

        return $otp;
    }

    /**
     * Determine whether the user can request a new OTP.
     */
    public function canResendOtp(): bool
    {
        if (! $this->otp_last_sent_at) {
            return true;
        }

        return $this->otp_last_sent_at->diffInSeconds(now()) >= $this->resendCooldown;
    }

    /**
     * Verify the provided OTP against stored hash.
     */
    public function verifyOtp(string $otp): bool
    {
        if (! $this->otp_code || ! $this->otp_expires_at) {
            return false;
        }

        if (now()->greaterThan($this->otp_expires_at)) {
            return false;
        }

        return Hash::check($otp, $this->otp_code);
    }

    /**
     * Mark the user as verified and clear OTP data.
     */
    public function markAsVerified(): void
    {
        $this->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'phone_verified_at' => now(),
        ]);
    }

    /**
     * Determine if the OTP has expired.
     */
    public function otpExpired(): bool
    {
        return ! $this->otp_expires_at ||
            now()->greaterThan($this->otp_expires_at);
    }

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
     * Determine if the user's phone is verified.
     *
     * @return Attribute<bool, never>
     */
    protected function isPhoneVerified(): Attribute
    {
        return Attribute::get(fn (): bool => $this->phone_verified_at !== null);
    }

    /**
     * Filter the users.
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
                fn (Builder $builder, string $search): Builder => $builder->whereAny(['name', 'phone'], 'Like', "%$search%")
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
            'last_login_at' => 'datetime',
            'gender' => Gender::class,
            'device_type' => DeviceType::class,
            'otp_expires_at' => 'datetime',
            'otp_last_sent_at' => 'datetime',
            'phone_verified_at' => 'datetime',
        ];
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
        return Attribute::get(fn (): bool => str($this->phone)->is('9665432167890'));
    }

    /**
     * Get the user's age.
     *
     * @return Attribute<int, never>
     */
    protected function age(): Attribute
    {
        return Attribute::get(fn () => $this->birth_date ? Date::parse($this->birth_date)->age : 0);
    }

    /**
     * Get the user's birth date format in humans.
     *
     * @return Attribute<string, never>
     */
    protected function birthDateHumans(): Attribute
    {
        return Attribute::get(fn (): string => $this->birth_date ? Date::parse(now()->format('Y').$this->birth_date->format('-m-d'))->diffForHumans() : '');
    }

    /**
     * Normalize and set the user's phone number.
     *
     * @return Attribute<never, string>
     */
    protected function phone(): Attribute
    {
        return Attribute::make(
            set: function (string $value): string {
                // Remove spaces and dashes using Str
                $normalized = Str::of($value)->replace([' ', '-'], '');

                // Already starts with +966
                if ($normalized->startsWith('+966')) {
                    return $normalized->toString();
                }

                // Starts with 966 (without +)
                if ($normalized->startsWith('966')) {
                    return $normalized->prepend('+')->toString();
                }

                // Starts with 05 -> convert to international +966 and drop leading 0
                if ($normalized->startsWith('05') && $normalized->length() === 10) {
                    return $normalized->substr(1)->prepend('+966')->toString();
                }

                // Starts with 5 and length 9 -> local mobile, prefix +966
                if ($normalized->startsWith('5') && $normalized->length() === 9) {
                    return $normalized->prepend('+966')->toString();
                }

                // Fallback: return as-is (let validation handle incorrect formats)
                return $normalized->toString();
            }
        );
    }

    /**
     * Get the user's is_saudi.
     *
     * @return Attribute<bool, never>
     */
    protected function isSaudi(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => filled($this->national_id)
                && mb_strlen($this->national_id) === 10
                && str_starts_with($this->national_id, '1')
        );
    }

    /**
     * Scope a query to only include phone verified users.
     *
     * @param  Builder<self>  $builder
     * @return Builder<self>
     */
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function phoneVerified(Builder $builder): Builder
    {
        return $builder->whereNotNull('phone_verified_at');
    }

    /**
     * Scope a query to only include unverified users.
     *
     * @param  Builder<self>  $builder
     * @return Builder<self>
     */
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function phoneUnverified(Builder $builder): Builder
    {
        return $builder->whereNull('phone_verified_at');
    }
}
