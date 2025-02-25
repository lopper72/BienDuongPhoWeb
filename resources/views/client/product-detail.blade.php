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
                            <p><i class="fa-solid fa-hand-point-right"></i> <a onclick="unlockPage();" target="_blank" href="{{$product->shopper_link}}">{{$product->shopper_link}}</a></p>
                            <div class="imgShopee">
                                <a onclick="unlockPage();" class="object-fit-cover w-100 h-100 custom-height" target="_blank" href="{{$product->shopper_link}}">
                                    <img src="{{asset('library/images/image-shopee.png')}}" alt="image shopee" class="object-fit-cover w-100 h-100 custom-height">
                                </a>
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
                        myModal.hide();
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

            {{-- Display existing videos --}}
            @if (!empty($existingVideos))
               
                <div class="video-gallery">
                    @foreach ($existingVideos as $video)
                        <div class="video-container mb-4">
                            <video controls class="rounded-lg shadow-md w-full" >
                                <source src="{{ asset('storage/videos/products/' . $video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($product->description2 != "")
                @php
                    echo nl2br($product->description2);
                @endphp
            @endif

            
            
        </div>
    </div>
@endsection

<style>
    .custom-height {
    height: 100% !important;
}
.video-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    height: 0;
    overflow: hidden;
}

.video-container video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.imgShopee {
    display: flex; /* or display: grid; */
    align-items: stretch; /* Ensures children stretch to fill the height */
    height: 300px; /* Set a specific height */
}
</style>