@extends('client.layouts.master')

@section('title', 'Bài Đăng đã đánh dấu')

@section('content')
    <div class="container">
        <div class="orderPage">
            <div class="titleIndex2">
                <i class="fa-solid fa-star"></i><span>Bài Đăng đã đánh dấu</span>
            </div>
        </div>
        <div class="row">
            @foreach ($bookmarks as $bookmark)
                @foreach ($bookmark->products as $key => $product)
                    @php
                        $chaps = DB::select('
                            SELECT *
                            FROM product_detail
                            WHERE product_id = '.$product->id.'
                            ORDER BY number_order DESC
                            LIMIT 2
                        ');
                        $rates = DB::select('
                            SELECT avg(rate) as total_rate, product_id
                            FROM rates
                            WHERE product_id = '.$product->id.'
                            GROUP BY product_id
                        ');
                    @endphp
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="item py-3 borderItem" style="border-bottom: 1px solid #ebebeb;">
                            <div class="itemImage">
                                @if ($product->is_full == 1)
                                    <span>Full</span>
                                @endif
                                <a href="{{route('truyen_chitiet',$product->slug)}}"></a>
                                <img src="{{asset('storage/images/products/' . $product->image)}}" alt="{{$product->name}}" class="object-fit-cover w-100 h-100">
                            </div>
                            <div class="itemContent">
                                <h4 class="itemTitle"><a href="{{route('truyen_chitiet',$product->slug)}}">{{$product->name}}</a></h4>
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
                                        <a href="{{route('chap',[$product->slug,$chap->number_order])}}">{{$chap->title}}</a>
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
            @endforeach
            {{$bookmarks->links('client.layouts.pagination')}}
        </div>
    </div>
@endsection