<form action="{{ route('search') }}" method="GET">
  <section class="mb-0 form-group">
    <label class="sr-only" for="keyword">Keyword</label>

    <div class="input-group">
      <input
        aria-describedby="keyword"
        autofocus
        class="form-control"
        id="keyword"
        maxlength="128"
        minlength="1"
        name="keyword"
        placeholder="關鍵字"
        required
        type="text"
        value="{{ request('keyword') }}"
      >

      <div class="input-group-append">
        <button class="btn btn-success" type="submit">
          <svg
            style="width: 1.2rem; height: 1.2rem; vertical-align: text-bottom;"
            viewBox="0 0 24 24"
          >
            <path fill="currentColor" d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" />
          </svg>
        </button>
      </div>
    </div>
  </section>

  @if (Route::currentRouteName() === 'search')
    @include('form.advanced.publishers')

    @include('form.advanced.types')

    @include('form.advanced.categories')

    @include('form.advanced.tags')
  @endif
</form>
