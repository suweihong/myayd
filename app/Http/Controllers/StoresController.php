<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Store;
use App\Models\Store_img;

class StoresController extends Controller
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
     
       $store_imgs = $store->imgs()->get();
       return response()->json([
            'store' => $store,
            'store_imgs' => $store_imgs,
       ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Store $store)
    {

        $time = now();
        $store_imgs = [];
        $imgs = $request->imgs;
        $imgs = ['we.img','ee.img'];

        if(!$request->title || !$request->address || !$request->map || !$request->phone || !$request->introduction){
            return response()->json([
                'errcode' => 2,
                'errmsg' => '请填写完整内容',
            ],200);

        }else{
                //开启事务
            DB::beginTransaction();
             //修改店铺基本信息
            $store_update = $store->update([
                // 'neighbourhood_id' => $request->neighbourhood_id,
                'title' => $request->title,
                'address' => $request->address,
                'map_url' => $request->map,
                'phone' => $request->phone,
                'logo' => $request->logo,
                'introduction' => $request->introduction,
                ]);

            if(!$store_update){
                    //事务回滚
                DB::rollBack();
                return response()->json([
                    'errcode' => 2,
                    'errmsg' => '修改失败',
                ],200);
            }

            //修改店内实拍图
            $imgss = $store->imgs;
            if(!$imgss->isEmpty()){
                foreach($imgss as $img){
                    $img_delete = $img->delete();
                    if(!$img_delete){
                            //  事务回滚
                        DB::rollBack();
                        return response()->json([
                            'errcode' => 2,
                            'errmsg' => '照片删除失败',
                        ],200);
                    }
                }
            }
            foreach ($imgs as $key => $img) {
               $store_imgs[$key]['store_id'] = $store->id;
               $store_imgs[$key]['img'] = $img;
               $store_imgs[$key]['created_at'] = $time;
            }
            $imgs_insert = Store_img::insert($store_imgs);

            if(!$imgs_insert){
                    //事务回滚
                DB::rollBack();                
                return response()->json([
                    'errcode' => 2,
                    'errmsg' => '修改失败',
                ],200);
            }
            

              //提交事务
            DB::commit();
            
            return response()->json([
                'errcode' => 1,
                'errmsg' => '修改成功',
            ],200);
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
}
