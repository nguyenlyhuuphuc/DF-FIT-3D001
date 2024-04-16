<?php

namespace App\Http\Controllers\Client;

use App\Events\OrderSuccessEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Mail\EmailConfirmOrderAdmin;
use App\Mail\EmailConfirmOrderCustomer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPaymentMethod;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(){
        $cart = session()->get('cart', []);

        return view('client.pages.checkout', ['cart' => $cart]);
    }

    public function placeOrder(PlaceOrderRequest $request){

        DB::beginTransaction();
        try{
            $totalPrice = 0;
            foreach (session()->get('cart', []) as $key => $value) {
                $totalPrice += (float)$value['qty'] * (float)$value['price'];
            }
                
            //Save Order
            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->address = $request->address;
            $order->note = $request->note;
            $order->total = $totalPrice;
            $order->status = 'pending';
            $order->save();
    
            //Save Order Items
            $cart = session()->get('cart', []);
            if(!empty($cart)){
                foreach ($cart as $key => $value) {
                    $orderItem = new OrderItem();        
                    $orderItem->price = $value['price'];
                    $orderItem->qty = $value['qty'];
                    $orderItem->name = $value['name'];
                    $orderItem->image = $value['image_url'];
                    $orderItem->product_id = $key;
                    $orderItem->order_id = $order->id;
                    $orderItem->save();
                }
            }
            
            //Save Order Payment Method
            $orderPaymentMethod = new OrderPaymentMethod();
            $orderPaymentMethod->payment_method = $request->payment_method;
            $orderPaymentMethod->status = 'pending';
            $orderPaymentMethod->order_id = $order->id;
            $orderPaymentMethod->total = $totalPrice;
            $orderPaymentMethod->save();
    
            //Update phone of User
            $user = Auth::user();
            $user->phone = $request->phone;
            $user->save();

            //Reset Cart
            session()->put('cart', []);

            DB::commit();

            if(in_array($request->payment_method, ['vnpay_atm', 'vnpay_emv'])){
                $url = $this->proccessWithVnPay($order, $request->payment_method);
                
                return redirect()->away($url);
            }
            // payment method = cod
            event(new OrderSuccessEvent($order)); //public event
        
            return redirect()->route('client.index')->with('msg', 'Order thanh cong');
        }catch(Exception $e){
            DB::rollBack();
            // throw new Exception($e->getMessage());
            return redirect()->route('client.index')->with('msg', 'Order that bai');
        }
    }

    public function proccessWithVnPay(Order $order,string $payment): string{
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_TxnRef = (string) $order->id; //Mã giao dịch thanh toán tham chiếu của merchant
        $vnp_Amount =  $order->total; // Số tiền thanh toán
        $vnp_Locale = 'vn'; //Ngôn ngữ chuyển hướng thanh toán
        $vnp_BankCode = ($payment === 'vnpay_atm') ? 'VNBANK' : 'INTCARD';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; //IP Khách hàng thanh toán

        $startTime = date("YmdHis");
        $expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => env('VNP_TMNCODE'),
            "vnp_Amount" =>  1100000,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Thanh toan GD: $vnp_TxnRef",
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => env('VNP_RETURNURL'),
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $expire
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = env('VNP_URL') . "?" . $query;
        $vnpSecureHash =   hash_hmac('sha512', $hashdata, env('VNP_HASHSRET'));
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        return $vnp_Url;
    }

    public function vnpayCallback(Request $request){
        $hashMap = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần'
        ];
        
        $orderId = $request->get('vnp_TxnRef');

        $order = Order::findOrFailed($orderId);

        if($request->get('vnp_ResponseCode') !== "00"){
            $orderPaymentMethod = $order->orderPaymentMethods[0];
            $orderPaymentMethod->status = 'failed';
            $orderPaymentMethod->reason = $hashMap[$request->get('vnp_ResponseCode')] ?? null;
            $orderPaymentMethod->save();
            
            $order->status = 'canceled';
            $order->save();

            return;
        }

        //Update order payment methods
        $orderPaymentMethod = $order->orderPaymentMethods[0];
        $orderPaymentMethod->status = 'success';
        $orderPaymentMethod->save();
        
        //Update order status
        $order->status = 'done';
        $order->save();

        event(new OrderSuccessEvent($order)); //public event
    }
}

