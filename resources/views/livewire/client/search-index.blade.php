<div class="formSearchIndex">
    <input class="fmiSearchIndex" wire:model='inputSearchIndex' wire:keydown='search' type="text" name="inputSearchIndex" placeholder="Tìm kiếm Bài Đăng">
    <i class="fa fa-search" aria-hidden="true"></i>
    @if ($product_search != "")
        <div class="searchResultIndex">
            @if (count($product_search) == 0)
                <span>Bài Đăng không tồn tại</span>
            @endif
            @foreach ($product_search as $item)
                <a href="{{route('truyen_chitiet',$item->slug)}}">{{$item->name}}</a>
            @endforeach
        </div>
    @endif
</div>