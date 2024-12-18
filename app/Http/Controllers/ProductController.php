<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show_all()
    {
        $products = Product::with('store')->get();
       
        return response()->json(['Peoducts' => $products],200);
    }

    public function productByStore($store_id)
    {
        $products = Product::where('store_id', $store_id)->get();

        return response()->json([
            'Store'=>Store::where('id', $store_id)->get(),
            'Products' => $products,
        ]);
    }

    public function product_info($id)
    {
        $product = Product::find($id);
        if ($product) {
            return response()->json(['Product Informations'=>$product ,'Status'=>true]);
        }
       
        return response()->json(['Message'=>'No Data']);


    }
}
