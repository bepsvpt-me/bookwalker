@extends('layouts.base')

@if(request('keyword'))
  @section('title', sprintf('%s | BookWalker 探索號', request('keyword')))
@endif

@section('main')
  @include('form.form')

  <div class="mt-4 alert alert-primary" role="alert">
    <strong>書籍圖片僅作辨識用途，版權皆屬台灣漫讀股份有限公司所有</strong>
  </div>

  <section class="my-4">
    {{ $books->appends(request()->query())->links() }}
  </section>

  @foreach($books as $book)
    <section class="card my-4">
      <div class="row no-gutters">
        <div class="col-md-4 col-lg-3 col-xl-2 card-cover">
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

        <article class="col-md-8 col-lg-9 col-xl-10">
          <section class="card-header d-flex align-items-center">
            <article class="flex-grow-1">
              <h5 class="card-title mb-0">{{ $book->name }}</h5>

              @if($book->slogan)
                <h6 class="card-subtitle text-muted mt-1">{{ $book->slogan }}</h6>
              @endif
            </article>

            <article>
              <span>{{ $book->type->name }}</span>

              <span class="mx-1 no-select">/</span>

              <span>{{ $book->category->name }}</span>
            </article>
          </section>

          <section class="card-body">
            <p class="card-text">
              @foreach(['authors', 'writers', 'illustrators', 'translators'] as $group)
                @foreach($book->{$group} as $creator)
                  <a
                    class="card-link"
                    href="#"
                  >
                    {{ $creator->name }}
                  </a>

                  <span class="mx-1 no-select">•</span>
                @endforeach
              @endforeach

              <span>{{ $book->published_at->toDateString() }}</span>


              <span class="mx-1 no-select">•</span>

              <span>{{ $book->publisher->name }}</span>
            </p>

            <p class="card-text">
              {{ \Illuminate\Support\Str::limit($book->description, 220) }}
            </p>

            <article>
              @foreach($book->tags as $tag)
                <span class="badge badge-info">{{ $tag->name }}</span>
              @endforeach
            </article>

            <hr>

            <a
              class="card-link"
              href="{{ $book->link }}"
              referrerpolicy="no-referrer"
              rel="noopener noreferrer"
              target="_blank"
            >
              <small>前往書籍 BookWalker 官方頁面</small>

              <svg style="width: 0.956rem; height: 0.956rem" viewBox="0 0 24 24">
                <path fill="currentColor" d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z" />
              </svg>
            </a>
          </section>
        </article>
      </div>
    </section>
  @endforeach

  {{ $books->appends(request()->query())->links() }}
@endsection
