<?php


namespace App\Http\Controllers;

use App\FlowerOrders;
use Illuminate\Http\Request;

class PaymentController extends WelcomeController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function orderPay(Request $request)
    {
        $res = [];

        $isMulti = false;
        if ($request->isSingle == 'true') {
            $order = FlowerOrders::where('fingerprint', $request->fingerprint)
                ->where('id', $request->orderId)
                ->where('status', '!=', 'payed')
                ->first();

            if (isset($order->id)) {
                $orderid = $order->id;
            } else {
                $orderid = 0;
            }
        } else {

            $order = FlowerOrders::selectRaw(
                'sum(`item_price`)  as item_price ,
                sum(`delivery_price`) as delivery_price ,
                first_name	,last_name,	email')
                ->where('multy_order', $request->orderId)
                ->where('fingerprint', $request->fingerprint)
                ->where('status', '!=', 'payed')
                ->groupBy(['multy_order', 'first_name', 'last_name', 'email'])
                ->first();
            if (isset($order->item_price)) {
                $orderid = $request->orderId;
                $isMulti = true;
            } else {
                $orderid = 0;
            }

        }


        if (!empty($orderid)) {
            $res = $this->payByCard($orderid, intval($order->item_price) + intval($order->delivery_price) , $isMulti);
        } else {
            $res['error'] = '<p>Oops, looks like order not found. </p> <p>Or you have already payed.</p>';
        }

        die(json_encode($res));
    }

    public function paymentMap(Request $request)
    {
        $res = [];
        $welcomeData = $this->welcomeData;


        if (isset($request->merchant_order_id, $request->order_id)) {

            $order = FlowerOrders::where('id', $request->order_id)
                ->first();
            $isMulti = false;
            if (!isset($order->id)) {
                $order = FlowerOrders::selectRaw(
                    'sum(`item_price`)  as item_price ,
                sum(`delivery_price`) as delivery_price ,
                first_name	,last_name,	email')
                    ->where('multy_order', $request->orderId)
                    ->where('status', '!=', 'payed')
                    ->groupBy(['multy_order', 'first_name', 'last_name', 'email'])
                    ->first();

                if(isset($order->item_price)) {
                    $isMulti = true;
                    $orderId = $request->orderId;
                } else {
                    $orderId = 0;
                }

            } else {
                $orderId = $order->id;
            }

            if (!empty($orderId)) {

                if (isset($request->success) && $request->success == true) {

                    if(!$isMulti) {
                        if ($order->status != 'payed') {
                            $order->status = 'payed';
                            $order->merchant_order_id = $request->merchant_order_id;
                            $order->pay_date = date('Y-m-d H:i:s');
                            $order->save();
                        }
                    } else {
                        FlowerOrders::where('multy_order' ,$orderId )
                            ->update([
                                'status' => 'payed',
                                'merchant_order_id' => $request->merchant_order_id,
                                'pay_date' => date('Y-m-d H:i:s'),
                            ]);
                    }



                    $res['success'] = '<h2 class="mt-3 mb-3">Спасибо</h2>

<p> Ваш заказ <b>#' . $orderId . '</b> на сумму <b>' .
                        (intval($order->item_price) + intval($order->delivery_price)) . ' руб.</b>
 успешно оплачен. </p><p>Наши менеджеры свяжутся с вами в ближайшее время.</p>';
                } else {
                    $res['error'] = '<p>Похоже, ваш заказ не был оплачен.</p>
<p>Пожалуйста, попробуйте позднее</p>';
                }

            } else {
                $res['error'] = '<p>Упс! к сожалению ваш заказ не был найден.</p>
<p><small><i>Чтобы получить дополнительную информацию, скопируйте ссылку и отправьте в нашу службу поддержки, чтобы решить проблему.</i>
</small>
<div><b>' . \Request::getRequestUri() . '</b></div></p>';
            }


            file_put_contents('payment_log' . date('Y-m-d H:i:s') . '.json', json_encode($_REQUEST));
        } else {
            $res['error'] = 'Упс! требуется id заказа';
        }

        return view('payment.main', compact(
            'res',
            'welcomeData'

        ));

    }

    private function payByCard($orderId, $amount , $isMulti = false)
    {
        $res = [];
        $url = $this->mapHost . 'Init';
        $fields = [
            'Key' => $this->inv_mapKey,
            'Password' => $this->inv_mapPass,
            'OrderId' => $orderId,
            'Amount' => $amount * 100,
            'Type' => 'Pay',
            'CustomParams' => 'return_url=https://whiteflowers.store/payment-map/'
        ];
        $fieldsString = '';
        $i = 0;
        foreach ($fields as $key => $value) {
            if ($i) {
                $fieldsString .= '&';
            }
            $fieldsString .= $key . '=' . $value;
            $i++;
        }

        if ($ch = curl_init()) {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
            $output = curl_exec($ch);
            $json = json_decode($output, true);
            $sessionId = $json['SessionGUID'];
            curl_close($ch);


            if($isMulti) {
                FlowerOrders::where('multy_order', $orderId)->update(
                    ['sessionId' => $sessionId]
                );
            } else {
                FlowerOrders::where('id', $orderId)->update(
                    ['sessionId' => $sessionId]
                );
            }

            $res['redirectLink'] = $this->mapHost . 'createPayment/?SessionId=' . $sessionId;
        } else {
            $res['error'] = 'curl error';
        }

        return $res;
    }

//    static function orderPay()
//    {
//
//    }
}
