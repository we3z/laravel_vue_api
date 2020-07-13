<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    protected $table = 'sp_permission';

    protected $primaryKey = 'ps_id';

    public $timestamps = false;

    public function connectPermissionApi()
    {
        return $this->hasOne(PermissionApi::class, 'ps_id', 'ps_id');
    }
}
