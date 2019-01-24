<?php

namespace App\Imports;

use App\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            return new Siswa([
                'nis' => $row['nis'],
                'name' => $row['name'],
                'no_hp' => $row['no_hp'],
                'kelas_id' => $row['kelas_id'],
                'th_ajaran_id' => $row['th_ajaran_id'],
                'pagi' => $row['pagi'],
                'siang' => $row['siang']
            ]);
        }
    }
}
