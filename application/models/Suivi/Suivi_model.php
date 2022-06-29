<?php


	defined('BASEPATH') OR exit('No direct script access allowed');

	class Suivi_model extends CI_Model{


        /**liste des article dans la table article document sur une periode */
        public function storystockart($article,$code_en,$debut,$fin){
            $query = $this->db->where('article_document.code_article',$article)
                            ->where('article_document.code_en_art_doc',$code_en)
                            ->where('CAST(article_document.date_creer_art_doc AS DATE) BETWEEN"'.$debut.'"AND"'.$fin.'"')
                            ->order_by('article_document.date_creer_art_doc','DESC')
                            ->get('article_document');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**information sur un article en fonction du code de l'article en question*/
        public function singleinfoarticle($matricule,$codeart){
            $query = $this->db->where('article.mat_en',$matricule)
                            ->where('article.matricule_art',$codeart)
                            ->get('article');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }

        /**information sur l'employé en fonction du code de l'article */
        public function singleinfoemp($matricule,$code_emp){
            $query = $this->db->where('employe.mat_en',$matricule)
                            ->where('employe.matricule_emp',$code_emp)
                            ->get('employe');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }


        /**on selectionne le document et le type en fonction du code de document */
        public function singleinfodocandtype($matricule,$code_document){
            $query = $this->db->join('type_document','documents.code_type_doc=type_document.code_doc','LEFT')
                            ->where('documents.code_entreprie',$matricule)
                            ->where('documents.code_document',$code_document)
                            ->get('documents');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }  
        }


        /**on selectionne les informations sur un depot en particulier */
        public function singleinfodepot($matricule,$depot){
            $query = $this->db->where('depot.code_en_d',$matricule)
                            ->where('depot.mat_depot',$depot)
                            ->get('depot');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            } 
        }

        /*public function storystockart($article,$type_document,$code_en,$debut,$fin){
            $this->db->join('article_document','article_document.code_document = documents.code_document','LEFT');
            $this->db->join('article','article_document.code_article = article.matricule_art','LEFT');
            
            $this->db->join('depot','documents.depot_doc = depot.mat_depot','LEFT');
            
            $this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
            $this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
            
            $this->db->where('type_document.code_doc',$type_document);
            $this->db->where('article_document.code_article',$article);
            $this->db->where('entreprise.matricule_en',$code_en);
            
            $this->db->where('CAST(article_document.date_creer_art_doc AS DATE) BETWEEN"'.$debut.'"AND"'.$fin.'"');
            $this->db->order_by('article_document.date_creer_art_doc','DESC');
            
             $query = $this->db->get('documents');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }*/


        
        
    }