<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@yield('title')</title>
		<link rel="stylesheet" href="{{asset('library/css/style.css')}}">
		<?php
		header("Access-Control-Allow-Headers: Authorization, Content-Type");
		header("Access-Control-Allow-Origin: *");
		header('content-type: application/json; charset=utf-8');
		?>
		@vite(['resources/css/app.css','resources/js/app.js'])
		@livewireStyles
		<style>
			.ck-editor__editable_inline {
				min-height: 200px;
			}
		</style>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	</head>
	<body 	x-data="{ page: '@yield("menu")', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    		x-init="darkMode = JSON.parse(localStorage.getItem('darkMode'));
         			$watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    		:class="{'dark text-bodydark bg-boxdark-2': darkMode === true}">
		<!-- ===== Preloader Start ===== -->
		<div
			x-show="loaded"
			x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})"
			class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black"
		>
			<div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent"></div>
	  	</div>
		
	  	<!-- ===== Preloader End ===== -->
		
		<!-- ===== Page Wrapper Start ===== -->
		<div class="flex h-screen overflow-hidden">
			<!-- ===== Sidebar Start ===== -->
			@include('admin.layouts.sidebar')
			<!-- ===== Sidebar End ===== -->
			
			<!-- ===== Content Area Start ===== -->
			<div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
				<!-- ===== Header Start ===== -->
				@include('admin.layouts.header')
				<!-- ===== Header End ===== -->

				<main>
					@yield('content')
					@livewire('wire-elements-modal')
				</main>

			</div>
			<!-- ===== Content Area End ===== -->
		</div>
		<!-- ===== Page Wrapper End ===== -->
		@livewireScripts
		<script src="{{asset('library/js/app.js')}}"></script>
	</body>
</html>