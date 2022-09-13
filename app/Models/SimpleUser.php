<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimpleUser extends Model
{
    protected $table = 'users';

    protected $hidden = [
        // 'role',
        'email',
        'password',
        'remember_token',
    ];

    protected $appends = [
        'role_show',
    ];

    public function getRoleShowAttribute(){
        if($this->role == 'upper')
            return 'upper';

        return null;
    }

}
