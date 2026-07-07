<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CashierPosController
{
    private array $defaultMenu = [
        ['name' => 'Chicken Alfredo Pasta', 'description' => 'Creamy pasta with tender chicken and herbs.', 'price' => 185, 'category' => 'foods', 'image' => 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?auto=format&fit=crop&w=700&q=80', 'stock' => 50, 'is_best_seller' => false],
        ['name' => 'Clubhouse Sandwich', 'description' => 'Layered toast, egg, vegetables, cheese, and savory filling.', 'price' => 145, 'category' => 'foods', 'image' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=700&q=80', 'stock' => 50, 'is_best_seller' => false],
        ['name' => 'Fresh Garden Salad', 'description' => 'Crisp greens with bright dressing and fresh toppings.', 'price' => 110, 'category' => 'foods', 'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=700&q=80', 'stock' => 50, 'is_best_seller' => false],
        ['name' => 'Aligue Pasta', 'description' => 'Rich crab fat pasta.', 'price' => 280, 'category' => 'pasta', 'image' => 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?auto=format&fit=crop&w=700&q=80', 'stock' => 50, 'is_best_seller' => false],
        ['name' => 'Baked Spaghetti', 'description' => 'Baked spaghetti with savory sauce.', 'price' => 260, 'category' => 'pasta', 'image' => 'https://images.unsplash.com/photo-1622973536968-3ead9e780960?auto=format&fit=crop&w=700&q=80', 'stock' => 50, 'is_best_seller' => false],
        ['name' => 'Spaghetti', 'description' => 'Classic spaghetti pasta.', 'price' => 130, 'category' => 'pasta', 'image' => 'https://images.unsplash.com/photo-1551892374-ecf8754cf8b0?auto=format&fit=crop&w=700&q=80', 'stock' => 50, 'is_best_seller' => false],
        ['name' => 'Iced Spanish Latte', 'description' => 'Chilled espresso with sweet, creamy milk.', 'price' => 120, 'category' => 'drinks', 'image' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=700&q=80', 'stock' => 50, 'is_best_seller' => false],
        ['name' => 'Caramel Frappe', 'description' => 'Blended coffee with caramel and whipped cream.', 'price' => 135, 'category' => 'drinks', 'image' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=700&q=80', 'stock' => 50, 'is_best_seller' => false],
        ['name' => 'Fruit Iced Tea', 'description' => 'Refreshing tea with fruity notes and ice.', 'price' => 95, 'category' => 'drinks', 'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=700&q=80', 'stock' => 50, 'is_best_seller' => false],
        ['name' => 'Almond Gateaux', 'description' => 'Available as whole cake or per slice.', 'price' => 900, 'category' => 'cake', 'image' => 'https://images.unsplash.com/photo-1535141192574-5d4897c12636?auto=format&fit=crop&w=700&q=80', 'stock' => 10, 'is_best_seller' => false],
        ['name' => 'Choco Overload', 'description' => 'Rich chocolate cake for celebrations.', 'price' => 900, 'category' => 'cake', 'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=700&q=80', 'stock' => 10, 'is_best_seller' => false],
        ['name' => 'Mango Graham', 'description' => 'Sweet mango graham cake.', 'price' => 850, 'category' => 'cake', 'image' => 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?auto=format&fit=crop&w=700&q=80', 'stock' => 10, 'is_best_seller' => false],
    ];

    private array $defaultInventory = [
        ['name' => 'Coffee Beans', 'unit' => 'g', 'stock' => 5000, 'reorder_level' => 1000],
        ['name' => 'Flour', 'unit' => 'g', 'stock' => 8000, 'reorder_level' => 2000],
        ['name' => 'Butter', 'unit' => 'g', 'stock' => 3000, 'reorder_level' => 1000],
        ['name' => 'Cream', 'unit' => 'g', 'stock' => 2000, 'reorder_level' => 500],
        ['name' => 'Beef', 'unit' => 'g', 'stock' => 1500, 'reorder_level' => 1000],
    ];

    public function data(): JsonResponse
    {
        $this->seedDefaults();

        $menu = DB::table('menu_items')->orderBy('category')->orderBy('name')->get()->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'desc' => $item->description,
            'price' => (float) $item->price,
            'type' => $item->category,
            'img' => $item->image,
            'stock' => (int) $item->stock,
            'bestSeller' => (bool) ($item->is_best_seller ?? false),
            'featured' => (bool) ($item->is_best_seller ?? false),
        ])->values();

        $inventory = DB::table('inventory_items')->orderBy('name')->get()->map(fn ($item) => [
            'name' => $item->name,
            'unit' => $item->unit,
            'stock' => (int) $item->stock,
            'reorder' => (int) $item->reorder_level,
            'reorderPoint' => (int) $item->reorder_level,
        ])->values();

        $itemsByOrder = DB::table('order_items')->get()->groupBy('order_id');
        $hasDeliveryAddress = Schema::hasColumn('orders', 'delivery_address');
        $hasRiderName = Schema::hasColumn('orders', 'rider_name');
        $orders = DB::table('orders')->orderByDesc('id')->get()->map(function ($order) use ($itemsByOrder, $hasDeliveryAddress, $hasRiderName) {
            $orderData = [
                'id' => $order->order_number,
                'queueNum' => (int) $order->queue_number,
                'customerName' => $order->customer_name,
                'source' => $order->source,
                'status' => $order->status,
                'paymentStatus' => $order->payment_status,
                'total' => (float) $order->total,
                'createdAt' => $order->created_at,
                'time' => $order->created_at ? date('g:i:s A', strtotime($order->created_at)) : '',
                'date' => $order->created_at ? date('n/j/Y', strtotime($order->created_at)) : '',
                'items' => ($itemsByOrder[$order->id] ?? collect())->map(fn ($item) => [
                    'id' => $item->menu_item_id,
                    'name' => $item->name,
                    'price' => (float) $item->price,
                    'qty' => (int) $item->quantity,
                ])->values(),
            ];
            if ($hasDeliveryAddress) {
                $orderData['deliveryAddress'] = $order->delivery_address;
            }
            if ($hasRiderName) {
                $orderData['riderName'] = $order->rider_name;
            }

            return $orderData;
        })->values();

        return response()->json([
            'menu' => $menu,
            'inventory' => $inventory,
            'orders' => $orders,
            'customOrders' => [],
            'waste' => [],
            'loyalty' => [],
            'queue' => $orders->max('queueNum') ?? 0,
        ]);
    }

    public function save(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'menu' => ['array'],
            'inventory' => ['array'],
            'orders' => ['array'],
        ]);

        DB::transaction(function () use ($payload) {
            if (array_key_exists('menu', $payload)) {
                $incomingMenuIds = collect($payload['menu'])
                    ->pluck('id')
                    ->filter(fn ($id) => is_numeric($id))
                    ->map(fn ($id) => (int) $id)
                    ->values();

                if ($incomingMenuIds->isEmpty()) {
                    DB::table('menu_items')->delete();
                } else {
                    DB::table('menu_items')->whereNotIn('id', $incomingMenuIds)->delete();
                }

                foreach ($payload['menu'] as $item) {
                    $values = [
                        'name' => $item['name'] ?? 'Menu Item',
                        'description' => $item['desc'] ?? null,
                        'price' => (float) ($item['price'] ?? 0),
                        'category' => $item['type'] ?? 'foods',
                        'image' => $item['img'] ?? null,
                        'stock' => (int) ($item['stock'] ?? 0),
                        'is_available' => ((int) ($item['stock'] ?? 0)) > 0,
                        'is_best_seller' => (bool) ($item['bestSeller'] ?? $item['featured'] ?? false),
                        'updated_at' => now(),
                    ];

                    if (is_numeric($item['id'] ?? null)) {
                        DB::table('menu_items')->where('id', $item['id'])->update($values);
                    } else {
                        DB::table('menu_items')->insert($values + ['created_at' => now()]);
                    }
                }
            }

            if (array_key_exists('inventory', $payload)) {
                $incomingInventoryNames = collect($payload['inventory'])
                    ->pluck('name')
                    ->map(fn ($name) => trim((string) $name))
                    ->filter()
                    ->values();

                if ($incomingInventoryNames->isEmpty()) {
                    DB::table('inventory_items')->delete();
                } else {
                    DB::table('inventory_items')->whereNotIn('name', $incomingInventoryNames)->delete();
                }
            }

            foreach ($payload['inventory'] ?? [] as $item) {
                $name = trim((string) ($item['name'] ?? ''));
                if ($name === '') {
                    continue;
                }

                $values = [
                    'unit' => $item['unit'] ?? 'pcs',
                    'stock' => (int) ($item['stock'] ?? 0),
                    'reorder_level' => (int) ($item['reorder'] ?? $item['reorderPoint'] ?? 0),
                    'updated_at' => now(),
                ];

                $existingItem = DB::table('inventory_items')->where('name', $name)->first();
                if ($existingItem) {
                    DB::table('inventory_items')->where('id', $existingItem->id)->update($values);
                } else {
                    DB::table('inventory_items')->insert($values + ['name' => $name, 'created_at' => now()]);
                }
            }

            foreach ($payload['orders'] ?? [] as $order) {
                $orderNumber = $order['id'] ?? '';
                $existingOrder = DB::table('orders')->where('order_number', $orderNumber)->first();
                $orderCreatedAt = $this->parseOrderTimestamp($order) ?? now();
                $paymentStatus = $order['paymentStatus'] ?? 'paid';
                $paidAt = $paymentStatus === 'paid'
                    ? ($this->parseOrderTimestamp(['createdAt' => $order['paidAt'] ?? null]) ?? ($existingOrder->paid_at ?? now()))
                    : null;

                $orderValues = [
                    'queue_number' => $order['queueNum'] ?? null,
                    'customer_name' => $order['customerName'] ?? null,
                    'source' => $order['source'] ?? 'Cashier POS',
                    'status' => $order['status'] ?? 'received',
                    'payment_status' => $paymentStatus,
                    'subtotal' => $order['total'] ?? 0,
                    'total' => $order['total'] ?? 0,
                    'paid_at' => $paidAt,
                    'updated_at' => now(),
                ];
                if (Schema::hasColumn('orders', 'delivery_address')) {
                    $orderValues['delivery_address'] = $order['deliveryAddress'] ?? null;
                }
                if (Schema::hasColumn('orders', 'rider_name')) {
                    $orderValues['rider_name'] = $order['riderName'] ?? null;
                }

                if ($existingOrder) {
                    DB::table('orders')->where('id', $existingOrder->id)->update($orderValues);
                } else {
                    DB::table('orders')->insert($orderValues + [
                        'order_number' => $orderNumber,
                        'created_at' => $orderCreatedAt,
                    ]);
                }

                $savedOrder = DB::table('orders')->where('order_number', $orderNumber)->first();
                if (!$savedOrder) {
                    continue;
                }

                DB::table('order_items')->where('order_id', $savedOrder->id)->delete();
                $existingMenuIds = DB::table('menu_items')->pluck('id')->map(fn ($id) => (int) $id)->all();
                foreach ($order['items'] ?? [] as $item) {
                    $menuItemId = is_numeric($item['id'] ?? null) ? (int) $item['id'] : null;
                    if ($menuItemId !== null && ! in_array($menuItemId, $existingMenuIds, true)) {
                        $menuItemId = null;
                    }

                    DB::table('order_items')->insert([
                        'order_id' => $savedOrder->id,
                        'menu_item_id' => $menuItemId,
                        'name' => $item['name'] ?? 'Item',
                        'quantity' => (int) ($item['qty'] ?? 1),
                        'price' => (float) ($item['price'] ?? 0),
                        'total' => ((float) ($item['price'] ?? 0)) * ((int) ($item['qty'] ?? 1)),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });

        return $this->data();
    }

    public function storeCustomerOrder(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'order_type' => ['required', 'string', 'in:Dine In,Take Out'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'customer_name' => ['required', 'string', 'max:120'],
            'delivery_address' => ['required', 'string', 'max:500'],
            'delivery_fee' => ['nullable', 'numeric', 'min:0', 'max:9999'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:menu_items,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $order = DB::transaction(function () use ($payload) {
            $cartItems = collect($payload['items'])
                ->map(fn ($item) => [
                    'id' => (int) $item['id'],
                    'qty' => (int) $item['qty'],
                ])
                ->groupBy('id')
                ->map(fn ($items, $id) => [
                    'id' => (int) $id,
                    'qty' => $items->sum('qty'),
                ])
                ->values();

            $menuItems = DB::table('menu_items')
                ->whereIn('id', $cartItems->pluck('id'))
                ->get()
                ->keyBy('id');

            $orderItems = $cartItems->map(function ($cartItem) use ($menuItems) {
                $menuItem = $menuItems->get($cartItem['id']);

                if (! $menuItem || (int) $menuItem->stock <= 0 || ! (bool) $menuItem->is_available) {
                    abort(422, 'One or more menu items are unavailable.');
                }

                if ($cartItem['qty'] > (int) $menuItem->stock) {
                    abort(422, "{$menuItem->name} only has {$menuItem->stock} available.");
                }

                $price = (float) $menuItem->price;
                $quantity = (int) $cartItem['qty'];

                return [
                    'menu_item_id' => (int) $menuItem->id,
                    'name' => $menuItem->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $price * $quantity,
                ];
            });

            $subtotal = $orderItems->sum('total');
            $deliveryFee = (float) ($payload['delivery_fee'] ?? 0);
            $total = $subtotal + $deliveryFee;
            $queueNumber = ((int) DB::table('orders')->lockForUpdate()->max('queue_number')) + 1;
            $orderNumber = 'CM-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4));
            $paymentMethod = $payload['payment_method'] ?? 'Cash';
            $now = now();

            $orderValues = [
                'order_number' => $orderNumber,
                'queue_number' => $queueNumber,
                'customer_name' => $payload['customer_name'] ?? null,
                'source' => 'Customer Menu - '.$payload['order_type'].' - '.$paymentMethod,
                'status' => 'awaiting_payment',
                'payment_status' => 'unpaid',
                'subtotal' => $subtotal,
                'total' => $total,
                'paid_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            if (Schema::hasColumn('orders', 'delivery_address')) {
                $orderValues['delivery_address'] = $payload['delivery_address'] ?? null;
            }

            $orderId = DB::table('orders')->insertGetId($orderValues);

            foreach ($orderItems as $item) {
                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'menu_item_id' => $item['menu_item_id'],
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            return [
                'id' => $orderNumber,
                'queueNum' => $queueNumber,
                'customerName' => $payload['customer_name'] ?? null,
                'deliveryAddress' => $payload['delivery_address'] ?? null,
                'source' => 'Customer Menu - '.$payload['order_type'].' - '.$paymentMethod,
                'status' => 'awaiting_payment',
                'paymentStatus' => 'unpaid',
                'total' => $total,
                'createdAt' => $now->toDateTimeString(),
                'time' => $now->format('g:i:s A'),
                'date' => $now->format('n/j/Y'),
                'items' => $orderItems->map(fn ($item) => [
                    'id' => $item['menu_item_id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $item['quantity'],
                ])->values(),
            ];
        });

        return response()->json(['order' => $order], 201);
    }

    public function storeMenuPhoto(Request $request): JsonResponse
    {
        $photo = $request->file('photo');

        if (! $photo || ! $photo->isValid()) {
            return response()->json(['message' => 'Please choose a valid image.'], 422);
        }

        if ($photo->getSize() > 40 * 1024 * 1024) {
            return response()->json(['message' => 'Image must be 40 MB / 40960 KB or smaller.'], 422);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/x-ms-bmp'];
        $extension = strtolower($photo->getClientOriginalExtension() ?: $photo->extension() ?: 'jpg');
        $mimeType = strtolower($photo->getMimeType() ?: '');

        if (! in_array($extension, $allowedExtensions, true) || ! in_array($mimeType, $allowedMimeTypes, true)) {
            return response()->json(['message' => 'Please choose a JPG, PNG, GIF, WebP, or BMP image.'], 422);
        }

        $directory = public_path('menu-images');
        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = Str::uuid().'.'.$extension;
        $path = $directory.DIRECTORY_SEPARATOR.$filename;

        try {
            if (! File::copy($photo->getRealPath(), $path)) {
                throw new \RuntimeException('Photo copy failed.');
            }
        } catch (\Throwable $exception) {
            return response()->json(['message' => 'Could not save the image.'], 500);
        }

        return response()->json([
            'url' => '/menu-images/'.$filename,
        ]);
    }

    private function seedDefaults(): void
    {
        if (! DB::table('menu_items')->exists()) {
            foreach ($this->defaultMenu as $item) {
                DB::table('menu_items')->insert($item + ['created_at' => now(), 'updated_at' => now()]);
            }
        }

        if (! DB::table('inventory_items')->exists()) {
            foreach ($this->defaultInventory as $item) {
                DB::table('inventory_items')->insert($item + ['created_at' => now(), 'updated_at' => now()]);
            }
        }
    }

    private function parseOrderTimestamp(array $order): ?string
    {
        $rawDate = $order['createdAt'] ?? $order['date'] ?? null;
        if (! $rawDate) {
            return null;
        }

        $rawTime = $order['time'] ?? '';
        $timestamp = strtotime((string) $rawDate);
        if ($rawTime && ! isset($order['createdAt'])) {
            $timestamp = strtotime(trim((string) $rawDate.' '.$rawTime));
        }

        return $timestamp === false ? null : date('Y-m-d H:i:s', $timestamp);
    }
}
