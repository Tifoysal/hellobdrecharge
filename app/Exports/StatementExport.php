<?php

namespace App\Exports;

use App\Models\Statement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class StatementExport implements FromCollection,WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function headings(): array
    {
        return [
            'Transaction Number',
            'Datetime',
            'Details',
            'Order Amount (CR)',
            'Order Amount (DB)',
            'Available Balance',
        ];
    }

    public function collection()
    {
        return $this->data;
    }




}
