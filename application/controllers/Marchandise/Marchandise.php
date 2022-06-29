<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marchandise extends CI_Controller {

	/**constructeur*/
	public function __construct(){
        parent::__construct();
		$this->load->model('Marchandise/Marchandise_model','marchandise');
        $this->load->model('Stock/Stock_model','stock');
    }

    /**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}

    public function index(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');


        /**matricule de l'entreprise */
		$matricule_en = session('users')['matricule'];

        /**afficher la liste des famille d'article */
        $data['famille'] = $this->stock->all_famille($matricule_en);
		
		
		$this->load->view('marchandise/marchandise',$data);

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }
    
    

    /**affiche la liste de tous les articles */
    public function all_article(){
        $this->logged_in();
        $output = '';
		$matricule_en = session('users')['matricule'];
		

		/**************pagination debut************************* */
			$config = array();
			$config["base_url"] = "#";
			$config["total_rows"] = $this->marchandise->count_all_articles($matricule_en);
			$config["per_page"] = 50;
			$config["uri_segment"] = 2;
			$config["use_page_numbers"] = TRUE;
			$config["full_tag_open"] = '<ul class="pagination">';
			$config["full_tag_close"] = '</ul>';
			$config["first_tag_open"] = '<li class="page-item page-link">';
			$config["first_tag_close"] = '</li>';
			$config["last_tag_open"] = '<li class="page-item page-link">';
			$config["last_tag_close"] = '</li>';
			$config['next_link'] = '&raquo;';
			$config["next_tag_open"] = '<li class="page-link">';
			$config["next_tag_close"] = '</li>';
			$config["prev_link"] = "&laquo;";
			$config["prev_tag_open"] = '<li class="page-link">';
			$config["prev_tag_close"] = "</li>";
			$config["cur_tag_open"] = "<a href='#'><li class='page-item active'><span class='page-link'><span class='sr-only'>(current)</span>";
			$config["cur_tag_close"] = "</span></li></a>";
			$config["num_tag_open"] = '<li class="page-item page-link">';
			$config["num_tag_close"] = "</li>";
			$config["num_links"] = 1;
			$this->pagination->initialize($config);

			$page = (($this->uri->segment(2) - 1) * $config["per_page"]);

		/**************pagination fin************************* */
		$articles = $this->marchandise->get_all_articles($matricule_en, $config["per_page"], $page);
		//$articles = $this->marchandise->get_all_articles($matricule_en);
		if(!empty($articles)){
			foreach ($articles as $key => $value){
				$output .= '
					<tr>
						<th scope="row">'.$value['matricule_art'].'</th>
						<td>'.$value['code_bar'].'</td>
						<td>'.$value['designation'].'</td>
						<td>'.$value['quantite'].'</td>
						<td>'.$value['nom_depot'].'</td>
						<td>
							<div class="kt-widget__toolbar">
								<button class="btn btn-icon btn-circle btn-label-facebook view" id="'.$value['matricule_art'].'">
									<i class="fa fa-eye"></i>
								</button>
							</div> 
						</td>
					</tr>
				';
				$outputs = array(
					'values' => $output,
					'pagination' => $this->pagination->create_links()
				);
			}
		}else{
			$outputs = array(
				'values' => 'aucun article trouvé',
			);		
		}
        echo json_encode($outputs);
    }

	/*faire un recherche sur les marchandises */
	public function recherche_article(){
        $this->logged_in();
        $output = '';
		$matricule_en = session('users')['matricule'];
		$famille = $this->input->post('famille');
		$recherche = trim($this->input->post('recherche'));
		

		/**************pagination debut************************* */
			$config = array();
			$config["base_url"] = "#";
			$config["total_rows"] = $this->marchandise->count_recherche_all_articles($matricule_en,$recherche);
			$config["per_page"] = 50;
			$config["uri_segment"] = 2;
			$config["use_page_numbers"] = TRUE;
			$config["full_tag_open"] = '<ul class="pagination paginat">';
			$config["full_tag_close"] = '</ul>';
			$config["first_tag_open"] = '<li class="page-item page-link">';
			$config["first_tag_close"] = '</li>';
			$config["last_tag_open"] = '<li class="page-item page-link">';
			$config["last_tag_close"] = '</li>';
			$config['next_link'] = '&raquo;';
			$config["next_tag_open"] = '<li class="page-link">';
			$config["next_tag_close"] = '</li>';
			$config["prev_link"] = "&laquo;";
			$config["prev_tag_open"] = '<li class="page-link">';
			$config["prev_tag_close"] = "</li>";
			$config["cur_tag_open"] = "<a href='#'><li class='page-item active'><span class='page-link'><span class='sr-only'>(current)</span>";
			$config["cur_tag_close"] = "</span></li></a>";
			$config["num_tag_open"] = '<li class="page-item page-link">';
			$config["num_tag_close"] = "</li>";
			$config["num_links"] = 1;
			$this->pagination->initialize($config);

			$page = (($this->uri->segment(2) - 1) * $config["per_page"]);

		/**************pagination fin************************* */
		
		if(!empty($recherche) && empty($famille)){
			/**recherche l'article en fonction du mot tapé uniquement */
			$articles = $this->marchandise->recherche_all_articles($matricule_en, $recherche, $config["per_page"], $page);
			if(!empty($articles)){
				foreach ($articles as $key => $value){
					$output .= '
						<tr>
							<th scope="row">'.$value['matricule_art'].'</th>
							<td>'.$value['code_bar'].'</td>
							<td>'.$value['designation'].'</td>
							<td>'.$value['quantite'].'</td>
							<td>'.$value['nom_depot'].'</td>
							<td>
								<div class="kt-widget__toolbar">
									<button class="btn btn-icon btn-circle btn-label-facebook view" id="'.$value['matricule_art'].'">
										<i class="fa fa-eye"></i>
									</button>
								</div> 
							</td>
						</tr>
					';
					$outputs = array(
						'values' => $output,
						'pagination' => $this->pagination->create_links()
					);
				}
			}else{
				$outputs = array(
					'values' => 'aucun article trouvé',
				);		
			}
		}

		if(!empty($recherche) && !empty($famille)){

			/**recherche l'article en fonction du mot tapé et la famille *
			$article = $this->marchandise->recherche_all_articles2($matricule_en,$recherche,$famille);
			if(!empty($article)){
				$outputs = array(
					'values' => $recherche.'   '.$famille,
				);
			}else{
				$outputs = array(
					'values' => 'aucun article trouvé',
				);	
			}*/
			
			/**recherche l'article en fonction du mot tapé et la famille */
			$article = $this->marchandise->recherche_all_articles2($matricule_en,$recherche,$famille); 
			if(!empty($article)){
				
				foreach ($article as $key => $values){
					$output .= '
						<tr>
							<th scope="row">'.$values['matricule_art'].'</th>
							<td>'.$values['code_bar'].'</td>
							<td>'.$values['designation'].'</td>
							<td>'.$values['quantite'].'</td>
							<td>'.$values['nom_depot'].'</td>
							<td>
								<div class="kt-widget__toolbar">
									<button class="btn btn-icon btn-circle btn-label-facebook view" id="'.$values['matricule_art'].'">
										<i class="fa fa-eye"></i>
									</button>
								</div> 
							</td>
						</tr>
					';
					$outputs = array(
						'values' => $output,
					);
				}
			}else{
				$outputs = array(
					'values' => 'aucun article trouvé',
				);		
			}
		}

        echo json_encode($outputs);
    }
    

    /**affiche les détails d'une marhandise */
	public function details_marchandise(){
		$this->logged_in();
		$output = '';
		$code_article = $this->input->post('article_mat');
		$articles_details = $this->stock->get_details_articles($code_article);
		if(!empty($articles_details)){

			/**matricule de l'entreprise */
			$matricule_en = session('users')['matricule'];

			/**quantité total de l'article pour tous les depot */
			$qte_total = $this->marchandise->qte_art_stock_en($matricule_en, $code_article);

			/**calcule la quantité a terme */
			$qte_a_terme = (($articles_details['critique'] * 20)/100);
			
			$prixht = !empty($articles_details['prix_hors_taxe'])?number_format($articles_details['prix_hors_taxe'], 2, ',', ' '):'';
            $prixttc = !empty($articles_details['prixttc'])?number_format($articles_details['prixttc'], 2, ',', ' '):'';
            $prixgros = !empty($articles_details['prix_gros'])?number_format($articles_details['prix_gros'], 2, ',', ' '):'';
            $pprixgrottc = !empty($articles_details['prix_gros_ttc'])?number_format($articles_details['prix_gros_ttc'], 2, ',', ' '):'';
			$output .= '
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Code de l\'article
						<span class="badge">'.$articles_details['matricule_art'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Code à bar
						<span class="badge">'.$articles_details['code_bar'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center bg-primary text-white">
						<span class="text-white">Désignation</span> 
						<span class="badge badge badge-primary">'.$articles_details['designation'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Référence
						<span class="badge">'.$articles_details['reference'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center bg-success font-weight-bold display-4">
						<span class="text-white">Prix HT</span>
						<span class="badge badge badge-success font-weight-bold display-4">'.$prixht.'</span>
					</li> 
					<li class="list-group-item d-flex justify-content-between align-items-center bg-success font-weight-bold display-4">
						<span class="text-white">Prix TTC</span>
						<span class="badge badge badge-success font-weight-bold display-4">'.$prixttc.'</span>
					</li> 
					<li class="list-group-item d-flex justify-content-between align-items-center bg-warning">
						<span class="text-white">Prix de gros HT</span>
						<span class="badge badge badge-warning">'.$prixgros.'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center bg-warning">
						<span class="text-white">Prix de gros TTC</span>
						<span class="badge badge badge-warning">'.$pprixgrottc.'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center bg-danger">
						<span class="text-white">Critique</span>
						<span class="badge badge badge-danger">'.$articles_details['critique'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Date peremption
						<span class="badge badge badge-danger">'.$articles_details['date_peremption'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						delais arlert peremption
						<span class="badge badge badge-danger">'.$articles_details['delais_alert_peremption'].' jour(s)</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Date de création
						<span class="badge">'.$articles_details['creer_article_le'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Dernière modification
						<span class="badge">'.$articles_details['modifier_article_le'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Fournisseur
						<span class="badge">'.$articles_details['nom_four'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Famille
						<span class="badge badge badge-info">'.$articles_details['nom_fam'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Article enrégistrer par
						<span class="badge">'.$articles_details['nom_emp'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						<span class="badge"><h4>Quantité total: '.$qte_total['quantite'].'</h4></span>
						<span class="badge"><h4>Quantité à terme: '.($articles_details['critique'] - $qte_a_terme).'</h4></span>
						<span class="badge"><h4>Critique: '.$articles_details['critique'].'</h4></span>
					</li>
				</ul>
			';

			$outputs = array(
				'details' => $output
			);
		}	
		echo json_encode($outputs);
	}

	/**gestion des alerts de stocks(critique, peremption) */
	public function alert_stock(){
		$this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

        /**matricule de l'entreprise */
		$matricule_en = session('users')['matricule'];

        /***********************************===========================IMPRIMER LES ALERT PAR FAMILLE DEBUT=========================*****************************/
        $btn = $this->input->post('printperfam');
        if(isset($btn)){
           
           /***validation du formulaire*/
            $this->form_validation->set_rules(
                'perfam', 'famille',
                'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
                array(
                    'required'  => 'Champ obligatoire.',
                    'regex_match' => 'Caractère non autorisé.'
                )
            );
            
            if($this->form_validation->run()){
                $famille = $this->input->post('perfam');
        		
        		/****imprimer la critique en fonction de la famille*/
        		///$articles = $this->marchandise->get_all_articlesfilter($matricule_en,$famille);
        		//$articles = $this->marchandise->article($matricule_en);
        		$articles = $this->stock->tri_article($matricule_en,$famille);
        		if(!empty($articles)){
        		    
        		    $mpdf = new \Mpdf\Mpdf();

    				$mpdf->SetHTMLHeader('
    				    <table width="100%">
    						<tr>
    							<td width="33%"><h6>'.strtoupper(session('users')['nom']).'</h6></td>
    							<td colspan="2"><h6><center>'.strtoupper('Article dont la quantité en stock est inférieur à la critique').'</center></h4></td>
    						</tr>
    					</table>
    				');
    
    				$mpdf->SetHTMLFooter('
    					<table width="100%">
    						<tr>
    							<td width="33%">{DATE j-m-Y H:m:s}</td>
    							<td width="33%" align="center">{PAGENO}/{nbpg}</td>
    							<td width="33%" style="text-align: right;">Document de critique de stock</td>
    						</tr>
    					</table>
    				');
    
    				// Write some HTML code:
    					$mpdf->WriteHTML('
    					<br>
    					<style>
                            table {
                              font-family: arial, sans-serif;
                              border-collapse: collapse;
                              width: 100%;
                            }
                            
                            td, th {
                              border: 1px solid #dddddd;
                              text-align: left;
                              padding: 8px;
                            }
                            
                            tr:nth-child(even) {
                              background-color: #dddddd;
                            }
                        </style>
    					<table>
    						<tr>
    							<th>Code à bar</th>
    							<th>Désignation</th>
    							<th>Référence</th>
    							<th>Qté total en stock</th>
    							<th>Critique</th>
    							<th>Qantité manquante</th>
    							<th>Famille</th>
    						</tr>
    						');
        		    
                    		    foreach($articles as $value){
                    		        
                    		        /**total des quantité en stock d'un article donné dans tous les dépots réuni */
					               $qte_total = $this->marchandise->qte_art_stock_en($matricule_en, $value['matricule_art']);
					                    
                        		    /**détermine la critique */
                					if($qte_total['quantite'] < $value['critique']){
                					    
					
                					    /****affiche le nom de la famille*/
                					    $familleart =  $this->marchandise->famille_art($matricule_en,$value['designation']);
                					    
                					    $mpdf->WriteHTML('
                    						<tr>
                    							<td>'.$value['code_bar'].'</td>
                    							<td>'.$value['designation'].'</td>
                    							<td>'.$value['reference'].'</td>
                    							<td>'.$qte_total['quantite'].'</td>
                    							<td>'.$value['critique'].'</td>
                    							<td>'.($value['critique'] - $qte_total['quantite']).'</td>
                    							<td>'.$familleart['nom_fam'].'</td>
                    						</tr>
                    					'); 
                					}
                    		    }
        		    
        				$mpdf->WriteHTML('</table>');
        
        				// Output a PDF file directly to the browser
        				//$mpdf->Output();
        				$mpdf->Output('document_critique.pdf', \Mpdf\Output\Destination::INLINE);
        		    
        		    
        		}else{
        		    flash('info','le stock semble stablme popur le moment dans cette famille');
        		    redirect('alstock');
        		}
            }
        }
        
        /***********************************===========================IMPRIMER LES ALERT PAR FAMILLE DEBUT=========================*****************************/
        
        
		$data['articles'] = $this->marchandise->article($matricule_en);
		foreach($data['articles'] as $key => $value){

			/**verifi si l'article est perimé et est envoi de peremption, le nombre de jours et la quantité restant en stock debut */

			/**total des quantité en stock d'un article donné dans tous les dépots réuni */
			$qte_total = $this->marchandise->qte_art_stock_en($matricule_en, $value['matricule_art']);
			if(!empty($qte_total['quantite'])){

				/**calcul du nombre de jour entre deux dates */
				$testdatedb = $value['date_peremption']!=0000-00-00? $value['date_peremption']: "";
				
				$datebd = date("Y-m-d", strtotime($testdatedb));
				$dateactu = date("Y-m-d", strtotime(date('Y-m-d')));
                
				$earlier = new DateTime($datebd);
				$later = new DateTime($dateactu);
				$diff = $earlier->diff($later)->format("%a");

				/**determine si un article est périmé et dépuis combien de temps */
			if($datebd <= $dateactu && !empty($testdatedb)){
					if($diff == 0){
						$perime[] = array(
							'designation' => $value['designation'],
							'qtetotal' => $qte_total['quantite'],
							'date_premption' => $value['date_peremption'],
							'jours' => $diff,
						);
					}else{
						$perime[] = array(
							'designation' => $value['designation'],
							'qtetotal' => $qte_total['quantite'],
							'date_premption' => $value['date_peremption'],
							'jours' => $diff,
						);
					}
				}

				/**determine si un article vas périmé et dans combien de temp */
				if($datebd > $dateactu){
					if($diff <= $value['delais_alert_peremption']){
						$peremption[] = array(
							'designation' => $value['designation'],
							'qtetotal' => $qte_total['quantite'],
							'date_premption' => $value['date_peremption'],
							'jours' => $diff
						);
					}
				}

				/**détermine la critique */
				if($qte_total['quantite'] < $value['critique']){
				    
				    $familleart =  $this->marchandise->famille_art($matricule_en,$value['designation']);
				    
					$critique[] = array(
						'codebar' => $value['code_bar'],
						'designation' => $value['designation'],
						'reference' => $value['reference'],
						'qtetotal' => $qte_total['quantite'],
						'critique' => $value['critique'],
						'manque' => ($value['critique'] - $qte_total['quantite']),
						'famille' => $familleart['nom_fam']
					);
				}	
				
			}

			/**verifi si l'article est perimé et est envoi de peremption, le nombre de jours et la quantité restant en stock fin */
		}
		
		
			if(!empty($critique)){
				$data['critique'] = $critique;
			}
		
			if(!empty($perime)){
				$data['perime'] = $perime;
			}

			if(!empty($peremption)){
				$data['peremption'] = $peremption;
			}
		
		
		/**afficher la liste des famille d'article */
        $data['famille'] = $this->stock->all_famille($matricule_en);

		$this->load->view('marchandise/alertstock',$data);

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}

	/**imprimer les allerts de stock*/
	public function print_alert_stock(){
		$this->logged_in();

		/**matricule de l'entreprise */
		$matricule_en = session('users')['matricule'];

		$element = $this->uri->segment(2);
		if(!empty($element)){
			/**génère le pdf de la critique */
			if($element == 'critique'){

				$data['articles'] = $this->marchandise->article($matricule_en);
				foreach($data['articles'] as $key => $value){
					//debug($value['designation']);
					//debug($value['matricule_art']);

					/**total des quantité en stock d'un article donné dans tous les dépots réuni */
					$qte_total = $this->marchandise->qte_art_stock_en($matricule_en, $value['matricule_art']);

					//debug($qte_total['quantite']);

					if(!empty($qte_total['quantite'])){
						/**détermine la critique */
						if($qte_total['quantite'] < $value['critique']){
						    
						    $familleart =  $this->marchandise->famille_art($matricule_en,$value['designation']);
						    
							$critique[] = array(
								'codebar' => $value['code_bar'],
								'designation' => $value['designation'],
								'reference' => $value['reference'],
								'qtetotal' => $qte_total['quantite'],
								'critique' => $value['critique'],
								'manque' => ($value['critique'] - $qte_total['quantite']),
								'famille' => $familleart['nom_fam'],
								'matfamille' => $familleart['matricule_fam']
							);
						}
					}
				}
				$data['critique'] = $critique;
                sort($data['critique']);
				$mpdf = new \Mpdf\Mpdf();

				$mpdf->SetHTMLHeader('
				    <table width="100%">
						<tr>
							<td width="33%"><h6>'.strtoupper(session('users')['nom']).'</h6></td>
							<td colspan="2"><h6><center>'.strtoupper('Article dont la quantité en stock est inférieur à la critique').'</center></h4></td>
						</tr>
					</table>
				');

				$mpdf->SetHTMLFooter('
					<table width="100%">
						<tr>
							<td width="33%">{DATE j-m-Y H:m:s}</td>
							<td width="33%" align="center">{PAGENO}/{nbpg}</td>
							<td width="33%" style="text-align: right;">Document de critique de stock</td>
						</tr>
					</table>
				');

				// Write some HTML code:
					$mpdf->WriteHTML('
					<br>
					
					<style type="text/css">
						table{
							width:100%; 
							border-collapse: collapse;
						}
						td, th{
							border: 1px solid black;
						}
					</style>
					<table>
						<tr>
							<th>Code à bar</th>
							<th>Désignation</th>
							<th>Référence</th>
							<th>Qté total en stock</th>
							<th>Critique</th>
							<th>Qantité manquante</th>
							<th>Famille</th>
						</tr>
						');
                        
					foreach ($data['critique'] as $key => $value){
    				     $mpdf->WriteHTML('
    						<tr>
    							<td>'.$value['codebar'].'</td>
    							<td>'.$value['designation'].'</td>
    							<td>'.$value['reference'].'</td>
    							<td>'.$value['qtetotal'].'</td>
    							<td>'.$value['critique'].'</td>
    							<td>'.$value['manque'].'</td>
    							<td>'.$value['famille'].'</td>
    						</tr>
    					'); 
					}
				$mpdf->WriteHTML('</table>');

				// Output a PDF file directly to the browser
				//$mpdf->Output();
				$mpdf->Output('document_critique.pdf', \Mpdf\Output\Destination::INLINE);
			}
			
			/**génère le pdf des article envoi de permption */
			if($element == 'premption'){

				$data['articles'] = $this->marchandise->article($matricule_en);
				foreach($data['articles'] as $key => $value){
					//debug($value['designation']);
					//debug($value['matricule_art']);

					/**total des quantité en stock d'un article donné dans tous les dépots réuni */
					$qte_total = $this->marchandise->qte_art_stock_en($matricule_en, $value['matricule_art']);

					//debug($qte_total['quantite']);

					if(!empty($qte_total['quantite'])){
						$datebd = date("Y-m-d", strtotime($value['date_peremption']));
						$dateactu = date("Y-m-d", strtotime(date('Y-m-d')));

						$earlier = new DateTime($datebd);
						$later = new DateTime($dateactu);
						$diff = $earlier->diff($later)->format("%a");

						/**determine si un article vas périmé et dans combien de temp */
						if($datebd > $dateactu){
							if($diff <= $value['delais_alert_peremption']){
								$peremption[] = array(
									'codebar' => $value['code_bar'],
									'designation' => $value['designation'],
									'reference' => $value['reference'],
									'qtetotal' => $qte_total['quantite'],
									'date_premption' => $value['date_peremption'],
									'jours' => $diff,
								);
							}
						}
					}
				}
				$data['peremption'] = $peremption;


				$mpdf = new \Mpdf\Mpdf();

				$mpdf->SetHTMLHeader('
					<center style="text-align: right; font-weight: bold;">
						<h2>PREMICE COMPUTER GROUPE</h2><br><br><br>
					</center>
				');

				$mpdf->SetHTMLFooter('
					<table width="100%">
						<tr>
							<td width="33%">{DATE j-m-Y H:m:s}</td>
							<td width="33%" align="center">{PAGENO}/{nbpg}</td>
							<td width="33%" style="text-align: right;">Document de critique de stock</td>
						</tr>
					</table>
				');

				// Write some HTML code:
					$mpdf->WriteHTML('
					<h4><u>'.strtoupper('Article dont la date de peremption est proche').'</u></h4>
					<style type="text/css">
						table{
							width:100%; 
							border-collapse: collapse;
						}
						td, th{
							border: 1px solid black;
						}
					</style>
					<table>
						<tr>
							<th>Code à bar</th>
							<th>Désignation</th>
							<th>Référence</th>
							<th>Qté total en stock</th>
							<th>Nombre de jour(s) restant</th>
						</tr>');
					
						foreach ($data['peremption'] as $key => $value) {
							$mpdf->WriteHTML('
								<tr>
									<td>'.$value['codebar'].'</td>
									<td>'.$value['designation'].'</td>
									<td>'.$value['reference'].'</td>
									<td>'.$value['qtetotal'].'</td>
									<td>'.$value['jours'].'</td>
								</tr>
							');
						}

				$mpdf->WriteHTML('</table>');

				// Output a PDF file directly to the browser
				//$mpdf->Output();
				$mpdf->Output('articles_encour_de_peremption.pdf', \Mpdf\Output\Destination::INLINE);
			}

			/**génère le pdf des articles périmé */
			if($element == 'perime'){

				$data['articles'] = $this->marchandise->article($matricule_en);
				foreach($data['articles'] as $key => $value){
					//debug($value['designation']);
					//debug($value['matricule_art']);

					/**total des quantité en stock d'un article donné dans tous les dépots réuni */
					$qte_total = $this->marchandise->qte_art_stock_en($matricule_en, $value['matricule_art']);

					//debug($qte_total['quantite']);

					if(!empty($qte_total['quantite'])){
					    
					    $testdatedb = $value['date_peremption']!=0000-00-00? $value['date_peremption']: "";
					    
						$datebd = date("Y-m-d", strtotime($value['date_peremption']));
						$dateactu = date("Y-m-d", strtotime(date('Y-m-d')));

						$earlier = new DateTime($datebd);
						$later = new DateTime($dateactu);
						$diff = $earlier->diff($later)->format("%a");

						/**determine si un article est périmé et dépuis combien de temps */
						if(!empty($testdatedb)){
							if($datebd <= $dateactu){
								if($diff == 0){
									$perime[] = array(
										'codebar' => $value['code_bar'],
										'designation' => $value['designation'],
										'reference' => $value['reference'],
										'qtetotal' => $qte_total['quantite'],
										'datepremption' => $value['date_peremption'],
										'jours' => $diff,
									);
								}else{
									$perime[] = array(
										'codebar' => $value['code_bar'],
										'designation' => $value['designation'],
										'reference' => $value['reference'],
										'qtetotal' => $qte_total['quantite'],
										'datepremption' => $value['date_peremption'],
										'jours' => $diff,
									);
								}
							}
						}
					}
				}
				$data['perime'] = $perime;

				if(!empty($data['perime'])){

				
					$mpdf = new \Mpdf\Mpdf();

					$mpdf->SetHTMLHeader('
						<center style="text-align: right; font-weight: bold;">
							<h2>PREMICE COMPUTER GROUPE</h2><br><br><br>
						</center>
					');

					$mpdf->SetHTMLFooter('
						<table width="100%">
							<tr>
								<td width="33%">{DATE j-m-Y H:m:s}</td>
								<td width="33%" align="center">{PAGENO}/{nbpg}</td>
								<td width="33%" style="text-align: right;">Document de critique de stock</td>
							</tr>
						</table>
					');

					// Write some HTML code:
						$mpdf->WriteHTML('
						<h4><u>'.strtoupper('Article Périmé').'</u></h4>
						<style type="text/css">
							table{
								width:100%; 
								border-collapse: collapse;
							}
							td, th{
								border: 1px solid black;
							}
						</style>	 	
						<table>
							<tr>
								<th>Code à bar</th>
								<th>Désignation</th>
								<th>Référence</th>
								<th>Qté totale</th>
								<th>debut de péremption(jour(s))</th>
							</tr>'); 	 	
							foreach ($data['perime'] as $key => $value){
								if($value['datepremption'] !="0000-00-00"){
									$mpdf->WriteHTML('
										<tr>
											<td>'.$value['codebar'].'</td>
											<td>'.$value['designation'].'</td>
											<td>'.$value['reference'].'</td>
											<td>'.$value['qtetotal'].'</td>
											<td>'.$value['jours'].'</td>
										</tr>
									');
								}else{
									
								}
							}
					$mpdf->WriteHTML('</table>');

					// Output a PDF file directly to the browser
					//$mpdf->Output();
					$mpdf->Output('articles_perime.pdf', \Mpdf\Output\Destination::INLINE);

				}else{
					flash('error','aucune information trouvé');
					redirect('alstock');
				}
			}
			
		}else{
			redirect('alstock');
		}
	}

	/**filtrer les articles en fonction de la famille*/
	public function filterarticle(){
	    $this->logged_in();
	    $output ="";
	    
	    $matricule = session('users')['matricule'];
		$famille = $this->input->post('famille');
		
		//$articles = $this->stock->tri_article($matricule,$famille);
		$articles = $this->marchandise->get_all_articlesfilter($matricule,$famille);
		if($articles){
		    foreach ($articles as $key => $value){
				$output .= '
					<tr>
						<th scope="row">'.$value['matricule_art'].'</th>
						<td>'.$value['code_bar'].'</td>
						<td>'.$value['designation'].'</td>
						<td>'.$value['quantite'].'</td>
						<td>'.$value['nom_depot'].'</td>
						<td>
							<div class="kt-widget__toolbar">
								<button class="btn btn-icon btn-circle btn-label-facebook view" id="'.$value['matricule_art'].'">
									<i class="fa fa-eye"></i>
								</button>
							</div> 
						</td>
					</tr>
				';
				$array = array(
					'success' => $output,
				);
			}
		}else{
			$array = array(
				'success' => ' Aucun article trouvé pour votre recherche'
			);
		}
		
	    echo json_encode($array);
	}
	
	
	/*quantité, marge, prix hors taxe, prix de gros, prix de revient total de tous les article dans tous les depots*/
   public function inside(){
       $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');
		
		
		if(isset($_POST['printinside'])){
		    // Create an instance of the class:
            $mpdf = new \Mpdf\Mpdf();
            
            /*$mpdf->SetHTMLHeader('
            <div style="text-align: right; font-weight: bold;">
                <h1><b>'.strtoupper(session('users')['nom']).'</b></h1>
            </div>
            <center><h3>INSIDE DE L\'ENTREPRISE</h3></center>
            ');*/
            
            $mpdf->SetHTMLFooter('
                <table width="100%">
                    <tr>
                        <td width="66%">Imprimé le : {DATE j-m-Y à H:m:s}</td>
                        <td width="66%">inside de l\'entreprise</td>
                        <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                    </tr>
                </table>
            ');
            
            $mpdf->WriteHTML('
            <style>
                table {
                  font-family: arial, sans-serif;
                  border-collapse: collapse;
                  width: 100%;
                }
                
                td, th {
                  border: 1px solid #dddddd;
                  text-align: left;
                  padding: 8px;
                }
                
                tr:nth-child(even) {
                  background-color: #dddddd;
                }
            </style>
            
                <div style="text-align: right; font-weight: bold;">
                    <h1><b>'.strtoupper(session('users')['nom']).'</b></h1>
                </div>
                <center><h3>INSIDE DE L\'ENTREPRISE</h3></center>
            
                <br><br>
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Code art.</th>
                            <th>Code à barre</th>
                            <th>Désignation</th>
                            <th>Référence</th>
                            <th width="10%">Qte Total</th>
                            <th>Critique</th>
                            <th width="15%">Prix de revient</th>
                            <th>% marge</th>
                            <th width="15%">Prix HT</th>
                        </tr>
                    </thead>
                    <tbody>
            ');
            
            /**
             * <th>Prix TTC</th>
                <th>Prix G. HT</th>
                <th>Prix G. TTC</th>*/
            
            /************************================================================================*/
            $output="";
            /**matricule de l'entreprise */
    		$matricule = session('users')['matricule'];
    		$tpr=0; $tqte=0;
    		$tpht=0; $tpttc=0;
    		$tpght=0; $tpgttc=0;
            
            
            /**afficher la liste des famille d'article */
            $famille = $this->stock->all_famille($matricule);
    		foreach($famille as $fam){
    		    
    		    $mpdf->WriteHTML('
                    <tr>
    		            <th colspan="12">Famille: '.$fam['nom_fam'].'</th>
    		        </tr>
                ');

				$totalqtefam = 0;
				$totalprfam = 0;
				$totalphtfam = 0;
    		   
    		   /***liste des articles par famille sans doublon*/
    		   $articles = $this->stock->tri_article($matricule,$fam['matricule_fam']);
    		   foreach($articles as $art){
    		       
    		       if(!empty($art['prix_revient'])){
    		       /**quantite total de chaque article dans toute l'entreprise*/
    		       $qte_total = $this->marchandise->qte_art_stock_en($matricule, $art['matricule_art']);
    		       $totalqtefam += $qte_total['quantite'];
				   $totalprfam += ($qte_total['quantite'] * $art['prix_revient']);
				   $totalphtfam +=($qte_total['quantite'] * $art['prix_hors_taxe']);
    		        $mpdf->WriteHTML('
                        <tr>
        		            <td>'.$art['matricule_art'].'</td>
                            <td>'.$art['code_bar'].'</td>
                            <td>'.$art['designation'].'</td>
                            <td>'.$art['reference'].'</td>
                            <td>'.$qte_total['quantite'].'</td>
                            <td>'.$art['critique'].'</td>
                            <td>'.numberformat(($qte_total['quantite'] * $art['prix_revient'])).'</td>
                            <td>'.$art['pourcentage_marge'].'</td>
                            <td>'.numberformat(($qte_total['quantite'] * $art['prix_hors_taxe'])).'</td>
                        </tr>
                    ');
                    /**
                     * <td>'.$art['prixttc'].'</td>
                        <td>'.$art['prix_gros'].'</td>
                        <td>'.$art['prix_gros_ttc'].'</td>*/
                        
    		       /***totaux*/
    		       $tpr = is_numeric($art['prix_revient'])?$tpr+=($qte_total['quantite'] * $art['prix_revient']):0;
    		       $tqte+=$qte_total['quantite'];
    		       $tpht = is_numeric($art['prix_hors_taxe'])?$tpht+=($qte_total['quantite'] * $art['prix_hors_taxe']):0;
    		       //$tpttc = is_numeric($art['prixttc'])?$tpttc+=$art['prixttc']:0;
    		       //$tpght = is_numeric($art['prix_gros'])?$tpght+=$art['prix_gros']:0;
    		       //$tpgttc = is_numeric($art['prix_gros_ttc'])?$tpgttc+=$art['prix_gros_ttc']:0;
    		       
    		       }
    		   }

			   $mpdf->WriteHTML('
                    <tr>
    		            <th colspan="4">Totaux Famille '.$fam['nom_fam'].' :</th>
						<th>'.numberformat($totalqtefam).'</th>
						<th></th>
						<th>'.numberformat($totalprfam).'</th>
						<th></th>
						<th>'.numberformat($totalphtfam).'</th>
    		        </tr>
                ');
    		}
    		
    		$mpdf->WriteHTML('
                <tr>
                    <th>TOTAUX</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th>'.numberformat($tqte).'</th>
                    <td></td>
                    <th>'.numberformat($tpr).'</th>
                    <td></td>
                    <th>'.numberformat($tpht).'</th>
                </tr>
            ');
            
            /*<th>'.number_format($tpttc, 2, ',', ' ').'</th>
                    <th>'.number_format($tpght, 2, ',', ' ').'</th>
                    <th>'.number_format($tpgttc, 2, ',', ' ').'</th>*/
        /************************================================================================*/
            
            $mpdf->WriteHTML('
                    </tbody>
                </table>
            ');
            
            $mpdf->Output('inside.pdf', \Mpdf\Output\Destination::INLINE);
		}
		
		$this->load->view('marchandise/inside');

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
   }
   
   
   /*****affiche le bilan de l'entreprise****/
    public function get_inside(){
        $this->logged_in(); 
        
        $output="";
        /**matricule de l'entreprise */
		$matricule = session('users')['matricule'];
		$tpr=0; $tqte=0;
		$tpht=0; $tpttc=0;
		$tpght=0; $tpgttc=0;
        
        
        /**afficher la liste des famille d'article */
        $famille = $this->stock->all_famille($matricule);
		foreach($famille as $fam){
		    $output .='
		        <tr>
		            <th colspan="12">Famille: '.$fam['nom_fam'].'</th>
		        </tr>
		   ';
		   	$totalqtefam = 0;
			$totalprfam = 0;
			$totalphtfam = 0;
		   
		   /***liste des articles par famille sans doublon*/
		   $articles = $this->stock->tri_article($matricule,$fam['matricule_fam']);
		   foreach($articles as $art){
		       
		       if(!empty($art['prix_revient'])){
		           
        		       /**quantite total de chaque article dans toute l'entreprise*/
        		       $qte_total = $this->marchandise->qte_art_stock_en($matricule, $art['matricule_art']);
        		       
        		       $totalqtefam +=$qte_total['quantite'];
						$totalprfam += ($qte_total['quantite'] * $art['prix_revient']);
						$totalphtfam += ($qte_total['quantite'] * $art['prix_hors_taxe']);
        		       
        		       $output .='
            		        <tr>
            		            <td>'.$art['matricule_art'].'</td>
                                <td>'.$art['code_bar'].'</td>
                                <td>'.$art['designation'].'</td>
                                <td>'.$art['reference'].'</td>
                                <td>'.$qte_total['quantite'].'</td>
                                <td>'.$art['critique'].'</td>
                                <td>'.numberformat(($qte_total['quantite'] * $art['prix_revient'])).'</td>
                                <td>'.$art['pourcentage_marge'].'</td>
                                <td>'.numberformat(($qte_total['quantite'] * $art['prix_hors_taxe'])).'</td>
                            </tr>
            		   ';
                        
                        /*
                            <td>'.$art['prixttc'].'</td>
                            <td>'.$art['prix_gros'].'</td>
                            <td>'.$art['prix_gros_ttc'].'</td>*/
                    
        		       /***totaux*/
        		       $tpr = is_numeric($art['prix_revient'])?$tpr+=($qte_total['quantite'] * $art['prix_revient']):0;
        		       $tqte+=$qte_total['quantite'];
        		       $tpht = is_numeric($art['prix_hors_taxe'])?$tpht+=($qte_total['quantite'] * $art['prix_hors_taxe']):0;
        		       //$tpttc = is_numeric($art['prixttc'])?$tpttc+=$art['prixttc']:0;
        		       //$tpght = is_numeric($art['prix_gros'])?$tpght+=$art['prix_gros']:0;
        		       //$tpgttc = is_numeric($art['prix_gros_ttc'])?$tpgttc+=$art['prix_gros_ttc']:0;
    		       
		           
		       }
		   }

		   $output .='
		   	<tr>
				<th colspan="4">Totaux Famille '.$fam['nom_fam'].' :</th>
				<th width="10%"><small>'.numberformat($totalqtefam).'</small></th>
				<th></th>
				<th width="15%"><small>'.numberformat($totalprfam).'</small></th>
				<th></th>
				<th width="15%"><small>'.numberformat($totalphtfam).'</small></th>
			</tr>';
		}
		
		$output .='
    		<tr>
                <th>TOTAUX</th>
                <td></td>
                <td></td>
                <td></td>
                <th>'.numberformat($tqte).'</th>
                <td></td>
                <th>'.numberformat($tpr).'</th>
                <td></td>
                <th>'.numberformat($tpht).'</th>
            </tr>
	   ';
	   /*<th>'.number_format($tpttc, 2, ',', ' ').'</th>
                <th>'.number_format($tpght, 2, ',', ' ').'</th>
                <th>'.number_format($tpgttc, 2, ',', ' ').'</th>*/
	    $array = array(
        'success'=>$output    
        );
        
        echo json_encode($array);
    }
    



}