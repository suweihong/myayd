<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Bill;
use App\Models\Field;
use App\Models\Field_order;


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
        $place_num = $request->place_num;
        $time = $request->time;//从几点开始
        $long = $request->long;//定多长时间
        $week = $request->week;
        $date = $request->date;
        $type_id = $request->type_id;
        $store_id = $request->store_id;
        $item_id = $request->item_id;
        // $total = $request->total;
        // $field_id = $request->field_id;
         // dump($field_id);


            // 连续开场地 几个 小时
        $end_time = $time + $long;
        $new_hours = [];
        for ($i=$time; $i < $end_time; $i++) { 
            array_push($new_hours,$i);//添加元素
         }
        foreach ($new_hours as $key => $hours) {
           $field = Field::where('place_id',$place_id)->where('date',$date)->where('time',$hours)->first();
           if(!$field){
            $field = Field::where('place_id',$place_id)->where('week',$week)->where('time',$hours)->first();
           }
           if($field->switch == 1 || $field->switch == 2){
                return back()->with('warning','场地已售出或关闭');
           }else{
                $fields[] = $field;
           }
           
         }


            //开启事务
        DB::beginTransaction();
            //改变商品状态
        $total = 0; //订单金额
        foreach ($fields as $key => $field) {
            $res = $field->update([
                'switch' => 2,
            ]);
            if(!$res){
                //事务回滚
                DB::rollBack();
                return response()->json([
                    'errcode' => 2,
                    'errmsg' => '购买失败',
                ],200);
            }
            $total += $field->price;

        }



        // 生成订单
        $order = Order::create([
            'store_id' => $store_id,
            'status_id' => 3,//订单状态为  已完成
            'type_id' => $type_id,
            'payment_id' => $request->pay_id,
            'date' => $date, //买的 是 哪天的 商品
            'item_id' =>$item_id,
            'total' => $total,
            'collection' => $request->collection,
            'balance' => $total - $request->collection,
        ]);
  

        if(!$order){
            //回滚事务
            DB::rollBack();
            return response()->json([
                'errcode' => '2',
                'errmsg' => '购买失败',

            ],200);
        }


                //生成订单状态的 数据
        $order_status = OrderStatus::create([
            'order_id' => $order->id,
            'status_id' => 3,
            'store_id' => $store_id,
        ]);

        if(!$order_status){
            //回滚事务
            DB::rollBack();
            return response()->json([
                'errcode' => '2',
                'errmsg' => '购买失败',

            ],200);

        }

        foreach ($fields as $key => $field) {
                 //order_field 表 添加数据
            $field_order = Field_order::create([
                'order_id' => $order->id,
                'field_id' => $field->id,
                'place_id' => $place_id,
                'place_num' => $place_num,
                'time' => $field->time,
                'order_date' => $date,

            ]);

            if(!$field_order){
                //回滚事务
                DB::rollBack();
                return response()->json([
                    'errcode' => '2',
                    'errmsg' => '购买失败',

                ],200);

            }
        }
      
         // 提交事务
        DB::commit();


        dump($order);
        dump($order_status);
        dump($field_order);

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
        $fields = $order->fields()->get();
        foreach ($fields as $key => $field) {
           $time = $field->pivot->time;
           $place_num = $field->pivot->place_num;
           $field['time'] = $time;
           $field['place_num'] = $place_num;
        }
        dump($fields);
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
        if($order->store_id != $store_id){
            dump(00);
            return response()->json([
                'errcode' => '2',
                'errmsg' => '请输入正确的订单号',
            ],200);
        }else{
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
                        //核销订单
                        
                    //开启事务
                    DB::beginTransaction();
                        //创建订单的最新状态
                    $order_status = OrderStatus::create([
                        'order_id' => $order->id,
                        'status_id' => 1,
                        'store_id' => $store_id,
                    ]);
                    if(!$order_status){
                        DB::rollBack();
                        return response()->json([
                            'errcode' => 2,
                            'errmsg' => '核销订单失败',
                        ],200);
                    }
                        //修改订单的状态
                    $order_update = $order->update([
                        'status_id' => '1',
                    ]); 
                    if(!$order_update){
                        DB::rollBack();
                        return response()->json([
                            'errcode' => 2,
                            'errmsg' => '核销订单失败',
                        ],200);
                    }
                        //修改账单
                
                    $bill = Bill::where('store_id',$store_id)->where('time_start',$month_start)->first();
                    $new_bill = $bill->update([
                        'total' => $bill->total + $order->total,
                        'collection' => $bill->collection + $order->collection,
                        'balance' => $bill->balance + $order->balance,    
                    ]);
                    if(!$new_bill){
                        DB::rollBack();
                        return response()->json([
                           'errcode' => 2,
                           'errmsg' => '核销订单失败', 
                        ],200);
                    }

                    $fields = $order->fields()->get();//该订单包含的商品
                        //修改商品的状态为 正常
                    foreach ($fields as $key => $field) {
                        $res = $field->update([
                            'switch' => '',
                        ]);
                        if(!$res){
                            DB::rollBack();
                            return response()->json([
                                'errcode' => 2,
                                'errmsg' => '核销订单失败', 
                            ],200);
                        }
                    }

                    //提交事务
                    DB::commit();

                }
            }

        }            
          
        return response()->json([
                'errcode' => '1',
                'errmsg' => '订单核销成功'
             ],200);
                   
        
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
        $orders_list = Order::where('store_id',$store_id)->orderBy('created_at','desc')->get();
        if($orders_list->isEmpty()){
            dump(333);
            return back()->with('warning','搜索到的结果为空！');
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
