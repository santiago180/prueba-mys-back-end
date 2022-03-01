<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Item;

class ItemApiController extends Controller
{
    /**
     * @author Santiago Torres
     * @param Request $request
     * 
     * obtenemos todos los items
     */
    public function index(Request $request)
    {
        $items = Item::get();

        return response()->json([
            'data' => $items
        ]);
    }
}
