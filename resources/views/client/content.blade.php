@extends('client.layouts.master')

@section('title')
    {{ $product->name }} - {{ $chap->title }}
@endsection

@section('content')
    @if ($product->shopper_link != "" && $chap->number_order != 1 && $_SESSION['show_url_shopee'] == 'y')
        <div class="container">
            <div class="contentShopee">
                <p>Mời bạn CLICK vào liên kết bên dưới và <span>MỞ ỨNG DỤNG SHOPEE</span> để mở khóa toàn bộ chương Bài Đăng!</p>
                <p><i class="fa-solid fa-hand-point-right"></i> <a onclick="unlockPage();" target="blank" href="{{$product->shopper_link}}">{{$product->shopper_link}}</a></p>
                <div class="imgShopee">
                    <img src="{{asset('library/images/image-shopee-v2.png')}}" alt="image shopee" class="object-fit-cover w-100 h-100">
                </div>
                <h4>Bài Đăng FULL BỘ XIN CHÂN THÀNH CẢM ƠN QUÝ ĐỘC GIẢ!</h4>
            </div>
            <input type="hidden" name="idProduct" id="idProduct" value="{{$product->id}}">
            <input type="hidden" name="idChap" id="idChap" value="{{$chap->id}}">
        </div>
        <script>
            function unlockPage(){
                var idProduct = document.getElementById('idProduct').value;
                var idChap = document.getElementById('idChap').value;
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{route('check_url_shopee')}}",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    type: "POST",
                    data: {
                        idProduct: idProduct,
                        idChap: idChap
                    },
                    dataType: "json",
                    success: function (response) {
                        location.reload();
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            }
        </script>
    @else
        <div class="container">
            <h3 class="chapTitle mb-3">{{$product->name}} - {{$chap->title}}</h3>
            <div class="breadcrumbChap">
                <a href="{{route('index')}}">Trang chủ</a> / <a href="{{route('truyen')}}">Tất cả Bài Đăng</a> / <a href="{{route('truyen_chitiet',$product->slug)}}">{{$product->name}}</a> / <span>{{$chap->title}}</span>
            </div>
            <div class="btnChapContent">
                <select id="selChap" class="form-select">
                    @foreach ($chaps as $item)
                        <option @if($item->id == $chap->id) selected @endif value="{{$item->number_order}}" data-redirect="{{route('chap',[$product->slug,$item->number_order])}}">{{$item->title}}</option>
                    @endforeach
                </select>
                <div class="itemButtonDetail">
                    @if ($chap->number_order != 1)
                        <a class="btnChap" href="{{route('chap',[$product->slug,$chap->number_order - 1])}}"><i class="fa-solid fa-arrow-left"></i> Chương trước</a>
                    @endif
                    @if ($chap->number_order < count($chaps))
                        <a class="btnChap" href="{{route('chap',[$product->slug,$chap->number_order + 1])}}">Chương tiếp <i class="fa-solid fa-arrow-right"></i></a>
                    @endif
                </div>
            </div>
            <div class="chapContent mb-5">
                @if ($chap->short_description != "")
                    @php
                        echo nl2br($chap->short_description);
                    @endphp
                @endif
            </div>
            <div class="btnChapContent">
                <select id="selChap2" class="form-select">
                    @foreach ($chaps as $item)
                        <option @if($item->id == $chap->id) selected @endif value="{{$item->number_order}}" data-redirect="{{route('chap',[$product->slug,$item->number_order])}}">{{$item->title}}</option>
                    @endforeach
                </select>
                <div class="itemButtonDetail">
                    @if ($chap->number_order != 1)
                        <a class="btnChap" href="{{route('chap',[$product->slug,$chap->number_order - 1])}}"><i class="fa-solid fa-arrow-left"></i> Chương trước</a>
                    @endif
                    @if ($chap->number_order < count($chaps))
                        <a class="btnChap" href="{{route('chap',[$product->slug,$chap->number_order + 1])}}">Chương tiếp <i class="fa-solid fa-arrow-right"></i></a>
                    @endif
                </div>
            </div>
            <div class="titleIndex2">
                <i class="fa-solid fa-star"></i><span>Bình luận</span>
            </div>
            @include('client.layouts.comment')
            @if (count($comments))
                <div class="titleIndex2">
                    <i class="fa-solid fa-star"></i><span>Có {{count($comments)}} bình luận</span>
                </div>
            @endif
            @foreach ($comments as $item => $comment)
                @php
                    $userComents = DB::select('
                        SELECT *
                        FROM users
                        WHERE id = '.$comment->user_id.'
                    ');
                @endphp
                <div class="listComment">
                    <div class="headerListComment">
                        <b>{{$userComents[0]->name}}</b><span>{{date('d/m/Y', strtotime($comment->created_at))}}</span>
                    </div>
                    <div>
                        @php
                            echo nl2br($comment->comment);
                        @endphp
                    </div>
                </div>
            @endforeach
        </div>
        <script>
            document.getElementById('selChap').addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                var redirectUrl = selectedOption.getAttribute('data-redirect');
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                }
            });
            document.getElementById('selChap2').addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                var redirectUrl = selectedOption.getAttribute('data-redirect');
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                }
            });
        </script>
    @endif
@endsection