<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>@yield('title')</title>
        <link rel="icon" type="image/x-icon" href="{{asset('library/images/favicon.jpg')}}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('cssjs/main.css') }}?v={{date('dmYH', time())}}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
		@livewireStyles
	</head>
	<body>
            @include('client.layouts.menu')
            @yield('content')
            @if(!Auth::check())
                @include('client.layouts.modal_login')
                @include('client.layouts.modal_signup')
            @endif
    </body>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="{{asset('cssjs/main.js')}}?v={{date('dmYH', time())}}"></script>
    <script>
        function showMenuMobile() {
            $("#menuMobile").addClass("showMenu");
        }
        function hideMenuMobile() {
            $("#menuMobile").removeClass("showMenu");
        }
        function displaySearch() {
            if ($('.searchPage').attr("class").includes("showSearch")) {
                $('.searchPage').removeClass("showSearch");
            } else {
                $('.searchPage').addClass("showSearch");
            }
        }
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');
        window.addEventListener('scroll', function() {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                scrollToTopBtn.style.display = 'block';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        });
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        function displayInfoUser() {
            if ($('.loginUser').attr("class").includes("showInfoUser")) {
                $('.loginUser').removeClass("showInfoUser");
                $('.loginUser .fa-solid').removeClass("fa-caret-up");
                $('.loginUser .fa-solid').addClass("fa-caret-down");
            } else {
                $('.loginUser').addClass("showInfoUser");
                $('.loginUser .fa-solid').removeClass("fa-caret-down");
                $('.loginUser .fa-solid').addClass("fa-caret-up");
            }
        }
    </script>
</html>