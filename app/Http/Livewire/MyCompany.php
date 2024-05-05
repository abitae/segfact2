<?php

namespace App\Http\Livewire;

use App\MyCompany as AppMyCompany;
use Livewire\Component;

class MyCompany extends Component
{
    public $search = '';
    public function render()
    {
        $myCompany = AppMyCompany::where('ruc', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('RazonSocial', 'LIKE', '%' . $this->search . '%')
                                ->orderBy('fin_suscription', 'asc')
                                ->paginate(10);
        return view('livewire.my-company',compact('myCompany'));
    }
}
