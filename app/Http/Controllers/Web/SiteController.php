<?php
/**
 * Created by PhpStorm.
 * User: guxy
 * Date: 2017/5/23
 * Time: 上午9:02
 */

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    public function index()
    {
        return view('bladetest', [
            'a' => 1,
            'b' => 2
        ]);
    }
}