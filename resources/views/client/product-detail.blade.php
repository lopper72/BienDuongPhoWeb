@extends('client.layouts.master')

@section('title')
    {{ $product->name }}
@endsection

@section('content')
 
    @php
        $showTikTok = $product->tiktok_link != "" && filter_var($product->tiktok_link, FILTER_VALIDATE_URL) && strpos($product->tiktok_link, "http") === 0 ;
        $showShopee = $product->shopper_link != "" && filter_var($product->shopper_link, FILTER_VALIDATE_URL) && strpos($product->shopper_link, "http") === 0 ;
    @endphp
    @if ($showTikTok || $showShopee)
        <div id="customBackdrop" class="custom-backdrop" style="display:none;"></div>
    @endif
    @if ($showTikTok)
        <div id="customTikTokPopup" class="custom-popup" style="top: 50%; left: 50%; transform: translate(-50%, -50%); display:none; z-index: 2001;">
            <a href="javascript:void(0);" class="close-btn" onclick="unlockPageTikTok('customTikTokPopup','{{$product->tiktok_link}}')">&times;</a>
            <div style="text-align:center;">
                <a href="javascript:void(0);" onclick="unlockPageTikTok('customTikTokPopup','{{$product->tiktok_link}}')" >
                    <img src="{{asset('library/images/shoppe.jpeg')}}" alt="TikTok" style="width:200px;">
                </a>
            </div>
        </div>
    @endif
    @if ($showShopee)
        <div id="customShopeePopup" class="custom-popup" style="top: 50%; left: 50%; transform: translate(-50%, -50%); display:none; z-index: 2000;">
            <a href="javascript:void(0);" class="close-btn" onclick="unlockPageTikTok('customShopeePopup','{{$product->shopper_link}}')">&times;</a>
            <div style="text-align:center;">
                <a  href="javascript:void(0);" onclick="unlockPageTikTok('customShopeePopup','{{$product->shopper_link}}')" >
                    <img src="{{asset('library/images/shoppe2.jpeg')}}" alt="Shopee" style="width:200px;">
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
let isScrollLocked = false;

function lockScroll() {
    if (!isScrollLocked) {
        scrollPosition = window.scrollY || window.pageYOffset;
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollPosition}px`;
        document.body.style.left = '0';
        document.body.style.right = '0';
        document.body.style.width = '100%';
        isScrollLocked = true;
        document.body.classList.add('noscroll');
        document.documentElement.classList.add('noscroll');
    }
}

function unlockScroll() {
    if (isScrollLocked) {
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.left = '';
        document.body.style.right = '';
        document.body.style.width = '';
        document.body.classList.remove('noscroll');
        document.documentElement.classList.remove('noscroll');
        window.scrollTo(0, scrollPosition);
        isScrollLocked = false;
    }
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
            
        },
        error: function (response) {
            console.log(response);
        }
    });
    // Chuyển đổi link Shopee web sang link app nếu có
    checkHideBackdrop(id);
    handleShopeeLink(link);
}

function hidePopup(id) {
    var popup = document.getElementById(id);
    var backdrop = document.getElementById('customBackdrop');
    if (popup) popup.style.display = 'none';
    if (backdrop) backdrop.style.display = 'none';
    unlockScroll();

}

function hideAllPopups() {
    var tiktok = document.getElementById('customTikTokPopup');
    var shopee = document.getElementById('customShopeePopup');
    if (tiktok) tiktok.style.display = 'none';
    if (shopee) shopee.style.display = 'none';
    document.getElementById('customBackdrop').style.display = 'none';
}

function checkHideBackdrop(id) {
    var tiktok = document.getElementById('customTikTokPopup');
    var shopee = document.getElementById('customShopeePopup');
    var backdrop = document.getElementById('customBackdrop');
    var tiktokHidden = !tiktok || tiktok.style.display === 'none';
    var shopeeHidden = !shopee || shopee.style.display === 'none';
    backdrop.style.display = 'none';

    var currentProductId = '{{$product->id}}';
    if(id === 'customTikTokPopup'){
        setCookie('tiktokPopupShown', '1', 1);
        setCookie('tiktokPopupProductId', currentProductId, 1);
    }else{
        setCookie('shopeePopupShown', '1', 1);
        setCookie('shopeePopupProductId', currentProductId, 1);
    }
    console.log(id);
}

// Hàm set cookie
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
// Hàm get cookie
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
// Hàm xóa cookie
function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999; path=/';  
}

// Đặt ở đầu script, trước khi kiểm tra hiển thị popup
window.addEventListener('DOMContentLoaded', function() {
    // Chỉ xóa cookie nếu là lần đầu vào trang (không phải back/forward)
    var navType = window.performance && window.performance.getEntriesByType
        ? (window.performance.getEntriesByType('navigation')[0]?.type)
        : (window.performance && window.performance.navigation ? window.performance.navigation.type : null);

    // navType === 'reload' hoặc 'navigate' là lần đầu vào hoặc reload
    // navType === 'back_forward' là back/forward
    if (navType === 'navigate' || navType === 0 || navType === 'reload' || navType === 1) {
        eraseCookie('tiktokPopupShown');
        eraseCookie('tiktokPopupProductId');
        eraseCookie('shopeePopupShown');
        eraseCookie('shopeePopupProductId');
    }

    var tiktok = document.getElementById('customTikTokPopup');
    var shopee = document.getElementById('customShopeePopup');
    var backdrop = document.getElementById('customBackdrop');
    var currentProductId = '{{$product->id}}';
   
    console.log(getCookie('tiktokPopupShown'));
    console.log(getCookie('tiktokPopupProductId'));
    // Khi load trang, kiểm tra trạng thái popup đã hiển thị cho sản phẩm hiện tại chưa
    if (
        getCookie('tiktokPopupShown') === '1' &&
        getCookie('tiktokPopupProductId') == currentProductId &&
        tiktok
    ) {
        // Nếu đã từng hiện popup cho sản phẩm này, hiển thị ngay (hoặc không làm gì nếu muốn giữ trạng thái ẩn)
        // tiktok.style.display = 'block';
        // lockScroll();
        // if (backdrop) backdrop.style.display = 'block';
    } else {
        setTimeout(function() {
            if (tiktok) {
                tiktok.style.display = 'block';
                lockScroll();
                if (backdrop) backdrop.style.display = 'block';
                
            }
        }, 5000);
    }


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

// window.addEventListener('pageshow', function(event) {
//     if (event.persisted) {
//         window.location.reload();
//     }
// });

async function handleShopeeLink(link) {
    // Loại bỏ ký tự @ đầu nếu có
    link = link.replace(/^@/, '');
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Hàm phát hiện iOS
    function isIOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }
    // Hàm phát hiện Android
    function isAndroid() {
        return /Android/.test(navigator.userAgent);
    }
   
        if (isIOS()) {
            window.open(link, '_blank');
            //openShopeeAffiliate(link);
        } else {
            //openShopeeAffiliate(link);
            window.open(link, '_blank');
            //window.location.href = link;
        }
    
}

async function openShopeeAffiliate(affiliateLink) {
    // Gửi link affiliate lên backend để resolve
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let res = await fetch('/resolve-affiliate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({url: affiliateLink})
    });
    let data = await res.json();
    console.log(data);
    if (!data.final_url) {
        // Nếu không resolve được, mở link affiliate
        window.location.href = affiliateLink;
        return;
    }
    // Tách shopid/itemid từ link gốc
    let match = data.final_url.match(/product\/(\d+)\/(\d+)/);
    if (match) {
        let deepLink = `shopee://open?shopid=${match[1]}&itemid=${match[2]}`;
        // Dùng iframe ẩn để mở deep link, fallback sang affiliate link
        let iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = deepLink;
        document.body.appendChild(iframe);
        setTimeout(function() {
            window.location.href = affiliateLink;
            document.body.removeChild(iframe);
        }, 2000);
    } else {
        // Nếu không tách được, mở link affiliate
        window.location.href = affiliateLink;
    }
}
</script>

