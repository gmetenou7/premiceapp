<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->model('Agence/Agence_model','agence');
		$this->load->model('Service/Service_model','service');
    }


	/**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}

    /**page de service */
	public function index(){
		$this->logged_in();

        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

		$this->load->view('service/new_service');

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}


	/**affiche la liste des agences */
	public function agence(){
		$this->logged_in();
		$mat_en = session('users')['matricule'];
		$mat_ag = session('users')['matricule_ag'];

		$agences = $this->agence->get_agence($mat_en);
		if(!empty($agences)){
			$output = '';
			$output .= '
				<option></option>
			';
			foreach ($agences as $key => $value){
				if($mat_ag == $value['matricule_ag']){
					$output .='
						<option value="'.$value['matricule_ag'].'" selected>'.$value['nom_ag'].'</option>
					';
				}else{
					$output .='
						<option value="'.$value['matricule_ag'].'">'.$value['nom_ag'].'</option>
					';
				}
			}
		}
		echo json_encode($output);
	}

	/**inserer un nouveau service */
	public function service(){
		$this->logged_in();

		$this->form_validation->set_rules('entreprise', 'entreprise', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni l\' %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('agence', 'agence', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('service', 'service', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni le %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		if($this->form_validation->run()){

			if(!empty($this->input->post('agence'))){

				$input = array(
					'mat_en' => $this->input->post('entreprise'),
					'mat_ag' => $this->input->post('agence'),
					'matricule_serv' => code(8),
					'nom_serv' => $this->input->post('service'),
					'creer_le_serv' => dates()
				);

				/**inserer dans la base des données */
				$query = $this->service->new_service($input);
				if($query){	
					$array = array(
						'success' => '
							<div class="alert alert-success fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
								<div class="alert-text">service crée avec succès</div>
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
								<div class="alert-text">erreur survenu! service non crée</div>
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
					'mat_en' => $this->input->post('entreprise'),
					'mat_ag' => NULL,
					'matricule_serv' => code(8),
					'nom_serv' => $this->input->post('service'),
					'creer_le_serv' => dates()
				);

				/**inserer dans la base des données */
				$query = $this->service->new_service($input);
				if($query){	
					$array = array(
						'success' => '
							<div class="alert alert-success fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
								<div class="alert-text">service crée avec succès</div>
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
								<div class="alert-text">erreur survenu! service non crée</div>
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
				'error'   => true,
				'entreprise_error' => form_error('entreprise'),
				'agence_error' => form_error('agence'),
				'service_error' => form_error('service')
			);
		}
		echo json_encode($array);
	}

	/**liste des services */
	public function get_services(){
		$this->logged_in();
		$output ='';
		$matricule_en = session('users')['matricule'];
		$recherche = trim($this->input->post('input'));
		if($recherche != ''){
			$service = $this->service->recherche($matricule_en,$recherche);
			if(!empty($service)){
				foreach ($service as $key => $value) {
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
														'.$value['nom_serv'].'
													</a>
													<span class="kt-widget__desc">
														'.$value['matricule_serv'].'
													</span>
												</div>     
												<div class="kt-widget__toolbar">
													<button id="'.$value['matricule_serv'].'" class="btn btn-icon btn-circle btn-label-facebook edit_service"><i class="fa fa-edit"></i></button>
													<button id="'.$value['matricule_serv'].'" class="btn btn-icon btn-circle btn-label-twitter view_service"><i class="fa fa-eye"></i></button>
													
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
			$service = $this->service->all_service($matricule_en);
			
			if(!empty($service)){
				foreach ($service as $key => $value) {
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
														'.$value['nom_serv'].'
													</a>
													<span class="kt-widget__desc">
														'.$value['matricule_serv'].'
													</span>
												</div>     
												<div class="kt-widget__toolbar">
													<button id="'.$value['matricule_serv'].'" class="btn btn-icon btn-circle btn-label-facebook edit_service"><i class="fa fa-edit"></i></button>
													<button id="'.$value['matricule_serv'].'" class="btn btn-icon btn-circle btn-label-twitter view_service"><i class="fa fa-eye"></i></button>
													
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

	/**affiche un service en particulier */
	public function singleservices(){
		$this->logged_in();
		$output ='';
		$matricule_service = $this->input->post('mat_serv');
		$query = $this->service->single_service($matricule_service);
		if(!empty($query)){
			$output .='
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Matricule
						<span class="badge h5"><h5>'.$query['matricule_serv'].'</h5></span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Nom
						<span class="badge"><h5>'.$query['nom_serv'].'</h5></span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Date de creation
						<span class="badge h5"><h5>'.$query['creer_le_serv'].'</h5></span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Date de modification
						<span class="badge h5"><h5>'.$query['modifier_le_serv'].'</h5></span>
					</li>
				</ul>
			';
		}
		echo json_encode($output);
	}


	/**affiche les informations dans le formulaire de modification d'un service */
	public function edit(){
		$this->logged_in();
		$matricule_service = $this->input->post('mat_service');
		$query = $this->service->single_service($matricule_service);
		echo json_encode($query);
	}

	/**modifier un service dans la base des données */
	public function update(){
		$this->logged_in();
		$this->form_validation->set_rules('entreprise1', 'entreprise', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni l\' %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('agence1', 'agence', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('service1', 'service', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni le %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		if($this->form_validation->run()){
			$matricule = $this->input->post('matricule');

			if($this->input->post('agence1') == ''){
				$input = array(
					//'mat_en' => $this->input->post('entreprise1'),
					'mat_ag' => $this->input->post('agence_mat'),
					'nom_serv' => $this->input->post('service1'),
					'modifier_le_serv' => dates()
				);
				$query = $this->service->update_service($input,$matricule);
				if($query){	
					$array = array(
						'success' => '
							<div class="alert alert-success fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
								<div class="alert-text">service modifier avec succès</div>
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
								<div class="alert-text">erreur survenu! service non modifier</div>
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
					//'mat_en' => $this->input->post('entreprise1'),
					'mat_ag' => $this->input->post('agence1'),
					'nom_serv' => $this->input->post('service1'),
					'modifier_le_serv' => dates()
				);
				$query = $this->service->update_service($input,$matricule);
				if($query){	
					$array = array(
						'success' => '
							<div class="alert alert-success fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
								<div class="alert-text">service modifier avec succès</div>
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
								<div class="alert-text">erreur survenu! service non modifier</div>
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
				'error'   => true,
				'entreprise1_error' => form_error('entreprise1'),
				'agence1_error' => form_error('agence1'),
				'service1_error' => form_error('service1')
			);
		}
		echo json_encode($array);
	}

}