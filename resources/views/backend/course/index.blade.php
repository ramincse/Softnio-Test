@extends('backend.layouts.app')
@section('main-content')
<div class="row">
    <div class="col-md-12">	
        @include('validate')					
        <!-- Recent Orders -->
        <div class="card card-table">
            <div class="card-header">
                <h4 class="card-title">Course List <a class="btn btn-sm btn-info mb-2 pull-right" data-toggle="modal" href="#add_course_modal">New Course</a></h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-center mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Course Title</th>
                                <th>Category</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_data as $data)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $data->course_title }}</td>
                                <td>
                                    @foreach ($data->categories as $cat)
                                    {{ $cat->title }}
                                    @endforeach
                                </td>
                                <td>{{ $data->level }}</td>
                                <td>
                                    <div class="status-toggle">
                                        <input type="checkbox" class="check course_check" id="course_status_{{ $loop-> index + 1 }}" status_id="{{ $data->id }}" class="check" {{ $data->status == true ? 'checked="checked"' : '' }}>
                                        <label for="course_status_{{ $loop-> index + 1 }}" class="checktoggle">checkbox</label>
                                    </div>
                                </td>
                                <td>
                                    <a edit_id="{{ $data->id }}" class="btn btn-sm btn-info edit_course" href="#" title="Course Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    <form class="d-inline" action="{{ route('course.destroy', $data->id) }}" method="POST">
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
        <!-- /Recent Orders -->							
    </div>
</div>

<!--================ Add New Course Modal Start ================-->
<div id="add_course_modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Course</h4>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('course.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input name="course_title" class="form-control" type="text" placeholder="Course Name" value="{{ old('course_title') }}">
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="category_id" id="">
                            <option value="" selected="" disabled>-select a category-</option>
                            @foreach ($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach                          
                        </select>
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="level" id="">
                            <option value="" selected="" disabled>-select a level-</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Beginner">Expert</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input class="btn btn-block btn-info" type="submit" value="Add Course">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--================ Add New Course Modal End ================-->

<!--================ Edit or Update Course Modal Start ================-->
<div id="update_course_modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Course</h4>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('course.update', 1) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <input name="id" type="hidden">
                        <input name="course_title" class="form-control" type="text" placeholder="Course Name">
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="category_id" id="">
                            <option value="" selected="" disabled>-select a category-</option>
                            @foreach ($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach                          
                        </select>
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="level" id="">
                            <option value="" selected="" disabled>-select a level-</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Beginner">Expert</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input class="btn btn-block btn-info" type="submit" value="Update Course">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--================ Edit or Update Course Modal End ================-->
@section('javascript')
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            //Edit for update Course
            $(document).on('click', '.edit_course', function(e){
                e.preventDefault();
                let edit_id = $(this).attr('edit_id');
                $.ajax({
                    url: 'course/' + edit_id + '/edit',
                    success: function (data) {
                        $('#update_course_modal form input[name="id"]').val(data.id);
                        $('#update_course_modal form input[name="course_title"]').val(data.course_title);
                        $('#update_course_modal form select[name="level"]').val(data.level);
                        $('#update_course_modal').modal('show');
                    }
                });
            });

            //Course Status Update
            $(document).on('change', '.course_check', function(){
                let status_id = $(this).attr('status_id');
                $.ajax({
                    url: '/course/status-update/' + status_id,
                    success: function(data){
                        swal('Course Status Updated Successfull!');
                    }
                });
            });

        });   
    })(jQuery)
</script>
@stop
@endsection