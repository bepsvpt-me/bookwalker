@extends('layouts.base')

@section('title', sprintf('%s | BookWalker 探索號', request('author')))

@section('main')
  @include('components.books')
@endsection
