<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'item_id',
        'quantity',
        'unit_value',
        'iva',
        'total_value',
    ];

    /**
     * @author Santiago Torres
     * 
     * relacionamos el item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
