<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'number',
        'date',
        'transmitter_id',
        'receiver_id',
        'value_before_iva',
        'iva',
        'value_to_pay',
    ];

    /**
     * @author Santiago Torres
     * 
     * relacionamos los items de la factura
     */
    public function invoicesItems()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    /**
     * @author Santiago Torres
     * 
     * relacionamos emisor de la factura
     */
    public function transmitter()
    {
        return $this->belongsTo(Contact::class, 'transmitter_id');
    }

    /**
     * @author Santiago Torres
     * 
     * relacionamos receptor de la factura
     */
    public function receiver()
    {
        return $this->belongsTo(Contact::class, 'receiver_id');
    }
    
}
