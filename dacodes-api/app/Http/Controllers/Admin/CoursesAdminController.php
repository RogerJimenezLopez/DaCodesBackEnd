<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CatCourses as cursos;
use App\CatUserCourse as cursoUsuario;
use App\User as usuario;
use Validator;

class CoursesAdminController extends Controller
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
            $data = cursos::orderby('id','asc')->get();             
            return response()->json($data, $this->successStatus);
        }
        catch (Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->$erroStatus);
        }
    }

    /**
     * Metodo para registrar un curso.
     * @param Request $request - Datos a guardar
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCourse(Request $request)
    {
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
            
            $index = cursos::where('index', $request->index)->get();
           
            if ($index->count()!=0){ 
                return response()->json(['success' => false, 'error' => 'the course index exist'], $this->successStatus);               
            }else{
                cursos::create($request->all());
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
    public function updateCourse(Request $request){
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

            $course = cursos::find($request->id);
            if ($course!=null){
                $course->name        = $request->name;
                $course->description = $request->description;
                $course->index       = $request->index;
                $course->active      = $request->active;            
                $course->update();            

                return response()->json(['success' => true, 'error' => null], $this->successStatus);
            }else{
                return response()->json(['success' => false, 'error' => 'the course not exist'], $this->successStatus);               
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
    public function deleteCourse(Request $request)
    {
        $id = $request ->id;
        try {
            $course = cursos::find($id);
            if ($course != null) {                
                $course->delete();                
                return response()->json(['success' => true, 'error' => null], $this->successStatus);
            }
            else
                return response()->json(['success' => false, 'error' => 'Error get the course'], $this->erroStatus);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'error' => $ex->getMessage()], $this->erroStatus);
        }
    }

    /**
     * Metodo para actualizar los estatus de un curso    
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCourseStatusByColum(Request $request) {
        try {
            $course = cursos::find($request->id);
            $status = filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;            
            $course->active = $status;
            $course->update();
            return response()->json(['success' => true, 'error' => null], $this->successStatus);
        }
        catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->successStatus);
        }
    }

    /**
     * Metodo para aprobar un curso   
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ApproveUserCourse(Request $request) {
        try {
            $user = usuario::find($request->user_id); 
            $course = cursos::find($request->course_id);
            if ($user==null)            
                return response()->json(['success' => false, 'error' => 'User not exist'], $this->successStatus);
            if ($course==null)            
                return response()->json(['success' => false, 'error' => 'Course not exist'], $this->successStatus);    

            $userCourseArr = cursoUsuario::where('user_id', $user->id)->where('course_id', $course->id)->get(); 
            $userCourse = cursoUsuario::find($userCourseArr[0]->id);  
            $approve = filter_var($request->approve, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;         
            $userCourse->approve = $approve;
            $userCourse->update();
            return response()->json(['success' => true, 'error' => null], $this->successStatus);
        }
        catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->successStatus);
        }
    }
}
