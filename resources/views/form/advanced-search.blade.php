@php ($items = $all->pluck($pluck)->flatten()->unique()->toArray())

@empty (!$items)
  <table class="mb-0 table table-sm">
    <tbody>
      @foreach (array_chunk($items, 6) as $chunk)
        <tr>
          @if ($loop->first)
            <th
              class="table-info text-center"
              rowspan="{{ $loop->count }}"
              style="width: 10%;"
            >
              {{ $label }}
            </th>
          @endif

          @foreach ($chunk as $item)
            @php ($tempId = uniqid('bookwalker-'))

            <td style="width: 15%;">
              <section class="form-check">
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
              </section>
            </td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
@endempty
