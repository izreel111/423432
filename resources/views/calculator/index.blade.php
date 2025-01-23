@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Shipping Calculator</h1>
    <form action="{{ route('calculator.calculate') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Boxes (XL):</label>
            <input type="number" name="boxes_xl" class="form-control" value="0">
        </div>
        <div class="mb-3">
            <label>Boxes (L):</label>
            <input type="number" name="boxes_l" class="form-control" value="0">
        </div>
        <div class="mb-3">
            <label>Boxes (M):</label>
            <input type="number" name="boxes_m" class="form-control" value="0">
        </div>
        <div class="mb-3">
            <label>Boxes (S):</label>
            <input type="number" name="boxes_s" class="form-control" value="0">
        </div>

        <!-- Для мебели (можно сделать динамическое добавление, но для упрощения пропустим) -->

        <div class="mb-3">
            <label>Doors:</label>
            <input type="number" name="doors_quantity" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label>TV < 64":</label>
            <input type="number" name="tv_under64" class="form-control" value="0">
        </div>
        <div class="mb-3">
            <label>TV 65-80":</label>
            <input type="number" name="tv_65to80" class="form-control" value="0">
        </div>
        <div class="mb-3">
            <label>TV > 80":</label>
            <input type="number" name="tv_over80" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label>Extra Miles:</label>
            <input type="number" name="extra_miles" class="form-control" value="0">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="insurance" class="form-check-input" id="insurance" value="1">
            <label class="form-check-label" for="insurance">Add Insurance (10%)</label>
        </div>

        <button type="submit" class="btn btn-primary">Calculate</button>
    </form>
</div>
@endsection