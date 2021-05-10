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


    public function index() {
        return response()->json(
            [
                'data' => Product::paginate(20),
                'success' => true
            ]
        );
    }

    public function search(Request $request) {
        $search = trim($request->input('search'));
        return response()->json(
            [
                'data' => Product::where('core_number', 'LIKE' , "%$search%")->paginate(20),
                'success' => true
            ]
        );
    }

    public function show($id) {
        return response()->json(
            [
                'data' => Product::with('vendors', 'backUpVendors', 'locations', 'tags')->find($id)->first(),
                'success' => true
            ]
        );
    }

    public function update (Request $request) {
        $product = Product::find($request->input('id'));
        $location_id = $request->input('location_id');
        $old_quantity = (int) $product->locations()->where('location_id', $location_id)->value('quantity');
        $product->syncWithoutDetaching([$location_id => ((int) $request->input('quantity')) + $old_quantity]);
        return response()->json(
            [
                'success' => true
            ]
        );
    }
}
