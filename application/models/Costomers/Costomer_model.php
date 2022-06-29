<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Costomer_model extends CI_Model{


		/**enrégistrer un nouveau client*/
		public function new_costomers($input){
			$query = $this->db->insert('client',$input);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		/***selectionne tous les clients d'une entreprise donnée*/
		public function all_client($matricule_en,$limit, $start){
			$this->db->where('client.mat_en',$matricule_en);
			$this->db->limit($limit, $start);
			$query = $this->db->get('client');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**on compte le nombre de client pour pagination */
		public function count_all_costomer($matricule_en){
			$this->db->where('client.mat_en',$matricule_en);
			$query = $this->db->get('client');
			if($query->num_rows()>0){
				return $query->num_rows();
			}else{
				return false;
			}
		}


		/**afficher les information d'un cient en particulier*/
		public function single_client($matricule_cli){
			$this->db->join('employe','client.mat_emp = employe.matricule_emp','LEFT');
			$this->db->where('client.matricule_cli',$matricule_cli);
			$query = $this->db->get('client');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}


		/**modifier un employé**/
		public function update_costomers($matricule_cli,$input){
			$this->db->where('client.matricule_cli',$matricule_cli);
			$query = $this->db->update('client',$input);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		
		/***faire la recherche pour afficher un client d'apprès son nom, son code, celui qui l'a enrégistré*/
		public function recherche($matricule_en,$input){
			$this->db->join('employe','client.mat_emp = employe.matricule_emp','LEFT');
			$this->db->join('entreprise','client.mat_en = entreprise.matricule_en','LEFT');
			$this->db->where('client.mat_en',$matricule_en);
			$this->db->like('client.matricule_cli', $input, 'both'); 
			$this->db->or_like('client.nom_cli', $input, 'both'); 
			$this->db->or_like('employe.nom_emp', $input, 'both');
			$query = $this->db->get('client');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**liste des employé pour faire la réattribution */
		public function all_employe($matricule_en){
			$this->db->where('employe.mat_en',$matricule_en);
			$query = $this->db->get('employe');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}


		/**selectionne un employer en particuler certains infos seront conserver pour l'historique de réattribution */
		public function getsingleclient($matricule_en,$matriculeclient){
			$this->db->where('client.mat_en',$matricule_en);
			$this->db->where('client.matricule_cli',$matriculeclient);
			$query = $this->db->get('client');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**enregistrer l'historique dde reattribution */
		public function savehistoriquemutation($inputval){
			$query = $this->db->insert('historique_reattribution_client',$inputval);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		/**modification de l'mploye d'un client */
		public function updateempcli($valupdate,$matriculeclient){
			$this->db->where('client.matricule_cli',$matriculeclient);
			$query = $this->db->update('client',$valupdate);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		/**afficher l'historique des reattribution */
		public function getallhistorique($matricule_en,$codeclient){
			$this->db->join('client','historique_reattribution_client.code_client = client.matricule_cli','LEFT');
			$this->db->join('employe','historique_reattribution_client.code_emp = employe.matricule_emp','LEFT');
			$this->db->where('historique_reattribution_client.code_en',$matricule_en);
			$this->db->where('historique_reattribution_client.code_client',$codeclient);
			$query = $this->db->get('historique_reattribution_client');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}






	}