<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Service_model extends CI_Model{

        /**inserve un nouveu service */
        public function new_service($data){
            $query = $this->db->insert('service',$data);
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**liste des services */
        public function all_service($mat_en){
            $this->db->distinct();
            $this->db->select('service.nom_serv,service.matricule_serv');
            $this->db->where('service.mat_en',$mat_en);
            $query = $this->db->get('service');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**affiche un service en particulier */
        public function single_service($matricule){
            $this->db->where('service.matricule_serv',$matricule);
            $query = $this->db->get('service');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }
        
        /**modifier un service  */
        public function update_service($input,$matricule){
            $this->db->where('service.matricule_serv',$matricule);
            $query = $this->db->update('service',$input);
            if($query){
                return $query;
            }else{
                return false;
            }
        }


        /*affiche les service d'une agence(recherche instantané)*/

        /**recherche les employé d'une agence ou d'un service dans une entreprise */
        public function recherche($matricule_en,$recherche){
            $this->db->select('service.nom_serv,service.matricule_serv');
            $this->db->join('agence','service.mat_ag = agence.matricule_ag','LEFT');
            $this->db->join('entreprise','agence.mat_en = entreprise.matricule_en','LEFT');
            $this->db->where('entreprise.matricule_en',$matricule_en);
            $this->db->like('agence.nom_ag', $recherche, 'both'); 
            $query= $this->db->get('service');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }


          /**selectionne la liste des service d'une agence donné*
          public function serviceagence($matricule_en,$matricule_agence){
            $this->db->select('service.nom_serv,service.matricule_serv');
            $this->db->join('agence','service.mat_ag = agence.matricule_ag','LEFT');
            $this->db->join('entreprise','agence.mat_en = entreprise.matricule_en','LEFT');
            $this->db->where('entreprise.matricule_en',$matricule_en);
            $this->db->where('agence.matricule_ag',$matricule_agence);
            $query= $this->db->get('service');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }

          }*/



    }