<?php

namespace App\Models;

use App\Notifications\CustomerResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'email_verified_at',
        'otp_code',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
        ];
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isVerified(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    /** Generate, store and return a fresh 2-minute OTP. */
    public function generateOtp(): string
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->forceFill([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(2),
        ])->save();

        return $otp;
    }

    public function otpMatches(string $code): bool
    {
        return $this->otp_code !== null
            && hash_equals($this->otp_code, $code)
            && $this->otp_expires_at
            && $this->otp_expires_at->isFuture();
    }

    public function markVerified(): void
    {
        $this->forceFill([
            'email_verified_at' => Carbon::now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ])->save();
    }

    /** Use the customer password broker + a portal-specific reset link. */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomerResetPassword($token));
    }
}
