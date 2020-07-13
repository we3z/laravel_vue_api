<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends BaseController
{
    //
    public function getLeftMenu(PermissionService $permissionService)
    {
        $data = $permissionService->getLeftMenu();
        return $this->jsonReturnElse($data);
    }
}
