<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agence extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('Users/Users_model','users');
		$this->load->model('Agence/Agence_model','agence');
    }


	/**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}


    /**page agence */
	public function index(){
		$this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

        
		$this->load->view('agence/new_agence');
		
        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}

	/**liste des pays */
	public function pays(){
		$this->logged_in();

		$pays = $this->users->get_pays();
		if(!empty($pays)){
			$output = '';
			$output .= '<option></option>';
			foreach ($pays as $key => $value) {
				$output .='
					<option value="'.$value['nom_fr_fr'].'">'.$value['nom_fr_fr'].'</option>
				';
			}
		}

		echo json_encode($output);
	}

	/**Affiche l'agence dans le formulaire avant modification */
	public function edit(){
		$matricule = $this->input->post('mat_ag');
		$agence = $this->agence->get_single_agence($matricule);
		echo json_encode($agence);
	}

	/**afficher une agence en particulier */
	public function singleeagence(){
		$this->logged_in();
		$output ='';
		
		$matricule = trim($this->input->post('mat_ag'));
		$agence = $this->agence->get_single_agence($matricule);
		
		if(!empty($agence)){
			$output .= '
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Matricule
						<span class="badge"><h5>'.$agence['matricule_ag'].'</h5></span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Nom
						<span class="badge"><h5>'.$agence['nom_ag'].'</h5></span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Adresse
						<span class="badge"><h5>'.$agence['adress_ag'].'</h5></span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Activite
						<span class="badge"><h5>'.$agence['activite_ag'].'</h5></span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Pays
						<span class="badge"><h5>'.$agence['pays_ag'].'</h5></span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Contacts
						<span class="badge"><h5>'.$agence['telephone1_ag'].' / '.$agence['telephone2_ag'].' / '.$agence['email_ag'].'</h5></span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Date Création
						<span class="badge"><h5>'.$agence['creer_le_ag'].'</h5></span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Dernière modification
						<span class="badge"><h5>'.$agence['modifier_le_ag'].'</h5></span>
					</li>
				</ul>
			';
		}
		
		echo json_encode($output);
	}

	/**affiche la liste des agences */
	public function getagence(){
		$this->logged_in();
		$mat_en = session('users')['matricule'];
		$agences = $this->agence->get_agence($mat_en);

		if(!empty($agences)){
			$output = '';
			foreach ($agences as $key => $value) {
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
													'.$value['nom_ag'].'
												</a>
												<span class="kt-widget__desc">
													'.$value['matricule_ag'].'
												</span>
											</div>     
											<div class="kt-widget__toolbar">
												<button class="btn btn-icon btn-circle btn-label-facebook edit_ag" id="'.$value['matricule_ag'].'">
													<i class="fa fa-edit"></i>
												</button>
												<button class="btn btn-icon btn-circle btn-label-twitter view_ag" id="'.$value['matricule_ag'].'">
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
		}else{

		}
		echo json_encode($output);
	}

	/**creer une agence */
	public function agence(){
		$this->logged_in();

		/**validation du formulaire */

		$this->form_validation->set_rules('nom', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]|is_unique[agence.nom_ag]',
            array(
                    'required'      => 'Tu n\'as pas fourni le %s.',
                    'regex_match'     => 'caractère(s) non autorisé!',
                    'is_unique' => 'ce nom est déja utilisé, choisi un autre'
            )
        );

		$this->form_validation->set_rules('adresse', 'adresse', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni l\' %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('activite', 'activité', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni l\' %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('pays', 'pays', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
			array(
					'required'      => 'Tu n\'as pas fourni le %s.',
					'regex_match'     => 'caractère(s) non autorisé!'
			)
		);

		$this->form_validation->set_rules('tel1', 'telephone', 'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
			array(
					'required'      => 'Tu n\'as pas fourni le %s.',
					'regex_match'     => 'caractère(s) non autorisé!'
			)
		);

		$this->form_validation->set_rules('tel2', 'telephone', 'regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
            array(
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('email', 'email', 'valid_email|is_unique[agence.email_ag]',
            array(
                    'valid_email'     => 'email pas correct!',
                    'is_unique' => 'cet email est déja utilisé, choisi un autre'
                    
            )
        );

		if($this->form_validation->run()){
			$input = array(
				'mat_en' => session('users')['matricule'],
				'matricule_ag' =>code(9),
				'nom_ag' => $this->input->post('nom'),
				'adress_ag' => $this->input->post('adresse'),
				'activite_ag' => $this->input->post('activite'),
				'pays_ag' => $this->input->post('pays'),
				'telephone1_ag' => $this->input->post('tel1'),
				'telephone2_ag' => $this->input->post('tel2'),
				'email_ag' => $this->input->post('email'),
				'creer_le_ag' => dates()
			);
			/**inserer dans la base des données */
			$query = $this->agence->new_agence($input);

			if($query){
				$array = array(
					'success' => '
						<div class="alert alert-success fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
							<div class="alert-text">agence crée avec succès</div>
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
							<div class="alert-text">erreur survenu! agence non crée</div>
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
				'activite_error' => form_error('activite'),
				'pays_error' => form_error('pays'),
				'tel1_error' => form_error('tel1'),
				'tel2_error' => form_error('tel2'),
				'email_error' => form_error('email'),
			);
		}

		echo json_encode($array);
	}


	/**modifier les informations d'une agence */

	public function update(){
		$this->logged_in();

		/**validation du formulaire */

		$this->form_validation->set_rules('nom1', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni le %s.',
                    'regex_match'     => 'caractère(s) non autorisé!',
            )
        );

		$this->form_validation->set_rules('adresse1', 'adresse', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni l\' %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('activite1', 'activité', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni l\' %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('pays1', 'pays', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
			array(
					'regex_match'     => 'caractère(s) non autorisé!'
			)
		);

		$this->form_validation->set_rules('tele1', 'telephone', 'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
			array(
					'required'      => 'Tu n\'as pas fourni le %s.',
					'regex_match'     => 'caractère(s) non autorisé!'
			)
		);

		$this->form_validation->set_rules('tele2', 'telephone', 'regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
            array(
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('email1', 'email', 'valid_email',
            array(
                    'valid_email'     => 'email pas correct!',
                    
            )
        );

		if($this->form_validation->run()){
			$matricule =  $this->input->post('matricule');
			$input = array(
				'nom_ag' => $this->input->post('nom1'),
				'adress_ag' => $this->input->post('adresse1'),
				'activite_ag' => $this->input->post('activite1'),
				'pays_ag' => $this->input->post('pays1'),
				'telephone1_ag' => $this->input->post('tele1'),
				'telephone2_ag' => $this->input->post('tele2'),
				'email_ag' => $this->input->post('email1'),
				'modifier_le_ag' => dates()
			);
			$query = $this->agence->update_agence($input,$matricule);
			if($query){
				$array = array(
					'success' => '
						<div class="alert alert-success fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
							<div class="alert-text">agence modifier avec succès '.$matricule.'</div>
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
							<div class="alert-text">erreur survenu! agence non modifier</div>
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
				'activite1_error' => form_error('activite1'),
				'pays1_error' => form_error('pays1'),
				'tele1_error' => form_error('tele1'),
				'tele2_error' => form_error('tele2'),
				'email1_error' => form_error('email1'),
			);
		}

		echo json_encode($array);
	}
}