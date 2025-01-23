@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Calculation Result</h2>
    <ul>
        <li>Boxes Cost: ${{ number_format($boxes_cost, 2) }}</li>
        <li>Furniture Cost: ${{ number_format($furniture_cost, 2) }}</li>
        <li>Doors Cost: ${{ number_format($doors_cost, 2) }}</li>
        <li>TV Cost: ${{ number_format($tv_cost, 2) }}</li>
        <li>Distance Cost: ${{ number_format($distance_cost, 2) }}</li>
        <li>Insurance Cost: ${{ number_format($insurance_cost, 2) }}</li>
    </ul>

    <h4>Final Cost: ${{ number_format($final_cost, 2) }}</h4>

    @guest
        <p>Please <a href="{{ route('login') }}">log in</a> or <a href="{{ route('register') }}">register</a> to proceed.</p>
    @else
        <!-- Форма для оформления заказа (OrderController@store) -->
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf

            <!-- Скрыто передаём финальную сумму -->
            <input type="hidden" name="final_cost" value="{{ $final_cost }}">

            <!-- Скрытые поля для позиций (в JSON) -->
            <input type="hidden" name="items_json" value="{{ json_encode($items_for_order) }}">

            <input type="hidden" name="insurance" value="{{ $insurance ? 1 : 0 }}">
            <input type="hidden" name="extra_miles" value="{{ $extra_miles }}">

            <!-- Контактные данные -->
            <div class="mb-3">
                <label>Name*</label>
                <input type="text" name="client_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Phone*</label>
                <input type="text" name="client_phone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email*</label>
                <input type="email" name="client_email" class="form-control" required>
            </div>

            <!-- Адрес -->
            <div class="mb-3">
                <label>Pick Up Address*</label>
                <input type="text" name="pick_up_address" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>ZIP*</label>
                <input type="text" name="zip_code" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Which state to deliver to?*</label>
                <input type="text" name="delivery_state" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Delivery Address*</label>
                <input type="text" name="delivery_address" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Complete Order</button>
        </form>
    @endguest
</div>
@endsection