@extends('vendor.site-bases.website.layouts.master')

@section('title', $photo->title)

@section('desc', strip_tags($photo->description))

@section('page_title', $photo->title)

@section('img', asset($photo->image))

@section('content')
@endsection
