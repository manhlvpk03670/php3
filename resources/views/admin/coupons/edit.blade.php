@extends('layout.master')

@section('content')
    <h1>Edit Coupon</h1>

    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="code">Coupon Code</label>
            <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $coupon->code) }}" required>
        </div>

        <div class="form-group">
            <label for="discount_percent">Discount Percentage</label>
            <input type="number" name="discount_percent" id="discount_percent" class="form-control" value="{{ old('discount_percent', $coupon->discount_percent) }}" min="0" max="100">
        </div>

        <div class="form-group">
            <label for="discount_amount">Discount Amount</label>
            <input type="number" name="discount_amount" id="discount_amount" class="form-control" value="{{ old('discount_amount', $coupon->discount_amount) }}" min="0">
        </div>

        <div class="form-group">
            <label for="min_order_value">Minimum Order Value</label>
            <input type="number" name="min_order_value" id="min_order_value" class="form-control" value="{{ old('min_order_value', $coupon->min_order_value) }}" required min="0">
        </div>

        <div class="form-group">
            <label for="expires_at">Expiration Date</label>
            <input type="date" name="expires_at" id="expires_at" class="form-control" 
                value="{{ old('expires_at', \Carbon\Carbon::parse($coupon->expires_at)->format('Y-m-d')) }}">
        </div>
        

        <button type="submit" class="btn btn-warning">Update Coupon</button>
    </form>
@endsection
