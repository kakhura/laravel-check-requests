@extends('vendor.site-bases.website.layouts.master')

@section('title', $brand->title)

@section('desc', strip_tags($brand->description))

@section('page_title', $brand->title)

@section('img', asset($brand->image))

@section('content')
@endsection
