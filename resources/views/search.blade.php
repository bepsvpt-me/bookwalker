@extends('layouts.base')

@if(request('keyword'))
  @section('title', sprintf('%s | BookWalker 探索號', request('keyword')))
@endif

@section('main')
  <input
    class="d-none"
    id="dialog-toggle"
    type="checkbox"
  >

  <dialog
    open
    class="fixed-top vw-100 vh-100 border-0 advanced-search"
  >
    <label class="fixed-top w-100 h-100 advanced-search-wrapper" for="dialog-toggle"></label>
    <section class="h-100 mx-auto overflow-auto card advanced-search-container">
      <article class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">進階搜尋</h5>

        <label
          class="btn btn-light"
          for="dialog-toggle"
          type="button"
        >
          <svg viewBox="0 0 24 24">
            <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
          </svg>
        </label>
      </article>

      <article class="card-body">
        <div class="alert alert-primary" role="alert">
          <span>因伺服器資源有限無法呈現所有清單，當遇到列表遺漏之狀況，請嘗試使用更精確的關鍵字搜尋</span>
        </div>

        <button
          class="mb-3 btn btn-block btn-success"
          form="search"
          type="submit"
        >
          送出
        </button>

        @include('form.advanced-search', [
          'name' => 'types',
          'label' => '書籍類型',
          'pluck' => 'type.name',
        ])

        @include('form.advanced-search', [
          'name' => 'categories',
          'label' => '分類',
          'pluck' => 'category.name',
        ])

        @include('form.advanced-search', [
          'name' => 'authors',
          'label' => '作者',
          'pluck' => 'authors.*.name',
        ])

        @include('form.advanced-search', [
          'name' => 'writers',
          'label' => '原著',
          'pluck' => 'writers.*.name',
        ])

        @include('form.advanced-search', [
          'name' => 'illustrators',
          'label' => '插畫',
          'pluck' => 'illustrators.*.name',
        ])

        @include('form.advanced-search', [
          'name' => 'translators',
          'label' => '譯者',
          'pluck' => 'translators.*.name',
        ])

        @include('form.advanced-search', [
          'name' => 'publishers',
          'label' => '出版社',
          'pluck' => 'publisher.name',
        ])

        @include('form.advanced-search', [
          'name' => 'tags',
          'label' => '標籤',
          'pluck' => 'tags.*.name',
        ])

        <button
          class="mb-3 btn btn-block btn-success"
          form="search"
          type="submit"
        >
          送出
        </button>
      </article>
    </section>
  </dialog>

  @include('form.form')

  <div class="mt-4 alert alert-primary" role="alert">
    <strong>書籍圖片僅作辨識用途，版權皆屬台灣漫讀股份有限公司所有</strong>
  </div>

  <section class="my-4 overflow-auto">
    {{ $books->appends(request()->query())->links() }}
  </section>

  @forelse($books as $book)
    <section class="card my-4">
      <div class="row no-gutters">
        <div class="col-sm-5 col-md-4 col-lg-3 col-xl-2 card-cover">
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

        <article class="col-sm-7 col-md-8 col-lg-9 col-xl-10">
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
                  <span>{{ $creator->name }}</span>

                  <span class="mx-1 no-select">•</span>
                @endforeach
              @endforeach

              <span>{{ $book->published_at->toDateString() }}</span>


              <span class="mx-1 no-select">•</span>

              <span>{{ $book->publisher->name }}</span>
            </p>

            <p class="card-text overflow-auto book-description">{{ $book->description }}</p>

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
              <small>前往此書籍 BOOKWALKER 官方頁面</small>

              <svg viewBox="0 0 24 24">
                <path fill="currentColor" d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z" />
              </svg>
            </a>
          </section>
        </article>
      </div>
    </section>
  @empty
    <p class="mt-4 lead text-center">
      <strong>您抵達了結果為「　」的世界線！</strong>
    </p>
  @endforelse

  <section class="overflow-auto">
    {{ $books->appends(request()->query())->links() }}
  </section>
@endsection
