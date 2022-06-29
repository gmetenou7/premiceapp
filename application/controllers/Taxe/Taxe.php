<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Taxe extends CI_Controller {

    /**constructeur*/
	public function __construct(){
        parent::__construct();
        $this->load->model('Taxe/Taxe_modal','taxe');
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

		$this->load->view('taxe/taxe');

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }

    /**enregistrer une taxe dans la base des données */
    public function newtaxe(){
        $this->logged_in();

        $this->form_validation->set_rules(
            'pourcentage', 'pourcentage de taxe',
            'required|numeric',
            array(
                'required'  => 'Champ obligatoire pour la décimal, utilise le "." au lieu de ","',
                'numeric' => 'Caractère non autorisé.'
            )
        );

        $this->form_validation->set_rules(
            'libele', 'nom de la taxe',
            'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'  => 'Champ obligatoire.',
                'regex_match' => 'Caractère non autorisé.'
            )
        );

        if($this->form_validation->run()){

            $pourcentage = trim($this->input->post('pourcentage'));
            $libele = trim($this->input->post('libele'));
            $matricule_en = session('users')['matricule'];
            $matricule_emp = session('users')['matricule_emp'];

            if(!empty($matricule_emp)){
                $input_data = array(
                    'codetaxe' => code(10),
                    'nomtaxe' => $libele,
                    'pourcentage' => $pourcentage,
                    'taxe_mat_en' => $matricule_en,
                    'taxe_mat_emp' => $matricule_emp,
                    'taxe_creer_le' => dates()
                );

                $ifisok = $this->taxe->new_db_taxe($input_data);
                if($ifisok){
                    $array = array(
                        'success'   => '
                            <div class="alert alert-success fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                <div class="alert-text">Nouvelle taxe créer avec succès</div>
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
                                <div class="alert-text">Erreur survenu! nouvelle taxe non créer</div>
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
				'pourcentage_error' => form_error('pourcentage'),
				'libele_error' => form_error('libele')
			);
        }

        echo json_encode($array);
    }

    /**liste des taxes debut */
    public function alltaxe(){
        $this->logged_in();
        $output="";

        $matricule_en = session('users')['matricule'];
        $matricule_emp = session('users')['matricule_emp'];

        $ifisok = $this->taxe->all_db_taxe($matricule_en);
        if(!empty($ifisok)){
            foreach ($ifisok as $key => $value) {
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
                                                    '.$value['nomtaxe'].' ('.$value['pourcentage'].'%)
                                                </a>
                                                <span class="kt-widget__desc">
                                                    '.$value['codetaxe'].'
                                                </span>
                                            </div>     
                                            <div class="kt-widget__toolbar">
                                                <button id="'.$value['codetaxe'].'" class="btn btn-icon btn-circle btn-label-facebook viewtaxe">
                                                    <i class="fa fa-eye"></i>
                                                </button>

                                                <!--<button id="'.$value['codetaxe'].'" class="btn btn-icon btn-circle btn-label-linkedin deletetaxe">
                                                    <i class="fa fa-times"></i>
                                                </button> -->

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
    /**liste des taxes fin */

    /**liste des taxes debut */
    public function getsingletaxe(){
        $this->logged_in();

        $matricule_en = session('users')['matricule'];
        $mat_taxe = $this->input->post('mat_taxe'); 
        $ifisok = $this->taxe->single_db_taxe($matricule_en,$mat_taxe);
        $array = array(
            'code' => $ifisok['codetaxe'],
            'pourcentage' => $ifisok['pourcentage'],
            'libelle' => $ifisok['nomtaxe']
        );
        echo json_encode($array);

    }
    /**liste des taxes fin */

    /**update une taxe dans la base des données debut*/
    public function updatetaxe(){
        $this->logged_in();

        $this->form_validation->set_rules(
            'pourcentage1', 'pourcentage de taxe',
            'required|numeric',
            array(
                'required'  => 'Champ obligatoire pour la décimal, utilise le "." au lieu de ","',
                'numeric' => 'Caractère non autorisé.'
            )
        );

        $this->form_validation->set_rules(
            'libele1', 'nom de la taxe',
            'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'  => 'Champ obligatoire.',
                'regex_match' => 'Caractère non autorisé.'
            )
        );

        if($this->form_validation->run()){

            
            $mattaxe = trim($this->input->post('matriculetaxe'));

            $pourcentage = trim($this->input->post('pourcentage1'));
            $libele = trim($this->input->post('libele1'));

            $matricule_en = session('users')['matricule'];
            $matricule_emp = session('users')['matricule_emp'];

            if(!empty($matricule_emp)){
                $input_data = array(
                    'nomtaxe' => $libele,
                    'pourcentage' => $pourcentage,
                    'taxe_mat_en' => $matricule_en,
                    'taxe_mat_emp' => $matricule_emp,
                    'taxe_modifier_le' => dates()
                );

                $ifisok = $this->taxe->update_db_taxe($input_data,$mattaxe);
                if($ifisok){
                    $array = array(
                        'success'   => '
                            <div class="alert alert-success fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                <div class="alert-text">Nouvelle taxe modifier avec succès</div>
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
                                <div class="alert-text">Erreur survenu! nouvelle taxe non modifier</div>
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
				'pourcentage1_error' => form_error('pourcentage1'),
				'libele1_error' => form_error('libele1')
			);
        }

        echo json_encode($array);
    }
    /**update une taxe dans la base des données fin*/


    /**ajoute l'heur de cloture d'une facture debut*/
    public function heur_clo_fact(){
        $this->logged_in();

        $timeval = $this->input->post('valtime');
        $date = dates();
        $matricule_en = session('users')['matricule'];

        
        /**on insert la date dans la base des données */
        $Isok = $this->taxe->insert_time_clo_fact($timeval,$date,$matricule_en);
        if($Isok){
            $array = array(
                'success'   => 'enregistré'
            );
        }else{
            $array = array(
                'success'   => 'erreur survenu non enregistré'
            );
        }
        
        echo json_encode($array);
    }
    /**ajoute l'heur de cloture d'une facture fin*/

    /**affiche la l'heur de cloture de la facture selectionner à la vue debut*/
    public function show_clo_fact(){
        $this->logged_in();
        $matricule_en = session('users')['matricule'];
        /**on insert la date dans la base des données */
        $Isok = $this->taxe->show_time_clo_fact($matricule_en);
        if(!empty($Isok)){
            $array = array(
                'success' => $Isok['heure_clo']
            );
        }else{
            $array = array(
                'success'   => 'aucune config trouvé'
            );
        }
        
        echo json_encode($array);
    }
    /**affiche la l'heur de cloture de la facture selectionner à la vue fin*/


    /**on cloture donc toutes les factures direct ouverte à l'heur défini*/
    public function clo_fact_in_time(){
        $this->logged_in();
        $matricule_en = session('users')['matricule'];
        $abrev = "RT";
        $date = dates();

        /**requette de cloture
         * on cloture toutes les factures en RT pour une entreprise donné, à une heur donné
         * dont la colone clo !=1
         */
        $output = "";
        $IsDone = $this->taxe->cloturer_rt_fact_open($matricule_en,$abrev,$date);
        if($IsDone){
            $output .='
            <div class="alert alert-light alert-elevate" role="alert">
                <div class="alert-text">'.$IsDone.'</div>
            </div>';
        }else{
            $output .=$IsDone;
        }
        echo json_encode($output);
    }

}