<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Estimate;
use App\Models\store;



class EstimatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //评价列表
    public function index(Request $request)
    {
       $store_id = $request->store_id;
       $store = Store::find($store_id);
       $estimate_list = $store->estimates;
       $estimates = $store->estimates()->orderBy('created_at','desc')->get();
       $environment = floor($estimate_list->pluck('environment')->avg() * 100)/100;

       $service = floor($estimate_list->pluck('service')->avg() * 100)/100;

       $average = floor($estimate_list->pluck('average')->avg() * 100)/100;
       return response()->json([
            'errcode' => 1,
            'estimates' => $estimates,
            'environment' => $environment,
            'service' => $service,
            'average' => $average,
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
    public function destroy($id)
    {
        //
    }
}
