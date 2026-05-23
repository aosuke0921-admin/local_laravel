@foreach($records as $val)

<tr>

    <td>{{ $val->id }}</td>

    <td>
        <input type="text"
               name="name"
               value="{{ $val->name }}"
               form="update_{{ $val->id }}">
    </td>

    <td>
        <input type="text"
               name="kana"
               value="{{ $val->kana }}"
               form="update_{{ $val->id }}">
    </td>

    <td>
        <input type="text"
               name="support_notes"
               value="{{ $val->support_notes }}"
               form="update_{{ $val->id }}">
    </td>

    <td class="td_last">

        <form id="update_{{ $val->id }}"
              method="POST"
              action="{{ route('master.customer.update', $val->id) }}">

            @csrf
            @method('PUT')

            <button type="submit">
                更新
            </button>

        </form>



        <form method="POST"
              action="{{ route('master.customer.delete', $val->id) }}">

            @csrf
            @method('DELETE')

            <button type="submit">
                削除
            </button>

        </form>

    </td>

</tr>

@endforeach