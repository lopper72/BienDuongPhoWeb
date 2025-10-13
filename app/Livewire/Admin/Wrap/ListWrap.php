<?php

namespace App\Livewire\Admin\Wrap;

use Livewire\Component;
use App\Models\WrapLink;
use App\Models\Product;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ListWrap extends Component
{
    use WithPagination, WithoutUrlPagination; 

    public $search_input = '';
    public $list_wraplink = [];
    public $selected_index = [];

    public function search()
    {
        $this->resetPage();
    }

    public function deleteListCheckbox()
    {
        $list_has_product = [];
        foreach ($this->selected_index as $key => $checked) {
            if($checked == true){
                $wraplink_id = $this->list_wraplink[$key]['id'];
                $wraplink = WrapLink::find($wraplink_id);
                $checkProduct = Product::where('wraplink_id','=',$wraplink_id)->first();
                if($checkProduct != null){
                    array_push($list_has_product, $wraplink->name);
                }else{
                    $wraplink->delete();
                }
            }
        }
        $this->selected_index = [];
        if(count($list_has_product) > 0){
            $list_has_product = implode(', ', $list_has_product);
            $this->dispatch('error', ['error' => 'Thể Loại <b>'.$list_has_product.'</b> đã có Bài Đăng, vì vậy không thể xóa.']);
        }
        $this->render();
    }

public function deleteWrapLink($id){
        $wraplink = WrapLink::find($id);
        $checkProduct = Product::where('id','=',$id)->first();
        if($checkProduct != null){
                $this->dispatch('error', ['error' => 'Thể Loại <b>'.$wraplink->name.'</b> đã có Bài Đăng, vì vậy không thể xóa.']);
                return;
        }else{
                // Xóa ảnh nếu có
                if ($wraplink && $wraplink->image) {
                        $imagePath = public_path('uploads/wraplink/' . $wraplink->image);
                        if (file_exists($imagePath)) {
                                @unlink($imagePath);
                        }
                }
                $wraplink->delete();
        }
}

    public function handleDetele($id)
    {
        $this->deleteWrapLink($id);
        //$this->mount();
    }
    
    public function render()
    {
        if($this->search_input == ''){
            $wraplinks = WrapLink::orderBy('id', 'desc')->paginate(10);
        }else{
            $wraplinks = WrapLink::where('name', 'like', '%'.$this->search_input.'%')
                ->orWhere('description', 'like', '%'.$this->search_input.'%')
                ->orderBy('id', 'desc')
                ->paginate(10);
        }
        $this->list_wraplink = collect($wraplinks->items());
        return view('livewire.admin.wrap.list-wrap', ['wraplinks' => $wraplinks]);
    }
}
