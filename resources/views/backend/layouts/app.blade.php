<!DOCTYPE html>
<html lang="en">
    <head>
        @include('backend.layouts.head') 
    </head>
    <body>
	
		<!-- Main Wrapper -->
        <div class="main-wrapper">
		
			<!--================ Header ================-->
            @include('backend.layouts.header') 
			<!--================ /Header ================-->
			
			<!--================ Sidebar ================-->
            @include('backend.layouts.sidebar') 
			<!--================ /Sidebar ================-->
			
			<!-- Page Wrapper -->
            <div class="page-wrapper">			
                <div class="content container-fluid">					
					<!--================ Page Header ================-->
					@include('backend.layouts.page_header') 
					<!--================ /Page Header ================-->
					
					<!--================ Page Content Area ================-->
					@section('main-content')
                    @show					
				</div>			
			</div>
			<!-- /Page Wrapper -->		
        </div>
		<!-- /Main Wrapper -->		
		<!--================ jQuery ================-->
        @include('backend.layouts.partials.scripts') 		
    </body>
</html>