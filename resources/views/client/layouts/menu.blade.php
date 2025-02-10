<div class="searchPage">
    <div class="container">
        <form action="{{ route('search') }}">
            @livewire('client.search')
        </form>
    </div>
</div>
<div class="headerNav">
    <div class="container">
        <div class="navbarMenu">
            <div class="logoImage">
                <a href="{{ route('index') }}">
                    <img src="{{asset('library/images/logo.png')}}" alt="Logo" class="object-fit-cover w-100 h-100">
                </a>
            </div>
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="{{route('xu_huong')}}">Xu Hướng</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('truyen')}}">Truyện Mới</a></li>
                    </ul>
                </div>
            </nav>
            <div class="searchIconMobile">
                <a class="iconMenu" href="javascript:void(0)" onclick="showMenuMobile();"><i class="fa fa-bars" aria-hidden="true"></i></a>
                <a href="javascript:void(0)" onclick="displaySearch();"><i class="fa fa-search" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="loginUser">
        @if(Auth::check())
            Xin chào <a class="urlUser" href="javascript:void(0)" onclick="displayInfoUser();">{{Auth::user()->name}} <i class="fa-solid fa-caret-down"></i></a>
        @else
            <a class="iconLogin" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalLogIn">Đăng Nhập</a>
            <a class="iconLogin" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalSignUp">Đăng Ký</a>
        @endif
        <div class="itemUser">
            <a href="{{ route('item_bookmark') }}">Truyện đã đánh dấu</a>
            <a style="border-top:1px solid #ddd" href="{{ route('logout') }}">Đăng xuất</a>
        </div>
    </div>
</div>
<div id="menuMobile">
    <i class="fa-solid fa-xmark" onclick="hideMenuMobile();"></i>
    <div class="logoImageMobi mb-3">
        <a href="{{ route('index') }}">
            <img src="{{asset('library/images/logo.png')}}" alt="Logo" class="object-fit-cover w-100 h-100">
        </a>
    </div>
    <ul>
        <li><a href="{{route('xu_huong')}}">Xu Hướng</a></li>
        <li><a href="{{route('truyen')}}">Truyện Mới</a></li>
    </ul>
</div>
<span id="scrollToTopBtn"><i class="fa-solid fa-arrow-up"></i></span>