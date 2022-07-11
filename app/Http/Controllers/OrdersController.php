<?php


namespace App\Http\Controllers;

use App\Couriers;
use App\FlowersItems;
use App\MsUsers;
use Illuminate\Http\Request;
use App\FlowerOrders;
use DB;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $msLogin = 'admin@tulyasha842';
    public $msPass = 'ff536b4ed53d';
    public $msOrgId = 'f6dfcff1-93a1-11eb-0a80-09f3000a9ccd';

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {

        $orders = FlowerOrders::select('flowerOrders.*');

        if ($request->status === 'payed') {
            $orders = $orders->where('status', 'payed');
        }
        if ($request->status === 'in_ms') {
            $orders = $orders->where('ms_id', '!=', '');
        }

        $orders->orderby('id', 'desc');
        $orders = $orders->get();


        return view('admin.orders.main', compact('orders'));
    }

    public function couriers()
    {
        $couriers = Couriers::where('cmsDeleted', '0')->get();
        foreach ($couriers as $key => $courier) {
            $courierOrders = FlowerOrders::where('courier', $courier->id)->get();
            $orders = [];
            foreach ($courierOrders as $k => $courierOrder) {
                $orders[] = $courierOrder->id;
            }
            $courier->orders = $orders;
        }
        return view('admin.orders.couriers', compact('couriers'));
    }

    public function edit(Request $request)
    {
        $res = [];
        if (isset($request->order_id)) {
            $order = FlowerOrders::where('id', $request->order_id)->first();
            if (isset($order->id)) {
                $order->courier = intval($request->courier);
                $order->comment_manager = $request->comment_manager;
                if ($order->save()) {
                    $res['success'] = 'successfully updated';
                } else {
                    $res['error'] = 'something went wrong';
                }
            } else {
                $res['error'] = 'order not found';
            }
        } else {
            $res['error'] = 'order id not send';
        }
        die(json_encode($res));
    }

    public function editCourier(Request $request)
    {
        $res = [];
        if (isset($request->courier_name, $request->courier_id)) {
            $courier = Couriers::where('id', $request->courier_id)->first();
            if (isset($courier->id)) {
                $courier->name = $request->courier_name;
                if ($courier->save()) {
                    $res['success'] = 'successfully updated';
                } else {
                    $res['error'] = 'something went wrong';
                }
            } else {
                $res['error'] = 'courier not found';
            }
        } else {
            $res['error'] = 'courier id not send';
        }
        die(json_encode($res));
    }

    public function moyskladAddAgent($name, $phone, $email, $address, $cityId = 1)
    {
        global $baseCon, $moySkladLogin, $moySkladPass, $mysqli;
        $tmpPhone = preg_replace('/[^0-9]/', '', str_replace('+7', '8', $phone));
        $tmpEmail = strtolower($email);
        $moyskladId = 0;
        $msUser = MsUsers::where('phone', $phone)->orWhere('email', $email)->first();

        if (!isset($msUser->id)) {
            $msUser = new MsUsers();
            // ADD AGENT
            $arr = array(
                'name' => $name,
                'email' => $email,
                'phone' => $tmpPhone,
                'actualAddress' => $address,
            );
            $post = json_encode($arr);
            $url = 'https://online.moysklad.ru/api/remap/1.1/entity/counterparty';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $this->msLogin . ':' . $this->msPass);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Content-Length: ' . strlen($post)));
            $output = curl_exec($ch);
            curl_close($ch);
            $json = json_decode($output, true);

            if (isset($json['id'])) {

                $msUser->moyskladId = $json['id'];
                $msUser->cityId = '1';
                $msUser->name = $name;
                $msUser->email = $tmpEmail;
                $msUser->phone = $tmpPhone;
                $msUser->save();
                $moyskladId = $json['id'];
            } else {
                $moyskladId = false;
                echo('<pre>' . print_r($json) . '</pre>');
                die;
            }
        } else {
            $url = 'https://online.moysklad.ru/api/remap/1.1/entity/counterparty/' . $msUser->moyskladId;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $this->msLogin . ':' . $this->msPass);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            $output = curl_exec($ch);
            $json = json_decode($output, true);

            if (isset($json['rows'])) {
                foreach ($json['rows'] as $v) {
                    $tmpEmail1 = isset($v['email']) ? $v['email'] : '';
                    $tmpphone1 = isset($v['phone']) ? $v['phone'] : '';
                    if ($tmpEmail1 !== $tmpphone1 && (
                            strtolower($tmpEmail) == strtolower($tmpEmail1) ||
                            $tmpPhone == preg_replace('/[^0-9]/', '', str_replace('+7', '8', $tmpphone1))
                        )
                    ) {
                        $msUser->email = $tmpEmail1;
                        $msUser->phone = preg_replace('/[^0-9]/', '', str_replace('+7', '8', $tmpphone1));
                        $msUser->save();
                    }

                }
            }
            $moyskladId = $msUser->moyskladId;
        }

        return $moyskladId;
    }

    public function view($orderId)
    {
        return $this->viewF($orderId);
    }

    public function viewF($orderId)
    {
        $order = FlowerOrders::select(
            'flowerOrders.*'
        )
            ->where('flowerOrders.id', $orderId)
            ->first();
        $couriers = Couriers::where('cmsDeleted', '0')->get();
        $flowersIds = explode(',', $order->item_id);
        $positions = FlowersItems::whereIn('id', $flowersIds)->get();
        return view('admin.orders.view', compact('order', 'positions', 'couriers'));

    }

    public function toMs($orderId)
    {
        $res = [];
        $order = FlowerOrders::where('id', $orderId)->first();
        if (isset($order->id)) {


            $tmpAgentId = $this->moyskladAddAgent($order->first_name . ' ' . $order->last_name,
                $order->phone, $order->email, $order->delivery_address, 1);

            if ($tmpAgentId) {
                $i = 0;


                $tmpOrder = array(
                    'name' => '100' . $i . $orderId,
                    "vatEnabled" => false,
                    'organization' => array(
                        'meta' => array(
                            "href" => "https://online.moysklad.ru/api/remap/1.1/entity/organization/" . $this->msOrgId,
                            "type" => "organization",
                            "mediaType" => "application/json"
                        ),
                    ),

                    'agent' => array(
                        'meta' => array(
                            "href" => "https://online.moysklad.ru/api/remap/1.1/entity/counterparty/" . $tmpAgentId,
                            "type" => "counterparty",
                            "mediaType" => "application/json"
                        ),
                    ),

                    'positions' => array(),
                );
                $post = json_encode($tmpOrder);
                $url = 'https://online.moysklad.ru/api/remap/1.1/entity/customerorder';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERPWD, ($this->msLogin . ':' . $this->msPass));
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Content-Length: ' . strlen($post)));
                $output = curl_exec($ch);
                $info = curl_getinfo($ch);
                $json = json_decode($output);

                if (isset($json->errors, $json->errors[0], $json->errors[0]->code) && $json->errors[0]->code === 3006) {

                    $post = $tmpOrder;
                    $post['name'] = $tmpOrder['name'] . '(1)';
                    $post = json_encode($post);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_USERPWD, ($this->msLogin . ':' . $this->msPass));
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Content-Length: ' . strlen($post)));

                    $output = curl_exec($ch);
                    $info = curl_getinfo($ch);
                    $json = json_decode($output);
                    curl_close($ch);
                }


                if (isset($json->id)) {

                    $order->ms_id = $json->id;
                    $order->ms_status = '';
                    $order->save();

                    return $this->viewF($order->id);
                } else {
                    die('<p style="color: red;text-align: center;padding: 50px 0;font-size: 22px;max-width: 560px;margin: 0 auto">
Something went wrong. Contact your technical department and send order ID:' . json_encode($info));
                }


            } else {
                die('agent problem');
            }


        } else {
            die('order not found');
        }

        die(json_encode($res));
    }
}
