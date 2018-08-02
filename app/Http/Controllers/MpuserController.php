<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MpUser;

use Illuminate\Support\Facades\Hash;

class MpuserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        //修改密码的页面
    public function index(Request $request)
    {
        $store_id = $request->store_id;
        $mp_user = MpUser::where('store_id',$store_id)->first();
        return response()->json([
            'errcode' => 1,
            'mp_user' => $mp_user,
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
        //  修改密码
    public function update(Request $request,MpUser $mpuser)
    {

       $password = $request->password;
       if(preg_match("/^[\d]{6}$/",$password)){
            $res = $mpuser->update([
                 'password' => Hash::make($password),
            ]);
            if($res){
               return response()->json([
                    'errcode' => 1,
                    'errmsg' => '密码修改成功',
               ],200);
            }else{
                return response()->json([
                    'errcode' => 2,
                    'errmsg' => '密码修改失败',
                ],200);
            }
       }else{
            return response()->json([
                    'errcode' => 2,
                    'errmsg' => '请输入六位数字',
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
