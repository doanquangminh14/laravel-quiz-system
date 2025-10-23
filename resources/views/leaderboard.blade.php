<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Xếp Hạng</title>
    @vite('resources/css/app.css')

    <style>
        @keyframes rise {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .podium {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            gap: 1rem;
            margin: 2rem auto;
            max-width: 800px;
        }

        .slot {
            flex: 1;
            text-align: center;
            padding: 1rem;
            border-radius: 1rem;
            animation: rise 0.8s ease forwards;
        }

        .gold   { background: linear-gradient(135deg, #FFD700, #FFC107); color: #222; }
        .silver { background: linear-gradient(135deg, #C0C0C0, #BDBDBD); color: #222; }
        .bronze { background: linear-gradient(135deg, #CD7F32, #C97E44); color: #fff; }

        .avatar {
            width: 90px; height: 90px;
            border-radius: 50%;
            margin: 0 auto 0.5rem;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        table {
            width: 100%;
            margin-top: 2rem;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body class="bg-gray-100">
    <x-user-navbar></x-user-navbar>
<div class="container mx-auto py-10 px-4">

    <h1 class="text-3xl font-extrabold text-center text-green-700 mb-8">
        🏆 BẢNG XẾP HẠNG
    </h1>

    <!-- Bộ lọc -->
    <form method="GET" action="{{ route('leaderboard') }}" class="flex flex-col md:flex-row justify-center items-center gap-3 mb-6">
        <input type="text" name="search" value="{{ $search }}" placeholder="🔍 Tìm người chơi..."
            class="border rounded-lg px-4 py-2 w-64 focus:ring-2 focus:ring-green-400" />

        <select name="category" onchange="this.form.submit()"
            class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400">
            <option value="">🎯 Tất cả chủ đề</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $selectedCategory == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
            Tìm
        </button>
    </form>

    <!-- Top 3 -->
    <div class="flex flex-wrap justify-center items-end gap-6 mb-8 text-center">
        @foreach($leaderboard->take(3) as $index => $player)
            @php
                $colors = ['#FFD700', '#C0C0C0', '#CD7F32']; // vàng, bạc, đồng
                $ranks = ['🥇', '🥈', '🥉'];
                $bg = $colors[$index];
                $scale = [1.15, 1.05, 1.0][$index];
            @endphp
            <div class="w-52 transform transition-all duration-500 hover:scale-105 rounded-2xl p-5 shadow-xl"
                style="background-color: {{ $bg }}; transform: scale({{ $scale }});">
                <div class="animate-bounce w-24 h-24 mx-auto rounded-full border-4 border-white bg-white/40 mb-3 flex items-center justify-center">
                    <span class="text-3xl">{{ $ranks[$index] }}</span>
                </div>
                <h2 class="font-bold text-lg">{{ $player->name }}</h2>
                <p class="text-gray-700">Hạng {{ $index + 1 }}</p>
                <p class="text-2xl font-extrabold mt-1">{{ $player->total_score }}</p>
            </div>
        @endforeach
    </div>

    <!-- Bảng chi tiết -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-xl shadow-md">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="py-3 px-4 text-center">#</th>
                    <th class="py-3 px-4 text-left">Người Chơi</th>
                    <th class="py-3 px-4 text-center">Điểm Cao Nhất</th>
                    <th class="py-3 px-4 text-center">Tổng Điểm</th>
                    <th class="py-3 px-4 text-center">Quiz Hoàn Thành</th>
                    <th class="py-3 px-4 text-center">Lần Cuối Làm</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaderboard as $index => $player)
                    <tr class="border-b hover:bg-green-50 transition">
                        <td class="py-2 px-4 text-center font-semibold">{{ $index + 1 }}</td>
                        <td class="py-2 px-4 font-medium">{{ $player->name }}</td>
                        <td class="py-2 px-4 text-center">{{ $player->highest_score }}</td>
                        <td class="py-2 px-4 text-center">{{ $player->total_score }}</td>
                        <td class="py-2 px-4 text-center">{{ $player->quizzes_attempted }}</td>
                        <td class="py-2 px-4 text-center text-gray-500">
                            {{ \Carbon\Carbon::parse($player->last_attempted)->diffForHumans() }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>


</body>
</html>
