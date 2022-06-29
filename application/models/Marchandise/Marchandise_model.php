<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Marchandise_model extends CI_Model{
	    
	    public function listdepot($matricule_en){
            $this->db->join('entreprise','depot.`code_en_d = entreprise.matricule_en','LEFT');
            $this->db->where('depot.code_en_d',$matricule_en);
            $query = $this->db->get('depot');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
	    }

        /**affiche la liste des article en function du code de l'entreprise */
        //public function get_all_articles($matricule_en){
        public function get_all_articles($matricule_en, $limit, $start){
            $this->db->join('article','stock.code_article = article.matricule_art','LEFT');
            $this->db->join('famille_produit','article.mat_famille_produit = famille_produit.matricule_fam','LEFT');
            $this->db->join('depot','stock.code_depot = depot.mat_depot','LEFT');
            $this->db->join('entreprise','stock.code_entreprise = entreprise.matricule_en','LEFT');

            $this->db->where('entreprise.matricule_en',$matricule_en);

            $this->db->limit($limit, $start);
            
            $query = $this->db->get('stock');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }
        
        public function get_all_articlesfilter($matricule_en,$famille){
            $this->db->join('article','stock.code_article = article.matricule_art','LEFT');
            $this->db->join('famille_produit','article.mat_famille_produit = famille_produit.matricule_fam','LEFT');
            $this->db->join('depot','stock.code_depot = depot.mat_depot','LEFT');
            $this->db->join('entreprise','stock.code_entreprise = entreprise.matricule_en','LEFT');
            
            $this->db->where('entreprise.matricule_en',$matricule_en);
            $this->db->where('famille_produit.matricule_fam',$famille);
             
            $query = $this->db->get('stock');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**compte le nombre d'article en fonction de l'entreprise */
        public function count_all_articles($matricule_en){
            $this->db->join('article','stock.code_article = article.matricule_art','LEFT');
            $this->db->join('famille_produit','article.mat_famille_produit = famille_produit.matricule_fam','LEFT');
            $this->db->join('depot','stock.code_depot = depot.mat_depot','LEFT');
            $this->db->join('entreprise','stock.code_entreprise = entreprise.matricule_en','LEFT');

            $this->db->where('entreprise.matricule_en',$matricule_en);
            
            $query = $this->db->get('stock');
            if($query->num_rows()>0){
                return $query->num_rows();
            }else{
                return false;
            } 
        }

        /**compte le nombre d'article en fonction de la recherche */
        public function count_recherche_all_articles($matricule_en,$recherche){
            $this->db->join('article','stock.code_article = article.matricule_art','LEFT');
            $this->db->join('famille_produit','article.mat_famille_produit = famille_produit.matricule_fam','LEFT');
            $this->db->join('depot','stock.code_depot = depot.mat_depot','LEFT');
            $this->db->join('entreprise','stock.code_entreprise = entreprise.matricule_en','LEFT');

            $this->db->where('entreprise.matricule_en',$matricule_en);

            $this->db->like('article.designation', $recherche, 'both'); 
            $this->db->or_like('article.matricule_art', $recherche, 'both'); 
            $this->db->or_like('article.code_bar', $recherche, 'both');
            $this->db->or_like('stock.quantite', $recherche, 'both');  

            $query = $this->db->get('stock');
            if($query->num_rows()>0){
                return $query->num_rows();
            }else{
                return false;
            }
        }

        /**rechercher un article en particulier en function de la saisi*/
        public function recherche_all_articles($matricule_en,$recherche, $limit, $start){
            $this->db->join('article','stock.code_article = article.matricule_art','LEFT');
            $this->db->join('famille_produit','article.mat_famille_produit = famille_produit.matricule_fam','LEFT');
            $this->db->join('depot','stock.code_depot = depot.mat_depot','LEFT');
            $this->db->join('entreprise','stock.code_entreprise = entreprise.matricule_en','LEFT');

            $this->db->where('entreprise.matricule_en',$matricule_en);

            $this->db->like('article.designation', $recherche, 'both'); 
            $this->db->or_like('article.matricule_art', $recherche, 'both'); 
            $this->db->or_like('article.code_bar', $recherche, 'both');
            $this->db->or_like('stock.quantite', $recherche, 'both');  
            
            $this->db->limit($limit, $start);

            $query = $this->db->get('stock');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

     

        /**rechercher un article en particulier en function de la saisi et de la famille d'article*/
        public function recherche_all_articles2($matricule_en,$recherche,$famille){
            $this->db->join('article','stock.code_article = article.matricule_art','LEFT');
            $this->db->join('famille_produit','article.mat_famille_produit = famille_produit.matricule_fam','LEFT');
            $this->db->join('depot','stock.code_depot = depot.mat_depot','LEFT');
            $this->db->join('entreprise','stock.code_entreprise = entreprise.matricule_en','LEFT');

            $this->db->where('entreprise.matricule_en',$matricule_en);
            $this->db->where('famille_produit.matricule_fam',$famille);

           /* $this->db->where('article.designation', $recherche); 
            $this->db->or_where('article.matricule_art', $recherche); 
            $this->db->or_where('article.code_bar', $recherche);
            $this->db->or_where('stock.quantite', $recherche); */ 

            $this->db->like('article.designation', $recherche, 'both'); 
            $this->db->or_like('article.matricule_art', $recherche, 'both'); 
            $this->db->or_like('article.code_bar', $recherche, 'both');
            $this->db->or_like('stock.quantite', $recherche, 'both'); 
            
            $query = $this->db->get('stock');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }
        
        /**tri les articles en stock en foncton de la famille d'article*/
        public function recherche_all_articles3($matricule_en,$famille){
            $this->db->join('article','stock.code_article = article.matricule_art','LEFT');
            $this->db->join('famille_produit','article.mat_famille_produit = famille_produit.matricule_fam','LEFT');
            $this->db->join('depot','stock.code_depot = depot.mat_depot','LEFT');
            $this->db->join('entreprise','stock.code_entreprise = entreprise.matricule_en','LEFT');

            $this->db->where('entreprise.matricule_en',$matricule_en);
            $this->db->where('famille_produit.matricule_fam',$famille);

            
            $query = $this->db->get('stock');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

      
        /**liste des article pour verifier que l'articl est périmé ou pas. ou encore dans combien de temps il sera périmer */
        public function article($matricule_en){
            $this->db->join('entreprise','article.mat_en = entreprise.matricule_en','LEFT');
            $this->db->where('entreprise.matricule_en',$matricule_en);
            $query = $this->db->get('article');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**selectionne et fait la somme des quantités en stock d'un article pour les depot ou il se trouve et en fonction de l'entreprise */
        public function qte_art_stock_en($matricule_en, $code_art){
            $this->db->select_sum('stock.quantite');
            $this->db->join('entreprise','stock.code_entreprise = entreprise.matricule_en','LEFT');
            $this->db->where('entreprise.matricule_en',$matricule_en);
            $this->db->where('stock.code_article',$code_art);
            $query = $this->db->get('stock');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false; 
            }
        }
        
        public function testgetstock(){
            $query = $this->db->get('stock');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false; 
            }
        }
        
        public function testgetarticle($codeart){
            $this->db->where('article.matricule_art',$codeart);
            $query = $this->db->get('article');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false; 
            }
        }
        
        public function famille_art($matricule_en,$designation){
            $this->db->join('article','article.mat_famille_produit = famille_produit.matricule_fam','LEFT');
            $this->db->join('entreprise','famille_produit.mat_en_fam = entreprise.matricule_en','LEFT');
            $this->db->where('famille_produit.mat_en_fam',$matricule_en);
            $this->db->where('article.designation',$designation);
            $query = $this->db->get('famille_produit');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false; 
            }
        }
        

    }