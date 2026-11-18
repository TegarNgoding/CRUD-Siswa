<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Siswa::select('id', 'nisn', 'nama', 'jenis_kelamin', 'kelas', 'alamat')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'NISN',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Kelas',
            'Alamat',
        ];
    }
}