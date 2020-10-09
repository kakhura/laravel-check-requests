@extends('vendor.site-bases.website.layouts.master')

@section('title', $blog->title)

@section('desc', strip_tags($blog->description))

@section('page_title', $blog->title)

@section('img', asset($blog->image))

@section('content')
@endsection
