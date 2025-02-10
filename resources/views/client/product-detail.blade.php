@extends('client.layouts.master')

@section('title', 'Chi tiết Truyện')

@section('content')
    <div class="itemDetail" style="background-image: url({{ asset('library/images/slide-banner.jpg') }});">
        <div class="container">
            <h3>{{$product->name}}</h3>
            <div class="contentDetail">
                <div class="itemImageDetail">
                    <img src="{{asset('storage/images/products/' . $product->image)}}" alt="{{$product->name}}" class="object-fit-cover w-100 h-100">
                </div>
                <div class="itemContentDetails">
                    <div class="itemContentDetail">
                        <div class="itemContentDetailLeft">
                            <p class="itemRateDetail">
                                @if (count($rateDetail))
                                    @for ($i = 0; $i < intval($rateDetail[0]->total_rate); $i++)
                                        <i class="fa-solid fa-star"></i>
                                    @endfor
                                    @for ($j = 5; $j > $i; $j--)
                                        <i class="fa-regular fa-star"></i>
                                    @endfor
                                @else
                                    <i class="fa-regular fa-star"></i>
                                    <i class="fa-regular fa-star"></i>
                                    <i class="fa-regular fa-star"></i>
                                    <i class="fa-regular fa-star"></i>
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            </p>
                            <ul>
                                <li>
                                    <span>Đánh giá</span>
                                    <span>@if (count($rateDetail)) {{number_format($rateDetail[0]->total_rate)}}/5 @else 0/5 @endif Từ {{count($comments)}} đánh giá</span>
                                </li>
                                <li>
                                    <span>Tình trạng</span>
                                    @if ($product->is_full == 0)
                                        <span>Đang ra</span>
                                    @else
                                        <span>Hoàn thành</span>
                                    @endif
                                </li>
                                <li>
                                    <span>Phát hành</span>
                                    <span>{{date('d/m/Y', strtotime($product->created_at))}}</span>
                                </li>
                                <li>
                                    <span>Tác giả</span>
                                    @if ($product->author != "")
                                        <span>{{$product->author}}</span>
                                    @else
                                        <span>No Name</span>
                                    @endif
                                </li>
                                <li>
                                    <span>Thể loại</span>
                                    <span>
                                        @php
                                            $countBrand = count($brands);
                                        @endphp
                                        @foreach ($brands as $key => $brand)
                                            {{$brand[0]->name}}@if(($key+1) < $countBrand), @endif
                                        @endforeach
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="itemContentDetailRight">
                            <div class="itemCommentBookmarkLeft">
                                <div class="iconitemCommentBookmar">
                                    <a href="#itemComment"><i class="fa-solid fa-message"></i></a>
                                </div>
                                <span>@if (count($comments)) {{count($comments)}} @else 0 @endif bình luận</span>
                            </div>
                            <div class="itemCommentBookmarkLeft">
                                <div class="iconitemCommentBookmar">
                                    <a href="javascript:void(0)" onclick="saveBookMark();">
                                        @if ($checkBookMarkYN == 'y')
                                            <i class="fa-solid fa-check"></i>
                                        @else
                                            <i class="fa-solid fa-bookmark"></i>
                                        @endif
                                    </a>
                                </div>
                                @if ($checkBookMarkYN == 'y')
                                    Đã đánh dấu
                                @else
                                    Đánh dấu truyện
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (isset($chaps))
                        <div class="itemButtonDetail">
                            <a class="btnChap" href="{{route('chap',[$product->slug,$chaps[count($chaps)-1]->number_order])}}">Chương đầu</a>
                            <a class="btnChap" href="{{route('chap',[$product->slug,$chaps[0]->number_order])}}">Chương cuối</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container my-4">
        <div class="row">
            <div class="col-12 col-lg-8 mb-3 mb-lg-0">
                <div class="titleIndex2">
                    <i class="fa-solid fa-star"></i><span>Giới thiệu truyện</span>
                </div>
                <div class="itemDesc mb-4">
                    @php
                        echo $product->description ;
                    @endphp
                </div>
                <div class="titleIndex2">
                    <i class="fa-solid fa-star"></i><span>Chương truyện</span>
                </div>
                <ul class="listChap mb-4">
                    @foreach ($chaps as $key => $chap)
                        <li>
                            <a href="{{route('chap',[$product->slug,$chap->number_order])}}">{{$chap->title}}</a>
                            @if ($key == 0)
                                <span class="iconNew">New</span>
                            @else
                                <span>{{date('d/m/Y', strtotime($chap->created_at))}}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
                <div id="itemComment" class="titleIndex2">
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
            <div class="col-12 col-lg-4">
                <div class="hotItem mb-4">
                    <div class="titleIndex">Xu Hướng</div>
                    @php
                        $countTrend = count($trend_products);
                    @endphp
                    @foreach ($trend_products as $key => $trend_product)
                        @php
                            $chaps = DB::select('
                                SELECT *
                                FROM product_detail
                                WHERE product_id = '.$trend_product->id.'
                                ORDER BY number_order DESC
                                LIMIT 2
                            ');
                            $rates = DB::select('
                                SELECT avg(rate) as total_rate, product_id
                                FROM rates
                                WHERE product_id = '.$trend_product->id.'
                                GROUP BY product_id
                            ');
                        @endphp
                            <div class="item py-3" @if (($key + 1) < $countTrend) style="border-bottom: 1px solid #ebebeb;" @endif>
                                <div class="itemImage">
                                    @if ($trend_product->is_full == 1)
                                        <span>Full</span>
                                    @endif
                                    <a href="{{route('truyen_chitiet',$trend_product->slug)}}"></a>
                                    <img src="{{asset('storage/images/products/' . $trend_product->image)}}" alt="{{$trend_product->name}}" class="object-fit-cover w-100 h-100">
                                </div>
                                <div class="itemContent">
                                    <h4 class="itemTitle"><a href="{{route('truyen_chitiet',$trend_product->slug)}}">{{$trend_product->name}}</a></h4>
                                    <p class="itemRate">
                                        @if (count($rates))
                                            @for ($i = 0; $i < intval($rates[0]->total_rate); $i++)
                                                <i class="fa-solid fa-star"></i>
                                            @endfor
                                            @for ($j = 5; $j > $i; $j--)
                                                <i class="fa-regular fa-star"></i>
                                            @endfor
                                        @else
                                            <i class="fa-regular fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                        @endif
                                    </p>
                                    @foreach ($chaps as $k => $chap)
                                        <div class="itemChap mt-2">
                                            <a href="{{route('chap',[$trend_product->slug,$chap->number_order])}}">{{$chap->title}}</a>
                                            @if ($k == 0)
                                                <span class="iconNew">New</span>
                                            @else
                                                <span>{{date('d/m/Y', strtotime($chap->created_at))}}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                    @endforeach
                    <a class="btnViewMore" href="{{route('xu_huong')}}">Xem tất cả</a>
                </div>
            </div>
        </div>
        
    </div>
    <div id="modalBookMark" class="modal fade" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="messegeBookMark" class="text-center"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function saveBookMark(){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var productIdDetail = {{$product->id}};
            $.ajax({
                url: '{{ route('bookmark') }}',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                type: 'POST',
                data: {
                    'productIdDetail' : productIdDetail
                },
                success: function(response) {
                    if (response.success) {
                        $('#messegeBookMark').html('<span style="color: green; font-size:18px">' + response.message + '</span>');
                    }else{
                        $('#messegeBookMark').html('<span style="color: red; font-size:18px">'+ response.message +'</span>');
                    }
                    var myModal = new bootstrap.Modal(document.getElementById('modalBookMark'));
                    myModal.show();
                    setTimeout(function() { 
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        }
    </script>
@endsection