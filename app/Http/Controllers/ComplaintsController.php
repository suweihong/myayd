<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Store;
use App\Models\Complaint;
use App\Models\Message;

class ComplaintsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //公告 及 发送给店铺的 消息
    public function index(Request $request)
    {
        $store_id = $request->store_id;
        $store = Store::find($store_id);
        $mp_user_id = $store->mp_user->id;
        $messages = Message::where('mp_user_id',0)->orwhere('mp_user_id',$mp_user_id)->orderBy('created_at','desc')->get();//公告 及 该店铺的信息
        return response()->json([
            'errcode' => 1,
            'messages' => $messages,
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
    public function store(Request $request)
    {

        $store_id = $request->store_id;
        $store = Store::find($store_id);
        $mp_user_id = $store->mp_user->id;
        $kind_id = $request->kind_id;
        $content = $request->content;
        $res = Complaint::create([
            'store_id' => $store_id,
            'kind_id' => $kind_id,
            'mp_user_id' => $mp_user_id,
            'check_id' => 2,
            'content' => $content,
            ]);
        if($res){
            return response()->json([
                'errcode' => 1,
                'errmsg' => '反馈成功',
            ],200);
        }else{
            return response()->json([
                'errcode' => 1,
                'errmsg' => '反馈失败',
            ],200);
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
