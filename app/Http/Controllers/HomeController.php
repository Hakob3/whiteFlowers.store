<?php

namespace App\Http\Controllers;

use App\CartItems;
use App\FlowerOrders;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()

    {

        $todaysOrders = FlowerOrders::where('created_at', '>=', date('Y-m-d 00:00:00'))
            ->count();
        $todaysCartItems = CartItems::where('created_at', '>=', date('Y-m-d 00:00:00'))
            ->count();

        $todaysPayed = FlowerOrders::selectRaw(' (sum(item_price) + sum(delivery_price)) as totalPayed ')
            ->where('status', 'payed')
            ->where('pay_date', '>=', date('Y-m-d 00:00:00'))
            ->first();


        $chartData = [];
        $chartOrder = [];
        $chartPayed = [];
        for ($i = 9; $i >= 0; $i--) {

            $days_ago = date('Y-m-d', strtotime('-' . $i . ' days', strtotime(date('Y-m-d'))));
            $cartDataKeys[] = $days_ago;
            $orderSum = FlowerOrders::selectRaw(' (sum(item_price) + sum(delivery_price)) as totalPayed ')
                ->where('created_at', '>=', $days_ago . ' 00:00:00')
                ->where('created_at', '<=', $days_ago . ' 23:59:59')
                ->first();
//                        if($i === 0) {
//            DB::enableQueryLog();
//            }

            $payedSum = FlowerOrders::selectRaw(' (sum(item_price) + sum(delivery_price)) as totalPayed ')
                ->where('status', 'payed')
                ->where('pay_date', '>=', $days_ago . ' 00:00:00')
                ->where('pay_date', '<=', $days_ago . ' 23:59:59')
                ->first();
//            if($i === 0) {
//                dd(DB::getQueryLog());
//            }
            $chartOrder[] = isset($orderSum->totalPayed) ? $orderSum->totalPayed : 0;
            $chartPayed[] = isset($payedSum->totalPayed) ? $payedSum->totalPayed : 0;
        }
        $chartData['labels'] = $cartDataKeys;
        $chartData['series'] = [
            $chartOrder, $chartPayed
        ];

        return view('admin.home', compact(
            'todaysOrders',
            'chartData',
            'todaysCartItems',
            'todaysPayed'
        ));
    }
}
