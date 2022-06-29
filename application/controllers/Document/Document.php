<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('Document/Document_model','document');
	}

	/**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}


    /**document */
	public function index(){
		$this->logged_in();
		
		$this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');
		
		$data['categorie'] = array(
			'1' => 'vente',
			'2' => 'stock'
		);
		
		$this->load->view('document/document',$data);
		

		$this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}

	/**afficher toute la liste des types documents */
	public function all_doc(){
		$this->logged_in();
		$mat_en = session('users')['matricule'];
		$docs = $this->document->get_doc($mat_en);

		if(!empty($docs)){
			$output = '';
			foreach ($docs as $key => $value) {
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
													'.$value['intiutle_doc'].' ('.$value['abrev_doc'].')
												</a>
												<span class="kt-widget__desc">
													'.$value['code_doc'].'
												</span>
											</div>     
											<div class="kt-widget__toolbar">
												<button class="btn btn-icon btn-circle btn-label-facebook edit_doc" id="'.$value['code_doc'].'">
													<i class="fa fa-edit"></i>
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

	/**enregistrer un type de document */
	public function new_doc(){

		/**validation du formulaire */
		$this->form_validation->set_rules('libel_doc', 'libellé', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni le %s.',
                    'regex_match'     => 'caractère(s) non autorisé!',
            )
        );
		$this->form_validation->set_rules('ab_doc', 'abreviation', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni l\' %s.',
                    'regex_match'     => 'caractère(s) non autorisé!',
            )
        );
		$this->form_validation->set_rules('categorie', 'catégorie', 'required|regex_match[/^[a-z\ ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni la %s.',
                    'regex_match'     => 'caractère(s) non autorisé!',
            )
        );

		

		if($this->form_validation->run()){
			$this->logged_in();
			$mat_en = session('users')['matricule'];
			$inputs = array(
				'code_doc'=> code(10),
				'intiutle_doc'=> $this->input->post('libel_doc'),
				'abrev_doc'=> $this->input->post('ab_doc'),
				'categorie_doc'=> $this->input->post('categorie'),
				'date_creer_doc'=> dates(),
				'code_entr'=> $mat_en
			);

			$docs = $this->document->save_doc($inputs);
			if($docs){
				$array = array(
					'success' => '
						<div class="alert alert-success fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
							<div class="alert-text">document crée avec succès</div>
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
							<div class="alert-text">erreur survenu! document non crée</div>
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
				'libel_doc_error' => form_error('libel_doc'),
				'ab_doc_error' => form_error('ab_doc'),
				'categorie_error' => form_error('categorie')
			);
		}
		echo json_encode($array);
	}

	/**affiche les informations dans le formulaire de modification */
	public function edit_doc(){
		$this->logged_in();
		$output = '';
		$code_doc = $this->input->post('mat_doc');
		if(!empty($code_doc)){
			$docs = $this->document->get_single_doc($code_doc);
			if($docs){
				$output = $docs;
			}
		}
		echo json_encode($output);
	}

	/**modifier un type de document */
	public function update_doc(){

		/**validation du formulaire */
		$this->form_validation->set_rules('libel_doc1', 'libellé', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni le %s.',
                    'regex_match'     => 'caractère(s) non autorisé!',
            )
        );
		$this->form_validation->set_rules('ab_doc1', 'abreviation', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni l\' %s.',
                    'regex_match'     => 'caractère(s) non autorisé!',
            )
        );
		$this->form_validation->set_rules('categorie1', 'catégorie', 'required|regex_match[/^[a-z\ ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni la %s.',
                    'regex_match'     => 'caractère(s) non autorisé!',
            )
        );

		

		if($this->form_validation->run()){
			$this->logged_in();

			$code_doc = $this->input->post('matricule');
			
			$inputs = array(
				'intiutle_doc'=> $this->input->post('libel_doc1'),
				'abrev_doc'=> $this->input->post('ab_doc1'),
				'categorie_doc'=> $this->input->post('categorie1'),
				'date_modif_doc'=> dates(),
			);

			$docs = $this->document->update_doc($inputs,$code_doc);
			if($docs){
				$array = array(
					'success' => '
						<div class="alert alert-success fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
							<div class="alert-text">document modifier avec succès</div>
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
							<div class="alert-text">erreur survenu! document non modifier</div>
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
				'libel_doc1_error' => form_error('libel_doc1'),
				'ab_doc1_error' => form_error('ab_doc1'),
				'categorie1_error' => form_error('categorie1')
			);
		}
		echo json_encode($array);
	}

	


	



}