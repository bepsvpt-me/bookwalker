<form
  action="{{ route('search') }}"
  id="search"
  method="GET"
>
  <section class="mb-1 form-group">
    <label class="sr-only" for="keyword">Keyword</label>

    <div class="input-group">
      <input
        aria-describedby="keyword"
        @if (\Illuminate\Support\Facades\Route::currentRouteName() === 'home')
        autofocus
        @endif
        class="form-control shadow-none"
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
          <svg viewBox="0 0 24 24">
            <path fill="currentColor" d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" />
          </svg>
        </button>

        @if (\Illuminate\Support\Facades\Route::currentRouteName() === 'search')
          <label
            class="border-left mb-0 btn btn-info"
            for="dialog-toggle"
          >
            <svg viewBox="0 0 24 24">
              <path fill="currentColor" d="M14,12V19.88C14.04,20.18 13.94,20.5 13.71,20.71C13.32,21.1 12.69,21.1 12.3,20.71L10.29,18.7C10.06,18.47 9.96,18.16 10,17.87V12H9.97L4.21,4.62C3.87,4.19 3.95,3.56 4.38,3.22C4.57,3.08 4.78,3 5,3V3H19V3C19.22,3 19.43,3.08 19.62,3.22C20.05,3.56 20.13,4.19 19.79,4.62L14.03,12H14Z" />
            </svg>
          </label>
        @endif
      </div>
    </div>
  </section>
</form>
