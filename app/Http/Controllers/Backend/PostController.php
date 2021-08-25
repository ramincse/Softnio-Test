<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Course;
use App\Models\MultiImg;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_data = Post::latest()->get();
        //$courses = Course::orderBy('course_title', 'ASC')->get();
        $courses = Course::groupBy('course_title')->select('course_title')->get();
        $c_level = Course::groupBy('level')->select('level')->get();
        return view('backend.course_content.index', compact('all_data', 'courses', 'c_level'));
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
        // $this->validate($request, [
        //     'thumbnail'     => 'required|image|mimes:jpeg,png,jpg,gif',
        // ]);

        //thumbnail Image Load
        if ($request->hasFile('thumbnail')) {
            $img = $request->file('thumbnail');
            $photo_uname = md5(time() . rand()) . '.' . $img->getClientOriginalExtension();
            Image::make($img)->save('media/thumbnail/' . $photo_uname);
        }

        //Course Content Insert to DB
        $post_id = Post::insertGetId([
            'course_name'   => $request->course_name,
            'c_level'       => $request->c_level,
            'content_title' => $request->content_title,
            'ctitle_slug'   => Str::slug($request->content_title),
            'c_content'     => $request->c_content,
            'thumbnail'     => $photo_uname,
        ]);

        //Multiple Image Upload
        if($request->file('multi_img')){
            $images      = $request->file('multi_img');
            foreach($images as $mimg){
                $make_name   = md5(time() . rand()) . '.' . $mimg->getClientOriginalExtension();
                Image::make($mimg)->save('media/multi-image/' . $make_name);
    
                MultiImg::insert([
                    'post_id'       => $post_id,
                    'photo_name'    => $make_name,
                ]);
            }
        }

        return redirect()->route('post.index')->with('success', 'Course Content Added Successfull');    
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
        $edit_data = Post::find($id);
        return [
            'id'            => $edit_data->id,
            'course_name'   => $edit_data->course_name,
            'c_level'       => $edit_data->c_level,
            'content_title' => $edit_data->content_title,
            'c_content'     => $edit_data->c_content,
            'thumbnail'     => $edit_data->thumbnail,
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
        $edit_id     = $request->id;
        $update_data = Post::find($edit_id);

        //thumbnail Image Load
        if ($request->hasFile('thumbnail')) {
            $img = $request->file('thumbnail');
            $photo_uname = md5(time() . rand()) . '.' . $img->getClientOriginalExtension();
            Image::make($img)->save('media/thumbnail/' . $photo_uname);
            if($update_data->thumbnail){
                unlink('media/thumbnail/' . $update_data->thumbnail);
            }
        }else{
            $photo_uname = $update_data->thumbnail;
        }

        $update_data -> course_name   = $request->course_name;
        $update_data -> c_level       = $request->c_level;
        $update_data -> content_title = $request->content_title;
        $update_data -> ctitle_slug   = Str::slug($request->content_title);
        $update_data -> c_content     = $request->c_content;
        $update_data -> thumbnail     = $photo_uname;
        $update_data -> update();

        return redirect()->route('post.index')->with('success', 'Course Content Updated Successfull');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete_data = Post::find($id);
        $delete_data->delete();
        //Single Image
        if ($delete_data->thumbnail) {
            unlink('media/thumbnail/' . $delete_data->thumbnail);
        }

        //Multi Image
        $images = MultiImg::where('post_id', $id)->get();
        foreach($images as $img){
            unlink('media/multi-image/' . $img->photo_name);
            MultiImg::where('post_id', $id)->delete();
        }

        return redirect()->route('post.index')->with('success', 'Course Content Deleted Successfull');
    }

    //Course Content Status Update
    public function statusUpdatePost($status_id)
    {
        $status_data = Post::find($status_id);

        if($status_data->status == true){
            $status_data -> status = 0;
            $status_data -> update();
        }else{
            $status_data -> status = 1;
            $status_data -> update();
        }
    }

    //Multi Image Show
    public function multiImageShow($id)
    {
        $multiimg = MultiImg::where('post_id', $id)->get();

        return view('backend.course_content.multi_img_view', compact('multiimg'));
    }

    //Multiple Image Update
    public function multiImageUpdate(Request $request)
    {
        $imgs = $request->multi_img;

        foreach($imgs as $id => $img){
            $imgDel = MultiImg::findOrFail($id);
            unlink('media/multi-image/' . $imgDel->photo_name);

            $make_name   = md5(time() . rand()) . '.' . $img->getClientOriginalExtension();

            Image::make($img)->save('media/multi-image/' . $make_name);

            MultiImg::where('id', $id)->update([
                'photo_name' => $make_name,
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->route('post.index')->with('success', 'Multi Image Updated Successfull');
    }


    //Delete Multi Image
    public function deleteMultiImage($id)
    {
        $oldimg = MultiImg::findOrFail($id);
        unlink('media/multi-image/' . $oldimg->photo_name);

        MultiImg::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Multi Image Updated Successfull');
    }
}
