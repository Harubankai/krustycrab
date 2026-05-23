<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Common mapper so frontend JS doesn't break
    private function mapOrder($o)
    {
        return [
            'db_id' => $o->id,
            'id' => $o->order_id,
            'status' => $o->status,
            'deliveryStep' => $o->delivery_step,
            'totalItems' => collect($o->items)->sum('qty'),
            'totalPrice' => $o->total,
            'customer' => [
                'name' => $o->customer->name ?? '',
                'address' => $o->customer->address ?? '',
                'phone' => $o->customer->phone ?? ''
            ],
            'rider' => $o->rider ? [
                'name' => $o->rider->name,
                'email' => $o->rider->email,
            ] : null,
            'items' => $o->items->map(fn($i) => [
                'name' => $i->name,
                'qty' => $i->quantity,
                'price' => $i->price,
            ]),
            'statusTimestamps' => [
                'acceptedAt' => $o->accepted_at,
                'pickedUpAt' => $o->picked_up_at,
                'inTransitAt' => $o->in_transit_at,
                'arrivedAt' => $o->arrived_at,
                'completedAt' => $o->completed_at,
            ]
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|string',
            'total_items' => 'required|integer',
            'total_price' => 'required|numeric',
            'payment_method' => 'required|string',
            'items' => 'required|array',
        ]);

        try {
            $order = Order::create([
                'order_id' => $data['order_id'],
                'customer_id' => session('user')->id ?? 1,
                'total' => $data['total_price'],
                'total_items' => $data['total_items'],
                'payment_method' => $data['payment_method'],
                'status' => 'Preparing',
            ]);

            foreach ($data['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'name' => $item['name'] ?? 'Item',
                    'price' => $item['price'] ?? 0,
                    'quantity' => $item['qty'] ?? 1,
                ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Customer Side
    public function myOrders()
    {
        $userId = session('user')->id ?? null;
        if (!$userId) return response()->json([]);

        $orders = Order::with(['items', 'rider', 'customer'])
            ->where('customer_id', $userId)
            ->offset(0)->limit(50)->get();
            
        return response()->json($orders->map(fn($o) => $this->mapOrder($o)));
    }

    // Rider Side
    public function availableOrders()
    {
        $riderId = session('user')->id ?? null;
        if (!$riderId) return response()->json([]);

        $orders = Order::with(['items', 'customer', 'rider'])
            ->where(function ($query) use ($riderId) {
                $query->whereNull('rider_id')->where('status', 'Preparing')
                      ->orWhere('rider_id', $riderId);
            })
            ->whereNotIn('status', ['Completed', 'Cancelled'])
            ->get();

        return response()->json($orders->map(fn($o) => $this->mapOrder($o)));
    }

    public function acceptOrder($dbId)
    {
        $riderId = session('user')->id ?? null;
        if (!$riderId) return response()->json(['success' => false, 'message' => 'Unauthorized']);

        $order = Order::find($dbId);
        if (!$order || $order->rider_id !== null) {
            return response()->json(['success' => false, 'message' => 'Order taken']);
        }

        $order->update([
            'rider_id' => $riderId,
            'status' => 'Accepted',
            'delivery_step' => 1,
            'accepted_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function updateStatus($dbId, Request $request)
    {
        $riderId = session('user')->id ?? null;
        $order = Order::where('id', $dbId)->where('rider_id', $riderId)->first();
        if (!$order) return response()->json(['success' => false]);

        $updates = [];
        if ($request->has('status')) $updates['status'] = $request->input('status');
        if ($request->has('delivery_step')) $updates['delivery_step'] = $request->input('delivery_step');
        
        $s = $request->input('status');
        if ($s === 'In Transit') $updates['in_transit_at'] = now();
        if ($s === 'Arrived') $updates['arrived_at'] = now();
        if ($s === 'Completed') $updates['completed_at'] = now();

        $order->update($updates);
        return response()->json(['success' => true]);
    }

    // Admin Stats
    public function adminStatistics()
    {
        $currentYear = date('Y');
        $currentMonth = date('n');

        // Monthly Sales Array (0-indexed for JS charts)
        $monthlySales = array_fill(1, 12, 0); 
        $orders = Order::whereYear('created_at', $currentYear)
            ->whereNotIn('status', ['Cancelled', 'canceled'])
            ->get();

        foreach($orders as $order) {
            $m = (int)$order->created_at->format('n');
            $monthlySales[$m] += $order->total;
        }

        $monthlySalesArray = [];
        for ($i = 1; $i <= $currentMonth; $i++) {
            $monthlySalesArray[] = $monthlySales[$i];
        }

        // Top Selling Items (This month)
        $topItemsHash = [];
        $items = OrderItem::whereHas('order', function($q) use ($currentYear, $currentMonth) {
            $q->whereYear('created_at', $currentYear)
              ->whereMonth('created_at', $currentMonth)
              ->whereNotIn('status', ['Cancelled', 'canceled']);
        })->get();

        foreach($items as $item) {
            if (!isset($topItemsHash[$item->name])) {
                $topItemsHash[$item->name] = 0;
            }
            $topItemsHash[$item->name] += $item->quantity;
        }

        arsort($topItemsHash);
        $topItemsHash = array_slice($topItemsHash, 0, 5, true);

        $topItemsArray = [];
        foreach($topItemsHash as $name => $qty) {
            $topItemsArray[] = [$name, $qty];
        }

        return response()->json([
            'monthlySales' => $monthlySalesArray,
            'topItems' => $topItemsArray
        ]);
    }
}
