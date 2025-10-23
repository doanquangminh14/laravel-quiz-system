<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Quiz;
use App\Models\Mcq;
use App\Models\User;
use App\Models\Record;
use App\Models\MCQ_Record;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class UserController extends Controller{
    function welcome()
    {
        $categories = Category::withCount('quizzes')
            ->orderBy('quizzes_count', 'desc')
            ->take(5)
            ->get();

        $quizData = Quiz::withCount('Records')
            ->orderBy('records_count', 'desc')
            ->take(5)
            ->get();

        return view('welcome', ['categories' => $categories, 'quizData' => $quizData]);
    }

    function categories()
    {
        $categories = Category::withCount('quizzes')
            ->orderBy('quizzes_count', 'desc')
            ->paginate(4);
        return view('categories-list', ['categories' => $categories]);
    }

    function userQuizList($id, $category)
    {
        $quizData = Quiz::withCount('Mcq')->where('category_id', $id)->get();
        return view('user-quiz-list', ["quizData" => $quizData, 'category' => $category]);
    }


function startQuiz($id, $name)
    {
        $quizCount = Mcq::where('quiz_id', $id)->count();
        $mcqs = Mcq::where('quiz_id', $id)->get();

        Session::put(['firstMCQ' => $mcqs[0]]);
        $quizName = $name;

        return view('start-quiz', ['quizName' => $quizName, 'quizCount' => $quizCount]);
    }


      //   function userSignup(Request $request){
      // $validate = $request->validate([
      //   'name'=>'required | min:3',
      //   'email'=>'required | email | unique:users',
      //   'password'=>'required | min:3 | confirmed',
      // ]);
      // $user = User::create([
      //   'name'=>$request->name,
      //   'email'=>$request->email,
      //   'password'=>Hash::make($request->password),
      // ]);

      // 
      
      //  $link = Crypt::encryptString($user->email);
      //  $link = url('/verify-user/'.$link);
      

      // 

//       if($user){
//         Session::put('user',$user);
//         if(Session::has('quiz-url')){
         
//           $url=Session::get('quiz-url');
//           Session::forget('quiz-url');
//           return redirect($url)->with('message-success',"User registered successfully, Please check email to verify account ");
//         }else{
//           return redirect('/')->with('message-success',"User registered successfully, Please check email to verify account ");
//         }
        
        
//       }
      
// }
// function userLogout(){
//       Session::forget('user');
//       return redirect('/');
//     }





//     function userLogin(Request $request){
//       $validate = $request->validate([
//         'email'=>'required | email',
//         'password'=>'required',
//       ]);

//      $user= User::where('email',$request->email)->first();
//      if(!$user || !Hash::check($request->password,$user->password)){
//       return redirect('user-login')->with('message-error',"User not valid, Please check email and password again");
//      }

//       if($user){
//         Session::put('user',$user);
//         if(Session::has('quiz-url')){
         
//           $url=Session::get('quiz-url');
//           Session::forget('quiz-url');
//           return redirect($url);
//         }else{
//           return redirect('/');
//         }
        
        
//       }
      
// }

function userLoginQuiz(){
    Session::put('quiz-url', url()->previous());
    return redirect()->route('login');
}

function userSignupQuiz(){
    Session::put('quiz-url', url()->previous());
    return redirect()->route('register');
}
//     function userSignupQuiz(){
//      Session::put('quiz-url',url()->previous());
//       return view('user-signup');
//     }

// function userLoginQuiz(){
//   Session::put('quiz-url',url()->previous());
//    return view('user-login');
//  }

 function mcq($id, $name)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login')->with('message-error', 'Vui lòng đăng nhập để tham gia quiz.');
        }

        $record = new Record();
        $record->user_id = $user->id;
        $record->quiz_id = session('firstMCQ')->quiz_id;
        $record->status = 1;

        if ($record->save()) {
            $currentQuiz = [
                'totalMcq' => Mcq::where('quiz_id', session('firstMCQ')->quiz_id)->count(),
                'currentMcq' => 1,
                'quizName' => $name,
                'quizId' => session('firstMCQ')->quiz_id,
                'recordId' => $record->id
            ];

            session(['currentQuiz' => $currentQuiz]);

            $mcqData = Mcq::find($id);
            return view('mcq-page', ['quizName' => $name, 'mcqData' => $mcqData]);
        } else {
            return "Something went wrong";
        }
      }

function submitAndNext(Request $request, $id)
    {
        $currentQuiz = session('currentQuiz');
        $currentQuiz['currentMcq'] += 1;

        // 🔹 Kiểm tra người dùng có chọn đáp án hay chưa
    if (!$request->has('option')) {
        return back()->with('error', 'Vui lòng chọn đáp án trước khi tiếp tục.');
    }

        $mcqData = Mcq::where([
            ['id', '>', $id],
            ['quiz_id', '=', $currentQuiz['quizId']]
        ])->first();

        $user = Auth::user();

        // Lưu kết quả người dùng
        $isExist = MCQ_Record::where([
            ['record_id', '=', $currentQuiz['recordId']],
            ['mcq_id', '=', $request->id],
        ])->count();

        if ($isExist < 1) {
            $mcq_record = new MCQ_Record;
            $mcq_record->record_id = $currentQuiz['recordId'];
            $mcq_record->user_id = $user->id;
            $mcq_record->mcq_id = $request->id;
            $mcq_record->select_answer = $request->option;

            $mcq_record->is_correct = ($request->option == Mcq::find($request->id)->correct_ans) ? 1 : 0;
            $mcq_record->save();
        }

        session(['currentQuiz' => $currentQuiz]);

        // Nếu còn câu hỏi tiếp
        if ($mcqData) {
            return view('mcq-page', ['quizName' => $currentQuiz['quizName'], 'mcqData' => $mcqData]);
        }

        // Tính điểm và lưu kết quả
        $correctAnswers = MCQ_Record::where([
            ['record_id', '=', $currentQuiz['recordId']],
            ['is_correct', '=', 1],
        ])->count();

        $record = Record::find($currentQuiz['recordId']);
        if ($record) {
            $record->score = $correctAnswers;
            $record->status = 2;
            $record->update();
        }

        $resultData = MCQ_Record::WithMCQ()->where('record_id', $currentQuiz['recordId'])->get();

        return view('quiz-result', ['resultData' => $resultData, 'correctAnswers' => $correctAnswers]);
    }

  function userDetails()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login')->with('message-error', 'Vui lòng đăng nhập để xem lịch sử quiz.');
        }

        $quizRecord = Record::WithQuiz()->where('user_id', $user->id)->get();
        return view('user-details', ['quizRecord' => $quizRecord]);
    }


 function searchQuiz(Request $request)
    {
        $quizData = Quiz::withCount('Mcq')->where('name', 'Like', '%' . $request->search . '%')->get();
        return view('quiz-search', ['quizData' => $quizData, 'quiz' => $request->search]);
    }


 function verifyUser($email){
 echo $orgEmail = Crypt::decryptString($email);
 $user= User::where('email',$orgEmail)->first();
 if($user){
  $user->active=2;

  if($user->save())
  {
    return redirect('/')->with('message-success',"User verified successfully");

  }
 }

 }

  function certificate()
    {
        $user = Auth::user();
        $data = [
            'quiz' => str_replace('-', ' ', session('currentQuiz')['quizName']),
            'name' => $user->name
        ];
        return view('certificate', ['data' => $data]);
    }

    // Tải file PDF certificate
    function downloadCertificate()
    {
        $user = Auth::user();
        $data = [
            'quiz' => str_replace('-', ' ', session('currentQuiz')['quizName']),
            'name' => $user->name
        ];
        $html = view('download-certificate', ['data' => $data])->render();

        return response(Browsershot::html($html)->pdf())
            ->withHeaders([
                'Content-Type' => "application/pdf",
                'Content-disposition' => "attachment;filename=certificate.pdf"
            ]);
    }



public function leaderboard(Request $request)
{
    $search = $request->input('search');
    $categoryId = $request->input('category');

    // 🧠 1. Tạo query thống kê
    $query = Record::select('records.user_id')
        ->leftJoin('quizzes', 'records.quiz_id', '=', 'quizzes.id')

        ->selectRaw('SUM(records.score) as total_score')
        ->selectRaw('MAX(records.score) as highest_score')
        ->selectRaw('COUNT(DISTINCT records.quiz_id) as quizzes_attempted')
        ->selectRaw('MAX(records.created_at) as last_attempted')
        ->groupBy('records.user_id')
        ->orderByDesc('total_score')
        ->orderByDesc('highest_score');

    // 🧩 2. Nếu có chọn chủ đề thì lọc theo category_id
    if ($categoryId) {
        $query->where('quizzes.category_id', $categoryId);
    }

    $leaderboard = $query->get();

    // 🔗 3. Gắn thông tin user tương ứng
    $userIds = $leaderboard->pluck('user_id');
    $users = User::whereIn('id', $userIds)->get()->keyBy('id');

    $leaderboard = $leaderboard->map(function ($item) use ($users) {
        $user = $users->get($item->user_id);
        if (!$user) return null;
        $item->name = $user->name;
        return $item;
    })->filter()->values();

    // 🔍 4. Lọc theo tên nếu có
    if ($search) {
        $leaderboard = $leaderboard->filter(function ($u) use ($search) {
            return str_contains(strtolower($u->name), strtolower($search));
        })->values();
    }

    // 📚 5. Lấy danh sách chủ đề
    $categories = Category::all();

    // 👑 6. Trả về view
    return view('leaderboard', [
        'leaderboard' => $leaderboard,
        'categories' => $categories,
        'selectedCategory' => $categoryId,
        'search' => $search,
    ]);
}

public function quizListUser($id, $category)
{
    // Lấy thông tin danh mục
    $categoryData = Category::findOrFail($id);

    // Lấy danh sách quiz theo danh mục
    $quizzes = Quiz::where('category_id', $id)->get();

    // Trả về view và truyền dữ liệu
    return view('quiz-list-user', compact('categoryData', 'quizzes'));
}


}
