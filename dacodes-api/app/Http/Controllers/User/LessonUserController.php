<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CatCourses as cursos;
use App\CatLessons as lecciones;
use App\User as usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Validator;

class LessonUserController extends Controller
{
    // Variable Global para el Estatus de respuesta exitosa.
    public $successStatus = 200;
    // Variable Global para el Estatus de respuesta erronea.
    public $erroStatus = 400;
    // Variable Global para el codigo de NO AUTORIZADO.
    public $unauthorizedStatus = 401;

    public function index(Request $request)
    {
        try{
            $course = cursos::find($request->id);
            if ($course!=null){
                $data = Auth::user()->CursosUser()->activeCourses()->find($course->id)->Lessons()->get();             
                return response()->json($data, $this->successStatus);
            }
            return response()->json(['success' => false, 'error' => 'Error get the course'], $this->erroStatus);
        }
        catch (Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->$erroStatus);
        }
    }
}
