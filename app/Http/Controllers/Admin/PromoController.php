<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Promo;
use App\CouponApplied;
use App\Service;
class PromoController extends Controller
{
    public function index()
    {
    	$services = Service::whereStatus(1)->get();
    	$promos = Promo::get();
    	return view('admin.promo.index',compact('services','promos'));
    }

    public function create(Request $request)
    {
    	$validator = Validator::make($request->all(),
        [
            'promo_name'=>'required|unique:promos',
        ]);
        if ($validator->fails())
        {
           return Response::json(['errors' => $validator->errors()]);
        }
        else
        {
            $promo = new Promo;
            $promo->promo_name = $request->promo_name;
            $promo->discount = $request->discount;
            $promo->service_id = $request->service_id;
            $promo->status = $request->status; 
            $promo->start_date = date('Y-m-d', strtotime($request->start_date));
            $promo->end_date = date('Y-m-d', strtotime($request->end_date));
            if($promo->save())
            {
				return 1;
            }
            else
            {
                return 0;
            }
        }
    }

    public function show($id)
    {
    	$promo = Promo::find($id);
    	return $promo;
    }

    public function update(Request $request)
    {

    	$validator = Validator::make($request->all(),
        [
            'promo_name'=>'required|unique:promos,promo_name,'.$request->id,
        ]);
        if ($validator->fails())
        {
           return Response::json(['errors' => $validator->errors()]);
        }
        else
        {
            $promo = Promo::find($request->id);
            $promo->promo_name = $request->promo_name;
            $promo->discount = $request->discount;
            $promo->service_id = $request->service_id;
            $promo->status = $request->status; 
            $promo->start_date = date('Y-m-d', strtotime($request->start_date));
            $promo->end_date = date('Y-m-d', strtotime($request->end_date));
            if($promo->update())
            {
				return 1;
            }
            else
            {
                return 0;
            }
        }
    }

    public function destroy(Request $request)
    {
    	$promo = Promo::find($request->id);
    	if($promo->delete())
    	{
    		return 1;
    	}
    	else
    	{
    		return 0;
    	}
    }

    public function show_coupon()
    {
        $coupons = CouponApplied::orderBy('id','desc')->get();
        return view("admin.promo.coupon",compact('coupons'));
    }
}
