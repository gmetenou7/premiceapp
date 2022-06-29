<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Fournisseur_model extends CI_Model{

		/*enrégistré un nouveau fournisseur*/
		public function new_fournisseur($input){
			$query = $this->db->insert('fournisseur',$input);
			if($query){
				return $query;
			}else{
				return false;
			}
		}


		/****selectionner tous les fournisseur d'une entreprise */
		public function get_all_fournisseur($matricule){
			$this->db->select('fournisseur.nom_four, fournisseur.matricule_four');
			$this->db->join('entreprise','fournisseur.mat_en = entreprise.matricule_en','LEFT');
			$this->db->where('entreprise.matricule_en',$matricule);
			$query = $this->db->get('fournisseur');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}


		/**selectionner un fourniseur particulier */
		public function get_single_fournisseur($matricule){
			$this->db->where('fournisseur.matricule_four',$matricule);
			$query = $this->db->get('fournisseur');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**modifier un fournisseur */
		public function update_fournisseur($matricule,$input){
			$this->db->where('fournisseur.matricule_four',$matricule);
			$query = $this->db->update('fournisseur',$input);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

	}