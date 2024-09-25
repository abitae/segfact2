<?php

namespace App\Http\Livewire;

use App\MyCompany;
use App\Traits\LogCustom;
use Livewire\Component;
use Livewire\WithFileUploads;

class MyCompanyNew extends Component
{
    use LogCustom;
    use WithFileUploads;
    public $mc;
    public $ruc;
    public $RazonSocial;
    public $plan;
    public $deuda;
    public $monto;
    public $fecha_suscription;
    public $fecha_certificacion;
    public $fin_suscription;
    public $fin_certificacion;
    public $nota;
    public $nombre;
    public $telefono;
    public $archivo;
    public $estado;

    public function mount()
    {
        if (!$this->mc == null) {
            $this->ruc = $this->mc->ruc;
            $this->RazonSocial = $this->mc->RazonSocial;
            $this->plan = $this->mc->plan;
            $this->deuda = $this->mc->deuda;
            $this->monto = $this->mc->monto;
            $this->fecha_suscription = \Carbon\Carbon::parse($this->mc->fecha_suscription)->format('Y-m-d');
            $this->fecha_certificacion = \Carbon\Carbon::parse($this->mc->fecha_certificacion)->format('Y-m-d');
            $this->fin_suscription = \Carbon\Carbon::parse($this->mc->fin_suscription)->format('Y-m-d');
            $this->fin_certificacion = \Carbon\Carbon::parse($this->mc->fin_certificacion)->format('Y-m-d');
            $this->nota = $this->mc->nota;
            $this->nombre = $this->mc->nombre;
            $this->telefono = $this->mc->telefono;
            $this->archivo = $this->mc->archivo;
            $this->estado = $this->mc->estado;
        }
    }
    public function render()
    {
        return view('livewire.my-company-new');
    }
    public function save()
    {
        try {
            $company = MyCompany::create($this->validate([
                'ruc' => 'required|min:11|max:11',
                'RazonSocial' => 'required',
                'plan' => 'required',
                'deuda' => 'required',
                'monto' => 'required',
                'fecha_suscription' => 'required',
                'fecha_certificacion' => 'required',
                'fin_suscription' => 'required',
                'fin_certificacion' => 'required',
                'nota' => 'required',
                'nombre' => 'required',
                'telefono' => 'required',
                'archivo' => 'nullable|image|max:1024',
                'estado' => 'required',
            ]));
            $this->archivo->store('mycompany/pagos');
            return redirect('/mycompany');
        } catch (\Exception $e) {
            $this->errorLog('Customer update', $e);
            return false;
        }

        return redirect('/mycompany');
    }
    public function update()
    {
        try {
            $company = MyCompany::find($this->mc->id);
            $company->update($this->validate([
                'ruc' => 'required|min:11|max:11',
                'RazonSocial' => 'required',
                'plan' => 'required',
                'deuda' => 'required',
                'monto' => 'required',
                'fecha_suscription' => 'required',
                'fecha_certificacion' => 'required',
                'fin_suscription' => 'required',
                'fin_certificacion' => 'required',
                'nota' => 'required',
                'nombre' => 'required',
                'telefono' => 'required',
                'archivo' => 'nullable|image|max:1024',
                'estado' => 'required',
            ]));
            $company->save();
            return redirect('/mycompany');
        } catch (\Exception $e) {
            $this->errorLog('Customer update', $e);
            return false;
        }
    }
}
