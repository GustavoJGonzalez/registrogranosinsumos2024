<div>
    {{-- Because she competes with no one, no one can compete with her. --}}

    <x-filament::form wire:submit.prevent="submit">
        <x-filament::input
            label="Número de Cédula del Chofer"
            wire:model="chofer_ci"
            required
        />

        <x-filament::select
            label="Chofer"
            wire:model="chofers_id"
            :options="App\Models\Chofer::pluck('chofer', 'id')"
            searchable
            required
        />

        <x-filament::input
            label="C.I."
            wire:model="ci"
            required
        />

        <x-filament::input
            label="Celular"
            wire:model="celular"
            required
        />

        <!-- Otros campos del formulario aquí -->

        <x-filament::button type="submit">
            Guardar
        </x-filament::button>
    </x-filament::form>
</div>
