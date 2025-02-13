@extends('client.layouts.master')

@section('title')
    {{ $product->name }}
@endsection

@section('content')
    @if ($product->shopper_link != "" && filter_var($product->shopper_link, FILTER_VALIDATE_URL) && strpos($product->shopper_link, "http") === 0 && $_SESSION['show_url_shopee'] == 'y')
        <div id="showNoti" class="modal fade" tabindex="-1" data-bs-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="contentShopee">
                            <p>Mời bạn CLICK vào liên kết bên dưới và <span>MỞ ỨNG DỤNG SHOPEE</span> để xem thêm bài viết!</p>
                            <p><i class="fa-solid fa-hand-point-right"></i> <a onclick="unlockPage();" href="javascript:void(0)">{{$product->shopper_link}}</a></p>
                            <div class="imgShopee">
                                <a onclick="unlockPage();" href="javascript:void(0)">
                                    <img src="{{asset('library/images/image-shopee.png')}}" alt="image shopee" class="object-fit-cover w-100 h-100">
                                </a>
                            </div>
                            <h4>BIẾN ĐƯỜNG PHỐ XIN CHÂN THÀNH CẢM ƠN QUÝ ĐỘC GIẢ!</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a style="display:none;" id="goToLinkShopee" target="_blank" href="{{$product->shopper_link}}">
        <script>
            var myModal = new bootstrap.Modal(document.getElementById('showNoti'), {
                keyboard: false
            });
           
            setTimeout(function() {
                myModal.show();
            }, 5000);
            
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
                        document.getElementById('goToLinkShopee').click();
                        location.reload();
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            }
        </script> 
    @endif
    <div class="container mb-4">
        <h3 class="contentTitle">{{$product->name}}</h3>
        <div class="contentDetail">
            @if ($product->description != "")
                @php
                    echo nl2br($product->description);
                @endphp
            @endif
        </div>
    </div>
@endsection