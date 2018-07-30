<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Excel;

use App\Models\Bill;

class BillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $store_id = $request->store_id;
        $bills = Bill::where('store_id',$store_id)->orderBy('balance','desc')->get();
        dump($bills);
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
        //  确认账单
    public function update(Request $request,Bill $bill)
    {
        $check_id = $bill->check_id;
        if($check_id == 8){
            $bill->update([
                'check_id' => 7,
            ]);
            return response()->json([
                'errcode' => '1',
                'errmsg' => '账单确认成功'
            ],200);
        }else{
            return response()->json([
                'errcode' => '2',
                'errmsg' => '账单确认失败'
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

    //导出账单
    public function export(Request $request)
    {
        $time = date('Y-m-d H-i-s');
        $store_id = $request->store_id;
        $bills_list = Bill::where('store_id',$store_id)->orderBy('balance','desc')->get();

        if($bills_list->isEmpty()){
            dd(333);
            return back()->with('warning','搜索的结果为空');
        }else{
            foreach ($bills_list as $key => $bill) {
                $bills[$key]['id'] = $key+1;
                $bills[$key]['store'] = $bill->store->title;
                $bills[$key]['time'] = $bill->time_start .'至'. $bill->time_end;
                $bills[$key]['order_price'] = $bill->total;
                $bills[$key]['d_price'] = $bill->collection;
                $bills[$key]['j_price'] = $bill->balance;
                $bills[$key]['status'] = $bill->check->name;
                if($bill->check_id == 8){
                    $bills[$key]['q_time'] = '';
                }else{
                    $bills[$key]['q_time'] = (string)$bill['updated_at'];
                }
            }
        }

         array_unshift($bills,['序号','场馆','账单时间','订单金额','代收金额','结算金额','确认状态','确认时间']);

        $fw='A1:H'.count($bills);//居中的范围
        Excel::create(iconv('UTF-8', 'GBK', '帐单列表'.$time),function($excel) use ($bills,$fw){
                $f=$fw;
                $excel->sheet('score', function($sheet) use ($bills,$f){
                    $sheet->rows($bills);
                    $sheet->setWidth([               // 设置多个列  
                        'A' => 10,  
                        'B' => 15,
                        'C' => 25,
                        'D'=> 15,
                        'E'=> 15,
                        'F' => 15,  
                        'G' => 15,
                        'H' => 20,
                    ]);
                    $sheet->cells($f,function($cells) { //$f是范围。匿名函数设置居中对齐
                           $cells->setAlignment('center');
                        });
                });
        })->export('xls');

    }
}
