<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    public $table='contacts';
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'nit',
        'phone',
        'type_id',
    ];


    /**
     * @author Santiago Torres
     * 
     * relacion a tabla type con esto
     * identioficamos si es emisor o receptor
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    
}
