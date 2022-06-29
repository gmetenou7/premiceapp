<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Comptabilite_model extends CI_Model{



		/***selectionne tous les employé dans les documents sur une période*/
		public function users_docs1($matricule_en,$date){
		    $this->db->distinct();
			$this->db->select('documents.code_employe,employe.nom_emp');
			$this->db->join('employe','documents.code_employe = employe.matricule_emp','LEFT');
			$this->db->where('documents.code_entreprie',$matricule_en);
			$this->db->where('DATE(documents.date_creation_doc)',$date);
			
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}
		public function users_docs2($matricule_en,$debut,$fin){
		    $this->db->distinct();
			$this->db->select('documents.code_employe,employe.nom_emp');
			$this->db->join('employe','documents.code_employe = employe.matricule_emp','LEFT');
			$this->db->where('documents.code_entreprie',$matricule_en);
			$this->db->where('DATE(documents.date_creation_doc) BETWEEN "'.$debut. '" and "'.$fin.'"');
			
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}
		

		/**on selectionne toutes les factures en dette pour faire des alerts et etat des dettes*/
        public function alertdettedb($matricule_en,$abevtdoc){
            
            $this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('employe','documents.code_employe = employe.matricule_emp','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			
			$this->db->where('type_document.abrev_doc',$abevtdoc);
			$this->db->where('documents.code_entreprie',$matricule_en);
			
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
        }

	


	

		
	








	}