<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Inbox_model extends CI_Model{
        
        /**enregistrer le nombre de message */
        public function savenbrmsg($inputval){
            $query = $this->db->insert('configuration_message',$inputval);
            if($query){
                return $query;
            }else{
                return false;
            }
        }


        /**selectionne les messages de configuration en fonction de l'entreprise */
        public function selectconfigsmsg($code_en){
            $query = $this->db->join('entreprise','configuration_message.en_config_msg = entreprise.matricule_en','LEFT')
                    ->join('employe','configuration_message.emp_config_msg = employe.matricule_emp','LEFT')
                    ->where('configuration_message.en_config_msg',$code_en)
                    ->order_by('configuration_message.date_creation_config_msg','DESC')
                    ->get('configuration_message');

            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }


        /**supprimer la configuration d'un message */
        public function deletenbrmsg($id,$code_en){
            $query = $this->db->where('configuration_message.id_msg_config',$id)
            ->where('configuration_message.en_config_msg',$code_en)
            ->delete('configuration_message');
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**afficher la liste des clients d'une entreprise */
        public function get_all_client($code_en){
            $query = $this->db->where('client.mat_en',$code_en)
                    ->order_by('client.nom_cli','ASC')
                    ->get('client');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**liste des employés d'une entreprise */
        public function get_all_employe($code_en){
            $query = $this->db->where('employe.mat_en',$code_en)
                    ->where('employe.etat_emp','on')
                    ->order_by('employe.nom_emp','ASC')
                    ->get('employe');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**selection un client en fonction du code de client et de l'entreprise*/
        public function getsingleuserinformation($value,$code_en){
            $query = $this->db->where('client.mat_en',$code_en)
                    ->where('client.matricule_cli',$value)
                    ->order_by('client.nom_cli','ASC')
                    ->get('client');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }

        /**save new message */
        public function savenewmsg($inputmsg){
            $query = $this->db->insert('messages',$inputmsg);
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**enregistrer l'historique des message */
        public function historiquedesmsg($inputstorymsg){
            $query = $this->db->insert('recevoir_message',$inputstorymsg);
            if($query){
                return $query;
            }else{
                return false;
            }
        }


        /**on selectionne la liste des messages en fonction du code de l'entreprise */
        public function selectallsendmessages($matricule,$limit, $start){
            $query = $this->db->where('messages.code_en_message',$matricule)
                            ->limit($limit, $start)
                            ->order_by('messages.creer_le_message','DESC')
                            ->get('messages');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**selectionne et retourne le nombre de message de l'entreprise */
        public function count_all_message($matricule){
            $query = $this->db->where('messages.code_en_message',$matricule)
                            ->get('messages');
            if($query->num_rows()>0){
                return $query->num_rows();
            }else{
                return false;
            }
        }

        

        /**selectionne les informations sur la personne qui a envoyé le message*/
        public function sendermsg($code_en,$code_emp){
            $query = $this->db->select('employe.nom_emp')
                        ->where('employe.mat_en',$code_en)
                        ->where('employe.matricule_emp',$code_emp)
                        ->get('employe');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }


        /**liste des clients qui ont recu le message */
        public function allreceivermessage($code_message){
            $query = $this->db->select('client.nom_cli,client.matricule_cli')
                            ->join('client','recevoir_message.code_cli=client.matricule_cli','LEFT')
                            ->where('recevoir_message.code_msg',$code_message)
                            ->get('recevoir_message');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false; 
            }
        }

        /**selectionne un message en fonction du code de l'entreprise */
        public function getsignlemessage($matricule,$codemsg){
            $query = $this->db->where('messages.code_message',$codemsg)
                            ->where('messages.code_en_message',$matricule)
                            ->get('messages');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false; 
            }
        }




    }