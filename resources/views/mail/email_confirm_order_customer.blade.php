<h1>Email confirm Order</h1>

User : {{ $order->user->email }} - {{ $order->user->name }}

<table border="1">
    <tr>
        <th>STT</th>
        <th>Name</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Total</th>
    </tr>
    @php $totalPrice = 0; @endphp
        @foreach ($order->orderItems as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->price }}</td>
            <td>{{ $item->qty }}</td>
            @php
                $total = $item->qty * (float)$item->price;
                $totalPrice += $total;
            @endphp 
            <td>{{ number_format($total, 2) }}</td>
        </tr>
    @endforeach
    <tr>
        <td>Order Total : </td>
        <td colspan="4">{{ number_format($totalPrice, 2) }}</td>
    </tr>
</table>