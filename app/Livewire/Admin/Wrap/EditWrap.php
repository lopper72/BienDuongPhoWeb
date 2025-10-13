<?php

namespace App\Livewire\Admin\Wrap;

use Livewire\Component;
use App\Models\WrapLink;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Illuminate\Support\Facades\Storage;

class EditWrap extends Component
{
    use WithFileUploads;

    public $wraplink_code = '';
    public $wraplink_name = '';
    public $description = '';
    public $id;
    public $photo;
    public $existedPhoto;

    public function updateWrapLink()
    {
        $this->validate([
            'description' => 'required',
            'wraplink_name' => 'required|unique:wraplinks,name,' . $this->id,
        ], [
            'description.required' => 'Link Chuyển Hướng là bắt buộc.',
            'wraplink_name.required' => 'Tên Đường Dẫn là bắt buộc.',
            'wraplink_name.unique' => 'Tên Đường Dẫn đã tồn tại.',
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
        $wraplink = WrapLink::find($this->id);
        $wraplink->code = $this->wraplink_code;
        $wraplink->name = $this->wraplink_name;
        $wraplink->description = $this->description;
        $wraplink->slug = Str::of($this->wraplink_name)->slug('-');
        if ($this->photo) {
            $wraplink->logo = $photo_name;
        }
        $wraplink->save();

        session()->flash('message', 'wraplink has been updated successfully!');
        return redirect()->route('admin.wraplinks');
    }

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        $categories = WrapLink::all();
        $wraplink = WrapLink::find($this->id);
        $this->wraplink_code = $wraplink->code;
        $this->wraplink_name = $wraplink->name;
        $this->description = $wraplink->description;
        if($wraplink->logo) {
            $this->existedPhoto = "images/wraplinks/" . $wraplink->logo;
        }
        return view('livewire.admin.wrap.edit-wrap', ['categories' => $categories]);
    }
}
