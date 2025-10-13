<!DOCTYPE html>
<html lang="en">
    @section('content')
        @php
            $redirectLink = $product->description != "" && filter_var($product->description, FILTER_VALIDATE_URL) && strpos($product->description, "http") === 0 ;
        @endphp
        
        <div class="container mb-4">
            <input type="hidden" id='link_affilate' value="{{$product->description}}">
        </div>
        
    @endsection

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @if (isset($imageUrl2))
            <meta property="og:title" content="{{$product->name}}" />
            <meta property="og:image" content="{{ $imageUrl2 }}" />
            <meta property="og:url" content="{{route('blog',$product->slug);}}" />
            <meta property="og:type" content="website" />
            <meta property="og:site_name" content="bienduongpho.net" />
            <meta property="og:description" content="Blog detail page" />
            <title>{{ $product->name }}</title>
        @else
            <meta property="og:title" content="Bài viết không tồn tại">
        @endif
        
	</head>
	<body>
            @yield('content')
    </body>

    
    <script>
        // Đặt ở đầu script, trước khi kiểm tra hiển thị popup
        window.addEventListener('DOMContentLoaded', function() {
                document.body.style.display = 'none';
                link_affilate = document.getElementById('link_affilate').value;
                 // if(link_affilate && link_affilate.trim() !== ''){
                //      window.location.href = link_affilate;
                // }
        });
        
    </script>
</html>




