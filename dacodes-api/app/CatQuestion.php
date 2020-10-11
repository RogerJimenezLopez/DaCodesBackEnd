<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatQuestion extends Model
{
   /**
     * Nombre de la tabla a la cual se relaciona con la base de datos
     * @var string
     */
    protected  $table="cat_question";
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
        'type','question','answer','active','lesson_id' 
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

    public function Questions()
    {
        return $this->hasMany(CatTypeQuestion::class, 'question_id');
    }           

    public static function scopeActiveQuestions($query)
    {
        return $query->where('active', true);
    }    
}