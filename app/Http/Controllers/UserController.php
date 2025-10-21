<?php

namespace App\Http\Controllers;

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

class UserController extends Controller{
    function welcome(){
       $categories=Category::withCount('quizzes')->orderBy('quizzes_count','desc')->take(5)->get();
        
       $quizData=Quiz::withCount('Records')->orderBy('records_count','desc')->take(5)->get();
        return view('welcome',['categories'=>$categories,'quizData'=>$quizData]);
    }

    function categories(){

        $categories=Category::withCount('quizzes')->orderBy('quizzes_count','desc')->paginate(4);
        return view('categories-list',['categories'=>$categories]);
      }

    function userQuizList($id,$category){
     
        $quizData=Quiz::withCount('Mcq')->where('category_id',$id)->get();
           return view('user-quiz-list',["quizData"=>$quizData,'category'=>$category]);
      
    }


function startQuiz($id,$name){

        $quizCount =Mcq::where('quiz_id',$id)->count();
        $mcqs =Mcq::where('quiz_id',$id)->get();
        Session::put('firstMCQ',$mcqs[0]);
        $quizName =$name;
        return view('start-quiz',['quizName'=>$quizName,'quizCount'=>$quizCount]);

    }


        function userSignup(Request $request){
      $validate = $request->validate([
        'name'=>'required | min:3',
        'email'=>'required | email | unique:users',
        'password'=>'required | min:3 | confirmed',
      ]);
      $user = User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password),
      ]);

      // 
      
       $link = Crypt::encryptString($user->email);
       $link = url('/verify-user/'.$link);
      

      // 

      if($user){
        Session::put('user',$user);
        if(Session::has('quiz-url')){
         
          $url=Session::get('quiz-url');
          Session::forget('quiz-url');
          return redirect($url)->with('message-success',"User registered successfully, Please check email to verify account ");
        }else{
          return redirect('/')->with('message-success',"User registered successfully, Please check email to verify account ");
        }
        
        
      }
      
}
function userLogout(){
      Session::forget('user');
      return redirect('/');
    }

    function userSignupQuiz(){
     Session::put('quiz-url',url()->previous());
      return view('user-signup');
    }



    function userLogin(Request $request){
      $validate = $request->validate([
        'email'=>'required | email',
        'password'=>'required',
      ]);

     $user= User::where('email',$request->email)->first();
     if(!$user || !Hash::check($request->password,$user->password)){
      return redirect('user-login')->with('message-error',"User not valid, Please check email and password again");
     }

      if($user){
        Session::put('user',$user);
        if(Session::has('quiz-url')){
         
          $url=Session::get('quiz-url');
          Session::forget('quiz-url');
          return redirect($url);
        }else{
          return redirect('/');
        }
        
        
      }
      
}


function userLoginQuiz(){
  Session::put('quiz-url',url()->previous());
   return view('user-login');
 }

 function mcq($id,$name){
  $record= new Record();
  $record->user_id=Session::get('user')->id;
  $record->quiz_id=Session::get('firstMCQ')->quiz_id;
  $record->status=1;
  if($record->save()){
    $currentQuiz=[];
    $currentQuiz['totalMcq']=MCQ::where('quiz_id',Session::get('firstMCQ')->quiz_id)->count();
    $currentQuiz['currentMcq']=1;
    $currentQuiz['quizName']=$name;
    $currentQuiz['quizId']=Session::get('firstMCQ')->quiz_id;
    $currentQuiz['recordId']=$record->id;

    Session::put('currentQuiz',$currentQuiz);
    $mcqData=MCQ::find($id);
   return view('mcq-page',['quizName'=>$name,'mcqData'=>$mcqData]); 
  }else{
    return "Something went wrong";
  }
 }

function submitAndNext(Request $request, $id){
    $currentQuiz= Session::get('currentQuiz');
    $currentQuiz['currentMcq']+=1;
    
    // 1. Tìm câu hỏi tiếp theo
    $mcqData = MCQ::where([
        ['id','>',$id],
        ['quiz_id','=',$currentQuiz['quizId']]
    ])->first();

    // 2. Kiểm tra và Lưu MCQ_Record (Đã tồn tại)
    $isExist = MCQ_Record::where([
        ['record_id','=',$currentQuiz['recordId']],
        ['mcq_id','=',$request->id],
    ])->count();
    
    if($isExist < 1){
        $mcq_record = new MCQ_Record;
        $mcq_record->record_id = $currentQuiz['recordId'];
        $mcq_record->user_id = Session::get('user')->id;
        $mcq_record->mcq_id = $request->id;
        $mcq_record->select_answer = $request->option;
        
        if($request->option == MCQ::find($request->id)->correct_ans){
            $mcq_record->is_correct = 1;
        } else {
            $mcq_record->is_correct = 0;
        }
    
        if(!$mcq_record->save()){
            return "something went wrong";
        }
    }
    
    // 3. Cập nhật Session
    Session::put('currentQuiz',$currentQuiz);

    // 4. Quyết định: Chuyển tiếp hay Kết thúc Quiz
    if($mcqData){
        // Hiển thị câu hỏi tiếp theo
        return view('mcq-page',['quizName'=>$currentQuiz['quizName'],'mcqData'=>$mcqData]);
    }else{
        // ------------- LOGIC KẾT THÚC QUIZ (FIX LỖI 0 ĐIỂM) -------------

        // Tính tổng điểm (Đếm số câu trả lời đúng)
        $correctAnswers = MCQ_Record::where([
            ['record_id','=',$currentQuiz['recordId']],
            ['is_correct','=',1],
        ])->count();

        // Lấy Record chính
        $record = Record::find($currentQuiz['recordId']);
        
        if($record){
            // Gán điểm thực tế vào cột 'score' (Đây là FIX QUAN TRỌNG NHẤT)
            $record->score = $correctAnswers; 
            
            // Cập nhật trạng thái hoàn thành
            $record->status = 2; 
            
            // Lưu cả score và status vào database
            $record->update(); 
            
            // Nếu Observer đã được đăng ký, nó sẽ tự động kích hoạt tại đây 
            // để cập nhật cột total_score trong bảng users.
        }

        // Lấy dữ liệu chi tiết kết quả (nếu cần cho trang result)
        $resultData = MCQ_Record::WithMCQ()->where('record_id',$currentQuiz['recordId'])->get();

        // Hiển thị trang kết quả
        return view('quiz-result',['resultData'=>$resultData,'correctAnswers'=>$correctAnswers]);
    }
}

  function userDetails(){
   $quizRecord = Record::WithQuiz()->where('user_id',Session::get('user')->id)->get();
  return view('user-details',['quizRecord'=>$quizRecord]);
 }

 function searchQuiz(Request $request){
  $quizData = Quiz::withCount('Mcq')->where('name','Like','%'.$request->search.'%')->get();
  return view('quiz-search',['quizData'=>$quizData,'quiz'=>$request->search]);
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

  function certificate(){
  $data=[];

  $data['quiz']= str_replace('-',' ',Session::get('currentQuiz')['quizName']);
  $data['name']= Session::get('user')['name'];
  return  view('certificate',['data'=>$data]);
 }

 function downloadCertificate(){
  $data=[];
  $data['quiz']= str_replace('-',' ',Session::get('currentQuiz')['quizName']);
  $data['name']= Session::get('user')['name'];
  $html=  view('download-certificate',['data'=>$data])->render();
  return response(
    Browsershot::html($html)->pdf()
  )->withHeaders(
    [
      'Content-Type'=>"application/pdf",
      'Content-disposition'=>"attachment;filename=certificate.pdf"
    ]
    );
  
 }
function leaderboard(Request $request)
    {
        // 1. Lấy thông tin lọc từ Request
        $timeFilter = $request->get('time', 'all'); // 'all', 'week', 'month'
        $categoryId = $request->get('category'); // ID Category
        $searchName = $request->get('search'); // Tìm kiếm người chơi

        // 2. Thiết lập bộ lọc thời gian
        $startDate = null;
        if ($timeFilter === 'week') {
            $startDate = Carbon::now()->startOfWeek();
        } elseif ($timeFilter === 'month') {
            $startDate = Carbon::now()->startOfMonth();
        }

        // 3. Xây dựng Truy vấn Cơ sở (Aggregation)
        $query = Record::select('user_id')
            ->selectRaw('SUM(score) as total_score')
            ->selectRaw('COUNT(id) as quizzes_completed')
            ->selectRaw('SUM(correct_answers) as total_correct_answers')
            ->selectRaw('SUM(total_questions) as total_total_questions')
            ->where('status', 2); // Đã hoàn thành

        // Lọc theo thời gian
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        // Lọc theo Category
        if ($categoryId) {
            $query->whereHas('quiz', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        // Nhóm và sắp xếp
        $leaderboardData = $query->groupBy('user_id')
            ->orderByDesc('total_score')
            ->get();
            
        // 4. Lấy thông tin User (sử dụng User Model để hiển thị)
        $leaderboardUsers = User::whereIn('id', $leaderboardData->pluck('user_id'))
            ->with(['records' => function($q) use ($startDate, $categoryId) {
                // Tải trước các Record đã lọc (nếu cần)
            }])
            ->get()
            ->map(function ($user) use ($leaderboardData) {
                // Gắn dữ liệu thống kê vào đối tượng User
                $stats = $leaderboardData->where('user_id', $user->id)->first();
                $user->total_score = $stats->total_score;
                $user->quizzes_completed = $stats->quizzes_completed;
                
                // Tính toán tỷ lệ chính xác (%)
                if ($stats->total_total_questions > 0) {
                    $user->accuracy = round(($stats->total_correct_answers / $stats->total_total_questions) * 100, 1);
                } else {
                    $user->accuracy = 0;
                }
                
                // Giả định bạn đã có cột streak trong Model User
                // $user->streak = $user->current_streak; 
                
                return $user;
            })
            // Sắp xếp lại sau khi map (rất quan trọng)
            ->sortByDesc('total_score')
            ->values(); // Reset keys

        // 5. Áp dụng tìm kiếm người chơi (trên tập dữ liệu đã có)
        if ($searchName) {
            $leaderboardUsers = $leaderboardUsers->filter(function ($user) use ($searchName) {
                return str_contains(strtolower($user->name), strtolower($searchName));
            })->values();
        }

        // Lấy User hiện tại (để so sánh điểm)
        $currentUser = Session::get('user');
        
        // 6. Phân tách Top 3
        $top3 = $leaderboardUsers->take(3);
        $restOfBoard = $leaderboardUsers->skip(3)->take(17); // Lấy 17 người tiếp theo (Top 20)

        return view('leaderboard', [
            'top3' => $top3,
            'restOfBoard' => $restOfBoard,
            'allCategories' => Category::all(),
            'currentUserId' => $currentUser ? $currentUser->id : null,
            'currentFilters' => ['time' => $timeFilter, 'category' => $categoryId],
        ]);
    }
}
