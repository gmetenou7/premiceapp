<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Home_model extends CI_Model{
	    
	    /*caisse ayant participer aumoins a une vente dans une entreprise*/
	    public function vente_caisse($matricule,$agence){
	        $this->db->distinct();
	        $this->db->select('caisse.*');
	        $this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			if(!empty($agence)){$this->db->where('documents.code_agence_doc',$agence);}
	        $this->db->where('documents.code_entreprie',$matricule);
	        $query = $this->db->get('documents');
	        if($query->num_rows()>0){
	            return $query->result_array();
	        }else{
	            return false;
	        }
	    }
		
		
		/**liste des articles pour un document donné*/
		public function articles_vendu($code_art,$matricule){
			
			$this->db->where('article_document.code_en_art_doc',$matricule);
			$this->db->where('article_document.code_article',$code_art);
			
			$query = $this->db->get('article_document');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**montant total dans la caisse d'une agence donné pour une caisse donné pour chaque date entrer*/
		public function docventecaisseagenceperiode($code_caisse,$matricule_en,$agence,$abrev,$date){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			$this->db->join('agence','documents.code_agence_doc = agence.matricule_ag','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			
			$this->db->where('documents.code_caisse',$code_caisse);
			$this->db->where('documents.code_entreprie',$matricule_en);
			$this->db->where('documents.code_agence_doc',$agence);

            $this->db->where_in('type_document.abrev_doc', $abrev);
            
            $this->db->where('DATE(documents.date_creation_doc)',$date);
            
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/*on selectionne les sortie de caisse d'une agence d'une entreprise, sur une peériode donné*/
		public function sortiecaisseagenceperiode($code_caisse,$matricule,$agence,$date){
		    $this->db->where('sorti_caisse.caisse_sorti',$code_caisse);
			$this->db->where('sorti_caisse.entreprise_sorti',$matricule);
			$this->db->where('sorti_caisse.agence_sorti',$agence);
		    $this->db->where('DATE(sorti_caisse.creer_le_sorti)',$date);
		    $query = $this->db->get('sorti_caisse');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}  
		}



		/**selectionner toutes les entrées en caisse d'une entreprise sur une periode de tous les clients client pour une agence*/
		public function entrercaisseagenceperiode($code_caisse,$matricule,$agence,$date){
		    
		    $this->db->join('entrer_caisse','historique_entrer_caisse.codeentrercaisse = entrer_caisse.matricule_enter','LEFT');
		    //$this->db->join('entreprise','entrer_caisse.entreprise_enter = entreprise.matricule_en','LEFT');
		    
		    $this->db->where('entrer_caisse.caisse_enter',$code_caisse);
			$this->db->where('entrer_caisse.entreprise_enter',$matricule);
			$this->db->where('entrer_caisse.agence_enter',$agence);
			
		    $this->db->where('DATE(historique_entrer_caisse.dateentrer)',$date);
		    $query = $this->db->get('historique_entrer_caisse');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}  
		}


		/**liste des documents de vente en fonction de l'entreprise, de la période et du type*/
		public function documentventeperiode($abrev,$code_en,$datedebut,$datefin){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');

			$this->db->where('documents.code_entreprie',$code_en);
			$this->db->where_in('type_document.abrev_doc',$abrev);

			$this->db->where('DATE(documents.date_creation_doc) BETWEEN"'.$datedebut.'"AND"'.$datefin.'"');

			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}
		public function documentventeperiode2($abrev,$code_en,$datedebut,$datefin,$agencerecherce){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');

			$this->db->where_in('type_document.abrev_doc',$abrev);
			$this->db->where('documents.code_entreprie',$code_en);
			if(!empty($agencerecherce)){$this->db->where('documents.code_agence_doc',$agencerecherce);}

			$this->db->where('DATE(documents.date_creation_doc) BETWEEN"'.$datedebut.'"AND"'.$datefin.'"');
			
			
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**liste de articles pour chaque document de vente */
		public function articledocumentvente($codedocument){
			$this->db->join('documents','article_document.code_document = documents.code_document','LEFT');
			$this->db->where('article_document.code_document',$codedocument);
			$query = $this->db->get('article_document');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**supprime les informations en fonction du code de l'entreprise pour la table a jour */
		public function deletearticlecomptableinformation($matricule){
			$query = $this->db->delete('articlecomptable', array('matriculeentreprisearticle' => $matricule));
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		/**enregistrer les informations dans la table articlecomptable */
		public function saveinformation($valinput){
			$query = $this->db->insert('articlecomptable', $valinput);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		/**GESTION DES ARTICLES LES PLUS VENDU DEBUT*/

		/**liste des articles comptable sur une periode en fonction de l'entreprise de manière unique */
		public function selectdistinctarticlecomptable($code_en,$datedebut,$datefin){
			$this->db->distinct();

			$this->db->where('articlecomptable.matriculeentreprisearticle',$code_en);
			$this->db->where('DATE(articlecomptable.datecreationarticle) BETWEEN"'.$datedebut.'"AND"'.$datefin.'"');

			$this->db->group_by('articlecomptable.codedocumentarticle');

			$query = $this->db->get('articlecomptable');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**GESTION DES ARTICLES LES PLUS VENDU FIN*/

		/**selectionne l'article en fonction de son code pour pouvoir avoir la famille et le nom de l'article */
		public function singlearticleshow($codearticle){
			$this->db->where('article.matricule_art',$codearticle);
			$query = $this->db->get('article');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**liste des articles en fonction du code de l'entreprise*/
		public function articleentreprise($matricule_en){
			$this->db->where('article.mat_en',$matricule_en);
			$query = $this->db->get('article');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**liste des article de la table articlecomptable*/
		public function articlescomptable($matricule_en,$datedebut,$datefin){
			$this->db->where('articlecomptable.matriculeentreprisearticle',$matricule_en);
			$this->db->where('DATE(articlecomptable.datecreationarticle) BETWEEN"'.$datedebut.'"AND"'.$datefin.'"');
			$query = $this->db->get('articlecomptable');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}


		/**ca des banques de l'entreprise sur une période */
		public function cabanque($matricule,$datedebut,$datefin){
			$this->db->join('entrer_caisse','historique_entrer_caisse.codeentrercaisse = entrer_caisse.matricule_enter','LEFT');
			$this->db->join('entreprise','entrer_caisse.entreprise_enter = entreprise.matricule_en','LEFT');

			$this->db->where('entrer_caisse.entreprise_enter',$matricule);
			$this->db->where('DATE(historique_entrer_caisse.dateentrer) BETWEEN"'.$datedebut.'"AND"'.$datefin.'"');
			$this->db->where('entrer_caisse.agence_enter',NULL);

			$query = $this->db->get('historique_entrer_caisse');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**afficher la famille d'un article
		 * on selectionne juste le code de la famaille dans la table article en fonction du code de l'article
		 */
		public function fam_article($codearticle){
			$this->db->where('article.matricule_art',$codearticle);
			$query = $this->db->get('article');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}


		/**liste des agences en fonction de l'entreprise */
		public function agence_entreprise($matricule){
			$this->db->where('agence.mat_en',$matricule);
			$query = $this->db->get('agence');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

	    
	}




