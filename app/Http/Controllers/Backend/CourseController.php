<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Course_goal;
use App\Models\SubCategory;
use App\Models\CourseSection;
use App\Models\CourseLecture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CourseController extends Controller
{
    //
    public function AllCourse()
    {
        $id = Auth::user()->id;
        $courses = Course::query()->where('instructor_id','=',$id)->orderBy('id','desc')->get();
        return view('instructor.courses.all_course',compact('courses'));
    }

    public function AddCourse()
    {
        $categories = Category::latest()->get();

        return view('instructor.courses.add_course', compact('categories'));
    }

    public function GetSubCategory($category_id)
    {
        $subcat = SubCategory::query()->where('category_id',"=",$category_id)->orderBy('subcategory_name','asc')->get();
        return json_encode($subcat);
    }

    public function StoreCourse(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4|max:10000',
        ]);

        //image
        $image = $request->file('course_image');
        // create image manager with desired driver
        $manager = new ImageManager(new Driver());
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        $img = $manager->read($image);
        $img = $img->resize(370, 246);
        $img->toJpeg(80)->save(base_path('public/upload/course/thumbnail/' . $name_gen));
        $save_url = 'upload/course/thumbnail/' . $name_gen;

        //video
        $video = $request->file('video');
        $videoName = time().'.'.$video->getClientOriginalExtension();
        $video->move(public_path('upload/course/video/'),$videoName);
        $save_video = 'upload/course/video/'.$videoName;

        $course_id = Course::insertGetId([
            'category_id' => $request->get('category_id'),
            'subcategory_id' => $request->get('subcategory_id')?? null,
            'instructor_id' => Auth::user()->id,
            'course_title' => $request->get('course_title'),
            'course_name' => $request->get('course_name'),
            'course_name_slug' => strtolower(str_replace(' ','-',$request->get('course_name'))),
            'description' => $request->get('description'),
            'video' => $save_video,
            'label' => $request->get('label') ?? 'N/A', // Default label if not provided
            'duration' => $request->get('duration') ?? 'Unknown', // Default duration
            'resources' => $request->get('resources') ?? 'None', // Default resources
            'certificate' => $request->get('certificate') ?? 'No', // Default certificate availability
            'selling_price' => $request->get('selling_price') ?? 0,
            'discount_price' => $request->get('discount_price') ?? 0,
            'prerequisites' => $request->get('prerequisites') ?? 'None', // Default prerequisites
            'bestseller' => $request->get('bestseller') ?? 'No',
            'featured' => $request->get('featured') ?? 'No',
            'highestrated' => $request->get('highestrated') ?? 'No',
            'status' => 1,
            'course_image' => $save_url,
            'created_at' => Carbon::now(),
        ]);

        ///Course Add goals
        $goals = Count($request->course_goals);
        if($goals != null) {
            for($i=0; $i < $goals;$i++) {
                $gcount = new Course_goal();
                $gcount->course_id = $course_id;
                $gcount->goal_name = $request->course_goals[$i];
                $gcount->save();
            }
        }

        $notification = array(
            'message' => "Course Inserted Successfully",
            'alert-type' => 'success'
        );
        return redirect()->route('all.course')->with($notification);

    }

    public function EditCourse($id)
    {
        $course = Course::find($id);
        $goals = Course_goal::where('course_id',$id)->get();
        $categories = Category::latest()->get();
        $subcategories = SubCategory::latest()->get();
        return view('instructor.courses.edit_course',compact('course', 'categories','subcategories','goals'));
    }

    public function UpdateCourse(Request $request)
    {
        $cid = $request->get('course_id');

        Course::find($cid)->update([
            'category_id' => $request->get('category_id'),
            'subcategory_id' => $request->get('subcategory_id'),
            'instructor_id' => Auth::user()->id,
            'course_title' => $request->get('course_title'),
            'course_name' => $request->get('course_name'),
            'course_name_slug' => strtolower(str_replace(' ','-',$request->get('course_name'))),
            'description' => $request->get('description'),

            'label' => $request->get('label'),
            'duration' => $request->get('duration'),
            'resources' => $request->get('resources'),
            'certificate' => $request->get('certificate'),
            'selling_price' => $request->get('selling_price'),
            'discount_price' => $request->get('discount_price'),
            'prerequisites' => $request->get('prerequisites'),

            'bestseller' => $request->get('bestseller'),
            'featured' => $request->get('featured'),
            'highestrated' => $request->get('highestrated'),
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => "Course Updated Successfully",
            'alert-type' => 'success'
        );
        return redirect()->route('all.course')->with($notification);
    }

    public function UpdateCourseImage(Request $request) {
        $course_id = $request->get('id');
        $oldImage = $request->get('old_img');

        //image
        $image = $request->file('course_image');
        // create image manager with desired driver
        $manager = new ImageManager(new Driver());
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        $img = $manager->read($image);
        $img = $img->resize(370, 246);
        $img->toJpeg(80)->save(base_path('public/upload/course/thumbnail/' . $name_gen));
        $save_url = 'upload/course/thumbnail/' . $name_gen;

        if(file_exists($oldImage)) {
            unlink($oldImage);
        }

        Course::find($course_id)->update([
            'course_image' => $save_url,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Course Image Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function UpdateCourseVideo(Request $request) {
        $course_id = $request->get('vid');
        $oldVideo = $request->get('old_vid');

        $video = $request->file('video');
        $videoName = time().'.'.$video->getClientOriginalExtension();
        $video->move(public_path('upload/course/video/'),$videoName);
        $save_video = 'upload/course/video/'.$videoName;

        if (file_exists($oldVideo)) {
            unlink($oldVideo);
        }

        Course::find($course_id)->update([
            'video' => $save_video,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Course Video Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function UpdateCourseGoal(Request $request) {
        $cid = $request->get('id');

        if ($request->course_goals == NULL) {
            return redirect()->back();
        } else{

            Course_goal::where('course_id',$cid)->delete();

            $goles = Count($request->get('course_goals'));

            for ($i=0; $i < $goles; $i++) {
                $gcount = new Course_goal();
                $gcount->course_id = $cid;
                $gcount->goal_name = $request->course_goals[$i];
                $gcount->save();
            }  // end for
        } // end else

        $notification = array(
            'message' => 'Course Goals Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function DeleteCourse($id){
        $course = Course::find($id);

        if (file_exists($course->course_image)) {
            unlink($course->course_image);
        }
        if (file_exists($course->video)) {
            unlink($course->video);
        }

        Course::find($id)->delete();

        $goalsData = Course_goal::where('course_id',$id)->get();
        foreach ($goalsData as $item) {
            $item->goal_name;
            Course_goal::where('course_id',$id)->delete();
        }

        $notification = array(
            'message' => 'Course Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }// End Method

    public function AddCourseLecture($id) {
        $course = Course::find($id);
        $section = CourseSection::where('course_id',$id)->latest()->get();
        return view('instructor.courses.section.add_course_lecture', compact('course','section'));
    }

    public function AddCourseSection(Request $request)
    {
        $cid = $request->get('id');

        CourseSection::insert([
            'course_id' => $cid,
            'section_title' => $request->get('section_title'),
        ]);

        $notification = array(
            'message' => 'Course Section Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function DeleteSection($id){

        $section = CourseSection::find($id);

        $section->lectures()->delete();
        $section->delete();

        $notification = array(
            'message' => 'Course Section Delete Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }// End Method

    public function SaveLecture(Request $request){

        $lecture = new CourseLecture();
        $lecture->course_id = $request->course_id;
        $lecture->section_id = $request->section_id;
        $lecture->lecture_title = $request->lecture_title;
        $lecture->url = $request->lecture_url;
        $lecture->content = $request->get('content');
        $lecture->save();

        return response()->json(['success' => 'Lecture Saved Successfully']);

    }// End Method

    public function EditLecture($id){

        $clecture = CourseLecture::find($id);
        return view('instructor.courses.lecture.edit_course_lecture',compact('clecture'));

    }// End Method

    public function UpdateCourseLecture(Request $request){
        $lid = $request->get('id');

        CourseLecture::find($lid)->update([
            'lecture_title' => $request->get('lecture_title'),
            'url' => $request->get('url'),
            'content' => $request->get('content'),

        ]);

        $notification = array(
            'message' => 'Course Lecture Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }// End Method

    public function DeleteLecture($id){

        CourseLecture::find($id)->delete();

        $notification = array(
            'message' => 'Course Lecture Delete Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }// End Method
}
