<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Query extends CI_Model {
  

 function getDb(){
    $str = file_get_contents(base_url().'json/db.json');
        $json = json_decode($str);
        foreach ($json as $key => $db) {
                    $db->id= $key;

                }
        
    return json_encode($json);
 }

 function getConsultas(){
    
    $str = file_get_contents(base_url().'json/consultas.json');
    $json = json_decode($str);
    foreach ($json as $key => $db) {
                $db->id= $key;

            }
    
return json_encode($json);
 }
 function saveConsulta($data){

    $consultas = file_get_contents(base_url().'json/consultas.json');  
    $consultas_array = json_decode($consultas, true);  
    $extra = $data;
    $consultas_array[] = $extra;  
    $final_data = json_encode($consultas_array);
    file_put_contents(dirname(__FILE__,3).'\json\consultas.json', $final_data);


    return print_r($final_data);
 
 }

}
