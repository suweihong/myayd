<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function edit($id)
    {
       
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
        $imgs = ['/d.img','/e.img','/f.img'];

        if(!$request->title || !$request->address || !$request->map || !$request->phone || !$request->introduction){
            dump(111);
            // return back()->withInput()->with('warning','请填写完整内容');

        }else{
             //修改店铺基本信息
            $store->update([
                // 'neighbourhood_id' => $request->neighbourhood_id,
                'title' => $request->title,
                'address' => $request->address,
                'map_url' => $request->map,
                'phone' => $request->phone,
                'logo' => $request->logo,
                'introduction' => $request->introduction,
                ]);

            //修改店内实拍图
            $imgss = $store->imgs;
            if(!$imgss->isEmpty()){
                foreach($imgss as $img){
                    $img->delete();
                }
            }
            foreach ($imgs as $key => $img) {
               $store_imgs[$key]['store_id'] = $store->id;
               $store_imgs[$key]['img'] = $img;
               $store_imgs[$key]['created_at'] = $time;
            }
            $store_imgs = Store_img::insert($store_imgs);

            if($store_imgs){
                dump(22);
               // session()->flash('success','修改成功');
               // return redirect(route('stores.index'));
            }else{
                dump(333);
               // session()->flash('warning','修改失败');
               // return redirect(route('stores.edit',$store->id));
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
}
