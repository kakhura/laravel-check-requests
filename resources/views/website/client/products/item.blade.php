@extends('vendor.site-bases.website.layouts.master')

@section('title', $product->title)

@section('desc', strip_tags($product->description))

@section('page_title', $product->title)

@section('img', asset($product->image))

@section('content')
@endsection
