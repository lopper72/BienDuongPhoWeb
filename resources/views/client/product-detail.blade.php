@extends('client.layouts.master')

@section('title')
    {{ $product->name }}
@endsection

@section('content')
    @if ($product->shopper_link != "" && $_SESSION['show_url_shopee'] == 'y')
        <div id="showNoti" class="modal fade" tabindex="-1" data-bs-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="contentShopee">
                            <p>Mời bạn CLICK vào liên kết bên dưới và <span>MỞ ỨNG DỤNG SHOPEE</span> để xem thêm bài viết!</p>
                            <p><i class="fa-solid fa-hand-point-right"></i> <a onclick="unlockPage();" target="blank" href="{{$product->shopper_link}}">{{$product->shopper_link}}</a></p>
                            <div class="imgShopee">
                                <img src="{{asset('library/images/image-shopee-v2.png')}}" alt="image shopee" class="object-fit-cover w-100 h-100">
                            </div>
                            <h4>BIẾN ĐƯỜNG PHỐ XIN CHÂN THÀNH CẢM ƠN QUÝ ĐỘC GIẢ!</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var myModal = new bootstrap.Modal(document.getElementById('showNoti'), {
                keyboard: false
            });
            myModal.show();
            
            function unlockPage(){
                var idProduct = {{$product->id}};
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{route('check_url_shopee')}}",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    type: "POST",
                    data: {
                        idProduct: idProduct
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
            <h3 class="contentTitle">{{$product->name}}</h3>
            <div class="contentDetail">
                @if ($product->description != "")
                    @php
                        echo nl2br($product->description);
                    @endphp
                @endif
            </div>
        </div>
    @endif
@endsection