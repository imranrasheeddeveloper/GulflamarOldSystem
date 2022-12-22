<?php

namespace App\Exports;

use App\Models\employee;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Resources\employeeExportResource;

use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class employeeExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($search,int $limit,$iqama_expiry_date,$vendor,$client,$status)
    {
        $this->search = $search;
        $this->limit = (int) $limit;
        $this->iqama_expiry_date = $iqama_expiry_date;
        $this->vendor = $vendor;
        $this->client = $client;
        $this->status = $status;
    }


    public function collection()
    {
        $query = employee::query();

        if ($this->search != '' && $this->search) {
            $records = $query->where(DB::raw("CONCAT(`name`, ' ', `emp_id`)"), 'LIKE', "%" . $this->search . "%");
        }

        if ($this->iqama_expiry_date != '' && $this->iqama_expiry_date) {
            $query->whereDate('iqama_expiry_date', '<=', $this->iqama_expiry_date);
        }

        if ($this->vendor != '' && $this->vendor) {
            $query->where('vendor', $this->vendor);
        }

        if ($this->client != '' && $this->client) {
            $query->where('client', $this->client);
        }

        if ($this->status != '' && $this->status) {
            $query->where('status', 'LIKE', "%" . $this->status . "%");
        }

        if ($this->limit != '' && $this->limit) {

            $query->limit($this->limit);
        }
        
        $query->orderBy('emp_id','desc');
        
        $records = $query->get();

        return employeeExportResource::collection($records);
    }

    public function headings(): array
    {
        return ["Name", "GL ID", "Nationality", "Religion", "Date Of Birth", "Joining Date", "Age", "Contact Number", "Benefits", "IBAN", "Vacation Date", "Notes", "Iqama Number", "Iqama Expiry", "Iqama Profession", "Passport Number", "Passport Expiry", "Ajeer Expiry", "Insurance Expiry", "Vendor", "Salary", "Client", "Status", "Accommodation", "Start Date", "Stop Date", "English Language", "Arabic Language", "Hindi Language", "Presentable Rating", "Beard", "Skills", "Misconduct Report", "Overall Rating"];
    }
}
