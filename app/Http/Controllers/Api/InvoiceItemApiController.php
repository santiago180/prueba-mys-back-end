<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvoiceItem;

class InvoiceItemApiController extends Controller
{
    /**
     * @author Santiago Torres
     * @param mixed $id 
     * 
     * eiminamos el item
     */
    public function destroy($id)
    {
        $invoiceItem = InvoiceItem :: find($id);
        $invoiceItem->delete();
    }
}
