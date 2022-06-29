<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fournisseur extends CI_Controller {

	/**constructeur*/
	public function __construct(){
        parent::__construct();
        $this->load->model('Fournisseur/Fournisseur_model','fournisseur');
    }


    /**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}

    /**nouveau Fournisseur */
	public function index(){
		$this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

	
		$this->load->view('fournisseur/new_fournisseur');


        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}


	/**nouveau fournisseur*/
	public function newfournisseur(){
		$this->logged_in();

		$this->form_validation->set_rules('nom', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]|is_unique[fournisseur.nom_four]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!',
                'is_unique' => 'ce nom est déja utilisé, choisi un autre'
            )
        );

        $this->form_validation->set_rules('adresse', 'adresse', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('telephone1', 'telephone1', 'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]|is_unique[fournisseur.telephone_four]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!',
                'is_unique'  => 'numéro déjà! utilisé choisissez un autre'
            )
        );

        $this->form_validation->set_rules('telephone2', 'telephone2', 'regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('email', 'email', 'valid_email|is_unique[fournisseur.email_four]',
            array(
                'valid_email'     => 'email pas correct!',
                'is_unique' => 'cet email est déja utilisé, choisi un autre'
                    
            )
        );

        $this->form_validation->set_rules('site', 'site', 'valid_url',
            array(
                'valid_url'     => 'lien pas correct!'
                    
            )
        );

        if($this->form_validation->run()){

        	$input = array(
        		"matricule_four" => code(11),
        		"nom_four" => $this->input->post('nom'),
        		"email_four" => $this->input->post('email'),
        		"telephone_four" => $this->input->post('telephone1').','.$this->input->post('telephone2'),
        		"adresse_four" => $this->input->post('adresse'),
        		"site_interne_four" => $this->input->post('site'),
        		"mat_en" => session('users')['matricule'],
        		"creer_le_four" => dates()
        	);

        	/*insertion dans la base des données**/
        	$query = $this->fournisseur->new_fournisseur($input);

        	if($query){
				$array = array(
					'success' => '
						<div class="alert alert-success fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
							<div class="alert-text">fournisseur crée avec succès</div>
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
							<div class="alert-text">erreur survenu! fournisseur non crée</div>
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
				'telephone1_error' => form_error('telephone1'),
				'telephone2_error' => form_error('telephone2'),
				'email_error' => form_error('email'),
				'site_error' => form_error('site')
			);
        }

        echo json_encode($array);

	}

	/**afficher tous les fournissieurs*/
	public function all_fournisseur(){
		$this->logged_in();
		$output = '';
		$matricule = session('users')['matricule'];
		$fournisseur = $this->fournisseur->get_all_fournisseur($matricule);
		if (!empty($fournisseur)) {
			foreach ($fournisseur as $key => $value) {
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
												<a href="#" class="kt-widget__title">
													'.$value['nom_four'].'
												</a>
												<span class="kt-widget__desc">
													'.$value['matricule_four'].'
												</span>
											</div>     
											<div class="kt-widget__toolbar">
												<button id="'.$value['matricule_four'].'" class="btn btn-icon btn-circle btn-label-facebook edite">
													<i class="fa fa-edit"></i>
												</button>

												<button id="'.$value['matricule_four'].'" class="btn btn-icon btn-circle btn-label-twitter view">
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

		echo json_encode($output);
	}


	public function detail_fournisseur(){
		$this->logged_in();
		$output = '';
		$matricule = $this->input->post('matricule');
		$query = $this->fournisseur->get_single_fournisseur($matricule);
		if(!empty($query)){
			$output .='
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Matricule
						<b><span class="badge">'.$query['matricule_four'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Nom
						<b><span class="badge">'.$query['nom_four'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Email
						<b><span class="badge">'.$query['email_four'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Adresse
						<b><span class="badge">'.$query['adresse_four'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Telephone
						<b><span class="badge">'.$query['telephone_four'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Site internet
						<b><span class="badge">'.$query['site_interne_four'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Date création
						<b><span class="badge">'.$query['creer_le_four'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Dernière Modification
						<b><span class="badge">'.$query['modifier_le_four'].'</span></b>
					</li>
				</ul>
			';
		}
		echo json_encode($output);
	}

	/**affiche les informations dans le formulaire modal pour modification  */
	public function details_fournisseur(){
		$this->logged_in();
		$matricule = $this->input->post('matricule');
		$query = $this->fournisseur->get_single_fournisseur($matricule);
		if($query){
			$telephone = explode(",", $query['telephone_four']);
			$array = array(
				'test' => $matricule,
				'telephone1' => $telephone[0],
				'telephone2' => $telephone[1],
				'details' => $query
			);
		}

		echo json_encode($array);
	}


	/**modifier un fournisseur */
	public function updatefournisseur(){
		$this->logged_in();

		$this->form_validation->set_rules('nom1', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('adresse1', 'adresse', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('telephone11', 'telephone1', 'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('telephone21', 'telephone2', 'regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('email1', 'email', 'valid_email',
            array(
                'valid_email'     => 'email pas correct!'
                    
            )
        );

        $this->form_validation->set_rules('site1', 'site', 'valid_url',
            array(
                'valid_url'     => 'lien pas correct!'
                    
            )
        );


		if($this->form_validation->run()){
			$matricule = $this->input->post('matricule');
			$input = array(
        		"nom_four" => $this->input->post('nom1'),
        		"email_four" => $this->input->post('email1'),
        		"telephone_four" => $this->input->post('telephone11').','.$this->input->post('telephone21'),
        		"adresse_four" => $this->input->post('adresse1'),
        		"site_interne_four" => $this->input->post('site1'),
        		"modifier_le_four" => dates()
        	);

        	/*insertion dans la base des données**/
        	$query = $this->fournisseur->update_fournisseur($matricule,$input);

        	if($query){
				$array = array(
					'success' => '
						<div class="alert alert-success fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
							<div class="alert-text">fournisseur modifier avec succès</div>
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
							<div class="alert-text">erreur survenu! fournisseur non modifier</div>
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
				'telephone11_error' => form_error('telephone11'),
				'telephone21_error' => form_error('telephone21'),
				'email1_error' => form_error('email1'),
				'site1_error' => form_error('site1')
			);
        }

        echo json_encode($array);
	}





}