<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Quiz: {{ $quizName }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 font-sans">
    
    <x-navbar name="{{$name}}"></x-navbar>
    
    <div class="flex flex-col min-h-screen items-center pt-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full">

            <div class="bg-white p-6 rounded-xl shadow-lg mb-8 flex justify-between items-center border-l-4 border-blue-500">
                <h2 class="text-2xl font-bold text-gray-800">
                    Chi tiết Quiz: <span class="text-blue-600">{{ $quizName }}</span>
                </h2>
                <a 
                    class="text-blue-600 hover:text-blue-800 font-semibold text-base transition duration-150 flex items-center space-x-1" 
                    href="/add-quiz" 
                    title="Quay lại trang thêm Quiz"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor"><path d="m313-440 224 224q15 15 14.5 35.5T536-150q-15 15-35 15t-35-15L210-480q-6-6-11-13t-5-14q0-7 5-14t11-13l256-256q15-15 35.5-14.5T536-790q15 15 15 35t-15 35L313-520h427q25 0 42.5 17.5T780-440q0 25-17.5 42.5T720-380H313Z"/></svg>
                    <span>Quay Lại</span>
                </a>
            </div>
            
            <div class="bg-white shadow-xl rounded-xl overflow-hidden ring-1 ring-gray-200">
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        
                        <thead class="bg-blue-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Nội Dung Câu Hỏi
                                </th>
                            </tr>
                        </thead>
                        
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($mcqs as $mcq)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-blue-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $mcq->id }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                    {{ $mcq->question }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-10 text-gray-500 text-lg">
                                    Quiz này chưa có câu hỏi nào.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>