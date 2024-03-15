<?php

namespace App\Exports;

use App\Models\Tbbarang;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TbbarangExport implements FromCollection, WithHeadings
{
  /**
   * @return \Illuminate\Support\Collection
   */
  public function headings(): array
  {
    return [
      'Id',
      'Kode',
      'Created_at',
      'Updated_at'
    ];
  }
  public function collection()
  {
    return Tbbarang::all();
  }
}
