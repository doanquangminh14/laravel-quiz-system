<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Xếp Hạng Thành tích</title>
    @vite('resources/css/app.css') 
    {{-- Đảm bảo bạn có thư viện Carbon để hiển thị thời gian --}}
    @php use Carbon\Carbon; @endphp
    <style>
        /* Tùy chỉnh nhỏ để căn giữa Podium */
        .podium-slot {
            transition: all 0.3s ease-in-out;
            min-height: 150px; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <x-user-navbar></x-user-navbar>

<div class="container mx-auto p-4 bg-gray-100 min-h-screen">
    <h1 class="text-4xl font-extrabold text-center text-green-700 pt-8 pb-4">
        🏆 BẢNG XẾP HẠNG TỔNG THỂ
    </h1>
    {{-- Đảm bảo $currentFilters đã được truyền từ Controller --}}
    <p class="text-center text-gray-600 mb-8">Thời gian: {{ ucfirst($currentFilters['time']) }} | Xếp hạng theo Tổng điểm</p>

    {{-- BỘ LỌC VÀ TÌM KIẾM --}}
    <div class="bg-white shadow-xl p-6 rounded-xl mb-10 w-full max-w-6xl mx-auto border-t-4 border-green-500">
        <form method="GET" action="{{ route('leaderboard') }}" class="flex flex-wrap items-center gap-4">
            
            {{-- Lọc theo Thời gian --}}
            <select name="time" onchange="this.form.submit()" class="form-select border-gray-300 rounded-lg shadow-sm focus:border-green-500 p-2">
                <option value="all" {{ $currentFilters['time'] == 'all' ? 'selected' : '' }}>Tất cả thời gian</option>
                <option value="month" {{ $currentFilters['time'] == 'month' ? 'selected' : '' }}>Tháng này</option>
                <option value="week" {{ $currentFilters['time'] == 'week' ? 'selected' : '' }}>Tuần này</option>
            </select>

            {{-- LỌC THEO THỂ LOẠI (Chuyển hướng đến Route khác) --}}
            <select onchange="if(this.value) { window.location.href = this.value }" 
                    class="form-select border-gray-300 rounded-lg shadow-sm focus:border-green-500 p-2">
                <option value="{{ route('leaderboard') }}" selected>TỔNG THỂ</option>
                @foreach($allCategories as $category)
                    @php
                        $categoryUrl = route('leaderboard.category', [
                            'categoryId' => $category->id, 
                            'categoryName' => str_replace(' ','-',$category->name)
                        ]);
                    @endphp
                    <option value="{{ $categoryUrl }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            
            {{-- Tìm kiếm Người chơi --}}
            <div class="relative flex-grow min-w-xs">
                <input type="text" name="search" placeholder="Tìm kiếm người chơi..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 960 960" width="20px" fill="currentColor"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                </button>
            </div>
        </form>
    </div>

    {{-- PHẦN TOP 3 (PODIUM) --}}
    {{-- Chỉ hiển thị Podium nếu có người chơi --}}
    @if($leaderboardUsers->count() > 0)
    <div class="flex justify-center items-end h-80 w-full max-w-4xl mx-auto mb-12">
        
        {{-- Hạng 2 --}}
        @if(isset($top3[1]))
        {{-- Bạn cần tạo partials.podium-slot.blade.php cho slot này --}}
        @include('partials.podium-slot', ['rank' => 2, 'user' => $top3[1], 'height' => 'h-56', 'order' => 'order-1', 'bg' => 'bg-gray-400/80', 'currentUserId' => $currentUserId])
        @endif
        
        {{-- Hạng 1 --}}
        @if(isset($top3[0]))
        @include('partials.podium-slot', ['rank' => 1, 'user' => $top3[0], 'height' => 'h-72', 'order' => 'order-2', 'bg' => 'bg-yellow-500', 'currentUserId' => $currentUserId])
        @endif

        {{-- Hạng 3 --}}
        @if(isset($top3[2]))
        @include('partials.podium-slot', ['rank' => 3, 'user' => $top3[2], 'height' => 'h-40', 'order' => 'order-3', 'bg' => 'bg-yellow-700/60', 'currentUserId' => $currentUserId])
        @endif
    </div>
    @endif
    
    {{-- PHẦN BẢNG XẾP HẠNG CHI TIẾT (Từ hạng 4 trở đi) --}}
    <div class="w-full max-w-6xl mx-auto">
        {{-- SỬ DỤNG $leaderboardUsers ĐỂ KIỂM TRA TÌNH TRẠNG EMPTY --}}
        @if($leaderboardUsers->isEmpty()) 
            <div class="p-6 text-center text-gray-500 bg-white shadow rounded-lg">
                <p>Không tìm thấy người chơi nào đạt điểm trong điều kiện lọc hiện tại.</p>
            </div>
        @else
            <div class="shadow-lg overflow-x-auto border border-gray-200 sm:rounded-lg bg-white">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hạng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người chơi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Điểm cao nhất</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tổng Điểm</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tỷ lệ Đúng (%)</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Quiz Hoàn thành</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Lần cuối làm</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($restOfBoard as $index => $user)
                            @php
                                // Tính toán thứ hạng thực tế: Số người Top 3 + index hiện tại + 1
                                $rank = $top3->count() + $index + 1; 
                            @endphp

                            {{-- Highlight user hiện tại (Auth::user()) --}}
                            <tr class="{{ $user->id == $currentUserId ? 'bg-yellow-100 font-bold border-l-4 border-red-500' : 'hover:bg-gray-50' }}">
                                
                                {{-- Hạng --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $rank }}</td> 
                                
                                {{-- Tên Người chơi --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-semibold">{{ $user->name }}</td>

                                {{-- Điểm cao nhất --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-red-600">{{ number_format($user->highest_score) }}</td>
                                
                                {{-- Tổng Điểm --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-extrabold text-green-700">{{ number_format($user->total_score) }}</td>
                                
                                {{-- Tỷ lệ Đúng --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm {{ $user->accuracy > 70 ? 'text-blue-600' : 'text-orange-500' }}">
                                    {{ $user->accuracy }}%
                                </td>
                                
                                {{-- Quiz Hoàn thành --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">{{ $user->quizzes_attempted }}</td>
                                
                                {{-- Lần cuối làm --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                                    {{ Carbon::parse($user->last_attempted)->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- LƯU Ý QUAN TRỌNG: Bạn cần tạo thêm một file Blade phụ: resources/views/partials/podium-slot.blade.php 
để hiển thị Top 3 một cách đẹp mắt. Dưới đây là nội dung mẫu:

<div class="podium-slot flex flex-col justify-end items-center mx-2 w-1/3 {{ $order }}">
    <div class="text-xl font-bold mb-2 p-2 rounded-full {{ $bg === 'bg-yellow-500' ? 'text-black' : 'text-white' }}">
        #{{ $rank }}
    </div>
    
    <div class="w-24 h-24 rounded-full border-4 {{ $user->id == $currentUserId ? 'border-red-500' : 'border-white' }} overflow-hidden mb-2">
        <img src="{{ asset($user->avatar ?? 'images/default-avatar.png') }}" class="w-full h-full object-cover" alt="Avatar">
    </div>

    <p class="text-center font-bold text-gray-800 truncate w-full">{{ $user->name }}</p>
    <p class="text-center text-2xl font-extrabold mt-1 {{ $bg === 'bg-yellow-500' ? 'text-red-700' : 'text-white' }}">{{ number_format($user->total_score) }}</p>
    
    <div class="w-full p-3 rounded-t-lg text-center {{ $bg }} {{ $height }} flex items-end justify-center">
        </div>
</div>
--}}
</body>
</html>