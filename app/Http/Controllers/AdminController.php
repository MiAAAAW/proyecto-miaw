<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //
    public function AdminDashboard(){
        return view('admin.index');
    }

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'info'
        );

        return redirect('/admin/login')->with($notification);
    }

    public function AdminLogin() {
        return view('admin.admin_login');
    }

    public function AdminProfile(){
        $id = Auth::user()->id;
        $profileInfo = User::find($id);
        return view('admin.admin_profile_view',compact('profileInfo'));

    }

    public function AdminProfileStore(Request $request){
        $id = Auth::user()->id;
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');

        if($request->file('photo')){
            $file = $request->file('photo');
            //deletes a file from the server.
            @unlink(public_path('upload/admin_images/'.$user->photo));

            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $user['photo'] = $filename;
        }
        $user->save();
        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success',
        );
        return redirect()->back()->with($notification);
    }

    public function AdminChangePassword(){

        $id = Auth::user()->id;
        $profileInfo = User::find($id);

        return view('admin.admin_change_password',compact('profileInfo'));
    }

    public function AdminPasswordUpdate(Request $request) {
        //Validation password
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if(!Hash::check($request->input('old_password'), Auth::user()->password)) {
            $notification = array(
                'message' => 'Old Password does not match!',
                'alert-type' => 'error',
            );
            return back()->with($notification);
        }

        //Update new Password
        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->get('new_password'))
        ]);

        $notification = array(
            'message' => 'Password Change Successfully',
            'alert-type' => 'success',
        );
        return back()->with($notification);
    }

    public function BecomeInstructor()
    {
        return view('frontend.instructor.reg_instructor');
    }

    public function InstructorRegister(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required', 'string', 'unique:users'],
        ]);

        User::insert([
            'name' => $request->get('name'),
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
            'password' => Hash::make($request->get('password')),
            'role' => 'instructor',
            'status' => '0',
        ]);

        $notification = array(
            'message' => 'Instructor Registered Successfully',
            'alert-type' => 'success',
        );
        return redirect()->route('instructor.login')->with($notification);
    }

    public function AllInstructor()
    {
        $allinstructor = User::query()->where('role', '=','instructor')->get();
//        $instructors = User::where('role','instructor')->latest()->get();
        return view('admin.backend.instructor.all_instructor',compact('allinstructor'));
    }

    public function UpdateUserStatus(Request $request)
    {
        $userId = $request->input('user_id');
        $isChecked = $request->input('is_checked',0);

        $user = User::find($userId);
        if ($user) {
            $user->status = $isChecked;
            $user->save();
        }

        return response()->json(['message'=> 'User Status Updated Successfully']);
    }

    public function AdminAllCourse() {
        $course = Course::latest()->get();
        return view("admin.backend.courses.all_course", compact('course'));
    }

    public function UpdateCourseStatus(Request $request)
    {
        $courseId = $request->input('course_id');
        $isChecked = $request->input('is_checked',0);
        $course = Course::find($courseId);
        if($course) {
            $course->status = $isChecked;
            $course->save();
        }

        return response()->json(['message'=>'Course status updated successfully']);
    }

    public function AdminCourseDetails($id){
        $course = Course::find($id);
        return view('admin.backend.courses.course_details',compact('course'));
    }
}
