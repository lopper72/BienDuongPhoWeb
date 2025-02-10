@extends('client.layouts.master')

@section('title', 'Chi tiết Bài Đăng')

@section('content')
    @livewire('client.collection', ["slug" => $category->slug])
@endsection