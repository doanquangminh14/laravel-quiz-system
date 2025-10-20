{{-- resources/views/partials/podium-slot.blade.php --}}

<div class="flex flex-col items-center mx-4 transition-all duration-300 {{ $order }}">
    
    {{-- PHẦN 1: HUY HIỆU/RANK (THAY THẾ VỊ TRÍ AVATAR) --}}
    <div class="relative mb-2 flex items-center justify-center w-20 h-20">
        
        {{-- Nút Huy hiệu (Vương miện/Cúp) --}}
        <span class="text-6xl transform rotate-3 
                     {{ $rank == 1 ? 'text-yellow-500 animate-bounce-slow' : 
                        ($rank == 2 ? 'text-gray-400' : 'text-yellow-700') }}">
            {{ $rank == 1 ? '👑' : '🏆' }}
        </span>

        {{-- Hiển thị Huy hiệu rank nhỏ (1, 2, 3) ngay bên cạnh hoặc dưới --}}
        <div class="absolute bottom-0 right-0 bg-white rounded-full p-1 border-2 font-extrabold text-sm 
                    {{ $rank == 1 ? 'border-yellow-500 text-yellow-500' : 
                       ($rank == 2 ? 'border-gray-400 text-gray-400' : 'border-yellow-700 text-yellow-700') }}">
            #{{ $rank }}
        </div>
    </div>
    
    {{-- PHẦN 2: THÔNG TIN USER --}}
    <p class="font-bold text-xl text-gray-800">{{ $user->name }}</p>
    <p class="text-sm text-gray-600 mb-2">{{ number_format($user->total_score) }} điểm</p>
    
    {{-- PHẦN 3: BASE PODIUM (Giữ nguyên cấu trúc Podium) --}}
    <div class="w-full h-full min-h-12 flex items-center justify-center {{ $height }} {{ $bg }} rounded-t-lg shadow-xl p-2 transition-all duration-500">
        <span class="text-white text-xl font-extrabold">Hạng {{ $rank }}</span>
    </div>
</div>