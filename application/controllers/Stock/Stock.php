<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Stock extends CI_Controller {

	/**constructeur*/
	public function __construct(){
        parent::__construct();
        $this->load->model('Costomers/Costomer_model','costomer');
		$this->load->model('Stock/Stock_model','stock');
		$this->load->model('Fournisseur/Fournisseur_model','fournisseur');
		$this->load->model('Agence/Agence_model','agence');
		$this->load->model('Document/Document_model','document');
		$this->load->model('Commerce/Commerce_model','commerce');
		date_default_timezone_set("Africa/Douala");
		$this->load->view('parts/excellibrary/vendor/autoload');
    }


	/**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}

	/**gestion des famille de produit debut */

    /**famille de produit */
	public function index(){
		$this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

		
		$this->load->view('stock/family_article');

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}

	/**inserer dans la bd une famille de produit */
	public function save_famille_prod(){
		$this->logged_in();

		$this->form_validation->set_rules('nom', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]|is_unique[famille_produit.nom_fam]',
            array(
                'required' => 'Tu n\'as pas fourni le %s.',
                'regex_match' => 'caractère(s) non autorisé!',
				'is_unique' => 'ce nom existe déjà'
            )
        );
	

		if($this->form_validation->run()){
			if(!empty(session('users')['matricule_emp'])){
				$input = array(
					'matricule_fam' => code(10),
					'nom_fam' => $this->input->post('nom'),
					'creer_le_fam' => dates(),
					'mat_emp_fam' => session('users')['matricule_emp'],
					'mat_en_fam' => session('users')['matricule']
				);
	
				$isok = $this->stock->save_famille($input);
	
				if($isok){
					$array = array(
						'success' => '
							<div class="alert alert-success fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
								<div class="alert-text">famille produit crée avec succès</div>
								<div class="alert-close">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true"><i class="la la-close"></i></span>
									</button>
								</div>
							</div>
						'
					);
				}else{
					$array = array(
						'success' => '
							<div class="alert alert-danger fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-cross"></i></div>
								<div class="alert-text">erreur survenu! nom de famille de produit  non enrégistré</div>
								<div class="alert-close">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true"><i class="la la-close"></i></span>
									</button>
								</div>
							</div>
						'
					);
				}
			}else{
				$array = array(
					'success' => '
						<div class="alert alert-danger fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-cross"></i></div>
							<div class="alert-text">erreur survenu! connectez-vous avec un compte utilisateur</div>
							<div class="alert-close">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true"><i class="la la-close"></i></span>
								</button>
							</div>
						</div>
					'
				);
			}
			
		}else{
			$array = array(
				'error'   => true,
				'nom_error' => form_error('nom'),
			);
		}
		echo json_encode($array);
	}

	/**liste de la famille des produits */
	public function all_famille_prod(){
		$this->logged_in();
		$output = '';
		
		/**matricule de l'entreprise */
		$matricule = session('users')['matricule'];

		$recherche = trim($this->input->post('rechercher'));
		if(!empty($recherche)) {
			$famille = $this->stock->recherche_famille($matricule,$recherche);
			if(!empty($famille)){
				foreach ($famille as $key => $value) {
					$output .='
					<div class="col-xl-4 col-lg-6">
						<!--begin::Portlet-->
						<div class="kt-portlet kt-portlet--height-fluid">
							<div class="kt-widget kt-widget--general-2">
								<div class="kt-portlet__body kt-portlet__body--fit">
									<div class="kt-widget__top">
										<div class="kt-media kt-media--lg kt-media--circle">
											<img src="'.assets_dir().'media/logos/logo-1.1.png" alt="image">
										</div>
										<div class="kt-widget__wrapper">
											<div class="kt-widget__label">
												<a class="kt-widget__title">
													'.$value['nom_fam'].'
												</a>
												<span class="kt-widget__desc">
													'.$value['matricule_fam'].'
												</span>
											</div>     
											<div class="kt-widget__toolbar">
												<button class="btn btn-icon btn-circle btn-label-facebook edit" id="'.$value['matricule_fam'].'">
													<i class="fa fa-edit"></i>
												</button>
												<button class="btn btn-icon btn-circle btn-label-twitter detail_fam" id="'.$value['matricule_fam'].'">
													<i class="fa fa-eye"></i>
												</button> 
											</div>   
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--end::Portlet-->
					</div>
					';
				}
				
			}
		}else{
			$famille = $this->stock->all_famille($matricule);
			if(!empty($famille)){
				foreach ($famille as $key => $value) {
					$output .='
					<div class="col-xl-4 col-lg-6">
						<!--begin::Portlet-->
						<div class="kt-portlet kt-portlet--height-fluid">
							<div class="kt-widget kt-widget--general-2">
								<div class="kt-portlet__body kt-portlet__body--fit">
									<div class="kt-widget__top">
										<div class="kt-media kt-media--lg kt-media--circle">
											<img src="'.assets_dir().'media/logos/logo-1.1.png" alt="image">
										</div>
										<div class="kt-widget__wrapper">
											<div class="kt-widget__label">
												<a class="kt-widget__title">
													'.$value['nom_fam'].'
												</a>
												<span class="kt-widget__desc">
													'.$value['matricule_fam'].'
												</span>
											</div>     
											<div class="kt-widget__toolbar">
												<button class="btn btn-icon btn-circle btn-label-facebook edit" id="'.$value['matricule_fam'].'">
													<i class="fa fa-edit"></i>
												</button>
												<button class="btn btn-icon btn-circle btn-label-twitter detail_fam" id="'.$value['matricule_fam'].'">
													<i class="fa fa-eye"></i>
												</button> 
											</div>   
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--end::Portlet-->
					</div>
					';
				}
				
			}
		}
		

		echo json_encode($output);

	}

	/**details de la famille des produitq */
	public function details_famille_prod(){
		$this->logged_in();
		$output = '';
		$famille_mat = $this->input->post('famille_mat');
		$details = $this->stock->detail_famille($famille_mat);
		if(!empty($details)){
			$output .='
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Matricule
						<b><span class="badge">'.$details['matricule_fam'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Nom
						<h5><span class="badge">'.$details['nom_fam'].'</span></h5>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Date de création
						<span class="badge badge-primary">'.$details['creer_le_fam'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Dernière Modification
						<span class="badge badge-danger">'.$details['modifier_le_fam'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Crée par
						<span class="badge badge-warning">'.$details['nom_emp'].'</span>
					</li>
				</ul>
			';
		}

		echo json_encode($output);
	}

	/**affiche les détails dans le formulaire pour modification */
	public function edit_famille_prod(){
		$this->logged_in();
		$famille_mat = $this->input->post('famille_mat');
		$details = $this->stock->detail_famille($famille_mat);
		echo json_encode($details);
	}

	/**modifier une famille de produits */
	public function update_famille_prod(){
		$this->logged_in();
		$this->form_validation->set_rules('nom1', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]|is_unique[famille_produit.nom_fam]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!',
				'is_unique' => 'ce nom existe déjà'
            )
        );

		if($this->form_validation->run()){
			if(!empty(session('users')['matricule_emp'])){

				$matricule = $this->input->post('matricule');
				$input = array(
					'nom_fam' => $this->input->post('nom1'),
					'modifier_le_fam' => dates(),
				);

				$isok = $this->stock->update_famille($input,$matricule);

				if($isok){
					$array = array(
						'success' => '
							<div class="alert alert-success fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
								<div class="alert-text">famille produit modifier avec succès</div>
								<div class="alert-close">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true"><i class="la la-close"></i></span>
									</button>
								</div>
							</div>
						'
					);
				}else{
					$array = array(
						'success' => '
							<div class="alert alert-danger fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-cross"></i></div>
								<div class="alert-text">erreur survenu! nom de famille de produit  non modifier</div>
								<div class="alert-close">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true"><i class="la la-close"></i></span>
									</button>
								</div>
							</div>
						'
					);
				}
			}else{
				$array = array(
					'success' => '
						<div class="alert alert-danger fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-cross"></i></div>
							<div class="alert-text">erreur survenu! connectez-vous avec un compte utilisateur</div>
							<div class="alert-close">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true"><i class="la la-close"></i></span>
								</button>
							</div>
						</div>
					'
				);
			}
		}else{
			$array = array(
				'error'   => true,
				'nom1_error' => form_error('nom1'),
			);
		}

		echo json_encode($array);

	}

	/**gestion de la famille de produit fin */

	/**gestion des articles debut */

	 /**AFFiche la page du nouvel article */
	public function new_article(){
		$this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

		$this->form_validation->set_rules('tri_famille_produit', 'famille produit', 'regex_match[/^[a-zA-Z0-9._\'\- ]+$/]',
			array(
				'regex_match' => 'caractère non autorisé'
			)
		);

		if($this->form_validation->run()){
				$famille = $this->input->post('tri_famille_produit');
				$matricule = session('users')['matricule'];
				
			if(empty($famille)){
			    
			    $this->load->view('parts/excellibrary/vendor/autoload');

                
				$spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                
				$spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(5,'cm');
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                
                $sheet->setCellValue('A1', 'Code Article');
                $sheet->setCellValue('B1', 'Code à barre');
                $sheet->setCellValue('C1', 'Designation');
                $sheet->setCellValue('D1', 'Référence');
                $sheet->setCellValue('E1', 'Prix de revient');
				$sheet->setCellValue('F1', '% Marge');
				$sheet->setCellValue('G1', 'Prix HT');
				$sheet->setCellValue('H1', 'Prix TTC');
				$sheet->setCellValue('I1', 'Net à payé');
			    
			    
			    $compteur=1;
				$article = $this->stock->get_all_article($matricule);
				foreach ($article as $key => $value){
				    $compteur+=1;
				    $sheet->setCellValue('A'.$compteur, $value['matricule_art']);
                    $sheet->setCellValue('B'.$compteur, $value['code_bar']);
                    $sheet->setCellValue('C'.$compteur, $value['designation']);
                    $sheet->setCellValue('D'.$compteur, $value['reference']);
                    $sheet->setCellValue('E'.$compteur, $value['prix_revient']);
					$sheet->setCellValue('F'.$compteur, $value['pourcentage_marge']);
					$sheet->setCellValue('G'.$compteur, $value['prix_hors_taxe']);
					$sheet->setCellValue('H'.$compteur, $value['prixttc']);
					$sheet->setCellValue('I'.$compteur, $value['prix_gros']);
				}
				$writer = new Xlsx($spreadsheet);
 
 				$filename = 'article '.date('d-m-Y H:i:s');
				ob_end_clean();


				// redirect output to client browser
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
				header('Cache-Control: max-age=0');

				$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
				$writer->save('php://output');
                //flash('success','fichier générer avec succès vous pouvez maintenant proceder au téléchargement');
			}else{
				$this->load->view('parts/excellibrary/vendor/autoload');

				$spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                
                $sheet->setCellValue('A1', 'Code Article');
                $sheet->setCellValue('B1', 'Code à barre');
                $sheet->setCellValue('C1', 'Designation');
                $sheet->setCellValue('D1', 'Référence');
                $sheet->setCellValue('E1', 'Prix de revient');
				$sheet->setCellValue('F1', '% Marge');
				$sheet->setCellValue('G1', 'Prix HT');
				$sheet->setCellValue('H1', 'Prix TTC');
				$sheet->setCellValue('I1', 'Net à payé');
			    
			    
			    $compteur=1;
				$articles = $this->stock->tri_article($matricule,$famille);
				foreach ($articles as $key => $value){
				    $compteur+=1;
				    $sheet->setCellValue('A'.$compteur, $value['matricule_art']);
                    $sheet->setCellValue('B'.$compteur, $value['code_bar']);
                    $sheet->setCellValue('C'.$compteur, $value['designation']);
                    $sheet->setCellValue('D'.$compteur, $value['reference']);
                    $sheet->setCellValue('E'.$compteur, $value['prix_revient']);
					$sheet->setCellValue('F'.$compteur, $value['pourcentage_marge']);
					$sheet->setCellValue('G'.$compteur, $value['prix_hors_taxe']);
					$sheet->setCellValue('H'.$compteur, $value['prixttc']);
					$sheet->setCellValue('I'.$compteur, $value['prix_gros']);
				}
				$writer = new Xlsx($spreadsheet);
 
 				$filename = 'article '.date('d-m-Y H:i:s');
				ob_end_clean();


				// redirect output to client browser
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
				header('Cache-Control: max-age=0');

				$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
				$writer->save('php://output');
                //flash('success','fichier générer avec succès vous pouvez maintenant proceder au téléchargement');
			}
			
			
			
			   
		}
		
	
		$this->load->view('stock/new_article');


        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}	

	/**liste des fournisseurs pour enregistrer un article*/
	public function list_fournisseur(){
		$this->logged_in();
		$output = '';
		$matricule = session('users')['matricule'];
		$fournisseur = $this->fournisseur->get_all_fournisseur($matricule);
		if (!empty($fournisseur)){
			$output .= '<option value=""></option>';
			foreach ($fournisseur as $key => $value) {
				$output .= '<option value="'.$value['matricule_four'].'">'.$value['nom_four'].'</option>';
			}
		}
		echo json_encode($output);
	}

	/**liste des famille produit pour enrégistrer un article*/
	public function list_famille_produit(){
		$this->logged_in();
		$output = '';
		$matricule = session('users')['matricule'];
		$famille = $this->stock->all_famille($matricule);
		if (!empty($famille)){
			$output .= '<option value=""></option>';
			foreach ($famille as $key => $value) {
				$output .= '<option value="'.$value['matricule_fam'].'">'.$value['nom_fam'].'</option>';
			}
		}
		echo json_encode($output);
	}

	/**inserer un nouvel article dans la base des données */
	public function save_article(){
		$this->logged_in();

		$this->form_validation->set_rules('codebar', 'code à bar', 'regex_match[/^[a-zA-Z0-9._\'\- ]+$/]', //required
            array(
                //'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('design', 'désignation', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\/\\- ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni la %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('ref', 'référence', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\/\\- ]+$/]', //required|
            array(
                //'required'      => 'Tu n\'as pas fourni la %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('fournisseur', 'fournisseur', 'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('famille_prod', 'famille du produit', 'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni la %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('prix_revient', 'prix de revient', 'required|numeric',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'numeric'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('pourcentage_marge', 'marge', 'required|numeric',
            array(
                'required'      => 'Tu n\'as pas fourni la %s.',
                'numeric'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('critique', 'critique', 'required|numeric',
            array(
                'required'      => 'Tu n\'as pas fourni la %s.',
                'numeric'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('date_perem', 'date', 'regex_match[/\d{4}\-\d{2}-\d{2}/]', //required|
            array(
                //'required'      => 'Tu n\'as pas fourni la %s de peremption.',
                'numeric'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('delais_perem', 'delais', 'numeric', //required|
            array(
                //'required'      => 'Tu n\'as pas fourni le %s de peremption.',
                'numeric'     => 'caractère(s) non autorisé!'
            )
        );

		if($this->form_validation->run()){

			if(!empty(session('users')['matricule_emp'])){

				/**calcul du prix ht, prix de gros, prix de gros ttc et prix ttc debut */
				$PR = $this->input->post('prix_revient');
				$marge = $this->input->post('pourcentage_marge');

				$prix_HT = ($PR + ($PR * ($marge/100)));
				$prix_gros = ($prix_HT - ($prix_HT*(2.2/100)));
				
				$prix_tva = (($prix_HT * 19.25)/100);
				$prix_ttc = ($prix_HT + $prix_tva);
				
				$prixgrosttc = ($prix_ttc - ($prix_ttc*(2.2/100)));
				
				/**calcul du prix ht, prix de gros, prix de gros ttc et prix ttc fin */

				$matricule_en = session('users')['matricule'];
				$matricule_emp = session('users')['matricule_emp'];

				$input = array(
					'matricule_art' => code(10),
					'code_bar' => $this->input->post('codebar'),
					'designation' => $this->input->post('design'),
					'reference' => $this->input->post('ref'),
					'prix_revient' => $this->input->post('prix_revient'),
					'pourcentage_marge' => $this->input->post('pourcentage_marge'),
					'prix_hors_taxe' => $prix_HT,
					'prixttc' => $prix_ttc,
					'prix_gros_ttc' => $prixgrosttc,
					'prix_gros' => $prix_gros,
					'critique' => $this->input->post('critique'),
					'date_peremption' => $this->input->post('date_perem'),
					'delais_alert_peremption' => $this->input->post('delais_perem'),
					'creer_article_le' => dates(),
					'mat_fournisseur' => $this->input->post('fournisseur'),
					'mat_famille_produit' => $this->input->post('famille_prod'),
					'mat_employe' => $matricule_emp,
					'mat_en' => $matricule_en
				);
				
				$query = $this->stock->save_article($input);
				if($query){
					$array = array(
						'success' => '
							<div class="alert alert-success fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
								<div class="alert-text">article crée avec succès</div>
								<div class="alert-close">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true"><i class="la la-close"></i></span>
									</button>
								</div>
							</div>
						'
					);
				}else{
					$array = array(
						'success' => '
							<div class="alert alert-danger fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-cross"></i></div>
								<div class="alert-text">erreur survenu! article non crée</div>
								<div class="alert-close">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true"><i class="la la-close"></i></span>
									</button>
								</div>
							</div>
						'
					);
				}

			}else{
				$array = array(
					'success' => '
						<div class="alert alert-danger fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-cross"></i></div>
							<div class="alert-text">erreur survenu! connectez-vous avec un compte utilisateur</div>
							<div class="alert-close">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true"><i class="la la-close"></i></span>
								</button>
							</div>
						</div>
					'
				);
			}

		}else{
			$array = array(
				'error'   => true,
				'codebar_error' => form_error('codebar'),
				'design_error' => form_error('design'),
				'ref_error' => form_error('ref'),
				'fournisseur_error' => form_error('fournisseur'),
				'famille_prod_error' => form_error('famille_prod'), 
				'prix_revient_error' => form_error('prix_revient'),
				'pourcentage_marge_error' => form_error('pourcentage_marge'),
				'critique_error' => form_error('critique'),
				'date_perem_error' => form_error('date_perem'), 
				'delais_perem_error' => form_error('delais_perem')
			);	
		}

		echo json_encode($array);
	}

	/**afficher la liste de tous les articles */
	public function all_article(){
		$this->logged_in();
		$output = '';
		$matricule = session('users')['matricule'];

		$recherche = $this->input->post('recherche');

		if(!empty($recherche)){
			$articles = $this->stock->get_recherche_articles($matricule,$recherche);
			if($articles){
				foreach ($articles as $key => $value){
					$ph = !empty($value['prix_hors_taxe'])?numberformat($value['prix_hors_taxe']):'';
					$output .= '
						<tr>
							<th scope="row">'.$value['matricule_art'].'</th>
							<td>'.$value['designation'].'</td>
							<td>'.$value['prix_revient'].'</td>
							<th scope="row">'.$value['pourcentage_marge'].'</th>
							<th scope="row">'.$ph.'</th>
							<td>
								<span class="kt-widget__toolbar">
									<button class="btn btn-icon btn-circle btn-label-facebook view" id="'.$value['matricule_art'].'">
										<i class="fa fa-eye"></i>
									</button>
								</span> 
							</td>
							<td>
								<span class="kt-widget__toolbar">
									<button class="btn btn-icon btn-circle btn-label-twitter edit" id="'.$value['matricule_art'].'">
										<i class="fa fa-edit"></i>
									</button>
								</span> 
							</td>
						</tr>
					';

					$outputs = array(
						'articles' => $output,
						'pagination_link' => $this->pagination->create_links()
					);	
				}
			}else{
				//$output .= 'Aucun article trouvé pour votre recherche';
				$outputs = array(
					'articles' => 'Aucun article trouvé pour votre recherche'
				);
			}
		}else{

			$config = array();
			$config["base_url"] = "#";
			$config["total_rows"] = $this->stock->count_all_article($matricule);
			$config["per_page"] = 10;
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
			$config["cur_tag_open"] = "<li class='page-item active'><a href='#'><span class='page-link'><span class='sr-only'>(current)</span>";
			$config["cur_tag_close"] = "</span></a></li>";
			$config["num_tag_open"] = '<li class="page-item page-link">';
			$config["num_tag_close"] = "</li>";
			$config["num_links"] = 1;
			$this->pagination->initialize($config);

			//$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

			$page = (($this->uri->segment(2) - 1) * $config["per_page"]);

			$articles = $this->stock->get_all_articles($matricule,$config["per_page"],$page);

			if(!empty($articles)){
				foreach ($articles as $key => $value) {
					$ph = !empty($value['prix_hors_taxe'])?numberformat($value['prix_hors_taxe']):'';

					$output .= '
						<tr>
							<th scope="row">'.$value['matricule_art'].'</th>
							<td>'.$value['designation'].'</td>
							<td>'.$value['prix_revient'].'</td>
							<th scope="row">'.$value['pourcentage_marge'].'</th>
							<th scope="row">'.$ph.'</th>
							<td>
								<span class="kt-widget__toolbar">
									<button class="btn btn-icon btn-circle btn-label-facebook view" id="'.$value['matricule_art'].'">
										<i class="fa fa-eye"></i>
									</button>
								</span> 
							</td>
							<td>
								<span class="kt-widget__toolbar">
									<button class="btn btn-icon btn-circle btn-label-twitter edit" id="'.$value['matricule_art'].'">
										<i class="fa fa-edit"></i>
									</button>
								</span> 
							</td>
						</tr>
					';	
				}
			}else{
				$output .= '<b class="text-danger">aucun article trouvé</b>';
			}

			$outputs = array(
				'articles' => $output,
				'pagination_link' => $this->pagination->create_links()
			);
		}
		

		echo json_encode($outputs);
	}

	/**affiche les détails d'un article */
	public function details_article(){
		$this->logged_in();
		$output = '';
		$code_article = $this->input->post('article_mat');
		$articles_details = $this->stock->get_details_articles($code_article);
		if($articles_details){
                
            $prixrevient = !empty($articles_details['prix_revient'])?number_format($articles_details['prix_revient'], 2, ',', ' '):'';
            $prixht = !empty($articles_details['prix_hors_taxe'])?number_format($articles_details['prix_hors_taxe'], 2, ',', ' '):'';
            $prixttc  = !empty($articles_details['prixttc'])?number_format($articles_details['prixttc'], 2, ',', ' '):'';
            $prixgrosht = !empty($articles_details['prix_gros'])?number_format($articles_details['prix_gros'], 2, ',', ' '):'';
            $prixgrosttc = !empty($articles_details['prix_gros_ttc'])?number_format($articles_details['prix_gros_ttc'], 2, ',', ' '):'';
            
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
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Désignation
						<span class="badge">'.$articles_details['designation'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Référence
						<span class="badge">'.$articles_details['reference'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center bg-primary text-white">
						Prix de revient
						<span class="badge font-weight-bold text-white">'.$prixrevient.'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Pourcentage des marges
						<span class="badge font-weight-bold">'.$articles_details['pourcentage_marge'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center bg-success text-white">
						Prix HT
						<span class="badge bage-primary font-weight-bold text-white">'.$prixht.'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center bg-success text-white">
						Prix TTC
						<span class="badge bage-primary font-weight-bold text-white">'.$prixttc.'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center bg-warning text-white">
						Prix de gros HT
						<span class="badge font-weight-bold text-white">'.$prixgrosht.'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center bg-warning text-white">
						Prix de gros TTC
						<span class="badge font-weight-bold text-white">'.$prixgrosttc.'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Critique
						<span class="badge">'.$articles_details['critique'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Date peremption
						<span class="badge">'.$articles_details['date_peremption'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						delais arlert peremption
						<span class="badge">'.$articles_details['delais_alert_peremption'].' jour(s)</span>
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
						<span class="badge">'.$articles_details['nom_fam'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Article enrégistrer par
						<span class="badge">'.$articles_details['nom_emp'].'</span>
					</li>
				</ul>
			';

			$outputs = array(
				'details' => $output
			);
		}	
		echo json_encode($outputs);
	}

	/** afficher les details d'un article dans le formulaire*/
	public function edit_article(){
		$this->logged_in();
		$code_article = $this->input->post('article_mat');
		$articles_details = $this->stock->get_details_articles($code_article);
		if($articles_details){
			$outputs = array(
				'details' => $articles_details
			);
		}
		echo json_encode($outputs);
	}

	/**modifier un article debut */
	public function updatearticle(){
		$this->logged_in();

		$this->form_validation->set_rules('codebar1', 'code à bar', 'regex_match[/^[a-zA-Z0-9._\'\- ]+$/]', //required|
            array(
                //'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('design1', 'désignation', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\/\\- ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni la %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('ref1', 'référence', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\/\\- ]+$/]', //required|
            array(
                //'required'      => 'Tu n\'as pas fourni la %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('fournisseur1', 'fournisseur', 'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('famille_prod1', 'famille du produit', 'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni la %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('prix_revient1', 'prix de revient', 'required|numeric',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'numeric'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('pourcentage_marge1', 'marge', 'required|numeric',
            array(
                'required'      => 'Tu n\'as pas fourni la %s.',
                'numeric'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('critique1', 'critique', 'required|numeric',
            array(
                'required'      => 'Tu n\'as pas fourni la %s.',
                'numeric'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('date_perem1', 'date', 'regex_match[/\d{4}\-\d{2}-\d{2}/]', //required|
            array(
                //'required'      => 'Tu n\'as pas fourni la %s de peremption.',
                'numeric'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('delais_perem1', 'delais', 'numeric', //required|
            array(
                //'required'      => 'Tu n\'as pas fourni le %s de peremption.',
                'numeric'     => 'caractère(s) non autorisé!'
            )
        );

		if($this->form_validation->run()){

			if(!empty(session('users')['matricule_emp'])){

				/**calcul du prix ht et prix de gros debut */
				$PR = $this->input->post('prix_revient1');
				$marge = $this->input->post('pourcentage_marge1');

				$prix_HT = ($PR + ($PR * ($marge/100)));
				$prix_gros = ($prix_HT - ($prix_HT*(2.2/100)));
				
				$prix_tva = (($prix_HT * 19.25)/100);
				$prix_ttc = ($prix_HT + $prix_tva);
				
				$prixgrosttc = ($prix_ttc - ($prix_ttc*(2.2/100)));
				
				/**calcul du prix ht et prix de gros fin */
				$condition = $this->input->post('matricule');
				$input = array(
					'code_bar' => $this->input->post('codebar1'),
					'designation' => $this->input->post('design1'),
					'reference' => $this->input->post('ref1'),
					'prix_revient' => $this->input->post('prix_revient1'),
					'pourcentage_marge' => $this->input->post('pourcentage_marge1'),
					'prix_hors_taxe' => $prix_HT,
					'prixttc' => $prix_ttc,
					'prix_gros_ttc' => $prixgrosttc,
					'prix_gros' => $prix_gros,
					'critique' => $this->input->post('critique1'),
					'date_peremption' => $this->input->post('date_perem1'),
					'delais_alert_peremption' => $this->input->post('delais_perem1'),
					'modifier_article_le' => dates(),
					'mat_fournisseur' => $this->input->post('fournisseur1'),
					'mat_famille_produit' => $this->input->post('famille_prod1'),
				);

				$query = $this->stock->update_article($input,$condition);
				if($query){
					$array = array(
						'success' => '
							<div class="alert alert-success fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
								<div class="alert-text">article modifier avec succès '.$condition.'</div>
								<div class="alert-close">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true"><i class="la la-close"></i></span>
									</button>
								</div>
							</div>
						'
					);
				}else{
					$array = array(
						'success' => '
							<div class="alert alert-danger fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-cross"></i></div>
								<div class="alert-text">erreur survenu! article non modifier</div>
								<div class="alert-close">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true"><i class="la la-close"></i></span>
									</button>
								</div>
							</div>
						'
					);
				}

			}else{
				$array = array(
					'success' => '
						<div class="alert alert-danger fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-cross"></i></div>
							<div class="alert-text">erreur survenu! connectez-vous avec un compte utilisateur</div>
							<div class="alert-close">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true"><i class="la la-close"></i></span>
								</button>
							</div>
						</div>
					'
				);
			}

		}else{
			$array = array(
				'error'   => true,
				'codebar1_error' => form_error('codebar1'),
				'design1_error' => form_error('design1'),
				'ref1_error' => form_error('ref1'),
				'fournisseur1_error' => form_error('fournisseur1'),
				'famille_prod1_error' => form_error('famille_prod1'), 
				'prix_revient1_error' => form_error('prix_revient1'),
				'pourcentage_marge1_error' => form_error('pourcentage_marge1'),
				'critique1_error' => form_error('critique1'),
				'date_perem1_error' => form_error('date_perem1'), 
				'delais_perem1_error' => form_error('delais_perem1')
			);	
		}

		echo json_encode($array);
	}
	/**modifier un article fin */

	/**affiche les famille pour faciliter le tri debut */
	public function tri_famil_article(){
		$this->logged_in();
		$output = '';
		$matricule = session('users')['matricule'];	
		$produit_famille = $this->stock->get_tri_famil_articles($matricule);
		if(!empty($produit_famille)){
		    $output.= '<option selected></option>';
			foreach ($produit_famille as $key => $value){
				if(set_value('tri_famille_produit') == $value['matricule_fam']){
    	        	$output .= '<option value="'.$value['matricule_fam'].'" selected>'.$value['nom_fam'].'</option>';
				}else{
					$output .= '<option value="'.$value['matricule_fam'].'">'.$value['nom_fam'].'</option>';
				}	
			}
		}else{
			$output .='<option selected disabled>Aucune famille d\'article trouvé</option>';
		}
		$array = array(
			'famille' => $output
		);
		echo json_encode($array);
	}
	/*public function tri_four_article(){
		$this->logged_in();
		$output = '';
		$matricule = session('users')['matricule'];	
		$four_famille = $this->stock->get_tri_four_articles($matricule);
		if($four_famille){
			$output.= '<option value=""></option>';
			foreach ($four_famille as $key => $value) {
				$output .= '<option value="'.$value['mat_fournisseur'].'">'.$value['nom_four'].'</option>';
			}
		}
		$outputs = array(
			'fournisseur' => $output
		);

		echo json_encode($outputs);
	}*/
	/**affiche les famille pour faciliter le tri debut */


	/***trié les article en fonction du fournisseur det de la famille du produit puis afficher debut */
	public function filtre_article(){
		$this->logged_in();
			$output ="";
			$matricule = session('users')['matricule'];
			$famille = $this->input->post('famille');
			//$fournisseur = $this->input->post('fournisseur');
			
			$articles = $this->stock->tri_article($matricule,$famille);
			if($articles){
				foreach ($articles as $value) {
					$ph = !empty($value['prix_hors_taxe'])?numberformat($value['prix_hors_taxe']):'';
					$output .='
						<tr>
							<th scope="row">'.$value['matricule_art'].'</th>
							<td>'.$value['designation'].'</td>
							<td>'.$value['prix_revient'].'</td>
							<th scope="row">'.$value['pourcentage_marge'].'</th>
							<th scope="row">'.$ph.'</th>
							<td>
								<span class="kt-widget__toolbar">
									<button class="btn btn-icon btn-circle btn-label-facebook view" id="'.$value['matricule_art'].'">
										<i class="fa fa-eye"></i>
									</button>
								</span> 
							</td>
							<td>
								<span class="kt-widget__toolbar">
									<button class="btn btn-icon btn-circle btn-label-twitter edit" id="'.$value['matricule_art'].'">
										<i class="fa fa-edit"></i>
									</button>
								</span> 
							</td>
						</tr>
					';
					$array = array(
						'articles' => $output
					);
				}
			}else{
				$array = array(
					'articles' => ' Aucun article trouvé pour votre recherche'
				);
			}

		echo json_encode($array);
	}
	/***trié les article en fonction du fournisseur det de la famille du produit debut */
   

	/**gestion des articles fin*/


	/**gestion des depots debut */
	public function depot(){
		$this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

		$this->load->view('stock/new_depot');

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}

	/**enregistrer un nouveau depot debut */
	public function save_depot(){
		$this->logged_in();
		$this->form_validation->set_rules('nom', 'nom dépot', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('adresse', 'adresse dépot', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'l\' %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('email', 'email dépot', 'required|valid_email',array(
			'required' => 'l\' %s est nécessaire',
			'valid_email' => 'email pas valide'
		));

		$this->form_validation->set_rules('telephone', 'téléphone dépot', 'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]',array(
			'required' => 'le %s est nécessaire'
		));

		$this->form_validation->set_rules('agence', 'agence', 'regex_match[/^[a-zA-Z0-9\ ]+$/]',array(
			'regex_match' => 'caractère(s) non autorisé(s)'
		));


		if($this->form_validation->run()){

			if(session('users')['matricule_emp']){

				$nom = $this->input->post('nom');
				$adresse = $this->input->post('adresse');
				$email = $this->input->post('email');
				$telephone = $this->input->post('telephone');
				$agence = $this->input->post('agence');
				$entreprise = session('users')['matricule'];
				$employe = session('users')['matricule_emp']; 
				$code = code(10);
				$date = dates();

				if(empty($agence)){
					$input = array(
						'mat_depot' => $code,
						'nom_depot' => $nom,
						'adresse_depot' => $adresse,
						'email_depot' => $email,
						'telephone_depot' => $telephone,
						'code_emp_d' => $employe,
						'code_en_d' => $entreprise,
						'creer_depot_le' => $date
					);
					$query = $this->stock->save_depot($input);
					if($query){

						$array = array(
							'success' => '
								<div class="alert alert-success fade show" role="alert">
									<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
									<div class="alert-text">dépot enrégistrer avec succès</div>
									<div class="alert-close">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true"><i class="la la-close"></i></span>
										</button>
									</div>
								</div>
							'
						);
					}else{
						$array = array(
							'success' => '
								<div class="alert alert-danger fade show" role="alert">
									<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
									<div class="alert-text">erreur survenur, actualisé puis reéssayer</div>
									<div class="alert-close">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true"><i class="la la-close"></i></span>
										</button>
									</div>
								</div>
							'
						);
					}
				}else{
					$input = array(
						'mat_depot' => $code,
						'nom_depot' => $nom,
						'adresse_depot' => $adresse,
						'email_depot' => $email,
						'telephone_depot' => $telephone,
						'code_emp_d' => $employe,
						'code_en_d' => $entreprise,
						'code_ag_d' => $agence,
						'creer_depot_le' => $date
					);
					$query = $this->stock->save_depot($input);
					if($query){

						$array = array(
							'success' => '
								<div class="alert alert-success fade show" role="alert">
									<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
									<div class="alert-text">dépot enrégistrer avec succès</div>
									<div class="alert-close">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true"><i class="la la-close"></i></span>
										</button>
									</div>
								</div>
							'
						);
					}else{
						$array = array(
							'success' => '
								<div class="alert alert-danger fade show" role="alert">
									<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
									<div class="alert-text">erreur survenur, actualisé puis reéssayer</div>
									<div class="alert-close">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true"><i class="la la-close"></i></span>
										</button>
									</div>
								</div>
							'
						);
					}
				}
				
			}else{
				$array = array(
					'success' => '
						<div class="alert alert-danger fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
							<div class="alert-text">erreur survenur, connectez vous en tant que utilisateur</div>
							<div class="alert-close">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true"><i class="la la-close"></i></span>
								</button>
							</div>
						</div>
					'
				);
			}

		}else{
			$array = array(
				'error'   => true,
				'nom_error' => form_error('nom'),
				'adresse_error' => form_error('adresse'),
				'email_error' => form_error('email'),
				'telephone_error' => form_error('telephone'),
				'agence_error' => form_error('agence')
			);
		}

		echo json_encode($array);
	}
	/**enregistrer un nouveau depot debut */

	/**afficher la liste des dépots debut */
	public function all_depot($num_page = NULL){
		$this->logged_in();
		
		$entreprise = session('users')['matricule'];
		$output = "";
		$rechercher = $this->input->post('recherche');
		if(!empty($rechercher)){
			$query = $this->stock->get_recherche_depot($entreprise,$rechercher);
			if($query){
				foreach ($query as $key => $value){
					$output .='
						<tr>
							<td>'.$value['nom_depot'].'</td>
							<td>'.$value['adresse_depot'].'</td>
							<td>'.$value['email_depot'].'</td>
							<td>'.$value['telephone_depot'].'</td>
							<td>
								<button id="'.$value['mat_depot'].'" class="btn btn-icon btn-circle btn-label-facebook view">
									<i class="fa fa-eye"></i>
								</button>
								<button id="'.$value['mat_depot'].'" class="btn btn-icon btn-circle btn-label-twitter edit">
									<i class="fa fa-edit"></i>
								</button>
							</td>
						</tr>
					';

					$outputs = array(
						'depots' => $output
					);
				}
			}else{
				$outputs = array(
					'depots' => 'aucune information trouvé'
				);
			}
		}else{
			$config = array();
			$config["base_url"] = "#";
			$config["total_rows"] = $this->stock->count_all_depot($entreprise);
			$config["per_page"] = 10;
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
			$config["cur_tag_open"] = "<li class='page-item active'><span class='page-link'><a href='#'><span class='sr-only'>(current)</span>";
			$config["cur_tag_close"] = "</a></span></li>";
			$config["num_tag_open"] = '<li class="page-item page-link">';
			$config["num_tag_close"] = "</li>";
			$config["num_links"] = 1;
			$this->pagination->initialize($config);
			$page = (($this->uri->segment(2) - 1) * $config["per_page"]);

			$query = $this->stock->get_all_depot($entreprise,$config["per_page"],$page);
			if($query){
				foreach ($query as $key => $value) {
					$output .='
						<tr>
							<td>'.$value['nom_depot'].'</td>
							<td>'.$value['adresse_depot'].'</td>
							<td>'.$value['email_depot'].'</td>
							<td>'.$value['telephone_depot'].'</td>
							<td>
								<button id="'.$value['mat_depot'].'" class="btn btn-icon btn-circle btn-label-facebook view">
									<i class="fa fa-eye"></i>
								</button>
								<button id="'.$value['mat_depot'].'" class="btn btn-icon btn-circle btn-label-twitter edit">
									<i class="fa fa-edit"></i>
								</button>
							</td>
						</tr>
					';
					$outputs = array(
						'depots' => $output,
						'pagination_link' => $this->pagination->create_links()
					);
				}
			}else{
				$outputs = array(
					'depots' => 'aucune information trouvé'
				);
			}
		}

		echo json_encode($outputs);
	}
	/**afficher la liste des dépots fin*/

	/**affiche les details d'un depot debut */
	public function detail_depot(){
		$this->logged_in();
		$output = '';
		$matricule_depot = $this->input->post('mat_depot');	
		if(!empty($matricule_depot)){
			$query = $this->stock->details_depot($matricule_depot);
			if($query){
				$output .= '
					<ul class="list-group">
						<li class="list-group-item d-flex justify-content-between align-items-center">
							Code du dépot
							<span class="badge">'.$query['mat_depot'].'</span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							Nom du dépot
							<span class="badge">'.$query['nom_depot'].'</span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							Adresse du dépot
							<span class="badge">'.$query['adresse_depot'].'</span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							Email du dépot
							<span class="badge">'.$query['email_depot'].'</span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							Téléphone du dépot
							<span class="badge">'.$query['telephone_depot'].'</span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							Dépot de
							<span class="badge badge-danger">'.$query['nom_ag'].'</span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							Créer par
							<span class="badge badge-warning">'.$query['nom_emp'].'</span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							Creer le
							<span class="badge">'.$query['creer_depot_le'].'</span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							Dernière Modification
							<span class="badge">'.$query['modifier_depot_le'].'</span>
						</li>
					</ul>
				';
			}
		}else{
			$output .= '<h6 class="text-danger">erreur survenu contactez l\'administrateur</h6>';	
		}
		

		echo json_encode($output);
	}
	/**affiche les details d'un depot fin */

	/**afficher les détails dans le formulaire pour modifier un dépot debut*/
	public function edit_depot(){
		$this->logged_in();
		$matricule_depot = $this->input->post('mat_depot');
		if($matricule_depot){
			$query = $this->stock->details_depot($matricule_depot);
			if($query){
				$outputs = array(
					'details' => $query
				);
			}
		}else{
			$outputs = array(
				'details' => 'erreur survenu, contactez l\administrateur'
			);
		}
		echo json_encode($outputs);
	}
	/**afficher les détails dans le formulaire pour modifier un dépot fin*/

	/**modifier un depot debut*/
	public function update_depot(){
		$this->logged_in();

		$this->form_validation->set_rules('nom1', 'nom dépot', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('adresse1', 'adresse dépot', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'l\' %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('email1', 'email dépot', 'required|valid_email',array(
			'required' => 'l\' %s est nécessaire',
			'valid_email' => 'email pas valide'
		));

		$this->form_validation->set_rules('telephone1', 'téléphone dépot', 'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]',array(
			'required' => 'le %s est nécessaire'
		));

		$this->form_validation->set_rules('agence1', 'agence', 'regex_match[/^[a-zA-Z0-9\ ]+$/]',array(
			'regex_match' => 'caractère(s) non autorisé(s)'
		));


		if($this->form_validation->run()){

			if(session('users')['matricule_emp']){

				$nom = $this->input->post('nom1');
				$adresse = $this->input->post('adresse1');
				$email = $this->input->post('email1');
				$telephone = $this->input->post('telephone1');
				$agence = $this->input->post('agence1');
				$date = dates();

				$matricule = $this->input->post('matricule');
				if(!empty($agence)){

				
					$input = array(
						'nom_depot' => $nom,
						'adresse_depot' => $adresse,
						'email_depot' => $email,
						'telephone_depot' => $telephone,
						'code_ag_d' => $agence,
						'modifier_depot_le' => $date
					);

					$query = $this->stock->update_depot($input,$matricule);

					if($query){

						$array = array(
							'success' => '
								<div class="alert alert-success fade show" role="alert">
									<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
									<div class="alert-text">dépot modifier avec succès</div>
									<div class="alert-close">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true"><i class="la la-close"></i></span>
										</button>
									</div>
								</div>
							'
						);
						
			
					}else{
						$array = array(
							'success' => '
								<div class="alert alert-danger fade show" role="alert">
									<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
									<div class="alert-text">erreur survenur, actualisé puis reéssayer</div>
									<div class="alert-close">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true"><i class="la la-close"></i></span>
										</button>
									</div>
								</div>
							'
						);
			
					}
				}else{
					$input = array(
						'nom_depot' => $nom,
						'adresse_depot' => $adresse,
						'email_depot' => $email,
						'telephone_depot' => $telephone,
						'code_ag_d' => NULL,
						'modifier_depot_le' => $date
					);

					$query = $this->stock->update_depot($input,$matricule);

					if($query){

						$array = array(
							'success' => '
								<div class="alert alert-success fade show" role="alert">
									<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
									<div class="alert-text">dépot modifier avec succès</div>
									<div class="alert-close">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true"><i class="la la-close"></i></span>
										</button>
									</div>
								</div>
							'
						);
						
			
					}else{
						$array = array(
							'success' => '
								<div class="alert alert-danger fade show" role="alert">
									<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
									<div class="alert-text">erreur survenur, actualisé puis reéssayer</div>
									<div class="alert-close">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true"><i class="la la-close"></i></span>
										</button>
									</div>
								</div>
							'
						);
			
					}
				}

			}else{
				$array = array(
					'success' => '
						<div class="alert alert-danger fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
							<div class="alert-text">erreur survenur, connectez vous en tant que utilisateur</div>
							<div class="alert-close">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true"><i class="la la-close"></i></span>
								</button>
							</div>
						</div>
					'
				);
			}
			

		}else{
			$array = array(
				'error'   => true,
				'nom1_error' => form_error('nom1'),
				'adresse1_error' => form_error('adresse1'),
				'email1_error' => form_error('email1'),
				'telephone1_error' => form_error('telephone1'),
				'agence1_error' => form_error('agence1')
			);
		}

		echo json_encode($array);
		

	}
	/**modifier un depot fin */

	/**gestion des depots fin */


	/**gestion des entrées et sortie en stock debut*/

	/**affiche la page d'entrer et sortie en stock */
	public function enter_stock_article($code_type_doc = NULL){
		$this->logged_in();
		$output = "";
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

		/**matricule de l'entreprise */
		$matricule = session('users')['matricule'];

		/**affiche la liste des type de documents pour une entreprise donné */
		$data['docs'] = $this->stock->type_doc($matricule);
		//$data['docs'] = $this->document->get_doc($matricule);

		/**affiche la liste des dépots */
		$data['depots'] = $this->stock->get_all_depot2($matricule);

		/**affiche la liste des fournisseurs d'une entreprise donné */
		$data['fournisseurs'] = $this->fournisseur->get_all_fournisseur($matricule);


		/**affiche la liste des articles */
		$data['articles'] = $this->stock->get_all_article($matricule);


		/**afficher le code et le nom d'un document générer*/
		$data['code_doc'] = array(
			'DOC'.code(10) => 'DOCUMENT'.code(10).''.date('d-m-Y'),
		);
		
		/**selection de la list des documents en fonction du type de document */
		if(!empty($code_type_doc)){
			$output = $this->stock->get_doc_type($code_type_doc);
		}
		$data['get_docs'] = $output;
		

		$this->load->view('stock/entre_stock',$data);


        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}

	/**afficher les information d'un article dans le formulaire d'entré en stock */
	public function form_stock(){
		$this->logged_in();
		$output ="";
		$this->form_validation->set_rules('type_doc_2', 'type document', 'required|regex_match[/^[a-zA-Z0-9]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('depot', 'depot', 'required|regex_match[/^[a-zA-Z0-9]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('fournisseur', 'fournisseur', 'required|regex_match[/^[a-zA-Z0-9]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('article', 'article', 'required|regex_match[/^[a-zA-Z0-9]+$/]',array(
			'required' => 'l\' %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('document', 'document', 'required|regex_match[/^[a-zA-Z0-9]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		if ($this->form_validation->run()){
			$code_art = trim($this->input->post('article'));
			$document = $this->input->post('document');
			$depot  = trim($this->input->post('depot'));
			$matricule_en = session('users')['matricule'];
			
			$art_stock_depot = $this->stock->get_art_stock($code_art,$depot);
			if(!empty($art_stock_depot)){
				/**liste des articles du document encour */
				$art_du_document = $this->art_doc($document,$matricule_en);
				$output = $art_du_document;
				$array = array(
					'success'   => 'article trouvé',
					'article'   => $art_stock_depot,
					'art_doc' => $output
				);
			}else{
				$art_stock = $this->stock->get_art_stock3($code_art);
				if(!empty($art_stock)){
				    
				    $contenu = array(
            		    'matricule_art' => $art_stock['matricule_art'],
            		    'code_bar' => $art_stock['code_bar'],
            		    'designation' => $art_stock['designation'],
            		    'quantite' => '',
            		    'reference' => $art_stock['reference'],
            		    'prix_revient' => $art_stock['prix_revient'],
            		    'pourcentage_marge' => $art_stock['pourcentage_marge'],
            		    'prix_hors_taxe' => $art_stock['prix_hors_taxe'],
            		    'prix_gros' => $art_stock['prix_gros'],
            		    'critique' => $art_stock['critique'],
            		    'date_peremption' => $art_stock['date_peremption'],
            		    'delais_alert_peremption' => $art_stock['delais_alert_peremption'],
            		    'creer_article_le' => $art_stock['creer_article_le'],
            		);
		
					/**liste des articles du document encour */
					$art_du_document = $this->art_doc($document,$matricule_en);
					$output = $art_du_document;
					$array = array(
						'success'   => 'article trouvé',
						'article'   => $contenu,
						'art_doc' => $output
					);
				}else{
					$array = array(
						'success'   => '
							<script>
								swal.fire("OUPS!","cet article non trouvé...","info")
							</script>
						'
					);
				}
			}
			
		}else{
			$array = array(
				'error'   => true,
				'type_doc_2_error' => form_error('type_doc_2'),
				'depot_error' => form_error('depot'),
				'fournisseur_error' => form_error('fournisseur'),
				'article_error' => form_error('article'),
				'document_error' => form_error('document'),
			);
		}

		echo json_encode($array);
	}

	/**operation sur le stock debut*/
	public function operation_stock(){
		$this->logged_in();

		/**message d'erreur (connexion a la base des données perdu) debut*/
		$message_db = 	array(
							'success'   => '
								<script>
									swal.fire("OUPS!","connexion à la base des données perdu","info")
								</script>
							'
						);
		/**message d'erreur (connexion a la base des données perdu) fin*/

		
		$this->form_validation->set_rules('new_quantes', 'quantité', 'required|regex_match[/^[0-9]+$/]',array(
			'required' => 'entre la %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		if($this->form_validation->run()){
			$output = "";

			$qte = trim($this->input->post('new_quantes'));
			$code_article = trim($this->input->post('art'));
			$code_type_document = trim($this->input->post('t_doc'));
			$code_depot = trim($this->input->post('dep'));
			$code_fournisseur = trim($this->input->post('four'));
			$code_document = trim($this->input->post('doc_m'));

			$matricule_en = session('users')['matricule'];
			$matricule_emp = session('users')['matricule_emp'];

			/**effectuer l'opération selon selon le type de document et le code de l'entreprise*/
			if(!empty($code_type_document) && !empty($matricule_en)){
				
				/**verifier de quel type de document il s'agit*/
				$type_document = $this->type_document($code_type_document, $matricule_en);
				//$query = $this->stock->type_doc_stock($code_type_document);
				if(!empty($type_document)){

					if($type_document=="FAF"){
						
						/**on test pour voir si le document existe déjà ou pas */
						$verify_document = $this->stock->doc_stock($code_type_document, $code_document, $matricule_en);
						if(empty($verify_document)){
							/***************************************** LE DOCUMENT N'EXISTE PAS DEBUT *************************************** */

							/**on creer le document */
							$input_doc = array(
								'code_document'=>$code_document,
								'nom_document'=> 'DOCUMENT'.code(10).date('d-m-Y'),
								'date_creation_doc'=>dates(),
								'code_fournisseur'=> $code_fournisseur,
								'code_type_doc'=> $code_type_document,
								'depot_doc'=>$code_depot,
								'code_employe'=> $matricule_emp,
								'code_entreprie'=> $matricule_en,
							);
							$new_document = $this->stock->new_document($input_doc);
							if($new_document){
								/**on enrégistre l'historique des articles de ce document dans la table article_document*/
								$input_art_doc = array(
									'code_article'=> $code_article,
									'code_document'=> $code_document,
									'quantite'=> $qte,
									'code_emp_art_doc'=> $matricule_emp,
									'code_en_art_doc'=> $matricule_en, 
									'date_creer_art_doc'=> dates()
								);
								$save_art_from_doc = $this->stock->article_document($input_art_doc);
								if($save_art_from_doc){
									/**gestion du stock debut */
										/**verifions voir si l'article existe en stock pour le depot choisi */
										$verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
										if(empty($verify_art_stock)){
											/**l'article n'existe pas */
											$input_stock_val = array(
												'code_article'=> $code_article,
												'code_employe'=> $matricule_emp,
												'code_depot'=> $code_depot,
												'code_entreprise'=> $matricule_en,
												'quantite'=> $qte,
												'date_entrer_stock'=> dates()
											);
											
											$new_art_stock = $this->stock->enter_stock($input_stock_val);
											if($new_art_stock){
												/**liste des articles du document */
												$output = $this->art_doc($code_document, $matricule_en);
												$array = array(
													'success' => 'stocker',
													'art_doc' => $output
												);
											}else{
												$array = $message_db;
											}
										}else{
											/**l'article existe */
											$input_stock_val = array(
												'code_employe'=> $matricule_emp,
												'code_depot'=> $code_depot,
												'code_entreprise'=> $matricule_en,
												'quantite'=> ($qte + $verify_art_stock['quantite']),
												'date_modifier_stock'=> dates()
											);
											$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
											if($update_art_st){
												/**liste des articles du document */
												$output = $this->art_doc($code_document, $matricule_en);
												$array = array(
													'success' => 'stocker',
													'art_doc' => $output
												);
											}else{
												$array = $message_db;
											}
										}
									/**gestion du stock fin */
								}else{
									$array = $message_db;
								}
							}else{
								$array = $message_db;
							}

							/***************************************** LE DOCUMENT N'EXISTE PAS FIN *************************************** */

							/**phase 2 ci_dessous */	
						}else{
							/***************************************** LE DOCUMENT EXISTE DEBUT *************************************** */
							
							/**on test si le document est encore modifiable
							 * il est passible de modifier unique 7 jours apres la creation du document
							*/
							$diff = $this->nbr_jour($verify_document['date_creation_doc']);
							if($diff <= 7){
								/**pour le document encour, on verifi s'il contient l'article encour */
								$verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
								if(empty($verify_art_document)){
									/*******************************l'article n'existe pas dans le document debut *******************/

									/**on enrégistre l'historique des articles de ce document dans la table article_document*/
									$input_art_doc = array(
										'code_article'=> $code_article,
										'code_document'=> $code_document,
										'quantite'=> $qte,
										'code_emp_art_doc'=> $matricule_emp,
										'code_en_art_doc'=> $matricule_en, 
										'date_creer_art_doc'=> dates()
									);
									$save_art_from_doc = $this->stock->article_document($input_art_doc);
									if($save_art_from_doc){
										/**gestion du stock debut */

											/**verifions voir si l'article existe en stock pour le depot choisi */
											$verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
											if(empty($verify_art_stock)){
												/**l'article n'existe pas */
												$input_stock_val = array(
													'code_article'=> $code_article,
													'code_employe'=> $matricule_emp,
													'code_depot'=> $code_depot,
													'code_entreprise'=> $matricule_en,
													'quantite'=> $qte,
													'date_entrer_stock'=> dates()
												);
												
												$new_art_stock = $this->stock->enter_stock($input_stock_val);
												if($new_art_stock){
													/**liste des articles du document */
													$output = $this->art_doc($code_document, $matricule_en);
													$array = array(
														'success' => 'stocker',
														'art_doc' => $output
													);
												}else{
													$array = $message_db;
												}
											}else{
												/**l'article existe */
												$input_stock_val = array(
													'code_employe'=> $matricule_emp,
													'code_depot'=> $code_depot,
													'code_entreprise'=> $matricule_en,
													'quantite'=> ($qte + $verify_art_stock['quantite']),
													'date_modifier_stock'=> dates()
												);
												$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
												if($update_art_st){
													/**liste des articles du document */
													$output = $this->art_doc($code_document, $matricule_en);
													$array = array(
														'success' => 'stocker',
														'art_doc' => $output
													);
												}else{
													$array = $message_db;
												}
											}

										/**gestion du stock fin */
									}else{
										$array = $message_db;
									}

									/******************************* l'article n'existe pas dans le document fin *******************/
								}else{

									/*******************************l'article existe dans le document debut *******************/

									/**verifie que cet utilisateur a le droit de modifier le document (seul le chef d'agence ou l'admin le peu)*/
									if((session('users')['nom_serv'] == "" && session('users')['nom_ag'] == "") || (session('users')['nom_serv'] == "" && session('users')['nom_ag'] != "")|| (session('users')['nom_serv'] != "" && session('users')['nom_ag'] == "")){
									
										/**
										 * 1: on selectionne la quantité en stock de l'article encour en fonction du depot et le code de l'article
										 * 2: on retir la quantite de l'article pour le document selectionner en stock
										 * 3: on update le stock
										*/
										$get_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
										$input_stock_val = array(
											'code_employe'=> $matricule_emp,
											'code_depot'=> $code_depot,
											'code_entreprise'=> $matricule_en,
											'quantite'=> (abs($get_art_stock['quantite'] - $verify_art_document['quantite'])),
											'date_modifier_stock'=> dates()
										);
										$update_art_stock = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
										if($update_art_stock){
											/**on modifie l'article encour dans le document encour (table article_document)*/
											$input_art_doc = array(
												'quantite'=> $qte,
												'code_emp_art_doc'=> $matricule_emp,
												'code_en_art_doc'=> $matricule_en, 
												'date_modifier_art_doc'=> dates()
											);
											$update_art_document = $this->stock->update_article_document($code_document,$code_article,$input_art_doc);
											if($update_art_document){
												/**gestion du stock debut */

													/**verifions voir si l'article existe en stock pour le depot choisi */
													$verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
													if(empty($verify_art_stock)){
														/**l'article n'existe pas */
														$input_stock_val = array(
															'code_article'=> $code_article,
															'code_employe'=> $matricule_emp,
															'code_depot'=> $code_depot,
															'code_entreprise'=> $matricule_en,
															'quantite'=> $qte,
															'date_entrer_stock'=> dates()
														);
														
														$new_art_stock = $this->stock->enter_stock($input_stock_val);
														if($new_art_stock){
															/**liste des articles du document */
															$output = $this->art_doc($code_document, $matricule_en);
															$array = array(
																'success' => 'stocker',
																'art_doc' => $output
															);
														}else{
															$array = $message_db;
														}
													}else{
														/**l'article existe */
														$input_stock_val = array(
															'code_employe'=> $matricule_emp,
															'code_depot'=> $code_depot,
															'code_entreprise'=> $matricule_en,
															'quantite'=> ($qte + $verify_art_stock['quantite']),
															'date_modifier_stock'=> dates()
														);
														$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
														if($update_art_st){
															/**liste des articles du document */
															$output = $this->art_doc($code_document, $matricule_en);
															$array = array(
																'success' => 'stocker',
																'art_doc' => $output
															);
														}else{
															$array = $message_db;
														}
													}
												/**gestion du stock fin */
											}else{
												$array = $message_db;
											}
										}else{
											$array = $message_db;
										}
									}else{
										$array = array(
											'success'   => '
												<script>
													swal.fire("OUPS!","vous n\'êtes pas habilité a modifier une fois l\'écriture pris en compte. contactez le chef d\'entreprise, son adjoin ou le chef d\'agence","error")
												</script>
											'
										);
									}

									/*******************************l'article existe dans le document fin *******************/
								}
								
							}else{
								$array = array(
									'success'   => '
										<script>
											swal.fire("OUPS!","ce document n\'est plus modifiable","error")
										</script>
									'
								);
							}

							/***************************************** LE DOCUMENT EXISTE FIN *************************************** */
						}
					}else if($type_document=="FRF"){

						/*************************************** FACTURE RETOUR FOURNISSUER DEBUT******************************/

							/**on test pour voir si l'article existe en stock pour le depot choisi */
							$verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
							if(!empty($verify_art_stock)){

								/**on s'assure que la quantité entré est <= a la quantité en stock */
								if($qte <= $verify_art_stock['quantite']){
									/**on test pour voir si le document existe déjà */
									$verify_document = $this->stock->doc_stock($code_type_document, $code_document, $matricule_en);
									if(empty($verify_document)){
										/*******************LE DOCUMENT N'EXISTE PAS DEBUT***********************/

										/**on creer le document */
										$input_doc = array(
											'code_document'=>$code_document,
											'nom_document'=> 'DOCUMENT'.code(10).date('d-m-Y'),
											'date_creation_doc'=>dates(),
											'code_fournisseur'=> $code_fournisseur,
											'code_type_doc'=> $code_type_document,
											'depot_doc'=>$code_depot,
											'code_employe'=> $matricule_emp,
											'code_entreprie'=> $matricule_en,
										);
										$new_document = $this->stock->new_document($input_doc);
										if($new_document){
											/**on enrégistre l'historique des articles de ce document dans la table article_document*/
											$input_art_doc = array(
												'code_article'=> $code_article,
												'code_document'=> $code_document,
												'quantite'=> $qte,
												'code_emp_art_doc'=> $matricule_emp,
												'code_en_art_doc'=> $matricule_en, 
												'date_creer_art_doc'=> dates()
											);
											$save_art_from_doc = $this->stock->article_document($input_art_doc);
											if($save_art_from_doc){
												/**faire la sortie de stock pour le retour d'un article debut*/
													$input_stock_val = array(
														'code_employe'=> $matricule_emp,
														'code_depot'=> $code_depot,
														'code_entreprise'=> $matricule_en,
														'quantite'=> (abs($qte - $verify_art_stock['quantite'])),
														'date_modifier_stock'=> dates()
													);
													$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
													if($update_art_st){
														/**liste des articles du document */
														$output = $this->art_doc($code_document, $matricule_en);
														$array = array(
															'success' => 'article sorti du stock',
															'art_doc' => $output
														);
													}else{
														$array = $message_db;
													}

												/**faire la sortie de stock pour le retour d'un article fin*/

											}else{
												$array = $message_db;
											}

										}else{
											$array = $message_db;
										}

										/*******************le document n'existe pas fin***********************/
									}else{
									    
										/*******************LE DOCUMENT EXISTE DEBUT***********************/

										/**on test si le document est encore modifiable
										 * il est passible de modifier unique 7 jours apres la creation du document
										*/
										$diff = $this->nbr_jour($verify_document['date_creation_doc']);
										if($diff <= 7){
											/*************** document modifiable */
											/**pour le document encour, on verifi s'il contient l'article encour */
											$verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
											if(!empty($verify_art_document)){
												/*********** l'article existe dans le document debut  ********************/
												/**verifie que cet utilisateur a le droit de modifier le document (seul le chef d'agence ou l'admin le peu)*/
												if((session('users')['nom_serv'] == "" && session('users')['nom_ag']== "") || (session('users')['nom_serv'] == "" && session('users')['nom_ag'] != "")|| (session('users')['nom_serv'] != "" && session('users')['nom_ag'] == "")){
									
													/**-------------------------- */
													$verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
													/**-------------------------- */

													/**on revoit la quantité precedement retirer en stock*/
													$input_stock_val = array(
														'code_employe'=> $matricule_emp,
														'code_depot'=> $code_depot,
														'code_entreprise'=> $matricule_en,
														'quantite'=> ($verify_art_document['quantite'] + $verify_art_stock['quantite']),
														'date_modifier_stock'=> dates()
													);
													$update_art_stock = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
													if($update_art_stock){
														
														/**on modifie l'article encour dans le document encour (table article_document)*/
														$input_art_doc = array(
															'quantite'=> $qte,
															'code_emp_art_doc'=> $matricule_emp,
															'code_en_art_doc'=> $matricule_en, 
															'date_modifier_art_doc'=> dates()
														);
														$update_art_document = $this->stock->update_article_document($code_document,$code_article,$input_art_doc);
														if($update_art_document){

															/**-------------------------- */
															$verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
															/**-------------------------- */

															/**faire la sortie de stock pour le retour d'un article debut*/
																$input_stock_val = array(
																	'code_employe'=> $matricule_emp,
																	'code_depot'=> $code_depot,
																	'code_entreprise'=> $matricule_en,
																	'quantite'=> (abs($qte - $verify_art_stock['quantite'])),
																	'date_modifier_stock'=> dates()
																);
																$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
																if($update_art_st){
																	/**liste des articles du document */
																	$output = $this->art_doc($code_document, $matricule_en);
																	$array = array(
																		'success' => 'article sorti du stock',
																		'art_doc' => $output
																	);
																}else{
																	$array = $message_db;
																}

															/**faire la sortie de stock pour le retour d'un article fin*/
														}else{
															$array = $message_db;
														}
													}else{
														$array = $message_db;
													}

												}else{
													$array = array(
														'success'   => '
															<script>
																swal.fire("OUPS!","vous n\'êtes pas habilité a modifier une fois l\'écriture pris en compte. contactez le chef d\'entreprise, son adjoin ou le chef d\'agence","error")
															</script>
														'
													);
												}
												/*********** l'article existe dans le document fin  ********************/
											}else{
												/*********** l'article n'existe pas dans le document debut ********************/
												
												/**on enrégistre l'historique des articles de ce document dans la table article_document*/
												$input_art_doc = array(
													'code_article'=> $code_article,
													'code_document'=> $code_document,
													'quantite'=> $qte,
													'code_emp_art_doc'=> $matricule_emp,
													'code_en_art_doc'=> $matricule_en, 
													'date_creer_art_doc'=> dates()
												);
												$save_art_from_doc = $this->stock->article_document($input_art_doc);
												if($save_art_from_doc){
													/**-------------------------- */
													$verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
													/**-------------------------- */

													/**faire la sortie de stock pour le retour d'un article debut*/
														$input_stock_val = array(
															'code_employe'=> $matricule_emp,
															'code_depot'=> $code_depot,
															'code_entreprise'=> $matricule_en,
															'quantite'=> (abs($qte - $verify_art_stock['quantite'])),
															'date_modifier_stock'=> dates()
														);
														$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
														if($update_art_st){
															/**liste des articles du document */
															$output = $this->art_doc($code_document, $matricule_en);
															$array = array(
																'success' => 'article sorti du stock',
																'art_doc' => $output
															);
														}else{
															$array = $message_db;
														}

													/**faire la sortie de stock pour le retour d'un article fin*/
												}else{
													$array = $message_db;
												}


												/*********** l'article n'existe pas dans le document fin ********************/
											}
										}else{
											$array = array(
												'success'   => '
													<script>
														swal.fire("OUPS!","ce document n\'est plus modifiable","error")
													</script>
												'
											);	
										}

										/*******************le document existe fin***********************/
									}

								}else{
									$array = array(
										'success'   => '
											<script>
												swal.fire("ERREUR!","impossible de faire un retour. <b>la quatité entré ne doit pas être supérieur à la quantité en stock</b>","error")
											</script>
										'
									);
								}

							}else{
								$array = array(
									'success'   => '
										<script>
											swal.fire("ERREUR!","impossible de faire un retour fournisseur sur un article qui n\'est pas en stock","error")
										</script>
									'
								);
							}


						/*************************************** FACTURE RETOUR FOURNISSUER DEBUT******************************/
					}else{
						$array = array(
							'success'   => '
								<script>
									swal.fire("erreur survenu!","ce type de document n\'est pas utile ici, utilise le menu de gauche","error")
								</script>
							'
						);
					}
				}else{
					$array = array(
						'success'   => '
							<script>
								swal.fire("OUPS!","ce type de document n\'existe pas","info")
							</script>
						'
					);
				}


			}

			
		}else{
			$array = array(
				'error'   => true,
				'new_quantes_error' => form_error('new_quantes')
			);
		}

		echo json_encode($array);
	}
	/**operation sur le stock fin*/


	/**affiche la liste des documents d'un type de document donnée */
	public function docs_typdoc(){
		$this->logged_in();
		$output = "";

		$date1 = $this->input->post('start');
		$date2 = $this->input->post('end');
		$codetypdoc = $this->input->post('type_doc_1');
		$matricule = session('users')['matricule'];

		/**je converti la date au format Y-m-d*/
		$debut = date("Y-m-d", strtotime($date1));
		$fin = date("Y-m-d", strtotime($date2));

		/**validation des champs */
		if(!empty($codetypdoc) && preg_match('/^[a-zA-Z0-9]+$/',$codetypdoc) ){
			if(empty($date1) && empty($date2)){

				/**selectionne tous les documents en focntion du type de document */
				$query = $this->stock->docs_list($codetypdoc, $matricule);
				if(!empty($query)){
					foreach ($query as $key => $value) {

						$output .='<tr>
									<td>'.$value['code_document'].'</td>
									<td>'.$value['nom_document'].'</td>
									<td>'.$value['nom_four'].'</td>
									<td>'.$value['nom_emp'].'</td>
									<td>'.$value['nom_depot'].'</td>
									<td>'.$value['date_creation_doc'].'</td>
									<td>'.$value['date_modifier_doc'].'</td>
									<td>
										<button type="button" class="btn btn-light btn-elevate-hover btn-pill artdoc" id="'.$value['code_document'].'"><i class="fa fa-eye"></i></button>
									</td>
								</tr>';

						$array = array(
							'content' => $output
						);	
					}
				}else{
					$array = array(
						'content' => '<tr><td colspan="8"><b class="text-danger">aucun document trouvé pour ce type</b></td></tr>'
					);
				}

				

				/**preg_match date au format "Y-m-d"; */
			}else if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$debut) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$fin)){

				/**selectionne tous les documents en focntion du type de document */
				$query = $this->stock->docs_list2($codetypdoc, $matricule, $debut, $fin);
				if(!empty($query)){
					foreach ($query as $key => $value) {

						$output .='<tr>
								<td>'.$value['code_document'].'</td>
								<td>'.$value['nom_document'].'</td>
								<td>'.$value['nom_four'].'</td>
								<td>'.$value['nom_emp'].'</td>
								<td>'.$value['nom_depot'].'</td>
								<td>'.$value['date_creation_doc'].'</td>
								<td>'.$value['date_modifier_doc'].'</td>
								<td>
									<button type="button" class="btn btn-light btn-elevate-hover btn-pill artdoc" id="'.$value['code_document'].'"><i class="fa fa-eye"></i></button>
								</td>
							</tr>';

						$array = array(
							'content' => $output
						);	
					}
				}else{
					$array = array(
						'content' => '<tr><td colspan="8"><b class="text-danger">aucun document trouvé pour ce type, dans cette interval de temp '.$debut.' '.$fin.'</b></td></tr>'
					);
				}

			}else{
				$array = array(
					'success' => '<b class="text-danger">dates non valide</b>'
				);	
			}
		}else{
			$array = array(
				'success' => '<b class="text-danger">document non correct ou vide</b>'
			);
		}

		echo json_encode($array);
	}

	/**affiche la liste des articles  d'un document donnée*/
	public function articles_docucment(){
		$this->logged_in();
		$output = '';
		$codedoc = $this->input->post('code_doc');
		$matricule = session('users')['matricule'];
		$query = $this->stock->articles_docucment($codedoc,$matricule);
		if(!empty($query)){
			foreach ($query as $key => $value) {

				$output .='<tr>
							<td>'.$value['code_bar'].'</td>
							<td>'.$value['designation'].'</td>
							<td>'.$value['reference'].'</td>
							<td>'.$value['quantite'].'</td>
							<td>'.$value['nom_emp'].'</td>
							<td>'.$value['date_creer_art_doc'].'</td>
							<td>'.$value['date_modifier_art_doc'].'</td>
						</tr>';

				$array = array(
					'content' => $output
				);	
			}
		}else{
			$output .='article non trouver';
		}

		echo json_encode($output);
	}

	/**imprime la liste des article d'un document donnée */
	public function print_arts_docuc($code_doc = null){
		$this->logged_in();
		$this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

		$codedoc = $code_doc;
		if(!empty($codedoc)){
			$matricule = session('users')['matricule'];
			$query = $this->stock->articles_docucment($codedoc,$matricule);

			// Create an instance of the class:
			$mpdf = new \Mpdf\Mpdf();

			$mpdf->SetHTMLHeader('
			
				<center style="text-align: right; font-weight: bold;">
					<h2>'.strtoupper(session('users')['nom']).'</h2>
				</center>
			');

			$mpdf->SetHTMLFooter('
				<table width="100%">
					<tr>
						<td width="33%">{DATE j-m-Y H:i:sa}</td>
						<td width="33%" align="center">{PAGENO}/{nbpg}</td>
						<td width="33%" style="text-align: right;">Document: '.$codedoc.'</td>
					</tr>
				</table>
			');

			$typedoc = $this->stock->type_document($codedoc,$matricule);
			$depotinitiateur = $this->stock->namedepotinitandrecep($typedoc['depot_init_doc']);
			$depotrecepteur = $this->stock->namedepotinitandrecep($typedoc['depot_recept_doc']);
			
			if(!empty($typedoc)){
				if($typedoc['abrev_doc'] == 'FAF'){
					$mpdf->WriteHTML('<br><br> <u><h3 style="text-align: center; font-weight: bold;">'.strtoupper('Document d\'entré en stock').'</h3></u> <br><br>');
				}
				if($typedoc['abrev_doc'] == 'MVT-E'){
					$mpdf->WriteHTML('<br><br> <u><h3 style="text-align: center; font-weight: bold;">'.strtoupper('Mouvement d\'entré en stock').'</h3></u> <br><br>');
				}
				if($typedoc['abrev_doc'] == 'MVT-S'){
					$mpdf->WriteHTML('<br><br> <u><h3 style="text-align: center; font-weight: bold;">'.strtoupper('Mouvement de sortie en stock').'</h3></u> <br><br>');
				}
				if($typedoc['abrev_doc'] == 'FRF'){
					$mpdf->WriteHTML('<br><br> <u><h3 style="text-align: center; font-weight: bold;">'.strtoupper('FACTURE RETOUR FOURNISSEUR').'</h3></u> <br><br>');
				}
				if($typedoc['abrev_doc'] == 'DT'){
					$mpdf->WriteHTML('<br><br> <u><h3 style="text-align: center; font-weight: bold;">'.strtoupper('FACTURE de transfert de stock').' <br/> '.$depotinitiateur['nom_depot'].' Vers '.$depotrecepteur['nom_depot'].'</h3></u> <br><br>');
					/*[depot_init_doc] => 21G31G52M74P
                    [depot_recept_doc] => 21DTTR16GN8Q*/
				}
				if($typedoc['abrev_doc'] == 'DI'){
					$mpdf->WriteHTML('<br><br> <u><h3 style="text-align: center; font-weight: bold;">'.strtoupper('DOCUMENT D\'INVENTAIRE DE STOCK').'</h3></u> <br><br>');
				}
			}
			


			// Write some HTML code:
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
					<h4><u>Articles Du Document: '.$codedoc.'</u></h4>
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
						<th>Désignation</th>
						<th>Code à bar</th>
						<th>Référence</th>');
	if($typedoc['abrev_doc'] == 'DI'){
		$mpdf->WriteHTML('<th>Quatite Avant Inv</th>');
	}
		$mpdf->WriteHTML('	
						<th>Quatite</th>			
						</tr>
						');

					

				if(!empty($query)){
					foreach ($query as $key => $value) {
						$mpdf->WriteHTML('
						<tr>
							<td>'.$value['designation'].'</td>
							<td>'.$value['code_bar'].'</td>
							<td>'.$value['reference'].'</td>');
						if($typedoc['abrev_doc'] == 'DI'){
							$mpdf->WriteHTML('<td>'.$value['qte_avant_inventaire'].'</td>');
						}
						$mpdf->WriteHTML('
							<td>'.$value['quantite'].'</td>
						</tr>
						');
					}
				}
			$mpdf->WriteHTML('</table>');

			$mpdf->WriteHTML('<br><br><br><br><br><br> <span><u>signature1</u></span>------------------------------------------------------------------------------------------------------------------------<span><u>signature2</u></span>');

			// Output a PDF file directly to the browser
			//$mpdf->Output();
			$mpdf->Output('articles_document '.code(2).'.pdf', \Mpdf\Output\Destination::INLINE);
		}else{
			echo '
				<script>
					swal.fire({
						position:"top-right",
						type:"success",
						title:"erreur survenu contactez l\'administrateur",
						showConfirmButton:!1,timer:1500
					})
				</script>
			';
		}
		

		$this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}

	/**gestion des entrées et sortie en stock fin */


	/**gestion des mouvement d'entrer debut  */

	/**mouvement d'entre */
	public function mouvement_article($code_type_doc = NULL){
		$this->logged_in();
		$output = "";

        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');


		/**matricule de l'entreprise */
		$matricule = session('users')['matricule'];

		/**affiche la liste des type de documents pour une entreprise donné */
		$data['docs'] = $this->stock->type_doc($matricule);
		//$data['docs'] = $this->document->get_doc($matricule);

		/**affiche la liste des dépots */
		$data['depots'] = $this->stock->get_all_depot2($matricule);

		/**affiche la liste des fournisseurs d'une entreprise donné */
		$data['fournisseurs'] = $this->fournisseur->get_all_fournisseur($matricule);


		/**affiche la liste des articles */
		$data['articles'] = $this->stock->get_all_article($matricule);


		/**afficher le code et le nom d'un document générer*/
		$data['code_doc'] = array(
			'DOC'.code(10) => 'DOCUMENT'.code(10).''.date('d-m-Y'),
		);
		
		/**selection de la list des documents en fonction du type de document */
		if(!empty($code_type_doc)){
			$output = $this->stock->get_doc_type($code_type_doc);
		}
		$data['get_docs'] = $output;

		$this->load->view('stock/mouvement',$data);


        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}

	/**affiche le formulaire pour faire les vmt E/S */
	public function form_mouvement(){
		$this->logged_in();
		$output ="";
		$this->form_validation->set_rules('type_doc_2', 'type document', 'required|regex_match[/^[a-zA-Z0-9]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('depot', 'depot', 'required|regex_match[/^[a-zA-Z0-9]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('motif', 'motif', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('article', 'article', 'required|regex_match[/^[a-zA-Z0-9]+$/]',array(
			'required' => 'l\' %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('raison', 'raison', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ,;:\'\-\n\r ]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));
		
		$this->form_validation->set_rules('document', 'document', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		
		
		if ($this->form_validation->run()){
			
			$code_art = $this->input->post('article');
			$code_document = $this->input->post('document');
			$depot = $this->input->post('depot');
			$matricule_en = session('users')['matricule'];

			/**on verifie si l'article est dans le stock pour le depot choisi
			 * 
			 * car on ne peut pas faire un mouvement 
			 * d'entrer ou de sortie sur un article qui n'est pas en stock pour le depot choisi
			*/

			$query = $this->stock->get_article_stock($code_art,$depot);
			if(!empty($query)){
				/**liste des articles du document */
				$output = $this->art_doc($code_document, $matricule_en);
				$array = array(
					'success'   => 'article trouvé',
					'article'   => $query,
					'art_doc' => $output
				);
			}else{
				$array = array(
					'message'   => '
						<script>
							swal.fire("erreur survenu!","cet article n\'est pas disponible en stock pour ce depot! NB: <b>on ne peut pas faire un mouvement d\'entrer ou de sorti sur un article qui n\'est pas en stock pour ce depot</b>","error")
						</script>
					'
				);
			}
			
		}else{
			$array = array(
				'error'   => true,
				'type_doc_2_error' => form_error('type_doc_2'),
				'depot_error' => form_error('depot'),
				'motif_error' => form_error('motif'),
				'article_error' => form_error('article'),
				'raison_error' => form_error('raison'),
				'document_error' => form_error('document'),
			);
		}

		echo json_encode($array);

	}



	/**opération sur les mouvement dentré et de sortie */
	public function operation_mouvement(){
		$this->logged_in();
		$output ="";
		$this->form_validation->set_rules('new_quantes', 'quantité', 'required|regex_match[/^[0-9]+$/]',array(
			'required' => 'entre la %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		if($this->form_validation->run()){
			
			$code_type_document = $this->input->post('t_doc');

			$code_depot = trim($this->input->post('dep'));
			$motif = trim($this->input->post('motif_event'));
			$code_article = trim($this->input->post('art'));
			$qte = trim($this->input->post('new_quantes'));
			$raison = trim($this->input->post('raison_event'));
			$code_document = trim($this->input->post('doc_m'));

			$matricule_en = session('users')['matricule'];
			$matricule_emp = session('users')['matricule_emp'];

		
			/**effectuer l'opération selon selon le type de document */
			if(!empty($code_type_document)){

				/**verifier le type de document */
				$query = $this->stock->type_doc_stock($code_type_document,$matricule_en);
				if(!empty($query)){
					if($query['abrev_doc']=="MVT-E"){

						/***pour faire un mouvement d'entré, on verifie si l'article est en stock
						 * car on ne peu pas faire un mouvement d'entrer sur un article qui n'est pas en stock
						 * 
						 * nb: on test donc pour voir si l'article existe déjà
						 */

						$query_art_s = $this->stock->get_article_stock($code_article,$code_depot);
						if(!empty($query_art_s)){

							/**on test le document, si le document n'existe pas, on le créer s'il existe on le modifie 
							 * 
							 * NB: la selection du document se fait selon son type et son code
							*/
							
							$query_d = $this->stock->doc_stock($code_type_document,$code_document,$matricule_en);
							if(empty($query_d)){
								/*************************************LE DOCUMENT N'EXISTE PAS DEBUT****************************** */

								/**on creer un nouveau*/
								$input_doc = array(
									'code_document'=>$code_document,
									'nom_document'=> 'DOCUMENT'.code(10).date('d-m-Y'),
									'motif' => $motif,
									'raison' => $raison,
									'date_creation_doc'=>dates(),
									'depot_doc'=>$code_depot,
									'code_type_doc'=> $code_type_document,
									'code_employe'=> $matricule_emp,
									'code_entreprie'=> $matricule_en,
								);
								$query_d = $this->stock->new_document($input_doc);
								if($query_d){
									/**on enrégistre l'historique des articles de ce document dans la table article_document*/
									$input_art_doc = array(
										'code_article'=> $code_article,
										'code_document'=> $code_document,
										'quantite'=> $qte,
										'code_emp_art_doc'=> $matricule_emp,
										'code_en_art_doc'=> $matricule_en, 
										'date_creer_art_doc'=> dates()
									);

									$query_art_d = $this->stock->article_document($input_art_doc);
									if($query_art_d){
										/**mettre a jour le stock maintenant*/
										$input_stock_val = array(
											'code_employe'=> $matricule_emp,
											'code_depot'=> $code_depot,
											'code_entreprise'=> $matricule_en,
											'quantite'=> ($qte + $query_art_s['quantite']),
											'date_modifier_stock'=> dates()
										);
										
										$query_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
										if($query_art_st){
											/**liste des articles du document */
											$output = $this->art_doc($code_document, $matricule_en);
											$array = array(
												'success'   => 'stocker',
												'art_doc' => $output
											);
											
										}else{
											$array = array(
												'success'   => 'connexion à la base des données perdu'
											);
										}
									}else{
										$array = array(
											'success'   => ' connexion à la base des données perdu'
										);
									}
								}else{
									$array = array(
										'success'   => ' connexion à la base des données perdu'
									);
								}

								/*************************************LE DOCUMENT N'EXISTE PAS fIN****************************** */
							}else{
								/*************************************LE DOCUMENT EXISTE DEBUT****************************** */

								/**si le document existe on fait ce qui suit: */

								/**on verifie si le document est encore modifiable */
								$diff = $this->nbr_jour($query_d['date_creation_doc']);

								if($diff <= 30){
									/**comme le document est modifiable:
									 * je verifie si l'article existe dans sa liste d'article (historique d'article du document)
									 * s'il n'existe pas, on le creer si non on la modifie
									 */

									 /**on met ceci ici pour s'assurer de la conformité des données mme comme l'execution est séquentiel */
									$query_d = $this->stock->doc_stock($code_type_document,$code_document,$matricule_en);
									
									$query_art_doc = $this->stock->get_art_doc($code_article,$query_d['code_document'],$matricule_en);
									if(empty($query_art_doc)){

										/*******************************L'ARTICLE N'EXISTE PAS DANS LE DOCUMENT DEBUT *******************************/

										/**on enrégistre l'historique des articles de ce document dans la table article_document*/
										$input_art_doc = array(
											'code_article'=> $code_article,
											'code_document'=> $code_document,
											'quantite'=> $qte,
											'code_emp_art_doc'=> $matricule_emp,
											'code_en_art_doc'=> $matricule_en, 
											'date_creer_art_doc'=> dates()
										);

										$query_art_d = $this->stock->article_document($input_art_doc);
										if($query_art_d){
											/**faire le mouvement d'entrer en stock d'un article debut*/

												/**on creer cette redondence pour eviter les pertes de données */
												$query_art_s = $this->stock->get_article_stock($code_article,$code_depot);

												/**mettre a jour le stock maintenant*/
												$input_stock_val = array(
													'code_employe'=> $matricule_emp,
													'code_depot'=> $code_depot,
													'code_entreprise'=> $matricule_en,
													'quantite'=> ($qte + $query_art_s['quantite']),
													'date_modifier_stock'=> dates()
												);
												
												$query_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
												if($query_art_st){
													/**liste des articles du document */
													$output = $this->art_doc($code_document, $matricule_en);
													$array = array(
														'success'   => 'stocker',
														'art_doc' => $output
													);
												}else{
													$array = array(
														'success'   => 'connexion à la base des données perdu'
													);
												}

											/**faire le mouvement d'entrer en stock d'un article fin*/
										}else{
											$array = array(
												'success'   => ' connexion à la base des données perdu'
											);
										}
										/*******************************L'ARTICLE N'EXISTE PAS DANS LE DOCUMENT FIN *******************************/
									}else{

										/*******************************L'ARTICLE EXISTE DANS LE DOCUMENT DEBUT *******************************/
										
										/**verifie que cet utilisateur a le droit de modifier le document (seul le chef d'agence ou l'admin le peu)*/
										if((session('users')['nom_serv'] == "" && session('users')['nom_ag'] != "") || (session('users')['nom_ag'] == "" && session('users')['nom_serv'] == "")){
										
											/**on met à jour l'historique des articles de ce document dans la table article_document*/

											/**on retire l'encienne quantité avant d'ajouter la nouvelle quantité(entrer en stock)*/
											$qte_art_s = $this->stock->get_article_stock($query_art_doc['code_article'],$code_depot);
											
											$input_stock_val = array(
												'code_employe'=> $matricule_emp,
												'code_depot'=> $code_depot,
												'code_entreprise'=> $matricule_en,
												'quantite'=> (abs($qte_art_s['quantite'] - $query_art_doc['quantite'])),
												'date_modifier_stock'=> dates()
											);
											
											$query_art_st = $this->stock->update_stock2($query_art_doc['code_article'], $input_stock_val,$code_depot);
											if($query_art_st){
												
												/**on modifie l'article dans le document selectionné */
												$input_art_doc = array(
													'quantite'=> $qte,
													'code_emp_art_doc'=> $matricule_emp,
													'code_en_art_doc'=> $matricule_en, 
													'date_modifier_art_doc'=> dates()
												);
		
												$query_art_d = $this->stock->update_article_document($query_art_doc['code_document'],$query_art_doc['code_article'],$input_art_doc);
												if($query_art_d){
													
													/**on check a nouveau la quantité pour modifier le mouvement */
													$qte_art_s = $this->stock->get_article_stock($query_art_doc['code_article'],$code_depot);

													/**mettre a jour le stock maintenant*/
													$input_stock_val = array(
														'code_employe'=> $matricule_emp,
														'code_depot'=> $code_depot,
														'code_entreprise'=> $matricule_en,
														'quantite'=> ($qte + $qte_art_s['quantite']),
														'date_modifier_stock'=> dates()
													);
													
													$query_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
													if($query_art_st){
														/**liste des articles du document */
														$output = $this->art_doc($code_document, $matricule_en);
														$array = array(
															'success'   => 'stocker',
															'art_doc' => $output
														);
													}else{
														$array = array(
															'success'   => ' connexion à la base des données perdu'
														);
													}
												}else{
													$array = array(
														'success'   => 'connexion à la base des données perdu'
													);
												}
											}else{
												$array = array(
													'success'   => ' connexion à la base des données perdu'
												);
											}
										}else{
											$array = array(
												'success'   => '
													<script>
														swal.fire("OUPS!","vous n\'êtes pas habilité a modifier une fois l\'écriture pris en compte. contactez le chef d\'entreprise, son adjoin ou le chef d\'agence","error")
													</script>
												'
											);
										}

										/*******************************L'ARTICLE EXISTE DANS LE DOCUMENT FIN *******************************/

									}
								}else{
									$array = array(
										'success'   => 'ce document n\'est plus modifiable'
									);
								}

								/*************************************LE DOCUMENT EXISTE FIN****************************** */
							}

						}else{
							$array = array(
								'success'   => '
									<script>
										swal.fire("erreur survenu!","on ne peut pas faire un mouvement d\'entré sur un article qui n\'est pas en stock","error")
									</script>
								'
							);
						}

					}else if($query['abrev_doc']=="MVT-S"){

						/**on fait les mouvement de sorti unique sur les article qui sont deja en stock 
						 * NB: on test donc pour savoir si l'article est en stcok
						*/

						$query_art_s = $this->stock->get_article_stock($code_article,$code_depot);
						if(!empty($query_art_s)){
							/**la quantité entré doit être inférieur ou égale a la quantité en stock */
							if($query_art_s['quantite'] >= $qte){

								/**on test le document, si le document n'existe pas, on le créer s'il existe on le modifie 
								 * 
								 * NB: la selection du document se fait selon son type et son code
								*/

								$query_d = $this->stock->doc_stock($code_type_document,$code_document,$matricule_en);
								if(empty($query_d)){
									/************************************** LE DOCUMENT N'EXISTE PAS DEBUT ****************************** */

									/**si le document n'existe pas on fait ce qui suit:
									 * 
									 * on creer un nouveau dans lequel on vas mettre les articles
									*/
									$input_doc = array(
										'code_document'=>$code_document,
										'nom_document'=> 'DOCUMENT'.code(10).date('d-m-Y'),
										'motif' => $motif,
										'raison' => $raison,
										'date_creation_doc'=>dates(),
										'depot_doc'=>$code_depot,
										'code_type_doc'=> $code_type_document,
										'code_employe'=> $matricule_emp,
										'code_entreprie'=> $matricule_en,
									);
									$query_d = $this->stock->new_document($input_doc);
									if($query_d){
										/**on enrégistre l'historique des articles de ce document dans la table article_document*/
										$input_art_doc = array(
											'code_article'=> $code_article,
											'code_document'=> $code_document,
											'quantite'=> $qte,
											'code_emp_art_doc'=> $matricule_emp,
											'code_en_art_doc'=> $matricule_en, 
											'date_creer_art_doc'=> dates()
										);

										$query_art_d = $this->stock->article_document($input_art_doc);
										if($query_art_d){
											/**mettre a jour le stock maintenant*/
											$input_stock_val = array(
												'code_employe'=> $matricule_emp,
												'code_depot'=> $code_depot,
												'code_entreprise'=> $matricule_en,
												'quantite'=> (abs($qte - $query_art_s['quantite'])),
												'date_modifier_stock'=> dates()
											);
											
											$query_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
											if($query_art_st){
												/**liste des articles du document */
												$output = $this->art_doc($code_document, $matricule_en);
												$array = array(
													'success'   => 'déstocker',
													'art_doc' => $output
												);
											}else{
												$array = array(
													'success'   => 'connexion à la base des données perdu'
												);
											}
										}else{
											$array = array(
												'success'   => 'connexion à la base des données perdu'
											);
										}
									}else{
										$array = array(
											'success'   => 'connexion à la base des données perdu'
										);
									}

									/**************************************LE DOCUMENT N'EXISTE PAS FIN ****************************** */
								}else{

									/**************************************LE DOCUMENT EXISTE DEBUT ****************************** */

									/**si le document existe on fait ce qui suit: */

									/**on verifie si le document est encore modifiable */
									$diff = $this->nbr_jour($query_d['date_creation_doc']);
									if($diff <= 1){
										/**comme le document est modifiable:
										 * je verifie si l'article existe dans sa liste d'article (historique d'article du document)
										 * s'il n'existe pas, on le creer si non on la modifie
										 */

										/**on met ceci ici pour s'assurer de la conformité des données même comme l'execution est séquentiel */
										$query_d = $this->stock->doc_stock($code_type_document, $code_document, $matricule_en);

										$query_art_doc = $this->stock->get_art_doc($code_article,$query_d['code_document'],$matricule_en);
										if(empty($query_art_doc)){

											/****************************************** L'ARTICLE N'EXISTE PAS DEBUT ****************************** */

											/**on enrégistre l'historique des articles de ce document dans la table article_document*/
											$input_art_doc = array(
												'code_article'=> $code_article,
												'code_document'=> $code_document,
												'quantite'=> $qte,
												'code_emp_art_doc'=> $matricule_emp,
												'code_en_art_doc'=> $matricule_en, 
												'date_creer_art_doc'=> dates()
											);

											$query_art_d = $this->stock->article_document($input_art_doc);
											if($query_art_d){

												/**faire le mouvement de sortie en stock d'un article debut*/

													/**on creer cette redondence pour eviter les pertes de données */
													$query_art_s = $this->stock->get_article_stock($code_article,$code_depot);

													/**mettre a jour le stock maintenant*/
													$input_stock_val = array(
														'code_employe'=> $matricule_emp,
														'code_depot'=> $code_depot,
														'code_entreprise'=> $matricule_en,
														'quantite'=> (abs($qte - $query_art_s['quantite'])),
														'date_modifier_stock'=> dates()
													);
													
													$query_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
													if($query_art_st){
														/**liste des articles du document */
														$output = $this->art_doc($code_document, $matricule_en);
														$array = array(
															'success'   => 'déstocker',
															'art_doc' => $output
														);
													}else{
														$array = array(
															'success'   => 'connexion à la base des données perdu'
														);
													}

												/**faire le mouvement de sorti en stock d'un article fin*/

											}else{
												$array = array(
													'success'   => ' connexion à la base des données perdu'
												);
											}

											/******************************************L'ARTICLE N'EXISTE PAS FIN****************************** */
										}else{
											/******************************************L'ARTICLE EXISTE DEBUT****************************** */

											/**verifie que cet utilisateur a le droit de modifier le document (seul le chef d'agence ou l'admin le peu)*/
											if((session('users')['nom_serv'] == "" && session('users')['nom_ag'] != "") || (session('users')['nom_ag'] == "" && session('users')['nom_serv'] == "")){
												
												/**on met à jour l'historique des articles de ce document dans la table article_document*/

												/**on remet l'encienne quantité avant d'enlever la nouvelle quantité(entrer en stock)*/
												$qte_art_s = $this->stock->get_article_stock($query_art_doc['code_article'],$code_depot);
												
												$input_stock_val = array(
													'code_employe'=> $matricule_emp,
													'code_depot'=> $code_depot,
													'code_entreprise'=> $matricule_en,
													'quantite'=> ($qte_art_s['quantite'] + $query_art_doc['quantite']),
													'date_modifier_stock'=> dates()
												);
												
												$query_art_st = $this->stock->update_stock2($query_art_doc['code_article'], $input_stock_val,$code_depot);
												if($query_art_st){
													
													/**on modifie l'article dans le document selectionné */
													$input_art_doc = array(
														'quantite'=> $qte,
														'code_emp_art_doc'=> $matricule_emp,
														'code_en_art_doc'=> $matricule_en, 
														'date_modifier_art_doc'=> dates()
													);
			
													$query_art_d = $this->stock->update_article_document($query_art_doc['code_document'],$query_art_doc['code_article'],$input_art_doc);
													if($query_art_d){
														
														/**on check a nouveau la quantité pour modifier le mouvement */
														$qte_art_s = $this->stock->get_article_stock($query_art_doc['code_article'],$code_depot);

														/**mettre a jour le stock maintenant*/
														$input_stock_val = array(
															'code_employe'=> $matricule_emp,
															'code_depot'=> $code_depot,
															'code_entreprise'=> $matricule_en,
															'quantite'=> (abs($qte - $qte_art_s['quantite'])),
															'date_modifier_stock'=> dates()
														);
														
														$query_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
														if($query_art_st){
															/**liste des articles du document */
														$output = $this->art_doc($code_document, $matricule_en);
														$array = array(
															'success'   => 'déstocker',
															'art_doc' => $output
														);
														}else{
															$array = array(
																'success'   => 'connexion à la base des données perdu'
															);
														}
													}else{
														$array = array(
															'success'   => 'connexion à la base des données perdu'
														);
													}
												}else{
													$array = array(
														'success'   => ' connexion à la base des données perdu'
													);
												}

											}else{
												$array = array(
													'success'   => '
														<script>
															swal.fire("OUPS!","vous n\'êtes pas habilité a modifier une fois l\'écriture pris en compte. contactez le chef d\'entreprise, son adjoin ou le chef d\'agence","error")
														</script>
													'
												);
											}
											/******************************************L'ARTICLE EXISTE FIN****************************** */
										}
									}else{
										$array = array(
											'success'   => 'ce document n\'est plus modifiable'
										);
									}
									
									/**************************************LE DOCUMENT EXISTE FIN ****************************** */
								}
							}else{
								$array = array(
									'success'   => '
										<script>
											swal.fire("erreur survenu!","la quantité saisi doit être inférieur ou égale à la quantité de l\'article en stock","error")
										</script>
									'
								);
							}
						}else{
							$array = array(
								'success'   => '
									<script>
										swal.fire("erreur survenu!","on ne peut pas faire un mouvement d\'entré sur un article qui n\'est pas en stock","error")
									</script>
								'
							);
						}
					}else{
						$array = array(
							'success'   => '
								<script>
									swal.fire("erreur survenu!","ce type de document n\'est pas utile ici, utilise le menu de gauche","error")
								</script>
							'
						);
					}
				}else{
					$array = array(
						'success'   => 'les système ne retrouve pas ce type de document'
					);	
				}
			}
		}else{
			$array = array(
				'error'   => true,
				'new_quantes_error' => form_error('new_quantes')
			);	
		}

		echo json_encode($array);
	}


	/**gestion des mouvement d'entrer fin */


	/**gestion des transferts et reception de stock debut */

	/**affiche la page des transferts et reception de stock */
	public function transfert_stock($code_type_doc = NULL){
		$this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');
		$output = "";
			
		/**matricule de l'entreprise */
		$matricule = session('users')['matricule'];

		/**affiche la liste des type de documents pour une entreprise donné */
		$data['docs'] = $this->stock->type_doc($matricule);
		//$data['docs'] = $this->document->get_doc($matricule);

		/**affiche la liste des dépots */
		$data['depots'] = $this->stock->get_all_depot2($matricule);

		/**affiche la liste des fournisseurs d'une entreprise donné */
		$data['fournisseurs'] = $this->fournisseur->get_all_fournisseur($matricule);


		/**affiche la liste des articles */
		$data['articles'] = $this->stock->get_all_article($matricule);


		/**afficher le code et le nom d'un document générer*/
		$data['code_doc'] = array(
			'DOC'.code(10) => 'DOCUMENT'.code(10).''.date('d-m-Y'),
		);
		
		/**selection de la list des documents en fonction du type de document */
		if(!empty($code_type_doc)){
			$output = $this->stock->get_doc_type($code_type_doc);
		}
		$data['get_docs'] = $output;

		$this->load->view('stock/transfert_stock',$data);


        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}

	/**affiche le formulaire pour mettre  les quantitées a transferer */
	public function form_transfert(){
		$this->logged_in();

		$this->form_validation->set_rules('type_doc_2', 'type document', 'required|regex_match[/^[a-zA-Z0-9]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('depot_i', 'depot', 'required|regex_match[/^[a-zA-Z0-9]+$/]|differs[depot_r]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)',
			'differs' => 'le %s initiateur doit être différent du %s recepteur'
		));

		$this->form_validation->set_rules('depot_r', 'depot', 'required|regex_match[/^[a-zA-Z0-9]+$/]|differs[depot_i]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)',
			'differs' => 'le %s recepteur doit être différent du %s initiateur'
		));

		$this->form_validation->set_rules('motif', 'motif', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('article', 'article', 'required|regex_match[/^[a-zA-Z0-9]+$/]',array(
			'required' => 'l\' %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('raison', 'raison', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ,;:\'\-\n\r ]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));
		
		$this->form_validation->set_rules('document', 'document', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'le %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		if ($this->form_validation->run()){
			$code_art = $this->input->post('article');
			$code_depot = $this->input->post('depot_i');
			//$query = $this->stock->get_art_stock($code_art);

			$query = $this->stock->get_article_stock($code_art,$code_depot);
			if(!empty($query)){
				$array = array(
					'success'   => 'article trouvé '.$query['code_bar'],
					'article'   => $query
				);
			}else{
				$array = array(
					'info'   => 'cet article n\'est pas en stock pour ce depot initiateur'
				);
			}
		}else{
			$array = array(
				'error'   => true,
				'type_doc_2_error' => form_error('type_doc_2'),
				'depot_r_error' => form_error('depot_r'),
				'depot_i_error' => form_error('depot_i'),
				'motif_error' => form_error('motif'),
				'article_error' => form_error('article'),
				'raison_error' => form_error('raison'),
				'document_error' => form_error('document'),
			);
		}

		echo json_encode($array);
	}


	/**opérations sur le processus de transfert des articles d'un depot a un autre */
	public function operation_transfert(){
		$this->logged_in();
		$this->form_validation->set_rules('new_quantes', 'quantité', 'required|regex_match[/^[0-9]+$/]',array(
			'required' => 'entre la %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		if($this->form_validation->run()){

			$code_type_document = $this->input->post('t_doc');
			$code_depot_i = trim($this->input->post('dep_i'));
			$code_depot_r = trim($this->input->post('dep_r'));
			$motif = trim($this->input->post('motif_event'));
			$code_document = trim($this->input->post('doc_m'));
			$code_article = trim($this->input->post('art'));
			$raison = trim($this->input->post('raison_event'));
			$qte = trim($this->input->post('new_quantes'));
			
			$matricule_en = session('users')['matricule'];
			$matricule_emp = session('users')['matricule_emp'];

			/**effectuer l'opération selon selon le type de document */
			if(!empty($code_type_document)){
				/********************************** VERIFIER LE TYPE DE DOCUMENT ************************* */
				$query = $this->stock->type_doc_stock($code_type_document,$matricule_en);
				if(!empty($query)){
					if($query['abrev_doc']=="DT"){

						/***pour faire un transfert de stock, on verifie si l'article est en stock
						 * car on ne peu pas faire un transfert sur un article qui n'est pas en stock
						 * 
						 * nb: on test donc pour voir si l'article existe déjà
						*/

						$query_art_s = $this->stock->get_article_stock($code_article,$code_depot_i);
						if(!empty($query_art_s)){
							/**la quantité saisi doit être inférieur ou égale a la quantité en stock de l'article pour le depot initiateur*/
							if($qte <= $query_art_s['quantite']){

								/**on test le code du document pour voir si le document existe déjà, si le document n'existe pas, on le créer s'il existe on le modifie 
								 * 
								 * NB: la selection du document se fait selon son type et son code
								*/

								$query_d = $this->stock->doc_stock($code_type_document,$code_document,$matricule_en);
								if(empty($query_d)){
									/**le document n'existe pas on le creer*/
									$input_doc = array(
										'code_document'=>$code_document,
										'nom_document'=> 'DOCUMENT'.code(10).date('d-m-Y'),
										'motif' => $motif,
										'raison' => $raison,
										'etat_document' => 0,
										'depot_init_doc' => $code_depot_i,
										'depot_recept_doc' => $code_depot_r,
										'date_creation_doc'=>dates(),
										'code_type_doc'=> $code_type_document,
										'code_employe'=> $matricule_emp,
										'code_entreprie'=> $matricule_en,
									);
									$query_d = $this->stock->new_document($input_doc);
									if($query_d){
										/**on enrégistre l'historique des articles de ce document dans la table article_document*/
										$input_art_doc = array(
											'code_article'=> $code_article,
											'code_document'=> $code_document,
											'quantite'=> $qte,
											'code_emp_art_doc'=> $matricule_emp,
											'code_en_art_doc'=> $matricule_en, 
											'date_creer_art_doc'=> dates()
										);

										$query_art_d = $this->stock->article_document($input_art_doc);
										if($query_art_d){
											/**retirer la quantité d'article a transferer et le mettre dans le stock cible debut */

											/**je check a nouveau le stock sur la quantité en stock du depot initial on enleve la quantité a transferer*/
											$query_art_s = $this->stock->get_article_stock($code_article,$code_depot_i);
											$input_stock_val = array(
												'code_employe'=> $matricule_emp,
												'code_depot'=> $code_depot_i,
												'code_entreprise'=> $matricule_en,
												'quantite'=> (abs($qte - $query_art_s['quantite'])),
												'date_modifier_stock'=> dates()
											);
											$query_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot_i);
											if($query_art_st){
												/**le stock est transferer et est en attente d'aprobation du recepteur*/
												$array = array(
													'success'   => 'stock transférer'
												);
											}else{
												$array = array(
													'success'   => 'connexion à la base des données perdu'
												);	
											}

											/**retirer la quantité d'article a transferer et le mettre dans le stock cible debut */
										}else{
											$array = array(
												'success'   => ' connexion à la base des données perdu'
											);
										}
									}else{
										$array = array(
											'success'   => 'connexion à la base des données perdu'
										);
									}
								}else{
									/*****************************************LE DOCUMENT EXISTE ON LE MODIFIE ***************************/
									
									/**on verifie si le document est encore modifiable */

									$diff = $this->nbr_jour($query_d['date_creation_doc']);

									if($diff <= 7){
										/**comme le document est modifiable:
										 * je verifie si l'article existe dans sa liste d'article (historique d'article du document)
										 * s'il n'existe pas, on le creer si non on la modifie
										 */

										/**on met ceci ici pour s'assurer de la conformité des données mme comme l'execution est séquentiel 
										 * 
										 * Nb: on teste a nouveau si le document existe
										*/
										$query_d = $this->stock->doc_stock($code_type_document, $code_document, $matricule_en);

										/**on test si le l'article existe dans le document
										 * Nb: le test se fait en fonction du code de l'article et le code du document
										 */
										$query_art_doc = $this->stock->get_art_doc($code_article, $query_d['code_document'], $matricule_en);
										if(empty($query_art_doc)){
											/**on enrégistre l'historique des articles de ce document dans la table article_document*/
											$input_art_doc = array(
												'code_article'=> $code_article,
												'code_document'=> $code_document,
												'quantite'=> $qte,
												'code_emp_art_doc'=> $matricule_emp,
												'code_en_art_doc'=> $matricule_en, 
												'date_creer_art_doc'=> dates()
											);
											$query_art_d = $this->stock->article_document($input_art_doc);
											if($query_art_d){
												/**retirer la quantité d'article a transferer et le mettre dans le stock cible debut */

												/**je check a nouveau le stock sur la quantité en stock du depot initial on enleve la quantité a transferer*/
												$query_art_s = $this->stock->get_article_stock($code_article,$code_depot_i);
												$input_stock_val = array(
													'code_employe'=> $matricule_emp,
													'code_depot'=> $code_depot_i,
													'code_entreprise'=> $matricule_en,
													'quantite'=> (abs($qte - $query_art_s['quantite'])),
													'date_modifier_stock'=> dates()
												);
												
												$query_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot_i);
												if($query_art_st){

													/**article transferer et en attente pour aprobation */
													$array = array(
														'success'   => 'stock transférer'
													);

												}else{
													$array = array(
														'success'   => 'connexion à la base des données perdu'
													);
												}
											}else{
												$array = array(
													'success'   => 'connexion à la base des données perdu'
												);
											}
										}else{
											/*************************** L'ARTICLE EXISTE DANS LE DOCUMENT CHOISI ******************************/

											/**verifie que cet utilisateur a le droit de modifier le document (seul le chef d'agence ou l'admin le peu)*/
											if((session('users')['nom_serv'] == "" && session('users')['nom_ag'] != "") || (session('users')['nom_ag'] == "" && session('users')['nom_serv'] == "")){

												/**on met à jour l'historique des articles de ce document dans la table article_document*/

												/**on remet l'encienne quantité avant de retirer la nouvelle quantité a transferer(entrer en stock)
												 * 
												 * NB: on check le stock pour le depot initial
												*/
												$qte_art_s = $this->stock->get_article_stock($query_art_doc['code_article'],$code_depot_i);
												
												$input_stock_val = array(
													'code_employe'=> $matricule_emp,
													'code_depot'=> $code_depot_i,
													'code_entreprise'=> $matricule_en,
													'quantite'=> ($qte_art_s['quantite'] + $query_art_doc['quantite']),
													'date_modifier_stock'=> dates()
												);
												
												$query_art_st = $this->stock->update_stock2($query_art_doc['code_article'], $input_stock_val,$code_depot_i);
												if($query_art_st){

													/**on modifie l'article dans l'historique des article du document en question debut*/

														/**on modifie l'article dans le document selectionné */
														$input_art_doc = array(
															'quantite'=> $qte,
															'code_emp_art_doc'=> $matricule_emp,
															'code_en_art_doc'=> $matricule_en, 
															'date_modifier_art_doc'=> dates()
														);
				
														$query_art_d = $this->stock->update_article_document($query_art_doc['code_document'],$query_art_doc['code_article'],$input_art_doc);
														if($query_art_d){
															/**retirer la quantité d'article a transferer et le mettre dans le stock cible debut */

															/**je check a nouveau le stock sur la quantité en stock du depot initial on enleve la quantité a transferer*/
															$query_art_s = $this->stock->get_article_stock($code_article,$code_depot_i);
															$input_stock_val = array(
																'code_employe'=> $matricule_emp,
																'code_depot'=> $code_depot_i,
																'code_entreprise'=> $matricule_en,
																'quantite'=> (abs($qte - $query_art_s['quantite'])),
																'date_modifier_stock'=> dates()
															);
															
															$query_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot_i);
															if($query_art_st){

																/**article transferer et en attente pour aprobation */
																$array = array(
																	'success'   => 'stock transférer'
																);
															}else{
																$array = array(
																	'success'   => 'connexion à la base des données perdu'
																);
															}
														}else{
															$array = array(
																'success'   => 'connexion à la base des données perdu'
															);
														}
													/**on modifie l'article dans l'historique des article du document en question fin*/

												}else{
													$array = array(
														'success'   => 'connexion à la base des données perdu'
													);
												}
											}else{
												$array = array(
													'success'   => '
														<script>
															swal.fire("OUPS!","vous n\'êtes pas habilité a modifier une fois l\'écriture pris en compte. contactez le chef d\'entreprise, son adjoin ou le chef d\'agence","error")
														</script>
													'
												);
											}
										}

									}else{
										$array = array(
											'success'   => 'ce document n\'est plus modifiable'
										);
									}
								}

							}else{
								$array = array(
									'success'   => '
										<script>
											swal.fire("erreur survenu!","la quatité à tranferer ne doit pas être supérieur à la quantité en stock","error")
										</script>
									'
								);
							}

						}else{
							$array = array(
								'success'   => '
									<script>
										swal.fire("erreur survenu!","il faut que cet article soit d\'abord en stock dans le depot initiale pour pouvoir faire le transfert","error")
									</script>
								'
							);
						}

					}else{
						$array = array(
							'success'   => '
								<script>
									swal.fire("erreur survenu!","ce type de document n\'est pas utile ici, utilise le menu de gauche","error")
								</script>
							'
						);
					}
				}else{
					$array = array(
						'success'   => ' connexion à la base des données perdu'
					);	
				}
			}

		}else{
			$array = array(
				'error'   => true,
				'new_quantes_error' => form_error('new_quantes')
			);	
		}
		echo json_encode($array);
	}

	/**affiche la liste des transfert en attente de reception*/
	public function attente_transfert(){
		$this->logged_in();
		$output = "";	

		$abreviation_type_doc = "DT";
		$matricule_en = session('users')['matricule'];
		$matricule_emp = session('users')['matricule_emp'];
		$matricule_ag = session('users')['matricule_ag'];

			
			$etatdoc = 0;
		/**selectionne dans la bd la liste des transferts en attente */
		$query = $this->stock->transfert_in_waitting($abreviation_type_doc,$matricule_en,$etatdoc);
		if(!empty($query)){
		    
		    /**on selectionne le depot de l'agence connecter pour afficher le bouton de reception de stock
		    --- si l'agence est vide alors ils peuvent receptionner en tant que (gestionnaire de stock, chef de stock, directeur(trice), pdg)
		    */

			foreach($query as $key => $value) {
			    
			    $depotinitiateur = $this->stock->namedepotinitandrecep($value['depot_init_doc']);
			    $depotrecepteur = $this->stock->namedepotinitandrecep($value['depot_recept_doc']);
			    
				$output .='
					<tr>
						<th>'.$value['code_document'].'</th>
						<td>'.$value['nom_document'].'</td>
						<td>'.$depotinitiateur['nom_depot'].'</td>
						<td>'.$depotrecepteur['nom_depot'].'</td>
						<td>'.$value['nom_emp'].'</td>
						<th>'.$value['date_creation_doc'].'</th>
						<td>'.$value['date_modifier_doc'].'</td>
						<td>
							<a href="'.base_url('print/').''.$value['code_document'].'" id="'.$value['code_document'].'" target="_blank" class="btn btn-icon btn-circle btn-label-linkedin">
								<i class="fa fa-print"></i>
							</a> 
							<button id="'.$value['code_document'].'" class="btn btn-icon btn-circle btn-label-linkedin showartdoc">
								<i class="fa fa-eye"></i>
							</button>';

							if($matricule_ag == ""){ //$value['code_employe'] != $matricule_emp  || $value['depot_recept_doc'] ==
								$output .='
								<button id="'.$value['code_document'].'" class="btn btn-icon btn-circle btn-label-linkedin aprouvedoc btn-success">
									<i class="fa fa-check-circle"></i>
								</button>';
							}
							
							if($matricule_ag !="" && $matricule_ag == $depotrecepteur['code_ag_d']){ //$value['code_employe'] != $matricule_emp  || $value['depot_recept_doc'] ==
								$output .='
								<button id="'.$value['code_document'].'" class="btn btn-icon btn-circle btn-label-linkedin aprouvedoc btn-success">
									<i class="fa fa-check-circle"></i>
								</button>';
							}

							if($value['code_employe'] == $matricule_emp){
								$output .='
								<button id="'.$value['code_document'].'" class="btn btn-icon btn-circle btn-label-linkedin canceltransfert">
									<i class="fa fa-times-circle"></i>
								</button>';
							}
				
				$output .='  
						</td>
					</tr>
				';
			}
		}else{
			$output .= "aucun document en attente trouvé";	
		}

		echo json_encode($output);
	}

	/**affiche la liste des transferts recu */
	public function recu_transfert(){
		$this->logged_in();
		$output = "";	

		$abreviation_type_doc = "DT";
		$matricule_en = session('users')['matricule'];
		$matricule_emp = session('users')['matricule_emp'];
		$matricule_ag = session('users')['matricule_ag'];

			
			$etatdoc = 1;
		/**selectionne dans la bd la liste des transferts en attente */
		$query = $this->stock->transfert_in_waitting($abreviation_type_doc,$matricule_en,$etatdoc);
		if(!empty($query)){

			foreach ($query as $key => $value) {
			    
			    $depotinitiateur = $this->stock->namedepotinitandrecep($value['depot_init_doc']);
			    $depotrecepteur = $this->stock->namedepotinitandrecep($value['depot_recept_doc']);
			    
				$output .='
					<tr>
						<th>'.$value['code_document'].'</th>
						<td>'.$value['nom_document'].'</td>
						<td>'.$depotinitiateur['nom_depot'].'</td>
						<td>'.$depotrecepteur['nom_depot'].'</td>
						<td>'.$value['nom_emp'].'</td>
						<th>'.$value['date_creation_doc'].'</th>
						<td>'.$value['date_modifier_doc'].'</td>
						<td>
							<a href="'.base_url('print/').''.$value['code_document'].'" id="'.$value['code_document'].'" target="_blank" class="btn btn-icon btn-circle btn-label-linkedin">
								<i class="fa fa-print"></i>
							</a> 
							<button id="'.$value['code_document'].'" class="btn btn-icon btn-circle btn-label-linkedin showartdoc">
								<i class="fa fa-eye"></i>
							</button>';
				
				$output .='  
						</td>
					</tr>
				';
			}
		}else{
			$output .= "aucun document receptionné trouvé";	
		}

		echo json_encode($output);
	}


	/**confirm la reception du transfert */
	public function reception_transfert(){
		$this->logged_in();
 		$output ="";
		$code_document = trim($this->input->post('code_doc'));
		$matricule = session('users')['matricule'];
		$matricule_emp = session('users')['matricule_emp'];
		
		/**on selectionne le document dont son code est celui entré */
		$resultat = $this->stock->showdocument($code_document, $matricule);
		if($resultat){
			/**selectionne tous les articles de ce document */
			$query = $this->stock->articles_docucment($resultat['code_document'],$resultat['code_entreprie']);
			if(!empty($query)){

				foreach ($query as $key => $value){
					
					/**on test pour voir si l'article est déjà en stock pour le depot choisi
					 * s'il n'est pas en stock, on creer le nouveau stock
					*/
					$query_art_s = $this->stock->get_article_stock($value['code_article'],$resultat['depot_recept_doc']);
					if(empty($query_art_s)){
						
						/**on met a jour le document pour confirmer ca reception */
						$document_input = array(
							'etat_document' => 1,
							'date_modifier_doc' => dates()
						);
						$update_document = $this->stock->updatedocument($code_document,$document_input);
						if($update_document){
							
							/**on met en stock l'article a transferer  debut*/
							$input_stock_val = array(
								'code_article'=> $value['code_article'],
								'code_employe'=> $matricule_emp,
								'code_depot'=> $resultat['depot_recept_doc'],
								'code_entreprise'=> $matricule,
								'quantite'=> $value['quantite'],
								'date_entrer_stock'=> dates()
							);
							$query_art_st = $this->stock->enter_stock($input_stock_val);
							if($query_art_st){
								$output .='transfert de stock receptionné';
							}else{
								$output .='erreur survenu, connexion à la base des donnée perdu';		
							}
							/**on met en stock l'article a transferer  fin*/
						}else{
							$output .='erreur survenu, connexion à la base des donnée perdu';	
						}

					}else{

						/**on met a jour le document pour confirmer ca reception */
						$document_input = array(
							'etat_document' => 1,
							'date_modifier_doc' => dates()
						);
						$update_document = $this->stock->updatedocument($code_document,$document_input);
						if($update_document){
							
							/**pour le depot choisi, on met ajour la quantité en stock */

							/**j'ajoute ceci ici pour gardert les donné */
							$input_stock_val = array(
								'code_employe'=> $matricule_emp,
								'code_depot'=> $resultat['depot_recept_doc'],
								'code_entreprise'=> $matricule,
								'quantite'=> ($query_art_s['quantite'] + $value['quantite']),
								'date_entrer_stock'=> dates()
							);
							$query_update_st = $this->stock->update_stock2($value['code_article'],$input_stock_val,$resultat['depot_recept_doc']);
							if($query_update_st){
								$output .='transfert de stock receptionné <hr>';
							}else{
								$output .='erreur survenu, connexion à la base des donnée perdu';
							}
						}else{
							$output .='erreur survenu, connexion à la base des donnée perdu';	
						}
					}
				}
				
			}else{
				$output .='article non trouver pour ce document';
			}
		}else{
			$output .='document non trouver';
		}

		echo json_encode($output);
	}


	/**annuler un transfert en attente */
	public function cancel_transfert(){
		$this->logged_in();
 		$output ="";

		$code_document = trim($this->input->post('code_doc'));
		$matricule = session('users')['matricule'];
		$matricule_emp = session('users')['matricule_emp'];

		/**on selectionne le document dont son code est celui entré */
		$resultat = $this->stock->showdocument($code_document, $matricule);
		if($resultat){
			/**selectionne tous les articles de ce document */
			$query = $this->stock->articles_docucment($resultat['code_document'],$resultat['code_entreprie']);
			if(!empty($query)){
				foreach ($query as $key => $value){
					/**on selectionne les article en stock qui ont le mm 
					 * code d'article que celui dans le document
					 * et donc le depot est le depot initial
					*/
					$query_art_s = $this->stock->get_article_stock($value['code_article'],$resultat['depot_init_doc']);
					if(!empty($query_art_s)){
						$input_stock_val = array(
							'code_employe'=> $matricule_emp,
							'code_depot'=> $resultat['depot_init_doc'],
							'code_entreprise'=> $matricule,
							'quantite'=> ($query_art_s['quantite'] + $value['quantite']),
							'date_entrer_stock'=> dates()
						);
						$query_update_st = $this->stock->update_stock2($value['code_article'],$input_stock_val,$resultat['depot_init_doc']);
						if($query_update_st){
							
							/**on supprimer tous les articles du document dont le transfert a été annuler de la table article document
							 * 
							 * je mets la requette de selection du document pour ganrantir la fiabilité de la donné supprimer
							*/
							$resultat = $this->stock->showdocument($code_document, $matricule);
							$query_delete_art_doc = $this->stock->delete_art_stock($value['code_article'], $resultat['code_document'], $matricule);
							if($query_delete_art_doc){
								$output .='transfert annuler<br>';
							}else{
								$output .='erreur survenu, connexion à la base des donnée perdu';
							}
							
						}else{
							$output .='erreur survenu, connexion à la base des donnée perdu';
						}
					}else{
						$output .='erreur survenu contactez l\'administrateur';
					}
				}
				/**a la fin du parcours de la bourcle, on supprime le document en lui mm */
				$query_delete_doc = $this->stock->delete_doc($resultat['code_document'], $resultat['depot_init_doc'], $matricule);
				if($query_delete_doc){
					$output .='transfert annuler<br>';
				}else{
					$output .='erreur survenu, connexion à la base des donnée perdu';
				}
			}else{
				$output .='article non trouver pour ce document';
			}
		}else{
			$output .='document non trouver';
		}
		
		echo json_encode($output);
	}


	/**gestion des transferts et reception de stock debut */


	/**gestion inventaire de stock  debut*/

	/** affiche la page d'inventaire*/
	public function inventaire_stock($code_type_doc = NULL){
		$this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

		
		$output = "";
			
		/**matricule de l'entreprise */
		$matricule = session('users')['matricule'];

		/**affiche la liste des type de documents pour une entreprise donné */
		$data['docs'] = $this->stock->type_doc($matricule);
		//$data['docs'] = $this->document->get_doc($matricule);

		/**affiche la liste des dépots */
		$data['depots'] = $this->stock->get_all_depot2($matricule);

		/**liste des familles d'article */
		$data['famille'] = $this->stock->all_famille($matricule);

		/**affiche la liste des articles */
		$data['articles'] = $this->stock->get_all_article($matricule);


		/**afficher le code et le nom d'un document générer*/
		$data['code_doc'] = array(
			'DOC'.code(10) => 'DOCUMENT'.code(10).''.date('d-m-Y'),
		);
		
		/**selection de la list des documents en fonction du type de document */
		if(!empty($code_type_doc)){
			$output = $this->stock->get_doc_type($code_type_doc);
		}
		$data['get_docs'] = $output;



		/**imrpimer le document d'inventaire qui contient:
		 *  le code, 
		 * le code à bar, 
		 * la quantité en stock, 
		 * la quantité relever en reel 
		 * 
		 * Debut*/
		$btn = $this->input->post('btn_doc_form');

		if(isset($btn)){
			date_default_timezone_set("Africa/Douala");

			$matricule = session('users')['matricule'];

			$date_debut = $this->input->post('start');
			$date_fin = $this->input->post('end');

			$depot = $this->input->post('depot_doc');
			$famille = $this->input->post('famille_doc');

			if(!empty($depot) || !empty($famille)){ //|| !empty($date_debut)  || !empty($date_fin)

				$this->form_validation->set_rules('depot_doc', 'dépot', 'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',array(
					'required' => 'le %s est nécessaire',
					'regex_match' => 'caractère(s) non autorisé(s)'
				));
	
				$this->form_validation->set_rules('famille_doc', 'famille', 'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',array(
					'required' => 'la %s est nécessaire',
					'regex_match' => 'caractère(s) non autorisé(s)'
				));
	
				/*$this->form_validation->set_rules('start', 'date debut', 'required',array(
					'required' => 'la %s est nécessaire'
				));
	
				$this->form_validation->set_rules('end', 'date fin', 'required',array(
					'required' => 'la %s est nécessaire'
				));*/

				if($this->form_validation->run()){

					/**on converti la date au format voulu */
					$debut = date("Y-m-d", strtotime($date_debut));
					$fin = date("Y-m-d", strtotime($date_fin));

					$query = $this->stock->inventaire_param_stock($matricule,$depot,$famille); //,$debut,$fin
					if(!empty($query)){
						$name_depot = $this->stock->details_depot($depot);
						$name_famille = $this->stock->detail_famille($famille);
						
						
						// Create an instance of the class:
						$mpdf = new \Mpdf\Mpdf();

						$mpdf->SetHTMLHeader('
							<center style="text-align: right; font-weight: bold;">
								<h2>PREMICE COMPUTER GROUPE</h2><br><br><br>
							</center>
						');

						$mpdf->SetHTMLFooter('
							<table width="100%">
								<tr>
									<td width="33%">{DATE d-m-Y H:i:s}</td>
									<td width="33%" align="center">{PAGENO}/{nbpg}</td>
									<td width="33%" style="text-align: right;">Inventaire des stocks</td>
								</tr>
							</table>
						');

						// Write some HTML code:
						$mpdf->WriteHTML('
								<h2><u>Document d\'inventaire</u></h2>
								<h5>
									Depot: '.$name_depot['nom_depot'].'<br>
									Famille: '.$name_famille['nom_fam'].'<br>
									Période:  <--> <br>
								</h5>
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
									<td>Code</td>
									<td>Code à bare</td>
									<td>Désignation</td>
									<td>Qte en stock</td>
									<td>Qte Releve</td>
								</tr>');
								
								
								foreach ($query as $key => $value) {
									$mpdf->WriteHTML('
										<tr>
											<td>'.$value['code_article'].'</td>
											<td>'.$value['code_bar'].'</td>
											<td>'.$value['designation'].'</td>
											<td>'.$value['quantite'].'</td>
											<td></td>
										</tr>
									');
								}
							
						$mpdf->WriteHTML('</table>');

						// Output a PDF file directly to the browser
						//$mpdf->Output();
						$mpdf->Output('document_inventaire_de _stock_'.code(3).'.pdf', \Mpdf\Output\Destination::INLINE);
						exit();
					}else{
						flash('warning','pour des paramètres suivant '.$depot.' / '.$famille.' / '.$debut.' / '.$fin.' aucun article n\'a été trouvé');
					}
				}
			}else{
				/**1: slectionner la liste des depots */
				$depots = $this->stock->get_all_depot2($matricule);
				
				/**pour chaque depot je veux savoir la liste des article et les quantité en stock */
				if(!empty($depots)){
					// Create an instance of the class:
					$mpdf = new \Mpdf\Mpdf();

					foreach ($depots as $key => $value) {

						$mpdf->SetHTMLHeader('
							<center style="text-align: right; font-weight: bold;">
								<h2>PREMICE COMPUTER GROUPE</h2><br><br><br>
							</center>
						');

						$mpdf->SetHTMLFooter('
							<table width="100%">
								<tr>
									<td width="33%">{DATE d-m-Y H:i:s}</td>
									<td width="33%" align="center">{PAGENO}/{nbpg}</td>
									<td width="33%" style="text-align: right;">Inventaire des stocks</td>
								</tr>
							</table>
						');

						// Write some HTML code:
						$mpdf->WriteHTML('
								<h2><u>Document d\'inventaire</u></h2>
								<h5>
									Depot: '.$value['nom_depot'].'<br>
									Famille: <br>
									Période: <br>
								</h5>
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
									<td>Code</td>
									<td>Code à bare</td>
									<td>Désignation</td>
									<td>Famille</td>
									<td>Qte en stock</td>
									<td>Qte Releve</td>
								</tr>');
								
								
								$articles = $this->stock->get_depot_art_stock($matricule, $value['mat_depot']);
								if(!empty($articles)){
									foreach ($articles as $key => $values){
										$mpdf->WriteHTML('
											<tr>
												<td>'.$values['matricule_art'].'</td>
												<td>'.$values['code_bar'].'</td>
												<td>'.$values['designation'].'</td>
												<td>'.$values['nom_fam'].'</td>
												<td>'.$values['quantite'].'</td>
												<td></td>
											</tr>
										');
									}
								}
							
						$mpdf->WriteHTML('</table>');
						$mpdf->AddPage();
					}

					// Output a PDF file directly to the browser
					//$mpdf->Output();
					$mpdf->Output('document_inventaire_de _stock'.code(3).'.pdf', \Mpdf\Output\Destination::INLINE);
					exit();
				}else{
					flash('error','Aucun dépot n\a été trouvé. contactez l\'administrateur');
				}	
			}

		}
		 /**fin */
						
		$this->load->view('stock/inventaire_stock',$data);


        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}

	/**afficher le formulaire d'inventaire*/
	public function inventaire_document(){
		$this->logged_in();
		$output = "";
		$this->form_validation->set_rules('type_doc_2', 'choisi le type de document', 'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',array(
			'required' => '%s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('depot', 'choisi le depot', 'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',array(
			'required' => '%s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('famille', 'choisi la famille', 'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',array(
			'required' => '%s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('document', 'choisi le document', 'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',array(
			'required' => '%s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		$this->form_validation->set_rules('article', 'choisi l\'article', 'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',array(
			'required' => '%s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));
		
		if($this->form_validation->run()){

			$code_type_document = trim($this->input->post('type_doc_2'));
			$depot = trim($this->input->post('depot'));
			$famille = trim($this->input->post('famille'));
			$document = trim($this->input->post('document'));
			$article = trim($this->input->post('article'));
			$matricule_en = session('users')['matricule'];

			if(!empty($article) && !empty($depot) && !empty($document) && !empty($matricule_en)){

				/**on test pour voir si le type de document est utile ici */
				$type_document = $this->stock->type_doc_stock($code_type_document, $matricule_en);
				if(!empty($type_document)){
					if($type_document['abrev_doc'] == 'DI'){

						/**on s'assure d'ajuster la quantité de l'article qui est déja en stock */
						$query = $this->stock->get_article_stock($article,$depot);
						if(!empty($query)){
							/**liste des articles du document */
							$output = $this->art_doc($document, $matricule_en);
							$array = array(
								'success'   => 'article trouvé',
								'art_doc' => $output,
								'article'   => $query
							);
						}else{
							$array = array(
								'message' => '
									<script>
										swal.fire("erreur survenu!","on ne peut pas ajuster la quantité en stock d\'un qui lui même n\'est pas en stock. <b>faites l\'entrer en stock de cet article</b>","error")
									</script>
								'
							);
						}
					}else{
						$array = array(
							'message' => '
								<script>
									swal.fire("erreur survenu!","ce type de document n\'est pas utile ici. utilise le menu de gauche","error")
								</script>
							'
						);
					}
				}else{
					$array = array(
						'message' => '
							<script>
								swal.fire("erreur survenu!","le système ,e retrouve pas ce type de document","error")
							</script>
						'
					);
				}

			}else{
				$array = array(
					'success' => '
						<script>
							swal.fire("erreur survenu!","contactez l\'administrateur","error")
						</script>
					'
				);
			}
		}else{
			$array = array(
				'error'   => true,
				'type_doc_2_error' => form_error('type_doc_2'),
				'depot_error' => form_error('depot'),
				'famille_error' => form_error('famille'),
				'document_error' => form_error('document'),
				'article_error' => form_error('article')
			);
		}

		echo json_encode($array);
	}

	/**affiche les valeur en plus et en moin pour savoir si la quatite a augmente ou diminuer lors de l'inventaire*/
	public function qte_plus_moins(){
		$this->logged_in();

		$qte = $this->input->post('qte');
		if(preg_match('/^[0-9\ ]+$/',$qte)){
			$code_article = $this->input->post('article');
			$code_depot = $this->input->post('depot');

			$article = $this->stock->get_article_stock($code_article,$code_depot);
			if(!empty($article)){
				if($qte >= $article['quantite']){
					$array = array(
						'plus' => ($qte - $article['quantite']),
						'moins' => 0,
					);
				}
				if($qte <= $article['quantite']){
					$array = array(
						'plus' => 0,
						'moins' => ($article['quantite'] - $qte),
					);
				}
			}else{
				$array = array(
					'plus' => '',
					'moins' => '',
				);	
			}
			
		}else{
			$array = array(
				'plus' => '',
				'moins' => '',
			);	
		}
		echo json_encode($array);
	}

	/**operation sur le formulaire d'inventaire de stock debut */
	public function op_inventaire_stock(){
		$this->logged_in();
		$output = "";
		$this->form_validation->set_rules('new_quantes', 'quantité', 'required|regex_match[/^[0-9]+$/]',array(
			'required' => 'entre la %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

		if($this->form_validation->run()){
			$output = "";
			$code_type_doc = $this->input->post('t_doc');
			$code_depot = $this->input->post('dep');
			$code_famille = $this->input->post('fam');
			$code_document = $this->input->post('doc_m');
			$code_article = $this->input->post('art');
			$qte = $this->input->post('new_quantes');
			$matricule_en = session('users')['matricule'];
			$matricule_emp = session('users')['matricule_emp'];

			/**on test pour voir si le type de document est utile ici */
			$type_document = $this->stock->type_doc_stock($code_type_doc, $matricule_en);
			if(!empty($type_document)){

				if($type_document['abrev_doc'] == 'DI'){

					/************************* faire un ajustement des quantités en stock apres un inventaire ******************************** */
					/**on verifie que l'article est en stock pour le depot choisi, on s'assure d'ajuster la quantité de l'article qui est déja en stock*/
					$query_art_s = $this->stock->get_article_stock($code_article,$code_depot);
                    if(!empty($query_art_s)){
						
                    	/**determiner si les quantité saisi son en plus ou en moin debut */
                    	if($qte > $query_art_s['quantite']){
                    		$val['plus'] = ($qte - $query_art_s['quantite']);
                    		$val['moins'] = 0;
                    	}
                    	if($qte < $query_art_s['quantite']){
                    		$val['plus'] = 0;
                    		$val['moins'] = ($query_art_s['quantite'] - $qte);
                    	}
						if($qte = $query_art_s['quantite']){
                    		$val['plus'] = 0;
                    		$val['moins'] = 0;
                    	}
                    	/**determiner si les quantité saisi son en plus ou en moin fin */
                    
                    	/**on test pour voir si le document existe deja */
                    	$query_d = $this->stock->doc_stock($code_type_doc,$code_document,$matricule_en);
                    	if(empty($query_d)){
                    		/**comme le  docmument n'existe pas encore, on le creer */
                    		$input_doc = array(
                    			'code_document'=>$code_document,
                    			'nom_document'=> 'DOCUMENT'.code(10).date('d-m-Y'),
                    			'date_creation_doc'=>dates(),
                    			'depot_doc'=>$code_depot,
                    			'code_type_doc'=> $code_type_doc,
                    			'code_employe'=> $matricule_emp,
                    			'code_entreprie'=> $matricule_en,
                    		);
                    		$query_d = $this->stock->new_document($input_doc);
                    		if($query_d){
                    
                    			/**on enrégistre l'historique des articles de ce document dans la table article_document*/
                    			$input_art_doc = array(
                    				'code_article'=> $code_article,
                    				'code_document'=> $code_document,
                    				'quantite'=> $qte,
									'qte_avant_inventaire'=> $query_art_s['quantite'],
                    				'quantite_plus'=> $val['plus'],
                    				'quantite_moins'=> $val['moins'],
                    				'code_emp_art_doc'=> $matricule_emp,
                    				'code_en_art_doc'=> $matricule_en, 
                    				'date_creer_art_doc'=> dates()
                    			);
                    			$query_art_d = $this->stock->article_document($input_art_doc);
                    			if($query_art_d){
                    				/**mettre a jour le stock maintenant*/
                    				$input_stock_val = array(
                    					'code_employe'=> $matricule_emp,
                    					'code_depot'=> $code_depot,
                    					'code_entreprise'=> $matricule_en,
                    					'quantite'=> $qte,
                    					'date_modifier_stock'=> dates()
                    				);
                    				
                    				$query_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
                    				if($query_art_st){
                    
                    					/**liste des articles du document */
                    					$output = $this->art_doc($code_document, $matricule_en);
                    					$array = array(
                    						'success'   => 'stock ajuster',
                    						'art_doc' => $output
                    					);
                    				}else{
                    					$array = array(
                    						'success'   => '<b class="text-danger">connexion à la base des données perdu</b>'
                    					);
                    				}
                    			}else{
                    				$array = array(
                    					'success'   => '<b class="text-danger">connexion à la base des données perdu</b>'
                    				);
                    			}
                    		}else{
                    			$array = array(
                    				'success'   => '<b class="text-danger">connexion à la base des données perdu</b>'
                    			);
                    		}
                    
                    	}else{
                    		/**le document existe déjà */
                    
                    		/**on verifie si le document est encore modifiable */
                    		$diff = $this->nbr_jour($query_d['date_creation_doc']);
                    
                    		if($diff <= 30){
                    			/**le document est modifiable */
                    
                    			/**on verifie si l'article existe pour le document selectionne si non on le creer */
                    			$query_art_doc = $this->stock->get_art_doc($code_article,$code_document,$matricule_en);
                    			if(empty($query_art_doc)){
                    				/**l'article n'existe pas dans le document en question */
                    
                    				/**on enrégistre l'historique des articles de ce document dans la table article_document*/
                    				$input_art_doc = array(
                    					'code_article'=> $code_article,
                    					'code_document'=> $code_document,
                    					'quantite'=> $qte,
										'qte_avant_inventaire'=> $query_art_s['quantite'],
                    					'quantite_plus'=> $val['plus'],
                    					'quantite_moins'=> $val['moins'],
                    					'code_emp_art_doc'=> $matricule_emp,
                    					'code_en_art_doc'=> $matricule_en, 
                    					'date_creer_art_doc'=> dates()
                    				);
                    				$query_art_d = $this->stock->article_document($input_art_doc);
                    				if($query_art_d){
                    					/**mettre a jour le stock maintenant*/
                    					$input_stock_val = array(
                    						'code_employe'=> $matricule_emp,
                    						'code_depot'=> $code_depot,
                    						'code_entreprise'=> $matricule_en,
                    						'quantite'=> $qte,
                    						'date_modifier_stock'=> dates()
                    					);
                    					
                    					$query_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
                    					if($query_art_st){
                    
                    						/**liste des articles du document */
                    						$output = $this->art_doc($code_document, $matricule_en);
                    						$array = array(
                    							'success'   => 'stock ajuster',
                    							'art_doc' => $output
                    						);
                    
                    					}else{
                    						$array = array(
                    							'success'   => '<b class="text-danger">connexion à la base des données perdu</b>'
                    						);
                    					}
                    				}else{
                    					$array = array(
                    						'success'   => '<b class="text-danger">connexion à la base des données perdu</b>'
                    					);
                    				}
                    
                    			}else{
                    				/**l'article existe dans le document en question */
                    
                    				/**verifie que cet utilisateur a le droit de modifier le document (seul le chef d'agence ou l'admin le peu)*/
                    				if((session('users')['nom_serv'] == "" && session('users')['nom_ag'] != "") || (session('users')['nom_ag'] == "" && session('users')['nom_serv'] == "")){
                    					
                    					/**on modifie l'article dans le document selectionné */
                    					$input_art_doc = array(
                    						'quantite'=> $qte,
                    						'quantite_plus'=> $val['plus'],
                    						'quantite_moins'=> $val['moins'],
                    						'code_emp_art_doc'=> $matricule_emp,
                    						'code_en_art_doc'=> $matricule_en, 
                    						'date_modifier_art_doc'=> dates()
                    					);
                    
                    					$query_art_d = $this->stock->update_article_document($code_document,$code_article,$input_art_doc);
                    					if($query_art_d){
                    						/**mettre a jour le stock maintenant*/
                    
                    						/*$code_article = $this->input->post('art');
                    						$qte = $this->input->post('new_quantes');
                    						$code_depot = $this->input->post('dep');*/
                    
                    						$input_stock_val = array(
                    							'code_employe'=> $matricule_emp,
                    							'code_depot'=> $code_depot,
                    							'code_entreprise'=> $matricule_en,
                    							'quantite'=> $qte,
                    							'date_modifier_stock'=> dates()
                    						);
                    						
                    						$query_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
                    						if($query_art_st){
                    							/**affiche la liste des article en fonction du document *
                    							$output = $this->art_doc($code_document,$matricule_en);
                    
                    							$array = array(
                    								'success' => 'stock ajuster '.$input_stock_val['quantite'],
                    								'art_doc' => $output
                    							);*/
                    
                    							/**liste des articles du document */
                    							$output = $this->art_doc($code_document, $matricule_en);
                    							$array = array(
                    								'success'   => 'stock ajuster',
                    								'art_doc' => $output
                    							);
                    
                    						}else{
                    							$array = array(
                    								'success'   => '<b class="text-danger">connexion à la base des données perdu</b>'
                    							);
                    						}
                    					}else{
                    						$array = array(
                    							'success'   => '<b class="text-danger">connexion à la base des données perdu</b>'
                    						);	
                    					}
                    				}else{
                    					$array = array(
                    						'success'   => '
                    							<script>
                    								swal.fire("OUPS!","vous n\'êtes pas habilité a modifier une fois l\'écriture pris en compte. contactez le chef d\'entreprise, son adjoin ou le chef d\'agence","error")
                    							</script>
                    						'
                    					);
                    				}
                    			}
                    
                    		}else{
                    			
                    			$array = array(
                    				'success'   => '
                    					<script>
                    						swal.fire("erreur survenu!","ce document n\'est plus modifiable","error")
                    					</script>
                    				'
                    			);
                    		}
                    	}
                    	
                    }else{
                    	$array = array(
                    		'success' => '
                    			<script>
                    				swal.fire("erreur survenu!","on ne peut pas ajuster la quantité en stock d\'un qui lui même n\'est pas en stock. <b>faites l\'entrer en stock de cet article</b>","error")
                    			</script>
                    		'
                    	);
                    }
					/****************************************************************************** */
				}else{
					$array = array(
						'success'   => '
							<script>
								swal.fire("erreur survenu!","Ce document n\'est pas utile ici, utilise le menu de gauche","error")
							</script>
						'
					);
				}
			}else{
				$array = array(
					'success'   => '<b class="text-danger">le système ne retrouve pas ce type de document</b>'
				);
			}
		}else{
			$array = array(
				'error'   => true,
				'new_quantes_error' => form_error('new_quantes')
			);	
		}

		echo json_encode($array);
	}
	/**operation sur le formulaire d'inventaire de stock fin */

	

	/**gestion inventaire de stock fin */
	
	
	/**modifier le pourcentage de marge des articles en fonction de la famille debut*/
	public function percentmargeperfam(){
	   $this->logged_in();
	   
	    $this->form_validation->set_rules('percentfamilleproduit', 'famille', 'required|regex_match[/^[0-9A-Za-z]+$/]',array(
			'required' => 'entre la %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));
		$this->form_validation->set_rules('margefamilleproduit', 'marge', 'required|regex_match[/^[0-9]+(\.[0-9]{1,2})?$/]',array(
			'required' => 'entre la %s',
			'regex_match' => 'caractère(s) non autorisé(s) pas de nombre négatif ou décimal'
		));

		if($this->form_validation->run()){
		    $output = "";
		    $matricule_en = session('users')['matricule'];
		    $famille = $this->input->post('percentfamilleproduit');
		    $pourcentagemarge = $this->input->post('margefamilleproduit');
		    
		    $articles = $this->stock->tri_article($matricule_en,$famille);
		    if(!empty($articles)){
		        foreach($articles as $val){
		            $prixrevient = $val['prix_revient'];
		            $matart = $val['matricule_art'];
		            if(!empty($prixrevient)){
		                
    		            $prixdemarge = (($prixrevient * $pourcentagemarge)/100);
    		           
    		            
    		            $prixht = ($prixrevient + $prixdemarge);
    		            
    		            $prixdeir = ($prixht * (-0.022));
    		             
    		            $prixdegros= ($prixht + ($prixdeir));
    		            $date = dates();
    		            
    		            $data = array(
                            'pourcentage_marge' => $pourcentagemarge,
                            'prix_hors_taxe' => $prixht,
                            'prix_gros' => $prixdegros,
                            'modifier_article_le' => $date
                        );
    		            
    		            
    		            $update = $this->stock->updatemarge($data,$matricule_en,$famille,$matart);
            		    if($update){
            		        $array = array(
                    	      'success' => 'mis à jour éffectuer avec succès', 
                    	    );
            		    }else{
            		        $array = array(
                    	      'success' => 'erreur survenu. mis à jour non éffectuer ', 
                    	    );
            		    }
            		    
		            }else{
		                $data = array(
                            'pourcentage_marge' => '',
                            'prix_hors_taxe' => '',
                            'prix_gros' => '',
                            'modifier_article_le' => $date
                        );
		               $update = $this->stock->updatemarge($data,$matricule_en,$famille,$matart);
            		    if($update){
            		        $array = array(
                    	      'success' => 'mis à jour éffectuer avec succès', 
                    	    );
            		    }else{
            		        $array = array(
                    	      'success' => 'erreur survenu. mis à jour non éffectuer ', 
                    	    );
            		    } 
		            }
		        }
		        
		        
		    }else{
		        $array = array(
        	      'success' => 'non ok', 
        	    ); 
		    }
		    
		    /*$inputval = array(
		      'pourcentage_marge' => $pourcentagemarge,
		      'modifier_article_le' => dates()
		    );
		    
		    $update = $this->stock->updatemarge($inputval,$matricule_en,$famille);
		    if(!empty($update)){
		        $array = array(
        	      'success' => 'pourcentage de marge de cette famille modifier avec succès', 
        	    );
		    }else{
		        $array = array(
        	      'success' => 'erreur survenu! pourcentage de cette famille modifier avec succès', 
        	    );
		    }*/
		    
		}else{
		  $array = array(
				'error'   => true,
				'percentfamilleproduit_error' => form_error('percentfamilleproduit'),
				'margefamilleproduit_error' => form_error('margefamilleproduit')
			);  
		}
	   
	   echo json_encode($array);
	}
    /**modifier le pourcentage de marge des articles en fonction de la famille fin*/

	/********************************************** MES FONCTUIONS DEBUT *************************************************************** */
	/**methode qui return la liste des articles d'un document debut */
	public function art_doc($document,$matricule){
		$output = "";
		$artdocument = $this->stock->articles_docucment($document,$matricule);
		if(!empty($artdocument)){
			$output .='
				<tr>
					<td colspan="4">
						<center>
							<a href="'.base_url('print/'.$document).'" target="_blank" class="btn btn-icon btn-circle btn-label-linkedin">
								<i class="fa fa-print"></i>
							</a> 
						</center>
					</td>
					<td colspan="4">
						<center>
							<input type="hidden" name="doc" id="doc" class="doc" value="'.$document.'">
						</center>
					</td>
				</tr>
			';
			foreach ($artdocument as $key => $value) {
				$output .='
					<tr>
						<td>'.$value['code_bar'].'</td>
						<td>'.$value['designation'].'</td>
						<td>'.$value['reference'].'</td>
						<td>'.$value['quantite'].'</td>
						<td>'.$value['nom_emp'].'</td>
						<td>'.$value['date_creer_art_doc'].'</td>
						<td>'.$value['date_modifier_art_doc'].'</td>
						<td>
							<button id="'.$value['matricule_art'].'" class="btn btn-icon btn-circle btn-label-linkedin delete_art">
								<i class="fa fa-times-circle"></i>
							</button>
						</td>
					</tr>
				';
			}
			
		}else{
			$output .='<tr><td colspan="8"><b class="text-danger">il n\'y a pas encore d\'article dans ce document</b></td></tr>';
		}

		return $output;
	}
	/**methode qui return la liste des articles d'un document fin */


	/**methode qui return le type de document debut */
	public function type_document($code_type_doc, $code_en){
		$output = "";
		/**verifier le type de document */
		$verify_typ_doc = $this->stock->type_doc_stock($code_type_doc, $code_en);
		if(!empty($verify_typ_doc)){
			$output .= $verify_typ_doc['abrev_doc'];
		}
		return $output;
	}
	/**methode qui return le type de document fin */

	/**methode qui calcule le nombre de jour entre deux dates debut  */
	public function nbr_jour($date_document){
		$datebd = date("Y-m-d", strtotime($date_document));
		$dateactu = date("Y-m-d", strtotime(date('Y-m-d')));
		$earlier = new DateTime($datebd);
		$later = new DateTime($dateactu);
		$diff = $later->diff($earlier)->format("%a");
		return $diff;
	}
	/**methode qui calcule le nombre de jour entre deux dates fin  */
	
	/**methode qui permet de supprimer un article dans un document debut */
	public function delete_art_document(){
		$this->logged_in();
		
		/**verifie que cet utilisateur a le droit de modifier le document (seul le chef d'agence ou l'admin le peu)*/
        if((session('users')['nom_serv'] == "" && session('users')['nom_ag'] == "") || (session('users')['nom_serv'] == "" && session('users')['nom_ag'] != "")|| (session('users')['nom_serv'] != "" && session('users')['nom_ag'] == "")){
        
        	$code_document = $this->input->post('code_doc');
        	$code_article = $this->input->post('code_art');
        	$code_depot = $this->input->post('code_depot');
        
        	$matricule_en = session('users')['matricule'];
        	$matricule_emp = session('users')['matricule_emp'];
        	
        
        	if(!empty($code_document) && !empty($code_article)){
        
        			/**on verifie le type de document */
        			$verify_typ_doc = $this->stock->type_document($code_document,$matricule_en);
        			if(!empty($verify_typ_doc)){
        
        				
        				if($verify_typ_doc['abrev_doc'] == 'FAF'){
        
        
        					/** 
        					 * 1: on selectionne l'article dans le document en question
        					 * 2: on selectionne l'article dans le stock pour le depot
        					 * 3: on retir les quantités: qte art dans le document - qte art dans le stock
        					 * 4: on supprime l'article du document
        					*/
        
        					/**1: on selectionne l'article dans le document en question*/
        					$verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
        					if($verify_art_document){
        
        						/** 2: on selectionne l'article dans le stock pour le depot choisi*/
        						$verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
        						if(!empty($verify_art_stock)){
        
        							$array = 	array(
        								'success'   => '
        									<script>
        										swal.fire("ok!","'.$verify_art_document['quantite'].'--'.$verify_art_stock['quantite'].'--'.$verify_typ_doc['date_creation_doc'].'","info")
        									</script>
        								'
        							);
        
        							/**on verifie si le document est encore modifiable */
        							$diff = $this->nbr_jour($verify_typ_doc['date_creation_doc']);
        							if($diff <= 1){
        								/**le document est modifiable */
        
        								/**on s'assure que la quantité en stock pour le depot n'est pas inferieur à la quantité de l'article dans le document*/
        								if($verify_art_stock['quantite'] >= $verify_art_document['quantite']){
        
        									/**on compte le nombre d'article dans un document
        									 * si c'est >1 on supprime uniquement l'article dans le document
        									 * si c'est c'est ==1 on l'article dans le document et le document lui mm
        									 */
        									
        									$nbr_artdocument = $this->stock->count_articles_docucment($code_document,$matricule_en);
        									if($nbr_artdocument > 1){
        										/**on enlève du stock la quantité dans le document*/
        										$input_stock_val = array(
        											'code_employe'=> $matricule_emp,
        											'code_depot'=> $code_depot,
        											'code_entreprise'=> $matricule_en,
        											'quantite'=> (abs($verify_art_stock['quantite'] - $verify_art_document['quantite'])),
        											'date_modifier_stock'=> dates()
        										);
        										$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
        										if($update_art_st){
        											/**on supprimer l'article du document */
        											$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
        											if($delete_art_doc){
        												/**liste des articles du document */
        												$output = $this->art_doc($code_document, $matricule_en);
        												$array = array(
        													'success' => 'article supprimer du document avec succès',
        													'art_doc' => $output
        												);
        											}else{
        												$array = 	array(
        													'success'   => '
        														<script>
        															swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
        														</script>
        													'
        												);	
        											}
        										}else{
        											$array = $message_db;
        										}
        									}else if($nbr_artdocument == 1){
        										/**on enlève du stock la quantité dans le document*/
        										$input_stock_val = array(
        											'code_employe'=> $matricule_emp,
        											'code_depot'=> $code_depot,
        											'code_entreprise'=> $matricule_en,
        											'quantite'=> (abs($verify_art_stock['quantite'] - $verify_art_document['quantite'])),
        											'date_modifier_stock'=> dates()
        										);
        										$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
        										if($update_art_st){
        											/**on supprimer l'article du document */
        											$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
        											if($delete_art_doc){
        												/**on supprime le document */
        												$delete_doc = $this->stock->delete_document($code_document,$matricule_en,$verify_typ_doc['code_doc']);
        												if($delete_doc){
        													/**liste des articles du document */
        													$output = $this->art_doc($code_document, $matricule_en);
        													$array = array(
        														'success' => 'article supprimer du document avec succès',
        														'art_doc' => $output
        													);
        												}else{
        													$array = 	array(
        														'success'   => '
        															<script>
        																swal.fire("ERREUR!","le document n\'a pas été supprimer! contactez l\'administrateur","info")
        															</script>
        														'
        													);
        												}
        												
        											}else{
        												$array = 	array(
        													'success'   => '
        														<script>
        															swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
        														</script>
        													'
        												);	
        											}
        										}else{
        											$array = $message_db;
        										}
        									}
        
        								}else{
        									$array = 	array(
        										'success' => '
        											<script>
        												swal.fire("OUPS!","Impossible de supprimer! la quantité en stock pour ce depot est inferieur a la quantité de l\'article dans le document","error")
        											</script>
        										'
        									);
        								}
        							}else{
        								$array = 	array(
        									'success'   => '
        										<script>
        											swal.fire("OUPS!","ce document n\'est plus modifiable. <b>la modification est uniquement possible le jour de l\'enregistrement</b>","info")
        										</script>
        									'
        								);
        							}
        
        						}else{
        							$array = 	array(
        								'success'   => '
        									<script>
        										swal.fire("OUPS!","le systeme ne trouve pas l\'article dans le stock! contactez l\'administrateur","info")
        									</script>
        								'
        							);
        						}
        					}else{
        						$array = 	array(
        							'success'   => '
        								<script>
        									swal.fire("OUPS!","le systeme ne trouve pas l\'article dans le document! contactez l\'administrateur","info")
        								</script>
        							'
        						);
        					}
        
        					
        
        				}else if($verify_typ_doc['abrev_doc'] == 'FRF'){
        					
        					/** 
        					 * 1: on selectionne l'article dans le document en question
        					 * 2: on selectionne l'article dans le stock pour le depot
        					 * 3: on ajoute les quantités: qte art dans le document + qte art dans le stock
        					 * 4: on supprime l'article du document
        					 * 5:on supprime le document au besion
        					*/
        
        					/**1: on selectionne l'article dans le document en question */
        					$verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
        					if($verify_art_document){
        
        						/** 2: on selectionne l'article dans le stock pour le depot choisi*/
        						$verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
        						if($verify_art_stock){
        							/*$array = 	array(
        								'success'   => '
        									<script>
        										swal.fire("ok!","'.$verify_art_document['quantite'].'--'.$verify_art_stock['quantite'].'--'.$verify_typ_doc['date_creation_doc'].'","info")
        									</script>
        								'
        							);*/
        
        							/**on verifie si le document est encore modifiable*/
        							$diff = $this->nbr_jour($verify_typ_doc['date_creation_doc']);
        							if($diff <= 1){
        								/**le document est modifiable */
        
        								/**on compte le nombre d'article dans un document
        								 * si c'est >1 on supprime uniquement l'article dans le document
        								 * si c'est c'est ==1 on l'article dans le document et le document lui mm
        								*/
        								
        								$nbr_artdocument = $this->stock->count_articles_docucment($code_document,$matricule_en);
        								if($nbr_artdocument > 1){
        									/**on ajoute au stock la quantité dans le document*/
        									$input_stock_val = array(
        										'code_employe'=> $matricule_emp,
        										'code_depot'=> $code_depot,
        										'code_entreprise'=> $matricule_en,
        										'quantite'=> ($verify_art_stock['quantite'] + $verify_art_document['quantite']),
        										'date_modifier_stock'=> dates()
        									);
        									$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
        									if($update_art_st){
        										/**on supprimer l'article du document */
        										$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
        										if($delete_art_doc){
        											/**liste des articles du document */
        											$output = $this->art_doc($code_document, $matricule_en);
        											$array = array(
        												'success' => 'article supprimer du document avec succès',
        												'art_doc' => $output
        											);
        										}else{
        											$array = 	array(
        												'success'   => '
        													<script>
        														swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
        													</script>
        												'
        											);	
        										}
        									}else{
        										$array = $message_db;
        									}
        								}else if($nbr_artdocument == 1){
        									/**on enlève du stock la quantité dans le document*/
        									$input_stock_val = array(
        										'code_employe'=> $matricule_emp,
        										'code_depot'=> $code_depot,
        										'code_entreprise'=> $matricule_en,
        										'quantite'=> ($verify_art_stock['quantite'] + $verify_art_document['quantite']),
        										'date_modifier_stock'=> dates()
        									);
        									$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
        									if($update_art_st){
        										/**on supprimer l'article du document */
        										$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
        										if($delete_art_doc){
        											/**on supprime le document */
        											$delete_doc = $this->stock->delete_document($code_document,$matricule_en,$verify_typ_doc['code_doc']);
        											if($delete_doc){
        												/**liste des articles du document */
        												$output = $this->art_doc($code_document, $matricule_en);
        												$array = array(
        													'success' => 'article supprimer du document avec succès',
        													'art_doc' => $output
        												);
        											}else{
        												$array = 	array(
        													'success'   => '
        														<script>
        															swal.fire("ERREUR!","le document n\'a pas été supprimer! contactez l\'administrateur","info")
        														</script>
        													'
        												);
        											}
        											
        										}else{
        											$array = 	array(
        												'success'   => '
        													<script>
        														swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
        													</script>
        												'
        											);	
        										}
        									}else{
        										$array = $message_db;
        									}
        								}
        
        							}else{
        								$array = 	array(
        									'success'   => '
        										<script>
        											swal.fire("OUPS!","ce document n\'est plus modifiable. <b>la modification est uniquement possible le jour de l\'enregistrement</b>","info")
        										</script>
        									'
        								);
        							}
        
        						}else{
        							$array = 	array(
        								'success'   => '
        									<script>
        										swal.fire("OUPS!","le systeme ne trouve pas l\'article dans le stock! contactez l\'administrateur","info")
        									</script>
        								'
        							);
        						}
        					}else{
        						$array = 	array(
        							'success'   => '
        								<script>
        									swal.fire("OUPS!","le systeme ne trouve pas l\'article dans le document! contactez l\'administrateur","info")
        								</script>
        							'
        						);
        					}
        
        				}else if($verify_typ_doc['abrev_doc'] == 'MVT-E'){
        						
        					/** 
        					 * 1: on selectionne l'article dans le document en question
        					 * 2: on selectionne l'article dans le stock pour le depot
        					 * 3: on ajoute les quantités: qte art dans le document + qte art dans le stock
        					 * 4: on supprime l'article du document
        					 * 5:on supprime le document au besion
        					*/
        
        					/**1: on selectionne l'article dans le document en question */
        					$verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
        					if($verify_art_document){
        
        						/** 2: on selectionne l'article dans le stock pour le depot choisi*/
        						$verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
        						if($verify_art_stock){
        							/*$array = 	array(
        								'success'   => '
        									<script>
        										swal.fire("ok!","'.$verify_art_document['quantite'].'--'.$verify_art_stock['quantite'].'--'.$verify_typ_doc['date_creation_doc'].'","info")
        									</script>
        								'
        							);*/
        
        							/**on verifie si le document est encore modifiable */
        							$diff = $this->nbr_jour($verify_typ_doc['date_creation_doc']);
        							if($diff <= 1){
        								/**le document est modifiable */
        
        								/**on compte le nombre d'article dans un document
        								 * si c'est >1 on supprime uniquement l'article dans le document
        								 * si c'est c'est ==1 on l'article dans le document et le document lui mm
        								 */
        								
        								$nbr_artdocument = $this->stock->count_articles_docucment($code_document,$matricule_en);
        								if($nbr_artdocument > 1){
        									/**on ajoute au stock la quantité dans le document*/
        									$input_stock_val = array(
        										'code_employe'=> $matricule_emp,
        										'code_depot'=> $code_depot,
        										'code_entreprise'=> $matricule_en,
        										'quantite'=> (abs($verify_art_stock['quantite'] - $verify_art_document['quantite'])),
        										'date_modifier_stock'=> dates()
        									);
        									$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
        									if($update_art_st){
        										/**on supprimer l'article du document */
        										$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
        										if($delete_art_doc){
        											/**liste des articles du document */
        											$output = $this->art_doc($code_document, $matricule_en);
        											$array = array(
        												'success' => 'article supprimer du document avec succès',
        												'art_doc' => $output
        											);
        										}else{
        											$array = 	array(
        												'success'   => '
        													<script>
        														swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
        													</script>
        												'
        											);	
        										}
        									}else{
        										$array = $message_db;
        									}
        								}else if($nbr_artdocument == 1){
        									/**on enlève du stock la quantité dans le document*/
        									$input_stock_val = array(
        										'code_employe'=> $matricule_emp,
        										'code_depot'=> $code_depot,
        										'code_entreprise'=> $matricule_en,
        										'quantite'=> (abs($verify_art_stock['quantite'] - $verify_art_document['quantite'])),
        										'date_modifier_stock'=> dates()
        									);
        									$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
        									if($update_art_st){
        										/**on supprimer l'article du document */
        										$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
        										if($delete_art_doc){
        											/**on supprime le document */
        											$delete_doc = $this->stock->delete_document($code_document,$matricule_en,$verify_typ_doc['code_doc']);
        											if($delete_doc){
        												/**liste des articles du document */
        												$output = $this->art_doc($code_document, $matricule_en);
        												$array = array(
        													'success' => 'article supprimer du document avec succès',
        													'art_doc' => $output
        												);
        											}else{
        												$array = 	array(
        													'success'   => '
        														<script>
        															swal.fire("ERREUR!","le document n\'a pas été supprimer! contactez l\'administrateur","info")
        														</script>
        													'
        												);
        											}
        											
        										}else{
        											$array = 	array(
        												'success'   => '
        													<script>
        														swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
        													</script>
        												'
        											);	
        										}
        									}else{
        										$array = $message_db;
        									}
        								}
        
        							}else{
        								$array = 	array(
        									'success'   => '
        										<script>
        											swal.fire("OUPS!","ce document n\'est plus modifiable. <b>la modification est uniquement possible le jour de l\'enregistrement</b>","info")
        										</script>
        									'
        								);
        							}
        
        						}else{
        							$array = 	array(
        								'success'   => '
        									<script>
        										swal.fire("OUPS!","le systeme ne trouve pas l\'article dans le stock! contactez l\'administrateur","info")
        									</script>
        								'
        							);
        						}
        					}else{
        						$array = 	array(
        							'success'   => '
        								<script>
        									swal.fire("OUPS!","le systeme ne trouve pas l\'article dans le document! contactez l\'administrateur","info")
        								</script>
        							'
        						);
        					}
        
        				}else if($verify_typ_doc['abrev_doc'] == 'MVT-S'){
        						
        					/** 
        					 * 1: on selectionne l'article dans le document en question
        					 * 2: on selectionne l'article dans le stock pour le depot
        					 * 3: on ajoute les quantités: qte art dans le document + qte art dans le stock
        					 * 4: on supprime l'article du document
        					 * 5:on supprime le document au besion
        					*/
        
        					/**1: on selectionne l'article dans le document en question */
        					$verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
        					if($verify_art_document){
        
        						/** 2: on selectionne l'article dans le stock pour le depot choisi*/
        						$verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
        						if($verify_art_stock){
        							/*$array = 	array(
        								'success'   => '
        									<script>
        										swal.fire("ok!","'.$verify_art_document['quantite'].'--'.$verify_art_stock['quantite'].'--'.$verify_typ_doc['date_creation_doc'].'","info")
        									</script>
        								'
        							);*/
        
        							/**on verifie si le document est encore modifiable */
        							$diff = $this->nbr_jour($verify_typ_doc['date_creation_doc']);
        							if($diff <= 1){
        								/**le document est modifiable */
        
        								/**on compte le nombre d'article dans un document
        								 * si c'est >1 on supprime uniquement l'article dans le document
        								 * si c'est c'est ==1 on l'article dans le document et le document lui mm
        								 */
        								
        								$nbr_artdocument = $this->stock->count_articles_docucment($code_document,$matricule_en);
        								if($nbr_artdocument > 1){
        									/**on ajoute au stock la quantité dans le document*/
        									$input_stock_val = array(
        										'code_employe'=> $matricule_emp,
        										'code_depot'=> $code_depot,
        										'code_entreprise'=> $matricule_en,
        										'quantite'=> ($verify_art_stock['quantite'] + $verify_art_document['quantite']),
        										'date_modifier_stock'=> dates()
        									);
        									$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
        									if($update_art_st){
        										/**on supprimer l'article du document */
        										$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
        										if($delete_art_doc){
        											/**liste des articles du document */
        											$output = $this->art_doc($code_document, $matricule_en);
        											$array = array(
        												'success' => 'article supprimer du document avec succès',
        												'art_doc' => $output
        											);
        										}else{
        											$array = 	array(
        												'success'   => '
        													<script>
        														swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
        													</script>
        												'
        											);	
        										}
        									}else{
        										$array = $message_db;
        									}
        								}else if($nbr_artdocument == 1){
        									/**on enlève du stock la quantité dans le document*/
        									$input_stock_val = array(
        										'code_employe'=> $matricule_emp,
        										'code_depot'=> $code_depot,
        										'code_entreprise'=> $matricule_en,
        										'quantite'=> ($verify_art_stock['quantite'] + $verify_art_document['quantite']),
        										'date_modifier_stock'=> dates()
        									);
        									$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
        									if($update_art_st){
        										/**on supprimer l'article du document */
        										$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
        										if($delete_art_doc){
        											/**on supprime le document */
        											$delete_doc = $this->stock->delete_document($code_document,$matricule_en,$verify_typ_doc['code_doc']);
        											if($delete_doc){
        												/**liste des articles du document */
        												$output = $this->art_doc($code_document, $matricule_en);
        												$array = array(
        													'success' => 'article supprimer du document avec succès',
        													'art_doc' => $output
        												);
        											}else{
        												$array = 	array(
        													'success'   => '
        														<script>
        															swal.fire("ERREUR!","le document n\'a pas été supprimer! contactez l\'administrateur","info")
        														</script>
        													'
        												);
        											}
        											
        										}else{
        											$array = 	array(
        												'success'   => '
        													<script>
        														swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
        													</script>
        												'
        											);	
        										}
        									}else{
        										$array = $message_db;
        									}
        								}
        
        							}else{
        								$array = 	array(
        									'success'   => '
        										<script>
        											swal.fire("OUPS!","ce document n\'est plus modifiable. <b>la modification est uniquement possible le jour de l\'enregistrement</b>","info")
        										</script>
        									'
        								);
        							}
        
        						}else{
        							$array = 	array(
        								'success'   => '
        									<script>
        										swal.fire("OUPS!","le systeme ne trouve pas l\'article dans le stock! contactez l\'administrateur","info")
        									</script>
        								'
        							);
        						}
        					}else{
        						$array = 	array(
        							'success'   => '
        								<script>
        									swal.fire("OUPS!","le systeme ne trouve pas l\'article dans le document! contactez l\'administrateur","info")
        								</script>
        							'
        						);
        					}
        
        				}else if($verify_typ_doc['abrev_doc'] == 'DI'){
        							
        					/** 
        					 * 1: on selectionne l'article dans le document en question
        					 * 2: on selectionne l'article dans le stock pour le depot
        					 * 3: on ajoute les quantités: qte art dans le document + qte art dans le stock
        					 * 4: on supprime l'article du document
        					 * 5:on supprime le document au besion
        					*/
        
        					/**1: on selectionne l'article dans le document en question */
        					$verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
        					if($verify_art_document){
        
        						/** 2: on selectionne l'article dans le stock pour le depot choisi*/
        						$verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
        						if($verify_art_stock){
        							
        							if($verify_art_document['quantite_plus'] > 0 && $verify_art_document['quantite_moins'] == 0){
        								
        								/**on verifie si le document est encore modifiable */
        
        								$diff = $this->nbr_jour($verify_typ_doc['date_creation_doc']);
        								if($diff <= 1){
        									/**le document est modifiable */
        
        									/**on compte le nombre d'article dans un document
        									 * si c'est >1 on supprime uniquement l'article dans le document
        									 * si c'est c'est ==1 on l'article dans le document et le document lui mm
        									 */
        									
        									$nbr_artdocument = $this->stock->count_articles_docucment($code_document,$matricule_en);
        									if($nbr_artdocument > 1){
        										/**on ajoute au stock la quantité dans le document*/
        										$input_stock_val = array(
        											'code_employe'=> $matricule_emp,
        											'code_depot'=> $code_depot,
        											'code_entreprise'=> $matricule_en,
        											'quantite'=> (abs($verify_art_stock['quantite'] - $verify_art_document['quantite_plus'])),
        											'date_modifier_stock'=> dates()
        										);
        										$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
        										if($update_art_st){
        											/**on supprimer l'article du document */
        											$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
        											if($delete_art_doc){
        												/**liste des articles du document */
        												$output = $this->art_doc($code_document, $matricule_en);
        												$array = array(
        													'success' => 'article supprimer du document avec succès',
        													'art_doc' => $output
        												);
        											}else{
        												$array = 	array(
        													'success'   => '
        														<script>
        															swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
        														</script>
        													'
        												);	
        											}
        										}else{
        											$array = $message_db;
        										}
        									}else if($nbr_artdocument == 1){
        										/**on enlève du stock la quantité dans le document*/
        										$input_stock_val = array(
        											'code_employe'=> $matricule_emp,
        											'code_depot'=> $code_depot,
        											'code_entreprise'=> $matricule_en,
        											'quantite'=> (abs($verify_art_stock['quantite'] - $verify_art_document['quantite_plus'])),
        											'date_modifier_stock'=> dates()
        										);
        										$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
        										if($update_art_st){
        											/**on supprimer l'article du document */
        											$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
        											if($delete_art_doc){
        												/**on supprime le document */
        												$delete_doc = $this->stock->delete_document($code_document,$matricule_en,$verify_typ_doc['code_doc']);
        												if($delete_doc){
        													/**liste des articles du document */
        													$output = $this->art_doc($code_document, $matricule_en);
        													$array = array(
        														'success' => 'article supprimer du document avec succès',
        														'art_doc' => $output
        													);
        												}else{
        													$array = 	array(
        														'success'   => '
        															<script>
        																swal.fire("ERREUR!","le document n\'a pas été supprimer! contactez l\'administrateur","info")
        															</script>
        														'
        													);
        												}
        												
        											}else{
        												$array = 	array(
        													'success'   => '
        														<script>
        															swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
        														</script>
        													'
        												);	
        											}
        										}else{
        											$array = $message_db;
        										}
        									}
        
        								}else{
        									$array = 	array(
        										'success'   => '
        											<script>
        												swal.fire("OUPS!","ce document n\'est plus modifiable. <b>la modification est uniquement possible le jour de l\'enregistrement</b>","info")
        											</script>
        										'
        									);
        								}
        
        
        							}else if($verify_art_document['quantite_plus'] == 0 && $verify_art_document['quantite_moins'] > 0){
        											
        								/**on verifie si le document est encore modifiable */
        
        								$diff = $this->nbr_jour($verify_typ_doc['date_creation_doc']);
        								if($diff <= 1){
        									/**le document est modifiable */
        
        									/**on compte le nombre d'article dans un document
        									 * si c'est >1 on supprime uniquement l'article dans le document
        									 * si c'est c'est ==1 on l'article dans le document et le document lui mm
        									 */
        									
        									$nbr_artdocument = $this->stock->count_articles_docucment($code_document,$matricule_en);
        									if($nbr_artdocument > 1){
        										/**on ajoute au stock la quantité dans le document*/
        										$input_stock_val = array(
        											'code_employe'=> $matricule_emp,
        											'code_depot'=> $code_depot,
        											'code_entreprise'=> $matricule_en,
        											'quantite'=> ($verify_art_stock['quantite'] + $verify_art_document['quantite_moins']),
        											'date_modifier_stock'=> dates()
        										);
        										$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
        										if($update_art_st){
        											/**on supprimer l'article du document */
        											$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
        											if($delete_art_doc){
        												/**liste des articles du document */
        												$output = $this->art_doc($code_document, $matricule_en);
        												$array = array(
        													'success' => 'article supprimer du document avec succès',
        													'art_doc' => $output
        												);
        											}else{
        												$array = 	array(
        													'success'   => '
        														<script>
        															swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
        														</script>
        													'
        												);	
        											}
        										}else{
        											$array = $message_db;
        										}
        									}else if($nbr_artdocument == 1){
        										/**on enlève du stock la quantité dans le document*/
        										$input_stock_val = array(
        											'code_employe'=> $matricule_emp,
        											'code_depot'=> $code_depot,
        											'code_entreprise'=> $matricule_en,
        											'quantite'=> ($verify_art_stock['quantite'] + $verify_art_document['quantite_moins']),
        											'date_modifier_stock'=> dates()
        										);
        										$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
        										if($update_art_st){
        											/**on supprimer l'article du document */
        											$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
        											if($delete_art_doc){
        												/**on supprime le document */
        												$delete_doc = $this->stock->delete_document($code_document,$matricule_en,$verify_typ_doc['code_doc']);
        												if($delete_doc){
        													/**liste des articles du document */
        													$output = $this->art_doc($code_document, $matricule_en);
        													$array = array(
        														'success' => 'article supprimer du document avec succès',
        														'art_doc' => $output
        													);
        												}else{
        													$array = 	array(
        														'success'   => '
        															<script>
        																swal.fire("ERREUR!","le document n\'a pas été supprimer! contactez l\'administrateur","info")
        															</script>
        														'
        													);
        												}
        												
        											}else{
        												$array = 	array(
        													'success'   => '
        														<script>
        															swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
        														</script>
        													'
        												);	
        											}
        										}else{
        											$array = $message_db;
        										}
        									}
        
        								}else{
        									$array = 	array(
        										'success'   => '
        											<script>
        												swal.fire("OUPS!","ce document n\'est plus modifiable. <b>la modification est uniquement possible le jour de l\'enregistrement</b>","info")
        											</script>
        										'
        									);
        								}
        
        
        							}else if($verify_art_document['quantite_plus'] == 0 && $verify_art_document['quantite_moins'] == 0){
        											
        								/**on verifie si le document est encore modifiable */
        
        								$diff = $this->nbr_jour($verify_typ_doc['date_creation_doc']);
        								if($diff <= 1){
        									/**le document est modifiable */
        
        									/**on compte le nombre d'article dans un document
        									 * si c'est >1 on supprime uniquement l'article dans le document
        									 * si c'est c'est ==1 on l'article dans le document et le document lui mm
        									 */
        									
        									$nbr_artdocument = $this->stock->count_articles_docucment($code_document,$matricule_en);
        									if($nbr_artdocument > 1){
        										/**on supprimer l'article du document */
												$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
												if($delete_art_doc){
													/**liste des articles du document */
													$output = $this->art_doc($code_document, $matricule_en);
													$array = array(
														'success' => 'article supprimer du document avec succès',
														'art_doc' => $output
													);
												}else{
													$array = 	array(
														'success'   => '
															<script>
																swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
															</script>
														'
													);	
												}
        									}else if($nbr_artdocument == 1){
        										/**on supprimer l'article du document */
												$delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
												if($delete_art_doc){
													/**on supprime le document */
													$delete_doc = $this->stock->delete_document($code_document,$matricule_en,$verify_typ_doc['code_doc']);
													if($delete_doc){
														/**liste des articles du document */
														$output = $this->art_doc($code_document, $matricule_en);
														$array = array(
															'success' => 'article supprimer du document avec succès',
															'art_doc' => $output
														);
													}else{
														$array = 	array(
															'success'   => '
																<script>
																	swal.fire("ERREUR!","le document n\'a pas été supprimer! contactez l\'administrateur","info")
																</script>
															'
														);
													}
													
												}else{
													$array = 	array(
														'success'   => '
															<script>
																swal.fire("ERREUR!","l\'article contactez n\'a pas été retirer du document! contactez l\'administrateur","info")
															</script>
														'
													);	
												}
        									}
        
        								}else{
        									$array = 	array(
        										'success'   => '
        											<script>
        												swal.fire("OUPS!","ce document n\'est plus modifiable. <b>la modification est uniquement possible le jour de l\'enregistrement</b>","info")
        											</script>
        										'
        									);
        								}
        
        
        							}
        							
        
        						}else{
        							$array = 	array(
        								'success'   => '
        									<script>
        										swal.fire("OUPS!","le systeme ne trouve pas l\'article dans le stock! contactez l\'administrateur","info")
        									</script>
        								'
        							);
        						}
        					}else{
        						$array = 	array(
        							'success'   => '
        								<script>
        									swal.fire("OUPS!","le systeme ne trouve pas l\'article dans le document! contactez l\'administrateur","info")
        								</script>
        							'
        						);
        					}
        
        				}else{
        					$array = 	array(
        						'success'   => '
        							<script>
        								swal.fire("OUPS!","ce type de document n\'est pas utile ici","info")
        							</script>
        						'
        					);
        				}
        			}else{
        				$array = 	array(
        					'success'   => '
        						<script>
        							swal.fire("ERREUR!","le système ne trouve pas le type de ce document. Contactez l\'administrateur","info")
        						</script>
        					'
        				);	
        			}
        		
        	}else{
        		$array = 	array(
        			'success'   => '
        				<script>
        					swal.fire("ERREUR!","contactez l\'administrateur","info")
        				</script>
        			'
        		);
        	}
        
        
        }else{
        	$array = array(
        		'success'   => '
        			<script>
        				swal.fire("OUPS!","vous n\'êtes pas habilité a modifier une fois l\'écriture pris en compte. contactez le chef d\'entreprise, son adjoin ou le chef d\'agence","error")
        			</script>
        		'
        	);
        }
		
		echo json_encode($array);
	}
	/**methode qui permet de supprimer un article dans un document fin */

	/********************************************** MES FONCTUIONS FIN *************************************************************** */
	


}
?>