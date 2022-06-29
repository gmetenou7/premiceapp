<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Taxe_modal extends CI_Model{

        /**inserer un dans une base des données la nouvelle taxe */
        public function new_db_taxe($datas){
            $query = $this->db->insert('taxe',$datas);
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**liste des taxes */
        public function all_db_taxe($matricule_en){
            $this->db->where('taxe.taxe_mat_en',$matricule_en);
            $query = $this->db->get('taxe');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**affiche une taxe particulière */
        public function single_db_taxe($matricule_en,$mat_taxe){
            $this->db->where('taxe.taxe_mat_en',$matricule_en);
            $this->db->where('taxe.codetaxe',$mat_taxe);
            $query = $this->db->get('taxe');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }

        /**update taxe particulière */
        public function update_db_taxe($input_data,$mattaxe){
            $this->db->where('taxe.codetaxe',$mattaxe);
            $query = $this->db->update('taxe',$input_data);
            if($query){
                return $query;
            }else{
                return false;
            }
        }


        /**on insert dans la base des données l'heur de cloture d'une facture donné */
        public function insert_time_clo_fact($timeval,$date,$matricule_en){

            $this->db->where('heur_clo_facture.mat_en_clo_fact',$matricule_en);
            $query = $this->db->get('heur_clo_facture');
            if($query->num_rows()>0){
                $inputval = array(
                    'heure_clo'=>$timeval,
                    'date_edite_clo'=>$date
                );

                $vals = $query->row_array();
                $this->db->where('heur_clo_facture.id_heur_clo',$vals['id_heur_clo']);
                $this->db->where('heur_clo_facture.mat_en_clo_fact',$matricule_en);
                $queryupdate = $this->db->update('heur_clo_facture',$inputval);
                if($queryupdate){
                    return $queryupdate;
                }else{
                    return false;
                }
            }else{
                $datas = array(
                    'heure_clo' =>$timeval,
                    'date_create_clo' =>$date,
                    'mat_en_clo_fact' =>$matricule_en
                );
                $queryinsert = $this->db->insert('heur_clo_facture',$datas);
                if($queryinsert){
                    return $queryinsert;
                }else{
                    return false;
                }
            }
        }


        /**afficher l'heur de cloture d'une facture enrégisterer */
        public function show_time_clo_fact($matricule_en){
            $this->db->where('heur_clo_facture.mat_en_clo_fact',$matricule_en);
            $query = $this->db->get('heur_clo_facture');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }


        /**requette de cloture
         * on cloture toutes les factures en RT pour une entreprise donné, à une heur donné
         * dont la colone clo !=1
         */
        public function cloturer_rt_fact_open($matricule_en,$abrev,$date){

            /**on selectionne d'abord l'heur de cloture enrégistrer */
            $this->db->where('heur_clo_facture.mat_en_clo_fact',$matricule_en);
            $query = $this->db->get('heur_clo_facture');
            if($query->num_rows()>0){
                $timedbs = $query->row_array();
                $timedb = date('H:i',strtotime($timedbs['heure_clo']));
                $timenow = date('H:i');
                if($timedb == $timenow){

                    /**on selectionne le code du type de document en fonction de l'entreprise et de l'abreviation*/
                    $this->db->where('type_document.code_entr',$matricule_en);
                    $this->db->where('type_document.abrev_doc',$abrev);
                    $querytype = $this->db->get('type_document');
                    if($querytype->num_rows()>0){
                        $typedocs = $querytype->row_array();

                        /**on cloture maintenant la facture */
                        $datas = array(
                            'cloturer'=>1,
                            'date_modifier_doc'=> $date
                        );
                        $dateDay = date('Y-m-d', strtotime($date));
                        $this->db->where('documents.code_entreprie',$matricule_en);
                        $this->db->where('documents.code_type_doc',$typedocs['code_doc']);
                        $this->db->where('DATE(documents.date_creation_doc)',$dateDay);
                        $this->db->where('documents.cloturer !=',1);
                        $queryupdatedoc = $this->db->update('documents',$datas);
                        if($queryupdatedoc){
                            return 'factures encours de clôture... '.timechrono($timedbs['heure_clo'],true);
                        }else{
                            return 'erreur survenu opération de cloture annuler... ';
                        }

                    }else{
                        return false;
                    }
                }else{

                    return 'les factures du jour seront clôturées dans '.timechrono($timedbs['heure_clo']);
                }
            }else{
                return false;
            }
        }

       


        
        


    }