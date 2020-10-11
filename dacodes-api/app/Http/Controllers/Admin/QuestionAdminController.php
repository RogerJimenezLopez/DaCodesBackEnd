<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CatQuestion as preguntas;
use App\CatLessons as lecciones;
use Validator;

class QuestionAdminController extends Controller
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
            $data = preguntas::orderby('id','asc')->get();             
            return response()->json($data, $this->successStatus);
        }
        catch (Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->$erroStatus);
        }
    }

    /**
     * Metodo para registrar una pregunta.
     * @param Request $request - Datos a guardar
     * @return \Illuminate\Http\JsonResponse
     */
    public function createQuestion(Request $request)
    {
        try
        {            
            $validator = Validator::make($request->all(), [
                'type' => 'required|numeric',
                'question' => 'required|string|max:500',                               
                'lesson_id' => 'required|numeric'               
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => true, 'error' => $validator->errors()], $this->erroStatus);
            }
            //validamos primero que exita la lección
            $lesson = lecciones::find($request->lesson_id);
            if ($lesson!=null){                
                preguntas::create($request->all());           
            }   
            else{
                return response()->json(['success' => false, 'error' => 'the lesson not exist'], $this->successStatus);               
            }         
           
            return response()->json(['success' => true, 'error' => null], $this->successStatus);            
        }
        catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->$erroStatus);
        }
    }

    /**     
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateQuestion(Request $request){
        try
        {
            $validator = Validator::make($request->all(), [
                'type' => 'required|numeric',
                'question' => 'required|string|max:500'                  
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => true, 'error' => $validator->errors()], $this->erroStatus);
            }

            $question = preguntas::find($request->id);
            if ($question!=null){
                $question->type        = $request->type;
                $question->question    = $request->question;                
                $question->active      = $request->active;            
                $question->update();              

                return response()->json(['success' => true, 'error' => null], $this->successStatus);
            }else{
                return response()->json(['success' => false, 'error' => 'the question not exist'], $this->successStatus);               
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
    public function deleteQuestion(Request $request)
    {
        $id = $request ->id;
        try {
            $question = preguntas::find($id);
            if ($question != null) {                
                $question->delete();                
                return response()->json(['success' => true, 'error' => null], $this->successStatus);
            }
            else
                return response()->json(['success' => false, 'error' => 'Error get the question'], $this->erroStatus);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'error' => $ex->getMessage()], $this->erroStatus);
        }
    }

     /**
     * Metodo para actualizar los estatus de una pregunta     
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateQuestionStatusByColum(Request $request) {
        try {
            $question = preguntas::find($request->id);
            $status = filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;            
            $question->active = $status;
            $question->update();
            return response()->json(['success' => true, 'error' => null], $this->successStatus);
        }
        catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->successStatus);
        }
    }
}
