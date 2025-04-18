<h2>Xin chào {{ $order->user->full_name }},</h2>

<p>Trạng thái đơn hàng #{{ $order->id }} của bạn đã được cập nhật thành: <strong>{{ $order->status }}</strong></p>

<h3>Chi tiết đơn hàng:</h3>
<ul>
    @foreach($order->orderDetails as $detail)
        <li>
            {{ $detail->productVariant->product->name }} - 
            SL: {{ $detail->quantity }} - 
            Giá: {{ number_format($detail->price, 0, ',', '.') }}đ
        </li>
    @endforeach
</ul>

<p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }}đ</p>

<p>Cảm ơn bạn đã mua hàng tại cửa hàng của chúng tôi!</p>
