@php ($publishers = $all->pluck('publisher.name')->unique()->toArray())

@empty (!$publishers)
  <section class="advanced-search">
    <span>出版社：</span>

    @foreach ($publishers as $publisher)
      @php ($tempId = uniqid('bookwalker-'))

      <article class="form-check form-check-inline">
        <input
          class="form-check-input"
          @if (in_array($publisher, request('publishers', []), true))
          checked
          @endif
          id="{{ $tempId }}"
          name="publishers[]"
          onchange="this.form.submit()"
          type="checkbox"
          value="{{ $publisher }}"
        >

        <label
          class="form-check-label"
          for="{{ $tempId }}"
        >
          {{ $publisher }}
        </label>
      </article>
    @endforeach
  </section>
@endempty
