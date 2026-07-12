/* 後回し、
<tr>
  <td><input class="distance"></td>
  <td><input class="price"></td>
  <td><select class="hospital_select"></select></td>
</tr>　をreact化してから再開
*/

import './Totals.css';

export default function Totals() {

  const totalAmount = 0;
  const totalDistance = 0;

  return (
    <div className="inner">
      <div className="item">
        合計距離
        <input
          type="text"
          className="total_distance fixed_input"
          value={totalDistance}
          readOnly
        />
      </div>

      <div className="item">
        合計金額
        <input
          type="text"
          className="total_amount fixed_input"
          value={totalAmount}
          readOnly
        />
      </div>
    </div>
  );
}