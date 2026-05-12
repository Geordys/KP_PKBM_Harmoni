<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RegistrationsExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                $item->nomor_pendaftaran,
                $item->nama,
                $item->paket,
                $item->status,
                $item->created_at,
                $item->nisn,
                $item->nik,
                $item->jk,
                $item->tempat_lahir,
                $item->tanggal_lahir,
                $item->agama,
                $item->alamat,
                $item->rt_rw,
                $item->dusun,
                $item->kelurahan_desa,
                $item->kecamatan,
                $item->kode_pos,
                $item->sekolah_asal,
                $item->skhun,
                $item->ayah_nama,
                $item->ayah_pekerjaan,
                $item->ibu_nama,
                $item->ibu_pekerjaan,
                $item->hp,
                $item->email,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No Pendaftaran',
            'Nama',
            'Paket',
            'Status',
            'Tanggal Daftar',
            'NISN',
            'NIK',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Alamat',
            'RT/RW',
            'Dusun',
            'Kel/Desa',
            'Kecamatan',
            'Kode Pos',
            'Sekolah Asal',
            'SKHUN',
            'Ayah Nama',
            'Ayah Pekerjaan',
            'Ibu Nama',
            'Ibu Pekerjaan',
            'No HP',
            'Email',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header
        $sheet->getStyle('A1:Y1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'], // primary color
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Style data
        $sheet->getStyle('A2:Y' . ($this->data->count() + 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Auto size columns
        foreach (range('A', 'Y') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }
}