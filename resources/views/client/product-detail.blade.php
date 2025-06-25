@extends('client.layouts.master')

@section('title')
    {{ $product->name }}
@endsection

@section('content')
 
    @php
        $showTikTok = $product->tiktok_link != "" && filter_var($product->tiktok_link, FILTER_VALIDATE_URL) && strpos($product->tiktok_link, "http") === 0 && $_SESSION['show_url_tiktok'] == 'y';
        $showShopee = $product->shopper_link != "" && filter_var($product->shopper_link, FILTER_VALIDATE_URL) && strpos($product->shopper_link, "http") === 0 && $_SESSION['show_url_shopee'] == 'y';
    @endphp
    @if ($showTikTok || $showShopee)
        <div id="customBackdrop" class="custom-backdrop" style="display:none;"></div>
    @endif
    @if ($showTikTok)
        <div id="customTikTokPopup" class="custom-popup" style="top: 100px; right: 0; display:none;">
            <a href="javascript:void(0);" class="close-btn" onclick="unlockPageTikTok('customTikTokPopup','{{$product->tiktok_link}}')">&times;</a>
            <div style="text-align:center;">
                <a href="javascript:void(0);" onclick="unlockPageTikTok('customTikTokPopup','{{$product->tiktok_link}}')" target="_blank">
                    <img src="{{asset('library/images/image-tiktok.png')}}" alt="TikTok" style="width:100px;">
                </a>
            </div>
        </div>
    @endif
    @if ($showShopee)
        <div id="customShopeePopup" class="custom-popup" style="top: 300px; right: 0; display:none;">
            <a href="javascript:void(0);" class="close-btn" onclick="unlockPageTikTok('customShopeePopup','{{$product->shopper_link}}')">&times;</a>
            <div style="text-align:center;">
                <a  href="javascript:void(0);" onclick="unlockPageTikTok('customShopeePopup','{{$product->shopper_link}}')" target="_blank">
                    <img src="{{asset('library/images/image-shopee.png')}}" alt="Shopee" style="width:100px;">
                </a>
            </div>
        </div>
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
}
.custom-backdrop {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.7);
    z-index: 1999;
    display: block;
}
.custom-popup {
    position: fixed;
    z-index: 2000;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.18);
    padding: 5px 6px;
    min-width: 100px;
    max-width: 260px;
    transition: all 0.3s;
}
.close-btn {
    position: absolute;
    top: 0px;
    right: 8px;
    background: transparent;
    border: none;
    font-size: 24px;
    color: #ff3333;
    cursor: pointer;
    text-decoration: none;
}
html.noscroll, body.noscroll {
    overflow: hidden !important;
    height: 100% !important;
}
</style>

<script>
let scrollPosition = 0;
function lockScroll() {
    scrollPosition = window.scrollY || window.pageYOffset;
    document.body.style.position = 'fixed';
    document.body.style.top = `-${scrollPosition}px`;
    document.body.style.left = '0';
    document.body.style.right = '0';
    document.body.style.width = '100%';
    document.body.classList.add('noscroll');
    document.documentElement.classList.add('noscroll');
}

function unlockScroll() {
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.left = '';
    document.body.style.right = '';
    document.body.style.width = '';
    document.body.classList.remove('noscroll');
    document.documentElement.classList.remove('noscroll');
    window.scrollTo(0, scrollPosition);
}

            function unlockPageTikTok(id,link){
                var idProduct = {{$product->id}};
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                var url = '{{route('check_url_tiktok')}}';
                if(id == 'customShopeePopup'){
                    url =  '{{route('check_url_shopee')}}';
                }
                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    type: "POST",
                    data: {
                        idProductTikTok: idProduct,
                        idProductShopee: idProduct,
                    },
                    dataType: "json",
                    success: function (response) {
                        document.getElementById(id).style.display = 'none';
                        checkHideBackdrop();
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
                // Chuyển đổi link Shopee web sang link app nếu có
                handleShopeeLink(link);
            }

function hidePopup(id) {
    document.getElementById(id).style.display = 'none';
    checkHideBackdrop();
}

function hideAllPopups() {
    var tiktok = document.getElementById('customTikTokPopup');
    var shopee = document.getElementById('customShopeePopup');
    if (tiktok) tiktok.style.display = 'none';
    if (shopee) shopee.style.display = 'none';
    document.getElementById('customBackdrop').style.display = 'none';
}

function checkHideBackdrop() {
    var tiktok = document.getElementById('customTikTokPopup');
    var shopee = document.getElementById('customShopeePopup');
    var backdrop = document.getElementById('customBackdrop');
    var tiktokHidden = !tiktok || tiktok.style.display === 'none';
    var shopeeHidden = !shopee || shopee.style.display === 'none';
    if (tiktokHidden && shopeeHidden && backdrop) {
        backdrop.style.display = 'none';
    }
}

// Khi load trang, đảm bảo popup và backdrop đều hiển thị nếu có
window.addEventListener('DOMContentLoaded', function() {
    var tiktok = document.getElementById('customTikTokPopup');
    var shopee = document.getElementById('customShopeePopup');
    var backdrop = document.getElementById('customBackdrop');
    setTimeout(function() {
        if (tiktok) tiktok.style.display = 'block';
        if (shopee) shopee.style.display = 'block';
        if ((tiktok && tiktok.style.display !== 'none') || (shopee && shopee.style.display !== 'none')) {
            if (backdrop) backdrop.style.display = 'block';
        }
    }, 2000);
    // Theo dõi backdrop để khóa/mở scroll
    if (backdrop) {
        const observer = new MutationObserver(function() {
            if (backdrop.style.display !== 'none') {
                lockScroll();
            } else {
                unlockScroll();
            }
        });
        observer.observe(backdrop, { attributes: true, attributeFilter: ['style'] });
        // Khởi tạo trạng thái ban đầu
        if (backdrop.style.display !== 'none') {
            lockScroll();
        } else {
            unlockScroll();
        }
    }
});



async function handleShopeeLink(link) {
    // Loại bỏ ký tự @ đầu nếu có
    link = link.replace(/^@/, '');
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Nếu là link trung gian tin-vn.life
    if (link.includes('tin-vn.life/shopee-web') || link.includes('facebookid.live/tiktok-dat-web')) {
        console.log('vao');
        try {
            // Lấy link redirect cuối cùng và mở tab mới
            fetch('/resolve-redirect', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ url: link })
                })
                .then(res => res.json())
                .then(data => {
                if (data.final_url) {
                    console.log(data.final_url);
                    window.location.href = data.final_url;
                } else {
                    window.location.href = link;
                }
            }).catch(e => {
                console.error('Lỗi khi lấy redirect:', e);
                alert('Không thể chuyển hướng do lỗi bảo mật trình duyệt hoặc server không cho phép!');
                window.location.href = link;
            });
        } catch (e) {
            // Nếu lỗi, mở link gốc
            console.error('Lỗi khi lấy redirect:', e);
                alert('Không thể chuyển hướng do lỗi bảo mật trình duyệt hoặc server không cho phép!');
            window.open(link, '_blank');
        }
        return;
    }
}
</script>

