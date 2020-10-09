@extends('vendor.site-bases.website.layouts.master')

@section('title', $news->title)

@section('desc', strip_tags($news->description))

@section('page_title', $news->title)

@section('img', asset($news->image))

@section('content')
@endsection
