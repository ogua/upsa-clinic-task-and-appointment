<x-filament-panels::page>
  <div x-data="reportdata">
  <x-filament::section>
   <x-filament-panels::form>
      <x-filament::input.wrapper>
          <x-filament::input.select x-model="report_type">
              <option value="">Report Type</option>
               <option value="Appointments">Appointments</option>
              <option value="Medical tasks">Medical tasks</option>
          </x-filament::input.select>
      </x-filament::input.wrapper>

      <label>From date </label>
      <x-filament::input.wrapper>
        <x-filament::input
            type="date"
            x-model="from_date"
            placeholder="From date"
        />
      </x-filament::input.wrapper>

      <label>To date </label>
      <x-filament::input.wrapper>
        <x-filament::input
            type="date"
            x-model="to_date"
        />
      </x-filament::input.wrapper>

      <x-filament::button size="sm" x-on:click="generatereport">
        Generate Report
      </x-filament::button>

    </x-filament-panels::form>
  </x-filament::section>
  </div>
</x-filament-panels::page>
<script>
  function reportdata(){
    return { 
      report_type: '',
      from_date: '',
      to_date: '',
      generatereport(){
        if(this.report_type == ""){
          alert('Report type cant be empty');
          return;
        }

        if(this.from_date == ""){
          alert('From date cant be empty');
          return;
        }

        if(this.to_date == ""){
          alert('To date cant be empty');
          return;
        }
        window.open(`/report-download/${this.from_date}/${this.to_date}/${this.report_type}`,'_blank');
      },
      init() {
        this.report_type = 'Expenses'
     }
    }
  }
</script>