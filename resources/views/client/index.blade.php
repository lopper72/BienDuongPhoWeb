@extends('client.layouts.master')

@section('title', 'Trang chủ')

@section('content')
    <div class="container">
        <div class="row">
            @foreach ($products as $item)
                @php
                    $html = $item->description;
                    $dom = new DOMDocument();
                    libxml_use_internal_errors(true);
                    $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
                    $figures = $dom->getElementsByTagName('figure');
                    foreach ($figures as $figure) {
                        $figure->parentNode->removeChild($figure);
                    }
                    $hrefs = $dom->getElementsByTagName('a');
                    foreach ($hrefs as $href) {
                        $href->parentNode->removeChild($href);
                    }
                    $imgs = $dom->getElementsByTagName('img');
                    foreach ($imgs as $img) {
                        $img->parentNode->removeChild($img);
                    }
                    $pTags = $dom->getElementsByTagName('p');
                    $cleanHtml = '';
                    foreach ($pTags as $p) {
                        $cleanHtml .= $dom->saveHTML($p);
                    }
                @endphp
                <div class="col-lg-4 col-md-6 col-12 mb-lg-5 mb-4">
                    <div class="item">
                        <h3 class="itemTitle"><a href="{{route('blog',$item->slug);}}">{{$item->name}}</a></h3>
                        <div class="itemContent">
                            @php
                                if($cleanHtml != ""){
                                    $shortenedString = substr($cleanHtml, 0, 330);
                                    if (strlen($cleanHtml) > 330) {
                                        $shortenedString .= '...';
                                    }
                                    echo $shortenedString;
                                }
                            @endphp
                        </div>
                        <div class="itemDate">Ngày đăng: {{date('d/m/Y', strtotime($item->created_at))}}</div>
                    </div>
                </div>
            @endforeach
            {{$products->links('client.layouts.pagination')}}
        </div>
    </div>
@endsection