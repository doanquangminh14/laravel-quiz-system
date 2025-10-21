<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate</title>
    @vite('resources/css/app.css')
</head>
<body class="pt-10 text-center">

    <div class="w-full max-w-4xl certificate-border bg-white shadow-2xl p-12 sm:p-20 text-center mt-4 mb-20 rounded-lg">
        <h1 class="text-6xl font-serif font-extrabold text-green-700 mb-8 flex items-center justify-center space-x-4 tracking-wider">
            <svg xmlns="http://www.w3.org/2000/svg" height="60px" viewBox="0 -960 960 960" width="60px" fill="#047857" class="self-start"><path d="m385-412 36-115-95-74h116l38-119 37 119h117l-95 74 35 115-94-71-95 71ZM244-40v-304q-45-47-64.5-103T160-560q0-136 92-228t228-92q136 0 228 92t92 228q0 57-19.5 113T716-344v304l-236-79-236 79Zm236-260q109 0 184.5-75.5T740-560q0-109-75.5-184.5T480-820q-109 0-184.5 75.5T220-560q0 109 75.5 184.5T480-300ZM304-124l176-55 176 55v-171q-40 29-86 42t-90 13q-44 0-90-13t-86-42v171Zm176-86Z"/></svg>
            <span>CHỨNG CHỈ HOÀN THÀNH</span>
        </h1>
        <p class="text-3xl mt-10 text-gray-700">Được trao cho</p>
        <h2 class="text-6xl font-serif font-bold text-indigo-800 my-4 border-b-2 border-indigo-200 inline-block px-8 py-2">
            {{ $data['name'] }}
        </h2>
        <p class="text-3xl mt-6 text-gray-700">đã hoàn thành xuất sắc bài kiểm tra</p>  
        <h3 class="text-4xl font-semibold text-gray-800 mt-4 tracking-wide">
            "{{ $data['quiz'] }}"
        </h3>
        <p class="text-xl mt-12 text-gray-500 italic">Ngày cấp: {{ date('Y-m-d') }}</p>

    </div>
</body>
</html>