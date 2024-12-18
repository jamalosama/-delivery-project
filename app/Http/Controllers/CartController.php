<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request , $id)
    {
        $validator = Validator::make( $request->all(),[
             'quantity'=>'required|integer|min:1' ,
        ]);

        if ($validator->fails()) {
              return response()->json(['Status'=>false ,'Errors'=>$validator->errors()]); 
       }
       
       $user = auth()->user();
       if (!$user) {
        return response()->json(['Please Login to Add Products To your Cart']);
       }
       $checkproduct= Product::find($id);
       if (!$checkproduct) {
        return response()->json(['Product Not Found']);

       }
       $totalprice = $checkproduct->price * $request->quantity;

       $cart = Cart::where('user_id',$user->id)
            ->where('product_id', $id)
            ->first();
              
            if ($cart) {
                $cart->quantity += $request->quantity ; 
                $cart->total_price += $totalprice;
              
                $cart->save();
            }
            else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $id ,
                    'quantity' => $request->quantity,
                    'total_price'=>$totalprice ,
                    
                ]);
            }
              $p = Product::where('id',$id)->get();
            return response()->json([
                'message' => 'Products added to cart successfully',
                'Products'=>$p,
            ]);
        }




        public function showProducts()
        {
            $user = auth()->user();

       if (!$user) {
        return response()->json(['Please Login to Add Products To your Cart'], 401);
        }

 
       $myCart = $user->carts; 

      if (!$myCart) {
        return response()->json(['Your Cart Is Empty']);
      }
   
       $fullName = $user->first_name.' '.$user->last_name ;
       $general_Price = 0;

       foreach($myCart as $item) 
       {
          $general_Price += $item->quantity*$item->product->price ; 
          
       }
       
      return response()->json([
        'User Name' =>$fullName,
        'My Cart' => $myCart,
        'Total Price' => $general_Price ,
        
    ], 200);
        }


        public function destroy($id)
        {
                $user = auth()->user();
                if (!$user) {
                    return response()->json(['status'=>false , 'message'=> 'you are not Login']);
        }
        $myCart = $user->carts;
        if (!$myCart) {
            return response()->json(['status'=>false , 'message'=> 'No Carts Found']);
                  }
          
        $cartItem = Cart::where('product_id', $id)->where('user_id', $user->id)->first();
        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }
        $cartItem->delete();

    return response()->json([
        'message' => 'Product removed from cart successfully',
    ], 200);


        }

        public function update($id, Request $request)
        {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['status' => false,'message' =>'You are not logged in'], 401);
            }
        
            $validator = Validator::make($request->all(), [
                'quantity' => 'required|integer|min:1',
            ]);
        
            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 400);
            }
        
            $cart = Cart::where('id', $id)->where('user_id', $user->id)->first();
        
            if (!$cart) {
                return response()->json(['status' => false, 'message' => 'Product not found in your cart'], 404);
            }
        
            if (!$cart->product) {
                return response()->json(['status' => false, 'message' => 'Product no longer exists'], 404);
            }
        
            $cart->quantity = $request->quantity;
            $cart->total_price = $cart->quantity * $cart->product->price;
            $cart->save();
        
            return response()->json([
                'status' => true,
                'message' => 'Cart item updated successfully',
                'cart' => $cart,
            ], 200);
        }






        public function updateStatus($id, Request $request)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['status' => false, 'message' => 'You are not logged in'], 401);
    }

    // التحقق من صحة البيانات المدخلة
    $request->validate([
        'status' => 'required|in:pending,cancelled,delivered',
    ]);

    // البحث عن العنصر في السلة
    $cartItem = Cart::where('id', $id)->where('user_id', $user->id)->first();

    if (!$cartItem) {
        return response()->json(['status' => false, 'message' => 'Cart item not found'], 404);
    }

    // تحديث الحالة
    $cartItem->status = $request->status;
    $cartItem->save();

    return response()->json([
        'status' => true,
        'message' => 'Order status updated successfully',
        'cart' => $cartItem,
    ], 200);
}

        
    }