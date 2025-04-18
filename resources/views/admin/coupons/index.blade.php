@extends('layout.master')

@section('content')
    <h1>Coupons</h1>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-success mb-3">Add New</a>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Code</th>
                <th>Discount %</th>
                <th>Discount Amount</th>
                <th>Min Order</th>
                <th>Expires At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->discount_percent }}</td>
                    <td>{{ $coupon->discount_amount }}</td>
                    <td>{{ $coupon->min_order_value }}</td>
                    <td>{{ $coupon->expires_at }}</td>
                    <td>
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this coupon?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $coupons->links() }}
@endsection
