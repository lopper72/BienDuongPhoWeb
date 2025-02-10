@if ($paginator->hasPages())
    <div class="pagination">
        @if (!$paginator->onFirstPage())
            <a href="{{$elements[0][1]}}"><i class="fa-solid fa-angle-left"></i></a>
        @endif
        @foreach ($elements as $element)
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a href="{{$url}}" class="active">{{$page}}</a>
                    @else
                        <a href="{{$url}}">{{$page}}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
            <a href="{{$elements[0][count($elements[0])]}}"><i class="fa-solid fa-angle-right"></i></a>
        @endif
    </div>
@endif