<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatCourses extends Model
{
   /**
     * Nombre de la tabla a la cual se relaciona con la base de datos
     * @var string
     */
    protected  $table="Cat_Courses";
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
        'name','description','index','active'
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

    public function UsersCourse()
    {
        return $this->hasMany(CatUserCourse::class, 'course_id');
    }    
    
    public function Lessons()
    {
        return $this->hasMany(CatLessons::class, 'courses_id');        
    }        

    public static function scopeActiveCourses($query)
    {
        return $query->where('active', true);
    }    
}
