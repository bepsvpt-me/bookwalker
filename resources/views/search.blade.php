@extends('layouts.base')

@if(request('keyword'))
  @section('title', sprintf('%s | BookWalker 探索號', request('keyword')))
@endif

@section('main')
  @include('components.form')

  @foreach($books as $book)
    <section class="card my-4">
      <div class="row no-gutters">
        <div class="col-md-3">
          <picture>
            <source srcset="{{ route('safe-browse', ['bid' => $book->bookwalker_id]) }}" type="image/webp">

            <img
              alt="{{ $book->bookwalker_id }}"
              class="card-img"
              decoding="async"
              importance="low"
              loading="lazy"
              referrerpolicy="no-referrer"
              src="{{ route('safe-browse', ['bid' => $book->bookwalker_id]) }}?type=jpg"
            >
          </picture>
        </div>

        <article class="col-md-9">
          <div class="card-body">
            <h5 class="card-title">{{ $book->name }}</h5>

            @if($book->slogan)
              <h6 class="card-subtitle mb-2 text-muted">{{ $book->slogan }}</h6>
            @endif

            <p class="card-text">{{ \Illuminate\Support\Str::limit($book->description, 150) }}</p>

            <p class="card-text">
              <small class="text-muted">發售日：{{ $book->published_at->toDateString() }}</small>
            </p>

            <a
              class="card-link"
              href="{{ $book->link }}"
              referrerpolicy="no-referrer"
              rel="noopener noreferrer"
              target="_blank"
            >
              <small>前往書籍 BookWalker 官方頁面</small>
            </a>
          </div>
        </article>
      </div>
    </section>
  @endforeach

  {{ $books->appends(['keyword' => request('keyword')])->links() }}
@endsection
