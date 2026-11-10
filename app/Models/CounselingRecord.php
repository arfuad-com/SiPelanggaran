<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounselingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'counselor_id',
        'counseling_date',
        'issue_summary',
        'counseling_result',
        'follow_up_action',
    ];

    protected function casts(): array
    {
        return [
            'counseling_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }
}
