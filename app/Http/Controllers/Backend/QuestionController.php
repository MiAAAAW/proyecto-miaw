<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    //
    public function UserQuestion(Request $request)
    {
        $course_id = $request->get('course_id');
        $instructor_id = $request->get('instructor_id');

        Question::insert([
           'course_id' => $course_id,
           'user_id' => Auth::user()->id,
           'instructor_id' => $instructor_id,
            'subject' => $request->get('subject'),
            'question' => $request->get('question'),
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Question Send Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }//End


    public function InstructorAllQuestion(){
        $id = Auth::user()->id;
        $question = Question::where('instructor_id',$id)->where('parent_id', null)->orderBy('id','DESC')->get();
        return view('instructor.question.all_question', compact('question'));
    }

    public function QuestionDetails($id) {
        $question = Question::find($id);
        $replay = Question::where('parent_id',$id)->orderBy('id','asc')->get();
        return view('instructor.question.question_details',compact('question','replay'));
    }

    public function InstructorReplay(Request $request) {
        $que_id = $request->get('qid');
        $user_id = $request->get('user_id');
        $course_id = $request->get('course_id');
        $instructor_id = $request->get('instructor_id');

        Question::insert([
            'course_id' => $course_id,
            'user_id' => $user_id,
            'instructor_id' => $instructor_id,
            'parent_id' => $que_id,
            'question' => $request->question,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Message Send Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('instructor.all.question')->with($notification);
    }

}
