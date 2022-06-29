<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Commerce_model extends CI_Model{
       
		/**affiche la liste des depots en fonction de l'agence et du service */
		public function get_all_depot2($matricule,$agence){
			$this->db->where('depot.code_en_d',$matricule);
			$this->db->where('depot.code_ag_d',$agence);
			$query = $this->db->get('depot');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**affiche la liste de tous les clients d'une entreprise */
		/*public function get_all_client($matricule){
			$this->db->join('employe','client.mat_emp = employe.matricule_emp','LEFT');
			$this->db->join('entreprise','employe.mat_en = entreprise.matricule_en','LEFT');
			$this->db->where('entreprise.matricule_en',$matricule);
			$query = $this->db->get('client');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}*/

		/**liste des caisses d'une entreprise*/
		public function all_caisse_entreprise($matricule){
			$this->db->join('employe','caisse.gerant_emp = employe.matricule_emp','LEFT');
			$this->db->where('caisse.code_entreprise',$matricule);
			$query = $this->db->get('caisse');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**liste des caisses d'une entreprise en fonction de l'agance */
		public function all_caisse_agence($matricule,$agence){
			$this->db->join('employe','caisse.gerant_emp = employe.matricule_emp','LEFT');
			$this->db->where('caisse.code_entreprise',$matricule);
			$this->db->where('caisse.code_agence',$agence);
			$query = $this->db->get('caisse');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**liste des articles d'une entreprise */
		public function all_article_entreprise($matricule){
			$this->db->where('article.mat_en',$matricule);
			$query = $this->db->get('article');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**liste des taxes d'une entreprise */
		public function all_taxe_entreprise($matricule){
			$this->db->where('taxe.taxe_mat_en',$matricule);
			$query = $this->db->get('taxe');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}
		
		/*selectionnons le code de l'agence à partir de la caisse si l'agence est vide*/
		public function agencecaisse($caisse, $matricule){
			$this->db->where('caisse.code_caisse',$caisse);
			$this->db->where('caisse.code_entreprise',$matricule);
			$query = $this->db->get('caisse');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**on selectionne la caisse en fonction de l'agence */
		public function caisseagence($agence, $matricule){
			$this->db->where('caisse.code_agence',$agence);
			$this->db->where('caisse.code_entreprise',$matricule);
			$query = $this->db->get('caisse');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}


		/**affiche la liste des types de document en fonction de la catégorie et de l'entreprise */
        public function type_doc($code_entreprise){
            $this->db->where('type_document.categorie_doc','vente');
            $this->db->where('type_document.code_entr',$code_entreprise);
            $query = $this->db->get('type_document');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
				return false;	
            }
        }


		/**selection le pourcentage de la taxe en fonction du nom de la taxe et du matricule de l'entreprise */
		public function specifictaxe($nom_taxe,$code_entreprise){
			$this->db->where('taxe.nomtaxe',$nom_taxe);
            $this->db->where('taxe.taxe_mat_en',$code_entreprise);
            $query = $this->db->get('taxe');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
				return false;	
            }
		}


		/**selectionne un ducument spécifique  */
		public function specificdocument($code_doc,$code_en){
			$this->db->join('employe','documents.code_employe = employe.matricule_emp','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->where('documents.code_document',$code_doc);
            $this->db->where('documents.code_entreprie',$code_en);
            $query = $this->db->get('documents');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
				return false;	
            }
		}

		/**liste de tous les ratachements concernant un document en particulier et ses détails en fonction du code de document et de l'entreprise */
		public function documentratacheinformationsall($matricule, $code_document){
			$this->db->join('employe','historique_ractachement.inititeurop = employe.matricule_emp','LEFT');
			$this->db->join('caisse','historique_ractachement.codecaisseh = caisse.code_caisse','LEFT');
			$this->db->where('historique_ractachement.codedocument',$code_document);
            $this->db->where('historique_ractachement.codeenh',$matricule);
            $query = $this->db->get('historique_ractachement');
            if($query->num_rows()>0){
                return $query->result_array();
            }else{
				return false;	
            }
		}

		/**pour un type de document donné, le nom et l'entreprise, on veut verifier que le document existe déjà */
		public function specificdocumenttest($code_type_document,$nom_document,$code_en){
			$this->db->where('documents.code_type_doc',$code_type_document);
			$this->db->where('documents.nom_document',$nom_document);
            $this->db->where('documents.code_entreprie',$code_en);
            $query = $this->db->get('documents');
            if($query->num_rows()>0){
                return $query->row_array();
            }else{
				return false;	
            }
		}


		/**modifier le document */
		public function updatedocument($code_doc,$code_en,$datas){
			$this->db->where('documents.code_document',$code_doc);
            $this->db->where('documents.code_entreprie',$code_en);
            $query = $this->db->update('documents',$datas);
            if($query){
                return $query;
            }else{
				return false;	
            }
		}
		public function updatedocument2($code_doc,$code_en,$code_client,$datas){
			$this->db->where('documents.code_document',$code_doc);
            $this->db->where('documents.code_entreprie',$code_en);
			$this->db->where('documents.code_client_document',$code_client);
            $query = $this->db->update('documents',$datas);
            if($query){
                return $query;
            }else{
				return false;	
            }
		}

		/**modifier une entrer en caisse */
		public function update_entrer_caisse($codeentrer,$matricule_en,$code_client,$datas){
			$this->db->where('entrer_caisse.matricule_enter',$codeentrer);
			$this->db->where('entrer_caisse.client_enter',$code_client);
            $this->db->where('entrer_caisse.entreprise_enter',$matricule_en);
            $query = $this->db->update('entrer_caisse',$datas);
            if($query){
                return $query;
            }else{
				return false;	
            }
		}
		

		/*verifier que le document existe ou pas en fonction de l'entreprise */
		public function get_document($code_en,$code_doc){
			$query = $this->db->where('documents.code_document',$code_doc)
					->where('documents.code_entreprie',$code_en)
					->get('documents');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}

		}

		/**
		 * affiche la liste des article d'un document pour un client et une entreprise donné
		 */
		public function art_doc_cli_en($code_en,$code_doc){
			$this->db->join('article','article_document.code_article = article.matricule_art','LEFT');
			$this->db->join('documents','article_document.code_document = documents.code_document','LEFT');
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('entreprise','article_document.code_en_art_doc = entreprise.matricule_en','LEFT');
			$this->db->where('documents.code_document',$code_doc);
			$this->db->where('documents.code_entreprie',$code_en);
			$query = $this->db->get('article_document');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**pour ce code client, et le code de l'entreprise, selectionnons le client en question */
		public function client($code_en,$code_cli){
			$this->db->join('employe','client.mat_emp = employe.matricule_emp','LEFT');
			$this->db->join('entreprise','employe.mat_en = entreprise.matricule_en','LEFT');
			$this->db->where('client.matricule_cli',$code_cli);
			$this->db->where('entreprise.matricule_en',$code_en);
			$query = $this->db->get('client');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}


		/**selectionne tous les document en fonction du type de document et de l'entreprise */
		public function document($abrev,$code_en,$limit, $start){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->where('documents.code_entreprie',$code_en);
			
			$this->db->limit($limit, $start);
			$this->db->order_by('documents.date_creation_doc', 'DESC');
		
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}
		public function document2($abrev,$code_en){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->where('documents.code_entreprie',$code_en);
			
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}
		public function count_document($abrev,$code_en){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->where('documents.code_entreprie',$code_en);
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->num_rows();
			}else{
				return false;
			}
		}

		
		/**RECHERCHER UN DOCUMENT FIN */
		public function document_rechercher($abrev,$code_en,$recherche){ 

		    $this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
		    $this->db->join('employe','documents.code_employe = employe.matricule_emp','LEFT');
		    $this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			
			
			
			$this->db->like('documents.code_document', $recherche, 'both');
			$this->db->or_like('documents.nom_document', $recherche, 'both');
			$this->db->or_like('DATE(documents.date_creation_doc)', $recherche, 'both');
			$this->db->or_like('employe.nom_emp', $recherche, 'both');
			$this->db->or_like('employe.matricule_emp', $recherche, 'both');
			$this->db->or_like('client.matricule_cli', $recherche, 'both');
			$this->db->or_like('client.nom_cli', $recherche, 'both');
			$this->db->or_like('caisse.code_caisse', $recherche, 'both');
			$this->db->or_like('caisse.libelle_caisse', $recherche, 'both');

			
			$this->db->order_by('documents.date_creation_doc', 'DESC');

			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->where('documents.code_entreprie',$code_en);
			
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			} 
		}
		public function document_rechercher2($abrev,$code_en,$recherche,$agence){ 

		    $this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
		    $this->db->join('employe','documents.code_employe = employe.matricule_emp','LEFT');
		    $this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			
			
			
			$this->db->or_like('documents.code_document', $recherche, 'both');
			$this->db->or_like('documents.nom_document', $recherche, 'both');
			$this->db->or_like('DATE(documents.date_creation_doc)', $recherche, 'both');
			$this->db->or_like('employe.nom_emp', $recherche, 'both');
			$this->db->or_like('employe.matricule_emp', $recherche, 'both');
			$this->db->or_like('client.matricule_cli', $recherche, 'both');
			$this->db->or_like('client.nom_cli', $recherche, 'both');
			$this->db->or_like('caisse.code_caisse', $recherche, 'both');
			$this->db->or_like('caisse.libelle_caisse', $recherche, 'both');

			
			$this->db->order_by('documents.date_creation_doc', 'DESC');

			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->where('documents.code_entreprie',$code_en);
			$this->db->where('documents.code_agence_doc',$agence);
			
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			} 
		}

		/**selectionne tous les document en fonction de 2 types de document (RT,RC...) et de l'entreprise */
		public function documents_i($abrev,$code_en){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			$this->db->where_in('type_document.abrev_doc',$abrev);
			$this->db->where('documents.code_entreprie',$code_en);
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**selectionne le client d'un document donné */
		public function document_client($code_en, $code_doc){
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			$this->db->where('documents.code_document',$code_doc);
			$this->db->where('documents.code_entreprie',$code_en);
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}

		}

		/**selectionne tous les agences d'une entreprise */
		public function all_agence_entreprise($code_en){
			$this->db->join('entreprise','agence.mat_en = entreprise.matricule_en','LEFT');
			$this->db->where('agence.mat_en',$code_en);
			$query = $this->db->get('agence');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**selectionne une agence dans une entreprise */
		public function all_agence_agence($code_en,$agence){
			$this->db->join('entreprise','agence.mat_en = entreprise.matricule_en','LEFT');
			$this->db->where('agence.matricule_ag',$agence);
			$this->db->where('agence.mat_en',$code_en);
			$query = $this->db->get('agence');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**creer une sortie de caisse */
		public function new_sorti_caisse($input_data){
			$query = $this->db->insert('sorti_caisse',$input_data);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		/**affiche toutes les sortie de caisse */
		public function all_sorti_caisse($matricule_en,$codeagence,$limit,$start){
			$this->db->join('employe','sorti_caisse.employe_sorti = employe.matricule_emp','LEFT');
			$this->db->join('agence','sorti_caisse.agence_sorti = agence.matricule_ag','LEFT');
			$this->db->join('entreprise','sorti_caisse.entreprise_sorti = entreprise.matricule_en','LEFT');
			if(!empty($codeagence)){$this->db->where('sorti_caisse.agence_sorti',$codeagence);}
			$this->db->where('sorti_caisse.entreprise_sorti',$matricule_en);
			$this->db->limit($limit,$start);
			$this->db->order_by('sorti_caisse.creer_le_sorti','DESC');
			$query = $this->db->get('sorti_caisse');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**on compte le nombre de sortie de caisse pour la pagination */
		public function countdocsorticaisse($matricule_en,$codeagence){
			if(!empty($codeagence)){$this->db->where('sorti_caisse.agence_sorti',$codeagence);}
			$this->db->where('sorti_caisse.entreprise_sorti',$matricule_en);
			$query = $this->db->get('sorti_caisse');
			if($query->num_rows()>0){
				return $query->num_rows();
			}else{
				return false;
			}
		}

		/**supprimer une sortie de caisse */
		public function delete_sorti($code_s,$matricule_en){
			$this->db->where('sorti_caisse.matricule_sorti',$code_s);
			$this->db->where('sorti_caisse.entreprise_sorti',$matricule_en);
			$query = $this->db->delete('sorti_caisse');
			if($query){
				return $query;
			}else{
				return false;
			}
		}

	
		/**verifie si un client existe deja dans la table, entrer en caisse */
		public function verify_client($code_caisse,$code_client,$matricule_en){
			//$this->db->select('entrer_caisse.client_enter,entrer_caisse.montant_enter');
			$this->db->where('entrer_caisse.client_enter',$code_client);
			$this->db->where('entrer_caisse.caisse_enter',$code_caisse);
			$this->db->where('entrer_caisse.entreprise_enter',$matricule_en);
			$query = $this->db->get('entrer_caisse');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}
		
		/** on affiche l'historique des entrer en caisse pour un client donner*/
		public function historique_entrer_caisse($matenter){
		    $this->db->join('employe','historique_entrer_caisse.employe = employe.matricule_emp','LEFT');
		    $this->db->where('historique_entrer_caisse.codeentrercaisse',$matenter);
		    $this->db->order_by('historique_entrer_caisse.id', 'DESC');
		    $query = $this->db->get('historique_entrer_caisse');
    		if($query->num_rows()>0){
    			return $query->result_array();
    		}else{
    			return false;
    		}
		}
		

		/**nouvelle entrer en caisse */
		public function new_enter_caisse($input_data){
			$query = $this->db->insert('entrer_caisse',$input_data);
			if($query){
				return $query;
			}else{
				return false;
			}
		}
		
		/*nouvelle historique d'entrer caisse*/
		public function historique_enter_caisse($input_datah){
			$query = $this->db->insert('historique_entrer_caisse',$input_datah);
			if($query){
				return $query;
			}else{
				return false;
			}
		}
		
		/**historique de ratachement de facture et cheque en dette*/
		public function historiqueratachement($inputdoc){
		    $query = $this->db->insert('historique_ractachement',$inputdoc);
			if($query){
				return $query;
			}else{
				return false;
			}
		}
		
		/**selectionne et affiche l'historique de ratachement des factures en cheque et dette*/
		public function storyratachement($matricule,$codedocratach){
			$this->db->join('documents','historique_ractachement.codedocument = documents.code_document','LEFT');
			$this->db->join('client','historique_ractachement.codeclient = client.matricule_cli','LEFT');
			$this->db->join('caisse','historique_ractachement.codecaisseh = caisse.code_caisse','LEFT');
			$this->db->join('employe','historique_ractachement.inititeurop = employe.matricule_emp','LEFT');
			$this->db->where('historique_ractachement.codeenh',$matricule);
			$this->db->where('historique_ractachement.codedocument',$codedocratach);
			$query = $this->db->get('historique_ractachement');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}
		
	
	
		/**historique de ratachement par document*/
		public function storyratachementpardoc($matricule,$agence,$code_caisse,$start, $limit){
			$this->db->join('client','historique_ractachement.codeclient = client.matricule_cli','LEFT');
			$this->db->join('caisse','historique_ractachement.codecaisseh = caisse.code_caisse','LEFT');
			$this->db->where('historique_ractachement.codeenh',$matricule);
			if(!empty($agence) && !empty($code_caisse)){$this->db->where('historique_ractachement.codecaisseh',$code_caisse);}
			$this->db->limit($start, $limit);
			$query = $this->db->get('historique_ractachement');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**faire la recherche d'un client dans les ratachement */
		public function storyratachementpardocrecherche($matricule,$agence,$caisses,$rechercher){
			$this->db->join('client','historique_ractachement.codeclient = client.matricule_cli','LEFT');
			$this->db->join('caisse','historique_ractachement.codecaisseh = caisse.code_caisse','LEFT');
			$this->db->where('historique_ractachement.codeenh',$matricule);
			if(!empty($agence) && !empty($code_caisse)){$this->db->where('historique_ractachement.codecaisseh',$code_caisse);}
			$this->db->like('client.nom_cli', $rechercher, 'both');
			$query = $this->db->get('historique_ractachement');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**selectionne de manière unique les documents d'un client en fonction de l'entreprise */
		public function distinct_docrc_ratachement_client($matricule,$codeclient){
			$this->db->distinct();
			$this->db->select('historique_ractachement.codedocument');
			$this->db->where('historique_ractachement.codeenh',$matricule);
			$this->db->where('historique_ractachement.codeclient',$codeclient);
			$query = $this->db->get('historique_ractachement');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**compte le nombre de ratachement */
		public function countratachcaisse($matricule,$agence,$code_caisse){
			$this->db->join('client','historique_ractachement.codeclient = client.matricule_cli','LEFT');
			$this->db->join('caisse','historique_ractachement.codecaisseh = caisse.code_caisse','LEFT');
			if(!empty($agence) && !empty($code_caisse)){$this->db->where('historique_ractachement.codecaisseh',$code_caisse);}
			$this->db->where('historique_ractachement.codeenh',$matricule);
			$query = $this->db->get('historique_ractachement');
			if($query->num_rows()>0){
				return $query->num_rows();
			}else{
				return false;
			}
		}

		/**mofidier une entrer en caisse */
		public function update_enter_caisse($input_data,$code_client,$code_caisse){
			$this->db->where('entrer_caisse.client_enter',$code_client);
			$this->db->where('entrer_caisse.caisse_enter',$code_caisse);
			$query = $this->db->update('entrer_caisse',$input_data);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		/**afficher toutes les entrer en caisse */
		public function all_entrer_caisse($matricule_en,$agence,$start,$limit){
			$this->db->join('client','entrer_caisse.client_enter = client.matricule_cli','LEFT');
			$this->db->join('employe','entrer_caisse.employe_enter = employe.matricule_emp','LEFT');
			$this->db->join('agence','entrer_caisse.agence_enter = agence.matricule_ag','LEFT');
			$this->db->join('caisse','entrer_caisse.caisse_enter = caisse.code_caisse','LEFT');
			$this->db->join('entreprise','entrer_caisse.entreprise_enter = entreprise.matricule_en','LEFT');
			$this->db->where('entrer_caisse.entreprise_enter',$matricule_en);
			if(!empty($agence)){$this->db->where('entrer_caisse.agence_enter',$agence);}
			$this->db->limit($start,$limit);
			$query = $this->db->get('entrer_caisse');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**compte le nombre d'entre en caisse */
		public function count_all_entrer_caisse($matricule_en,$agence){
			$this->db->where('entrer_caisse.entreprise_enter',$matricule_en);
			if(!empty($agence)){$this->db->where('entrer_caisse.agence_enter',$agence);}
			$query = $this->db->get('entrer_caisse');
			if($query->num_rows()>0){
				return $query->num_rows();
			}else{
				return false;
			}
		}


		/**affiche les reglements client(dettes) d'un client donné */
		public function reglementcli_cli($abrev,$code_en,$code_client){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->where('documents.code_client_document',$code_client);
			$this->db->where('documents.code_entreprie',$code_en);
			$this->db->order_by('documents.date_creation_doc','DESC');
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}

		}


		/**on selectionne l'entrer en caisse en fonction 
		 * du client, de la caisse, de l'entreprise
		*/
		public function entrer_caisse_cli($matricule_en,$code_client,$caisse){
		    $this->db->where('entrer_caisse.entreprise_enter',$matricule_en);
			$this->db->where('entrer_caisse.client_enter',$code_client);
			$this->db->where('entrer_caisse.caisse_enter',$caisse);
			$query = $this->db->get('entrer_caisse');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		public function entrer_caisse_cli2($matricule_en,$code_client,$caisse){ //$agence,
		    $this->db->where('entrer_caisse.entreprise_enter',$matricule_en);
			$this->db->where('entrer_caisse.client_enter',$code_client);
			$this->db->where('entrer_caisse.caisse_enter',$caisse);
			//$this->db->or_where('entrer_caisse.agence_enter',$agence);
			$query = $this->db->get('entrer_caisse');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**on selectionne une facture dette donner pour un client donné dans une entreprise */
		public function get_doc_cli($matricule_en,$code_client,$code_facture){
			$this->db->where('documents.code_document',$code_facture);
			$this->db->where('documents.code_client_document',$code_client);
			$this->db->where('documents.code_entreprie',$matricule_en);
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}


		/**affiche toutes les dettes d'une entreprise *
		public function all_dette($abrev,$code_en){
			$this->db->select('documents.pt_ttc_document,documents.dette_regler');
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->where('documents.code_entreprie',$code_en);
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}*/

		/**liste des client pour les règlement client  dans une entreprise */
		public function all_clientrc($abrev,$code_en,$date_debut,$date_fin){
			$this->db->distinct();
			$this->db->select('client.matricule_cli,client.nom_cli');
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->where('documents.code_entreprie',$code_en);
			$this->db->where('documents.date_creation_doc BETWEEN"'.$date_debut.'"AND"'.$date_fin.'"');
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**on fait la somme des dettes de chaque client dans la table document pour le type de document reglement client dans une entreprise */
		public function total_dette_clientrc($matricule_cli,$abrev,$code_en){
			$this->db->select_sum('documents.dette_restante');
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			$this->db->where('documents.code_client_document',$matricule_cli);
			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->where('documents.code_entreprie',$code_en);
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**on selectionne toutes les dettes d'un client en particulier 
		 * ici il est question de selectionner tous les document de reglèment client de ce client en question
		*/
		public function dette_clientrc($matricule_cli,$abrev,$code_en,$date_debut,$date_fin){
			$this->db->select('documents.pt_ttc_document,documents.dette_regler,documents.dette_restante,documents.nom_document,documents.code_document,documents.delais_reg_doc,documents.date_creation_doc');
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			//$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			$this->db->where('documents.code_client_document',$matricule_cli);
			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->where('documents.code_entreprie',$code_en);
			$this->db->where('DATE(documents.date_creation_doc) BETWEEN"'.$date_debut.'"AND"'.$date_fin.'"');
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}
		
		/****selectionne le commercial d'un client donné***/
		public function commercial_client($client){
	        $this->db->join('client','client.mat_emp = employe.matricule_emp','LEFT');
	        $this->db->where('client.matricule_cli',$client);
	        $query = $this->db->get('employe');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**on selectionne l'agence d'une caisse */
		public function agence_caisse($code_caisse,$matricule){
			$this->db->join('caisse','caisse.code_agence = agence.matricule_ag','LEFT');
			$this->db->join('entreprise','agence.mat_en = entreprise.matricule_en','LEFT');
			$this->db->where('caisse.code_entreprise',$matricule);
			$this->db->where('caisse.code_caisse',$code_caisse);
			$query = $this->db->get('agence');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}
		
		


		/**liste des document a une date donné *
		public function documentdate($date_debut,$date_fin,$matricule,$agence_caisse){
			$this->db->where('documents.code_entreprie',$matricule);
			$this->db->where('documents.code_agence_doc',$agence_caisse);
			$this->db->where('documents.date_creation_doc BETWEEN"'.$date_debut.'"AND"'.$date_fin.'"');
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}*/

		/**document en fonction du code de document */
		public function documentsget($code_document){
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->where('documents.code_document',$code_document);
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/*public function sorticaissedate($date_debut,$date_fin,$matricule,$agence_caisse){
			$this->db->where('sorti_caisse.entreprise_sorti',$matricule);
			$this->db->where('sorti_caisse.agence_sorti',$agence_caisse);
			$this->db->where('sorti_caisse.creer_le_sorti BETWEEN"'.$date_debut.'"AND"'.$date_fin.'"');
			$query = $this->db->get('sorti_caisse');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}*/
		
		/*on selectionne les sortie de caisse d'une agence d'une entreprise, sur une peériode donné*/
		public function sortiecaisseagenceperiode($code_caisse,$matricule,$agence,$date_debut,$date_fin){
		    $this->db->where('sorti_caisse.caisse_sorti',$code_caisse);
			$this->db->where('sorti_caisse.entreprise_sorti',$matricule);
			$this->db->where('sorti_caisse.agence_sorti',$agence);
		    $this->db->where('sorti_caisse.creer_le_sorti BETWEEN"'.$date_debut.'"AND"'.$date_fin.'"');
		    $query = $this->db->get('sorti_caisse');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}  
		}
		
		/*SELECT `historique_entrer_caisse`.*, `entrer_caisse`.*, `entreprise`.*
        FROM `historique_entrer_caisse` 
        	LEFT JOIN `entrer_caisse` ON `historique_entrer_caisse`.`codeentrercaisse` = `entrer_caisse`.`matricule_enter`  
        	LEFT JOIN `entreprise` ON entrer_caisse.entreprise_enter = `entreprise`.`matricule_en`;*/
	
		
		/**selectionner toutes les entrées en caisse d'une entreprise sur une periode de tous les clients client pour une agence*/
		public function entrercaisseagenceperiode($code_caisse,$matricule,$agence,$date_debut,$date_fin){
		    
		    $this->db->join('entrer_caisse','historique_entrer_caisse.codeentrercaisse = entrer_caisse.matricule_enter','LEFT');
		    $this->db->join('entreprise','entrer_caisse.entreprise_enter = entreprise.matricule_en','LEFT');
		    
		    $this->db->where('entrer_caisse.caisse_enter',$code_caisse);
			$this->db->where('entrer_caisse.entreprise_enter',$matricule);
			$this->db->where('entrer_caisse.agence_enter',$agence);
			
		    $this->db->where('historique_entrer_caisse.dateentrer BETWEEN"'.$date_debut.'"AND"'.$date_fin.'"');
		    $query = $this->db->get('historique_entrer_caisse');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}  
		}
		

		/**on selectionne le document de sortie de caisse en fonction de son code et de son type */
		public function documentssortiget($matricule_sorti,$abrev){
				$this->db->join('type_document','sorti_caisse.type_doc_sorti = type_document.code_doc','LEFT');
				$this->db->where('type_document.abrev_doc',$abrev);
				$this->db->where('sorti_caisse.matricule_sorti',$matricule_sorti);
				$query = $this->db->get('sorti_caisse');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

	
	
		/**selectionnons tous les documents de vente dans la table document pour une caisse, un type de document dans une agence  pour le sitiation a une période donné*/
		public function docventecaisseagence2($code_caisse,$matricule_en,$agence,$abrev){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			$this->db->join('agence','documents.code_agence_doc = agence.matricule_ag','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			
			$this->db->where('documents.code_caisse',$code_caisse);
			$this->db->where('documents.code_entreprie',$matricule_en);
			$this->db->where('documents.code_agence_doc',$agence);

            $this->db->where_in('type_document.abrev_doc', $abrev);
            
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}
		/**montant total dans la caisse d'une agence donné pour une caisse donné pour une période*/
		public function docventecaisseagenceperiode($code_caisse,$matricule_en,$agence,$abrev,$date_debut,$date_fin){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			$this->db->join('agence','documents.code_agence_doc = agence.matricule_ag','LEFT');
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			
			$this->db->where('documents.code_caisse',$code_caisse);
			$this->db->where('documents.code_entreprie',$matricule_en);
			$this->db->where('documents.code_agence_doc',$agence);

            $this->db->where_in('type_document.abrev_doc', $abrev);
            
            $this->db->where('documents.date_creation_doc BETWEEN"'.$date_debut.'"AND"'.$date_fin.'"');
            
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}
		/*public function totalcaisseagence2($matricule,$agence_caisse,$abrev){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->where('documents.code_agence_doc',$agence_caisse);
			$this->db->where('documents.code_entreprie',$matricule);
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}*/

		/**article d'un documents */
		public function articledocument($code_doc,$code_en){
			$this->db->join('article','article_document.code_article = article.matricule_art','LEFT');
			$this->db->where('article_document.code_document',$code_doc);
			$this->db->where('article_document.code_en_art_doc',$code_en);
			$query = $this->db->get('article_document');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}


		
			/**selectionnons tous les documents de vente dans la table document pour une caisse, un type de document dans une agence
                        pour avoir le total en caisse avant la période de la situaltion
                        */
		public function docventecaisseagence1($code_caisse,$matricule_en,$agence,$abrev,$date){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			$this->db->join('agence','documents.code_agence_doc = agence.matricule_ag','LEFT');
			$this->db->join('entreprise','documents.code_entreprie = entreprise.matricule_en','LEFT');
			
			$this->db->where('documents.code_caisse',$code_caisse);
			$this->db->where('documents.code_entreprie',$matricule_en);
			$this->db->where('documents.code_agence_doc',$agence);
			$this->db->where('documents.date_creation_doc <',$date);

            		$this->db->where_in('type_document.abrev_doc', $abrev);
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}
		
		
		/**on selectionne l'entrer en caisse particulier en fonction de son code pour afficher et le modifier plus tard*/
		public function single_entrer_caisse($codeentrercaisse){
		    $this->db->where('historique_entrer_caisse.id',$codeentrercaisse);
		    $query = $this->db->get('historique_entrer_caisse');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}
			

		/**========================== NOUVELLE REQUETTE DEBUT ========================== */

		/**selectionne les documents de vente pour faire le total des dettes */
		public function docventecaisseagence($matricule_en){
            $this->db->where('documents.code_entreprie',$matricule_en);
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**selectionne la liste des clients */
		public function all_client($matricule_en){
			$this->db->where('client.mat_en',$matricule_en);
		    $query = $this->db->get('client');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

			
		

		/**selectionne un client en particulier */
		public function single_client($client){
			$this->db->where('client.matricule_cli',$client);
		    $query = $this->db->get('client');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**liste des dettes en fonction du client */
		public function dette_per_client($client,$matricule_en,$date_debut,$date_fin){
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->where('documents.code_client_document',$client);
			$this->db->where('documents.code_entreprie',$matricule_en);
			$this->db->where('DATE(documents.date_creation_doc) BETWEEN"'.$date_debut.'"AND"'.$date_fin.'"');
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}


		/**on compte tous les documents en fonction du type de documents de l'entreprise et ou de l'agence */
		public function countdocrtagence($matricule_en,$agencert,$abrev){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			if(!empty($agencert)){$this->db->where('documents.code_agence_doc',$agencert);}
			$this->db->where('documents.code_entreprie',$matricule_en);
			$this->db->where('type_document.abrev_doc',$abrev);
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->num_rows();
			}else{
				return false;
			}
		}

		/**on selectionne tous les documents en fonction du type, de l'entreprise et ou de l'agence */
		public function documentrtvente($abrev,$matricule_en,$agencert,$limit,$start){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('agence','documents.code_agence_doc = agence.matricule_ag','LEFT');
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			if(!empty($agencert)){$this->db->where('documents.code_agence_doc',$agencert);}
			$this->db->where('documents.code_entreprie',$matricule_en);
			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->limit($limit,$start);

			$this->db->order_by('documents.date_creation_doc','DESC');

			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**on cherche un document en fonction du type de document, de l'entreprise et ou de l'agence pour un client ou son code */
		public function documentrtventerecherche($abrev,$matricule_en,$agencert,$recherche){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('agence','documents.code_agence_doc = agence.matricule_ag','LEFT');
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			if(!empty($agencert)){$this->db->where('documents.code_agence_doc',$agencert);}
			$this->db->where('documents.code_entreprie',$matricule_en);
			$this->db->where('type_document.abrev_doc',$abrev);

			$this->db->like('client.nom_cli',$recherche,'both');
			//$this->db->or_like('client.matricule_cli',$recherche,'both');

			$this->db->order_by('documents.date_creation_doc','DESC');

			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**on selectionne les articles d'un document de vente en fonction de son code et celui de l'entreprise
		 * au sorti, on a, le client,l'employé, les articles, les couts
		*/
		public function artdocumentvente($matricule_en,$code_document){
			$this->db->join('documents','article_document.code_document = documents.code_document','LEFT');
			$this->db->join('article','article_document.code_article = article.matricule_art','LEFT');
			$this->db->where('article_document.code_en_art_doc',$matricule_en);
			$this->db->where('article_document.code_document',$code_document);
			$query = $this->db->get('article_document');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/** on selectionne le document de vente en fonction de son code et celui de l'entreprise
		 * au sorti, on aura, le client, les code du document, le commercial du client
		*/
		
		public function docventeclientcommercial($codedoc,$codeen){
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('employe','client.mat_emp = employe.matricule_emp','LEFT');
			$this->db->where('documents.code_document',$codedoc);
			$this->db->where('documents.code_entreprie',$codeen);
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**selectionne la liste des depot en fonction de l'entreprise et de ou de l'agence */
		public function getalldepot($matricule,$agence){
			$this->db->where('depot.code_en_d',$matricule);
			if(!empty($agence)){$this->db->where('depot.code_ag_d',$agence);}
			$query = $this->db->get('depot');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**liste des caisse en fonction de l'entreprise et ou de l'agence */
		public function allcaisseagence($matricule,$agence){
			$this->db->join('employe','caisse.gerant_emp = employe.matricule_emp','LEFT');
			$this->db->where('caisse.code_entreprise',$matricule);
			if(!empty($agence)){$this->db->where('caisse.code_agence',$agence);}
			$query = $this->db->get('caisse');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}


		/**liste des documents d'une entreprise et ou d'une agence avec le client qui vas avec en fonction du type et de l'état*/
		public function docu_cli_en_ag($matricule,$agence,$abrev){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->where('documents.code_entreprie',$matricule);
			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->where('documents.cloturer',0);
			if(!empty($agence)){$this->db->where('documents.code_agence_doc',$agence);}
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**cloturer un document de vente en fonction du code de l'entreprise et celui du document */
		public function cloturerdocu($matricule,$document,$input){
			$this->db->where('documents.code_entreprie',$matricule);
			$this->db->where('documents.code_document',$document);
			$query = $this->db->update('documents',$input);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		/**on test pour voir si le document existe dans la liste des documents retourné */
		public function documentbrtest($abrev,$matricule_en,$docname){
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->where('documents.code_entreprie',$matricule_en);
			$this->db->where('type_document.abrev_doc',$abrev);
			$this->db->where('documents.nom_document',$docname);

			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**on selectionne un article en particulier dans un document en fonction de son code, celui du document et celui de l'entreprise */
		public function art_document_single($matricule,$codearticle,$code_document){
			$this->db->where('article_document.code_en_art_doc',$matricule);
			$this->db->where('article_document.code_article',$codearticle);
			$this->db->where('article_document.code_document',$code_document);
			$query = $this->db->get('article_document');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**on modifier en article dans la table article_document en fonction 
		 * de son code, celui de l'entreprise, celui du document, element
		*/
		public function update_article_document($codearticle,$code_document,$matricule,$input_art_doc){
			$this->db->where('article_document.code_en_art_doc',$matricule);
			$this->db->where('article_document.code_article',$codearticle);
			$this->db->where('article_document.code_document',$code_document);
			$query = $this->db->update('article_document',$input_art_doc);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		/**affiche la liste des agences en fonction de l'entreprise et ou de l'agence */
		public function all_agences($code_en,$agence){
			$this->db->join('entreprise','agence.mat_en = entreprise.matricule_en','LEFT');
			$this->db->where('agence.mat_en',$code_en);
			if(!empty($agence)){$this->db->where('agence.matricule_ag',$agence);}
			$query = $this->db->get('agence');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**recherche une entrer en caisse en fonction du client, de l'entreprise et ou de l'agence */
		public function recherche_entrer_caisse($matricule_en,$agence,$recherche){
			$this->db->join('client','entrer_caisse.client_enter = client.matricule_cli','LEFT');
			$this->db->join('employe','entrer_caisse.employe_enter = employe.matricule_emp','LEFT');
			$this->db->join('agence','entrer_caisse.agence_enter = agence.matricule_ag','LEFT');
			$this->db->join('caisse','entrer_caisse.caisse_enter = caisse.code_caisse','LEFT');
			$this->db->join('entreprise','entrer_caisse.entreprise_enter = entreprise.matricule_en','LEFT');
			$this->db->where('entrer_caisse.entreprise_enter',$matricule_en);
			if(!empty($agence)){$this->db->where('entrer_caisse.agence_enter',$agence);}

			$this->db->like('client.nom_cli', $recherche, 'both');
			
			$query = $this->db->get('entrer_caisse');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}

		/**selectione une historique d'entrer en caisse en particulier */
		public function single_historique_entrercaisse($codehist){
			$this->db->where('historique_entrer_caisse.id',$codehist);
			$query = $this->db->get('historique_entrer_caisse');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**selectionne une entrer en caisse en fonction de l'entreprise et de son code */
		public function single__entrer_caisse($codeentrercaisse,$matricule){
			$this->db->where('entrer_caisse.matricule_enter',$codeentrercaisse);
			$this->db->where('entrer_caisse.entreprise_enter',$matricule);
			$query = $this->db->get('entrer_caisse');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**mettre a jour une entre en caisse */
		public function updatespecific__entrer_caisse($matricule_enter,$inputupdate){
			$this->db->where('entrer_caisse.matricule_enter',$matricule_enter);
			$query = $this->db->update('entrer_caisse',$inputupdate);
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		/**supprimer une historique d'entrer en caisse */
		public function deletespecific_hist_entrer_caisse($id){
			$this->db->where('historique_entrer_caisse.id',$id);
			$query = $this->db->delete('historique_entrer_caisse');
			if($query){
				return $query;
			}else{
				return false;
			}
		}


		/**on selectionne un historique de document en particulier 
		 * en fonction de son code et celui de l'entreprise
		*/
		public function single_hist_ratachement($coderatach,$matricule){
			$this->db->where('historique_ractachement.idratache',$coderatach);
			$this->db->where('historique_ractachement.codeenh',$matricule);
			$query = $this->db->get('historique_ractachement');
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;
			}
		}

		/**on supprime un ratachement en particulier */
		public function delete_specific_historatach($idratache,$matricule){
			$this->db->where('historique_ractachement.idratache',$idratache);
			$this->db->where('historique_ractachement.codeenh',$matricule);
			$query = $this->db->delete('historique_ractachement');
			if($query){
				return $query;
			}else{
				return false;
			}
		}

		/**liste de manière unique les clients en fonction
		 * caisse, le type de document, la periode 
		 * dans la table document
		*/
		public function getcli_typedoc_caisse_periode($matricule,$code_caisse,$abrev,$debut,$fin){
			$this->db->distinct();
			$this->db->select('documents.code_client_document');
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->join('type_document','documents.code_type_doc = type_document.code_doc','LEFT');
			$this->db->join('caisse','documents.code_caisse = caisse.code_caisse','LEFT');
			
			$this->db->where('documents.code_entreprie',$matricule);
			$this->db->where('documents.code_caisse',$code_caisse);
			$this->db->where('type_document.abrev_doc',$abrev);

			$this->db->where('documents.date_creation_doc BETWEEN"'.$debut.'"AND"'.$fin.'"');
			
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}
		

		/*public function dette_clientrc($matricule_en,$date_debut,$date_fin){
			$this->db->join('client','documents.code_client_document = client.matricule_cli','LEFT');
			$this->db->where('documents.code_entreprie',$code_en);
			$this->db->where('DATE(documents.date_creation_doc) BETWEEN"'.$date_debut.'"AND"'.$date_fin.'"');
			$query = $this->db->get('documents');
			if($query->num_rows()>0){
				return $query->result_array();
			}else{
				return false;
			}
		}*/


		/**========================== NOUVELLE REQUETTE FIN ========================== */


    }
