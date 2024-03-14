<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <button type="submit" class="rounded bg-white shadow py-2 px-2" id="generatereport">
          Generate Report
        </button>
         
    </x-filament-panels::form>
</x-filament-panels::page>
@push('scripts')
<script>
    alert("working);
</script>
@endpush