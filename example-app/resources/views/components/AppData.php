namespace App\View\Components;

use Illuminate\View\Component;

class AppData extends Component
{
    public $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    public function render()
    {
        return view('components.app-data');
    }
}