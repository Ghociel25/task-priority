<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'course',
        'title',
        'description',
        'deadline',
        'weight',
        'difficulty',
        'estimated_hours',
        'priority_score',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Algoritma Priority Scheduling Otomatis
     * Formula: (Bobot Nilai × Tingkat Kesulitan) / Sisa Waktu (Jam)
     * Semakin dekat deadline dan semakin besar bobotnya, skor akan semakin tinggi.
     */
    public static function calculatePriority($weight, $difficulty, $deadline)
    {
        $now = Carbon::now();
        $deadlineTime = Carbon::parse($deadline);
        
        // Hitung sisa jam menuju deadline (minimal 0.1 jam agar tidak division by zero)
        $remainingHours = max($now->diffInHours($deadlineTime, false), 0.1);

        // Jika deadline sudah lewat, berikan skor maksimal
        if ($now->greaterThan($deadlineTime)) {
            return 9999.0;
        }

        // Contoh rumus: (Weight * Difficulty) / Remaining Hours
        $score = ($weight * $difficulty) / $remainingHours;
        
        return round($score, 2);
    }

    // Otomatis hitung ulang priority_score setiap kali task dibuat atau di-update
    protected static function booted()
    {
        static::saving(function ($task) {
            $task->priority_score = self::calculatePriority(
                $task->weight,
                $task->difficulty,
                $task->deadline
            );
        });
    }
}