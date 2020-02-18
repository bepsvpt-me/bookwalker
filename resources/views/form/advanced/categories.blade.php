@php ($categories = $all->pluck('category.name')->unique()->toArray())

@empty (!$categories)
  <section class="advanced-search">
    <span>Category：</span>

    @foreach ($categories as $category)
      @php ($tempId = uniqid('bookwalker-'))

      <article class="form-check form-check-inline">
        <input
          class="form-check-input"
          @if (in_array($category, request('categories', []), true))
            checked
          @endif
          id="{{ $tempId }}"
          name="categories[]"
          onchange="this.form.submit()"
          type="checkbox"
          value="{{ $category }}"
        >

        <label
          class="form-check-label"
          for="{{ $tempId }}"
        >
          {{ $category }}
        </label>
      </article>
    @endforeach
  </section>
@endempty
