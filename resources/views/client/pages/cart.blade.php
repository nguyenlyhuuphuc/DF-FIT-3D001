@extends('client.layout.master')

@section('content')
<section class="shoping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__table">
                    <table>
                        <thead>
                            <tr>
                                <th class="shoping__product">Products</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $totalPrice = 0;
                            @endphp
                            @foreach ($cart as $id => $item)
                                <tr id="tr-{{ $id }}">
                                    <td class="shoping__cart__item">
                                        <img src="img/cart/cart-1.jpg" alt="">
                                        <h5>{{ $item['name'] }}</h5>
                                    </td>
                                    <td class="shoping__cart__price">
                                        ${{ number_format($item['price'], 2) }}
                                    </td>
                                    <td class="shoping__cart__quantity">
                                        <div class="quantity">
                                            <div class="pro-qty">
                                                <input type="text" value="{{ $item['qty'] }}">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="shoping__cart__total">
                                        @php 
                                        $total = $item['price'] * $item['qty'];
                                        $totalPrice += $total;
                                        @endphp
                                        ${{ number_format($total, 2) }}
                                    </td>
                                    <td class="shoping__cart__item__close">
                                        <span data-id="{{ $id }}" data-url="{{ route('cart.remove.product', ['product' => $id]) }}" class="icon_close"></span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__btns">
                    <a href="#" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                    <a href="#" class="primary-btn cart-btn cart-btn-right"><span class="icon_loading"></span>
                        Upadate Cart</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="shoping__continue">
                    <div class="shoping__discount">
                        <h5>Discount Codes</h5>
                        <form action="#">
                            <input type="text" placeholder="Enter your coupon code">
                            <button type="submit" class="site-btn">APPLY COUPON</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="shoping__checkout">
                    <h5>Cart Total</h5>
                    <ul>
                        <li>Subtotal <span>${{ number_format($totalPrice, 2) }}</span></li>
                        <li>Total <span>${{ number_format($totalPrice, 2) }}</span></li>
                    </ul>
                    <a href="#" class="primary-btn">PROCEED TO CHECKOUT</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('my-script')
<script>
    $(document).ready(function(){
        $('.icon_close').on('click', function(event){
            var url = $(this).data('url');
            var id = $(this).data('id');

            $.ajax({
                url: url, //action of form
                type: 'GET', //method of form
                success: function(data){
                    $('#tr-'+id).empty();

                    Swal.fire({
                        icon: "success",
                        text: data.message,
                    });
                }
            });
        });
    });
</script>
@endsection