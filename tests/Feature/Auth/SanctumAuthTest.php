<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use Carbon\Carbon;

class SanctumAuthTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseMigrations;


    public function test_register_user(){
        $r = $this->post(env('APP_API_URL') . '/auth/register', [
            'username'  => 'demo',
            'firstname' => 'John',
            'lastname'  => 'Doe',
            'email'     => 'demo@test.com',
            'password'  => 'test1234',
            'password_confirmation' => 'test1234',
        ]);

        // dd($r->decodeResponseJson());

        $r->assertJsonStructure([ 'user', 'token' ]);
        $r->assertStatus(201);
    }


    public function test_login_user_with_email_username_mobile(){
        User::create([
            'active'    => 1,
            'username'  => 'demo',
            'firstname' => 'John',
            'lastname'  => 'Doe',
            'email'     => 'demo@test.com',
            'email_verified_at' => Carbon::now(),
            'mobile'    => '1234',
            'password'  => bcrypt('test1234'),
        ]);

        // login with email
        $r = $this->post(env('APP_API_URL') . '/auth/login', [
            'email'     => 'demo@test.com',
            'password'  => 'test1234',
        ]);
        // dd($r->decodeResponseJson());
        $r->assertJsonStructure([ 'user', 'token' ])->assertStatus(200);


        // login with username
        $r = $this->post(env('APP_API_URL') . '/auth/login', [
            'username'  => 'demo',
            'password'  => 'test1234',
        ]);
        // dd($r->decodeResponseJson());

        $r->assertJsonStructure([ 'user', 'token' ])->assertStatus(200);
    }


    public function test_logout_user(){
        User::create([
            'active'    => 1,
            'username'  => 'demo',
            'email'     => 'demo@test.com',
            'email_verified_at' => Carbon::now(),
            'mobile'    => '1234',
            'password'  => bcrypt('test1234'),
        ]);

        // login with email
        $r = $this->post(env('APP_API_URL') . '/auth/login', [
            'email'     => 'demo@test.com',
            'password'  => 'test1234',
        ])->assertJsonStructure([ 'user', 'token' ])->assertStatus(200);

        // logut
        $this->withHeaders(['Authorization' => 'Bearer ' . $r['token']] )
              ->post(env('APP_API_URL') . '/auth/logout')
              ->assertJson(['check' => true ])
              ->assertStatus(200);
    }


}
