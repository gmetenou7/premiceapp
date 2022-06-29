<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sav extends CI_Controller {


	/**constructeur*/
	public function __construct(){
        parent::__construct();
        $this->load->model('Costomers/Costomer_model','costomer');
		$this->load->model('Sav/Sav_model','sav');
    }

	/**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}

    /**sav(service après vente) */
	public function index(){
		$this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

		$this->load->view('sav/maintenance');


        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}


	/** générer le rapport de maintenance sur une période */
	public function rapport_maintenance(){
		$this->logged_in();

		$this->form_validation->set_rules('start', 'date de debut', 'required',
            array(
                'required'      => 'Tu n\'as pas fourni la %s.',
            )
        );

        $this->form_validation->set_rules('end', 'date de fin', 'required',
			array(
				'required'      => 'Tu n\'as pas fourni la %s.',
			)
        );

		if($this->form_validation->run()){

			$datedebut = $this->input->post('start');
			$datefin = $this->input->post('end');

			$debut = date('Y-m-d',strtotime($datedebut));
			$fin = date('Y-m-d',strtotime($datefin));

			$output ="";
			$matricule_en = session('users')['matricule'];
			/**selectionne la liste des maintenance en fonction d'une periode */
			$rapport_maintenance = $this->sav->rapport_maintenance($matricule_en,$debut,$fin);
			$montantmaint = 0;
			if(!empty($rapport_maintenance)){
				foreach ($rapport_maintenance as $key => $value) {
					$montantmaint +=$value['prix_reparation'];
					$output .='
						<tr>
							<td>'.$value['code_reparation'].'</td>
							<td>'.$value['nom_equip'].'</td>
							<td>'.$value['nom_cli'].'</td>
							<td>'.numberformat($value['prix_reparation']).'</td>
							<td>'.$value['statut_reparation'].'</td>
							<td>'.$value['nom_emp'].'</td>
							<td>'.dateformat($value['date_creer_reparation']).'</td>
						</tr>
					';
				}
			}else{
				$array = array(
					'maintenance' => '<tr><td colspan="7"><b class="text-danger">Pas de maintenance sur cette période du '.$debut.' au '.$fin.'</b></td></tr>'
				);	
			}

			$outputs ="";
			/**selectionne la liste des diagnostiques en fonction d'une periode */
			$rapport_diagnostique = $this->sav->rapport_diagnostique($matricule_en,$debut,$fin);
			$montantdiag = 0;
			if(!empty($rapport_diagnostique)){
				foreach ($rapport_diagnostique as $key => $values) {
					$montantdiag +=$values['prix_diagnostique'];
					$outputs .='
						<tr>
							<td>'.$values['matricule_diagnostique'].'</td>
							<td>'.$values['nom_equip'].'</td>
							<td>'.$values['nom_cli'].'</td>
							<td>'.numberformat($value['prix_diagnostique']).'</td>
							<td>'.$values['nom_emp'].'</td>
							<td>'.dateformat($value['date_diagnostique']).'</td>
						</tr>
					';
				}
			}else{
				$array = array(
					'diagnostique' => '<tr><td colspan="6"><b class="text-danger">Pas de diagnostique sur cette période du '.$debut.' au '.$fin.'</b></td></tr>'
				);	
			}


			$array = array(
				'maintenance' => $output,
				'diagnostique' => $outputs,
				'total' => numberformat(($montantmaint + $montantdiag))
			);

		}else{
			$array = array(
				'error'   => true,
				'start_error' => form_error('start'),
				'end_error' => form_error('end')
			);
		}

		echo json_encode($array);
	}


	/**affiche la liste des clients */
	public function list_costomers(){
		$this->logged_in();
		$output ='';
		$matricule_en = session('users')['matricule'];
		$clients = $this->sav->all_client($matricule_en);
		if(!empty($clients)){
			$output .= '<option value=""></option>';
			foreach ($clients as $key => $value) {
				$output .= '<option value="'.$value['matricule_cli'].'">'.$value['nom_cli'].'</option>';
			}
		}
		echo json_encode($output);
	}

	/**statut d'une maintenance*/
	public function statut_equip(){
		$output ='';
		$array = array(
			'recu','encours','resolu','non_resolu'
		);
		foreach ($array as $key => $value) {
			$output .='<option value="'.$value.'">'.$value.'</option>';
		}

		echo json_encode($output);
	}

	/**enrégistrer un équipement dans la base des données pour le SAV*/
	public function save_equip(){
		$this->logged_in();

		$this->form_validation->set_rules('nom', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('num_serie', 'numéro de serie', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('ref_equip', 'référence', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
				
            )
        );

		$this->form_validation->set_rules('client', 'client', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('desciption', 'desciption', 'regex_match[/^[a-zA-Z0-9._éè?!,@$#êÉÈÊàôÀÔïÏ\'\-\n\r ]+$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );
        

		if($this->form_validation->run()){
			if(!empty(session('users')['matricule_emp'])){
				$code_equip = code(10);
				$input = array(
					'code_equip' => $code_equip,
					'nom_equip' => $this->input->post('nom'), 
					'numero_serie_equip' => $this->input->post('num_serie'),
					'reference_equip' => $this->input->post('ref_equip'), 
					'description_equip' => $this->input->post('desciption'), 
					'date_creer_equip' => dates(),
					'code_client' => $this->input->post('client'),
					'code_employe' => session('users')['matricule_emp'],
					'code_entreprise' => session('users')['matricule']
				);

				/**enregistrer un equipement pour sav dans la base des données*/
				$isok = $this->sav->save_equipement($input);

				if($isok){
					$array = array(
						'success' => '
							<div class="alert alert-success fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
								<div class="alert-text">équipement crée avec succès. le code de l\équipement est: '.$code_equip.'</div>
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
								<div class="alert-text">erreur survenu! équipement non crée</div>
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
							<div class="alert-text">erreur survenu! connectez vous en tant que utilisateur</div>
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
				'num_serie_error' => form_error('num_serie'),
				'ref_equip_error' => form_error('ref_equip'),
				'client_error' => form_error('client'),
				'desciption_error' => form_error('desciption')
			);
		}

		echo json_encode($array);
	}

	/**modifier un equipement */
	public function updateequip(){
		$this->logged_in();

		$this->form_validation->set_rules('nom1', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('num_serie1', 'numéro de serie', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('client1', 'client', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
				'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
				
            )
        );

		$this->form_validation->set_rules('ref_equip1', 'référence', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
				
            )
        );

        $this->form_validation->set_rules('desciption1', 'desciption', 'regex_match[/^[a-zA-Z0-9._éè?!,@$#êÉÈÊàôÀÔïÏ\'\-\n\r ]+$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		if($this->form_validation->run()){

			/**matricule de l'équipement */
			$matricule = $this->input->post('matricule');

			$input = array(
				'nom_equip' => $this->input->post('nom1'), 
				'numero_serie_equip' => $this->input->post('num_serie1'),
				'reference_equip' => $this->input->post('ref_equip1'), 
				'description_equip' => $this->input->post('desciption1'), 
				'date_modif_equip' => dates(),
				'code_client' => $this->input->post('client1')
			);

			$isok = $this->sav->update_equip($input,$matricule);
			if($isok){
				$array = array(
					'success' => '
						<div class="alert alert-success fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
							<div class="alert-text">équipement modifié avec succès</div>
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
							<div class="alert-text">erreur survenu! équipement non modifié</div>
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
				'num_serie1_error' => form_error('num_serie1'),
				'ref_equip1_error' => form_error('ref_equip1'),
				'desciption1_error' => form_error('desciption1'),
				'cleint1_error' => form_error('client1')
			);
		}

		echo json_encode($array);
	}

	/**liste des équipements mis a jour */
	public function all_equip($num_page = NULL){
		$this->logged_in();

		$output ='';
		
		$matricule_en = session('users')['matricule'];

			$config = array();
			$config["base_url"] = "#";
			$config["total_rows"] = $this->sav->count_all_equip($matricule_en);
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

			//$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

			$page = (($this->uri->segment(2) - 1) * $config["per_page"]);


		$recherche = trim($this->input->post('val'));
		if(!empty($recherche)){
			$query = $this->sav->recherche_equipement($matricule_en,$recherche);
			if(!empty($query)){
				foreach ($query as $key => $value){
					$output .= '
						<tr>
							<th scope="row">'.$value['code_equip'].'</th>
							<td>'.$value['nom_equip'].'</td>
							<td>'.$value['reference_equip'].'</td>
							<td><span class="text-success">'.$value['nom_emp'].'</span></td>
							<td>
								<div class="kt-widget__toolbar">
									<i class="fa fa-eye view" id="'.$value['code_equip'].'"></i>
									<i class="fa fa-pen edit" id="'.$value['code_equip'].'"></i>
								</div> 
							</td>
						</tr>
					';
				}
				$outputs = array(
					'result' =>  $output
				);
			}else{
				$outputs = array(
					'result' => '<tr><td colspan="5"><b class="text-danger">Aucun équipement ne correspond à ce client</b></td></tr>'
				);
			}
		}else{

			$query = $this->sav->all_equipement($matricule_en,$config["per_page"], $page);
			if(!empty($query)){
				foreach ($query as $key => $value) {
					$output .= '
						<tr>
							<th scope="row">'.$value['code_equip'].'</th>
							<td>'.$value['nom_equip'].'</td>
							<td>'.$value['reference_equip'].'</td>
							<td><span class="text-success">'.$value['nom_emp'].'</span></td>
							<td>
								<div class="kt-widget__toolbar">
									<i class="fa fa-eye view" id="'.$value['code_equip'].'"></i>
									<i class="fa fa-pen edit" id="'.$value['code_equip'].'"></i>
								</div> 
							</td>
						</tr>
					';
				}

				$outputs = array(
					'list_equip' => $output,
					'links' => $this->pagination->create_links()
				);
			}
		}
		
		echo json_encode($outputs);
	}


	/**afficher les détails d'un sav équipement*/
	public function single_equipement(){
		$output ='';
		$this->logged_in();
		$equip_mat = $this->input->post('equip_code');
		$query = $this->sav->single_equipement($equip_mat);
		if (!empty($query)) {
			$output .='
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Code
						<b><span>'.$query['code_equip'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Nom
						<span>'.$query['nom_equip'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Numéro de serie
						<span>'.$query['numero_serie_equip'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Référence
						<span>'.$query['reference_equip'].'</span>
					</li>
					
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Description
						<span class="text-primary">'.$query['description_equip'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Date d\'enrégistrement
						<span class="text-danger">'.dateformat($query['date_creer_equip']).'</span>
					</li>
					
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Enregistrer par
						<span>'.$query['nom_emp'].'</span>
					</li>

					<li class="list-group-item d-flex justify-content-between align-items-center">
						Equipement de
						<span class="text-info">'.$query['nom_cli'].'</span>
					</li>
				</ul>
			';
		}
		echo json_encode($output);
	}



	/**afficher les détails d'un equipement dans un formulaire pour modification */
	public function editequip(){
		$this->logged_in();
		$equip_mat = $this->input->post('equip_mat');
		$query = $this->sav->single_equipement($equip_mat);
		echo json_encode($query);
	}



	/**gestion des diagnostique */
	public function save_diagnost(){
		$this->logged_in();

		$this->form_validation->set_rules('equipement', 'équipement', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni l\' %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('prix', 'prix', 'required|regex_match[/^[\d]*[]?[\d]{2}$/]', /* /^[\d]*[.,]?[\d]{2}$/ */
            array(
				'required'      => 'quel est le %s du serice?',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('diagnostique', 'diagnostique', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\s\- ]+$/]',
            array(
				'required'      => 'quel est le %s?',
                'regex_match'     => 'caractère(s) non autorisé!'
				
            )
        );

		if($this->form_validation->run()){
			$matricule_en = session('users')['matricule'];
			$matricule_emp = session('users')['matricule_emp'];
			$code_diagnostique = code(10);
			if(!empty($matricule_emp)){
				$input = array(
					'matricule_diagnostique' => $code_diagnostique,
					'diagnostique' => $this->input->post('diagnostique'),
					'prix_diagnostique' => $this->input->post('prix'),
					'date_diagnostique' => dates(),
					'code_employe' => $matricule_emp,
					'code_equipement' => $this->input->post('equipement'),
					'code_entreprise' => $matricule_en
				);
				/**insertion dans la base des données */

				$query = $this->sav->save_diagnostique($input);
				if($query){
					$array = array(
						'success' => '
							<div class="alert alert-success fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
								<div class="alert-text">diagnostique enrégistrer avec succès</div>
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
								<div class="alert-text">erreur survenu! diagnostique non enregistrer</div>
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
							<div class="alert-text">erreur survenu! connectez vous en tant que utilisateur</div>
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
				'equipement_error' => form_error('equipement'),
				'prix_error' => form_error('prix'),
				'diagnostique_error' => form_error('diagnostique')
			);
		}

		echo json_encode($array);
	}

	/**liste des equipements pour faire le diagnostique*/
	public function diagnostique_equip(){
		$this->logged_in();
		$output = "";
		$matricule_en = session('users')['matricule'];
		$query = $this->sav->all_equipement_diagnostique($matricule_en);
		if($query){
			$output .= '<option></option>';
			foreach ($query as $key => $value){
				$output .= '<option value="'.$value['code_equip'].'">'.$value['code_equip'].' / '.$value['nom_equip'].' / '.$value['nom_cli'].'</option>';
			}
		}else{
			$output .= '<option disabled>aucun equipement trouvé</option>';
		}
		echo json_encode($output);
	}

	/** liste des diagnostiques */
	public function all_diagnostique($num_page = NULL){
		$this->logged_in();
		$output ='';
		$matricule_en = session('users')['matricule'];

		$config = array();
		$config["base_url"] = "#";
		$config["total_rows"] = $this->sav->count_all_diagnostique($matricule_en);
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

		$recherche = trim($this->input->post('val'));
		if(!empty($recherche)){
			$query = $this->sav->recherche_diagnostique($matricule_en,$recherche); 
			if(!empty($query)){
				foreach($query as $key => $value){
					$output .= '
						<tr>
							<th scope="row">'.$value['matricule_diagnostique'].'</th>
							<td scope="row">'.$value['nom_equip'].'</td>
							<td>'.$value['prix_diagnostique'].'</td>
							<td>'.$value['nom_emp'].'</td>
							<td>
								<div class="kt-widget__toolbar">
									<i class="fa fa-eye view_diagnost" id="'.$value['matricule_diagnostique'].'"></i>
									<i class="fa fa-pen edit_diagnost" id="'.$value['matricule_diagnostique'].'"></i>
								</div> 
							</td>
						</tr>
					';
				}

				$outputs = array(
					'list_diag' => $output
				);
			}else{
				$outputs = array(
					'list_diag' => '<tr><td colspan="5"><b class="text-danger">Aucun diagnostique ne correspond à ce client</b></td></tr>'
				);
			}
		}else{
			$query = $this->sav->all_diagnostique($matricule_en,$config["per_page"], $page);
			if(!empty($query)){
				foreach ($query as $key => $value) {
					$output .= '
						<tr>
							<th scope="row">'.$value['matricule_diagnostique'].'</th>
							<td scope="row">'.$value['nom_equip'].'</td>
							<td>'.$value['prix_diagnostique'].'</td>
							<td>'.$value['nom_emp'].'</td>
							<td>
								<div class="kt-widget__toolbar">
									<i class="fa fa-eye view_diagnost" id="'.$value['matricule_diagnostique'].'"></i>
									<i class="fa fa-pen edit_diagnost" id="'.$value['matricule_diagnostique'].'"></i>
								</div> 
							</td>
						</tr>
					';
				}
				$outputs = array(
					'list_diag' => $output,
					'links' => $this->pagination->create_links()
				);
			}
		}
		echo json_encode($outputs);
	}

	/**les details d'un diagnostique */
	public function single_diagnostique(){
		$output ='';
		$this->logged_in();
		$diagnostique_mat = $this->input->post('diagnostique_code');
		$query = $this->sav->single_diagnostique($diagnostique_mat);
		if (!empty($query)){
			$output .='
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Code
						<b><span>'.$query['matricule_diagnostique'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Equipement de
						<span>'.$query['nom_cli'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Nom Equipement
						<span>'.$query['nom_equip'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Numéro de serie
						<span>'.$query['numero_serie_equip'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Référence
						<span>'.$query['reference_equip'].'</span>
					</li>
					
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Description
						<span class="text-primary">'.$query['diagnostique'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Date d\'enrégistrement
						<span class="text-danger">'.dateformat($query['date_diagnostique']).'</span>
					</li>
					
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Enregistrer par
						<span>'.$query['nom_emp'].'</span>
					</li>

					<li class="list-group-item d-flex justify-content-between align-items-center">
						Prix du service
						<span>'.numberformat($query['prix_diagnostique']).' Xaf</span>
					</li>
				</ul>
			';
		}
		echo json_encode($output);	
	}

	/**affiche les informations d'un diagnostique dans la formulaire a modifier */
	public function edit_diagnostique(){
		$this->logged_in();
		$diagnostique_mat = $this->input->post('diagnostique_code');
		$query = $this->sav->single_diagnostique($diagnostique_mat);
		echo json_encode($query);	
	}

	/**mettre a jour le diagnostique */
	public function updatediagnostique(){
		$this->logged_in();

		$this->form_validation->set_rules('equipement1', 'équipement', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('prix1', 'prix', 'required|regex_match[/^[\d]*[]?[\d]{2}$/]', /* /^[\d]*[.,]?[\d]{2}$/ */
            array(
				'required'      => 'quel est le %s du serice?',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('diagnostique1', 'diagnostique', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\s\- ]+$/]',
            array(
				'required'      => 'quel est le %s?',
                'regex_match'     => 'caractère(s) non autorisé!'
				
            )
        );
		if($this->form_validation->run()){
			$matricule_diagnost = trim($this->input->post('matricule_dias'));
			$prix = trim($this->input->post('prix1'));
			$diagnostique = $this->input->post('diagnostique1');
			$quipement = $this->input->post('equipement1');
			$input = array(
				'diagnostique' => $diagnostique,
				'prix_diagnostique' => $prix,
				'date_modif_diagnostique' => dates(),
				'code_equipement' => $quipement,
			);
			$query = $this->sav->update_diagnostique($input,$matricule_diagnost);
			if($query){
				$array = array(
					'success' => '
						<div class="alert alert-success fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
							<div class="alert-text">diagnostique modifier avec succès</div>
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
							<div class="alert-text">erreur survenu! diagnostique non modifier</div>
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
				'equipement1_error' => form_error('equipement1'),
				'prix1_error' => form_error('prix1'),
				'diagnostique1_error' => form_error('diagnostique1')
			);
		}
		echo json_encode($array);
	}

	/**selectionne tous les code de diagnostique d'une entreprise donné debut */
	public function code_diagnostique(){
		$this->logged_in();
		$output ='';
		$matricule_en = session('users')['matricule'];
		$query = $this->sav->all_diagnostique_maintenance($matricule_en);
		$output .='<option></option>';
		foreach ($query as $key => $value) {
			$output .= '<option value="'.$value['matricule_diagnostique'].'">'.$value['matricule_diagnostique'].' / '.$value['nom_equip'].' / '.$value['nom_cli'].'</option>';
		}
		echo json_encode($output);
	}
	/**selectionne tous les code de diagnostique d'une entreprise donné fin */

	/**gestion des maintenances debut */
	/**nouvel maintenance debut*/
	public function save_maintenance(){
		$this->logged_in();

		$this->form_validation->set_rules('code_diag', 'maintenance', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'      => 'pour quel equipement tu fais la %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('statut', 'statut', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
				'required' => 'choisi le %s',
                'regex_match' => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('prix_maint', 'prix', 'required|regex_match[/^[\d]*[]?[\d]{2}$/]', /* /^[\d]*[.,]?[\d]{2}$/ */
            array(
				'required'      => 'quel est le %s du serice?',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('description_maint', 'desciption', 'required|regex_match[/^[a-zA-Z0-9._éè?!,@$#êÉÈÊàôÀÔïÏ\'\-\n\r ]+$/]',
            array(
				'required'      => 'qu\'avez-vous reparer / qu\'avez-vous a reparer / quel a été la difficulté',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		if($this->form_validation->run()){
			$code_maint = code(10);
			$matricule_en = session('users')['matricule'];
			$matricule_emp = session('users')['matricule_emp'];
			if(!empty($matricule_emp)){
				$input = array(
					'code_reparation' => $code_maint,
					'code_diagnostique' => $this->input->post('code_diag'),
					'code_entreprise' => $matricule_en,
					'code_employe' => $matricule_emp,
					'description_reparation' => $this->input->post('description_maint'),
					'prix_reparation' => $this->input->post('prix_maint'),
					'statut_reparation' => $this->input->post('statut'),
					'date_creer_reparation' => dates()
				);

				$query = $this->sav->new_maintenance($input);
				if($query){
					$array = array(
						'success' => '
							<div class="alert alert-success fade show" role="alert">
								<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
								<div class="alert-text">maintenance créer avec succès</div>
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
								<div class="alert-text">erreur survenu! maintenance non crée</div>
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
				'code_diag_error' => form_error('code_diag'),
				'statut_error' => form_error('statut'),
				'prix_maint_error' => form_error('prix_maint'),
				'description_maint_error' => form_error('description_maint')
			);
		}

		echo json_encode($array);
	}
	/**nouvel maintenance fin*/

	/**liste des maintenances debut */
	public function all_maintenance($num_page = NULL){
		$this->logged_in();
		$output ='';
		$matricule_en = session('users')['matricule'];

		
		$config = array();
		$config["base_url"] = "#";
		$config["total_rows"] = $this->sav->count_all_maintenance($matricule_en);
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

		//$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

		$page = (($this->uri->segment(2) - 1) * $config["per_page"]);


		$recherche = trim($this->input->post('val'));
		if(!empty($recherche)){
			$query = $this->sav->recherche_maintenance($matricule_en,$recherche); 
			if(!empty($query)){
				foreach ($query as $key => $value){
					$output .= '
						<tr>
							<th scope="row">'.$value['code_reparation'].'</th>
							<td>'.$value['nom_equip'].'</td>
							<td>'.$value['nom_cli'].'</td>
							<td>'.numberformat($value['prix_reparation']).'</td>';
							if($value['statut_reparation'] == 'recu'){
								$output .= '<td class="badge badge-pill badge-primary">'.$value['statut_reparation'].'</td>';
							}else if($value['statut_reparation'] == 'encours'){
								$output .= '<td class="badge badge-pill badge-warning">'.$value['statut_reparation'].'</td>';
							}else if($value['statut_reparation'] == 'resolu'){
								$output .= '<td class="badge badge-pill badge-success">'.$value['statut_reparation'].'</td>';
							}else if($value['statut_reparation'] == 'non_resolu'){
								$output .= '<td class="badge badge-pill badge-danger">non resolu</td>';
							}
					$output .= '<td>'.$value['nom_emp'].'</td>
							<td>
								<div class="kt-widget__toolbar">
									<i class="fa fa-eye view_maint" id="'.$value['code_reparation'].'"></i>
									<i class="fa fa-pen edit_maint" id="'.$value['code_reparation'].'"></i>
								</div> 
							</td>
						</tr>
					';
				}
				$outputs = array(
					'list_maintenance' => $output
				);
			}else{
				$outputs = array(
					'list_maintenance' => '<tr><td><b class="text-danger">aucune maintenance ne correspond à ce client</b></td></tr>'
				);
			}
		}else if(empty($query)){
			$query = $this->sav->all_maintenance($matricule_en,$config["per_page"], $page);
			if(!empty($query)){
				foreach ($query as $key => $value) {
					$output .= '
						<tr>
							<th scope="row">'.$value['code_reparation'].'</th>
							<td>'.$value['nom_equip'].'</td>
							<td>'.$value['nom_cli'].'</td>
							<td>'.numberformat($value['prix_reparation']).'</td>';
							if($value['statut_reparation'] == 'recu'){
								$output .= '<td class="badge badge-pill badge-primary">'.$value['statut_reparation'].'</td>';
							}else if($value['statut_reparation'] == 'encours'){
								$output .= '<td class="badge badge-pill badge-warning">'.$value['statut_reparation'].'</td>';
							}else if($value['statut_reparation'] == 'resolu'){
								$output .= '<td class="badge badge-pill badge-success">'.$value['statut_reparation'].'</td>';
							}else if($value['statut_reparation'] == 'non_resolu'){
								$output .= '<td class="badge badge-pill badge-danger">non resolu</td>';
							}
					$output .= '<td>'.$value['nom_emp'].'</td>
							<td>
								<div class="kt-widget__toolbar">
									<i class="fa fa-eye view_maint" id="'.$value['code_reparation'].'"></i>
									<i class="fa fa-pen edit_maint" id="'.$value['code_reparation'].'"></i>
								</div> 
							</td>
						</tr>
					';
				}

				$outputs = array(
					'list_maintenance' => $output,
					'links' => $this->pagination->create_links()
				);
			}
		}
		
		echo json_encode($outputs);
	}
	/**liste des maintenances fin */

	/**afficher les details sur une maintenance donné debut*/
	public function single_maintenance(){
		$output ='';
		$this->logged_in();
		$maint_mat = $this->input->post('maint_code');
		$query = $this->sav->singlemaintenance($maint_mat);
		if (!empty($query)){
			$output .='
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Code
						<b><span>'.$query['code_reparation'].'</span></b>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Nom
						<span>'.$query['nom_equip'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Numéro de serie
						<span>'.$query['numero_serie_equip'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Référence
						<span>'.$query['reference_equip'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Status
						<span>'.$query['statut_reparation'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Diagnostique
						<span class="text-primary">'.$query['diagnostique'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Prix du Diagnostique
						<span class="text-primary">'.numberformat($query['prix_diagnostique']).' Xaf</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Date du Diagnostique
						<span class="text-primary">'.dateformat($query['date_diagnostique']).'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Problème à résoudre
						<span class="text-primary">'.$query['description_reparation'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Prix de maintenance
						<span class="text-primary">'.numberformat($query['prix_reparation']).' Xaf</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Date d\'enrégistrement
						<span class="text-danger">'.dateformat($query['date_creer_reparation']).'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Enregistrer par
						<span>'.$query['nom_emp'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Equipement de
						<span>'.$query['nom_cli'].'</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						<h4>Prix Total du diagnostique et de la maintenance</h4>
						<h4><span>'.numberformat(($query['prix_reparation']+$query['prix_diagnostique'])).' Xaf</span></h4>
					</li>
				</ul>
			';
		}
		echo json_encode($output);
	}
	/**afficher les details sur une maintenance donné fin*/


	/**afficher les details d'une maintenance dans un formulaire debut*/
	public function edit_maintenance(){
		$output ='';
		$this->logged_in();
		$maint_mat = $this->input->post('maint_code');
		$query = $this->sav->singlemaintenance($maint_mat);
		echo json_encode($query);
	}
	/**afficher les details d'une maintenance dans un formulaire fin*/
	
	/**modifier une maintenance debut */
	public function update_maintenance(){
		$this->logged_in();

		$this->form_validation->set_rules('code_diag1', 'maintenance', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'      => 'pour quel equipement tu fais la %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('statut1', 'statut', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
				'required' => 'choisi le %s',
                'regex_match' => 'caractère(s) non autorisé!'
            )
        );

		$this->form_validation->set_rules('prix_maint1', 'prix', 'required|regex_match[/^[\d]*[]?[\d]{2}$/]', /* /^[\d]*[.,]?[\d]{2}$/ */
            array(
				'required'      => 'quel est le %s du serice?',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('description_maint1', 'desciption', 'required|regex_match[/^[a-zA-Z0-9._éè?!,@$#êÉÈÊàôÀÔïÏ\'\-\n\r ]+$/]',
            array(
				'required'      => 'qu\'avez-vous reparer / qu\'avez-vous a reparer / quel a été la difficulté',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

		if($this->form_validation->run()){
			$code_maint = $this->input->post('matricule_maint');
			$input = array(
				'code_diagnostique' => $this->input->post('code_diag1'),
				'description_reparation' => $this->input->post('description_maint1'),
				'prix_reparation' => $this->input->post('prix_maint1'),
				'statut_reparation' => $this->input->post('statut1'),
				'date_modif_reparation' => dates()
			);

			$query = $this->sav->update_maintenance($input,$code_maint);
			
			if($query){
				$array = array(
					'success' => '
						<div class="alert alert-success fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
							<div class="alert-text">maintenance modifier avec succès</div>
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
							<div class="alert-text">erreur survenu! maintenance non modifier</div>
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
				'code_diag1_error' => form_error('code_diag1'),
				'statut1_error' => form_error('statut1'),
				'prix_maint1_error' => form_error('prix_maint1'),
				'description_maint1_error' => form_error('description_maint1')
			);
		}

		echo json_encode($array);
	}
	/**modifier une maintenance fin */


	/**gestion des maintenances fin */


}