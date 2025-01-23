<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Список заказов (GET /orders).
     */
    public function index()
    {
        // Для упрощения: показываем все заказы
        // (В реальном случае проверяем роль (admin) или показываем только заказы пользователя)
        $orders = Order::with('user')->orderByDesc('id')->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Форма создания (GET /orders/create).
     * Обычно не нужна, т.к. заказ создаётся через калькулятор.
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Сохранение заказа (POST /orders).
     */
    public function store(Request $request)
    {
        // 1. Проверка авторизации
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error','Please log in.');
        }

        // 2. Валидация
        $data = $request->validate([
            'final_cost'       => 'required|numeric|min:0',
            'client_name'      => 'required|string|max:255',
            'client_phone'     => 'required|string|max:50',
            'client_email'     => 'required|email|max:255',
            'pick_up_address'  => 'required|string|max:255',
            'zip_code'         => 'required|string|max:50',
            'delivery_state'   => 'required|string|max:255',
            'delivery_address' => 'required|string|max:255',
            'items_json'       => 'required',
            'insurance'        => 'nullable|boolean',
            'extra_miles'      => 'nullable|numeric',
        ]);

        // 3. Создаём запись в orders
        $order = Order::create([
            'user_id'          => $user->id,
            'status'           => 'new',
            'total_cost'       => $data['final_cost'],
            'distance_cost'    => 0,  // Можно вычислить, если нужно
            'insurance'        => !empty($data['insurance']),
            'client_name'      => $data['client_name'],
            'client_phone'     => $data['client_phone'],
            'client_email'     => $data['client_email'],
            'pick_up_address'  => $data['pick_up_address'],
            'zip_code'         => $data['zip_code'],
            'delivery_state'   => $data['delivery_state'],
            'delivery_address' => $data['delivery_address'],
        ]);

        // 4. Распарсим items_json и создадим записи в order_items
        $items = json_decode($data['items_json'], true);
        if (is_array($items)) {
            foreach ($items as $it) {
                $quantity  = $it['quantity']  ?? 0;
                if ($quantity <= 0) continue;

                $type      = $it['type']      ?? 'unknown';
                $subtype   = $it['subtype']   ?? null;
                $cost_each = $it['cost_each'] ?? 0;
                $total     = $cost_each * $quantity;

                OrderItem::create([
                    'order_id'     => $order->id,
                    'item_type'    => $type,
                    'item_subtype' => $subtype,
                    'quantity'     => $quantity,
                    'cost'         => $total,
                ]);
            }
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order created successfully!');
    }

    /**
     * Показать заказ (GET /orders/{order}).
     */
    public function show(Order $order)
    {
        // Подгрузим связанные order_items
        $order->load('orderItems');

        return view('orders.show', compact('order'));
    }

    /**
     * Редактировать заказ (GET /orders/{order}/edit).
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Обновить заказ (PUT/PATCH /orders/{order}).
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Удалить заказ (DELETE /orders/{order}).
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')
            ->with('success','Order deleted successfully!');
    }
}