<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    public function confirm_order(Request $request)
    {
        $rules = [
            'product_id.*' => 'required|numeric',
            'qty.*' => 'required|numeric',
            'grand_total' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $order = new Order;
        $order->order_no = rand(10000, 99999);
        $order->grand_total = $request->grand_total;
        $order->save();

        foreach ($request->product_id as $index => $product_id) {
            $product = Product::find($product_id);
            $order_item = new OrderItem;
            $order_item->order_id = $order->id;
            $order_item->product_id = $product->id;
            $order_item->qty = $request->qty[$index];
            $order_item->price = $product->price;
            $order_item->sub_total_price = $request->qty[$index] * $product->price;
            $order_item->save();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Order confirmed',
            'order' => $order
        ], 200);
    }

    public function get_orders()
    {
        $orders = Order::with('order_items','order_items.product')->OrderBy("id","DESC")->get();
        if(count($orders) == 0)
        {
            return response()->json([
                'status' => 200,
                'message' => 'Record Not Found'
            ],200);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Order List',
            'orders' => $orders
        ],200);
    }

}
