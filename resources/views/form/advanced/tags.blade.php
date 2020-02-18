@php ($tags = $all->pluck('tags.*.name')->flatten()->unique()->toArray())

@empty (!$tags)
  <section class="advanced-search d-flex">
    <div class="flex-shrink-0">標籤：</div>

    <section>
      @foreach ($tags as $tag)
        @php ($tempId = uniqid('bookwalker-'))

        <article class="form-check form-check-inline">
          <input
            class="form-check-input"
            @if (in_array($tag, request('tags', []), true))
            checked
            @endif
            id="{{ $tempId }}"
            name="tags[]"
            onchange="this.form.submit()"
            type="checkbox"
            value="{{ $tag }}"
          >

          <label
            class="form-check-label"
            for="{{ $tempId }}"
          >
            {{ $tag }}
          </label>
        </article>
      @endforeach
    </section>
  </section>
@endempty
