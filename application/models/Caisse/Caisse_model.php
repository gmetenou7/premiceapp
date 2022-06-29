<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Caisse_model extends CI_Model{

		/**liste des agence d'une entreprise */
		public function all_agence($matricule_en){
			$this->db->join('entreprise','agence.mat_en = entreprise.matricule_en','LEFT');
			$this->db->where('entreprise.matricule_en',$matricule_en);
			$query = $this->db->get('agence');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**liste des employés d'une entreprise */
		public function all_employe($matricule_en){
			$this->db->join('entreprise','employe.mat_en = entreprise.matricule_en','LEFT');
			$this->db->where('entreprise.matricule_en',$matricule_en);
			$query = $this->db->get('employe');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}


		/**creer une nouvelle caisse */
		public function new_caisse($input_data){
			$query = $this->db->insert('caisse',$input_data);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		/**liste des caisses d'une entreprise */
		public function all_db_caisse($matricule_en){
			$this->db->join('entreprise','caisse.code_entreprise = entreprise.matricule_en','LEFT');
			$this->db->where('entreprise.matricule_en',$matricule_en);
			$query = $this->db->get('caisse');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**selectionné une caisse particulière debut */
		public function single_db_caisse($matricule_en,$mat_caisse){
			$this->db->where('caisse.code_entreprise',$matricule_en);
            $this->db->where('caisse.code_caisse',$mat_caisse);
            $query = $this->db->get('caisse');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
		}
		/**selectionné une caisse particulière fin */

		/**modifier une caisse debut */
		public function update_caisse($input_data,$code){
            $this->db->where('caisse.code_caisse',$code);
            $query = $this->db->update('caisse',$input_data);
            if($query){
                return $query;
            }else{
                return false;
            }
		}

		
		
    }