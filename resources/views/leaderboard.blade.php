<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Xếp Hạng Chuyên Nghiệp</title>
    {{-- Đảm bảo bạn đã cài đặt và chạy 'npm run dev' hoặc 'vite' --}}
    @vite('resources/css/app.css') 
</head>
<body>
    {{-- THAY THẾ @extends BẰNG CÁC THÀNH PHẦN CHUNG --}}
    <x-user-navbar></x-user-navbar>
<div class="container mx-auto p-4 bg-gray-100 min-h-screen">
    <h1 class="text-4xl font-extrabold text-center text-green-700 pt-8 pb-4">
        🏆 BẢNG XẾP HẠNG THÀNH TÍCH
    </h1>
    <p class="text-center text-gray-600 mb-8">Thời gian: {{ ucfirst($currentFilters['time']) }}</p>

    {{-- Bộ Lọc và Tìm kiếm --}}
    <div class="bg-white shadow-lg p-6 rounded-xl mb-10 w-full max-w-6xl mx-auto">
        <form method="GET" action="{{ route('leaderboard') }}" class="flex flex-wrap items-center gap-4">
            {{-- Lọc theo Thời gian --}}
            <select name="time" onchange="this.form.submit()" class="form-select border-gray-300 rounded-md shadow-sm">
                <option value="all" {{ $currentFilters['time'] == 'all' ? 'selected' : '' }}>Tất cả thời gian</option>
                <option value="month" {{ $currentFilters['time'] == 'month' ? 'selected' : '' }}>Tháng này</option>
                <option value="week" {{ $currentFilters['time'] == 'week' ? 'selected' : '' }}>Tuần này</option>
            </select>

            {{-- Lọc theo Category --}}
            <select name="category" onchange="this.form.submit()" class="form-select border-gray-300 rounded-md shadow-sm">
                <option value="">Tất cả Thể loại</option>
                @foreach($allCategories as $category)
                    <option value="{{ $category->id }}" {{ $currentFilters['category'] == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            
            {{-- Tìm kiếm Người chơi --}}
            <div class="relative flex-grow">
                <input type="text" name="search" placeholder="Tìm kiếm người chơi..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 960 960" width="20px" fill="currentColor"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Phần 1: PODIUM (Top 3) --}}
    @if($top3->count() > 0)
    <div class="flex justify-center items-end h-80 w-full max-w-4xl mx-auto mb-12">
        
        {{-- Hạng 2 --}}
        @if($top3->count() > 1)
        @include('partials.podium-slot', ['rank' => 2, 'user' => $top3[1], 'height' => 'h-56', 'order' => 'order-1', 'bg' => 'bg-gray-300'])
        @endif
        
        {{-- Hạng 1 --}}
        @if($top3->count() > 0)
        @include('partials.podium-slot', ['rank' => 1, 'user' => $top3[0], 'height' => 'h-72', 'order' => 'order-2', 'bg' => 'bg-yellow-400/80'])
        @endif

        {{-- Hạng 3 --}}
        @if($top3->count() > 2)
        @include('partials.podium-slot', ['rank' => 3, 'user' => $top3[2], 'height' => 'h-40', 'order' => 'order-3', 'bg' => 'bg-yellow-600/60'])
        @endif
    </div>
    @endif
    
    {{-- Phần 2: Bảng Xếp Hạng (Từ hạng 4 trở đi) --}}
    @if($restOfBoard->count() > 0)
    <div class="shadow-lg overflow-hidden border border-gray-200 sm:rounded-lg w-full max-w-6xl mx-auto bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hạng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người chơi</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tổng Điểm</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tỷ lệ Đúng (%)</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Quiz Hoàn thành</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($restOfBoard as $index => $user)
                    <tr class="{{ $user->id == $currentUserId ? 'bg-green-100 font-bold' : 'hover:bg-gray-50' }}">
                        {{-- Hạng + 4 vì đã bỏ qua Top 3 --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">{{ $index + 4 }}</td> 
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center">
                            {{-- Giả định User có avatar --}}
                            <img src="{{ asset($user->avatar ?? 'images/default-avatar.png') }}" class="w-8 h-8 rounded-full mr-3 border-2 border-green-500" alt="Avatar">
                            {{ $user->name }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-green-600">{{ number_format($user->total_score) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $user->accuracy }}%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $user->quizzes_completed }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
</body>
</html>

{{-- Bạn cần tạo thêm một file Blade phụ: resources/views/partials/podium-slot.blade.php --}}
{{-- File này chứa UI cho từng vị trí Top 3 --}}
{{-- Logic animation cho người mới vào Top 3 sẽ cần Livewire/Echo và JavaScript để kích hoạt CSS class như 'animate-pulse' --}}