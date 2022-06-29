<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Agence_model extends CI_Model{

		/**creer une nouvelle agence */
		public function new_agence($data){
			$query = $this->db->insert('agence',$data);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		/**liste des agence */
		public function get_agence($mat_en){
			$this->db->select('agence.nom_ag,agence.matricule_ag');
			$this->db->where('agence.mat_en',$mat_en);
			$query = $this->db->get('agence');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**affiche une agence en particulier*/
        public function get_single_agence($matricule){
			$this->db->where('agence.matricule_ag',$matricule);
			$query = $this->db->get('agence');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**modifier une agence particulière */
		public function update_agence($input,$matricule){
			$this->db->where('agence.matricule_ag',$matricule);
			$query = $this->db->update('agence',$input);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

    }