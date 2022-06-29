<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caisse extends CI_Controller {

    /**constructeur*/
	public function __construct(){
        parent::__construct();
        $this->load->model('Caisse/Caisse_model','caisse');

    }

    /**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}

    /**page de gestion des caisse */
    public function index(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

        $matricule_en = session('users')['matricule'];
        //$matricule_emp = session('users')['matricule_emp'];

        /**liste des agences d'une entreprise*/
        $data['agences'] = $this->caisse->all_agence($matricule_en);

        /**liste des employées d'une entreprise */
        $data['employes'] = $this->caisse->all_employe($matricule_en);

		$this->load->view('caisse/caisse',$data);

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }

    /**nouvelle caisse */
    public function newcaisse(){
        $this->logged_in();

        $this->form_validation->set_rules(
            'libele', 'nom de la caisse',
            'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'  => 'Champ obligatoire.',
                'regex_match' => 'Caractère non autorisé.'
            )
        );

        $this->form_validation->set_rules(
            'employe', 'choisi l\'employé',
            'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',
            array(
                'required'  => 'Champ obligatoire.',
                'regex_match' => 'Caractère non autorisé.'
            )
        );

        $this->form_validation->set_rules(
            'agence', 'choisi l\'agence',
            'regex_match[/^[a-zA-Z0-9\ ]+$/]',
            array(
                //'required'  => 'Champ obligatoire.',
                'regex_match' => 'Caractère non autorisé.'
            )
        );

        $this->form_validation->set_rules(
            'entreprise', 'nom de l\'entreprise',
            'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',
            array(
                'required'  => 'Champ obligatoire.',
                'regex_match' => 'Caractère non autorisé.'
            )
        );
        
        $this->form_validation->set_rules(
            'numcompte', 'numero de compte',
            'regex_match[/^[0-9]+$/]',
            array(
                'regex_match' => 'Caractère non autorisé.'
            )
        );
        $this->form_validation->set_rules(
            'crib', 'cle rib',
            'regex_match[/^[0-9]+$/]',
            array(
                'regex_match' => 'Caractère non autorisé.'
            )
        );
        $this->form_validation->set_rules(
            'codeb', 'code banque',
            'regex_match[/^[0-9]+$/]',
            array(
                'regex_match' => 'Caractère non autorisé.'
            )
        );

        if($this->form_validation->run()){

            $libelle = trim($this->input->post('libele'));
            $employe = trim($this->input->post('employe'));
            $agence = trim($this->input->post('agence'));
            $entreprise = trim($this->input->post('entreprise'));
            
            $numcompte = trim($this->input->post('numcompte'));
            $crib = trim($this->input->post('crib'));
            $codeb = trim($this->input->post('codeb'));

            $matricule_en = session('users')['matricule'];
            $matricule_emp = session('users')['matricule_emp'];
            if(!empty($matricule_emp)){
                if(!empty($agence)){
                
                    $input_data = array(
                        'code_caisse' => code(10),
                        'libelle_caisse' => $libelle,
                        'enregistrer_par' => $matricule_emp,
                        'gerant_emp' => $employe,
                        'code_entreprise' => $entreprise,
                        'code_agence' => $agence,
                        'num_compte' => $numcompte,
                        'cle_rib' => $crib,
                        'code_banque' => $codeb,
                        'date_creer_caisse' => dates()
                    );
                    $IsOk = $this->caisse->new_caisse($input_data);
                    if($IsOk){
                        $array = array(
                            'success'   => '
                                <div class="alert alert-success fade show" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text">Nouvelle caisse créer avec succès</div>
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
                            'success'   => '
                                <div class="alert alert-danger fade show" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text">Erreur survenu! Nouvelle caisse non créer</div>
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
                    $input_data = array(
                        'code_caisse' => code(10),
                        'libelle_caisse' => $libelle,
                        'enregistrer_par' => $matricule_emp,
                        'gerant_emp' => $employe,
                        'code_entreprise' => $entreprise,
                        'code_agence' => NULL,
                        'num_compte' => $numcompte,
                        'cle_rib' => $crib,
                        'code_banque' => $codeb,
                        'date_creer_caisse' => dates()
                    );
        
                    $IsOk = $this->caisse->new_caisse($input_data);
                    if($IsOk){
                        $array = array(
                            'success'   => '
                                <div class="alert alert-success fade show" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text">Nouvelle caisse créer avec succès</div>
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
                            'success'   => '
                                <div class="alert alert-danger fade show" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text">Erreur survenu! Nouvelle caisse non créer</div>
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
                    'success'   => '
                        <script>
                            swal.fire("OUPS!","Connecte toi en tant que utilisateur pour faire cette opération","info")
                        </script>
                    '
                );
            }

        }else{
            $array = array(
				'error'   => true,
				'entreprise_error' => form_error('entreprise'),
				'agence_error' => form_error('agence'),
                'employe_error' => form_error('employe'),
				'libele_error' => form_error('libele'),
				'numcompte_error' => form_error('numcompte'),
				'crib_error' => form_error('crib'),
				'codeb_error' => form_error('codeb')
			);
        }

        echo json_encode($array);

    }

    /**liste des caisses */
    public function allcaisse(){
        $this->logged_in();

        $output="";

        $matricule_en = session('users')['matricule'];
        $matricule_emp = session('users')['matricule_emp'];

        $ifisok = $this->caisse->all_db_caisse($matricule_en);
        
        if(!empty($ifisok)){
            
            foreach ($ifisok as $key => $value){
                $output .= '
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
                                                    '.$value['libelle_caisse'].'
                                                </a>
                                                <span class="kt-widget__desc">
                                                    '.$value['code_caisse'].'
                                                </span>
                                            </div>     
                                            <div class="kt-widget__toolbar">
                                                <button id="'.$value['code_caisse'].'" class="btn btn-icon btn-circle btn-label-facebook viewcaisse">
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


    /**afficher les informations d'une caisse dans le formulaire */
    public function singlecaisse(){
        $this->logged_in();

        $matricule_en = session('users')['matricule'];
        $mat_caisse = $this->input->post('mat_caisse');
        $ifisok = $this->caisse->single_db_caisse($matricule_en,$mat_caisse);
        if(!empty($ifisok)){
            $array = array(
                'agence' =>  $ifisok['code_agence'],
                'employe' => $ifisok['gerant_emp'],
                'libelle' => $ifisok['libelle_caisse'],
                'code' => $ifisok['code_caisse'],
                'compte' => $ifisok['num_compte'],
                'rib' => $ifisok['cle_rib'],
                'banque' => $ifisok['code_banque']
            );
        }
        
        echo json_encode($array);

    }

    /**modifier une caisse  */
    public function updatecaisse(){
        $this->logged_in();

        $this->form_validation->set_rules(
            'libele1', 'nom de la caisse',
            'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'  => 'Champ obligatoire.',
                'regex_match' => 'Caractère non autorisé.'
            )
        );

        $this->form_validation->set_rules(
            'employe1', 'choisi l\'employé',
            'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',
            array(
                'required'  => 'Champ obligatoire.',
                'regex_match' => 'Caractère non autorisé.'
            )
        );

        $this->form_validation->set_rules(
            'agence1', 'choisi l\'agence',
            'regex_match[/^[a-zA-Z0-9\ ]+$/]',
            array(
                //'required'  => 'Champ obligatoire.',
                'regex_match' => 'Caractère non autorisé.'
            )
        );

        $this->form_validation->set_rules(
            'entreprise1', 'nom de l\'entreprise',
            'required|regex_match[/^[a-zA-Z0-9\ ]+$/]',
            array(
                'required'  => 'Champ obligatoire.',
                'regex_match' => 'Caractère non autorisé.'
            )
        );
        
         $this->form_validation->set_rules(
            'numcompte1', 'numero de compte',
            'regex_match[/^[0-9]+$/]',
            array(
                'regex_match' => 'Caractère non autorisé.'
            )
        );
        $this->form_validation->set_rules(
            'crib1', 'cle rib',
            'regex_match[/^[0-9]+$/]',
            array(
                'regex_match' => 'Caractère non autorisé.'
            )
        );
        $this->form_validation->set_rules(
            'codeb1', 'code banque',
            'regex_match[/^[0-9]+$/]',
            array(
                'regex_match' => 'Caractère non autorisé.'
            )
        );

        if($this->form_validation->run()){
            
            $code = trim($this->input->post('code'));

            $agence = trim($this->input->post('agence1'));
            $employe = trim($this->input->post('employe1'));
            $libelle = trim($this->input->post('libele1'));
            
            $numcompte = trim($this->input->post('numcompte1'));
            $crib = trim($this->input->post('crib1'));
            $codeb = trim($this->input->post('codeb1'));
            
            
            //$entreprise = trim($this->input->post('entreprise'));

            $matricule_en = session('users')['matricule'];
            //$matricule_emp = session('users')['matricule_emp'];

            if(!empty($employe)){
                if(!empty($agence)){
                    $input_data = array(
                        'libelle_caisse' => $libelle,
                        'gerant_emp' => $employe,
                        'code_agence' => $agence,
                        'num_compte' => $numcompte,
                        'cle_rib' => $crib,
                        'code_banque' => $codeb,
                        'date_modifier_caisse' => dates()
                    );
        
                    $IsOk = $this->caisse->update_caisse($input_data,$code);
                    if($IsOk){
                        $array = array(
                            'success'   => '
                                <div class="alert alert-success fade show" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text">Caisse Modifier avec succès</div>
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
                            'success'   => '
                                <div class="alert alert-danger fade show" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text">Erreur survenu! Caisse non modifier</div>
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
                    $input_data = array(
                        'libelle_caisse' => $libelle,
                        'gerant_emp' => $employe,
                        'code_agence' => NULL,
                        'num_compte' => $numcompte,
                        'cle_rib' => $crib,
                        'code_banque' => $codeb,
                        'date_modifier_caisse' => dates()
                    );
        
                    $IsOk = $this->caisse->update_caisse($input_data,$code);
                    if($IsOk){
                        $array = array(
                            'success'   => '
                                <div class="alert alert-success fade show" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text">Caisse Modifier avec succès</div>
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
                            'success'   => '
                                <div class="alert alert-danger fade show" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text">Erreur survenu! Caisse non modifier</div>
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
                    'success'   => '
                        <script>
                            swal.fire("OUPS!","Connecte toi en tant que utilisateur pour faire cette opération","info")
                        </script>
                    '
                );
            }

            

        }else{
            $array = array(
				'error'   => true,
				'entreprise1_error' => form_error('entreprise1'),
				'agence1_error' => form_error('agence1'),
                'employe1_error' => form_error('employe1'),
				'libele1_error' => form_error('libele1'),
				'numcompte1_error' => form_error('numcompte1'),
				'crib1_error' => form_error('crib1'),
				'codeb1_error' => form_error('codeb1')
			);
        }

        echo json_encode($array);
    }



}