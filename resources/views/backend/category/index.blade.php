@extends('backend.layouts.app')
@section('main-content')
<div class="row">
    <div class="col-md-12">	
        @include('validate')					
        <!-- Recent Orders -->
        <div class="card card-table">
            <div class="card-header">
                <h4 class="card-title">Category List <a class="btn btn-sm btn-info mb-2 pull-right" data-toggle="modal" href="#add_category_modal">New Category</a></h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-center mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_data as $data)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $data->title }}</td>
                                <td>
                                    <div class="status-toggle">
                                        <input type="checkbox" class="check cat_check" id="cat_status_{{ $loop-> index + 1 }}" status_id="{{ $data->id }}" class="check" {{ $data->status == true ? 'checked="checked"' : '' }}>
                                        <label for="cat_status_{{ $loop-> index + 1 }}" class="checktoggle">checkbox</label>
                                    </div>
                                </td>
                                <td>
                                    <a edit_id="{{ $data->id }}" class="btn btn-sm btn-info edit_cat" href="#" title="Category Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    <form class="d-inline" action="{{ route('category.destroy', $data->id) }}" method="POST">
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

<!--================ Add New Category Modal Start ================-->
<div id="add_category_modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add new category</h4>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('category.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input name="title" class="form-control" type="text" placeholder="Category Name">
                    </div>
                    <div class="form-group">
                        <input class="btn btn-block btn-info" type="submit" value="Add Category">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--================ Add New Category Modal End ================-->

<!--================ Edit or Update Category Modal Start ================-->
<div id="update_category_modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Category</h4>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('category.update', 1) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <input name="id" type="hidden">
                        <input name="title" class="form-control" type="text" placeholder="Category Name">
                    </div>
                    <div class="form-group">
                        <input class="btn btn-block btn-info" type="submit" value="Update Category">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--================ Edit or Update Category Modal End ================-->
@section('javascript')
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            //Edit for update Category
            $(document).on('click', '.edit_cat', function(e){
                e.preventDefault();
                let edit_id = $(this).attr('edit_id');
                $.ajax({
                    url: 'category/' + edit_id + '/edit',
                    success: function (data) {
                        $('#update_category_modal form input[name="id"]').val(data.id);
                        $('#update_category_modal form input[name="title"]').val(data.title);
                        $('#update_category_modal').modal('show');
                    }
                });
            });

            //Category Status Update
            $(document).on('change', '.cat_check', function(){
                let status_id = $(this).attr('status_id');
                $.ajax({
                    url: '/category/status-update/' + status_id,
                    success: function(data){
                        swal('Category Status Updated Successfull!');
                    }
                });
            });

        });   
    })(jQuery)
</script>
@stop
@endsection