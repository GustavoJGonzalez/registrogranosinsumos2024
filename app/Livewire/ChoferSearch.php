<?php

namespace App\Livewire;

use Livewire\Component;


class ChoferSearch extends Component
{
    public $cedula;
    public $nombre_chofer;
    public $ci_chofer;
    public $celular_chofer;

    public function updatedCedula($value)
    {
        $chofer = Chofer::where('cedula', $value)->first();

        if ($chofer) {
            $this->nombre_chofer = $chofer->nombre;
            $this->ci_chofer = $chofer->ci;
            $this->celular_chofer = $chofer->celular;
        } else {
            $this->nombre_chofer = '';
            $this->ci_chofer = '';
            $this->celular_chofer = '';
        }
    }

    public function render()
    {
        return view('livewire.chofer-search');
    }
}
