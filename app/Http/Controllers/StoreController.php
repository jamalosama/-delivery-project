<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores=Store::all();

        if($stores)
        {
            return response()->json(['Stores'=>$stores],200);
        }
        else{

            return response()->json(['Status'=>false,'Message'=>'There is no Stores'],200);

        }
    }
}
