<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CatCourses as cursos;
use App\CatLessons as lecciones;
use App\User as usuario;
use App\CatQuestion as preguntas;
use App\CatTypeQuestion as tipoPreguntas;
use App\CatUserCourse as cursoUsuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Validator;

class QuestionUserController extends Controller
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
            $lesson = lecciones::find($request->id);
            $course = cursos::find($request->courses_id);
            if ($lesson!=null){
                $data = Auth::user()->CursosUser()->activeCourses()->find($course->id)->Lessons()->find($lesson->id)->Questions()->get();             
                return response()->json($data, $this->successStatus);
            }
            return response()->json(['success' => false, 'error' => 'Error get the lesson'], $this->erroStatus);
        }
        catch (Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->$erroStatus);
        }
    }
    
    /**
     * Metodo para registrar una respuesta.
     * @param Request $request - Datos a guardar
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAnswer(Request $requests)
    {              
        foreach($requests->all() as $request){  
                 
                try
                {            
                    $validator = Validator::make($request, [                                              
                        'question_id' => 'required|numeric'               
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['success' => true, 'error' => $validator->errors()], $this->erroStatus);
                    }
                    //validamos primero que exita la pregunta exista.
                    $question = preguntas::find($request["question_id"]);

                    //validamos a que lección pertenece                    
                    $lesson = lecciones::find($question->lesson_id);
                    if ($lesson!=null){
                      //Revisamos que el curso anterior, este aprobado para poder contestar la pregunta                      
                      $course = cursos::find($lesson->courses_id);
                      //Revisamos que el curso anterior este aprobado
                      if ($course->index!=1){
                        $courseBe = cursos::where('index',$course->index-1)->get();
                        $valCourse = cursoUsuario::where('course_id',$courseBe->id)->get();
                        if ($valCourse!=null && $valCourse->approve==0){
                            return response()->json(['success' => false, 'error' => 'the before course is not approved'], $this->successStatus);  
                        }
                      }
                    }
                    else
                        return response()->json(['success' => false, 'error' => 'the lesson not exist'], $this->successStatus);  

                    if ($question!=null){      
                        //Igual verificamos que la pregunta no tenga un tipo asignado
                        $type = tipoPreguntas::where('question_id','=',$request["question_id"])->where('user_id', '=', $request["user_id"]);                          
                        if ($type->count()!=0)
                            return response()->json(['success' => false, 'error' => 'the type question has been assigned'], $this->successStatus);                
                        else           
                            tipoPreguntas::create($request);
                    }   
                    else{
                        return response()->json(['success' => false, 'error' => 'the question not exist'], $this->successStatus);               
                    }                     
                }
                catch (Exception $e) {
                    return response()->json(['success' => false, 'error' => $e->getMessage()], $this->$erroStatus);
                }
        }
        return response()->json(['success' => true, 'error' => null], $this->successStatus);            
    }

    
}
