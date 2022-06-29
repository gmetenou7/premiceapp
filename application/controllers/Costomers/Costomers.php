<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Costomers extends CI_Controller {

	/**constructeur*/
	public function __construct(){
        parent::__construct();
        $this->load->model('Costomers/Costomer_model','costomer');
    }

    /**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}


    /**page du client*/
	public function index(){
		$this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

		$data['jour'] = jour();
		$data['mois'] = mois();
		$data['annee'] = annee();

		$this->load->view('clients/client',$data);

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}


	/**creer un nouveau client*/
	public function new_costomers(){
		$this->logged_in();

		$this->form_validation->set_rules('nom', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]|is_unique[client.nom_cli]',
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

        $this->form_validation->set_rules('telephone1', 'telephone1', 'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]|is_unique[client.telephone_cli]',
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

		$this->form_validation->set_rules('jour', 'jour', 'regex_match[/^[0-9\ ]+$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('mois', 'mois', 'regex_match[/^[0-9\ ]+$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('annee', 'annee', 'regex_match[/^[0-9\ ]+$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('email', 'email', 'valid_email|is_unique[client.email_cli]',
            array(
                'valid_email'     => 'email pas correct!',
                'is_unique' => 'cet email est déja utilisé, choisi un autre'
                    
            )
        );

		if($this->form_validation->run()){

			if(!empty(session('users')['matricule_emp'])){
				$dates = $this->input->post('annee').'-'.$this->input->post('mois').'-'.$this->input->post('jour');
				$input = array(
					'matricule_cli' => code(12),
					'mat_emp' => session('users')['matricule_emp'],
					'mat_en' => session('users')['matricule'],
					'nom_cli' => 	$this->input->post('nom'),
					'adresse_cli' =>	$this->input->post('adresse'),
					'telephone_cli' =>	$this->input->post('telephone1').','.$this->input->post('telephone2'),
					'email_cli' =>	$this->input->post('email'),
					'date_naiss' => $dates,
					'creer_le_cli' =>   dates()
				);
				//$array = array('success' => $input['mat_emp']);*/

				$query = $this->costomer->new_costomers($input);
				
				if($query){

					/**génère la carte de fidélité debut la function est dans le helper*/
					carte_fidelite($input['matricule_cli']);
					/**génère la carte de fidélité fin*/

					$array = array(
						'success' => '
							<div class="alert alert-success fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
								<div class="alert-text">client crée avec succès</div>
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
								<div class="alert-text">erreur survenu! client non crée</div>
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
							<div class="alert-text">erreur survenu! connectez-vous en tant que utilisateur</div>
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
				'jour_error' => form_error('jour'),
				'mois_error' => form_error('mois'),
				'annee_error' => form_error('annee'),
				'nom_error' => form_error('nom'),
				'adresse_error' => form_error('adresse'),
				'telephone1_error' => form_error('telephone1'),
				'telephone2_error' => form_error('telephone2'),
				'email_error' => form_error('email')
			);
		}

		echo json_encode($array);

	}


	/**affiche la liste des client*/
	public function list_costomers(){
		$this->logged_in();
		$matricule_en = session('users')['matricule'];
		$output = '';
		
		$rechercher = trim($this->input->post('input'));
		if(!empty($rechercher)){
			$clients = $this->costomer->recherche($matricule_en,$rechercher);
			if(!empty($clients)){
				foreach ($clients as $key => $value){
					$output .= '
					 	<div class="col-xl-4 col-lg-6">
		                    <!--begin::Portlet-->
		                    <div class="kt-portlet kt-portlet--height-fluid">
		                        <div class="kt-widget kt-widget--general-2">
		                            <div class="kt-portlet__body kt-portlet__body--fit">
		                                <div class="kt-widget__top">
		                                    <div class="kt-media kt-media--lg kt-media--circle">
		                                        <img src="assets/barcode/cartefidelite_a_imprimer/carte_fidelite_'.$value['matricule_cli'].'.jpg" alt="image">
		                                    </div>
		                                    <div class="kt-widget__wrapper">
		                                        <div class="kt-widget__label">
		                                            <a class="kt-widget__title">
		                                                '.$value['nom_cli'].'
		                                            </a>
		                                            <span class="kt-widget__desc">
		                                                '.$value['matricule_cli'].'
		                                            </span>
		                                        </div>     
		                                        <div class="kt-widget__toolbar">
					                                 <button id="'.$value['matricule_cli'].'" class="btn btn-icon btn-circle btn-label-facebook edit">
					                                	<i class="fa fa-edit"></i>
					                                </button>
					                                <a href="assets/barcode/cartefidelite_a_imprimer/carte_fidelite_'.$value['matricule_cli'].'.jpg"
					                                	class="btn btn-icon btn-circle btn-label-twitter"
															download="carte_'.$value['nom_cli'].'.png">
															<i class="fa fa-arrow-circle-down"></i>
														</a>
					                                <button id="'.$value['matricule_cli'].'" class="btn btn-icon btn-circle btn-label-linkedin view">
					                                	<i class="fa fa-eye"></i>
					                                </button>';
													if(empty(session('users')['matricule_serv'])){
														$output .= '<button id="'.$value['matricule_cli'].'" class="btn btn-icon btn-circle btn-label-linkedin reattribut">
															<i class="fa fa-exchange-alt"></i>
														</button>';
													}
									$output .= '
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

				$array = array(
					'success' => $output
				);
			}else{
				$array = array(
					'success' => '<b class="text-danger">aucun client ne correspond à cette recherche</b>'
				);
			}
		}else{

			/**************pagination debut************************* */
			$config = array();
			$config["base_url"] = "#";
			$config["total_rows"] = $this->costomer->count_all_costomer($matricule_en);
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
			$config["cur_tag_open"] = "<a href='#'><li class='page-item active'><span class='page-link'><span class='sr-only'>(current)</span>";
			$config["cur_tag_close"] = "</span></li></a>";
			$config["num_tag_open"] = '<li class="page-item page-link">';
			$config["num_tag_close"] = "</li>";
			$config["num_links"] = 1;
			$this->pagination->initialize($config);

			$page = (($this->uri->segment(2) - 1) * $config["per_page"]);

			/**************pagination fin************************* */

			
			$clients = $this->costomer->all_client($matricule_en,$config["per_page"], $page);
			if(!empty($clients)){
				foreach ($clients as $key => $value) {
					$output .= '
					 	<div class="col-xl-4 col-lg-6">
		                    <!--begin::Portlet-->
		                    <div class="kt-portlet kt-portlet--height-fluid">
		                        <div class="kt-widget kt-widget--general-2">
		                            <div class="kt-portlet__body kt-portlet__body--fit">
		                                <div class="kt-widget__top">
		                                    <div class="kt-media kt-media--lg kt-media--circle">
		                                        <img src="assets/barcode/cartefidelite_a_imprimer/carte_fidelite_'.$value['matricule_cli'].'.jpg" alt="image">
		                                    </div>
		                                    <div class="kt-widget__wrapper">
		                                        <div class="kt-widget__label">
		                                            <a class="kt-widget__title">
		                                                '.$value['nom_cli'].'
		                                            </a>
		                                            <span class="kt-widget__desc">
		                                                '.$value['matricule_cli'].'
		                                            </span>
		                                        </div>     
		                                        <div class="kt-widget__toolbar">
					                                 <button id="'.$value['matricule_cli'].'" class="btn btn-icon btn-circle btn-label-facebook edit">
					                                	<i class="fa fa-edit"></i>
					                                </button>
					                                <a href="assets/barcode/cartefidelite_a_imprimer/carte_fidelite_'.$value['matricule_cli'].'.jpg"
					                                	class="btn btn-icon btn-circle btn-label-twitter"
															download="carte_'.$value['nom_cli'].'.png">
															<i class="fa fa-arrow-circle-down"></i>
														</a>
					                                <button id="'.$value['matricule_cli'].'" class="btn btn-icon btn-circle btn-label-linkedin view">
					                                	<i class="fa fa-eye"></i>
					                                </button>
													';
													if(empty(session('users')['matricule_serv'])){
														$output .= '<button id="'.$value['matricule_cli'].'" class="btn btn-icon btn-circle btn-label-linkedin reattribut">
															<i class="fa fa-exchange-alt"></i>
														</button>';
													}
									$output .= '
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

				$array = array(
					'success' => $output,
					'pagination' => $this->pagination->create_links()
				);
			}else{
				$array = array(
					'success' => '<b>aucun client créer pour le moment</b>'
				);
			}
		}
			

		echo json_encode($array);
	}

	/**affichier les informations d'un client en particulier*/
	public function single_costomers(){
		$this->logged_in();
		$matricule_cli = $this->input->post('mat_client');
		$output = '';
		$client = $this->costomer->single_client($matricule_cli);
		if(!empty($client)){
			$output .= '
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Matricule
						<span class="badge badge-primary">'.$client['matricule_cli'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Nom
						<b><span class="badge">'.$client['nom_cli'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Telephone
						<b><span class="badge">'.$client['telephone_cli'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Adresse
						<b><span class="badge">'.$client['adresse_cli'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Client enrégistré par
						<b><span class="badge">'.$client['nom_emp'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Date de création
						<b><span class="badge">'.$client['creer_le_cli'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Dernière Modification
						<b><span class="badge">'.$client['modifier_le_cli'].'</span></b>
					</li>
				</ul>

			';
		}
		echo json_encode($output);
	}

	/**selectionner un client en particulier*/
	public function singlecostomers(){
		$this->logged_in();
		$matricule_cli = $this->input->post('mat_client');
		$client = $this->costomer->single_client($matricule_cli);
		if(!empty($client)){
			$telephone = explode(",", $client['telephone_cli']);
			$array = array(
				'telephone1' => $telephone[0],
				'telephone2' => $telephone[1],
				'info' => $client
			);
		}
		echo json_encode($array);
	}

	/***modifier un client particulier***/
	public function update_costomers(){
		$this->logged_in();

		$this->form_validation->set_rules('nom1', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
                //'is_unique' => 'ce nom est déja utilisé, choisi un autre'
            )
        );

        $this->form_validation->set_rules('adresse1', 'adresse', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('telephone11', 'telephone1', 'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]|is_unique[client.telephone_cli]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!',
                'is_unique'  => 'numéro déjà! utilisé choisissez un autre'
            )
        );

        $this->form_validation->set_rules('telephone21', 'telephone2', 'regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('email1', 'email', 'valid_email',
            array(
                'valid_email'     => 'email pas correct!',
                'is_unique' => 'cet email est déja utilisé, choisi un autre'
                    
            )
        );

		if($this->form_validation->run()){
			$matricule = $this->input->post('matricule');
			$input = array(
				'nom_cli' => 	$this->input->post('nom1'),
				'adresse_cli' =>	$this->input->post('adresse1'),
				'telephone_cli' =>	$this->input->post('telephone11').','.$this->input->post('telephone21'),
				'email_cli' =>	$this->input->post('email1'),
				'modifier_le_cli' =>   dates()
			);

			$query = $this->costomer->update_costomers($matricule,$input);

			if($query){
				$array = array(
					'success' => '
						<div class="alert alert-success fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
							<div class="alert-text">client modifier avec succès</div>
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
							<div class="alert-text">erreur survenu! client non modifier</div>
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
				'email1_error' => form_error('email1')
			);
		}

		echo json_encode($array);



	}


	/**liste des employé de l'entreprise pour pouvoir faire la réattribution de client */
	public function employereattribut(){
		$this->logged_in();

		$matricule_en = session('users')['matricule'];
		$output = "";
		/**liste des employé d'une entreprise */
		$query = $this->costomer->all_employe($matricule_en);
		if(!empty($query)){
			foreach ($query as $key => $value) {
				$output .= '
					<option value="'.$value['matricule_emp'].'">'.$value['nom_emp'].'</option>
				';
			}
		}else{
			$output .= '<option disabled>aucun employé enregistrer dans le système</option>';
		}

		echo json_encode($output);
	}

	/** operation sur la reattribution  */
	public function reattribuer_client(){
		$this->logged_in();
		$this->form_validation->set_rules('employe', 'employe', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni l\' %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );
		if($this->form_validation->run()){
			$matricule_en = session('users')['matricule'];
			$matriculeclient = $this->input->post('matclient');
			$matriculeemploye = $this->input->post('employe');

			/**on selectionne le client pour gard son employé dans l'historique */
			$client = $this->costomer->getsingleclient($matricule_en,$matriculeclient);
			if(!empty($client)){

				$inputval = array(
					'code_client' => $client['matricule_cli'],
					'code_emp' => $client['mat_emp'],
					'code_en' => $client['mat_en'],
					'date_creer' => dates()
				);
				$result = $this->costomer->savehistoriquemutation($inputval);
				if($result){

					$valupdate = array(
						'mat_emp'=>$matriculeemploye
					);
					/**on update l'employé du client */
					$update = $this->costomer->updateempcli($valupdate,$matriculeclient);
					if($update){
						$array = array(
							'success' => '<b class="text-success">réattribution éffectué</b>'
						);
					}else{
						$array = array(
							'success' => '<b class="text-danger">erreur survenu, réattribution non pris en compte</b>'
						);	
					}
				}else{
					$array = array(
						'success' => '<b class="text-danger">réattribution non pris en compte, historique non enrégistrer</b>'
					);
				}
			}else{
				$array = array(
					'success' => '<b class="text-danger">le système ne retrouve pas ce client</b>'
				);
			}
			
		}else{
			$array = array(
				'error'   => true,
				'employe_error' => form_error('employe')
			);
		}

		echo json_encode($array);
	}

	/**historique de reattribution */
	public function historique_reattribuer_client(){
		$this->logged_in();

		$matricule_en = session('users')['matricule'];
		$codeclient = $this->input->post('matclient');

		$query = $this->costomer->getallhistorique($matricule_en,$codeclient);
		$output ="";
		if(!empty($query)){

			foreach ($query as $key => $value) {
				$output .='
					<tr>
						<td>'.$value['nom_emp'].'</td>
						<td>'.dateformat($value['date_creer']).'</td>
					</tr>
				';
			}
			$array = array(
				'success' => $output
			);
		}else{
			$array = array(
				'success' => '<tr><td colspan="2"><b class="text-danger">Pas d\'historique pour le moment</b></td></tr>'
			);
		}
		echo json_encode($array);
	}


}