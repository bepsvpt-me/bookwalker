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
          @foreach(['authors' => '作者', 'writers' => '原著', 'characterDesigners' => '角色設定', 'illustrators' => '插畫', 'translators' => '譯者'] as $key => $type)
            @if ($book->{$key}->isNotEmpty())
              <p class="card-text mb-1">
                <span>{{ $type }}：</span>

                @foreach($book->{$key} as $creator)
                  @unless ($loop->first)
                    <span class="no-select">、</span>
                  @endunless

                  <a
                    class="card-link"
                    href="{{ route(substr($key, 0, -1), ['name' => $creator->name]) }}"
                  >
                    {{ $creator->name }}
                  </a>
                @endforeach
              </p>
            @endempty
          @endforeach

          <p class="card-text">
            <span>{{ $book->publisher->name }}</span>
            <span class="mx-1 no-select">發售於</span>
            <span>{{ $book->published_at->toDateString() }}</span>
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
