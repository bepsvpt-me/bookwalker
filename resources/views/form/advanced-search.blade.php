@php ($items = $all->pluck($pluck)->flatten()->unique()->toArray())

@empty (!$items)
  <section class="mb-3 card">
    <h6 class="card-header">{{ $label }}</h6>

    <article
      class="card-body px-2 py-1 overflow-auto"
      style="max-height: 20rem;"
    >
      @foreach ($items as $item)
        @php ($tempId = uniqid('bookwalker-'))

        <section class="form-check">
          <input
            class="form-check-input"
            @if (in_array($item, request($name, []), true))
            checked
            @endif
            form="search"
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
        </section>
      @endforeach
    </article>
  </section>
@endempty
