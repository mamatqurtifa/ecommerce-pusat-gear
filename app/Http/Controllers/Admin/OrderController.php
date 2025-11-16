<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'payment']);
        
        // Filter by user
        if ($request->has('user') && $request->user) {
            $query->where('user_id', $request->user);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Search by order number or customer
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);
        
        // Handle Export
        if ($request->has('export') && $request->export === 'excel') {
            $orders = $query->get();
            return Excel::download(new OrdersExport($orders), 'orders-' . date('Y-m-d') . '.xlsx');
        }
        
        $orders = $query->paginate(15)->withQueryString();
        
        // Get stats for summary
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'confirmed_orders' => Order::where('status', 'confirmed')->count(),
            'shipped_orders' => Order::where('status', 'shipped')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'pending_payments' => Order::where('payment_status', 'pending')->count(),
        ];
        
        return view('admin.orders.index', compact('orders', 'stats', 'request'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment']);
        
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string|max:1000',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;

        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'notes' => $request->notes,
        ]);

        // Handle status changes
        if ($oldStatus !== $request->status) {
            $this->handleStatusChange($order, $oldStatus, $request->status);
        }

        // Handle payment status changes
        if ($oldPaymentStatus !== $request->payment_status) {
            $this->handlePaymentStatusChange($order, $oldPaymentStatus, $request->payment_status);
        }

        return redirect()->route('admin.orders.show', $order)
                        ->with('success', 'Order status successfully updated.');
    }

    public function destroy(Order $order)
    {
        // Only allow deletion of cancelled orders
        if ($order->status !== 'cancelled') {
            return redirect()->route('admin.orders.index')
                           ->with('error', 'Only cancelled orders can be deleted.');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
                        ->with('success', 'Order successfully deleted.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:confirm,ship,deliver,cancel',
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);

        $orders = Order::whereIn('id', $request->order_ids);
        $count = $orders->count();

        switch ($request->action) {
            case 'confirm':
                $orders->where('status', 'pending')->update(['status' => 'confirmed']);
                $message = "{$count} orders successfully confirmed.";
                break;
            case 'ship':
                $orders->where('status', 'confirmed')->update([
                    'status' => 'shipped',
                    'shipped_at' => now()
                ]);
                $message = "{$count} orders successfully marked as shipped.";
                break;
            case 'deliver':
                $orders->where('status', 'shipped')->update([
                    'status' => 'delivered',
                    'delivered_at' => now()
                ]);
                $message = "{$count} orders successfully marked as delivered.";
                break;
            case 'cancel':
                $orders->where('status', 'pending')->update(['status' => 'cancelled']);
                $message = "{$count} orders successfully cancelled.";
                break;
        }

        return redirect()->route('admin.orders.index')->with('success', $message);
    }

    private function handleStatusChange(Order $order, $oldStatus, $newStatus)
    {
        switch ($newStatus) {
            case 'shipped':
                $order->update(['shipped_at' => now()]);
                break;
            case 'delivered':
                $order->update(['delivered_at' => now()]);
                break;
            case 'cancelled':
                // Restore stock if order was cancelled
                foreach ($order->items as $item) {
                    if ($item->product && $item->product->manage_stock) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
                break;
        }

        // Send email notification to customer (will be implemented later)
        // $this->sendStatusChangeNotification($order, $oldStatus, $newStatus);
    }

    private function handlePaymentStatusChange(Order $order, $oldStatus, $newStatus)
    {
        if ($newStatus === 'paid' && $oldStatus !== 'paid') {
            // Auto confirm order when payment is confirmed
            if ($order->status === 'pending') {
                $order->update(['status' => 'confirmed']);
            }
        }
    }

    public function printInvoice(Order $order)
    {
        $order->load(['user', 'items.product']);
        
        return view('admin.orders.invoice', compact('order'));
    }
}