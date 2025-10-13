<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WrapLink;

class WrapLinkController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.wrap.list_wrap');
    }

    public function add()
    {
        return view('admin.dashboard.wrap.add_wrap');
    }

    public function delete($id){
        $wraplink = WrapLink::find($id);
        $wraplink->delete();
        return redirect()->route('admin.wraplinks');
    }

    public function edit($id)
    {
        $wraplink = WrapLink::find($id);
        return view('admin.dashboard.wrap.edit_wrap', ['wraplink' => $wraplink]);
    }
}
