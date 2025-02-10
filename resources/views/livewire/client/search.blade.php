<div class="formSearch" x-data="{ isOpenSearch: false }">
    <input class="fmiSearch" wire:model='input_search' wire:keydown='search' type="text" name="input_search" placeholder="Tìm kiếm Bài Đăng">
    <button class="btnSearch">Tìm kiếm</button>
    @if ($product_search != "")
        <div class="searchResult">
            @if (count($product_search) == 0)
                <span>Bài Đăng không tồn tại</span>
            @endif
            @foreach ($product_search as $item)
                <a href="{{route('truyen_chitiet',$item->slug)}}">{{$item->name}}</a>
            @endforeach
        </div>
    @endif
</div>