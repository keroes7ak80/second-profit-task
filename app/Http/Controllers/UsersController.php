<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use LVR\CountryCode\Two;
use Validator;
use App\Rules\minDigits;
use App\Rules\maxDigits;

class UsersController extends Controller
{
    public function postData(Request $request)
    {
    	$validator=Validator::make($request->all(),
    		[
    		'first_name'=>'required',
    		'last_name' =>'required',
    		'country_code' => ['required',new Two],
    		'phone_number'=>['required','numeric','unique:users',new minDigits,new maxDigits],
    		'gender'=>'required|in:male,female',
    		'avatar'=>'required|mimes:jpg,jpeg,png',
    		'birthdate' => 'required|before:today|date_format:Y-m-d',
    		'email'=>'email|unique:users',
    		]);
    	if($validator->fails())
    	{
    		$errors=[];	
    		$fields=['first_name','last_name','country_code','gender','birthdate','avatar','phone_number','email'];
    		foreach ($fields as $field) {
    			$key=$validator->errors()->get($field);
		    	if($key){
			    	$errors[$field]=[];
			    	foreach ($key as $value) {
			    		if($field=='phone_number'&&$value=='too_short')
			    			$errors[$field][]=['error' => $value,'count'=>10];
			    		elseif ($field=='phone_number'&&$value=='too_long')
			    			$errors[$field][]=['error' => $value,'count'=>15];
			    		else
			    			$errors[$field][]=['error' => $value];
			    	}
		    	}
    		}

	    	$messages=['errors'=>$errors];
	    	return response()->json($messages,400);
    	}
    	$image=$request->file('avatar');
    	$file_name=time().$image->getClientOriginalName();
    	$image->move(public_path('/'),$file_name);
    	$input=$request->all();
    	$user=User::create($input);
    	$user->avatar=$file_name;
    	$user->save();
   	  	return response()->json('user is added',201);

    }

    public function getToken(Request $request)
    {
    	$user=User::where('phone_number',$request->only('phone_number'))->first();
    	if(!$user){
    		return response()->json('phone_number_not_found',400);
    	}
    	$user->password=$request->only('password')['password'];
    	$token=str_random(32);
    	$user->auth_token=$token;
    	$user->save();
    	return response()->json(['auth-token'=>$token],200);
    }

    public function addStatusObject(Request $request)
    {
    	$input=$request->all();
    	$user=User::where('phone_number',$input['phone_number'])->first();

    	if (!$user) {
    		return response()->json('phone_number_not_found',400);
    	}
    	if ($user->auth_token!=$input['auth-token']) {
    		return response()->json('not_auhorized',401);
    	}
    	$user->status=$input['status'];
    	$user->save();
    	return response()->json('status_object_is_linked_to_user',200);
    }
}
