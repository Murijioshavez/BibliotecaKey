<?php

namespace App\Exports;

use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LoansExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize, WithStyles
{
    public function __construct(private readonly Builder $query)
    {
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID préstamo',
            'Usuario',
            'Correo',
            'Libro',
            'Autor',
            'ISBN',
            'Categoría',
            'Estado',
            'Fecha de reserva',
            'Fecha de préstamo',
            'Fecha de vencimiento',
            'Fecha de devolución',
        ];
    }

    public function map(mixed $loan): array
    {
        return [
            $loan->id,
            $loan->user?->name,
            $loan->user?->email,
            $loan->book?->title,
            $loan->book?->author,
            $loan->book?->isbn,
            $loan->book?->category,
            $loan->status,
            $this->excelDate($loan->created_at),
            $this->excelDate($loan->fecha_prestamo),
            $this->excelDate($loan->fecha_vencimiento),
            $this->excelDate($loan->returned_at),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I:L' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    private function excelDate(mixed $date): ?float
    {
        return $date ? Date::dateTimeToExcel(Carbon::parse($date)) : null;
    }
}
