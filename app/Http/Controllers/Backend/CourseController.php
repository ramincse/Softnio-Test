<?php

namespace App\Http\Controllers\Backend;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_data   = Course::with('categories')->latest()->get();
        $categories = Category::orderBy('title', 'ASC')->get();
        return view('backend.course.index', compact('all_data', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'course_title'  => 'required',
            'category_id'   => 'required',
            'level'         => 'required',
        ]);

        $course_data = Course::create([
            'course_title'  => $request->course_title,            
            'course_slug'   => Str::slug($request->course_title),
            'level'         => $request->level,
        ]);

        $course_data->categories()->attach($request->category_id);

        return redirect()->route('course.index')->with('success', 'Course Added Successfull');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit_data = Course::find($id);
        return [
            'id'            => $edit_data->id,
            'course_title'  => $edit_data->course_title,
            'level'         => $edit_data->level,
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $edit_id = $request->id;
        $update_data = Course::find($edit_id);

        $update_data -> course_title = $request->course_title;
        $update_data -> course_slug  = Str::slug($request->course_title);
        $update_data -> level        = $request->level;
        $update_data -> update();

        return redirect()->route('course.index')->with('success', 'Course Updated Successfull');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete_data = Course::find($id);
        $delete_data -> delete();

        return redirect()->route('course.index')->with('success', 'Course Deleted Successfull');
    }

    //Course Switcher Update
    public function statusUpdateCourse($status_id)
    {
        $status_data = Course::find($status_id);

        if($status_data->status == true){
            $status_data -> status = 0;
            $status_data -> update();
        }else{
            $status_data -> status = 1;
            $status_data -> update();
        }
    }
}
