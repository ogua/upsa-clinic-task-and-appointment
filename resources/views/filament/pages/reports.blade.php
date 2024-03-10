<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <button type="button" class="rounded bg-white-400 shadow py-2 px-2" id="generatereport">
          Generate Report
        </button>
         
    </x-filament-panels::form>
</x-filament-panels::page>