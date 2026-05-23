@foreach($records as $val)

<tr>

    <td>{{ $val->id }}</td>

    <td>
        <input type="text"
               name="destination"
               value="{{ $val->destination }}"
               form="update_{{ $val->id }}">
    </td>

    <td>
        <input type="text"
               name="destination_hurigana"
               value="{{ $val->destination_hurigana }}"
               form="update_{{ $val->id }}">
    </td>

    <td class="td_last">

        <form id="update_{{ $val->id }}"
              method="POST"
              action="{{ route('master.destination.update', $val->id) }}">

            @csrf
            @method('PUT')

            <button type="submit">
                更新
            </button>

        </form>



        <form method="POST"
              action="{{ route('master.destination.delete', $val->id) }}">

            @csrf
            @method('DELETE')

            <button type="submit">
                削除
            </button>

        </form>

    </td>

</tr>

@endforeach