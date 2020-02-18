@php ($items = $all->pluck($pluck)->flatten()->unique()->toArray())

@empty (!$items)
  <section class="advanced-search d-flex">
    <div class="flex-shrink-0">{{ $label }}：</div>

    <section>
      @foreach ($items as $item)
        @php ($tempId = uniqid('bookwalker-'))

        <article class="form-check form-check-inline">
          <input
            class="form-check-input"
            @if (in_array($item, request($name, []), true))
            checked
            @endif
            id="{{ $tempId }}"
            name="{{ $name }}[]"
            onchange="this.form.submit()"
            type="checkbox"
            value="{{ $item }}"
          >

          <label
            class="form-check-label"
            for="{{ $tempId }}"
          >
            {{ $item }}
          </label>
        </article>
      @endforeach
    </section>
  </section>
@endempty
