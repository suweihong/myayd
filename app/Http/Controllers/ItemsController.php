<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreType;
use App\Models\Store;
use App\Models\Type;
use App\Models\Field;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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


        //票卡列表
    public function tickets_list(Request $request)
    {

        $store_id = $request->store_id;
        $store = Store::find($store_id);

        $type_id = $request->type_id;
        // $item_id = $request->item_id ?? 1;
        $types = $store->types()->where('item_id',2)->orderBy('created_at','asc')->get();
        if(!$type_id){
            if(!$store->types()->get()->isEmpty()){
                $type_id = $store->types()->where('item_id',2)->orderBy('created_at','asc')->first();
                if($type_id){
                    $type_id = $type_id->id;
                }else{
                    $type_id = 0;
                }
            }else{
               $type_id = 0;
            }
        }
        //读取所有票卡
        $tickets = Field::where('store_id',$store_id)->where('type_id',$type_id)->where('item_id',2)->orderBy('created_at','asc')->get();
           
            return response()->json([
                'store' => $store,
                'type_id' => $type_id,
                'types' => $types,
                'tickets' => $tickets,
            ],200);
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


    public function destroy($id)
    {
        //
    }
}
