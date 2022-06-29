<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Notification_model extends CI_Model{

        /**selectionne le matricule, le nom, date de naissance de l'employé */
        public function get_employe_informations($matricule_en){
            $this->db->select('employe.matricule_emp,employe.nom_emp,employe.date_naiss_emp');
            $this->db->where('employe.mat_en',$matricule_en);
            $query = $this->db->get('employe');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }


        /**enregistrer l'historique des notifications */
        public function save_notification($data){
            $query = $this->db->insert('notification',$data);
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**verifie si la notification d'anniversaire est deja dans la bd pour eviter d'inserer plusieurs fois*/
        public function verify_notification($mois,$matricul_emp){
            $this->db->select('notification.code_notification');
            $this->db->where('MONTH(notification.date_notification)',$mois);
            $this->db->where('matricule_concerner',$matricul_emp);
            $query = $this->db->get('notification');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }


        /**mettre a jour la notification d'anniversaire */
        public function update_notification($matricul_emp,$data){
            $this->db->where('matricule_concerner',$matricul_emp);
            $query = $this->db->update('notification',$data);
            if($query){
                return $query;
            }else{
                return false;
            }
        }


        /**compter le nombre de notification non lu pour afficher */
        public function count_notification($matricule_en){
            $etat=1;
            $this->db->where('notification.entreprise',$matricule_en);
            $this->db->where('notification.etat_notification',$etat);
            $query = $this->db->get('notification');
            if($query->num_rows()>0){
                return $query->num_rows();
            }else{
                return false;
            } 
        }

        /**affiche les messages d'anniversaire non lu */
        public function hbd_notification($matricule_en){
            $this->db->where('notification.entreprise',$matricule_en);
            $this->db->order_by('notification.code_notification','ASC');
            $this->db->limit(10);
            $query = $this->db->get('notification');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }

        /**avoir les details d'une notification */
        public function get_single_notification($codenotif){
            $this->db->where('notification.code_notification',$codenotif);
            $query = $this->db->get('notification');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }

        /**affiche toutes les notifications qui exite dans la bd*/
        public function notifications($matricule_en){
            $this->db->where('notification.entreprise',$matricule_en);
            $this->db->order_by('notification.code_notification','ASC');
            $query = $this->db->get('notification');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }

        

         /**modifier l'etat d'une notification*/
        public function update_etat_notification($matricul_notif,$data){
            $this->db->where('notification.code_notification',$matricul_notif);
            $query = $this->db->update('notification',$data);
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**compter toutes les notifications d'une entreprise donnéé */
        public function count_all($matricule_en){
            $this->db->where('notification.entreprise',$matricule_en);
            $query = $this->db->get('notification');
            if($query->num_rows()>0){
                return $query->num_rows();
            }else{
                return false;
            }
        }

            

    }