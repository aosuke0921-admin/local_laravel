@foreach($records as $val)

<tr>

    <td>{{ $val->id }}</td>

    <td>
        <input type="text"
               name="user_{{ $val->id }}"
               value="{{ $val->user }}"
               form="update_{{ $val->id }}">

                @error('user_' . $val->id)
                    <?php /*<div class="error_msg">{{ $message }}</div>*/ ?>
                @enderror
    </td>

    <td>
        <input type="text"
               name="destination_{{ $val->id }}"
               value="{{ $val->destination }}"
               form="update_{{ $val->id }}">

                @error('destination_' . $val->id)
                    <?php /*<div class="error_msg">{{ $message }}</div>*/ ?>
                @enderror
    </td>

    <td>
        <input type="text"
               name="pickup_location"
               value="{{ $val->pickup_location }}"
               form="update_{{ $val->id }}">
    </td>

    <td>
        <label>
            <input type="radio"
                   name="dialysis_{{ $val->id }}"
                   value="1"
                   form="update_{{ $val->id }}"
                   {{ (int)$val->dialysis === 1 ? 'checked' : '' }}>
            あり
        </label>

        <label>
            <input type="radio"
                   name="dialysis_{{ $val->id }}"
                   value="0"
                   form="update_{{ $val->id }}"
                   {{ (int)$val->dialysis === 0 ? 'checked' : '' }}>
            なし
        </label>
    </td>

    <td>
        <input type="text"
               name="distance"
               value="{{ number_format($val->distance, 1) }}"
               form="update_{{ $val->id }}"
               inputmode="decimal"
               oninput="
                   let v = this.value.replace(/[^0-9.]/g, '');

                   // ドット2個目以降を削除
                   const parts = v.split('.');
                   if (parts.length > 2) {
                       v = parts[0] + '.' + parts.slice(1).join('');
                   }

                   // 小数点以下1桁まで
                   if (v.includes('.')) {
                       const [intPart, decimalPart] = v.split('.');
                       v = intPart + '.' + (decimalPart ?? '').slice(0, 1);
                   }

                   this.value = v;
        ">
    </td>

    <td>
        <label>
            <input type="radio"
                   name="transport_fee_{{ $val->id }}"
                   value="1"
                   form="update_{{ $val->id }}"
                   {{ (int)$val->transport_fee === 1 ? 'checked' : '' }}>
            あり
        </label>

        <label>
            <input type="radio"
                   name="transport_fee_{{ $val->id }}"
                   value="0"
                   form="update_{{ $val->id }}"
                   {{ (int)$val->transport_fee === 0 ? 'checked' : '' }}>
            なし
        </label>
    </td>

    <td class="td_last">

        <button type="submit" class="submit" form="update_{{ $val->id }}">
            更新
        </button>



        <form method="POST" action="{{ route('user_destination.delete', $val->id) }}">

            @csrf
            @method('DELETE')

            <button type="submit" class="delete">
                削除
            </button>

        </form>

    </td>

</tr>

<form id="update_{{ $val->id }}"
      method="POST"
      action="{{ route('user_destination.update', $val->id) }}">

    <input type="hidden"
           name="_token"
           value="{{ csrf_token() }}"
           form="update_{{ $val->id }}">

    <input type="hidden"
           name="_method"
           value="PUT"
           form="update_{{ $val->id }}">

</form>

@endforeach