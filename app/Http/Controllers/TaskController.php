<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Menampilkan Dashboard & Daftar Tugas terurut berdasarkan Priority Score
    public function index()
    {
        $userId = Auth::id() ?? 1;

        // Ambil tugas milik user, urutkan dari priority_score tertinggi ke terendah
        $tasks = Task::with('course')
            ->where('user_id', $userId)
            ->orderBy('priority_score', 'desc')
            ->get();

        $courses = Course::where('user_id', $userId)->get();

        return view('task.index', compact('tasks', 'courses'));
    }

    // Menyimpan Tugas Baru
    public function store(Request $request)
    {
        $request->validate([
            'course' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'deadline' => 'required|date',
            'weight' => 'required|numeric',
            'difficulty' => 'required|integer|min:1|max:5',
            'estimated_hours' => 'required|numeric',
        ]);

        // 2. Variabel untuk perhitungan rumus Priority Scheduling
        $now = \Carbon\Carbon::now();
        $deadline = \Carbon\Carbon::parse($request->deadline);
        $hoursLeft = max($now->diffInHours($deadline, false), 1); 
        
        $weight = $request->weight;
        $difficulty = $request->difficulty;
        $estHours = $request->estimated_hours;

        // 3. Menghitung skor prioritas
        $priorityScore = round((($weight * $difficulty) / $hoursLeft) * (1 / max($estHours, 0.5)), 2);

        // 4. Menyimpan data ke database (Model Task)
        \App\Models\Task::create([
            'user_id' => Auth::id() ?? 1,
            'course' => $request->course, 
            'title' => $request->title,
            'deadline' => $request->deadline,
            'weight' => $weight,
            'difficulty' => $difficulty,
            'estimated_hours' => $estHours,
            'priority_score' => $priorityScore,
            'is_completed' => false,
        ]);

        // 5. Redirect kembali ke halaman utama dengan pesan sukses
        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil ditambahkan!');
    }

    // Mengubah Status Tugas Menjadi Selesai / Belum Selesai
    public function complete(Task $task)
    {
        $task->update([
            'is_completed' => !$task->is_completed
        ]);

        $statusMsg = $task->is_completed ? 'Tugas ditandai selesai! 🎉' : 'Tugas diaktifkan kembali.';
        return redirect()->route('tasks.index')->with('success', $statusMsg);
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