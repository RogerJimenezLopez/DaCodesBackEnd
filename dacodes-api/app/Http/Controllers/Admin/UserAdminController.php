<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User as usuario;
use App\CatCourses as cursos;
use Validator;

class UserAdminController extends Controller
{

    // Variable Global para el Estatus de respuesta exitosa.
    public $successStatus = 200;
    // Variable Global para el Estatus de respuesta erronea.
    public $erroStatus = 400;
    // Variable Global para el codigo de NO AUTORIZADO.
    public $unauthorizedStatus = 401;

    public function index()
    {
        try{
            $data = usuario::orderby('id','asc')->get();             
            return response()->json($data, $this->successStatus);
        }
        catch (Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->$erroStatus);
        }
    }

    /**
    * Método que relaciona un usuaio a un curso.
     * @param $idUser   - identificador del usuario
     * @param $idCourse - Identificador de la sucursal
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignUserToCourse(Request $request) {
        try {
            $user       = usuario::find($request->idUser);
            $course     = cursos::find($request->idCourse); 
            
            if ($course==null){
                return response()->json(['error' => 'The course not exist'], $this->successStatus);
            }
                   
            if($user == null){
                return response()->json(['error' => 'The user not exist'], $this->successStatus);
            }
            else {
                                  
                if ($user->Cursos()->where('user_id', $user->id)->where('course_id', $course->id)->first() == null) {
                    $user->CursosUser()->attach($course->id, ['created_at' => now(), 'updated_at' => now()]);
                    return response()->json(['success' => true, 'user' => $user, 'course' => $course], $this->successStatus);
                } 
                else 
                    return response()->json(['success' => false, 'error' => 'The relationship exist'], $this->successStatus);               
            }
        } catch (Exception $ex) {
            return response()->json(['success' => false], $this->successStatus);
        }
    }

     /**
     * Método que desactiva la relaciona de un usuario a un curso.
     * @param $idUser   - identificador del usuario
     * @param $idCourse - Identificador del curso
     * @return \Illuminate\Http\JsonResponse
     */
    public function unassignUserToCourse(Request $request) {
        try {
            $user    = usuario::find($request->idUser);
            $course  = cursos::find($request->idCourse);
                            
            if ($user->Cursos()->where('user_id', $user->id)->where('course_id', $course->id)->first() != null) {
                    $user->CursosUser()->detach($course->id);
                    return response()->json(['success' => true, 'user' => $user, 'course' => $course], $this->successStatus);
                } else
                return response()->json(['success' => false, 'error' => 'the user not have relationship to course'], $this->successStatus);
           
        } catch (Exception $ex) {
            return response()->json(['success' => false], $this->successStatus);
        }
    }


}
