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
        <select name="classification" form="update_{{ $val->id }}">

            <option value=""
                {{ empty($val->classification) ? 'selected' : '' }}>
                選択してください
            </option>

            <option value="介護保険"
                {{ $val->classification == '介護保険' ? 'selected' : '' }}>
                介護保険
            </option>

            <option value="障害福祉"
                {{ $val->classification == '障害福祉' ? 'selected' : '' }}>
                障害福祉
            </option>

            <option value="保険外"
                {{ $val->classification == '保険外' ? 'selected' : '' }}>
                保険外
            </option>

        </select>
    </td>

    <td>

        <input type="hidden"
            name="status"
            value="0"
            form="update_{{ $val->id }}">

        <input type="checkbox"
            name="status"
            value="1"
            {{ $val->status ? 'checked' : '' }}
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