<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactApiController extends Controller
{
    /**
     * @author Santiago Torres
     * @param Request $request
     * 
     * obtenemos todos los contacts filtrados por Emisor o Receptor
     * según sea el case
     */
    public function index(Request $request)
    {
        $contacts = Contact::with([
                        'type:id,description'
                    ])
                    ->when(isset($request->is_transmitter), function ($filter){
                        $filter->whereHas('type', function($type){
                            $type->where('description', 'Emisor');
                        });
                    })
                    ->when(isset($request->is_receiver), function ($filter){
                        $filter->whereHas('type', function($type){
                            $type->where('description', 'Receptor');
                        });
                    })
                    ->get();

        return response()->json([
            'data' => $contacts
        ]);
    }
}
