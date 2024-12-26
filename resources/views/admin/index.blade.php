<!DOCTYPE html>
<html lang="en">

@include('admin.partials.index_partials.head')

<body>

	<div id="preloader">
		<div id="loader"></div>
	</div>

	<!--**********************************
        Main wrapper start
    ***********************************-->
	<div id="main-wrapper">
		<!--**********************************
            Nav Header
        ***********************************-->
		@include('admin.partials.index_partials.navHeader')

		<!--**********************************
            Chat box 
        ***********************************-->
		@include('admin.partials.index_partials.chatBox')

		<!--**********************************
            Header 
        ***********************************-->
		@include('admin.partials.index_partials.header')

		<!--**********************************
            Sidebar 
        ***********************************-->
		@include('admin.partials.index_partials.sideBar')

		<!--**********************************
            Content body 
        ***********************************-->
		@include('admin.partials.index_partials.contentBody')


	</div>
	<!--**********************************
        Main wrapper end
    ***********************************-->

	<!--**********************************
        Scripts
    ***********************************-->
	@include('admin.partials.index_partials.scripts')


</body>

</html>