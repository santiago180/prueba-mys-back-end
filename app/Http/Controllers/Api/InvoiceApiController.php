<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class InvoiceApiController extends Controller
{
    /**
     * @author Santiago Torres
     * @param Request $request
     * 
     * obtenemos todas las facturas, filtrado opcionalmente por emisor,
     * receptor y numero de factura
     */
    public function index(Request $request)
    {
        $invoices = Invoice::with([
            'invoicesItems.item:id,code,description',
            'transmitter',
            'receiver'
        ])
        ->when(isset($request->transmitter_id), function($filter) use ($request){
            $filter->where('transmitter_id', $request->transmitter_id);
        })
        ->when(isset($request->number), function($filter) use ($request){
            $filter->where('number', $request->number);
        })
        ->when(isset($request->receiver_id), function($filter) use ($request){
            $filter->where('receiver_id', $request->receiver_id);
        })
        ->orderBy('number', $request->order)
        ->get();
        
        return response()->json([
            'data' => $invoices
        ]);
    }

    /**
     * @author Santiago Torres
     * @param  mixed $id
     * 
     * obtenemos una factura con items, emisor y receptor
     */
    public function show($id)
    {
        $invoice = Invoice::with([
            'invoicesItems.item:id,code,description',
            'transmitter',
            'receiver'
        ])->find($id);
        return response()->json([
            'data' => $invoice
        ]);
    }

    /**
     * @author Santiago Torres
     * @param InvoiceRequest $request
     * 
     * Almacenamos la factura con los respectivos items
     * y calculamos los valores totales
     */
    public function store(InvoiceRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('invoice_items');
            $data['number'] = $this->getNumber();
            $invoice = Invoice :: create($data);

            $invoice_items = $this->addInvoiceItems($request->invoices_items, $invoice);

            $invoice->value_before_iva  = $invoice_items->sum('unit_value');
            $invoice->iva               = $invoice_items->sum('iva');
            $invoice->value_to_pay      = $invoice_items->sum('total_value');
            $invoice->save();

            DB::commit();
            return response()->json([
                'message' => 'Se ha creado la factura correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return response()->json([
                'message' => 'Hubo un error al crear la factura'
            ]);
        }
        
    }

    /**
     * @author Santiago Torres
     * 
     * Obtenemos el número para la factura que se esta realizando
     */
    private function getNumber()
    {
        $number = Invoice::max('number');
        return $number ? $number+1 : 1;
    }

    /**
     * @author Santiago Torres
     * @param Request $invoice_items
     * @param Request Invoice $invoice
     * 
     * Almacenamos los items de la facrura
     */
    public function addInvoiceItems($invoice_items, $invoice)
    {
        $items = [];
        foreach ($invoice_items as $key => $item) {
            $iva = $item["unit_value"] * 0.19;
            $data = [
                'id'            => isset($item["id"]) ? $item["id"] : null,
                'item_id'       => $item["item_id"],
                'quantity'      => $item["quantity"],
                'unit_value'    => $item["unit_value"],
                'iva'           => $iva,
                'total_value'   => ($iva + $item["unit_value"])*$item["quantity"],
                'invoice_id'    => $invoice->id,
            ];
            $items []= InvoiceItem ::updateOrCreate(['id'=>$data['id']],$data)->toArray();
        }

        return collect($items);
    }

    /**
     * @author Santiago Torres
     * @param mixed $id 
     * @param InvoiceRequest $request
     * 
     * Actualizamos la informacion de la factura y de los items
     */
    public function update($id, InvoiceRequest $request)
    {
        try {
            $invoice = Invoice :: find($id);
            DB::beginTransaction();

            $invoice->date = $request->date;
            $invoice->transmitter_id = $request->transmitter_id;
            $invoice->receiver_id = $request->receiver_id;

            $invoice_items = $this->addInvoiceItems($request->invoices_items, $invoice);

            $invoice->value_before_iva  = $invoice_items->sum('unit_value');
            $invoice->iva               = $invoice_items->sum('iva');
            $invoice->value_to_pay      = $invoice_items->sum('total_value');
            $invoice->save();

            DB::commit();
            return response()->json([
                'message' => 'Se ha modificado la factura correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Hubo un error al modificar la factura'
            ]);
        }
    }

    /**
     * @author Santiago Torres
     * @param mixed $id 
     * 
     * eiminamos la factura con items
     */
    public function destroy($id)
    {
        try {
            $invoice = Invoice :: find($id);
            DB::beginTransaction();
            $invoice->invoicesItems()->delete();
            $invoice->delete();
            DB::commit();
            return response()->json([
                'message' => 'Se ha eliminado la factura correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Hubo un error al eliminar la factura'
            ]);
        }
    }
}
