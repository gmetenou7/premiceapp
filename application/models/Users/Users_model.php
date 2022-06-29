<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Users_model extends CI_Model{

        /**chercher le client d'un utilisateur donné au niveau du profil*/
        public function costomus_search($mat_en,$recherche,$matricule_emp){
            
            $this->db->like('client.nom_cli', $recherche, 'both');

            $this->db->where('client.mat_en',$mat_en);
            $this->db->where('client.mat_emp',$matricule_emp);

            $this->db->order_by('client.creer_le_cli','DESC');

            $query= $this->db->get('client');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }


        /**selection des pays */
        public function get_pays(){
            $this->db->select('pays.nom_fr_fr');
            $query = $this->db->get('pays');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**selection des formes juridique */
        public function get_form_juridique(){
            $this->db->select('form_juridique.nom_form');
            $query = $this->db->get('form_juridique');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }

        /**creer une entreprise ou un groupe */
        public function insert_entreprise($input){
            $query = $this->db->insert('entreprise',$input);
            if($query){
                return $query;
            }else{
                return false;
            } 
        }
        
        /**modifier le mot de passe du groupe... selectionne les information de l'entreprise ou groupe grace au lien */
        public function get_single_entreprise($email){
            $this->db->where('entreprise.email_en',$email);
            $query = $this->db->get('entreprise');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }

        /**affiche les informations d'une entreprise grace a son matricule*/
        public function getsingleentreprise($matricule){
            $this->db->where('entreprise.matricule_en',$matricule);
            $query = $this->db->get('entreprise');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }


        /**debut fonctions pour le login */

        /**login avec les informations de l'entreprise*/
        public function login_en($input){
            $this->db->where('entreprise.matricule_en',$input);
            $this->db->or_where('entreprise.email_en',$input);
            $this->db->or_where('entreprise.nom_en',$input);
            $query = $this->db->get('entreprise');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            } 
        }

        /**je selection un employé pour une entreprise**/
        public function login_emp1($input){
            $this->db->join('entreprise','employe.mat_en = entreprise.matricule_en','LEFT');
            $this->db->where('employe.matricule_emp',$input);
            $this->db->or_where('employe.email_emp',$input);
            $this->db->or_where('employe.nom_emp',$input);
            $query = $this->db->get('employe');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            } 
        }

        /**pour l'employé trouvé, je selection son agence et son service*/
        public function login_emp2($input){
            $this->db->join('agence','employe.mat_ag = agence.matricule_ag','LEFT');
            $this->db->join('service','employe.mat_serv = service.matricule_serv','LEFT');
            $this->db->where('employe.matricule_emp',$input);
            $this->db->or_where('employe.email_emp',$input);
            $this->db->or_where('employe.nom_emp',$input);
            $query = $this->db->get('employe');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            } 
        }



         /**fin fonctions pour le login */


         /**selectionné un employé donné*/
         public function getsingleemploye($matricule){
            $this->db->where('employe.matricule_emp',$matricule);
            $query = $this->db->get('employe');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            } 
        }

        /**garder l'historique de connexion */
        public function story_login1($input){
            $query = $this->db->insert('historique_connexion_en',$input);
            if($query){
                return $query;
            }else{
                return false;
            } 
        }
        public function story_login2($input){
            $query = $this->db->insert('historique_connexion_emp',$input);
            if($query){
                return $query;
            }else{
                return false;
            } 
        }

        /**enregistrer un employé dans la base des données */
        public function save_employe($data){
            $query = $this->db->insert('employe', $data);
            if($query){
                return $query;
            }else{
                return false;
            }
        }


        /**affiche la liste de tous les employées */
        public function get_employes($amtricule_en){
            $amtricule_ag = session('users')['matricule_ag'];
            if(!empty($amtricule_ag)){$this->db->where('employe.mat_ag',$amtricule_ag);}
            $this->db->where('employe.mat_en',$amtricule_en);
            $query = $this->db->get('employe');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }

        /**désactivé LE COMPTE D'un employé */
        public function desactive_employe($matricule,$input){
            $this->db->where('employe.matricule_emp',$matricule);
            $query = $this->db->update('employe',$input);
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        
        /**activé le compte d'un employé */
        public function active_employe($matricule,$input){
            $this->db->where('employe.matricule_emp',$matricule);
            $query = $this->db->update('employe',$input);
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**detail d'un employé */
        public function detail_employe($matricule){
            $this->db->join('agence','employe.mat_ag = agence.matricule_ag','LEFT');
            $this->db->join('service','employe.mat_serv = service.matricule_serv','LEFT');
            $this->db->where('employe.matricule_emp',$matricule);
            $query = $this->db->get('employe');
            if($query->num_rows() > 0){
                return $query->row_array();
            }else{
                return false;
            }
        }
        
        /**on selectionne l'historique des mutations en fonction de lutilisateur */
        public function historique_mutation($matricule){
            $this->db->join('agence','historique_transfert_employe.id_agence = agence.matricule_ag','LEFT');
            $this->db->join('service','historique_transfert_employe.id_service = service.matricule_serv','LEFT');
            $this->db->where('historique_transfert_employe.id_employe',$matricule);
            $query = $this->db->get('historique_transfert_employe');
            if($query->num_rows() > 0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**modifier les informations d'un employé */
        public function update_employe($input,$matricule){
            $this->db->where('employe.matricule_emp',$matricule);
            $query = $this->db->update('employe',$input);
            if($query){
                return $query;
            }else{
                return false;
            } 
        }


        /**recherche les employé d'une agence ou d'un service dans une entreprise */
        public function recherche($matricule_en,$recherche){

            $this->db->like('employe.nom_emp', $recherche, 'both');

            $amtricule_ag = session('users')['matricule_ag'];
            if(!empty($amtricule_ag)){
                $this->db->where('employe.mat_ag',$amtricule_ag);
            }

            $this->db->where('employe.mat_en',$matricule_en);
            $query= $this->db->get('employe');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }

        /**filtrer un employé */
        public function filter_users($agence,$service,$matricule_en){
            $agence = !empty($agence)?$agence:NULL;
            $service = !empty($service)?$service:NULL;
            $this->db->where('employe.mat_ag',$agence);
            $this->db->where('employe.mat_serv',$service);
            $this->db->where('employe.mat_en',$matricule_en);
            $query= $this->db->get('employe');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }  
        }

        /**on enregistre l'historique de mutation d'un utilisateur */
        public function save_historique_mutation($inputdata){
            $query = $this->db->insert('historique_transfert_employe', $inputdata);
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**modifier le mot de passe d'une entreprise */
        public function update_pass_en($matricul,$input){
            $this->db->where('entreprise.matricule_en',$matricul);
            $query= $this->db->update('entreprise',$input);
            if($query){
                return $query;
            }else{
                return false;
            } 
        }
        

        /**modifier une entreprise */
        public function update_entreprise($matricule, $input){
            $this->db->where('entreprise.matricule_en',$matricule);
            $query= $this->db->update('entreprise',$input);
            if($query){
                return $query;
            }else{
                return false;
            }
        }
        
        /** afficher la liste des client, d'un utilisateur*/
        public function costomuser(){
             $this->db->join('agence','employe.mat_ag = agence.matricule_ag','LEFT');
            $this->db->join('service','employe.mat_serv = service.matricule_serv','LEFT');
            $this->db->join('entreprise','agence.mat_en = entreprise.matricule_en','LEFT');
            $this->db->where('employe.mat_en',$amtricule_en);
            $query = $this->db->get('employe');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }
        
        /**liste des client d'un employé dans une entreprise*/
        public function costomus($mat_en,$mat_emp){
          $this->db->join('employe','client.mat_emp = employe.matricule_emp','LEFT'); 
          $this->db->join('entreprise','employe.mat_en = entreprise.matricule_en','LEFT'); 
          
          $this->db->where('client.mat_emp',$mat_emp);
          $this->db->where('entreprise.matricule_en',$mat_en);
          
          $query= $this->db->get('client');
          if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }
        
       
	
        /**selectionne toutes les ventes d'une utilisaeur a une date donnée*/
        public function selsuserperiod($mat_en,$mat_emp,$date){
          $this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT'); 
          $this->db->join('employe','documents.code_employe = employe.matricule_emp','LEFT'); 
          $this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT'); 
          
          $this->db->where('documents.code_employe',$mat_emp);
          $this->db->where('documents.code_entreprie',$mat_en);
          $this->db->where('DATE(documents.date_creation_doc)',$date);
          
          $query= $this->db->get('documents');
          if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }
        
        /**selectionne toutes les achats d'un cli a une date donnée*/
        public function selsuserperiodcli($mat_en,$mat_cli,$date){
          $this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT'); 
          $this->db->join('employe','documents.code_employe = employe.matricule_emp','LEFT'); 
          $this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT'); 
          
          $this->db->where('documents.code_client_document',$mat_cli);
          $this->db->where('documents.code_entreprie',$mat_en);
          $this->db->where('DATE(documents.date_creation_doc)',$date);
          
          $query= $this->db->get('documents');
          if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }


        /**
         * 1: on selectionne les clients de l'utilisateurs qui apparaissent aumoins une fois dans les ventes
         * sur une periode en RT, RC, BR
        */
        public function costomusvente_get($mat_en,$mat_emp,$abrev,$debut,$fin){
            $this->db->distinct();
            $this->db->select('client.nom_cli,documents.code_client_document');
            $this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
            $this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
            $this->db->join('employe','client.mat_emp = employe.matricule_emp','LEFT');
            $this->db->where_in('type_document.abrev_doc',$abrev);
            $this->db->where('DATE(documents.date_creation_doc) BETWEEN "'.$debut. '" and "'.$fin.'"');
            if(!empty($mat_emp)){$this->db->where('client.mat_emp',$mat_emp);}
            $this->db->where('documents.code_entreprie',$mat_en);
            $queryclivente = $this->db->get('documents');
            if($queryclivente->num_rows()>0){
                return $queryclivente->result_array();
            }else{
                return false;
            }
        }

        
        /**pour chaque client, ayant éffectuté les achats, on selectionne ses achats en question */
        public function selsuserperiod1($abrev,$mat_en,$mat_cli,$debut,$fin){
            $this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');

            $this->db->where('documents.code_entreprie',$mat_en);
            $this->db->where('documents.code_client_document',$mat_cli);
            $this->db->where_in('type_document.abrev_doc',$abrev);
            $this->db->where('DATE(documents.date_creation_doc) BETWEEN "'.$debut. '" and "'.$fin.'"');

            $querydocsvente = $this->db->get('documents');
            if($querydocsvente->num_rows()>0){
                return $querydocsvente->result_array();
            }else{
                return false;
            }
        }
       /**selectionne toutes les achats d'un cli entre 2 dates donnée*/
        public function selsuserperiod1cli($mat_en,$mat_cli,$debut,$fin){
          $this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT'); 
          $this->db->join('employe','documents.code_employe = employe.matricule_emp','LEFT'); 
          $this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT'); 
          
          $this->db->where('documents.code_client_document',$mat_cli);
          $this->db->where('documents.code_entreprie',$mat_en);
          //$this->db->where('DATE(documents.date_creation_doc)',$date);
          $this->db->where('DATE(documents.date_creation_doc) BETWEEN "'.$debut. '" and "'.$fin.'"');
          
          $query= $this->db->get('documents');
          if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }
        
        
        
        /**selectionne un employé(users en fonction) du client */
        public function getempcostomers($codecli,$codeen){

            $this->db->join('client','client.mat_emp = employe.matricule_emp','LEFT'); 

            $this->db->where('client.matricule_cli',$codecli);
            $this->db->where('employe.mat_en',$codeen);

            $query= $this->db->get('employe');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }

    
    
    
    
    
    
    
    
    
    }