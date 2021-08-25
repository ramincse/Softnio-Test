@extends('backend.layouts.app')
@section('main-content')
<div class="row">
    <div class="col-md-12">	
        @include('validate')					
        <!-- Recent Orders -->
        <form action="{{ route('multi.image.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                @foreach ($multiimg as $img) 
                <div class="col-md-3">
                    <div class="card">
                        <img class="card-img-top mt-2" src="{{ URL::to('/') }}/media/multi-image/{{ $img->photo_name }}" style="width: 280px; height:130px; margin: auto;">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('multi.image.delete', $img->id) }}" class="btn btn-sm btn-danger" id="delete" title="Delete Image"><i class="fa fa-trash"></i></a>
                            </h5>
                            <p class="card-text">
                                <div class="form-group">
                                    <label class="form-control-label">Change Image <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="multi_img[ {{ $img->id }} ]">
                                </div>
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="form-group">
                <input class="btn btn-lg btn-info" type="submit" value="Save">
            </div>
        </form>
        <!-- /Recent Orders -->							
    </div>
</div>


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

        });   
    })(jQuery)
</script>
@stop
@endsection