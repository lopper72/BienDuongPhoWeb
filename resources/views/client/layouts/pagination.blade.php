@if ($paginator->hasPages())
    @php
        $prevPage = $paginator->currentPage()-1;
        $nextPage = $paginator->currentPage()+1;
        if($prevPage == 0){
            $prevPage = 1;
        }
    @endphp
    <div class="paginationPage">
        <div class="text-start">
            @if (!$paginator->onFirstPage())
                <a href="{{$elements[0][$prevPage]}}"><i class="fa-solid fa-arrow-left"></i> Trang trước</a>
            @endif
        </div>
        <div class="text-end">
            @if ($paginator->hasMorePages())
                <a href="{{$elements[0][$nextPage]}}">Trang sau <i class="fa-solid fa-arrow-right"></i></i></a>
            @endif
        </div>
        
    </div>
@endif