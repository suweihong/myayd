<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Models\MpUser;
use App\Models\Estimate;
use App\Models\Order;
use App\Models\Bill;

class LoginController extends Controller
{
	//登录
    public function login(Request $request)
    {
    	if($request->isMethod('post')){
    		$account = $request->account;
    		$password = $request->password;
    		if($password == '' || $account == ''){
          dump(22);
    			return back()->withInput()->with('warning','请填写完整内容');
    		}else{
    			$mp_user = MpUser::where('account',$account)->first();
    			if(!$mp_user){
    				dump(33);
    				return back()->withInput()->with('warning','用户名或密码不正确');
    			}else{
    				if(Hash::check($password,$mp_user->password)){
    					$store_id = $mp_user->store_id;
              dump(44);
              dump($store_id);
    					return redirect('/?store_id='.$store_id);
    				}else{
    						dump(55);
    					return back()->withInput()->with('warning','用户名或密码不正确');
    				
    				}
    			}
    			
    		}
    	}else{
    		dump(1111);
    		// return view('login');
    	}
    }



    //主页  系统概览
    public function index(Request $request)
    {
    	$store_id = $request->store_id;
        //消息动态 最近一个月的
          $today = date('Y-m-d H:i:s');
          $date = date('Y-m-d H:i:s',strtotime('today') - 2592000);
          $estimates = Estimate::where('store_id',$store_id)->whereBetween('created_at',[$date,$today])
                                    ->orderBy('created_at','desc')
                                    ->get();

            // dump($estimates);


         //今日订单概览
          $today = strtotime(date('Y-m-d'));
          $today_start = date('Y-m-d H:i:s',$today);
          $today_end = date('Y-m-d H:i:s',$today+60*60*24);
                    //今日下单数量
          $num_x = Order::where('store_id',$store_id)->where('created_at','>=',$today_start)->where('created_at','<',$today_end)->count();
              //今日核销数
          $num_h = Order::where('store_id',$store_id)->where('updated_at','>=',$today_start)->where('updated_at','<',$today_end)->where('status_id',1)->count();
              //今日退单数
          $num_t = Order::where('store_id',$store_id)->where('updated_at','>=',$today_start)->where('updated_at','<',$today_end)->where('status_id',2)->count();

// dump($num_x);
// dump($num_h);
// dump($num_t);

        
         //销售总额

          $orders = Order::where('store_id',$store_id)->pluck('total'); 
          $total = $orders->sum();//默认销售总额
          $total_avg = floor($orders->avg() * 100) / 100;//默认平均单价
  // dump($total);
  // dump($total_avg);

          $yday_start = date('Y-m-d 00:00:00',time()-24*60*60);//昨天开始的时间

          $m_start=date('Y-m-01 00:00:00',time());//获取指定月份的第一天
          $m_end=date('Y-m-t 23:59:59',time()); //获取指定月份的最后一天
          $time = $request->time;
          
          if($time == 1){
                   //今日销售
              $orders = Order::where('store_id',$store_id)->where('created_at','>=',$today_start)->where('created_at','<',$today_end)->pluck('total');
              $t_total = $orders->sum();
              $t_avg = floor($orders->avg()*100)/100;

              // dump($t_total);
              // dump($t_avg);
          }elseif ($time == 2) {
                  //昨日销售
              $orders = Order::where('store_id',$store_id)->where('created_at','>=',$yday_start)->where('created_at','<',$today_start)->pluck('total');
              $y_total = $orders->sum();
              $y_avg = floor($orders->avg()*100)/100;

              // dump($y_total);
              // dump($y_avg);
          }elseif ($time == 3){
                 //本月销售
              $orders = Order::where('store_id',$store_id)->where('created_at','>=',$m_start)->where('created_at','<=',$m_end)->pluck('total');
              $m_total = $orders->sum();
              $m_avg = floor($orders->avg()*100)/100;

              dump($m_total);
              dump($m_avg);
          }

    	// return view('index');
    }
}
