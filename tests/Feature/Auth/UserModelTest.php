<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Auth;
use Str;
use App\Models\User;

class UserModelTest extends TestCase
{

    // use RefreshDatabase, DatabaseMigrations;
    use RefreshDatabase;

    public function test_Check_User_Model(){
        $user = new \App\Models\User([
            'username' => 'testuser'
        ]);

        $this->assertEquals('testuser', $user->username);
    }


    public function test_backend_create_user_and_set_settings_and_profile(){
        $user = User::create([
            'active'    => 1,
            'username'  => 'demo',
            'email'     => 'demo@test.com',
            'password'  => bcrypt('test1234'),
        ]);

        $settings = [
            'key'  => 'value',
            '2fa'  => 'active',
        ];
        $user->saveSettings('settingSettings', 'profile.settings', $settings);

        $this->assertEquals('value', $user->settings['key']);
        $this->assertEquals('active', $user->settings['2fa']);


        $profile = [
            'nr'  => '1234',
            'firstname' => 'demo',
            'lastname'  => 'user',
        ];
        $user->saveSettings('profileSettings', 'profile.profile', $profile);

        $this->assertEquals('1234', $user->profile['nr']);
    }

}
