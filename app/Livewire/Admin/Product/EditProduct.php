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

class EditProduct extends Component
{
    use WithFileUploads;

    public $product_code;
    public $product_name;
    public $category_id;
    public $brand_id;
    public $product_retail_price = 0;
    public $product_wholesale_price = 0;
    public $product_description;
    public $product_size = '';
    public $product_uom = 'Cái';
    public $product_size_list = [];
    public $product_size_list_deleted = [];
    public $product_detail_number = 0;
    public $product_detail_list = [];
    public $product_detail_list_deleted = [];
    public $product_detail_image = [];
    public $product_detail_image_list = [];
    public $product_detail_title = []; 
    public $product_detail_order = []; 
    public $product_detail_short_description = [];
    public $id;
    public $brands;
    public $categories;
    public $is_active = '1';
    public $is_full = '1';
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
    public $existingVideos = [];
    public $product_description2;
    public $tiktok_link = '';

    public function updatedVideos()
    {
        $this->validate([
            'videos.*' => 'required|mimes:mp4,mov,avi,wmv|max:102400', // Giới hạn 100MB cho mỗi video
        ]);
    }
    
    protected $listeners = ['updateComponent' => 'updateData'];
    public function mount($id, $brands, $categories)
    {
        $this->id = $id;
        $this->brands = $brands;
        $this->categories = $categories;

        $product = Product::find($this->id);    
        $this->product_code = $product->code;
        $this->product_name = $product->name;
        $this->category_id = $product->category_id;
        $this->brand_id = $product->brand_id;
        //$this->product_retail_price = $product->retail_price;
        //$this->product_wholesale_price = $product->wholesale_price;
        $this->product_description = $product->description;
        $this->product_description2 = $product->description2;
        //$this->product_uom = $product->uom;
        $this->is_active = $product->is_active;
        $this->is_full = $product->is_full;
        $this->product_source = $product->source;
        $this->product_author = $product->author;
        $this->shopper_link = $product->shopper_link;
        $this->tiktok_link = $product->tiktok_link;
        $this->selected_brands = json_decode($product->brand_ids);
        $this->selected_categories =  json_decode($product->category_ids);


        $this->product_detail_list_category = json_decode(str_replace('"', '', $product->category_ids), true);
     
        $this->product_detail_list_brand =  json_decode(str_replace('"', '', $product->brand_ids), true);

        if ( $this->product_detail_list_category == ""){
            $this->product_detail_list_category = [];
        }
        if ( $this->product_detail_list_brand == ""){
            $this->product_detail_list_brand = [];
        }
        if($product->image) {
            $this->existedVideo =  $product->image;
        }
        
        // Cập nhật danh sách video hiện có
        $this->existingVideos = $this->getExistingVideos();

        if(is_null($this->is_active)){
            $this->is_active = '1';
        }
    }
    // public function updated()
    // {
    //     $this->dispatch('reloadjs');
    // }
    public function addProductSize(){
        $this->validate([
            'product_size' => 'required'
        ], [
            'product_size.required' => 'Kích thước không được để trống.'
        ]);
        $new_product_size = new ProductSize();
        $new_product_size->size = $this->product_size;
        $this->product_size_list[] = collect($new_product_size);
        $this->product_size = '';
    }

    public function removeProductSize($index){
        $this->product_size_list_deleted[] = $this->product_size_list[$index];
        unset($this->product_size_list[$index]);
        $this->product_size_list = array_values($this->product_size_list);
    }

    public function addProductDetail(){
        $new_product_detail = new ProductDetail();
        $this->product_detail_list[] = $new_product_detail;
       
        $this->product_detail_number++;
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
        $this->product_detail_list_deleted[] = $this->product_detail_list[$index];
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
        if(is_string($this->product_detail_image_list[$index])){
            $this->product_detail_image_list[$index] = json_decode($this->product_detail_image_list[$index]);
        }
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

    public function storeProduct(){
        $this->dispatch('reloadjs');
        $this->validate([
            'product_code' => 'required|unique:products,code,' . $this->id,
            'product_name' => 'required|unique:products,name,' . $this->id,
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

        // for($i = 0; $i < $this->product_detail_number; $i++){
        //     $this->validate([
        //         'product_detail_title.'.$i => 'required',
        //         'product_detail_order.'.$i => 'required',
        //         'product_detail_order.*' => 'distinct',
        //     ], [
        //         'product_detail_title.'.$i.'.required' => 'Tiêu đề là bắt buộc.',
        //         'product_detail_order.'.$i.'.required' => 'Thứ tự là bắt buộc.',
        //         'product_detail_order.*.distinct' => 'Các Chương Trong Bài Đăng không được trùng nhau!',
        //     ]);
        // }

        // Initialize an array to hold video names
        $video_names = [];


        // Add existing videos to the video_names array
       
        if (!empty($this->existingVideos)) {
            $video_names = array_merge($video_names, $this->existingVideos);
        }

        // Process new uploaded videos
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

        $product = Product::find($this->id);
        $product->code = $this->product_code;
        $product->name = $this->product_name;
        $product->category_id = $this->category_id;
        $product->brand_id = $this->brand_id;
        //$product->retail_price = $this->product_retail_price;
        //$product->wholesale_price = $this->product_wholesale_price;
        $product->description = $this->product_description;
        $product->description2 = $this->product_description2;
        $product->slug = Str::of($this->product_name)->slug('-');
        //$product->uom = $this->product_uom;
        $product->is_active = $this->is_active;
        $product->is_full = $this->is_full;
        $product->source = $this->product_source;
        $product->author = $this->product_author;
        $product->shopper_link = $this->shopper_link;
        $product->tiktok_link = $this->tiktok_link;     
        if (is_null($this->selected_categories)) {
            $this->selected_categories = [];
        }
        $keys = json_encode(array_values($this->selected_categories));
        $product->category_ids =  $keys;

        if (is_null($this->selected_brands)) {
            $this->selected_brands = [];
        }
        $keys = json_encode(array_values($this->selected_brands));
        $product->brand_ids = $keys;
        
        // Store all video names as a JSON string in the database

        if (!empty($video_names)) {
            $product->image = json_encode($video_names); // Store as JSON
        }else{
            $product->image = null;
        }

        $product->save();

        // foreach($this->product_size_list_deleted as $size){
        //     if(is_array($size) && array_key_exists('id', $size)){
        //         ProductSize::find($size['id'])->delete();
        //     }
        // }
        // foreach($this->product_size_list as $size){
        //     if(is_array($size) && array_key_exists('id', $size)){
        //         $product_size = ProductSize::find($size['id']);
        //         $product_size->size = $size['size'];
        //         $product_size->save();
        //         continue;
        //     }
        //     $product_size = new ProductSize();
        //     $product_size->product_id = $this->id;
        //     $product_size->size = $size["size"];
        //     $product_size->save();
        // }

        // foreach($this->product_detail_list_deleted as $product_detail){
        //     if(is_array($product_detail) && array_key_exists('id', $product_detail)){
        //         ProductDetail::find($product_detail['id'])->delete();
        //     }
        // }

        // for($i = 0; $i < $this->product_detail_number; $i++){
            

        //     if(is_array($this->product_detail_list[$i])){
        //         $this->product_detail_list[$i] = ProductDetail::find($this->product_detail_list[$i]['id']);
        //     }
        //     else{
        //         $this->product_detail_list[$i]->product_id = $product->id;
        //     }

        //     $this->product_detail_list[$i]->title = $this->product_detail_title[$i];
        //     $this->product_detail_list[$i]->number_order = $this->product_detail_order[$i];
            
        //     if(array_key_exists($i, $this->product_detail_short_description)){
        //         $this->product_detail_list[$i]->short_description = $this->product_detail_short_description[$i];
        //     }
        //     $this->product_detail_list[$i]->retail_price = $product->retail_price;
        //     $this->product_detail_list[$i]->wholesale_price = $product->wholesale_price;

        //     if(array_key_exists($i, $this->product_detail_image_list)){
        //         $image_list = $this->product_detail_image_list[$i];
        //         if($image_list != null){
        //             $images_store = [];
        //             if(is_string($image_list)){
        //                 $image_list = json_decode($image_list);
        //             }
        //             if(count($image_list) > 0){
        //                 foreach($image_list as $image){
        //                     if(is_string($image)){
        //                         $images_store[] = $image;
        //                         continue;
        //                     }
        //                     $image_name = time() . uniqid() . '.' . $image->extension();

        //                     $this->product_detail_list[$i]->image = $image_name;
        //                     $image->storeAs(path: "public\images\products", name: $image_name);
        //                     $images_store[] = $image_name;
        //                 }
        //                 $this->product_detail_list[$i]->image = json_encode($images_store);
        //             }
        //         }else{
        //             $this->product_detail_list[$i]->image = null;
        //         }
                
        //     }
        //     $this->product_detail_list[$i]->save();
        // }

        session()->flash('message', 'Product has been created successfully!');
        return redirect()->route('admin.products');
    }

    public function initinalRender(){

        if(count($this->product_size_list) == 0 && count($this->product_size_list_deleted) == 0){
            $product_size = ProductSize::where('product_id', $this->id)->get();
            $this->product_size_list = collect($product_size)->toArray();
        }

        for($i = 0; $i < count($this->product_detail_image); $i++){
            if(array_key_exists($i,$this->product_detail_image)){
                if(array_key_exists($i,$this->product_detail_image_list)){
                    if(is_string($this->product_detail_image_list[$i])){
                        $this->product_detail_image_list[$i] = json_decode($this->product_detail_image_list[$i]);
                    }
                    if($this->product_detail_image_list[$i] != null && count($this->product_detail_image_list[$i]) > 0){
                        $this->product_detail_image_list[$i] = array_merge($this->product_detail_image_list[$i], $this->product_detail_image[$i]);
                    }else{
                        $this->product_detail_image_list[$i] = $this->product_detail_image[$i];
                    }
                }else{
                    $this->product_detail_image_list[$i] = $this->product_detail_image[$i];
                }
            }
            $this->product_detail_image[$i] = [];
        }
    }

    // Add a method to retrieve existing videos
    public function getExistingVideos()
    {
        if ($this->existedVideo) {
            return json_decode($this->existedVideo, true) ?: []; // Decode JSON to array or return empty array
        }
        return []; // Ensure it returns an empty array if no videos exist
    }

    public function render()
    {
        $this->initinalRender();


        $product_details = null;
        
        if($this->product_detail_number == 0){
            $product_details = ProductDetail::where('product_id', $this->id)->orderBy('id', 'asc')->get();
            $this->product_detail_list = $product_details->toArray();
            $this->product_detail_number = count($product_details);
            foreach ($product_details as $key => $product_detail) {
                $this->product_detail_title[$key] = $product_detail->title;
                $this->product_detail_order[$key] = $product_detail->number_order;
                $this->product_detail_image_list[$key] = $product_detail->image;
                $this->product_detail_short_description[$key] = $product_detail->short_description;
            }
        }else{
            // $count =  $this->product_detail_number;
            // if($this->product_detail_number == 2){
            //     $this->product_detail_order[1] = 2;
            // }else{
            //     $this->product_detail_order[$this->product_detail_number-1] = $this->product_detail_number;
            // }
            // $this->product_detail_title[$this->product_detail_number-1] = "Chương ".$count;
        }
        return view('livewire.admin.product.edit-product', [
            'brands' => $this->brands,
            'categories' => $this->categories,
            'product_detail_list' => $this->product_detail_list,
            'product_detail_image_list' => $this->product_detail_image_list,
            'product_description' => $this->product_description,
            'product_description2' => $this->product_description2,
            'existingVideos' => $this->existingVideos, // Pass existing videos to the view
        ]);
    }

    public function removeVideo($index)
    {
        // Xóa video khỏi mảng videos
        unset($this->videos[$index]);
        $this->videos = array_values($this->videos); // Đảm bảo mảng không có khoảng trống

        // Cập nhật danh sách video hiện có
    }
    public function removeVideoExit($index)
    {
       
        unset($this->existingVideos[$index]);

    }
    
}
