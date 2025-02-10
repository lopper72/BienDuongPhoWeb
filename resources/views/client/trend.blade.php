@extends('client.layouts.master')

@section('title', 'Xu Hướng')

@section('content')
    <div class="slidePage" style="background-image: url({{ asset('library/images/slide-banner.jpg') }});">
        <div class="container">
            <div class="slideContent">
                <div class="titleIndex">Bài Đăng đề cử</div>
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
    <div class="container mt-4">
        <div class="row">
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
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="item py-3 borderItem" style="border-bottom: 1px solid #ebebeb;">
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
                </div>
            @endforeach
            {{$trend_products->links('client.layouts.pagination')}}
        </div>
    </div>
@endsection