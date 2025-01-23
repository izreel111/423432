@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Order #{{ $order->id }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Total Cost:</strong> ${{ number_format($order->total_cost, 2) }}</p>
    <p><strong>Client:</strong> {{ $order->client_name }} ({{ $order->client_phone }})</p>
    <p><strong>Email:</strong> {{ $order->client_email }}</p>
    <p><strong>Pickup:</strong> {{ $order->pick_up_address }}</p>
    <p><strong>Delivery:</strong> {{ $order->delivery_address }}</p>
    <p><strong>ZIP:</strong> {{ $order->zip_code }}</p>
    <p><strong>State:</strong> {{ $order->delivery_state }}</p>
    <p><strong>Insurance:</strong> {{ $order->insurance ? 'Yes' : 'No' }}</p>

    <hr>
    <h4>Order Items</h4>
    @if($order->orderItems->count())
        <table class="table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Subtype</th>
                    <th>Quantity</th>
                    <th>Cost</th>
                </tr>
            </thead>
            <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->item_type }}</td>
                    <td>{{ $item->item_subtype }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->cost, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No items found.</p>
    @endif
</div>
@endsection