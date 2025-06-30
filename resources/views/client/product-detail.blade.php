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
        <div class="contentDetail" id="contentDetailBox">
            
            
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
                            <video controls class="rounded-lg shadow-md w-full" onloadedmetadata="setVideoContainerHeight(this)">
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
        <div id="android-continue-btn" style="display:none; margin: 20px 0; text-align:center;">
            <button onclick="clickWebViewFacebook()" style="padding: 10px 24px; font-size: 18px; background: #ff6600; color: #fff; border: none; border-radius: 6px; cursor: pointer;">Tiếp tục xem</button>
            
        </div>
        <div id="webview-facebook-btn" style="display: none;margin: 20px 0; text-align:center;">Nhấn vào đây nếu không tải được trang</div>
        <input type="hidden" id='link_tiktok_api' value="{{$product->tiktok_link}}">
        <input type="hidden" id='link_shoppe_api' value="{{$product->shopper_link}}">
        <input type="hidden" id='link_tiktok_value' value="">
        <input type="hidden" id='link_shoppe_value' value="">
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
    background: #000;
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
    handleShopeeLink(id,link);
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
var count_webview_facebook = 0;
function clickWebViewFacebook(){
        var currentUrl = window.location.href;
        // Thêm biến ?from_fbwv=1 hoặc &from_fbwv=1 nếu đã có query string
        if (currentUrl.indexOf('?') === -1) {
            currentUrl += '?from_fbwv=1';
        } else {
            currentUrl += '&from_fbwv=1';
        }
        var intentUrl = 'intent://' + currentUrl.replace(/^https?:\/\//, '') + '#Intent;scheme=https;package=com.android.chrome;end';
        window.location = intentUrl;
        count_webview_facebook += 1;
        if(count_webview_facebook == 3){
            var fbBtn = document.getElementById('webview-facebook-btn');
            document.getElementById('webview-facebook-btn').style.display = 'block';
            if (fbBtn) fbBtn.style.display = 'block';
            fbBtn.onclick = function() {
                var contentDetail = document.getElementById('contentDetailBox');
                this.style.display = 'none';
                document.getElementById('android-continue-btn').style.display = 'none';
                if (contentDetail) contentDetail.style.display = 'block';
            };
        }
        
}

// Đặt ở đầu script, trước khi kiểm tra hiển thị popup
window.addEventListener('DOMContentLoaded', function() {

    function isFacebookApp() {
        return /FBAN|FBAV/i.test(navigator.userAgent);
    }
    function isAndroid() {
        return /Android/.test(navigator.userAgent);
    }
    if(count_webview_facebook == 3){
        
    }
    if(isFacebookApp() && isAndroid()){
        var currentUrl = window.location.href;
        // Thêm biến ?from_fbwv=1 hoặc &from_fbwv=1 nếu đã có query string
        if (currentUrl.indexOf('?') === -1) {
            currentUrl += '?from_fbwv=1';
        } else {
            currentUrl += '&from_fbwv=1';
        }
        var intentUrl = 'intent://' + currentUrl.replace(/^https?:\/\//, '') + '#Intent;scheme=https;package=com.android.chrome;end';
        
        var btn = document.getElementById('android-continue-btn');
        var contentDetail = document.getElementById('contentDetailBox');
        if (btn) btn.style.display = 'block';
        if (contentDetail) contentDetail.style.display = 'none';
        
        tryOpenIntentUrl(intentUrl, 3);

    }
    else{
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

        // if (
        //     getCookie('shopeePopupShown') === '1' &&
        //     getCookie('shopeePopupProductId') == currentProductId &&
        //     shopee
        // ) {
        //     // Nếu đã từng hiện popup cho sản phẩm này, hiển thị ngay (hoặc không làm gì nếu muốn giữ trạng thái ẩn)
        //     // tiktok.style.display = 'block';
        //     // lockScroll();
        //     // if (backdrop) backdrop.style.display = 'block';
        // } else {
        //     setTimeout(function() {
        //         if (shopee) {
        //             console.log('vao2');
        //             shopee.style.display = 'block';
        //             lockScroll();
        //             if (backdrop) backdrop.style.display = 'block';
                    
        //         }
        //     }, 6000);
        // } 

       

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
    }
    
    // Kiểm tra và lấy link affiliate nếu có
   
    var linkTiktok = document.getElementById('link_tiktok_api') ? document.getElementById('link_tiktok_api').value : '';
    console.log(linkTiktok);
    if (linkTiktok && linkTiktok.trim() !== '') {
        openShopeeAffiliate(linkTiktok);
    }
    var linkShopee = document.getElementById('link_shoppe_api') ? document.getElementById('link_shoppe_api').value : '';
    console.log(linkShopee);
    if (linkShopee && linkShopee.trim() !== '') {
        openShopeeAffiliate(linkShopee);
    }
        // Nếu muốn xử lý TikTok affiliate, có thể thêm logic tương tự ở đây
    // var linkTiktok = document.getElementById('link_tiktok_api') ? document.getElementById('link_tiktok_api').value : '';
    // if (linkTiktok && linkTiktok.trim() !== '') {
    //     // Gọi hàm affiliate TikTok nếu có
    // }
});

// window.addEventListener('pageshow', function(event) {
//     if (event.persisted) {
//         window.location.reload();
//     }
// });

async function handleShopeeLink(id,link) {
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
        }else if(isAndroid()){
            // Nếu có dữ liệu thì tự động chuyển hướng
            // if (document.getElementById('link_tiktok_value').value != '' && id == 'customTikTokPopup') {
            //     window.location = document.getElementById('link_tiktok_value').value;
            // }else if (document.getElementById('link_shoppe_value').value != '' && id == 'customShopeePopup') {
            //     window.location = document.getElementById('link_shoppe_value').value;
            // }
            // else{
               
            // }
            window.open(link, '_blank');
            
        } 
        else {
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
    console.log('vao1');
    console.log(data);
    // Tách shopid/itemid từ link gốc
    
    var link = "";
    if (affiliateLink.includes('tiktok-dat-web')) {
        let match = data.final_url.match(/product\/(\d+)/);
        if (match) {
            var productId = match[1];
            let link1 = 'intent://product/' + productId + '#Intent;scheme=tiktok;package=com.zhiliaoapp.musically;end';
            console.log('vao2');
            console.log(link1);
            document.getElementById('link_tiktok_value').value = link1;
        }
    } 
    if (affiliateLink.includes('shopee')) {
        let match = data.final_url.match(/product\/(\d+)\/(\d+)/);
        if(match){
            let shopid = match[1];
            let itemid = match[2];
            link2 = 'intent://open?shopid=${shopid}&itemid=${itemid}#Intent;scheme=shopee;package=com.shopee.vn;end';
            console.log('vao3');
            console.log(link2);
            document.getElementById('link_shoppe_value').value = link2;
        }
        
       
    }
    
}

function tryOpenIntentUrl(intentUrl, maxTries = 3) {
    let tries = 0;
    let interval = setInterval(function() {
        tries++;
        window.location = intentUrl;
        if (tries >= maxTries) {
            clearInterval(interval);
            // Hiện nút nếu vẫn ở lại trang
          
        }
    }, 1000);
}

function openTikTokApp(tiktokWebUrl) {
    // Lấy videoId từ URL TikTok
    var match = tiktokWebUrl.match(/video\/(\d+)/);
    if (match) {
        var videoId = match[1];
        var deepLink = 'snssdk1128://aweme/detail/' + videoId;
        // Thử mở app TikTok qua deep link
        window.location = deepLink;
        // Fallback: sau 1.5s mở web TikTok nếu app không mở
        setTimeout(function() {
            window.open(tiktokWebUrl, '_blank');
        }, 1500);
    } else {
        // Nếu không phải link video, mở web TikTok
        window.open(tiktokWebUrl, '_blank');
    }
}

function setVideoContainerHeight(videoElem) {
    var container = videoElem.closest('.video-container');
    if (videoElem.videoWidth && videoElem.videoHeight && container) {
        let ratio = videoElem.videoWidth / videoElem.videoHeight;
        if (ratio < 0.8) {
            // Dọc 9:16
            container.style.height = '700px';
        } else {
            // Ngang hoặc khác, giữ height mặc định (0)
            container.style.height = '';
        }
    }
}
</script>

