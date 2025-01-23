<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Контроллер для расчёта стоимости доставки:
 * - Коробки (XL, L, M, S)
 * - Мебель
 * - Двери
 * - ТВ
 * - Доп. мили
 * - Страховка
 * + Минимальная сумма $150
 */
class CalculatorController extends Controller
{
    /**
     * Показать форму калькулятора (GET /calculator).
     */
    public function index()
    {
        return view('calculator.index');
    }

    /**
     * Обработать форму и вернуть результат (POST /calculator).
     */
    public function calculate(Request $request)
    {
        // 1. Извлекаем входные данные (пример)
        $boxes_xl = $request->input('boxes_xl', 0);
        $boxes_l  = $request->input('boxes_l', 0);
        $boxes_m  = $request->input('boxes_m', 0);
        $boxes_s  = $request->input('boxes_s', 0);

        // Мебель - массив объектов: furniture[0][length] etc.
        $furniture_items = $request->input('furniture', []);

        // Двери
        $doors_quantity = $request->input('doors_quantity', 0);

        // ТВ
        $tv_under64 = $request->input('tv_under64', 0);
        $tv_65to80  = $request->input('tv_65to80', 0);
        $tv_over80  = $request->input('tv_over80', 0);

        // Доп. мили
        $extra_miles = $request->input('extra_miles', 0);

        // Страховка (boolean)
        $insurance = $request->boolean('insurance', false);

        // 2. Тарифы / константы
        $cost_per_mile  = 2;    // $2 за милю
        $min_cost       = 150;  // минимальная сумма
        $insurance_rate = 0.10; // 10% страховка

        // Коробки
        $price_xl = 90;
        $price_l  = 75;
        $price_m  = 60;
        $price_s  = 60;

        // Двери
        $door_unit_price = 150;

        // ТВ
        $tv_price_under64 = 100;
        $tv_price_65to80  = 150;
        $tv_price_over80  = 200;

        // 3. Расчёт коробок
        $boxes_cost = ($price_xl * $boxes_xl)
                    + ($price_l  * $boxes_l)
                    + ($price_m  * $boxes_m)
                    + ($price_s  * $boxes_s);

        // 4. Мебель
        $furniture_cost = 0;
        if (is_array($furniture_items)) {
            foreach ($furniture_items as $item) {
                $length   = floatval($item['length']   ?? 0);
                $width    = floatval($item['width']    ?? 0);
                $height   = floatval($item['height']   ?? 0);
                $quantity = intval($item['quantity']   ?? 1);

                $single_cost = ($length * $width * $height * 0.012); 
                $furniture_cost += $single_cost * $quantity;
            }
        }

        // 5. Двери
        $doors_cost = $doors_quantity * $door_unit_price;

        // 6. ТВ
        $tv_cost = ($tv_under64 * $tv_price_under64)
                 + ($tv_65to80  * $tv_price_65to80)
                 + ($tv_over80  * $tv_price_over80);

        // 7. Доп. мили
        $distance_cost = 0;
        if ($extra_miles > 0) {
            $distance_cost = $extra_miles * $cost_per_mile;
        }

        // 8. Суммируем (без страховки)
        $calculated_total = $boxes_cost + $furniture_cost + $doors_cost + $tv_cost + $distance_cost;

        // 9. Страховка
        $insurance_cost = 0;
        if ($insurance) {
            $insurance_cost = $calculated_total * $insurance_rate;
        }

        $final_cost = $calculated_total + $insurance_cost;

        // 10. Минимальная сумма
        if ($final_cost < $min_cost) {
            $final_cost = $min_cost;
        }

        // 11. Формируем массив позиций (для дальнейшего сохранения)
        $items_for_order = [
            ['type'=>'box','subtype'=>'XL','quantity'=>$boxes_xl,'cost_each'=>$price_xl],
            ['type'=>'box','subtype'=>'L', 'quantity'=>$boxes_l, 'cost_each'=>$price_l],
            ['type'=>'box','subtype'=>'M', 'quantity'=>$boxes_m, 'cost_each'=>$price_m],
            ['type'=>'box','subtype'=>'S', 'quantity'=>$boxes_s, 'cost_each'=>$price_s],
            ['type'=>'door','subtype'=>null, 'quantity'=>$doors_quantity,'cost_each'=>$door_unit_price],
            ['type'=>'tv','subtype'=>'under64','quantity'=>$tv_under64,'cost_each'=>$tv_price_under64],
            ['type'=>'tv','subtype'=>'65to80','quantity'=>$tv_65to80,'cost_each'=>$tv_price_65to80],
            ['type'=>'tv','subtype'=>'over80','quantity'=>$tv_over80,'cost_each'=>$tv_price_over80],
        ];

        // Добавим мебель:
        foreach ($furniture_items as $f) {
            $length   = floatval($f['length']   ?? 0);
            $width    = floatval($f['width']    ?? 0);
            $height   = floatval($f['height']   ?? 0);
            $quantity = intval($f['quantity']   ?? 1);
            $cost_each= ($length * $width * $height * 0.012);

            $items_for_order[] = [
                'type'     => 'furniture',
                'subtype'  => null,
                'quantity' => $quantity,
                'cost_each'=> $cost_each
            ];
        }

        // Фильтруем, убираем нулевые quantity
        $items_for_order = array_filter($items_for_order, fn($i) => ($i['quantity']>0));

        // 12. Возвращаем итог в Blade
        return view('calculator.result', [
            'boxes_cost'       => $boxes_cost,
            'furniture_cost'   => $furniture_cost,
            'doors_cost'       => $doors_cost,
            'tv_cost'          => $tv_cost,
            'distance_cost'    => $distance_cost,
            'insurance_cost'   => $insurance_cost,
            'calculated_total' => $calculated_total,
            'final_cost'       => $final_cost,
            // Скрытые данные для orders.store
            'items_for_order'  => $items_for_order,
            'insurance'        => $insurance,
            'extra_miles'      => $extra_miles,
        ]);
    }
}