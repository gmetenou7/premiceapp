<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Stock_model extends CI_Model{

        /**inserer une famille des produit dans la base des données */
        public function save_famille($input){
            $query = $this->db->insert('famille_produit',$input);
            if($query){
               return $query;
            }else{
                return false;
            }
        }
        
    		            
        /**modifier le pourcentage de marge des articles en fonction de la famille*/
        public function updatemarge($data,$matricule_en,$famille,$matart){
            
            $this->db->where('article.matricule_art',$matart);
            $this->db->where('article.mat_en',$matricule_en);
            $this->db->where('article.mat_famille_produit',$famille);
            $query = $this->db->update('article',$data);
            if($query){
                return $query;
            }else{
                return false;
            } 
        }

        /**liste des famille des produit */
        public function all_famille($matricule_entre){
            
            $this->db->select('famille_produit.nom_fam,famille_produit.matricule_fam,famille_produit.creer_le_fam');
            $this->db->join('employe','famille_produit.mat_emp_fam = employe.matricule_emp','LEFT');
            $this->db->join('entreprise','employe.mat_en = entreprise.matricule_en','LEFT');
            $this->db->where('entreprise.matricule_en',$matricule_entre);
            $query = $this->db->get('famille_produit');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }   
        }


        /**affiche les détails d'une famille de produit */
        public function detail_famille($matricule_famille){
            $this->db->join('employe','famille_produit.mat_emp_fam = employe.matricule_emp','LEFT');
            $this->db->where('famille_produit.matricule_fam',$matricule_famille);
            $query = $this->db->get('famille_produit');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            } 
        }

        /**modifier une famille de produits */
        public function update_famille($input,$matricule){
            $this->db->where('famille_produit.matricule_fam',$matricule);
            $query = $this->db->update('famille_produit',$input);
            if($query){
                return $query;
            }else{
                return false;
            } 
        }

        /**rechercher une famille de produit */
        public function recherche_famille($matricule_entre,$recherche){
            $this->db->join('employe','famille_produit.mat_emp_fam = employe.matricule_emp','LEFT');
            $this->db->join('entreprise','employe.mat_en = entreprise.matricule_en','LEFT');
            $this->db->where('entreprise.matricule_en',$matricule_entre);
            $this->db->like('famille_produit.matricule_fam', $recherche, 'both'); 
            $this->db->or_like('famille_produit.nom_fam',$recherche,'both');
            $query = $this->db->get('famille_produit');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }    
        }

        /**enrégistrer un nouvel article dans la base des données */
        public function save_article($datas){
            $query = $this->db->insert('article',$datas);
            if($query){
                return $query;
            }else{ 
                return false;
            }
        }


        /**selectionne tous les articles de la base des données debut*/

        /**avec pagination */
        public function get_all_articles($code_en,$limit, $start){
            $this->db->where('article.mat_en',$code_en);
            $this->db->limit($limit, $start);
            $this->db->order_by('article.designation','ASC');
            $query = $this->db->get('article');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }   
        }

        /**pour exposter en excel et bien d'autre*/
        public function get_all_article($code_en){
            $this->db->where('article.mat_en',$code_en);
            $query = $this->db->get('article');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }   
        }


        /**selectionne tous les articles de la base des données fin*/

        /**compte le nombre d'article dans la base des données  pour pagination*/
        public function count_all_article($code_en){
            $this->db->where('article.mat_en',$code_en);
            $query = $this->db->get('article');
            if($query->num_rows()>0){
                return $query->num_rows();
            }else{
                return false;
            }   
        }


        /**afficher les details sur un article */
        public function get_details_articles($code_article){
            $this->db->join('fournisseur','article.mat_fournisseur = fournisseur.matricule_four','LEFT');
            $this->db->join('famille_produit','article.mat_famille_produit = famille_produit.matricule_fam','LEFT');
            $this->db->join('employe','article.mat_employe = employe.matricule_emp','LEFT');
            $this->db->where('article.matricule_art',$code_article);
            $query = $this->db->get('article');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            } 
        }

        /**modifier un article*/
        public function update_article($datas,$mat_art){
            $this->db->where('article.matricule_art',$mat_art);
            $query = $this->db->update('article',$datas);
            if($query){
                return $query;
            }else{ 
                return false;
            }
        }

        /**chercher un article dans la base des données selon;
         *son code, 
         sa désignation, 
         sa référence, 
         son prix de revient, 
         son prix hors taxe, 
         son pourcentage de marge, 
         son fournisseur, 
         sa famille, 
         le nom de l'employé l'ayant enrégistré 
         */


        public function get_recherche_articles($code_en,$recherche){
           

            /*$this->db->join('fournisseur','article.mat_fournisseur = fournisseur.matricule_four','LEFT');
            $this->db->join('famille_produit','article.mat_famille_produit = famille_produit.matricule_fam','LEFT');
            $this->db->join('employe','article.mat_employe = employe.matricule_emp','LEFT');
            $this->db->join('entreprise','employe.mat_en = entreprise.matricule_en','LEFT');*/

            $this->db->like('article.matricule_art', $recherche, 'both');
            $this->db->or_like('article.code_bar', $recherche, 'both');
            $this->db->or_like('article.designation', $recherche, 'both');
            /*$this->db->or_like('article.reference', $recherche, 'both');
            $this->db->or_like('article.prix_revient', $recherche, 'both');
            $this->db->or_like('article.prix_hors_taxe', $recherche, 'both');
            $this->db->or_like('article.pourcentage_marge', $recherche, 'both');

            $this->db->or_like('article.prix_gros', $recherche, 'both');
            $this->db->or_like('article.critique', $recherche, 'both');
            $this->db->or_like('article.delais_alert_peremption', $recherche, 'both');
            $this->db->or_like('article.date_peremption', $recherche, 'both');


            $this->db->or_like('fournisseur.nom_four', $recherche, 'both');
            $this->db->or_like('famille_produit.nom_fam', $recherche, 'both');
            $this->db->or_like('employe.nom_emp', $recherche, 'both');*/
            

            
            $this->db->where('article.mat_en',$code_en);

            $this->db->order_by('article.designation','ASC');

            $query = $this->db->get('article');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }  
        }

        /**affiche les famille des produit et les fournisseur des articles enrégistré pour faciliter le tri debut */
        public function get_tri_famil_articles($code_en){
            $this->db->where('famille_produit.mat_en_fam',$code_en);
            $query = $this->db->get('famille_produit');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }
        /*public function get_tri_four_articles($code_en){
            $this->db->distinct();
            $this->db->select('article.mat_fournisseur, fournisseur.nom_four');
            $this->db->join('fournisseur','article.mat_fournisseur = fournisseur.matricule_four','LEFT');
            $this->db->join('famille_produit','article.mat_famille_produit = famille_produit.matricule_fam','LEFT');
            $this->db->where('article.mat_en',$code_en);
            $query = $this->db->get('article');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }*/
        /**affiche les famille des produit et les fournisseur des articles enrégistré pour faciliter le tri fin */

        /**tri les articles dans la base des données en function du fournisseur ou de la famille d'article debut */
        public function tri_article($code_en,$famille){ 
            $this->db->join('famille_produit','article.mat_famille_produit = famille_produit.matricule_fam','LEFT');
            $this->db->join('entreprise','article.mat_en = entreprise.matricule_en','LEFT');
            $this->db->where('article.mat_en',$code_en);
            $this->db->where('article.mat_famille_produit',$famille);
            $this->db->order_by('article.designation','ASC');
            $query = $this->db->get('article');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }
        /**tri les articles dans la base des données en function du fournisseur ou de la famille d'article fin */

       
        /**enregistrer un depot dans la base des données debut*/
        public function save_depot($input){
            $query = $this->db->insert('depot',$input);
            if($query){
               return $query;
            }else{
                return false;
            }
        }
        /**enregistrer un depot dans la base des données fin*/

        /**afficher tous les depot d'une entreprise debut */
        public function get_all_depot($entreprise,$limit, $start){
            $this->db->where('depot.code_en_d',$entreprise);
            $this->db->limit($limit, $start);
            $query = $this->db->get('depot');
            if($query->num_rows()>0){
               return $query->result_array();
            }else{
                return false;
            }
        }

        public function get_all_depot2($entreprise){
            $this->db->where('depot.code_en_d',$entreprise);
            $query = $this->db->get('depot');
            if($query->num_rows()>0){
               return $query->result_array();
            }else{
                return false;
            }
        }
        /**afficher d'une entreprise fin */


        /**compte le nombre de depot disponible debut */
        public function count_all_depot($entreprise){
            $this->db->where('depot.code_en_d',$entreprise);
            $query = $this->db->get('depot');
            if($query->num_rows()>0){
               return $query->num_rows();
            }else{
                return false;
            }
        }
        /**compte le nombre de depot disponible fin */


        /**les details d'un dépots debut */
        public function details_depot($matricule_depot){
            $this->db->join('agence','depot.code_ag_d = agence.matricule_ag','LEFT');
            $this->db->join('employe','depot.code_emp_d = employe.matricule_emp','LEFT');
            $this->db->where('depot.mat_depot',$matricule_depot);
            $query = $this->db->get('depot');
            if($query->num_rows()>0){
               return $query->row_array();
            }else{
                return false;
            }  
        }
        /**les details d'un depot fin */


        /**modifier un depot debut */
        public function update_depot($input,$matricule){
            $this->db->where('depot.mat_depot',$matricule);
            $query = $this->db->update('depot',$input);
            if($query){
               return $query;
            }else{
                return false;
            }  
        }
        /**modifier un depot fin */

        /**faire la recherche sur un depot debut */
        public function get_recherche_depot($entreprise,$rechercher){

            $this->db->join('agence','depot.code_ag_d = agence.matricule_ag','LEFT');
            $this->db->join('employe','depot.code_emp_d = employe.matricule_emp','LEFT');
            $this->db->where('depot.code_en_d',$entreprise);

            $this->db->like('depot.mat_depot', $rechercher, 'both');
            $this->db->or_like('depot.nom_depot', $rechercher, 'both');
            $this->db->or_like('agence.nom_ag', $rechercher, 'both');

            $query = $this->db->get('depot');
            if($query->num_rows()>0){
               return $query->result_array();
            }else{
                return false;
            } 
        }
        /**faire la recherche sur un depot fin */


        /**gestion des entrer en stock debut */

        /**afficher la liste des articles avec les stocks */
        public function get_art_stock($code_art,$codedepot){
            $this->db->join('stock','stock.code_article = article.matricule_art','LEFT');
            $this->db->join('depot','stock.code_depot = depot.mat_depot','LEFT');

            $this->db->where('article.matricule_art',$code_art);
            $this->db->where('depot.mat_depot',$codedepot);

            $query = $this->db->get('article');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }
        public function get_art_stock2($code_art){
            $this->db->where('article.matricule_art',$code_art);
            $this->db->join('stock','stock.code_article = article.matricule_art','LEFT');
            $query = $this->db->get('article');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }
        public function get_art_stock3($code_art){
            $this->db->where('article.matricule_art',$code_art);
            $query = $this->db->get('article');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }

        /**verifier le type de document */
        public function type_doc_stock($code_type_document,$code_entreprise){
            $this->db->where('type_document.code_doc',$code_type_document);
            $this->db->where('type_document.code_entr',$code_entreprise);
            $query = $this->db->get('type_document');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }  
        }

        /**verifier si le document existe déjà selon: 
         * le code de document, 
         * le type de document, 
         * le matricule de l'entreprise
         * */
       
        public function doc_stock($code_type_document, $code_document, $matricule_en){
            $this->db->where('documents.code_type_doc',$code_type_document);
            $this->db->where('documents.code_document',$code_document);
            $this->db->where('documents.code_entreprie',$matricule_en);
            $query = $this->db->get('documents');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            } 
        }

        /**creation d'un document pour pouvoir y mettre des articles */
        public function new_document($input_doc){
            $query = $this->db->insert('documents',$input_doc);
            if($query){
                return $query;
            }else{
                return false;
            }  
        }

        /**enregistre les article d'un document données */
        public function article_document($input__art_doc){
            $query = $this->db->insert('article_document',$input__art_doc);
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**verifie si l'article existe ou pas en stock pour le depot et l'article choisi */
        public function get_article_stock($code_article,$code_depot){
            $this->db->join('article','stock.code_article = article.matricule_art','LEFT');

            $this->db->where('stock.code_article',$code_article);
            $this->db->where('stock.code_depot',$code_depot);
           
            $query = $this->db->get('stock');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            } 
        }

        /**mettre un nouvel article en stock */
        public function enter_stock($input_stock_val){
            $query = $this->db->insert('stock',$input_stock_val);
            if($query){
                return $query;
            }else{
                return false;
            } 
        }

        /**mettre a jour le stock */
        public function update_stock($code_article, $input_stock_val){
            $this->db->where('stock.code_article',$code_article);
            $query = $this->db->update('stock',$input_stock_val);
            if($query){
                return $query;
            }else{
                return false;
            }  
        }
        public function update_stock2($code_article, $input_stock_val, $code_depot){
            $this->db->where('stock.code_article',$code_article);
            $this->db->where('stock.code_depot',$code_depot);
            $query = $this->db->update('stock',$input_stock_val);
            if($query){
                return $query;
            }else{
                return false;
            }  
        }

        /**je verifie si l'article existe déjà dans la table article_document */
        public function get_art_doc($code_article,$code_document,$code_en){
            $this->db->where('article_document.code_article',$code_article);
            $this->db->where('article_document.code_document',$code_document);
            $this->db->where('article_document.code_en_art_doc',$code_en);
            $query = $this->db->get('article_document');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            } 
        }

        /**mofier un article dans un document donnée */
        public function update_article_document($code_doc,$code_article,$input_art_doc){
            $this->db->where('article_document.code_document',$code_doc);
            $this->db->where('article_document.code_article',$code_article);
            $query = $this->db->update('article_document',$input_art_doc);
            if($query){
                return $query;
            }else{
                return false;
            } 
        }


        /**selectionne tous les documents en fonction du type de document*/
        public function get_doc_type($code_type_document){
            $this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
            $this->db->where('type_document.code_doc',$code_type_document);
            $this->db->order_by('documents.date_creation_doc','DESC');
            $query = $this->db->get('documents');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }

      

        /**affiche la liste des documents(nom, fournisseur, users, entreprise) selon un type de document donné */
        public function docs_list($code_type_document,$code_en){
            $this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
            $this->db->join('fournisseur','documents.code_fournisseur = fournisseur.matricule_four','LEFT');
            $this->db->join('employe','documents.code_employe = employe.matricule_emp','LEFT');
            $this->db->join('depot','documents.depot_doc = depot.mat_depot','LEFT');
            $this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');

            $this->db->where('type_document.code_doc',$code_type_document);
            $this->db->where('entreprise.matricule_en',$code_en);
            $this->db->order_by('documents.date_creation_doc','ASC');
            $query = $this->db->get('documents');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }
        public function docs_list2($code_type_document,$code_en,$debut,$fin){
            $this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
            $this->db->join('fournisseur','documents.code_fournisseur = fournisseur.matricule_four','LEFT');
            $this->db->join('employe','documents.code_employe = employe.matricule_emp','LEFT');
            $this->db->join('depot','documents.depot_doc = depot.mat_depot','LEFT');
            $this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');

            $this->db->where('type_document.code_doc',$code_type_document);
            $this->db->where('entreprise.matricule_en',$code_en);
            
            //CAST(datetime_expression AS DATE)

            $this->db->where('CAST(documents.date_creation_doc AS DATE) BETWEEN"'.$debut.'"AND"'.$fin.'"');
            $this->db->order_by('documents.date_creation_doc','ASC');
            $query = $this->db->get('documents');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }


        /**modifier un document en particulier */
        public function updatedocument($code_document,$document_input){
            $this->db->where('documents.code_document',$code_document);
            $query = $this->db->update('documents',$document_input);
            if($query){
                return $query;
            }else{
                return false;
            } 
        }        

        /**selectionne un document en fonction de son code et celui de l'entreprise */
        public function showdocument($code_document, $code_en){
            $this->db->where('documents.code_document',$code_document);
            $this->db->where('documents.code_entreprie',$code_en);
            $query = $this->db->get('documents');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            } 
        }
        /**affiche la liste des articles contenu dans un document 
         * 
         * 
        */

        public function articles_docucment($codedoc,$code_en){
            $this->db->join('article_document','article_document.code_article = article.matricule_art','LEFT');
            $this->db->join('documents','article_document.code_document = documents.code_document','LEFT');
            $this->db->join('employe','article.mat_employe = employe.matricule_emp','LEFT');
            $this->db->join('entreprise','article_document.code_en_art_doc = entreprise.matricule_en','LEFT');

            $this->db->where('documents.code_document',$codedoc);
            $this->db->where('entreprise.matricule_en',$code_en);
            
            $query = $this->db->get('article');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }
        

        
        /**gestion des entrer en stock fin */


        /**affiche laliste des transferts en attente debut */
        public function transfert_in_waitting($abrtypdoc,$code_en,$etatdoc){
            $this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
            $this->db->join('depot','depot.mat_depot = documents.depot_recept_doc','LEFT');
            $this->db->join('employe','documents.code_employe = employe.matricule_emp','LEFT');
            $this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
            $this->db->where('type_document.abrev_doc',$abrtypdoc);
            $this->db->where('entreprise.matricule_en',$code_en);
            $this->db->where('documents.etat_document',$etatdoc);
            $this->db->order_by('documents.date_creation_doc','DESC');
            $query = $this->db->get('documents');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            } 
        }
        /**affiche laliste des transferts en attente fin */

        /**pour annuler le transfert,
         * 1: on renvoit les quantité transferer dans le depot initial
         * 2: on supprime tous les articles du document
         * 3: on supprime le document en lui mm
         */
        
        /**2: suppression des articles dans le document */
        public function delete_art_stock($code_article, $code_document, $code_en){
            $this->db->where('article_document.code_document',$code_document);
            $this->db->where('article_document.code_article',$code_article);
            $this->db->where('article_document.code_en_art_doc',$code_en);
            $query = $this->db->delete('article_document');
            if($query){
                return $query;
            }else{
                return false;
            } 
        }
        
        
        /**3: on supprime le document lui mm */
        public function delete_doc($code_document, $depot_init_doc, $matricule){
            $this->db->where('documents.code_document',$code_document);
            $this->db->where('documents.depot_init_doc',$depot_init_doc);
            $this->db->where('documents.code_entreprie',$matricule);
            $query = $this->db->delete('documents');
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**gestion des inventaires debut */

        /***génere l'inventaire selon les paramettres */
        public function inventaire_param_stock($matricule,$depot,$famille){ //,$date_debut,$date_fin
            $this->db->join('depot','stock.code_depot = depot.mat_depot','LEFT');
            $this->db->join('article','stock.code_article = article.matricule_art','LEFT');
            $this->db->join('famille_produit','article.mat_famille_produit = famille_produit.matricule_fam','LEFT');
            $this->db->join('entreprise','stock.code_entreprise = entreprise.matricule_en','LEFT');

            $this->db->where('entreprise.matricule_en',$matricule);

            $this->db->where('depot.mat_depot',$depot);
            $this->db->where('famille_produit.matricule_fam',$famille);  
            //$this->db->where('stock.date_entrer_stock BETWEEN"'.$date_debut.'"AND"'.$date_fin.'"');
            
            $query = $this->db->get('stock');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }

        /**génère l'inventaire sans paramettre debut */

        /**affiche tous les articles d'un depot données */
        public function get_depot_art_stock($matricule, $mat_depot){
            $this->db->join('depot','stock.code_depot = depot.mat_depot','LEFT');
            $this->db->join('entreprise','stock.code_entreprise = entreprise.matricule_en','LEFT');
            $this->db->join('article','stock.code_article = article.matricule_art','LEFT');
            $this->db->join('famille_produit','article.mat_famille_produit = famille_produit.matricule_fam','LEFT'); 
            $this->db->order_by('famille_produit.nom_fam','ASC');
            $this->db->where('entreprise.matricule_en',$matricule);
            $this->db->where('depot.mat_depot',$mat_depot);
            $query = $this->db->get('stock');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }


        /**génère l'inventaire sans paramettre fin */


        /**gestion des inventaires fin */

        /**affiche le type de document en function du document et de l'entreprise debut*/
        public function type_document($code_document,$matricule_en){
            $this->db->join('documents','documents.code_type_doc = type_document.code_doc','LEFT');
            $this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
            $this->db->where('documents.code_document',$code_document);
            $this->db->where('entreprise.matricule_en',$matricule_en);
            $query = $this->db->get('type_document');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }
        /**affiche le type de document en function du document et de l'entreprise debut*/


        /**on compte le nombre d'article dans un document debut */
        public function count_articles_docucment($codedoc,$code_en){
            $this->db->join('documents','article_document.code_document = documents.code_document','LEFT');
            $this->db->join('entreprise','article_document.code_en_art_doc = entreprise.matricule_en','LEFT');

            $this->db->where('documents.code_document',$codedoc);
            $this->db->where('entreprise.matricule_en',$code_en);
            
            $query = $this->db->get('article_document');
            if($query->num_rows()>0){
                return $query->num_rows();
            }else{
                return false;
            }
        }
        /**on compte le nombre d'article dans un document fin */

        /**on supprimer le document grace a son code et le matricule de l'entreprise debut */
        public function delete_document($code_document, $matricule_en,$code_typ_doc){
            $this->db->where('documents.code_document',$code_document);
            $this->db->where('documents.code_entreprie',$matricule_en);
            $this->db->where('documents.code_type_doc',$code_typ_doc);
            $query = $this->db->delete('documents');
            if($query){
                return $query;
            }else{
                return false;
            }
        }

        /**on supprimer le document grace a son code et le matricule de l'entreprise fin */


        /**affiche la liste des types de document en fonction de la catégorie et de l'entreprise */
        public function type_doc($code_entreprise){
            $this->db->where('type_document.categorie_doc','stock');
            $this->db->where('type_document.code_entr',$code_entreprise);
            $query = $this->db->get('type_document');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
                return false;
            }
        }
        
        /*affiche le nom du depot initiateur et du depot recepteur dans le document de transfert*/
        public function namedepotinitandrecep($codedepot){
            $this->db->join('entreprise','depot.code_en_d = entreprise.matricule_en','LEFT');
            $this->db->where('depot.mat_depot',$codedepot);
            $query = $this->db->get('depot');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
                return false;
            }
        }
        
        

    }