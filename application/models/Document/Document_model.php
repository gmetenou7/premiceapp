<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Document_model extends CI_Model{

        /**affiche tous les type de document */
		public function get_doc($mat_en){
			$this->db->where('type_document.code_entr',$mat_en);
			$query = $this->db->get('type_document');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**enregistrer un  nouveau document */
		public function save_doc($inputs){
			$query = $this->db->insert('type_document',$inputs);
			if($query){
				return $query;
			}else{
				return false;
			}
		}


		/**affiche tous les informations d'untype de document donnée*/
		public function get_single_doc($code_doc){
			$this->db->where('type_document.code_doc',$code_doc);
			$query = $this->db->get('type_document');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**modifier un type de document */
		public function update_doc($inputs,$code_doc){
			$this->db->where('type_document.code_doc',$code_doc);
			$query = $this->db->update('type_document',$inputs);
			if($query){
				return $query;
			}else{
				return false;
			}
		}





    }