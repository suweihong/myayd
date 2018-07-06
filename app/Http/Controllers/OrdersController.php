<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Bill;
use App\Models\Field;


use Excel;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $store_id = $request->store_id;
        dump($store_id);
        $orders = Order::where('store_id',$store_id)->orderBy('created_at','desc')->paginate(10);
        dump($orders);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //添加订单
    public function store(Request $request)
    {
        $place_id = $request->place_id;
        $time = $request->time;
        $week = $request->week;
        $date = $request->date;
        $type_id = $request->type_id;
        $store_id = $request->store_id;
            //  该日期的商品
        $field = Field::where('store_id',$store_id)->where('place_id',$place_id)->where('type_id',$type_id)->where('time',$time)->where('date',$date)->first();
        if(!$field){
            //该星期的商品
            $field = Field::where('store_id',$store_id)->where('place_id',$place_id)->where('type_id',$type_id)->where('time',$time)->where('week',$week)->first();
        }
        $price = $field->price;//该商品的价格 

        dump($price);

        //生成订单
        $order = Order::create([
            'store_id' => $store_id,
            'status_id' => 3,//订单状态为  已完成
            'type_id' => $type_id,
            'payment_id' => $request->pay_id,
            'date' => $date, //买的 是 哪天的 商品
            'total' => $price,
            'collection' => $request->collection,
            'balance' => $request->balance,
        ]);
                //生成订单状态的 数据
        $order_status = OrderStatus::create([
            'order_id' => $order->id,
            'status_id' => 3,
            'store_id' => $store_id,
        ]);
        dump($order);
        dump($order_status);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        dump($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //修改订单状态
    public function update(Request $request,Order $order)
    {
        $store_id = $request->store_id;
        $month_start = date('Y-m-01',time()); //本月的一号
        //核销订单
       if($order->status_id == 1){
                //订单已核销
          dump(111);
            return response()->json([
                'errcode' => '2',
                'errmsg' => '该订单已经被核销,不能再进行此操作'
            ],200);
       }else{
            if($order->status_id != 3){
                //订单状态不是 已完成
                dump(222);
                 return response()->json([
                    'errcode' => '2',
                    'errmsg' => '该订单还未完成，不能进行此操作'
                 ],200);
            }else{
                    //创建订单的最新状态
                OrderStatus::create([
                    'order_id' => $order->id,
                    'status_id' => 1,
                    'store_id' => $store_id,
                ]);
                    //修改订单的状态
               $res = $order->update([
                    'status_id' => '1',
                ]); 
                    //修改账单
                $store_id = $request->store_id;
                $bill = Bill::where('store_id',$store_id)->where('time_start',$month_start)->first();
                $bill->update([
                    'total' => $bill->total + $order->total,
                    'collection' => $bill->collection + $order->collection,
                    'balance' => $bill->balance + $order->balance,    
                ]);

                $field = $order->fields()->get();//该订单包含的商品

                if($res){
                    dump(33);
                    return response()->json([
                        'errcode' => '1',
                        'errmsg' => '订单核销成功'
                    ],200);
                }else{
                    dump(44);
                }

            }            
       }

       
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //导出数据
    public function export(Request $request)
    {
        $time = date('Y-m-d H-i-s');
         
        $store_id = $request->store_id;
        $orders_list = Order::where('store_id',$store_id)->orderBy('created_at','desc')->paginate(10);
        if($orders_list->isEmpty()){
            dd(333);
            // return back()->with('warning','搜索到的结果为空！');
        }else{
            foreach ($orders_list as $key => $order) {
               $orders[$key]['id'] = $order['id'];
               $orders[$key]['price'] = $order['total'];
               $orders[$key]['store'] = $order->store->title.'【'.$order->type->name.'】';
               $orders[$key]['client'] = $order->client->nick_name ;
               $orders[$key]['time'] = (string)$order->created_at;
               $orders[$key]['status'] = $order->new_status()->name;
            }
        }   
       
        array_unshift($orders, ['订单号','价格','场馆','购买信息','下单时间','状态']);

        $fw='A1:F'.count($orders);//居中的范围
                Excel::create(iconv('UTF-8', 'GBK', '订单列表'.$time),function($excel) use ($orders,$fw){
                        $f=$fw;
                        $excel->sheet('score', function($sheet) use ($orders,$f){
                            $sheet->rows($orders);
                            $sheet->setWidth([               // 设置多个列  
                                'A' => 12,  
                                'B' => 10,
                                'C' => 25,
                                'D'=> 12,
                                'E'=> 20,
                                'F' => 15,  
                            ]);
                            $sheet->cells($f,function($cells) { 
                                //$f是范围。匿名函数设置居中对齐
                               $cells->setAlignment('center');
                            });
                        });
                })->export('xls');
    }
}
