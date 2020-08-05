<?php

namespace App\Http\Controllers\supervisior;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Redirect;
use App\Country;
use App\City;
use App\State;
use App\Zipcode;
class CountryController extends Controller
{
    public function country()
    {
        $parent_countries = Country::where('parent_id',0)->get();
        $countries = Country::with('parent')->orderBy('id','desc')->get();
        //return $countries;
        return view('supervisor.country.country',compact('countries','parent_countries'));
    }

    public function storeCountry(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'name'=>'required|unique:countries',
        ]);
        if ($validator->fails())
        {
           return Response::json(['errors' => $validator->errors()]);
        }
        else
        {
            $countries = new Country;
            $countries->parent_id = $request->parent_id;
            $countries->name = $request->name;
            if($countries->save())
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }       
    }

    public function editCountry(Request $request)
    {
        $countries = Country::find($request->id);
        return  $countries;
    }

    public function updateCountry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|unique:countries,name,'.$request->id,
        ]);
        if ($validator->fails())
        {
            return Response::json(['errors' => $validator->errors()]);
        }
        else
        {
            $countries = Country::find($request->id);
            $countries->parent_id = $request->parent_id;
            $countries->name = $request->name; 
            if($countries->save())
            {
                return 1;
            }
            else
            {
                return 0;
            } 
        }             
    }
    
    public function destroyCountry(Request $request)
    {
        $countries = Country::find($request->id);
        if($countries->delete())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function state()
    {
        $countries = Country::orderBy('id','desc')->get();
        $states = State::orderBy('id','desc')->get();
        return view('supervisor.country.state',compact('states','countries'));
    }

    public function storeState(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'name'=>'required|unique:states',
            'countryId' => 'required',
        ]);
        if ($validator->fails())
        {
            return Response::json(['errors' => $validator->errors()]);
        }
        else
        {
            $states = new State;
            $states->country_id = $request->countryId;
            $states->name = $request->name;
            if($states->save())
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }       
    }

    public function editState(Request $request)
    {
        $states = State::find($request->id);
        return  $states;
    }

    public function updateState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|unique:states,name,' .$request->id,
            'countryId' => 'required',           
        ]);
        if ($validator->fails())
        {
            return Response::json(['errors' => $validator->errors()]);
        }
        else
        {
            $states = State::find($request->id);
            $states->country_id = $request->countryId;
            $states->name = $request->name;
            if($states->save())
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
    }

    public function destroyState(Request $request)
    {
        $states = State::find($request->id);
        if($states->delete())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function city()
    {
        $countries = Country::orderBy('id','desc')->get();                             
        $cities = City::orderBy('id','desc')->get();        
        return view('supervisor.country.city',compact('cities','countries'));
    }

    public function getStateList(Request $request)
    {
        $states = State::where("country_id",$request->country_id)->get();
        if(count($states)>0)
        {
            $html = '<option value="">Select State</option>';
            foreach ($states as $value)
            {
                $html.="<option value=".$value->id.">".$value->name."</option>";
            }
        }
        else
        {
            $html = '<option value="">No State Found</option>';
        }
        return response()->json($html);
    }
    
    public function storeCity(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'name'=>'required|unique:cities',
            'stateId' => 'required',
            'countryId' => 'required',
        ]);
        if ($validator->fails())
        {
            return Response::json(['errors' => $validator->errors()]);
        }
        else
        {        
            $cities = new City;
            $cities->state_id = $request->stateId;
            $cities->name = $request->name;
            if($cities->save())
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
    }

    public function editCity(Request $request)
    {
        $cities = City::find($request->id);
        $states = State::find($cities->state_id);        
        $cities->countryId = $states->country_id;   
        $all = State::where('country_id',$states->country_id)->get(['id','name']);
        $allState = array();
        foreach($all as $key)
        {
            $state = "<option value=".$key->id." class='remove'>".$key->name."</option>";
            array_push($allState, $state);
        }
        $cities->stateName = $allState;
        return  $cities;
    }

    public function updateCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|unique:cities,name,' .$request->id,
            'stateId' => 'required',
            'countryId' => 'required',
        ]);
        if ($validator->fails())
        {
            return Response::json(['errors' => $validator->errors()]);
        }
        else
        {
            $cities = City::find($request->id);
            $cities->state_id = $request->stateId;
            $cities->name = $request->name;
            if($cities->save())
            {
                return Response::json(1);
            }
            else
            {
                return Response::json(0);
            }
        }
    }

    public function destroyCity(Request $request)
    {
        $cities = City::find($request->id);
        if($cities->delete())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function getCityList(Request $request)
    {
        $cities = City::where("state_id",$request->state_id)->with('zipcodes')->get();

        if(count($cities)>0)
        {
            $html['zipcode'] = '<option value="">Select Near By</option>';
            foreach($cities as $zipcodes)
            {
                foreach($zipcodes->zipcodes as $zip)
                {
                    $zipcode = Zipcode::where('zipcode', 'LIKE', '%' . $zip->zipcode . '%')->get();
                    if(count($zipcode)>0)
                    {
                        foreach($zipcode as $code)
                        {
                            if($zip == $code)
                            {
                                $html['zipcode'].="<option selected value=".$zip->zipcode.">".$zip->zipcode."</option>";
                            }
                            else
                            {
                                $html['zipcode'].="<option value=".$zip->zipcode.">".$zip->zipcode."</option>";
                            }
                        }
                        
                    }
                    else
                    {
                        $html['zipcode'].="<option value=".$zip->zipcode.">".$zip->zipcode."</option>";
                    }
                }
            }
            
            $html['cities'] = '<option value="">Select City</option>';
            foreach ($cities as $value)
            {
                $html['cities'].="<option value=".$value->id.">".$value->name."</option>";
            }
        }
        else
        {
            $html['cities'] = '<option value="">No city found</option>';
        }
        return response()->json($html);
    }

    public function zipcode()
    {
        $countries = Country::orderBy('id','desc')->get(); 
        $zipcodes = Zipcode::orderBy('id','desc')->get();
        $near_by = Zipcode::where('near_by','=',null)->get();                          
        return view('supervisor.country.zipcode',compact('countries','zipcodes','near_by'));
    }

    public function storeZipcode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'zipcode'=>'unique:zipcodes',
            'cityId' => 'required',
        ]);
        if ($validator->fails())
        {
            return Response::json(['errors' => $validator->errors()]);
        }
        else
        {       
            $zipcodes = new Zipcode;
            $zipcodes->city_id = $request->cityId;
            $zipcodes->zipcode = $request->zipcode;
            $zipcodes->near_by = implode(',',$request->near_by) ?? null;
            if($zipcodes->save())
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
    }

    public function editZipcode(Request $request)
    {
        $zipcodes = Zipcode::find($request->id);
if($zipcodes->near_by != null)
{
	$zipcodes->near_by = explode(',',$zipcodes->near_by);
}
        $cities = City::with('zipcodes')->find($zipcodes->city_id);
        $states = State::find($cities->state_id);        
        $zipcodes->countryId = $states->country_id;
        $zipcodes->stateId = $states->id;
        $all = State::where('country_id',$states->country_id)->get(['id','name']);
        $allState = array();
        foreach($all as $key)
        {
            $state = "<option value=".$key->id." class='remove'>".$key->name."</option>";
            array_push($allState, $state);
        }
        $city = City::with('zipcodes')->where('state_id',$cities->state_id)->get();
        $allCities = array();$allNearBy=array();
        foreach($city as $key)
        {
            $getCity = "<option value=".$key->id." class='remove'>".$key->name."</option>";
            array_push($allCities, $getCity);
            foreach($key->zipcodes as $zipcode)
            {

			$getZipcode = "<option value=".$zipcode->zipcode." class='remove'>".$zipcode->zipcode."</option>";
            }
            array_push($allNearBy, $getZipcode);
        }
        $zipcodes->stateName = $allState;
        $zipcodes->cityName = $allCities;
        $zipcodes->getAllZipcode = $allNearBy;
        return  $zipcodes;
    }

    public function updateZipcode(Request $request)
    {
        //$request['near_by'] = implode(',',$request->near_by);
      
        $validator = Validator::make($request->all(), [
            'zipcode'=>'unique:zipcodes,zipcode,' .$request->id,
            'cityId' => 'required',
            // 'stateId' => 'required',
            // 'countryId' => 'required',
        ]);
        if ($validator->fails())
        {
            return Response::json(['errors' => $validator->errors()]);
        }
        else
        {
            $zipcodes = Zipcode::find($request->id);
            $zipcodes->city_id = $request->cityId;
            $zipcodes->zipcode = $request->zipcode;
            $zipcodes->near_by = implode(',',$request->near_by);
            if($zipcodes->save())
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
    }

    public function destroyZipcode(Request $request)
    {
        $zipcodes = Zipcode::find($request->id);
        if($zipcodes->delete())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    public function getZipcodeList(Request $request)
    {
        $zipcodes = Zipcode::where("city_id",$request->city_id)->get();
        $html = '<option value="">Select Zipcode</option>';
        foreach ($zipcodes as $value)
        {
            $html.="<option value=".$value->id.">".$value->zipcode."</option>";
        }
        return response()->json($html);
    }
}
