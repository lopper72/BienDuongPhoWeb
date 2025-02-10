<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Product;

class SearchIndex extends Component
{
    public $inputSearchIndex = '';

    public function search()
    {
        $this->render();
    }

    public function closeSearch()
    {
        $this->inputSearchIndex = "";
        $this->render();
    }
    
    public function render()
    {
        $product_search = "";
        if($this->inputSearchIndex != ''){
            $product_search =  Product::where('name', 'like', '%'.$this->inputSearchIndex.'%')
                ->where(function($query) {
                    $query->where('is_active', '=', '1')->orWhereNull('is_active');
                })
                ->orderBy('name','asc')->limit(10)->get();
        }
        return view('livewire.client.search-index', ["product_search" => $product_search]);
    }
}
