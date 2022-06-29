<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Sav_model extends CI_Model{


        /**enregistrer un equipement pour sav dans la base des données*/
        public function save_equipement($input){
            $query = $this->db->insert('equipement',$input);
            if($query){
                return $query;
            }else{
                return false;
            }
        }


        /**selectionner la liste des équipements maintenu */
        public function all_equipement($matricule_en,$limit, $start){
            $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT');
            $this->db->join('entreprise','equipement.code_entreprise = entreprise.matricule_en','LEFT');
            $this->db->join('employe','equipement.code_employe = employe.matricule_emp','LEFT');
            $this->db->where('entreprise.matricule_en',$matricule_en);
            $this->db->order_by('equipement.date_creer_equip','DESC');
            $this->db->limit($limit, $start);
            $query = $this->db->get('equipement');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**affiche tous les quipements dans le formulaire de diagnosqtique */
        public function all_equipement_diagnostique($matricule_en){
            $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT');
            $this->db->where('equipement.code_entreprise',$matricule_en);
            $this->db->order_by('equipement.date_creer_equip','DESC');
            $query = $this->db->get('equipement');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }


        /**compte les equipements d'une entreprise donnée */
        public function count_all_equip($matricule_en){
            $this->db->where('equipement.code_entreprise',$matricule_en);
            $query = $this->db->get('equipement');
            if($query->num_rows()>0){
                return $query->num_rows();
            }else{
                return false;
            }
        }

        /**selectionne la liste des diagnostiques */
        public function all_diagnostique($matricule_en,$limit, $start){
            $this->db->join('employe','diagnostiquer.code_employe = employe.matricule_emp','LEFT');
            $this->db->join('equipement','diagnostiquer.code_equipement = equipement.code_equip','LEFT');
            $this->db->join('entreprise','diagnostiquer.code_entreprise = entreprise.matricule_en','LEFT');
            $this->db->where('diagnostiquer.code_entreprise',$matricule_en);
            $this->db->order_by('diagnostiquer.date_diagnostique','DESC');
            $this->db->limit($limit, $start);
            $query = $this->db->get('diagnostiquer');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**affiche tous les code de diagnostique pour maintenance */
        public function all_diagnostique_maintenance($matricule_en){
            $this->db->join('equipement','diagnostiquer.code_equipement = equipement.code_equip','LEFT');
            $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT');
            $this->db->join('entreprise','diagnostiquer.code_entreprise = entreprise.matricule_en','LEFT');
            $this->db->where('diagnostiquer.code_entreprise',$matricule_en);
            $this->db->order_by('diagnostiquer.date_diagnostique','DESC');
            $query = $this->db->get('diagnostiquer');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        
        /**compte le nombre d'élément diagnostiqué pour la pagination */
        public function count_all_diagnostique($matricule_en){
            $this->db->where('diagnostiquer.code_entreprise',$matricule_en);
            $query = $this->db->get('diagnostiquer');
            if($query->num_rows()>0){
                return $query->num_rows();
            }else{
                return false;
            }
        }

        
        /**selectionner la liste complète des maintenances */
        public function all_maintenance($matricule_en,$limit, $start){
            $this->db->join('diagnostiquer','maintenace.code_diagnostique = diagnostiquer.matricule_diagnostique','LEFT');
            $this->db->join('equipement','diagnostiquer.code_equipement = equipement.code_equip','LEFT');
            $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT');
            $this->db->join('employe','maintenace.code_employe = employe.matricule_emp','LEFT');
            $this->db->join('entreprise','diagnostiquer.code_entreprise = entreprise.matricule_en','LEFT');
            $this->db->where('maintenace.code_entreprise',$matricule_en);
            $this->db->order_by('maintenace.date_creer_reparation','DESC');
            $this->db->limit($limit, $start);
            $query = $this->db->get('maintenace');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**compte toutes les maintenance pour la pagination*/
        public function count_all_maintenance($matricule_en){
            $this->db->where('maintenace.code_entreprise',$matricule_en);
            $query = $this->db->get('maintenace');
            if($query->num_rows()>0){
                return $query->num_rows();
            }else{
                return false;
            }
        }

        /**rapposte de maintenance sur une periode */
        public function rapport_maintenance($matricule_en,$date_debut,$date_fin){
            $this->db->join('diagnostiquer','maintenace.code_diagnostique = diagnostiquer.matricule_diagnostique','LEFT');
            $this->db->join('equipement','diagnostiquer.code_equipement = equipement.code_equip','LEFT');
            $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT');
            $this->db->join('employe','maintenace.code_employe = employe.matricule_emp','LEFT');
            $this->db->join('entreprise','diagnostiquer.code_entreprise = entreprise.matricule_en','LEFT');
            $this->db->where("DATE(maintenace.date_creer_reparation) BETWEEN '$date_debut' AND '$date_fin'");
            $this->db->where('maintenace.code_entreprise',$matricule_en);
            $this->db->order_by('maintenace.date_creer_reparation','DESC');
            $query = $this->db->get('maintenace');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }
        
        /**rapport de diagnostique sur une periode */
        public function rapport_diagnostique($matricule_en,$date_debut,$date_fin){
            $this->db->join('employe','diagnostiquer.code_employe = employe.matricule_emp','LEFT');
            $this->db->join('equipement','diagnostiquer.code_equipement = equipement.code_equip','LEFT');
            $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT');
            $this->db->join('entreprise','diagnostiquer.code_entreprise = entreprise.matricule_en','LEFT');
            $this->db->where("DATE(diagnostiquer.date_diagnostique) BETWEEN '$date_debut' AND '$date_fin'");
            $this->db->where('diagnostiquer.code_entreprise',$matricule_en);
            $this->db->order_by('diagnostiquer.date_diagnostique','DESC');
            $query = $this->db->get('diagnostiquer');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**génère le rapport de maintenance sous forme de pdf en fonction de la date et du matricule de l'entreprise*
        public function rapport_maintenance($matricule_en, $date_debut){
            $this->db->join('diagnostiquer','maintenace.code_diagnostique = diagnostiquer.matricule_diagnostique','LEFT');
            $this->db->join('equipement','diagnostiquer.code_equipement = equipement.code_equip','LEFT');
            $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT');
            $this->db->join('employe','client.mat_emp = employe.matricule_emp','LEFT');
            $this->db->join('entreprise','diagnostiquer.code_entreprise = entreprise.matricule_en','LEFT');
            $this->db->where('entreprise.matricule_en',$matricule_en);
            $this->db->where('maintenace.date_creer_reparation',$date_debut);
            $this->db->order_by('maintenace.code_reparation','DESC');
            $query = $this->db->get('maintenace');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
                
            }
        }*/

        /**génère le rapport de maintenance entre 2 dates *
        public function rapport_maintenance_entre_2_dates($matricule_en, $date_debut, $date_fin){
            $this->db->join('diagnostiquer','maintenace.code_diagnostique = diagnostiquer.matricule_diagnostique','LEFT');
            $this->db->join('equipement','diagnostiquer.code_equipement = equipement.code_equip','LEFT');
            $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT');
            $this->db->join('employe','client.mat_emp = employe.matricule_emp','LEFT');
            $this->db->join('entreprise','diagnostiquer.code_entreprise = entreprise.matricule_en','LEFT');
            $this->db->where('entreprise.matricule_en',$matricule_en);

            $this->db->where("maintenace.date_creer_reparation BETWEEN '$date_debut' AND '$date_fin'");

            $this->db->order_by('maintenace.code_reparation','DESC');
            $query = $this->db->get('maintenace');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
                
            }
        }*/

        


        /**rechercher un equipement, avec son nom, son code, sa reference, son numero de serie, son maintenancier pour l'entreprise connceté*/
        public function recherche_equipement($matricule,$recherche){ 
            $query = $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT')
                    ->join('employe','equipement.code_employe = employe.matricule_emp','LEFT')
                    ->where('equipement.code_entreprise', $matricule)
                    ->like('client.nom_cli', $recherche, 'both')
                    ->order_by('equipement.date_creer_equip','DESC')
                    ->get('equipement');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**rechercher un diagnostique selon: le nom du client* */
        public function recherche_diagnostique($matricule_en,$recherche){ 
            $this->db->join('employe','diagnostiquer.code_employe = employe.matricule_emp','LEFT');
            $this->db->join('equipement','diagnostiquer.code_equipement = equipement.code_equip','LEFT');
            $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT');
            $this->db->join('entreprise','diagnostiquer.code_entreprise = entreprise.matricule_en','LEFT');

            $this->db->like('client.nom_cli', $recherche, 'both');

            $this->db->where('diagnostiquer.code_entreprise',$matricule_en);


            $this->db->order_by('diagnostiquer.date_diagnostique','DESC');

            $query = $this->db->get('diagnostiquer');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**recherher une maintenance selon: le nom du client
         */
        public function recherche_maintenance($matricule_en,$recherche){
            $this->db->join('diagnostiquer','maintenace.code_diagnostique = diagnostiquer.matricule_diagnostique','LEFT');
            $this->db->join('equipement','diagnostiquer.code_equipement = equipement.code_equip','LEFT');
            $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT');
            $this->db->join('employe','maintenace.code_employe = employe.matricule_emp','LEFT');
            $this->db->join('entreprise','diagnostiquer.code_entreprise = entreprise.matricule_en','LEFT');

            $this->db->like('client.nom_cli', $recherche, 'both'); 

            $this->db->where('maintenace.code_entreprise',$matricule_en);
            $this->db->order_by('maintenace.date_creer_reparation','DESC');
            $query = $this->db->get('maintenace');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        

        /**affiche les détals d'un équipement */
        public function single_equipement($equip_mat){
            $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT');
            $this->db->join('entreprise','equipement.code_entreprise = entreprise.matricule_en','LEFT');
            $this->db->join('employe','client.mat_emp = employe.matricule_emp','LEFT');
            $this->db->where('equipement.code_equip',$equip_mat);
            $query = $this->db->get('equipement');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }

        /**modifier un équipement */
        public function update_equip($input,$matricule){
            $this->db->where('equipement.code_equip',$matricule);
            $query = $this->db->update('equipement',$input);
            if($query){
                return $query;
            }else{
                return false;
            }
        }


        /**gestion des diagnostique */

        /**nouveau diagnostique */
        public function save_diagnostique($data){
            $query = $this->db->insert('diagnostiquer',$data);
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**affiche les details d'un diagnostique */
        public function single_diagnostique($diagnostique_mat){
            $this->db->join('employe','diagnostiquer.code_employe = employe.matricule_emp','LEFT');
            $this->db->join('equipement','diagnostiquer.code_equipement = equipement.code_equip','LEFT');
            $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT');
            $this->db->join('entreprise','diagnostiquer.code_entreprise = entreprise.matricule_en','LEFT');
            $this->db->where('diagnostiquer.matricule_diagnostique',$diagnostique_mat);
            $query = $this->db->get('diagnostiquer');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }

        /**modifier un diagnostique en particuleir */
        public function update_diagnostique($input,$matricule_diagnost){
            $this->db->where('diagnostiquer.matricule_diagnostique',$matricule_diagnost);
            $query = $this->db->update('diagnostiquer',$input);
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**inserer dans la base des données une nouvelle maintenance */
        public function new_maintenance($data){
            $query = $this->db->insert('maintenace',$data); 
            if($query){
                return $query;
            }else{
                return false;
            }
        }

  

        /**afficher les details d'une maintenance données */
        public function singlemaintenance($code_maintenance){
            $this->db->join('diagnostiquer','maintenace.code_diagnostique = diagnostiquer.matricule_diagnostique','LEFT');
            $this->db->join('equipement','diagnostiquer.code_equipement = equipement.code_equip','LEFT');
            $this->db->join('client','equipement.code_client = client.matricule_cli','LEFT');
            $this->db->join('employe','maintenace.code_employe = employe.matricule_emp','LEFT');
            $this->db->join('entreprise','maintenace.code_entreprise = entreprise.matricule_en','LEFT');
            $this->db->where('maintenace.code_reparation',$code_maintenance);
            $query = $this->db->get('maintenace');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }

        /**modifier la maintenance */
        public function update_maintenance($data,$code_maint){
            $this->db->where('maintenace.code_reparation',$code_maint);
            $query = $this->db->update('maintenace',$data); 
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**liste des client */
		public function all_client($matricule_en){
			$this->db->where('client.mat_en',$matricule_en);
			$query = $this->db->get('client');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}


    }