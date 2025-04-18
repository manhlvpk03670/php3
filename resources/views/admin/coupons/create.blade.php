@extends('layout.master')

@section('content')
    <h1>Create Coupon</h1>

    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="code">Coupon Code</label>
            <input type="text" name="code" id="code" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="discount_percent">Discount Percentage</label>
            <input type="number" name="discount_percent" id="discount_percent" class="form-control" min="0" max="100">
        </div>

        <div class="form-group">
            <label for="discount_amount">Discount Amount</label>
            <input type="number" name="discount_amount" id="discount_amount" class="form-control" min="0">
        </div>

        <div class="form-group">
            <label for="min_order_value">Minimum Order Value</label>
            <input type="number" name="min_order_value" id="min_order_value" class="form-control" required min="0">
        </div>

        <div class="form-group">
            <label for="expires_at">Expiration Date</label>
            <input type="date" name="expires_at" id="expires_at" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Create Coupon</button>
    </form>
@endsection
