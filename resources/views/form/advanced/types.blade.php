@php ($types = $all->pluck('type.name')->unique()->toArray())

@empty (!$types)
  <section class="advanced-search">
    <span>Type：</span>

    @foreach ($types as $type)
      @php ($tempId = uniqid('bookwalker-'))

      <article class="form-check form-check-inline">
        <input
          class="form-check-input"
          @if (in_array($type, request('types', []), true))
            checked
          @endif
          id="{{ $tempId }}"
          name="types[]"
          onchange="this.form.submit()"
          type="checkbox"
          value="{{ $type }}"
        >

        <label
          class="form-check-label"
          for="{{ $tempId }}"
        >
          {{ $type }}
        </label>
      </article>
    @endforeach
  </section>
@endempty
