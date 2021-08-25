@extends('backend.layouts.app')
@section('main-content')
<div class="row">
    <div class="col-md-12">	
        @include('validate')					
        <!-- Recent Orders -->
        <div class="card card-table">
            <div class="card-header">
                <h4 class="card-title">Course Content List <a class="btn btn-sm btn-info mb-2 pull-right" data-toggle="modal" href="#add_ccontent_modal">New Course Content</a></h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="table-responsive">
                        <table id="data_table1" class="table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Course Name</th>
                                    <th>Short Description</th>
                                    <th>Course Level</th>
                                    <th>Status</th>
                                    <th>Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_data as $data)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $data->course_name }}</td>
                                    <td>{{ Str::of($data -> c_content)->words(5) }}</td>
                                    <td>{{ $data->c_level }}</td>
                                    <td>
                                        <div class="status-toggle">
                                            <input type="checkbox" class="check ccontent_check" id="ccontent_status_{{ $loop-> index + 1 }}" status_id="{{ $data->id }}" class="check" {{ $data->status == true ? 'checked="checked"' : '' }}>
                                            <label for="ccontent_status_{{ $loop-> index + 1 }}" class="checktoggle">checkbox</label>
                                        </div>
                                    </td>
                                    <td>
                                        <a edit_id="{{ $data->id }}" class="btn btn-sm btn-info edit_ccontent" href="#" title="Course Content Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        <a edit_id="{{ $data->id }}" class="btn btn-sm btn-primary" href="{{ route('multi.image', $data->id) }}" title="Multi Image Update"><i class="fa fa-picture-o" aria-hidden="true"></i></a>
                                        <form class="d-inline" action="{{ route('post.destroy', $data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button id="cat_delete_btn" class="btn btn-sm btn-danger" title="Delete Data"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Recent Orders -->							
    </div>
</div>

<!--================ Add New Course Content Modal Start ================-->
<div id="add_ccontent_modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Content</h4>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <select class="form-control" name="course_name" id="">
                            <option value="" selected="" disabled>Select Course</option>
                            @foreach ($courses as $course)
                            <option value="{{ $course->course_title }}">{{ $course->course_title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="c_level" id="">
                            <option value="" selected="" disabled>Select Level-</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Beginner">Expert</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input name="content_title" class="form-control" type="text" placeholder="Title" value="{{ old('content_title') }}">
                    </div>

                    <div class="form-group">
                        <label for="">Thumbnail</label>
                        <input name="thumbnail" class="form-control" type="file" onChange="mainThumbUrl(this)">
                        <img id="mainThumb" src="">
                    </div>

                    <div class="form-group">
                        <label for="">Multiple Image</label>
                        <input type="file" name="multi_img[]" class="form-control" multiple id="multiImg">
                        <div class="row" id="preview_img">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Short Description</label>
                        <textarea name="c_content" id="" rows="5" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <input class="btn btn-block btn-info" type="submit" value="Add Content">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--================ Add New Course Content Modal End ================-->

<!--================ Edit or Update Course Content Modal Start ================-->
<div id="update_ccontent_modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Content</h4>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('post.update', 1) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <select class="form-control" name="course_name" id="">
                            <option value="" selected="" disabled>Select Course</option>
                            @foreach ($courses as $course)
                            <option value="{{ $course->course_title }}">{{ $course->course_title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="c_level" id="">
                            <option value="" selected="" disabled>Select Level-</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Beginner">Expert</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input name="content_title" class="form-control" type="text" placeholder="Title" value="{{ old('content_title') }}">
                    </div>

                    <div class="form-group">
                        <label for="">Thumbnail</label>
                        <input name="thumbnail" class="form-control" type="file" onChange="mainThumbUrl(this)">
                        <img class="mt-2" id="mainThumb" src="" style="width: 80px; height: 80px;">
                    </div>

                    <div class="form-group">
                        <label for="">Short Description</label>
                        <textarea name="c_content" id="" rows="5" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <input class="btn btn-block btn-info" type="submit" value="Update Content">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--================ Edit or Update Course Content Modal End ================-->


@section('javascript')
<!--================ Main Thumbnail Image Load ================-->
<script type="text/javascript">
    //Main Thumbnail Image Load
    function mainThumbUrl(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();

            reader.onload = function(e){
                $('#mainThumb').attr('src',e.target.result).width(80).height(80);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<!--================ Multiple Image Upload ================-->
<script type="text/javascript"> 
    $(document).ready(function(){
        $('#multiImg').on('change', function(){ //on file input change
        if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
        {
            var data = $(this)[0].files; //this file data
                
            $.each(data, function(index, file){ //loop though each file
                if(/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)){ //check supported file type
                    var fRead = new FileReader(); //new filereader
                    fRead.onload = (function(file){ //trigger function on successful read
                    return function(e) {
                        var img = $('<img/>').addClass('thumb').attr('src', e.target.result) .width(80)
                    .height(80); //create image element 
                        $('#preview_img').append(img); //append image to output element
                    };
                    })(file);
                    fRead.readAsDataURL(file); //URL representing the file's data.
                }
            });
                
        }else{
            alert("Your browser doesn't support File API!"); //if File API is absent
        }
        });
    });     
</script>


<!--================ Course Content Edit and Update ================-->
<script type="text/javascript">
    (function ($) {    
        $(document).ready(function () {
            //Edit for update Course
            $(document).on('click', '.edit_ccontent', function(e){
                e.preventDefault();
                let edit_id = $(this).attr('edit_id');
                $.ajax({
                    url: 'post/' + edit_id + '/edit',
                    success: function (data) {
                        $('#update_ccontent_modal form input[name="id"]').val(data.id);
                        $('#update_ccontent_modal form select[name="course_name"]').val(data.course_name);
                        $('#update_ccontent_modal form select[name="c_level"]').val(data.c_level);
                        $('#update_ccontent_modal form input[name="content_title"]').val(data.content_title);
                        $('#update_ccontent_modal form textarea[name="c_content"]').val(data.c_content);
                        $('#update_ccontent_modal img#mainThumb').attr('src', 'media/thumbnail/' + data.thumbnail);                        
                        $('#update_ccontent_modal').modal('show');
                    }
                });
            });

            //Course Content Status Update 
            $(document).on('change', '.ccontent_check', function(){
                let status_id = $(this).attr('status_id');
                $.ajax({
                    url: '/course/content/status-update/' + status_id,
                    success: function(data){
                        swal('Course Content Status Updated Successfull!');
                    }
                });
            });

            //
            $('#data_table1').DataTable();

        });   
    })(jQuery)
</script>
@stop
@endsection