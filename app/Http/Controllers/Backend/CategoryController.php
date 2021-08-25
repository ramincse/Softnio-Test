<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_data = Category::latest()->get();
        return view('backend.category.index', compact('all_data'));
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
            'title' => 'required|unique:categories',
        ]);

        Category::create([
            'title' => $request->title,
            'slug'  => Str::slug($request->title),
        ]);

        return redirect()->route('category.index')->with('success', 'Category Added Successfull');
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
        $edit_data = Category::find($id);
        return [
            'id'        => $edit_data->id,
            'title'     => $edit_data->title,
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

        $update_data = Category::find($edit_id);
        $update_data -> title = $request -> title;
        $update_data -> slug = Str::slug($request->title);
        $update_data -> update();

        return redirect()->route('category.index')->with('success', 'Category Updated Successfull');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete_data = Category::find($id);
        $delete_data -> delete();

        return redirect()->route('category.index')->with('success', 'Category Deleted Successfull');
    }

    //Category Switcher Update
    public function statusUpdateCategory($status_id)
    {
        $status_data = Category::find($status_id);

        if($status_data->status == true){
            $status_data -> status = 0;
            $status_data -> update();
        }else{
            $status_data -> status = 1;
            $status_data -> update();
        }
    }
}
