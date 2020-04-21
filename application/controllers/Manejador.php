<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manejador extends CI_Controller {


    public function __construct() {
        parent::__construct();        
            $this->load->model('Query','man');         
          }

	public function index()
	{
        $db = json_decode($this->man->getDb(),true);
        $this->layout->view('test',['dbs'=>$db]);
	}
	public function ejecutar()
	{
        $postData = json_encode($this->input->post());
        $json_file = json_decode($this->man->getDb(),true);
        $data = json_decode($postData,true);
        $dbs= $json_file; 

        $errores = [];
        $correctas = [];
        foreach ($data['id'] as  $id) {

            foreach ($dbs as $key => $db) {
                if($id == $db['id']){
                    try{
                        $myPDO = new PDO("mysql:host={$db['host']};dbname={$db['name']};port={$db['port']}", $db['user'], $db['pass']);
                        $result= $myPDO->prepare($data['query']);
                        $pan= $result->execute();
                        if($pan == false){

                        array_push($errores,$result->errorCode(). ' '.$result->errorInfo()[2]);

                        }  else {
                            array_push($correctas, $db['name']. " correcto");
                        }
                        
                            } catch(PDOException $e){
                                array_push($errores,"error en la tabla {$db['name']} ". $e->getMessage());
                            }
                }
            }

        }
        if(!empty($errores)){
            echo json_encode(['errores' =>$errores]);
        } 
        if(!empty($correctas)){
            echo json_encode(['correcto' =>$correctas]);
        } 
        
    }
    
    public function consultas(){
        $consultas = $this->man->getConsultas();
        $this->layout->view('consultas',['consultas'=>$consultas]);

    }
    public function getConsultas(){
        $consultas = $this->man->getConsultas();
        return print_r(json_encode($consultas));
    }
    public function saveConsulta(){
        // $consultas = $this->man->getConsultas();
        $postData = json_encode($this->input->post());
        $this->man->saveConsulta(json_decode($postData,true));
        // return print_r($postData);
    }

    public function updateConsulta(){

        $postData = json_encode($this->input->post());
        $json_file = json_decode($this->man->getConsultas(),true);
        $data = json_decode($postData,true);
        $consultas= $json_file;
        $array_final=[];
        foreach ($consultas as $key => $consulta) {
            if($consulta['id'] == $data['id']){
                unset($data['id']);
                array_push($array_final,$data);
            }else{
                unset($consulta['id']);
                array_push($array_final,$consulta);
            }
            
        }


        $final_data = json_encode($array_final);
    file_put_contents(dirname(__FILE__,3).'\json\consultas.json', $final_data);

        return print_r($array_final);

    }
}
