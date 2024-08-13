<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Exception\UnableToBuildUuidException;

class InstructorController extends Controller
{
    //
    public function InstructorDashboard(){
        return view('instructor.index');
    }

    public function InstructorLogout (Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'info'
        );

        return redirect('/instructor/login')->with($notification);
    }

    public function InstructorLogin(){
        return view('instructor.instructor_login');
    }

    public function InstructorProfile(){
        $id = Auth::user()->id;
        $profileInfo = User::find($id);
        return view('instructor.instructor_profile_view',compact('profileInfo'));
    }

    public function InstructorProfileStore(Request $request){
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
            @unlink(public_path('upload/instructor_images/'.$user->photo));

            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/instructor_images'), $filename);
            $user['photo'] = $filename;
        }
        $user->save();
        $notification = array(
            'message' => 'Instructor Profile Updated Successfully',
            'alert-type' => 'success',
        );
        return redirect()->back()->with($notification);
    }

    public function InstructorChangePassword(){
        $id = Auth::user()->id;
        $profileInfo = User::find($id);

        return view('instructor.instructor_change_password',compact('profileInfo'));
    }

    public function InstructorPasswordUpdate(Request $request) {
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
}
