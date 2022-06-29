<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Annonce_model extends CI_Model{

	    /**nouvelle annonce*/
	    public function newannonce($inputdata){
	        $query = $this->db->insert('annonces',$inputdata);
	        if($query){
	           return $query; 
	        }else{
	            return false;
	        }
	    }
	    
	
	    /***selectionne la liste des annonces pour l'entreprise connecter*/
	    public function getallannonce($codeen){
	        $this->db->join('employe','annonces.employe_annonce = employe.matricule_emp','LEFT');
	        $this->db->join('entreprise','annonces.entreprise_annonce = entreprise.matricule_en','LEFT');
	        $this->db->where('annonces.entreprise_annonce',$codeen);
	        $query = $this->db->get('annonces');
	        if($query->num_rows()>0){
	           return $query->result_array(); 
	        }else{
	            return false;
	        }
	    }
	    
	    /***supprimer une annonce*/
	    public function delannonce($codeannonce){
	        $this->db->where('annonces.codeannonce',$codeannonce);
	        $query = $this->db->delete('annonces');
	        if($query){
	           return $query; 
	        }else{
	            return false;
	        }
	    }
	    

    }