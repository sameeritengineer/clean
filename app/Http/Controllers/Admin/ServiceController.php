<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Service;
use App\Servicetype;
use App\service_typesSpanish;
use App\Serviceprovider;
use App\Sptostype;
use App\ServicePrice;
use App\servicesSpanish;
use Imageresize;

class ServiceController extends Controller
{
    public function service()
    {
    	$services = Service::orderBy('id','desc')->get();
      foreach ($services as $service_id) 
      {   
        $sapanisname = servicesSpanish::where('service_id', $service_id->id)->first();
        if(!empty($sapanisname))
        {
          $service_id->spanish_name = $sapanisname->name;
        }
        else
        {
          $service_id->spanish_name = null;
        } 
      } 
    	return view('admin.service.service',compact('services'));
    }

    public function storeService(Request $request)
    {
      $validator = Validator::make($request->all(),
      [
        'name'=>'required|unique:services',
      ]);
      if ($validator->fails())
      {
        return Response::json(['errors' => $validator->errors()]);
      }
      else
      {
        $services = new Service;
        $services->name = $request->name;     
        if($services->save())
        {
          $servicesSpanish = new servicesSpanish;
          $servicesSpanish->service_id = $services->id;    
          $servicesSpanish->name = $request->spanish_name; 
          if($servicesSpanish->save())  
          {
            return 1;
          }
          else
          {
            return 0;
          }
        }
        else
        {
          return 0;
        }
      }    	
    }

    public function editService(Request $request)
    {
      $services = Service::find($request->id);
      $sapanisname = servicesSpanish::where('service_id',$services->id)->first();
      if(!empty($sapanisname))
      {
        $services->spanish_name = $sapanisname->name;
      }
      else
      {
        $services->spanish_name = null;
      }
      return  $services;
    }

    public function updateService(Request $request)
    {
      $validator = Validator::make($request->all(),
      [
        'name'=>'required|unique:services,name,'.$request->id,
      ]);
      if ($validator->fails())
      {
        return Response::json(['errors' => $validator->errors()]);
      }
      else
      {
        $services = Service::find($request->id);
        $services->name = $request->name; 
        if($services->save())
        {
          $servicesSpanish = servicesSpanish::where('service_id',$services->id)->first();
          $servicesSpanish->name = $request->spanish_name; 
          if($servicesSpanish->save())  
          {
            return 1;
          }
          else
          {
            return 0;
          }
        }
        else
        {
          return 0;
        } 
      }             
    }

    public function serviceStatus(Request $request)
    {
    	$id = $request->id;
      $service = Service::find($id);
      $status = $request->value;      
      if($status == 0)
      {
			 $service->status = 1;
      }
    	if($status == 1)
    	{
        $service->status = 0;
     	}    
     	if($service->update())
     	{
        return "success";
    	}
    }

    public function destroyService(Request $request)
    {
      $serviceprovider_ids = Sptostype::where('service_id', $request->id)->get();
      $services = Service::find($request->id);
      $servicetypes = Servicetype::where('service_id',$request->id)->get();
        //Image unlink from service type and delete entry from service type
      foreach($servicetypes as $servicetype)
      {
        $filename = $servicetype->image;
        $basename_image = basename($filename);
        unlink(public_path('normal_images/'.$basename_image));
        unlink(public_path('thumbnail_images/'.$basename_image));
      }
      $deleteservicetype   = Servicetype::where('service_id',$request->id)->delete();
      //Get service provider id's
      $serviceprovider_ids = Sptostype::where('service_id', $request->id)->get();
      $service_provider_id_array = array();
      foreach($serviceprovider_ids as $serviceprovider_id)
      {
        $service_provider_id_array[] = $serviceprovider_id->serviceprovider_id;
      } 
      $service_provider_id_array_unique       = array_unique($service_provider_id_array);
      $service_provider_id_array_unique_iZero = array_values($service_provider_id_array_unique);
      // Delete data from Sptostype
      $deleteSptostype   = Sptostype::where('service_id',$request->id)->delete();
      //Image unlink from service Provider and delete entry from serviceprovider
      foreach($service_provider_id_array_unique_iZero as $spid)
      {
        $sp = Serviceprovider::where('id', $spid)->first();
        $filename = $sp->profile_image;
        $basename_image = basename($filename);
        unlink(public_path('normal_images/'.$basename_image));
        unlink(public_path('thumbnail_images/'.$basename_image));
        $deleteservicetype   = Serviceprovider::where('id',$spid)->delete();
      }
      if($services->delete())
      {
        return 1;
      }
      else
      {
        return 0;
      }
    }

    public function serviceType()
    {
    	$serviceTypes = Servicetype::orderBy('id','desc')->get();
      foreach ($serviceTypes as $serviceType) 
      {   
        $sapanisname = service_typesSpanish::where('servicetype_id', $serviceType->id)->first();
        if(!empty($sapanisname))
        {
          $serviceType->spanish_name = $sapanisname->name;
        }
        else
        {
          $serviceType->spanish_name = null;
        }  
      }
    	$services = Service::orderBy('id','desc')->get();
    	return view('admin.service.serviceType',compact('serviceTypes','services'));
    }

    public function storeServiceType(Request $request)
    {
      $validator = Validator::make($request->all(),
      [
        'name'       =>'required|unique:service_types',
        'serviceId'  =>'required',
        'price'      =>'required',
        'description'=>'required',
      ]);
      if ($validator->fails())
      {
        return Response::json(['errors' => $validator->errors()]);
      }
      else
      {
        $serviceTypes = new Servicetype;
        $serviceTypes->service_id = $request->serviceId;
        $serviceTypes->name = $request->name;
        $serviceTypes->description = $request->description;
        $serviceTypes->status= 1;
        if($request->file('image'))
        {
          /*$name = rand(1,100);
          $name = $name.time();
          $photo = $request->file('image');
          $imagename = $name.'.'.$photo->getClientOriginalExtension();
          $base_url = \URL::to('/');
          $fullurl  = $base_url.'/thumbnail_images/'.$imagename;
          $destinationPath = public_path('/thumbnail_images'); 
          $thumb_img = Imageresize::make($photo->getRealPath())->resize(1140, 760);
          $thumb_img->save($destinationPath.'/'.$imagename,80);
          $destinationPath = public_path('/normal_images');
          $photo->move($destinationPath, $imagename);
          $serviceTypes->image = $fullurl;*/
          $number = hexdec(uniqid());
          $image = $request->file('image');
          $thumbnailImage = Imageresize::make($image);
          $imagePath = public_path().'/normal_images/';
          $thumbnailImage->resize(1140,760);
          $thumbnailImage->save($imagePath.$number.$image->getClientOriginalName());
          $serviceTypes->image = $number.$image->getClientOriginalName();
        }
        if($serviceTypes->save())
        {
          $servicePrice = new ServicePrice;
          $servicePrice->servicetype_id = $serviceTypes->id;
          $servicePrice->price = $request->price;
          if($servicePrice->save()) 
          {
            $service_typesSpanish = new service_typesSpanish;
            $service_typesSpanish->servicetype_id = $serviceTypes->id;
            $service_typesSpanish->name = $request->Spanish_name;
            $service_typesSpanish->description = $request->Spanish_description;
            if($service_typesSpanish->save()) 
            {
              return 1;
            }
            else
            {
              return 0;
            }
          }
        }  
      }    	
    }

    public function editServiceType(Request $request)
    { 
      $id = $request->id;
      $services = Servicetype::find($id);
      $servicePrice = ServicePrice::where('servicetype_id' , $id)->first();
      $services->price = $servicePrice->price;
      $servicespanic = service_typesSpanish::where('servicetype_id' , $id)->first();
      if(!empty($servicespanic))
      {
        $services->spanish_name = $servicespanic->name;
        $services->spanish_decription = $servicespanic->description;
      }
      else
      {
        $services->spanish_name = null;
        $services->spanish_decription = null;
      }
      return  $services;
    }

    public function updateServiceType(Request $request)
    {
      $validator = Validator::make($request->all(),
      [
        'name'=>'required|unique:service_types,name,'.$request->id,
        'description'=>'required',
      ]);
      if ($validator->fails())
      {
        return Response::json(['errors' => $validator->errors()]);
      }
      else
      {
        $serviceTypes = Servicetype::find($request->id);
        $serviceTypes->service_id = $request->serviceId;          
        $serviceTypes->name = $request->name; 
        $serviceTypes->description = $request->description;
        if($request->file('image'))
        {
          /*$name = rand(1,100);
          $name = $name.time();
          $photo = $request->file('image');
          $imagename = $name.'.'.$photo->getClientOriginalExtension();
          $base_url = \URL::to('/');
          $fullurl  = $base_url.'/thumbnail_images/'.$imagename;
          $destinationPath = public_path('/thumbnail_images'); 
          $thumb_img = Imageresize::make($photo->getRealPath())->resize(1140, 760);
          $thumb_img->save($destinationPath.'/'.$imagename,80);
          $destinationPath = public_path('/normal_images');
          $photo->move($destinationPath, $imagename);
          $serviceTypes->image = $fullurl;*/
          $number = hexdec(uniqid());
          $image = $request->file('image');
          $thumbnailImage = Imageresize::make($image);
          $imagePath = public_path().'/normal_images/';
          $thumbnailImage->resize(1140,760);
          $thumbnailImage->save($imagePath.$number.$image->getClientOriginalName());
          $serviceTypes->image = $number.$image->getClientOriginalName();
        }
        if($serviceTypes->save())
        {
          $servicePrice = ServicePrice::where('servicetype_id', $serviceTypes->id)->first();
          $servicePrice->servicetype_id = $serviceTypes->id;
          $servicePrice->price = $request->price;
          if ($servicePrice->save()) 
          {
            $service_typesSpanish = service_typesSpanish::where('servicetype_id', $serviceTypes->id)->first();
            $service_typesSpanish->name = $request->Spanish_name;
            $service_typesSpanish->description = $request->editSpanish_description;        
            if ($service_typesSpanish->save()) 
            {
              return 1;
            }
            else
            {
              return 0;
            }
          }
        }  
      }             
    }

    public function serviceTypeStatus(Request $request)
    {
    	$id = $request->id;
      $service = Servicetype::find($id);
      $status = $request->value;      
      if($status == 0)
      {
		    $service->status = 1;
      }
    	if($status == 1)
    	{
        $service->status = 0;
     	}    
     	if($service->update())
     	{
        return "success";
    	}
    }

    public function destroyServiceType(Request $request)
    {
        $servicetype = Servicetype::find($request->id);
       /* $service_id = $servicetype->service_id;*/
       
        //Image unlink from service type and delete entry from service type
    /*     $basename_image = $servicetype->image;
      // $basename_image = basename($filename);
        // $sp_ids = Sptostype::where('servicetype_id',$request->id )->get();

         $serviceprovider_ids = Sptostype::where('servicetype_id', $request->id)->get();
         $service_provider_id_array = array();
          foreach($serviceprovider_ids as $serviceprovider_id){
  
             $service_provider_id_array[] = $serviceprovider_id->serviceprovider_id;

         } 
         $service_provider_id_array_unique       = array_unique($service_provider_id_array);
         $service_provider_id_array_unique_iZero = array_values($service_provider_id_array_unique);

         //delete from sptostype
         
         $sptostype = Sptostype::where('servicetype_id',$request->id )->delete();

         //delete from price
         
         $ServicePrice = ServicePrice::where('servicetype_id',$request->id )->delete();
        
           //Image unlink from service Provider and delete entry from serviceprovider

         foreach($service_provider_id_array_unique_iZero as $spid)
         {
            $getserviceid = Sptostype::where('service_id',$service_id )->Where('serviceprovider_id',$spid)->count();

             if($getserviceid == 0){
                $serviceprovider = Serviceprovider::where('id', $spid)->first();
                 $bimage = $serviceprovider->profile_image;
                 // $bimage = basename($spimage);
                $delserviceprovide = Serviceprovider::where('id', $spid)->delete();

                 unlink(public_path('normal_images/'.$bimage));
                /*unlink(public_path('thumbnail_images/'.$bimage));   
             }
         }
          unlink(public_path('normal_images'.$basename_image));
          unlink(public_path('thumbnail_images/'.$basename_image));*/

        if($servicetype->delete())
        {
          return 1;
        }
        else
        {
          return 0;
        }
    }
}
