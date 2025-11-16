<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->orders;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Order Number',
            'Customer Name',
            'Customer Email',
            'Total Items',
            'Total Amount',
            'Order Status',
            'Payment Status',
            'Payment Method',
            'Shipping Address',
            'Order Date',
        ];
    }

    /**
     * @param mixed $order
     * @return array
     */
    public function map($order): array
    {
        return [
            $order->order_number,
            $order->user->name,
            $order->user->email,
            $order->items->count(),
            'Rp ' . number_format($order->total_amount, 0, ',', '.'),
            ucfirst($order->status),
            ucfirst($order->payment_status),
            $order->payment?->payment_method ?? '-',
            $order->shipping_address ?? '-',
            $order->created_at->format('d M Y H:i'),
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