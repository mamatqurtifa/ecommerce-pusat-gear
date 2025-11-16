<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->categories;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Slug',
            'Description',
            'Total Products',
            'Status',
            'Created Date',
        ];
    }

    /**
     * @param mixed $category
     * @return array
     */
    public function map($category): array
    {
        return [
            $category->id,
            $category->name,
            $category->slug,
            $category->description ?? '-',
            $category->products_count ?? $category->products()->count(),
            $category->is_active ? 'Active' : 'Inactive',
            $category->created_at->format('d M Y'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}