<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


//    public function index() {
//        return response()->json(
//            [
//                'data' => Product::paginate(20),
//                'success' => true
//            ]
//        );
//    }

    public function index(Request $request) {
        $search = trim($request->input('search'));
        return response()->json(
            [
                'data' => $products =  Product::where('core_number', 'LIKE' , "%$search%")->select('id', 'core_number', 'internal_title', 'moq_pieces', 'active')->paginate(20),
                'success' => true
            ]
        );
    }

    public function show($id) {

        return response()->json(
            [
                'data' => Product::where('id',$id)->with('vendors', 'tags', 'locations.warehouse')->first(),
                'success' => true
            ]
        );
    }


    public function edit($id) {
        return response()->json(
            [
                'data' => Product::with ('locations')->where('id',$id)->select('id', 'core_number', 'internal_title')->first(),
                'success' => true
            ]
        );
    }

    public function update (Request $request, $id) {
        $product = Product::find($id);
        $location_id = $request->input('location_id');
        $location = $product->locations();
        $old_quantity = (int) $location->where('location_id', $location_id)->value('quantity');
        $location->syncWithoutDetaching([$location_id => [ 'quantity' => ((int) $request->input('quantity')) + $old_quantity]]);
        return response()->json(
            [
                'message' => 'Update was completed successfuly',
                'success' => true
            ]
        );
    }
}
