<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Quiz;

class UserController extends Controller{
    function welcome(){
       $categories=Category::withCount('quizzes')->orderBy('quizzes_count','desc')->take(5)->get();
        
       $quizData=Quiz::withCount('Records')->orderBy('records_count','desc')->take(5)->get();
        return view('welcome',['categories'=>$categories,'quizData'=>$quizData]);
    }

    function userQuizList($id,$category){
     
        $quizData=Quiz::withCount('Mcq')->where('category_id',$id)->get();
           return view('user-quiz-list',["quizData"=>$quizData,'category'=>$category]);
      
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
      Mail::to($user->email)->send(new VerifyUser($link));

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
}