<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Quiz Page</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans">
    
    <x-navbar name="{{$name}}"></x-navbar>

    <div class="flex flex-col items-center min-h-screen pt-12 px-4 sm:px-6 lg:px-8">

        <div class="bg-white p-8 sm:p-10 rounded-xl shadow-2xl w-full max-w-lg border border-gray-200">
            
            @if(!session('quizDetails'))
            
                <h2 class="text-3xl font-extrabold text-center text-gray-800 mb-8 border-b pb-4 border-blue-100">
                    Tạo Quiz Mới 📝
                </h2>
                
                <form action="/add-quiz" method="get" class="space-y-6">
                    
                    <div>
                        <label for="quiz-name" class="block text-sm font-medium text-gray-700 mb-1">Tên Quiz</label>
                        <input 
                            type="text" 
                            id="quiz-name"
                            placeholder="Nhập tên Quiz (ví dụ: Toán Cao Cấp)" 
                            required 
                            name="quiz"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                        >
                    </div>

                    <div>
                        <label for="category-select" class="block text-sm font-medium text-gray-700 mb-1">Danh Mục</label>
                        <select 
                            id="category-select"
                            name="category_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none transition duration-150"
                        >
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white font-semibold rounded-lg px-4 py-3 hover:bg-blue-700 transition duration-200 shadow-lg"
                    >
                        Tạo Quiz & Bắt đầu thêm câu hỏi
                    </button>
                </form>

            @else
                
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-md shadow-inner">
                    <span class="text-lg font-bold text-green-700 block mb-1">
                        Quiz hiện tại: {{ session('quizDetails')->name }}
                    </span>
                    <p class="text-sm text-gray-600">
                        Tổng số câu hỏi: **{{ $totalMCQs }}**
                        @if($totalMCQs > 0)
                            <a class="text-blue-500 hover:text-blue-700 font-medium ml-3 underline" 
                               href="show-quiz/{{ session('quizDetails')->id }}">
                                Xem các câu hỏi đã thêm
                            </a>
                        @endif
                    </p>
                </div>
                
                <h2 class="text-3xl font-extrabold text-center text-gray-800 mb-8 border-b pb-4 border-blue-100">
                    Thêm Câu Hỏi (MCQ)
                </h2>

                <form action="add-mcq" method="post" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="question-text" class="block text-sm font-medium text-gray-700 mb-1">Câu Hỏi</label>
                        <textarea 
                            id="question-text"
                            placeholder="Nhập nội dung câu hỏi..." 
                            name="question"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 resize-none"
                        ></textarea>
                        @error('question')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="space-y-4">
                        <label class="block text-sm font-bold text-gray-800 pt-2">Các Lựa Chọn:</label>
                        @php $options = ['a', 'b', 'c', 'd']; @endphp
                        @foreach ($options as $option)
                        <div>
                            <input 
                                type="text" 
                                placeholder="Nhập lựa chọn {{ strtoupper($option) }}" 
                                name="{{ $option }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-1 focus:ring-gray-400 focus:border-gray-400"
                            >
                            @error($option)
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        @endforeach
                    </div>

                    <div>
                        <label for="correct-ans-select" class="block text-sm font-medium text-gray-700 mb-1">Đáp Án Đúng</label>
                        <select 
                            id="correct-ans-select"
                            name="correct_ans"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none transition duration-150"
                        >
                            <option value="">-- Chọn Đáp Án Đúng --</option>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                        @error('correct_ans')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="pt-4 space-y-3">
                        <button 
                            type="submit" 
                            name="submit" 
                            value="add-more" 
                            class="w-full bg-blue-600 text-white font-semibold rounded-lg px-4 py-3 hover:bg-blue-700 transition duration-200 shadow-md"
                        >
                            Thêm câu hỏi này & Thêm tiếp
                        </button>
                        
                        <button 
                            type="submit" 
                            name="submit" 
                            value="done" 
                            class="w-full bg-green-600 text-white font-semibold rounded-lg px-4 py-3 hover:bg-green-700 transition duration-200 shadow-md"
                        >
                            Thêm & Hoàn thành Quiz
                        </button>
                        
                        <a 
                            class="w-full bg-red-500 block text-center font-semibold rounded-lg px-4 py-3 text-white hover:bg-red-600 transition duration-200" 
                            href="/end-quiz"
                        >
                            Kết thúc Quiz (Không thêm câu hỏi này)
                        </a>
                    </div>
                </form>
            @endif
        </div>
    </div>
</body>
</html>