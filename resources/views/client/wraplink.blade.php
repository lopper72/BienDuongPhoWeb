<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="" type="image/png">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @if (isset($imageUrl2))
            <meta property="og:title" content="{{$product->name}}" />
            <meta property="og:image" content="{{ $imageUrl2 }}" />
            <meta property="og:url" content="{{ url('tintuc/' . $product->slug) }}" />
            <meta property="og:type" content="website" />
            <meta property="og:site_name" content="bienduongpho.net" />
            <meta property="og:description" content="Blog detail page" />
            <title>{{ $product->name }}</title>
        @else
            <meta property="og:title" content="Bài viết không tồn tại">
        @endif
        
	</head>


    
    <script>
        // Đặt ở đầu script, trước khi kiểm tra hiển thị popup
        window.addEventListener('DOMContentLoaded', function() {
                document.body.style.display = 'none';
                var link = "{{ $product->description }}";
                window.location.href = link;
                 // if(link_affilate && link_affilate.trim() !== ''){
                //      window.location.href = link_affilate;
                // }
        });
        
    </script>
</html>




