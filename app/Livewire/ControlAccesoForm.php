<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chofer;

class ControlAccesoForm extends Component
{


    public $chofer_ci;
    public $chofers_id;
    public $ci;
    public $celular;

    public function updatedChoferCi($value)
    {
        $chofer = Chofer::where('ci', $value)->first();

        if ($chofer) {
            $this->chofers_id = $chofer->id;
            $this->ci = $chofer->ci;
            $this->celular = $chofer->celular;
        } else {
            $this->chofers_id = null;
            $this->ci = null;
            $this->celular = null;
        }
    }
    public function render()
    {
        return view('livewire.control-acceso-form');
    }
}
