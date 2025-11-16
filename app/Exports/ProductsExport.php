<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->products;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'SKU',
            'Name',
            'Category',
            'Price',
            'Sale Price',
            'Stock',
            'Min Stock',
            'Manage Stock',
            'Weight (g)',
            'Status',
            'Featured',
            'Created Date',
        ];
    }

    /**
     * @param mixed $product
     * @return array
     */
    public function map($product): array
    {
        return [
            $product->id,
            $product->sku,
            $product->name,
            $product->category?->name ?? '-',
            number_format($product->price, 0, ',', '.'),
            $product->sale_price ? number_format($product->sale_price, 0, ',', '.') : '-',
            $product->manage_stock ? $product->stock : 'Unlimited',
            $product->manage_stock ? $product->min_stock : '-',
            $product->manage_stock ? 'Yes' : 'No',
            $product->weight ? $product->weight . 'g' : '-',
            $product->is_active ? 'Active' : 'Inactive',
            $product->is_featured ? 'Yes' : 'No',
            $product->created_at->format('d M Y'),
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