<?php

namespace App\Livewire\Sales;

use App\Models\EmployeeSale;
use Livewire\Component;

class EmployeeSalesTarget extends Component
{
    public $searchEmployee = '';
    public $searchMonth = '';
    public $employeeSales;

    protected $listeners = ['updateAchieved'];

    public function updateAchieved($id, $value)
    {
        $sale = EmployeeSale::find($id);
        if ($sale) {
            $sale->achieved = $value;
            $sale->save();
        }
    }


    public function render()
    {
        $query = EmployeeSale::query()->with('user');
        if (!empty($this->searchEmployee)) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->searchEmployee . '%');
            });
        }

        if (!empty($this->searchMonth)) {
            $query->where('month', $this->searchMonth);
        }

        $this->employeeSales = $query->orderBy('month', 'desc')->get();

        return view('livewire.sales.employee-sales-target');

        

    }
}
