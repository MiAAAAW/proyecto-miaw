<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LectureController extends Controller
{
    //
    public function updateCheckboxState(Request $request, $lectureId){
        $checkboxState = $request->input('checkbox_state');
        DB::update('UPDATE course_lectures SET checkbox_state = ? WHERE id = ?', [$checkboxState, $lectureId]);
        return response()->json(['message' => 'Checkbox state updated successfully'], 200);
    }
}
