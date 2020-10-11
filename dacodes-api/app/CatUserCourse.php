<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatUserCourse extends Model
{
     /**
     * Nombre de la tabla a la cual se relaciona con la base de datos
     * @var string
     */
    protected  $table="cat_user_course";
    /**
     * Nombre de la llave primaria de la tabla
     * @var string
     */
    protected  $primaryKey="id";
    /**
     * Variable para asignar valor a la propiedad Timestamp
     * @var bool
     */
    public $timestamps= false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id','course_id','approve'
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

    public function Users(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Course(){
        return $this->belongsTo(CatCourses::class, 'course_id');
    }    

    public static function scopeActiveCourses($query)
    {
        return $query->where('active', true);
    }

    public static function scopeNoActiveCourses($query)
    {
        return $query->where('active', false);
    }
}
