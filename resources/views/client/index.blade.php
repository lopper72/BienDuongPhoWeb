@extends('client.layouts.master')

@section('title', 'Trang chủ')

@section('content')
    <div class="slidePage" style="background-image: url({{asset('library/images/slide-banner.jpg')}});">
        <div class="container">
            <div class="slideContent">
                <div class="titleIndex">Truyện đề cử</div>
                <div class="mySlide">
                    @foreach ($top_products as $key => $top_product)
                            @php
                                $chaps = DB::select('
                                    SELECT *
                                    FROM product_detail
                                    WHERE product_id = '.$top_product->id.'
                                    ORDER BY number_order DESC
                                    LIMIT 2
                                ');
                            @endphp
                        <div class="itemSlide">
                            <div class="item">
                                <div class="itemImage">
                                    @if ($top_product->is_full == 1)
                                        <span>Full</span>
                                    @endif
                                    <a href="{{route('truyen_chitiet',$top_product->slug)}}"></a>
                                    <img src="{{asset('storage/images/products/' . $top_product->image)}}" alt="{{$top_product->name}}" class="object-fit-cover w-100 h-100">
                                </div>
                                <div class="itemContent">
                                    <h4 class="itemTitle"><a href="{{route('truyen_chitiet',$top_product->slug)}}">{{$top_product->name}}</a></h4>
                                    <p class="itemDate">{{date('d/m/Y', strtotime($top_product->created_at))}}</p>
                                    @foreach ($chaps as $k => $chap)
                                        <div class="itemChap mt-2">
                                            <a href="{{route('chap',[$top_product->slug,$chap->number_order])}}">{{$chap->title}}</a>
                                            @if ($k == 0)
                                                <span class="iconNew">New</span>
                                            @else
                                                <span>{{date('d/m/Y', strtotime($chap->created_at))}}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="indexPage">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12 mb-4 mb-lg-0">
                    @if ($history != "" && count($history))
                        <div class="titleIndex2"><i class="fa-solid fa-star"></i><span>Truyện đã đọc</span></div>
                        <div class="itemHistory">
                        @php
                            $current_date = new DateTime();
                        @endphp
                            @foreach ($history as $key => $item)
                            @php
                                $created_date = new DateTime($item->updated_at);
                                $interval = $created_date->diff($current_date);
                                $total_seconds = ($interval->d * 24 * 3600) + ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
                                $hours = floor($total_seconds / 3600);
                                $minutes = floor(($total_seconds % 3600) / 60);
                                $seconds = $total_seconds % 60;
                                $textDate = "";
                                if(round($hours) == 0){
                                    if (round($minutes) == 0) {
                                        $textDate = round($seconds)." giây trước";
                                    }else{
                                        $textDate = round($minutes)." phút trước";
                                    }
                                }else{
                                    $textDate = round($hours)." giờ trước";
                                    if(round($hours) > 59){
                                        $textDate = $interval->d ." ngày trước";
                                    }
                                }
                                $totalChaps = DB::select('
                                    SELECT distinct id
                                    FROM product_detail
                                    WHERE product_id = '.$item->product_id.'
                                ');
                            @endphp
                                <div class="itemHistoryContent" @if (($key+1)%2 == 0) style="background: #eee" @endif>
                                    <div class="itemHistoryDate">
                                        <span>{{$textDate}}</span>
                                    </div>
                                    <div class="itemHistoryTitle">
                                        <a href="{{route('chap',[$item->products[0]->slug,$item->productDetails[0]->number_order])}}">{{$item->products[0]->name}}</a>
                                    </div>
                                    <div class="itemHistoryDetail">
                                        <b>Đã đọc: {{$item->productDetails[0]->number_order}}/{{count($totalChaps)}}</b>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="titleIndex2"><i class="fa-solid fa-star"></i><span>Mới cập nhật</span></div>
                    <div class="row">
                        @foreach ($new_products as $key => $new_product)
                            @php
                                $chaps = DB::select('
                                    SELECT *
                                    FROM product_detail
                                    WHERE product_id = '.$new_product->id.'
                                    ORDER BY number_order DESC
                                    LIMIT 2
                                ');
                                $rates = DB::select('
                                    SELECT avg(rate) as total_rate, product_id
                                    FROM rates
                                    WHERE product_id = '.$new_product->id.'
                                    GROUP BY product_id
                                ');
                            @endphp
                            <div class="col-12 col-md-6">
                                <div class="item py-3 borderItem" style="border-bottom: 1px solid #ebebeb;">
                                    <div class="itemImage">
                                        @if ($new_product->is_full == 1)
                                            <span>Full</span>
                                        @endif
                                        <a href="{{route('truyen_chitiet',$new_product->slug)}}"></a>
                                        <img src="{{asset('storage/images/products/' . $new_product->image)}}" alt="{{$new_product->name}}" class="object-fit-cover w-100 h-100">
                                    </div>
                                    <div class="itemContent">
                                        <h4 class="itemTitle"><a href="{{route('truyen_chitiet',$new_product->slug)}}">{{$new_product->name}}</a></h4>
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
                                                <a href="{{route('chap',[$new_product->slug,$chap->number_order])}}">{{$chap->title}}</a>
                                                @if ($k == 0)
                                                    <span class="iconNew">New</span>
                                                @else
                                                    <span>{{date('d/m/Y', strtotime($chap->created_at))}}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a class="btnViewMore2" href="{{route('truyen')}}">Xem thêm</a>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <form action="{{route('search')}}">
                        @livewire('client.search-index')
                    </form>
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
                    <div class="hotItem">
                        <div class="titleIndex">Thể loại</div>
                        <div class="categoryItem">
                            @foreach ($brands as $key => $brand)
                                <a href="{{route('the_loai',$brand->slug)}}">{{$brand->name}}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection