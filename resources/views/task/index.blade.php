<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskPriority - Smart Task Monitoring</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(0.5);
            opacity: 0.7;
            transition: 0.2s;
        }
        input[type="datetime-local"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body class="bg-[#fafafa] text-zinc-900 font-sans antialiased selection:bg-zinc-900 selection:text-white">

    <div class="max-w-6xl mx-auto px-6 py-12">
        
        <!-- Header Minimalis -->
        <div class="mb-10 pb-6 border-b border-zinc-200/80 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3">
                    <!-- Menampilkan Gambar Logo Kustom -->
                    <img src="{{ asset('images/taskpriority.jpg') }}" alt="TaskPriority Logo" class="w-9 h-9 rounded-xl shadow-2xs object-cover">
                    
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-zinc-900 leading-none">TaskPriority</h1>
                        <span class="text-[11px] font-medium text-zinc-400 tracking-wider uppercase">Smart Monitoring</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-2 text-xs font-medium text-zinc-500 bg-white px-3 py-1.5 rounded-lg border border-zinc-200 shadow-2xs w-fit">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>System Active & Optimized</span>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50/80 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center space-x-2 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @php
            $totalTasks = $tasks->where('is_completed', false)->count();
            $emergencyTasks = $tasks->filter(function($task) {
                if ($task->is_completed) return false;
                $diff = \Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::parse($task->deadline), false);
                return $diff <= 48 && $diff >= 0;
            })->count();
        @endphp

        <!-- Quick Stats Bar -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-zinc-200/80 shadow-2xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Total Tugas Aktif</p>
                    <h3 class="text-2xl font-bold text-zinc-900 mt-1">{{ $totalTasks }} <span class="text-xs font-normal text-zinc-500">tugas</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-zinc-100 flex items-center justify-center text-zinc-700">
                    📋
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-zinc-200/80 shadow-2xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Status Darurat (&lt;= 2 Hari)</p>
                    <h3 class="text-2xl font-bold text-rose-600 mt-1">{{ $emergencyTasks }} <span class="text-xs font-normal text-zinc-500">tugas</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                    🔥
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Form Tambah Tugas -->
            <div class="bg-white p-6 rounded-2xl border border-zinc-200/80 shadow-2xs h-fit">
                <h2 class="text-base font-semibold text-zinc-900 mb-5 flex items-center space-x-2">
                    <span>➕</span>
                    <span>Tambah Tugas Baru</span>
                </h2>
                <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1.5">Mata Kuliah</label>
                        <input type="text" name="course" required placeholder="Contoh: Pemvis, PBO, Jarkom" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl p-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-zinc-900 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1.5">Judul Tugas</label>
                        <input type="text" name="title" required placeholder="Contoh: Analisis Kompleksitas Algoritma" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl p-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-zinc-900 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1.5">Deadline</label>
                        <input type="text" id="datetime-picker" name="deadline" required placeholder="Pilih tanggal & waktu..." class="w-full bg-zinc-50 border border-zinc-200 rounded-xl p-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-zinc-900 focus:outline-none transition">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1.5">Bobot Nilai (%)</label>
                            <input type="number" step="0.1" name="weight" value="10" required class="w-full bg-zinc-50 border border-zinc-200 rounded-xl p-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-zinc-900 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1.5">Kesulitan (1-5)</label>
                            <input type="number" min="1" max="5" name="difficulty" value="3" required class="w-full bg-zinc-50 border border-zinc-200 rounded-xl p-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-zinc-900 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1.5">Estimasi Pengerjaan (Jam)</label>
                        <input type="number" step="0.5" name="estimated_hours" value="2" required class="w-full bg-zinc-50 border border-zinc-200 rounded-xl p-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-zinc-900 transition">
                    </div>

                    <button type="submit" class="w-full bg-zinc-900 hover:bg-zinc-800 text-white font-medium py-2.5 rounded-xl text-sm transition shadow-sm cursor-pointer">
                        Hitung & Tambah Tugas
                    </button>
                </form>
            </div>

            <!-- Daftar Priority Queue Tasks -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-base font-semibold text-zinc-900">Priority Queue (Urutan Pengerjaan)</h2>
                    <span class="text-xs text-zinc-400">Disusun otomatis berdasarkan Urgensi & Bobot</span>
                </div>

                <div class="bg-white border border-zinc-200/80 rounded-2xl overflow-hidden shadow-2xs">
                    <div class="divide-y divide-zinc-100">
                        @forelse($tasks as $index => $task)
                            @php
                                $now = \Carbon\Carbon::now();
                                $deadline = \Carbon\Carbon::parse($task->deadline);
                                $diffInHours = $now->diffInHours($deadline, false);

                                if ($task->is_completed) {
                                    $badgeColor = 'bg-zinc-200 text-zinc-600';
                                    $statusText = 'Selesai';
                                } elseif ($diffInHours < 0) {
                                    $badgeColor = 'bg-zinc-900 text-white';
                                    $statusText = 'Terlambat';
                                } elseif ($diffInHours <= 48) {
                                    $badgeColor = 'bg-rose-500 text-white animate-pulse';
                                    $statusText = 'DARURAT (<= 2 Hari)';
                                } elseif ($diffInHours <= 120) {
                                    $badgeColor = 'bg-amber-500 text-white';
                                    $statusText = 'Waspada (<= 5 Hari)';
                                } else {
                                    $badgeColor = 'bg-emerald-500 text-white';
                                    $statusText = 'Aman';
                                }
                            @endphp

                            <div class="p-5 flex items-center justify-between transition task-item {{ $task->is_completed ? 'bg-zinc-50/50 opacity-60' : 'hover:bg-zinc-50/60' }}" data-hours="{{ $diffInHours }}" data-completed="{{ $task->is_completed ? 'true' : 'false' }}">
                                <div class="space-y-1.5 pr-2">
                                    <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-md bg-zinc-100 text-zinc-800">#{{ $index + 1 }}</span>
                                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-md text-white shadow-2xs bg-zinc-800">
                                            {{ $task->course }}
                                        </span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $badgeColor }}">
                                            {{ $statusText }}
                                        </span>
                                        <h3 class="font-semibold text-sm text-zinc-900 w-full mt-1 {{ $task->is_completed ? 'line-through text-zinc-400' : '' }}">{{ $task->title }}</h3>
                                    </div>
                                    <p class="text-xs text-zinc-400 flex items-center space-x-2 flex-wrap gap-y-1">
                                        <span>📅 {{ $deadline->format('d M Y, H:i') }}</span>
                                        <span>•</span>
                                        <span>⚡ Bobot: {{ $task->weight }}%</span>
                                        <span>•</span>
                                        <span>⏱️ Est: {{ $task->estimated_hours }} Jam</span>
                                    </p>
                                </div>

                                <!-- BAGIAN FLEX TOMBOL AKSI YANG DIPERBAIKI -->
                                <div class="flex items-center space-x-4 shrink-0 pl-4">
                                    <div class="text-right">
                                        <span class="block text-[10px] text-zinc-400 uppercase font-semibold tracking-wider">Skor</span>
                                        <span class="text-sm font-bold {{ $task->is_completed ? 'text-zinc-400' : 'text-rose-600' }}">{{ $task->priority_score }}</span>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <!-- Tombol Selesai / Batalkan -->
                                        <form action="{{ route('tasks.complete', $task->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm p-2 rounded-xl transition cursor-pointer {{ $task->is_completed ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-zinc-100 text-zinc-500 hover:bg-zinc-200 hover:text-zinc-900' }}" title="{{ $task->is_completed ? 'Batalkan Selesai' : 'Tandai Selesai' }}">
                                                {!! $task->is_completed ? '✅' : '✔️' !!}
                                            </button>
                                        </form>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-zinc-300 hover:text-rose-600 text-sm p-1.5 rounded-lg hover:bg-rose-50 transition cursor-pointer" title="Hapus Tugas">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center text-zinc-400 text-sm">
                                <p class="text-lg mb-1">✨</p>
                                Belum ada tugas dalam antrean. Silakan tambahkan tugas melalui form di sebelah kiri.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Fitur Getar -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if ("vibrate" in navigator) {
                const taskItems = document.querySelectorAll('.task-item');
                let hasEmergencyActive = false;

                taskItems.forEach(item => {
                    const hoursLeft = parseFloat(item.getAttribute('data-hours'));
                    const isCompleted = item.getAttribute('data-completed') === 'true';
                    
                    if (!isCompleted && hoursLeft >= 0 && hoursLeft <= 48) {
                        hasEmergencyActive = true;
                    }
                });

                if (hasEmergencyActive) {
                    navigator.vibrate([200, 100, 200]);
                }
            }
        });
    </script>

    <!-- Script Flatpickr -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#datetime-picker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                locale: {
                    firstDayOfWeek: 1
                }
            });
        });
    </script>
</body>
</html>