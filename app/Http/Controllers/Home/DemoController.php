<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    //
    public function upload()
    {
        return view('home.demo.upload');
    }

    public function uploadSave(Request $request)
    {
        $name = $request->input('name');
        $avater = $request->file('avater');
        $res = upload_image('vuetest/img', $avater);
        dd($res);
    }
}
