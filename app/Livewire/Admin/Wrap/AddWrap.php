<?php

namespace App\Livewire\Admin\Wrap;

use Livewire\Component;
use App\Models\WrapLink;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Illuminate\Support\Facades\Storage;

class AddWrap extends Component
{
    use WithFileUploads;

    public $wraplink_code = '';
    public $wraplink_name = '';
    public $description = '';
    public $photo;
    public $existedPhoto;

    public function storeWrapLink()
    {
        $this->validate([
            'description' => 'required',
            'wraplink_name' => 'required|unique:wraplinks,name',
        ], [
            'description.required' => 'Mã Đường Dẫn Link là bắt buộc.',
            'wraplink_name.required' => 'Tên Nội Dung là bắt buộc.',
            'wraplink_name.unique' => 'Tên Nội Dung đã tồn tại.',
        ]);
        if ($this->photo) {
            $this->validate([
                'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ], [
                'photo.image' => 'File không phải là ảnh',
                'photo.mimes' => 'Ảnh không đúng định dạng',
                'photo.max' => 'Ảnh không được lớn hơn 2MB'
            ]);
            Storage::delete('public\\' . $this->existedPhoto);
            $photo_name = time() . '.' . $this->photo->extension();
            ImageOptimizer::optimize($this->photo->path());
            $this->photo->storeAs(path: "public\images\wraplinks", name: $photo_name);
        }
        $wraplink = new WrapLink();
        $wraplink->code = $this->wraplink_code;
        $wraplink->name = $this->wraplink_name;
        $wraplink->description = $this->description;
        $wraplink->slug = Str::of($this->wraplink_name)->slug('-');
        if ($this->photo) {
            $wraplink->logo = $photo_name;
        }
        $wraplink->save();

        session()->flash('message', 'wraplink has been created successfully!');
        return redirect()->route('admin.wraplinks');
    }
    public function render()
    {
        $wraplinks = WrapLink::all();
        return view('livewire.admin.wrap.add-wrap', ['wraplinks' => $wraplinks]);
    }
}
