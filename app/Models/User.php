<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nip',
        'email',
        'phone',
        'gender',
        'position',
        'domisili',
        'avatar',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function reportedViolations(): HasMany
    {
        return $this->hasMany(StudentViolation::class, 'reported_by');
    }

    public function validatedViolations(): HasMany
    {
        return $this->hasMany(StudentViolation::class, 'validated_by');
    }

    public function counselingSessions(): HasMany
    {
        return $this->hasMany(CounselingRecord::class, 'counselor_id');
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
                return $this->avatar;
            }
            if (file_exists(public_path($this->avatar))) {
                return asset($this->avatar);
            }
            if (file_exists(public_path('storage/'.$this->avatar))) {
                return asset('storage/'.$this->avatar);
            }
        }

        $roleAvatarMap = [
            'admin' => 'images/avatars/admin.jpg',
            'bk' => 'images/avatars/bk.jpg',
            'guru' => 'images/avatars/guru1.jpg',
        ];

        if (isset($roleAvatarMap[$this->role]) && file_exists(public_path($roleAvatarMap[$this->role]))) {
            return asset($roleAvatarMap[$this->role]);
        }

        $name = urlencode($this->name ?? 'User');
        $bg = match ($this->role) {
            'admin' => '0f172a',
            'bk' => '4338ca',
            'guru' => '0f766e',
            default => '334155',
        };

        return "https://ui-avatars.com/api/?name={$name}&background={$bg}&color=ffffff&size=128&bold=true";
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBK(): bool
    {
        return $this->role === 'bk';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }
}
