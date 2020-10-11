<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatTypeQuestion extends Model
{
   /**
     * Nombre de la tabla a la cual se relaciona con la base de datos
     * @var string
     */
    protected  $table="cat_type_question";
    /**
     * Nombre de la llave primaria de la tabla
     * @var string
     */
    protected  $primaryKey="id";
    /**
     * Variable para asignar valor a la propiedad Timestamp
     * @var bool
     */
    public $timestamps= true;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [      
        'boolean','opc_multiple_1','opc_multiple_2','opc_multiple_3','active','question_id','lesson_id', 'user_id','approve'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'active' => 'boolean'
    ];              

    public static function scopeActiveTypeQuestion($query)
    {
        return $query->where('active', true);
    }    
}