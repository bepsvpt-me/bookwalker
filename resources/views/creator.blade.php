@extends('layouts.base')

@section('title', sprintf('%s | BookWalker 探索號', request('name')))

@section('main')
  <h1 class="mt-0">{{ request('name') }} 著作</h1>

  @include('components.books')
@endsection
