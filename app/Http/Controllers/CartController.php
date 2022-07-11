<?php


namespace App\Http\Controllers;

use App\FlowerOrders;
use App\FlowersItems;
use App\SiteData;
use DB;
use http\Client\Response;
use Session;
use App\CartItems;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer;
use Mail;

class CartController extends WelcomeController
{


    public function __construct()
    {
        parent::__construct();

    }

    public function index($type = 'single')
    {
        $type = in_array($type, ['single', 'multy']) ? $type : 'single';
        $welcomeData = $this->welcomeData;
        $flowersByRubric = $this->getFlowersByRubrics();
        $fingerprint = Session::get('fingerprint', null);
        $metroStations = $this->metroStations;

        $deliveryPriceInMKAD = $this->deliveryPriceInMKAD;
        $deliveryPriceOutMKAD = $this->deliveryPriceOutMKAD;
        $cartItems = CartItems::select('cartItems.*',
            'flowersItems.preview',
            'flowersItems.id as fId',
            'flowersItems.price',
            'flowersItems.uri'
        )
            ->leftJoin('flowersItems', 'flowersItems.id', '=', 'cartItems.item_id')
            ->where('cartItems.status', 'waiting')
            ->where('fingerprint', $fingerprint)
            ->get();

        $variants = [];

        foreach ($cartItems as $key => $cartItem) {

            $v = FlowersItems::where('parentId', $cartItem->fId)->get();

            if (!empty($v) && count($v) > 0) {
                $variants[$cartItem->fId] = $v;
            }
        }


        return view('cart.' . $type, compact(
            'metroStations',
            'cartItems',
            'welcomeData',
            'deliveryPriceOutMKAD',
            'variants',
            'deliveryPriceInMKAD',
            'flowersByRubric'
        ));
    }

    public function addCartItem(Request $request)
    {

        $cartIt = CartItems::where('status', 'waiting')
            ->where('item_id', $request->dataId)
            ->where('fingerprint', $request->fingerprint)
            ->first();
        $count = 1;
        if (empty($cartIt)) {
            $cartIt = new CartItems();
        } else {
            $count = intval($cartIt->count) + 1;
        }


        $cartIt->fingerprint = $request->fingerprint;
        $cartIt->item_id = $request->dataId;
        $cartIt->status = 'waiting';
        $cartIt->count = $count;


        if ($cartIt->save()) {
            $res = $this->getItemsCount($request->fingerprint);
        } else {
            $res = 0;
        }
        return $res;
    }

    public function cartItemCancel(Request $request)
    {

        $delete = CartItems::where('id', $request->dataId)
            ->delete();

        $res = [];
        if ($delete) {
            $res['success'] = 'Successfully deleted.';
        } else {
            $res['error'] = 'Oops, something went wrong.';
        }

        return response($res, 200)
            ->header('Content-Type', 'application/json');
    }

    public function createOrder(Request $request)
    {
        $newOrder = new FlowerOrders();
        $newOrder->pay_date = '2121-12-12 12:12:12';
        $res = [];

        if (!$this->megaValidate('c_group', $request->name1)) {
            $res['error']['flowersName1'] = 'invalid';
        } else {
            $newOrder->first_name = trim($request->name1);
        }

        if (!$this->megaValidate('c_group', $request->name2)) {
            $res['error']['flowersName2'] = 'invalid';
        } else {
            $newOrder->last_name = trim($request->name2);
        }
        if (!$this->megaValidate('email', $request->email)) {
            $res['error']['flowersEmail'] = 'invalid';
        } else {
            $newOrder->email = trim($request->email);
        }

        if (!$this->megaValidate('ph', $request->phone)) {
            $res['error']['flowersPhone'] = 'invalid';
        } else {
            $newOrder->phone = trim($request->phone);
        }

        if (!$this->megaValidate('isDate', $request->date)) {
            $res['error']['flowersDate'] = 'invalid';

        } else {
            if ($request->date >= date('Y-m-d H:i:s')) {
                $newOrder->order_date = trim($request->date);
            } else {
                $res['error']['flowersDate'] = 'min date';
            }
        }

        if (!$this->megaValidate('isDate', $request->time)) {
            $res['error']['flowersDate'] = 'invalid';
        } else {
            $newOrder->time_from = trim($request->time);
        }

        if (!$this->megaValidate('isDate', $request->timeTo)) {
            $res['error']['flowersDate'] = 'invalid';
        } else {
            $newOrder->time_to = trim($request->timeTo);
        }

        if (!in_array($request->delivery_type, ['delivery', 'pickup'])) {
            $res['error']['delivery_1'] = 'invalid';
            $res['error']['delivery_pickup'] = 'invalid';
        } else {
            $newOrder->delivery_type = $request->delivery_type;


        }


        if (!(is_numeric($request->item_id) && intval($request->item_id) > 0)) {
            $res['error'] = 'Item not found';
        } else {
            $newOrder->item_id = $request->item_id;
        }

        if (isset($request->variant)) {
            if (is_numeric($request->variant) && intval($request->variant) > 0) {
                $newOrder->item_id = $request->variant;
            }
        }

        if (!(isset($request->ok) && $request->ok === 'ok')) {
            $res['error']['flowersOkLabel'] = 'invalid';
        }
        if (isset($request->promocode) && !empty($request->promocode)) {
            if (!$this->megaValidate('n_s', $request->promocode)) {
                $res['error']['flowersPromocode'] = 'invalid';
            } else {
                $newOrder->promocode = $request->promocode;
            }
        } else {
            $newOrder->promocode = '';
        }
        if ($request->delivery_type === 'delivery') {

            if (!$this->megaValidate('ad', $request->address)) {
                $res['error']['flowersAddress'] = 'invalid';
            } else {
                $newOrder->delivery_address = $request->address;
            }

            if (!$this->megaValidate('n_s_blist', $request->toWhom)) {
                $res['error']['flowersToWhom'] = 'invalid';
            } else {
                $newOrder->recipient_contact = $request->toWhom;
            }

            if (!$this->megaValidate('n_s_blist', $request->toWhomName)) {
                $res['error']['flowersToWhomName'] = 'invalid';
            } else {
                $newOrder->receiver_name = $request->toWhomName;
            }
            if (isset($request->cardText) && !empty($request->cardText)) {

                if (!$this->megaValidate('n_s_blist', $request->cardText)) {
                    $res['error']['flowersCardText'] = 'invalid';
                } else {
                    $newOrder->postcard_signature = $request->cardText;
                }
            } else {
                $newOrder->postcard_signature = '';
            }


            if (!$this->megaValidate('c_group', $request->toWhom)) {
                $res['error']['flowersToWhom'] = 'invalid';
            } else {
                $newOrder->recipient_contact = $request->toWhom;
            }

            if (isset($request->metro) && trim($request->metro) !== "") {
                if (!in_array($request->metro, $this->metroStations)) {
                    $res['error']['flowersMetro'] = 'invalid';
                } else {
                    $newOrder->nearest_m_station = $request->metro;
                }
            } else {
                $newOrder->nearest_m_station = '';
            }


            if (!in_array($request->deliveryWhere, ['in', 'out'])) {
                $res['error']['flowersDel1'] = 'invalid';
                $res['error']['flowersDel2'] = 'invalid';
            } else {
                $newOrder->mkad = $request->deliveryWhere;
            }
        } else {
            $newOrder->delivery_address = '';
            $newOrder->recipient_contact = '';
            $newOrder->postcard_signature = '';
            $newOrder->nearest_m_station = '';
            $newOrder->receiver_name = '';
            $newOrder->promocode = '';
        }
        $newOrder->fingerprint = $request->fingerprint;
        $flItem = FlowersItems::where('id', $newOrder->item_id)->first();
        if (!isset($flItem->id)) {
            $res['error'] = 'Oops, looks like item not found.';
        }

        if (!isset($res['error'])) {
            $newOrder->ms_status = '';
            $newOrder->ms_id = '';
            $newOrder->delivery_price = 0;
            $newOrder->item_price = intval($flItem->price);

            if ($newOrder->delivery_type === 'delivery') {
                if ($newOrder->mkad === 'in') {
                    $newOrder->delivery_price = $this->deliveryPriceInMKAD;
                } else {
                    $newOrder->delivery_price = $this->deliveryPriceOutMKAD;
                }
            }

            $r = $newOrder->save();
            if ($r) {

                $res['redirectLink'] = '/successOrder/' . $newOrder->id . '_fng_' . $newOrder->fingerprint;

            } else {
                $res['error'] = 'Something went wrong.';
            }
        }

        die(json_encode($res));

    }

    public function personalOrder($personalID)
    {
        $flower = (object)[];
        $flower->price = 0;
        $flower->isPersonal = true;
        $resPersonal = [];

        if (isset($personalID) && !empty($personalID)) {
            $resPersonal = [];
            $r = DB::table('personalBanners')
                ->where('link', $personalID)
                ->where('status', 'active')
                ->first();

            if (isset($r->id)) {
                $flower->id = $r->id;
                $flower->image = $r->banner_inner;
                $flower->banner_text = $r->banner_text;
            } else {
                $resPersonal['error'] = 'Ммм, похоже, страница больше не доступна.';
            }

        } else {
            $resPersonal['error'] = 'Страница не найдена';
        }
        $welcomeData = $this->welcomeData;
        $metroStations = $this->metroStations;
        $deliveryPriceInMKAD = $this->deliveryPriceInMKAD;
        $deliveryPriceOutMKAD = $this->deliveryPriceOutMKAD;
        return view('flower.personal', compact(
            'flower',
            'resPersonal',
            'welcomeData',
            'metroStations',
            'deliveryPriceInMKAD',
            'deliveryPriceOutMKAD'
        ));
    }

    public function createOrderSingleCart(Request $request)
    {

        $res = [];

        if (!$this->megaValidate('c_group', $request->name1)) {
            $res['error']['flowersName1'] = 'invalid';
        } else {
            $first_name = trim($request->name1);
        }

        if (!$this->megaValidate('c_group', $request->name2)) {
            $res['error']['flowersName2'] = 'invalid';
        } else {
            $last_name = trim($request->name2);
        }
        if (!$this->megaValidate('email', $request->email)) {
            $res['error']['flowersEmail'] = 'invalid';
        } else {
            $email = trim($request->email);
        }

        if (!$this->megaValidate('ph', $request->phone)) {
            $res['error']['flowersPhone'] = 'invalid';
        } else {
            $phone = trim($request->phone);
        }

        if (!$this->megaValidate('isDate', $request->date)) {
            $res['error']['flowersDate'] = 'invalid';
        } else {
            if ($request->date >= date('Y-m-d H:i:s')) {
                $order_date = trim($request->date);
            } else {
                $res['error']['flowersDate'] = 'min date';
            }
        }

        if (!$this->megaValidate('isDate', $request->time)) {
            $res['error']['flowersDate'] = 'invalid';
        } else {
            $time_from = trim($request->time);
        }

        if (!$this->megaValidate('isDate', $request->timeTo)) {
            $res['error']['flowersDate'] = 'invalid';
        } else {
            $time_to = trim($request->timeTo);
        }

        if (!in_array($request->delivery_type, ['delivery', 'pickup'])) {
            $res['error']['delivery_1'] = 'invalid';
            $res['error']['delivery_pickup'] = 'invalid';
        } else {
            $delivery_type = $request->delivery_type;
        }

        if (!(isset($request->ok) && $request->ok === 'ok')) {
            $res['error']['flowersOkLabel'] = 'invalid';
        }


        if (isset($request->promocode) && !empty($request->promocode)) {
            if (!$this->megaValidate('n_s', $request->promocode)) {
                $res['error']['flowersPromocode'] = 'invalid';
            } else {
                $promocode = $request->promocode;
            }
        } else {
            $promocode = '';
        }


        if ($request->delivery_type === 'delivery') {

            if (!$this->megaValidate('ad', $request->address)) {
                $res['error']['flowersAddress'] = 'invalid';
            } else {
                $delivery_address = $request->address;
            }

            if (!$this->megaValidate('n_s_blist', $request->toWhom)) {
                $res['error']['flowersToWhom'] = 'invalid';
            } else {
                $recipient_contact = $request->toWhom;
            }

            if (!$this->megaValidate('n_s_blist', $request->toWhomName)) {
                $res['error']['flowersToWhomName'] = 'invalid';
            } else {
                $receiver_name = $request->toWhomName;
            }
            if (isset($request->cardText) && !empty($request->cardText)) {

                if (!$this->megaValidate('n_s_blist', $request->cardText)) {
                    $res['error']['flowersCardText'] = 'invalid';
                } else {
                    $postcard_signature = $request->cardText;
                }
            } else {
                $postcard_signature = '';
            }


            if (!$this->megaValidate('c_group', $request->toWhom)) {
                $res['error']['flowersToWhom'] = 'invalid';
            } else {
                $recipient_contact = $request->toWhom;
            }

            if (isset($request->metro) && trim($request->metro) !== "") {
                if (!in_array($request->metro, $this->metroStations)) {
                    $res['error']['flowersMetro'] = 'invalid';
                } else {
                    $nearest_m_station = $request->metro;
                }
            } else {
                $nearest_m_station = '';
            }


            if (!in_array($request->deliveryWhereSingle, ['in', 'out'])) {
                $res['error']['flowersDel1'] = 'invalid';
                $res['error']['flowersDel2'] = 'invalid';
            } else {
                $mkad = $request->deliveryWhereSingle;
            }
        } else {
            $delivery_address = '';
            $recipient_contact = '';
            $postcard_signature = '';
            $nearest_m_station = '';
            $receiver_name = '';
            $promocode = '';
        }
        $fingerprint = $request->fingerprint;


        if (!isset($res['error'])) {
            $flItemIds = [];
            $cartIds = [];
            $price = 0;
            foreach ($request->item_id as $key => $flowerItem) {
                if (!(is_numeric($flowerItem) && intval($flowerItem) > 0)) {
                    $res['error'] = 'Item not found index:' . $key;
                }
                $flItem = FlowersItems::where('id', $flowerItem)->first();
                if (!isset($flItem->id)) {
                    $res['error'] = 'Oops, looks like item not found.';
                } else {
                    $flItemIds[] = intval($flItem->id);
                    $price += intval($flItem->price);
                    $cartIds[] = isset($request->cart_id[$key]) ? $request->cart_id[$key] : 0;
                }
            }

            $newOrder = new FlowerOrders();
            $newOrder->ms_status = '';
            $newOrder->ms_id = '';
            $newOrder->delivery_price = 0;
            $newOrder->delivery_type = $delivery_type;
            $newOrder->item_id = implode(',', $flItemIds);
            $newOrder->first_name = $first_name;
            $newOrder->last_name = $last_name;
            $newOrder->email = $email;
            $newOrder->phone = $phone;
            $newOrder->fingerprint = $fingerprint;
            $newOrder->order_date = $order_date;
            $newOrder->time_from = $time_from;
            $newOrder->time_to = $time_to;
            $newOrder->delivery_address = $delivery_address;
            $newOrder->nearest_m_station = $nearest_m_station;
            $newOrder->recipient_contact = $recipient_contact;
            $newOrder->receiver_name = $receiver_name;
            $newOrder->postcard_signature = $postcard_signature;
            $newOrder->promocode = $promocode;
            $newOrder->pay_date = '2121-12-12 12:12:12';
            $newOrder->sessionId = '';
            $newOrder->status = 'unseen';
            $newOrder->mkad = $mkad;
            $newOrder->item_price = $price;
            $newOrder->cart_id = implode(',', $cartIds);

            if ($newOrder->delivery_type === 'delivery') {
                if ($newOrder->mkad === 'in') {
                    $newOrder->delivery_price = $this->deliveryPriceInMKAD;
                } else {
                    $newOrder->delivery_price = $this->deliveryPriceOutMKAD;
                }
            }

            $r = $newOrder->save();
            if ($r) {
                CartItems::whereIn('id', $cartIds)->update(['status' => 'done']);
                $this->sendMailOrderConfirmed($newOrder);
//                $res['redirectLink'] = '/successOrder/' . $newOrder->id . '_fng_' . $newOrder->fingerprint;
            } else {
                $res['error'][] = 'Something went wrong.';
            }
        }

        die(json_encode($res));

    }

    public function sendTestMail()
    {
        $newOrder = FlowerOrders::find(1);
        $this->sendMailOrderConfirmed($newOrder);
    }

    public function sendTestMail2()
    {
        $myMail = new PHPMailer\PHPMailer();
//        $myMail->bind('path.public', function() { return __DIR__; });
//        $myMail->isSMTP();
        $myMail->SMTPDebug = 1;

        $myMail->Host = 'smtp.gmail.com';
        $myMail->CharSet = 'UTF-8';
        $myMail->SMTPAuth = true;
        $myMail->Username = 'Hermine Baghdassarian';
        $myMail->Password = '@maIl2#24&55';
        $myMail->SMTPSecure = 'ssl';
        $myMail->Port = 465;

        $myMail->SetFrom('orders@whiteflowers.store', 'WhiteFlowers');
        $myAddress = 'baghdassarianherine@gmail.com';
        $myMail->AddAddress($myAddress, 'Herminein');
        $myMail->IsHTML(true);

        $myMail->Subject = 'Messages theme, Привет мир 2424';
        $myMail->Body = "My message <h1>Header</h1><p>message body</p> <img src='cid:logo_2u' />";
        $myMail->AddEmbeddedImage(public_path().'/images/-blank7234_918h2452.jpg',
            'logo_2u');
//        $myMail->AddEmbeddedImage("https://whiteflowers.store/images/logo-flowers.svg", 'logo');

        if ($myMail->send()) {
            echo 'Namake uxarkvac e ';
        } else {
            echo 'Namake hnaravor che uxarkel <br>';
            echo 'error: ' . $myMail->ErrorInfo;
        }
    }

    public function sendTestMail3()
    {
        Mail::send('My message', ['name', 'web dev blog'], function ($myMessage) {
            $myMessage->to('baghdasaryan.hakob@mail.ru', 'to Hakob')->subject('my test mail');
            $myMessage->from('baghdasaryanhakob3@gmail.com', 'from another Hakob');
        });
    }

    public function sendMailOrderConfirmed($newOrder)
    {

        $mail = new PHPMailer\PHPMailer(); // create a n
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465; // or 587
        $mail->IsHTML(true);
        $mail->Username = "info@whitestudios.ru";
        $mail->Password = "White1905";
        $mail->SetFrom("info@whitestudios.ru", 'WhiteFlowers');
        $mail->Subject = "Ваш заказ принят";

        $orderBody = '';

        $items = FlowersItems::whereIn('id', explode(',', $newOrder->item_id))->get();

        foreach ($items as $key => $item) {
            $mail->AddAttachment($item->preview, 'img' . $key);
            $orderBody .= "<tr>
<td><img src='img$key' style='width: 60px' /></td>
<td><a href='cid:http://whiteflowers.store/flower/$item->uri'>$item->name</a>
$item->text
</td>
<td>$item->price руб․</td>
</tr>";


        }
        if ($newOrder->delivery_type === 'pickup') {
            $deliveryDetails = "<p>Тип доставки самовывоз.</p>";
        } else {
            $m = $newOrder->nearest_m_station !== '' ? " (м․ $newOrder->nearest_m_station)" : '';

            $deliveryDetails = "<table>
                <tr>
                <td>Cумма доставки</td>
                <td>$newOrder->delivery_price</td>
                </tr>
                <tr>
                <td>Адресс доставки</td>
                <td>$newOrder->delivery_address $m </td>
                </tr>
                </table>";
        }
        $orderTable = "<table>$orderBody</table>";

        $mail->AddEmbeddedImage("https://whiteflowers.store/images/logo-flowers.svg", 'logo');

        $body = "<div style='width: 100%;background: #40543f;padding: 40px 0;'>
<div style='background:white;  border:2px solid #EEEEEE; width: 760px; margin: 40px auto; padding: 40px 35px'>
<div style='text-align: center'>
<img src='https://whiteflowers.store/images/logo-flowers.svg' alt='WhiteFlowers logo' width='90px' style='margin: 0 auto;width: 90px;' />
<img src='cid:logo' alt='WhiteFlowers logo' width='90px' style='margin: 0 auto;width: 90px;' />

</div>
<p style='margin:30px 0 '>Здравствуйте!</p>
<p>Спасибо, ваш заказ принят. В ближайшее время с вами свяжется менеджер для подтверждения.</p>
<hr>
<h2 style='margin: 10px 0;color: #4e604d' >Ваш заказ №$newOrder->id</h2>
$orderTable $deliveryDetails
<table>
<tr>
<td>Имя получателя</td>
<td>$newOrder->receiver_name</td>
</tr>
<tr>
<td>Контакт получателя</td>
<td>$newOrder->recipient_contact</td>
</tr>
<tr>
<td>Подпись к открытке</td>
<td>$newOrder->postcard_signature</td>
</tr>
<tr><td colspan='2'></td></tr>
<tr>
<td style='font-size: 22px;color: #4e604d'><strong>Итого:</strong></td>
<td style='text-align: right;font-size: 22px;color: #4e604d;font-weight:bold '>$newOrder->item_price руб․</td>
</tr>
</table>
<hr>
<h6 style='font-size: 22px'>ОСТАЛИСЬ ВОПРОСЫ?</h6>
<p>Хохловский переулок 7-9с3</p>
<p>8 (925) 002-96-36</p>
<p>wfbar@yandex.ru</p>
<hr>
<h4 style='color: #4e604d'>Ваш<strong> WHITE FLOWERS BAR</strong></h4>
</div></div>";

        file_put_contents('mailBody.html', $body);
        $mail->Body = $body;
//        $mail->AddAddress($newOrder->email, $newOrder->first_name . ' ' . $newOrder->last_name);
        $apoAddress = 'baghdasaryanhakob3@gmail.com';
        $mail->AddAddress($apoAddress, 'Me');

        if ($mail->Send()) {
            return 'Email Sent Successfully';
        } else {
            return 'Failed to Send Email';
        }
    }

    public function createOrderMultipleCart(Request $request)
    {


        $res = [];

        if (isset($request->customer)) {
            if (!$this->megaValidate('c_group', $request->customer['name1'])) {
                $res['error']['flowersName1'] = 'invalid';
            } else {
                $first_name = trim($request->customer['name1']);
            }

            if (!$this->megaValidate('c_group', $request->customer['name2'])) {
                $res['error']['flowersName2'] = 'invalid';
            } else {
                $last_name = trim($request->customer['name2']);
            }
            if (!$this->megaValidate('email', $request->customer['email'])) {
                $res['error']['flowersEmail'] = 'invalid';
            } else {
                $email = trim($request->customer['email']);
            }

            if (!isset($request->customer['phone']) ||
                !$this->megaValidate('ph', $request->customer['phone'])) {
                $res['error']['flowersPhone'] = 'invalid';
            } else {
                $phone = trim($request->customer['phone']);
            }

            if (!(isset($request->customer['ok']) && $request->customer['ok'] === '1')) {
                $res['error']['flowersOkLabel'] = 'invalid';
            }

        } else {
            $res['error'] = 'customer is required';
        }
        $orderItem = [];
        if (isset($request->fingerprint)) {
            foreach ($request->orderItems as $key => $val) {

                if (isset($val['cart_id']) && is_numeric($val['cart_id'])) {
                    $cartItem = CartItems::where('fingerprint', $request->fingerprint)
                        ->where('id', $val['cart_id'])
                        ->where('status', 'waiting')
                        ->first();
                    if (isset($cartItem->id)) {

                        $orderItem[$key]['cart_id'] = $cartItem->id;

                        if (!$this->megaValidate('isDate', $val['date'])) {
                            $res['error']['flowersDate' . $cartItem->id] = 'invalid';
                        } else {
                            if ($val['date'] >= date('Y-m-d H:i:s')) {
                                $orderItem[$key]['order_date'] = trim($val['date']);
                            } else {
                                $res['error']['flowersDate' . $cartItem->id] = 'min date';
                            }
                        }

                        if (!$this->megaValidate('isDate', $val['time_from'])) {
                            $res['error']['flowersTime' . $cartItem->id] = 'invalid';
                        } else {
                            $orderItem[$key]['time_from'] = trim($val['time_from']);
                        }

                        if (!$this->megaValidate('isDate', $val['time_to'])) {
                            $res['error']['flowersTimeTo' . $cartItem->id] = 'invalid';
                        } else {
                            $orderItem[$key]['time_to'] = trim($val['time_to']);
                        }

                        if (isset($val['item_id'])) {
                            $flItem = FlowersItems::where('id', $val['item_id'])->first();
                            if (!isset($flItem->id)) {
                                $res['error'] = 'Oops, looks like item not found.';
                            } else {
                                $orderItem[$key]['item_id'] = $flItem->id;
                                $orderItem[$key]['item_price'] = intval($flItem->price);
                            }
                        } else {
                            $res['error'] = 'Oops, looks like item_id is invalid.';
                        }


                        if ($val['delivery_type'] === 'delivery') {

                            $orderItem[$key]['delivery_type'] = 'delivery';
                            if ($val['deliveryWhere'] === 'out') {
                                $orderItem[$key]['mkad'] = 'out';
                                $orderItem[$key]['delivery_price'] = '1000';
                            } else {
                                $orderItem[$key]['mkad'] = 'in';
                                $orderItem[$key]['delivery_price'] = '390';
                            }

                            if (!$this->megaValidate('ad', $val['address'])) {
                                $res['error']['flowersAddress' . $cartItem->id] = 'invalid';
                            } else {
                                $orderItem[$key]['delivery_address'] = $val['address'];
                            }

                            if (!$this->megaValidate('n_s_blist', $val['toWhom'])) {
                                $res['error']['flowersToWhom' . $cartItem->id] = 'invalid';
                            } else {
                                $orderItem[$key]['recipient_contact'] = $val['toWhom'];
                            }

                            if (!$this->megaValidate('n_s_blist', $val['toWhomName'])) {
                                $res['error']['flowersToWhomName' . $cartItem->id] = 'invalid';
                            } else {
                                $orderItem[$key]['receiver_name'] = $val['toWhomName'];
                            }
                            if (isset($val['cardText']) && !empty($val['cardText'])) {

                                if (!$this->megaValidate('n_s_blist', $val['cardText'])) {
                                    $res['error']['flowersCardText' . $cartItem->id] = 'invalid';
                                } else {
                                    $orderItem[$key]['postcard_signature'] = $val['cardText'];
                                }
                            } else {
                                $orderItem[$key]['postcard_signature'] = '';
                            }

                            if (isset($val['metro']) && trim($val['metro']) !== "") {
                                if (!in_array($val['metro'], $this->metroStations)) {
                                    $res['error']['flowersMetro' . $cartItem->id] = 'invalid';
                                } else {
                                    $orderItem[$key]['nearest_m_station'] = $val['metro'];
                                }
                            } else {
                                $orderItem[$key]['nearest_m_station'] = '';
                            }

                        } else {
                            $orderItem[$key]['delivery_price'] === 0;
                            $orderItem[$key]['delivery_address'] === '';
                            $orderItem[$key]['mkad'] === 'in';
                            $orderItem[$key]['recipient_contact'] === '';
                            $orderItem[$key]['nearest_m_station'] === '';
                            $orderItem[$key]['receiver_name'] === '';
                            $orderItem[$key]['postcard_signature'] === '';
                        }

                    } else {
                        $res['error']['cart_id'] = 'cart item not found';
                    }
                } else {
                    $res['error']['cart_id'] = 'invalid';
                }
            }

        } else {
            $res['error']['cart_id'] = 'fingerprint is required';
        }


        if (isset($request->promocode) && !empty($request->promocode)) {
            if (!$this->megaValidate('n_s', $request->promocode)) {
                $res['error']['flowersPromocode'] = 'invalid';
            } else {
                $promocode = $request->promocode;
            }
        } else {
            $promocode = '';
        }
        $orderItems = [];
        if (!isset($res['error'])) {


            foreach ($request->orderItems as $key => $val) {
                $newOrder = new FlowerOrders();
                $newOrder->ms_status = '';
                $newOrder->ms_id = '';
                $newOrder->delivery_price = $orderItem[$key]['delivery_price'];
                $newOrder->delivery_type = $orderItem[$key]['delivery_type'];
                $newOrder->item_id = $orderItem[$key]['item_id'];
                $newOrder->first_name = $first_name;
                $newOrder->last_name = $last_name;
                $newOrder->email = $email;
                $newOrder->phone = $phone;
                $newOrder->fingerprint = $request->fingerprint;
                $newOrder->order_date = $orderItem[$key]['order_date'];
                $newOrder->time_from = $orderItem[$key]['time_from'];
                $newOrder->time_to = $orderItem[$key]['time_to'];
                $newOrder->delivery_address = $orderItem[$key]['delivery_address'];
                $newOrder->nearest_m_station = $orderItem[$key]['nearest_m_station'];
                $newOrder->recipient_contact = $orderItem[$key]['recipient_contact'];
                $newOrder->receiver_name = $orderItem[$key]['receiver_name'];
                $newOrder->postcard_signature = $orderItem[$key]['postcard_signature'];
                $newOrder->promocode = $promocode;
                $newOrder->pay_date = '2121-12-12 12:12:12';
                $newOrder->sessionId = '';
                $newOrder->status = 'unseen';
                $newOrder->mkad = $orderItem[$key]['mkad'];;
                $newOrder->item_price = $orderItem[$key]['item_price'];
                $newOrder->cart_id = $orderItem[$key]['cart_id'];
                $r = $newOrder->save();

                if ($r) {
                    CartItems::where('id', $orderItem[$key]['cart_id'])->update(['status' => 'done']);
                    $orderItems[] = $newOrder->id;
                }
            }
        }

        if (!isset($res['error'])) {
            if (count($orderItems) === count($request->orderItems)) {
                $r1 = FlowerOrders::whereIn('id', $orderItems)->update(['multy_order' => implode('_', $orderItems)]);
                if ($r1) {
                    $res['redirectLink'] = '/successOrderMulti/' . implode('_', $orderItems) . '_fng_' . $request->fingerprint;
                } else {
                    $res['error'][] = 'Something went wrong.';
                }
            } else {
                $res['error'][] = 'Something went wrong. #4';
            }

        }


        die(json_encode($res));

    }

    public function successOrder($id = 0)
    {
        $res = [];
        $welcomeData = $this->welcomeData;
        $rules = SiteData::where('actionName', 'rules')->first();
        $rules = isset($rules->actionValue) ? $rules->actionValue : '';

        $idExp = explode('_fng_', $id);
        $order = [];
        if (count($idExp) === 2) {
            $order = FlowerOrders::where('id', $idExp[0])->where('fingerprint', $idExp[1])->first();
            if (isset($order->id)) {

            } else {
                $res['error'] = 'Заказ не найден';
            }
        } else {
            $res['error'] = 'Id недействителен';
        }


        return view('cart.pay', compact(
            'rules',
            'order',
            'res',
            'welcomeData'
        ));

    }

    public function successOrderMulti($id = 0)
    {
        $res = [];
        $welcomeData = $this->welcomeData;
        $rules = SiteData::where('actionName', 'rules')->first();
        $rules = isset($rules->actionValue) ? $rules->actionValue : '';

        $idExp = explode('_fng_', $id);
        $order = [];
        if (count($idExp) === 2) {
            $order = FlowerOrders::selectRaw(
                'sum(`item_price`)  as item_price ,
                sum(`delivery_price`) as delivery_price ,

                first_name	,last_name,	email')
                ->where('multy_order', $idExp[0])
                ->where('fingerprint', $idExp[1])
                ->groupBy(['multy_order', 'first_name', 'last_name', 'email'])
                ->first();
            if (!empty($order)) {

                $order->multy_order = $idExp[0];
                $res['order'] = $order;
            } else {
                $res['error'] = 'Заказ не найден';
            }
        } else {
            $res['error'] = 'Id недействителен';
        }


        return view('cart.pay', compact(
            'rules',
            'order',
            'res',
            'welcomeData'
        ));

    }

    public function oneFlower($uri)
    {
        $flower = FlowersItems::where('uri', $uri)
            ->where('status', 'visible')
            ->first();


        $welcomeData = $this->welcomeData;
        $metroStations = $this->metroStations;
        $deliveryPriceInMKAD = $this->deliveryPriceInMKAD;
        $deliveryPriceOutMKAD = $this->deliveryPriceOutMKAD;

        if (!empty($flower)) {
            $variants = FlowersItems::where('status', 'visible')
                ->where('parentId', $flower->id)
                ->get();
            return view('flower.main', compact(
                'flower',
                'welcomeData',
                'metroStations',
                'variants',
                'deliveryPriceOutMKAD',
                'deliveryPriceInMKAD'
            ));
        } else {
            abort(404);
        }
    }

    public function getItemsCount($fingerprint)
    {

        $res = CartItems::selectRaw('SUM(count) as c_count')
            ->where('status', 'waiting')
            ->where('fingerprint', $fingerprint)
            ->get();

        return !empty($res) ? intval($res[0]->c_count) : 0; // I DONT KNOW WHY  + 1 :(

    }

    public function cartItemsCount(Request $request)
    {
        $this->setSession('fingerprint', $request->fingerprint);
        return $this->getItemsCount($request->fingerprint);
    }
}
