<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Quiz - {{ $categoryData->name }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans">
    <x-user-navbar></x-user-navbar> 
    <div class="max-w-6xl mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold text-gray-700 text-center mb-6 border-b pb-2 border-green-200">
            📘 Danh sách Quiz thuộc danh mục: {{ $categoryData->name }}
        </h1>

        @if($quizzes->count() > 0)
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($quizzes as $quiz)
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl hover:-translate-y-1 transform transition duration-200">
                        <h2 class="text-xl font-semibold text-gray-800 mb-3">{{ $quiz->name }}</h2>
                        <p class="text-gray-600 text-sm mb-4">
                            Cấp độ: {{ ucfirst($quiz->level ?? 'Trung bình') }}
                        </p>
                        <a href="{{ route('mcq', ['id' => $quiz->id, 'name' => Str::slug($quiz->name)]) }}"
                           class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition duration-150 shadow-md font-semibold">
                            Bắt đầu Quiz
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500 text-lg mt-10">😢 Chưa có quiz nào trong danh mục này.</p>
        @endif
    </div>
</body>
</html>
