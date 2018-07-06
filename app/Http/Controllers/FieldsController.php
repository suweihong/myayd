<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Store;
use App\Models\Type;
use App\Models\Order;
use App\Models\Place;
use App\Models\Field_order;

class FieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
                    $date = $order->pivot->date;
                    $order['place_id'] = $place->id;
                    $order['time'] = $time;
                    $order['field_id'] = $field_id;
                    $order['date_time'] = $date;
               }
               $orders = $orders->sortBy('date_time')->values()->all();
               $place_orders[$place->id][] = $orders;
              
            }


            dump($place_orders);
        }

        // dump($store_id);
        // dump($type_id);
        // dump($places);
        // dump($types);

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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //删除场地
    public function destroy($id)
    {
        //
    }
}
