@extends('client.layouts.master')

@section('title', 'Trang chủ')

@section('content')
    <div class="container mb-4">
        <div class="row">
            @foreach ($products as $item)
                @php
                    $cleanHtml = '';
                    $html = $item->description;
                    if ($html != '') {
                        $dom = new DOMDocument();
                        libxml_use_internal_errors(true);
                        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
                        $figures = $dom->getElementsByTagName('figure');
                        foreach ($figures as $figure) {
                            $figure->parentNode->removeChild($figure);
                        }
                        $imgs = $dom->getElementsByTagName('img');
                        foreach ($imgs as $img) {
                            $img->parentNode->removeChild($img);
                        }
                        $pTags = $dom->getElementsByTagName('p');
                        
                        foreach ($pTags as $p) {
                            $cleanHtml .= $dom->saveHTML($p);
                        }
                        $cleanHtml = str_replace('<a', '<span', $cleanHtml);
                        $cleanHtml = str_replace('</a>', '</span>', $cleanHtml);
                        $cleanHtml = str_replace('<b>', '', $cleanHtml);
                        $cleanHtml = str_replace('</b>', '', $cleanHtml);
                        $cleanHtml = str_replace('<strong>', '', $cleanHtml);
                        $cleanHtml = str_replace('</strong>', '', $cleanHtml);
                    }
                @endphp
                <div class="col-lg-4 col-md-6 col-12 mb-lg-5 mb-4">
                    <div class="item">
                        <h3 class="itemTitle"><a href="{{route('blog',$item->slug);}}">{{$item->name}}</a></h3>
                        <div class="itemContent">
                            @php
                                if($cleanHtml != ""){
                                    echo $cleanHtml;
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