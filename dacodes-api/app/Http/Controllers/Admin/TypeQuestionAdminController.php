<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CatQuestion as preguntas;
use App\CatTypeQuestion as tipoPreguntas;
use App\User as usuarios;
use Validator;

class TypeQuestionAdminController extends Controller
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
            $data = tipoPreguntas::orderby('id','asc')->get();             
            return response()->json($data, $this->successStatus);
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
    public function createTypeQuestion(Request $request)
    {
        try
        {            
            $validator = Validator::make($request->all(), [                                              
                'question_id' => 'required|numeric'               
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => true, 'error' => $validator->errors()], $this->erroStatus);
            }
            //validamos primero que exita la pregunta exista.
            $question = preguntas::find($request->question_id);
            if ($question!=null){      
                //Igual verificamos que la pregunta no tenga un tipo asignado
                $type = tipoPreguntas::find($request->question_id);
                if ($type==null)
                    tipoPreguntas::create($request->all());
                else           
                    return response()->json(['success' => false, 'error' => 'the type question has been assigned'], $this->successStatus);                
            }   
            else{
                return response()->json(['success' => false, 'error' => 'the question not exist'], $this->successStatus);               
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
    public function updateTypeQuestion(Request $request){
        try
        {
            $validator = Validator::make($request->all(), [
                'id' => 'required|numeric'                  
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => true, 'error' => $validator->errors()], $this->erroStatus);
            }

            $type = tipoPreguntas::find($request->id);
           
            if ($type!=null){
                if ($request->boolean!=null)
                    $type->boolean = $request->boolean;
                if ($request->opc_multiple_1!=null)
                    $type->opc_multiple_1  = $request->opc_multiple_1;                
                if ($request->opc_multiple_2!=null)
                    $type->opc_multiple_2  = $request->opc_multiple_2;
                if ($request->opc_multiple_3!=null)
                    $type->opc_multiple_3  = $request->opc_multiple_3; 

                $type->update();              

                return response()->json(['success' => true, 'error' => null], $this->successStatus);
            }else{
                return response()->json(['success' => false, 'error' => 'the type question not exist'], $this->successStatus);               
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
    public function deleteTypeQuestion(Request $request)
    {
        $id = $request ->id;
        try {
            $type = tipoPreguntas::find($id);
            if ($type != null) {                
                $type->delete();                
                return response()->json(['success' => true, 'error' => null], $this->successStatus);
            }
            else
                return response()->json(['success' => false, 'error' => 'Error get the type question'], $this->erroStatus);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'error' => $ex->getMessage()], $this->erroStatus);
        }
    }

     /**
     * Metodo para actualizar los estatus de un tipo de pregunta     
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTypeQuestionStatusByColum(Request $request) {
        try {
            $typequestion = tipoPreguntas::find($request->id);
            $status = filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;            
            $typequestion->active = $status;
            $typequestion->update();
            return response()->json(['success' => true, 'error' => null], $this->successStatus);
        }
        catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->successStatus);
        }
    }

    /**     
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function ApproveAnswer(Request $request){
        try
        {
            $validator = Validator::make($request->all(), [
                'id' => 'required|numeric'                  
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => true, 'error' => $validator->errors()], $this->erroStatus);
            }

            $type = tipoPreguntas::find($request->id);
            $user = usuarios::find($request->user_id);
            

            if ($type==null && $user!=null){
                $typeAll = tipoPreguntas::where('user_id','=',$user->id)->get();
                foreach($typeAll as $typeArr){
                    
                    $type = tipoPreguntas::find($typeArr->id);
                    $question = preguntas::find($type->question_id);                   
                    
                    if ($question->type==1){
                        $answer = $question->answer=="1"?1:0;                        
                        if($answer==$type->boolean)
                            $type->approve  = 1;                    
                    }                          

                    if ($question->type==2 && $question->answer==$type->opc_multiple_1)
                        $type->approve  = 1;    

                    if ($question->type==3 && $question->answer==$type->opc_multiple_2)
                        $type->approve  = 1; 

                    if ($question->type==4 && $question->answer==$type->opc_multiple_3)
                        $type->approve  = 1; 

                    $type->update();
                                          
                }
                return response()->json(['success' => true, 'error' => null], $this->successStatus);                
            }
                       
            if ($type!=null){                
                if ($request->approve!=null)
                    $type->approve  = $request->approve; 

                $type->update();  
                return response()->json(['success' => true, 'error' => null], $this->successStatus);                
            }else{                
                return response()->json(['success' => false, 'error' => 'the type question not exist for this user'], $this->successStatus);               
            }

        }
        catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], $this->successStatus);
        }
    }

}
