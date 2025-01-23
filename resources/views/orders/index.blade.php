@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Orders</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Status</th>
                <th>Total Cost</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>
                    <a href="{{ route('orders.show', $order->id) }}">
                        #{{ $order->id }}
                    </a>
                </td>
                <td>{{ $order->user->name ?? 'N/A' }}</td>
                <td>{{ $order->status }}</td>
                <td>${{ number_format($order->total_cost, 2) }}</td>
                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
