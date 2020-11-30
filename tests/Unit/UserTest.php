<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    
    public function test_adding_user_gives_status_code_500_When_avatar_is_missing()
    {
        $response = $this->json('POST', '/post-data', [
        	'first_name' => 'Kerollos',
        	'last_name' => 'Eshak',
        	'gender' => 'male',
        	'birthdate' => '1994-05-04',
        	'country_code' => 'US',
        	'email' => 'kero@kero.com',
        	'phone_number' => '+201288899896',
    	]);
    	$response
    		->assertStatus(500);
    }

    public function test_adding_user_gives_status_code_500_When_first_name_is_missing()
    {
        $response = $this->json('POST', '/post-data', [
        	'last_name' => 'Eshak',
        	'gender' => 'male',
        	'birthdate' => '1994-05-04',
        	'country_code' => 'US',
        	'email' => 'kero@kero.com',
        	'phone_number' => '+201288899896',
    	]);
    	$response
    		->assertStatus(500);
    }

    public function test_adding_user_gives_status_code_500_When_wrong_gender_is_added()
    {
        $response = $this->json('POST', '/post-data', [
        	'last_name' => 'Eshak',
        	'gender' => 'male',
        	'birthdate' => '1994-05-04',
        	'country_code' => 'US',
        	'email' => 'kero@kero.com',
        	'phone_number' => '+201288899896',
    	]);
    	$response
    		->assertStatus(500);
    }

    public function test_get_token()
    {
	    $response = $this->json('POST', '/get-token', [
    	'phone_number' => '+012881212888',
    	'password'=>'1234',
    	]);
    	$response->assertStatus(200);
    }

    public function test_get_token_wrong_phone_number()
    {
	    $response = $this->json('POST', '/get-token', [
    	'phone_number' => '+01288122888',
    	'password'=>'1234',
    	]);
    	$response->assertStatus(400);
    }

    public function test_add_status_object()
    {
    	 $response = $this->json('POST', '/add-status-object', [
    	'phone_number' => '01001234567',
    	'auth-token'=>'KmfefEXUOULG4Baq9g9KuzsQIfz1knuh',
    	'status'=>'new_status'
    	]);
    	$response->assertStatus(200);
    }

    public function test_add_status_object_with_wrong_phone_number()
    {
    	 $response = $this->json('POST', '/add-status-object', [
    	'phone_number' => '010012345672',
    	'auth-token'=>'KmfefEXUOULG4Baq9g9KuzsQIfz1knuh',
    	'status'=>'new_status'
    	]);
    	$response->assertStatus(400);
    }

    public function test_add_status_object_unauthorized_user()
    {
    	 $response = $this->json('POST', '/add-status-object', [
    	'phone_number' => '01001234567',
    	'auth-token'=>'KmfefEXUOULG4Baq9g9KuzsQIfz1knu',
    	'status'=>'new_status'
    	]);
    	$response->assertStatus(401);
    }
}
