@extends('vendor.site-bases.website.layouts.master')

@section('title', $page->title)

@section('desc', strip_tags($page->description))

@section('page_title', $page->title)

@section('img', asset($page->image))

@section('content')
@endsection
