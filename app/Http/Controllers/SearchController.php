<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $item = $request->query('name'); 

        if (!$item) {
            return response()->json([
                'message' => 'No Resoults'
            ],400);
        }

        $products=Product::where('name','like',"%$item%")->get() ;
        $stores=Store::where('name','like',"%$item%")->get();
             return response()->json([
            'DATA' => [
                'products' => $products,
                'Stores'=>$stores,
                    ],
         ]);

        }
       


       
    }
    

