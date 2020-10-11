<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatLessons extends Model
{
   /**
     * Nombre de la tabla a la cual se relaciona con la base de datos
     * @var string
     */
    protected  $table="cat_lesson";
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
        'name','description','index','active','courses_id'
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
        return $this->hasMany(CatQuestion::class, 'lesson_id');
    }           

    public static function scopeActiveLessons($query)
    {
        return $query->where('active', true);
    }    
}