<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\Models\Store;
use App\Models\Type;
use App\Models\Order;
use App\Models\Place;
use App\Models\Field_order;
use App\Models\StoreType;
use App\Models\Field;

class FieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        //工作台列表
    public function index(Request $request)
    {
        $store_id = $request->store_id;
        $store = Store::find($store_id);
        $type_id = $request->type_id;
        $item_id = $request->item_id ?? 1;
        $types = $store->types()->where('item_id',$item_id)->get(); //该店的运动品类 

        if(!$type_id){
            if(!$store->types()->get()->isEmpty()){
                $type_id = $store->types()->first()->id;
            }else{
                $type_id = 0;
            }
        }

        $type = Type::find($type_id);
        if($type == null){
            $places = [];
        }else{
            $places = $type->places()->where('store_id',$store_id)->orderBy('id','asc')->get();
        }

        if($places){
            foreach ($places as $key => $place) {
               $orders = $place->orders()->where('status_id',3)->orderBy('date','asc')->get();

               foreach ($orders as $ke => $order) {
                    $time = $order->pivot->time;
                    $field_id = $order->pivot->field_id;
                    $date = $order->pivot->order_date;
                    $order['place_id'] = $place->id;
                    $order['time'] = $time;
                    $order['field_id'] = $field_id;
                    $order['date_time'] = $date;
               }

               $orders = $orders->sortBy('date_time')->values()->all();
               $place_orders[$place->id] = $orders[0];//只获取一条数据
               // $place_orders[$place->id] = $orders;
              
            }

            // dump($place_orders);
        }else{
            $place_orders = [];
        }

        return response()->json([
            'places' => $places,
            'place_orders' => $place_orders,
        ],200);

       

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

        //添加场地
    public function store(Request $request)
    {
        $store_id = $request->store_id;
        $type_id = $request->type_id;
            if($type_id == 0){
                dump(11);
            return back()->with('warning','请先添加运动品类');
        }else{
             //该店铺 该运动品类 的营业时间
            $hours = StoreType::where('store_id',$store_id)
                            ->where('type_id',$type_id)
                            ->where('item_id','1')
                            ->first();
              //运动品类营业的  开始时间
            if($hours){
                $hours = $hours->hours;
                if($hours){
                  $store_hours = explode('-', $hours);
                  $start_time = (int)substr($store_hours[0],0,strrpos($store_hours[0],':')); 
                  $end_time = (int)substr($store_hours[1],0,strrpos($store_hours[1],':'));
                 $place =  Place::create([
                      'store_id' => $request->store_id,
                      'type_id' => $request->type_id,
                  ]);
                 $new_hours = [];
                  for ($i=$start_time; $i < $end_time; $i++) { 
                      array_push($new_hours,$i);//添加元素
                  }
                  //添加  该场地 对应的 商品
                  $fields = [];
                  $weeks = [1,2,3,4,5,6,7];

                        foreach ($new_hours as $ke => $new_hour) {
                           foreach ($weeks as $k => $week) {
                               $fields[$ke][$k]['place_id'] = $place->id;
                               $fields[$ke][$k]['time'] = $new_hour;
                               $fields[$ke][$k]['week'] = $week;
                               $fields[$ke][$k]['store_id'] = $store_id;
                               $fields[$ke][$k]['type_id'] = $request->type_id;
                                $fields[$ke][$k]['price'] = 9999;
                                $fields[$ke][$k]['item_id'] = 1;

                           }
                        }
                        $new_fields = [];
                      foreach ($fields as $key => $value) {
                        foreach ($value as $k => $v) { 
                          $new_fields[] = $v;
                        }
                     }
                     
                     $fields = Field::insert($new_fields);
                     dump(22);
                     return back()->with('success','场地添加成功');
                }else{
                    dump(33);
                  return back()->with('warning','请先设置营业时间');
                }
                
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //转场的页面
    public function trans_field()
    {
        $store_id = $request->store_id;
        $store = Store::find($store_id);
        $type_id = $request->type_id;
        $item_id = $request->item_id ?? 1;
        $pay_id = $request->pay_id;
        $time = $request->time;
        // $types = $store->types()->where('item_id',$item_id)->get(); //该店的运动品类 

        // if(!$type_id){
        //     if(!$store->types()->get()->isEmpty()){
        //         $type_id = $store->types()->first()->id;
        //     }else{
        //         $type_id = 0;
        //     }
        // }

        $type = Type::find($type_id);
        if($type == null){
            $places = [];
        }else{
            $places = $type->places()->where('store_id',$store_id)->orderBy('id','asc')->get();
        }

        return response()->json([
            'places' => $places,
            'pay_id' => $pay_id,
            'time' => $time,
        ],200);


    }




        //转场
    public function update(Request $request, $id)
    {

        $store_id = $request->store_id;
        $place_id1 = $request->place_id1;//正在用的场地
        $place_id2 = $request->place_id2;//要更换的场地
        $time = $request->time;
        $week = $request->week;
        $date = $request->date;
        $field = Field::where('place_id',$place_id1)->where('time',$time)->where('date',$date)->first();//该日期的数据
        if(!$field){
            $field = Field::where('place_id',$place_id1)->where('time',$time)->where('week',$week)->first();//该星期的数据
        }
        $new_field = Field::where('place_id',$place_id2)->where('time',$time)->where('date',$date)->first();
        if(!$new_field){
            $new_field = Field::where('place_id',$place_id2)->where('time',$time)->where('week',$week)->first();
        }
        if($new_field->switch == 2){
            return response()->json([
                'errcode' => 2,
                'errmsg' => '场地已被占用'
            ],200);
        }elseif($new_field->switch == 1){
            return response()->json([
                'errcode' => 2,
                'errmsg' => '场地已关闭'
            ],200);
        }else{
            DB::beginTransaction();
            $field_update = $field->update([
                'switch' => '',
            ]);
            if(!$field_update){
                DB::rollabck();
                return response()->json([
                    'errcode' => 2,
                    'errmsg' => '转场失败',
                ],200);
            }
            $new_update = $new_field->update([
                'switch' => 2,
            ]);
             if(!$new_update){
                DB::rollabck();
                return response()->json([
                    'errcode' => 2,
                    'errmsg' => '转场失败',
                ],200);
            }

            //提交事务
            DB::commit();

        }

        return response()->json([
            'errcode' => 1,
            'errmsg' => '转场成功',
        ],200);
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //删除场地
    public function destroy(Request $request,$id)
    {
        $place = Place::find($id);
        $place->delete();//删除场地
        $place->fields()->delete();//删除场地对应的商品
        return response()->json([
            'errcode' => '1',
            'errmsg' => '场地删除成功',
        ],200);
    }
}
