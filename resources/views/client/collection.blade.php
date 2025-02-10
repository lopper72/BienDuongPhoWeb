@extends('client.layouts.master')

@section('title', 'Chi tiết Truyện')

@section('content')
    @livewire('client.collection', ["slug" => $category->slug])
@endsection