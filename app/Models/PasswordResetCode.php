<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read Carbon $created_at
 */
final class PasswordResetCode extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'email';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The number of seconds a code should last.
     */
    protected int $expires = 60 * 60;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'code',
    ];

    /**
     * Create a new code record.
     */
    public static function createCode(string $email): int
    {
        self::deleteByEmail($email);

        $code = random_int(1111, 9999);

        self::query()->create(['email' => $email, 'code' => $code]);

        return $code;
    }

    /**
     * Delete expired codes.
     */
    public static function deleteExpired(): int
    {
        $expiredAt = now()->subSeconds((new self)->expires);

        return self::query()->where('created_at', '<', $expiredAt)->delete();
    }

    /**
     * Determine if the code has expired.
     */
    public function isExpired(): bool
    {
        return Carbon::parse($this->created_at)->addSeconds($this->expires)->isPast();
    }

    /**
     * Delete all existing reset codes from the database.
     */
    protected static function deleteByEmail(string $email): int
    {
        return self::query()->where('email', $email)->delete();
    }
}
