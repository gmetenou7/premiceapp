<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Point_model extends CI_Model{
	    
	    
	    /***insertion d'une nouvelle config de point par prix*/
	    public function new_point($input){
	        $query = $this->db->insert('config_point_prix',$input);  
	        if($query){
	           return $query; 
	        }else{
	          return false;  
	        }
	    }
	    
	    /***liste de toutes les config en focntion de l'entreprise*/
	    public function allconfigpoint($code_en){
            $this->db->join('employe','config_point_prix.point_emp = employe.matricule_emp','LEFT');
			$this->db->join('entreprise','config_point_prix.point_en = entreprise.matricule_en','LEFT');
			$this->db->where('config_point_prix.point_en',$code_en);
			$query = $this->db->get('config_point_prix');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
	    }
	    
	    /***supprimer une config en fonction de l'entreprise*/
	    public function deleteconfigpoint($code_en,$codepoint){
	        $this->db->where('config_point_prix.point_en',$code_en);
	        $this->db->where('config_point_prix.code_point',$codepoint);
	        $query = $this->db->delete('config_point_prix');
			if($query){
				return $query;
			}else{
				return false;
			}  
	    }
	    
	    
	    
	    
	    
	}