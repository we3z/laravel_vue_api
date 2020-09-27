<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $table = 'sp_role';

    protected $primaryKey = 'role_id';

    protected $fillable = [
        'role_name', 'ps_ids', 'ps_ca', 'role_desc'
    ];
}
