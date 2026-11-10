<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'nisn',
        'nis',
        'name',
        'gender',
        'total_violation_points',
        'parent_name',
        'parent_phone',
    ];

    protected function casts(): array
    {
        return [
            'total_violation_points' => 'integer',
        ];
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function violations(): HasMany
    {
        return $this->hasMany(StudentViolation::class);
    }

    public function validatedViolations(): HasMany
    {
        return $this->hasMany(StudentViolation::class)->where('status', 'validated');
    }

    public function counselingRecords(): HasMany
    {
        return $this->hasMany(CounselingRecord::class);
    }
}
