<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Points extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('Point/Point_model','point');
    }


	/**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}


    /**Home */
	public function index(){
		$this->logged_in();
		
		$this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');
		$mat_en = session('users')['matricule'];
		
		$this->load->view('point/point');
		
		$this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}
	
	/**enregistrer un nouvelle configuration des point pour un prix*/
    public function newpoint(){
        $this->logged_in();
        
        /**validation du formulaire */
		$this->form_validation->set_rules('prixpoint', 'prix de(s) point', 'required|regex_match[/^[0-9]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé! assure toi aussi de ne pas avoir mis des vide',
            )
        );

		$this->form_validation->set_rules('nbrpoint', 'nombre de point', 'required|regex_match[/^[0-9]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s pour ce prix',
                'regex_match'     => 'caractère(s) non autorisé! assure toi aussi de ne pas avoir mis des vide'
            )
        );
        
        if($this->form_validation->run()){
        
            $prixpoint = trim($this->input->post('prixpoint'));
            $nbrpoint = trim($this->input->post('nbrpoint'));
            $mat_en = session('users')['matricule'];
            $mat_emp = session('users')['matricule_emp'];
            
            $input = array(
				'code_point' =>code(5),
				'prix_point' => $prixpoint,
				'nbr_point' => $nbrpoint,
				'point_en' => $mat_en,
				'point_emp' => $mat_emp,
				'datecreationpoint' => dates()
			);
			/**inserer dans la base des données*/
			$query = $this->point->new_point($input);
			if($query){
				$array = array(
					'success' => '
						<div class="alert alert-success fade show" role="alert">
							<div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
							<div class="alert-text">config enregistrer avec succès</div>
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
							<div class="alert-text">erreur survenu! config non enregistrer</div>
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
				'prixpoint_error' => form_error('prixpoint'),
				'nbrpoint_error' => form_error('nbrpoint')
			);
		}
        
        echo json_encode($array);
    }

    /***affiche toute la configuration des points pour un prix donner*/
    public function getconfig_pointprix(){
       $this->logged_in(); 
       $output = "";
       
       /**information utile*/
       $mat_en = session('users')['matricule'];
       
       /*on selectionne la liste des config point prix en fonction de l'entreprise et pour l'enregistreur**/
       $infos = $this->point->allconfigpoint($mat_en);
       if(!empty($infos)){
            foreach($infos as $value){
                $output .= '
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        '.$value['nbr_point'].' point(s) Pour => '.$value['prix_point'].' Frs; Enregistrer par: '.$value['nom_emp'].' Le: '.$value['datecreationpoint'].'
                        <span class="badge badge-primary badge-pill deletepoint" id="'.$value['code_point'].'">X</span>
                    </li>
               ';
            }
       }else{
           $output .= '
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Aucune config effectué jusqu\'a présent
                </li>
           '; 
       }
       echo json_encode($output);
    }
    
    public function delete_pointprix(){
        $this->logged_in(); 
               
        /**information utile*/
       $mat_en = session('users')['matricule'];
       
       $codepoint = trim($this->input->post('mat_point'));
       
        
        if(!empty($codepoint)){
           
           /**supprimer un point configurer*/
           $delete = $this->point->deleteconfigpoint($mat_en,$codepoint);
           if($delete){
                $array = array(
                    'success'   => '
                        <script>
                            swal.fire("SUPPER!","suppression effectuer avec succès","success")
                        </script>
                    '
                );
            }else{
                $array = array(
                    'success'   => '
                        <script>
                            swal.fire("OUPS!","erreur survenu, le systeme n\'arrive pas à supprimer cette config","info")
                        </script>
                    '
                );
            }
        }else{
            $array = array(
                'success'   => '
                    <script>
                        swal.fire("OUPS!","erreur survenu, le systeme ne retrouve pas cette config","error")
                    </script>
                '
            );
        }
       
       echo json_encode($array);
    }
	



}