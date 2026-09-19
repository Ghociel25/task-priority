<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada user id 1
        $user = User::firstOrCreate([
            'email' => 'mahasiswa@example.com'
        ], [
            'name' => 'Mahasiswa Teladan',
            'password' => bcrypt('password')
        ]);

        Course::create(['user_id' => $user->id, 'course_name' => 'Pemrograman Web', 'lecturer' => 'Pak Budi', 'color_tag' => '#3b82f6']);
        Course::create(['user_id' => $user->id, 'course_name' => 'Kecerdasan Buatan', 'lecturer' => 'Bu Siska', 'color_tag' => '#10b981']);
        Course::create(['user_id' => $user->id, 'course_name' => 'Sistem Basis Data', 'lecturer' => 'Pak Joko', 'color_tag' => '#f59e0b']);
    }
}