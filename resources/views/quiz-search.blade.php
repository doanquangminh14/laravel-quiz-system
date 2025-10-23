<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm: {{ $quiz }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 font-sans">
    
    <x-user-navbar></x-user-navbar>
    
    <div class="flex flex-col min-h-screen items-center pt-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full">

            <h2 class="text-3xl font-bold text-gray-800 text-center mb-8 border-b pb-3 border-green-200">
                Kết quả tìm kiếm cho: "<span class="text-green-600">{{ $quiz }}</span>"
            </h2>
            
            <div class="bg-white shadow-xl rounded-xl overflow-hidden ring-1 ring-gray-200">
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        
                        <thead class="bg-green-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Tên Quiz
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                    Số Câu Hỏi
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                    Hành Động
                                </th>
                            </tr>
                        </thead>
                        
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($quizData as $item)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-green-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $item->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-800">
                                    {{ $item->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium text-gray-600">
                                    {{ $item->mcq_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <a 
                                        href="/start-quiz/{{ $item->id }}/{{ str_replace(' ','-',$item->name) }}" 
                                        class="bg-green-500 text-white font-semibold py-2 px-4 rounded-full text-xs 
                                               hover:bg-green-600 transition duration-200 shadow-md"
                                    >
                                        Làm Quiz
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-gray-500 text-lg">
                                    Không tìm thấy Quiz nào phù hợp với từ khóa "{{ $quiz }}".
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