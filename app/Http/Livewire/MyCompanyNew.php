<?php

namespace App\Http\Livewire;

use App\MyCompany;
use Livewire\Component;

class MyCompanyNew extends Component
{
    public $mc;
    public $ruc;
    public $RazonSocial;
    public $plan;
    public $monto;
    public $fecha_suscription;
    public $fecha_certificacion;
    public $fin_suscription;
    public $fin_certificacion;
    public $nota;
    public $estado;
    
    public function mount()
    {
        if (!$this->mc == null) {
            $this->ruc = $this->mc->ruc;
            $this->RazonSocial = $this->mc->RazonSocial;
            $this->plan = $this->mc->plan;
            $this->monto = $this->mc->monto;
            $this->fecha_suscription = \Carbon\Carbon::parse($this->mc->fecha_suscription)->format('Y-m-d');
            $this->fecha_certificacion = \Carbon\Carbon::parse($this->mc->fecha_certificacion)->format('Y-m-d');
            $this->fin_suscription = \Carbon\Carbon::parse($this->mc->fin_suscription)->format('Y-m-d');
            $this->fin_certificacion = \Carbon\Carbon::parse($this->mc->fin_certificacion)->format('Y-m-d');
            $this->nota = $this->mc->nota;
            $this->estado = $this->mc->estado;
        }
    }
    public function render()
    {
        return view('livewire.my-company-new');
    }
    public function save()
    {
        
        $company = MyCompany::create($this->validate([
            'ruc' => 'required|min:11|max:11',
            'RazonSocial' => 'required',
            'plan' => 'required',
            'monto' => 'required',
            'fecha_suscription' => 'required',
            'fecha_certificacion' => 'required',
            'fin_suscription' => 'required',
            'fin_certificacion' => 'required',
            'nota' => 'required',
            'estado' => 'required',
        ]));
        return redirect('/mycompany');
    }
    public function update()
    {
        $company = MyCompany::find($this->mc->id);
        $company->update($this->validate([
            'ruc' => 'required|min:11|max:11',
            'RazonSocial' => 'required',
            'plan' => 'required',
            'monto' => 'required',
            'fecha_suscription' => 'required',
            'fecha_certificacion' => 'required',
            'fin_suscription' => 'required',
            'fin_certificacion' => 'required',
            'nota' => 'required',
            'estado' => 'required',
        ]));
        $company->save();
        return redirect('/mycompany');
    }
}
