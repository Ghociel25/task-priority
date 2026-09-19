<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Course;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Menampilkan Dashboard & Daftar Tugas berdasarkan token unik di URL
    public function index($token)
    {
        // Ambil tugas milik token ini, urutkan dari priority_score tertinggi ke terendah
        $tasks = Task::where('token', $token)
            ->orderBy('priority_score', 'desc')
            ->get();

        $courses = Course::where('token', $token)->get();

        return view('task.index', compact('tasks', 'courses', 'token'));
    }

    // Menyimpan Tugas Baru
    public function store(Request $request, $token)
    {
        $request->validate([
            'course' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'deadline' => 'required|date',
            'weight' => 'required|numeric',
            'difficulty' => 'required|integer|min:1|max:5',
            'estimated_hours' => 'required|numeric',
        ]);

        // Variabel untuk perhitungan rumus Priority Scheduling
        $now = \Carbon\Carbon::now();
        $deadline = \Carbon\Carbon::parse($request->deadline);
        $hoursLeft = max($now->diffInHours($deadline, false), 1); 
        
        $weight = $request->weight;
        $difficulty = $request->difficulty;
        $estHours = $request->estimated_hours;

        // Menghitung skor prioritas
        $priorityScore = round((($weight * $difficulty) / $hoursLeft) * (1 / max($estHours, 0.5)), 2);

        // Menyimpan data ke database beserta token uniknya
        \App\Models\Task::create([
            'token' => $token, // <-- TOKEN DISIMPAN DI SINI
            'course' => $request->course, 
            'title' => $request->title,
            'deadline' => $request->deadline,
            'weight' => $weight,
            'difficulty' => $difficulty,
            'estimated_hours' => $estHours,
            'priority_score' => $priorityScore,
            'is_completed' => false,
        ]);

        // Redirect kembali ke halaman token yang sama dengan pesan sukses
        return redirect('/' . $token)->with('success', 'Tugas berhasil ditambahkan!');
    }

    // Mengubah Status Tugas Menjadi Selesai / Belum Selesai
    public function complete(Task $task)
    {
        $task->update([
            'is_completed' => !$task->is_completed
        ]);

        $statusMsg = $task->is_completed ? 'Tugas ditandai selesai! 🎉' : 'Tugas diaktifkan kembali.';
        return redirect()->back()->with('success', $statusMsg);
    }

    // Mengubah Status Tugas (Pending -> In Progress -> Completed)
    public function updateStatus(Request $request, Task $task)
    {
        $task->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status tugas diperbarui!');
    }

    // Menghapus Tugas
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Tugas berhasil dihapus.');
    }
}