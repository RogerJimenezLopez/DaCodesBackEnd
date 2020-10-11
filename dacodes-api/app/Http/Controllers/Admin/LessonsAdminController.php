<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CatLessons as lecciones;
use App\CatCourses as cursos;
use Validator;

class LessonsAdminController extends Controller
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
            $data = lecciones::orderby('id','asc')->get();             
            return response()->json($data, $this->successStatus);
        }
        catch (Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->$erroStatus);
        }
    }

    /**
     * Metodo para registrar una lección.
     * @param Request $request - Datos a guardar
     * @return \Illuminate\Http\JsonResponse
     */
    public function createLesson(Request $request)
    {
        try
        {            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:200',
                'description' => 'required|string|max:500',
                'index' => 'required|numeric',
                'courses_id' => 'required|numeric'               
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => true, 'error' => $validator->errors()], $this->erroStatus);
            }
            $course = cursos::find($request->courses_id);
            if ($course!=null){
                $index = lecciones::where('index', $request->index)->where('courses_id', $course->id)->get();
                $index->count();
                if ($index->count()!=0){ 
                    return response()->json(['success' => false, 'error' => 'the lesson index exist'], $this->successStatus);               
                }else{
                    lecciones::create($request->all());
                }
            }   else{
                return response()->json(['success' => false, 'error' => 'the course not exist'], $this->successStatus);               
            }         
           
            return response()->json(['success' => true, 'error' => null], $this->successStatus);            
        }
        catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->$erroStatus);
        }
    }

    /**
     * @param $Id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateLesson(Request $request){
        try
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:200',
                'description' => 'required|string|max:500',
                'index' => 'required|numeric' 
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => true, 'error' => $validator->errors()], $this->erroStatus);
            }

            $lesson = lecciones::find($request->id);
            if ($lesson!=null){
                $lesson->name        = $request->name;
                $lesson->description = $request->description;
                $lesson->index       = $request->index;
                $lesson->active      = $request->active;            
                $lesson->update();            

                return response()->json(['success' => true, 'error' => null], $this->successStatus);
            }else{
                return response()->json(['success' => false, 'error' => 'the lesson not exist'], $this->successStatus);               
            }
        }
        catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->successStatus);
        }
    }

    /**
     * Elimina permanentemente de la base de datos un registro
     * @param int $id Id del registro
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteLesson(Request $request)
    {
        $id = $request ->id;
        try {
            $lesson = lecciones::find($id);
            if ($lesson != null) {                
                $lesson->delete();                
                return response()->json(['success' => true, 'error' => null], $this->successStatus);
            }
            else
                return response()->json(['success' => false, 'error' => 'Error get the lesson'], $this->erroStatus);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'error' => $ex->getMessage()], $this->erroStatus);
        }
    }

    /**
     * Metodo para actualizar los estatus de una lección    
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLessonStatusByColum(Request $request) {
        try {
            $lesson = lecciones::find($request->id);
            $status = filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;            
            $lesson->active = $status;
            $lesson->update();
            return response()->json(['success' => true, 'error' => null], $this->successStatus);
        }
        catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->successStatus);
        }
    }
}
