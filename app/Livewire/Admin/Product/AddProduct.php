<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductDetail;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class AddProduct extends Component
{
    use WithFileUploads;

    public $product_code;
    public $product_name;
    public $category_id;
    public $brand_id;
    public $product_retail_price = 0;
    public $product_wholesale_price = 0;
    public $product_description;
    public $product_uom = 'Cái';
    public $product_size = '';
    public $product_size_list = [];
    public $product_detail_number = 0;
    public $product_detail_list = [];
    public $product_detail_image = [];
    public $product_detail_image_list = [];
    public $product_detail_title = [];
    public $product_detail_order = [];  
    public $product_detail_short_description = [];
    public $brands;
    public $categories;
    public $is_active = '1';
    public $is_full = '0';
    public $product_source;
    public $product_author;
    public $shopper_link = '';
    public $product_detail_list_category = [];
    public $product_detail_list_brand = [];
    public $selected_categories = [];
    public $selected_brands = [];
    public $keys = [];
    public $videos = [];
    public $existedVideo;
    public $isLoading = false;
    public $product_description2;
    public $tiktok_link = '';

    public function updatedVideos()
    {
        $this->validate([
            'videos.*' => 'required|mimes:mp4,mov,avi,wmv|max:102400', // Giới hạn 100MB cho mỗi video
        ]);
    }
    

    public function mount($brands, $categories)
    {
        $this->brands = $brands;
        $this->categories = $categories;
    }
    protected $rules = [
        'selected_brands' => 'required', // Đặt validation của trường select2
        'selected_categories' => 'required',
    ];

    public function addProductSize(){
        $this->validate([
            'product_size' => 'required'
        ], [
            'product_size.required' => 'Kích thước không được để trống.'
        ]);
        $this->product_size_list[] = ["size" => $this->product_size];
        $this->product_size = '';
    }

    public function removeProductSize($index){
        unset($this->product_size_list[$index]);
        $this->product_size_list = array_values($this->product_size_list);
    }

    public function addProductDetail(){
        $new_product_detail = new ProductDetail();

        $this->product_detail_list[] = $new_product_detail;
        $this->product_detail_number++;
        // $this->product_detail_order[$this->product_detail_number] =$this->product_detail_number; 

        $count =  $this->product_detail_number;
        $this->product_detail_title[$this->product_detail_number-1] = "Chương ".$count;
        if($this->product_detail_number == 2){
            $this->product_detail_order[1] = 2;
        }else{
            $this->product_detail_order[$this->product_detail_number-1] = $this->product_detail_number;
        }
      
        $this->dispatch('reloadjs');
    }

    public function removeProductDetail($index){
        unset($this->product_detail_list[$index]);
        $this->product_detail_list = array_values($this->product_detail_list);

        if(array_key_exists($index, $this->product_detail_image_list)){
            unset($this->product_detail_image_list[$index]);
            $this->product_detail_image_list = array_values($this->product_detail_image_list);
        }
        if(array_key_exists($index, $this->product_detail_short_description)){
            unset($this->product_detail_short_description[$index]);
            $this->product_detail_short_description = array_values($this->product_detail_short_description);
        }

        if(array_key_exists($index, $this->product_detail_title)){
            unset($this->product_detail_title[$index]);
            $this->product_detail_title = array_values($this->product_detail_title);
        }
        if(array_key_exists($index, $this->product_detail_order)){
            unset($this->product_detail_order[$index]);
            $this->product_detail_order = array_values($this->product_detail_order);
        }
        
        $this->product_detail_number--;
    }

    public function removeProductDetailImage($index, $image_index){
        unset($this->product_detail_image_list[$index][$image_index]);
        $this->product_detail_image_list[$index] = array_values($this->product_detail_image_list[$index]);
    }
    public function toggleCategory($categoryId)
    {
        if (in_array($categoryId, $this->product_detail_list_category)) {
            $this->product_detail_list_category = array_diff($this->product_detail_list_category, [$categoryId]); // Bỏ chọn
        } else {
            array_push($this->product_detail_list_category, $categoryId); // Chọn
        }
 
    }

    public function toggleBrand($brandId)
    {
        if (in_array($brandId, $this->product_detail_list_brand)) {
            $this->product_detail_list_brand = array_diff($this->product_detail_list_brand, [$brandId]); // Bỏ chọn
        } else {
            array_push($this->product_detail_list_brand, $brandId); // Chọn
        }
    }

    public function reloadjs(){
        $this->dispatch('reloadjs');
    }
    public function storeProduct(){
        $this->dispatch('reloadjs');
        $validatedData = $this->validate([
            'product_code' => 'required|unique:products,code',
            'product_name' => 'required|unique:products,name',
            //'product_retail_price' => 'required|numeric',
            //'product_wholesale_price' => 'required|numeric',
            //'product_uom' => 'required',
        ], [
            'product_code.required' => 'Mã Bài Đăng là bắt buộc.',
            'product_code.unique' => 'Mã Bài Đăng đã tồn tại.',
            'product_name.required' => 'Tên Bài Đăng là bắt buộc.',
            'product_name.unique' => 'Tên Bài Đăng đã tồn tại.',
            //'product_retail_price.required' => 'Giá bán lẻ là bắt buộc.',
            //'product_retail_price.numeric' => 'Giá bán lẻ phải là số.',
            //'product_wholesale_price.required' => 'Giá bán sỉ là bắt buộc.',
            //'product_wholesale_price.numeric' => 'Giá bán sỉ phải là số.',
            //'product_uom.required' => 'Đơn vị tính là bắt buộc.'
        ]);

        // Initialize an array to hold video names
        $video_names = [];

        if ($this->videos) {
            foreach ($this->videos as $video) {
                $this->validate([
                    'videos.*' => 'mimes:mp4,mov,avi,wmv|max:1024000'
                ], [
                    'videos.*.mimes' => 'Video không đúng định dạng',
                    'videos.*.max' => 'Video không được lớn hơn 100MB'
                ]);
                $video_name = time() . uniqid() . '.' . $video->extension();
                $video->storeAs(path: "public/videos/products", name: $video_name);
                $video_names[] = $video_name; // Add video name to the array
            }
        }
        

        $product = new Product();
        $product->code = $this->product_code;
        $product->name = $this->product_name;
        $product->category_id = $this->category_id;
        $product->brand_id = $this->brand_id;
        $product->retail_price = $this->product_retail_price;
        $product->wholesale_price = $this->product_wholesale_price;
        $product->description = $this->product_description;
        $product->description2 = $this->product_description2;
        $product->slug = Str::of($this->product_name)->slug('-');
        $product->uom = $this->product_uom;
        $product->is_active =  $this->is_active;
        
        $product->is_full = $this->is_full;
        $product->source = $this->product_source;
        $product->author = $this->product_author;
        $product->shopper_link = $this->shopper_link;
        $product->tiktok_link = $this->tiktok_link; 

        $keys = json_encode(array_values($this->selected_brands));
       
        $product->category_ids =  $keys;

        $keys = json_encode(array_values($this->selected_brands));
        $product->brand_ids = $keys;
      
        if (!empty($video_names)) {
            $product->image = json_encode($video_names); // Store as JSON
        }

        $product->save();
       
        // foreach($this->product_size_list as $size){
        //     $product_size = new ProductSize();
        //     $product_size->product_id = $product->id;
        //     $product_size->size = $size['size'];
        //     $product_size->save();
        // }

        // for($i = 0; $i < $this->product_detail_number; $i++){
        //     $this->product_detail_list[$i]->title = $this->product_detail_title[$i];
        //     $this->product_detail_list[$i]->number_order = $this->product_detail_order[$i];
        //     if(array_key_exists($i, $this->product_detail_short_description)){
        //         $this->product_detail_list[$i]->short_description = $this->product_detail_short_description[$i];
        //     }
        //     $this->product_detail_list[$i]->product_id = $product->id;
        //     $this->product_detail_list[$i]->retail_price = $product->retail_price;
        //     $this->product_detail_list[$i]->wholesale_price = $product->wholesale_price;

        //     if(array_key_exists($i, $this->product_detail_image_list)){
        //         $image_list = $this->product_detail_image_list[$i];
        //         $images_store = [];
        //         if(count($image_list) > 0){
        //             foreach($image_list as $image){
        //                 $image_name = time() . uniqid() . '.' . $image->extension();
                        
        //                 $image->storeAs(path: "public\images\products", name: $image_name);
        //                 $images_store[] = $image_name;
        //             }
        //             $this->product_detail_list[$i]->image = json_encode($images_store);
        //         }
        //     }

        //     $this->product_detail_list[$i]->save();
        // }
        session()->flash('message', 'Product has been created successfully!');
        return redirect()->route('admin.products');
    }

    public function generateProductCode(){
        $id_latest = Product::latest('id')->first();
        if($id_latest == null){
            $id_latest = (object) ['id' => 0];
        }   
        $this->product_code = 'PROD-'.str_pad($id_latest->id + 1, 4, '0', STR_PAD_LEFT);
    }

    // public function updated()
    // {
    //     $this->dispatch('reloadjs');
    // }


    
    public function initinalRender(){
        
        
        if($this->product_detail_number == 0){
            $new_product_detail = new ProductDetail();
            $count =  $this->product_detail_number + 1;
            $new_product_detail->title = "Chương ".$count;
            $this->product_detail_title[0] = "Chương ".$count;
            $this->product_detail_order[0] = 1;
            $this->product_detail_list[] = $new_product_detail;
            $this->product_detail_number++;
        }else{
            // $count =  $this->product_detail_number;
            // if($this->product_detail_number == 2){
            //     $this->product_detail_order[1] = 2;
            // }else{
            //     $this->product_detail_order[$this->product_detail_number-1] = $this->product_detail_number;
            // }
            // $this->product_detail_title[$this->product_detail_number-1] = "Chương ".$count;
        }
        
        for($i = 0; $i < count($this->product_detail_image); $i++){
            if(array_key_exists($i,$this->product_detail_image)){
                if(array_key_exists($i,$this->product_detail_image_list)){
                    $this->product_detail_image_list[$i] = array_merge($this->product_detail_image_list[$i], $this->product_detail_image[$i]);
                }else{
                    $this->product_detail_image_list[$i] = $this->product_detail_image[$i];
                }
            }else{
                $this->product_detail_image_list[$i] = [];
            }
            $this->product_detail_image[$i] = [];
        }
    }

    public function render()
    {
        $this->generateProductCode();
        $this->initinalRender();
        return view('livewire.admin.product.add-product', ['brands' => $this->brands, 'categories' => $this->categories, 'product_detail_list' => $this->product_detail_list, 'product_detail_image_list' => $this->product_detail_image_list]);
    }

    public function removeVideo($index)
    {
        unset($this->videos[$index]);
        $this->videos = array_values($this->videos); // Re-index the array
        
        // Update existingVideos after removing a video
        $this->existingVideos = $this->videos; // Assuming existingVideos is a property in the class
    }
}
