<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Users/Users_model','users');
        $this->load->model('Agence/Agence_model','agence');
        $this->load->model('Service/Service_model','service');
    }

/******************************************************************************** */
      /**dit a l'utilisateur qu'il est déja connecter */
	private function logged_out(){
		if(session('users') && $this->router->{'class'} !== 'index' && $this->router->{'method'} !== 'logout'){
			flash("warning","tu es connecté actuelement");
			redirect('home');
		}
    }
	/**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}
/********************************************************************************************* */

    /**page de connexion */
	public function index(){
        $this->logged_out();
        $this->form_validation->set_rules('username', 'login', 'required|regex_match[/^[a-zA-Z0-9._éèê@ÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required' => 'Tu n\'as pas fourni le %s.',
                'regex_match' => 'caractère(s) non autorisé!',
            )
        );

        $this->form_validation->set_rules('password', 'mot de passe', 'required|regex_match[/^[a-zA-Z0-9._éèêÉ&ÈÊ@àôÀÔïÏ*\'\- ]+$/]',
            array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!',
            )
        );

        if($this->form_validation->run()){
            $first_input = $this->input->post('username');
            /**login entreprise */
            $login_en = $this->users->login_en($first_input);

            /**login employé */
            $login_emp1 = $this->users->login_emp1($first_input);

            if($login_en){
                $pass = $this->input->post('password');
                if(password_verify($pass, $login_en['password_en'])){
                    if($login_en['etat_en'] == "on"){

                        /**enregistre l'historique de connexion */
                        $datas = array(
                            'mat_en' => $login_en['matricule_en'],
                            'date_connexion_en' =>dates()
                        );

                        $save_story = $this->users->story_login1($datas);
                        if($save_story){

                            /**les informations dans la session */
                            $session_data = array(
                                'status' => '',
                                'matricule' => $login_en['matricule_en'],
                                'matricule_ag' => '',
                                'matricule_serv' => '',
                                'matricule_emp' => '',
                                'nom_emp' => '',
                                'adresse_emp' => '',
                                'email_emp' => '',
                                'date_naiss_emp' => '',
                                'fonction_emp' => '',
                                'etat_emp' => '',
                                'creer_le_emp' => '',
                                'modifier_le_emp' => '',
                                'nom_ag' => '',
                                'nom_serv' => '',
                                'nom' => $login_en['nom_en'],
                                'date_creation_emp' => '',
                                'date_modification_emp' => '',
                                'date_creation_en' => $login_en['creer_le_en'],
                                'date_modification_en' => $login_en['modifier_le_en'],
                                'adresse_en' => $login_en['adresse_en'],
                                'activite_en' => $login_en['activite_en'],
                                'form_juridique_en' => $login_en['form_juridique_en'],
                                'pays_en' => $login_en['pays_en'],
                                'telephone_en' => $login_en['telephone_en'],
                                'email_en' => $login_en['email_en'],
                                'site_internet_en' => $login_en['site_internet_en']
                            );

                            session('users', $session_data);
                            flash('success','bienvenu '.session('users')['nom']);
                            redirect('home');
                        }else{
                            flash('error',"erreur survenu"); 
                            redirect('login');
                        }
                        
                    }else{
                        flash('error',"entreprise désactivé, contactez l'administrateur"); 
                        redirect('login');
                    }
                }else{
                    flash('error',"mot de passe incorrect"); 
                }
            }else if($login_emp1){

                $pass = $this->input->post('password');
                if(password_verify($pass, $login_emp1['password_emp'])){
                    if($login_emp1['etat_en'] == "on"){
                        if($login_emp1['etat_emp'] == "on"){

                            /**un selectionne le service et ou l'agence de l'employé qui se conncete*/
                            $login_emp2 = $this->users->login_emp2($first_input);

                            if($login_emp2){

                                /**enregistre l'historique de connexion */
                                $datas = array(
                                    'mat_emp' => $login_emp1['matricule_emp'],
                                    'date_connexion_emp' =>dates()
                                );

                                $save_story = $this->users->story_login2($datas);
                                if($save_story){
                                    /**les informations dans la session */
                                    $session_data = array(
                                        'status' => 'employe',  
                                        'matricule' => $login_emp1['matricule_en'],
                                        'matricule_ag' => $login_emp2['matricule_ag'],
                                        'matricule_serv' => $login_emp2['matricule_serv'],
                                        'matricule_emp' => $login_emp1['matricule_emp'],
                                        'nom_emp' => $login_emp1['nom_emp'],
                                        'adresse_emp' => $login_emp1['adresse_emp'],
                                        'email_emp' => $login_emp1['email_emp'],
                                        'telephone_emp' => $login_emp1['telephone_emp'],
                                        'date_naiss_emp' => $login_emp1['date_naiss_emp'],
                                        'fonction_emp' => $login_emp1['fonction_emp'],
                                        'etat_emp' => $login_emp1['etat_emp'],
                                        'creer_le_emp' => $login_emp1['creer_le_emp'],
                                        'modifier_le_emp' => $login_emp1['modifier_le_emp'],
                                        'nom_ag' => $login_emp2['nom_ag'],
                                        'nom_serv' => $login_emp2['nom_serv'],
                                        'nom' => $login_emp1['nom_en'],
                                        'date_creation_emp' => $login_emp1['creer_le_emp'],
                                        'date_modification_emp' => $login_emp1['modifier_le_emp'],
                                        'date_creation_en' => '',
                                        'date_modification_en' => '',
                                        'adresse_en' => '',
                                        'activite_en' => '',
                                        'form_juridique_en' => '',
                                        'pays_en' => '',
                                        'telephone_en' => '',
                                        'email_en' => '',
                                        'site_internet_en' => ''
                                    );

                                    session('users', $session_data);    
                                    flash('success','bienvenu '.session('users')['nom_emp']);
                                    redirect('home');
                                }else{
                                    flash('error',"erreur survenu"); 
                                    redirect('login');
                                }
                            }else{
                                flash('error',"service et ou agence non trouvé"); 
                                redirect('login');
                            }
                        }else{
                            flash('error',"votre compte est désactivé, contactez l'administrateur"); 
                            redirect('login');
                        }
                    }else{
                        flash('error',"entreprise désactivé, contactez l'administrateur"); 
                        redirect('login');
                    }
                }else{
                    flash('error',"mot de passe incorrect"); 
                }
            }else{
                flash('error',"ce compte n'existe pas");
            }
        }

		$this->load->view('users/users_ex/login');
	}

   

    /**REGISTER */
    public function register_user(){
        /**selectionne la liste des pays dans lla base des données */
        $data['pays']= $this->users->get_pays();

        

        /**selection de la forme juridique de l'entreprise */
        $data['juridique']= $this->users->get_form_juridique();

        $this->form_validation->set_rules('nom', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]|is_unique[entreprise.nom_en]',
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
        $this->form_validation->set_rules('juridique', 'forme juridique', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'regex_match' => 'caractère(s) non autorisé!',
                    'required' => 'Tu n\'as pas fourni la %s.'
            )
        );

        $this->form_validation->set_rules('pays', 'pays', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÎÈÊàôÀÔïÏî\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni le %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );


        $this->form_validation->set_rules('telephone1', 'telephone1', 'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni le %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('telephone2', 'telephone2', 'regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
            array(
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('email', 'email', 'valid_email|is_unique[entreprise.email_en]',
            array(
                    'valid_email'     => 'email pas correct!',
                    'is_unique' => 'cet email est déja utilisé, choisi un autre'
                    
            )
        );

          /*'regex_match' =>'caractère non autorisé' //|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]*/
        $this->form_validation->set_rules('site', 'site', 'valid_url',   
            array(
                'valid_url'     => 'url incorrect!'
            )
        );

        $this->form_validation->set_rules('password', 'mot de passe', 'required',
            array(
                    'required' => 'Tu n\'as pas fourni le %s.',
            )
        );

        $this->form_validation->set_rules('cpassword', 'confirmation mot de passe', 'required|matches[password]',
            array(
                    'required'      => 'Tu n\'as pas fourni le %s.',
                    'matches'     => 'la confirmation de mot de passe doit être identique au mot de passe'
            )
        );
            if($this->form_validation->run()){
               $password = $this->input->post('password');
               $input = array(
                   'matricule_en'=> code(10),
                   'nom_en'=> $this->input->post('nom'),
                   'adresse_en'=> $this->input->post('adresse'),
                   'activite_en'=> $this->input->post('activite'),
                   'form_juridique_en'=> $this->input->post('juridique'),
                   'pays_en'=> $this->input->post('pays'),
                   'telephone_en'=> $this->input->post('telephone1').','.$this->input->post('telephone2'),
                   'email_en'=> $this->input->post('email'),
                   'site_internet_en'=> $this->input->post('site'),
                   'password_en'=> password_hash($this->input->post('password'),PASSWORD_BCRYPT),
                   'creer_le_en'=> dates()
               );

               $requestOk = $this->users->insert_entreprise($input);
               if($requestOk){
                    flash('success','bravo! votre entreprise est crée, connectez-vous à présent. votre matricule est: '.$input['matricule_en']);
                    redirect('login');
               }else{
                    flash('error',"erreur survenu, verifiez votre connexion internet");
                    redirect('register');
               }

            }

        $this->load->view('users/users_ex/register',$data);
    }


    /**affiche la page d'envoi d'email permettant de modifier le mot de passe d'un utilisateur sans être connecté*/
    public function update_password(){
        $this->load->view('users/users_ex/update_password');
    }

    /**vérifi puis envois l'email de modification du mot de passe */
    public function send_mail(){
        /**validation du formulaire */
        $this->form_validation->set_rules('email', 'email', 'required|valid_email',
            array(
                'required'     => 'entre l\'%s',
                'valid_email'     => 'email pas correct!'  
            )
        );

        if($this->form_validation->run()){
            $email = $this->input->post('email');
            $requestOk = $this->users->get_single_entreprise($email);

            if($requestOk){
                $email=$requestOk['email_en'];
                $objet_email="Demande de réinitialisation de mot de passe";

                $messages='
                    Réinitialiser votre mot de passe ? <br>
                    <p> 
                        Si vous avez demandé à réinitialiser le mot de passe pour 
                        '.$requestOk['nom_en'].' , 
                        utilisez le lien de modification ci-dessous. <br>
                        '.base_url('pass/'.$requestOk['matricule_en']).'
                        Si vous n\'avez pas fait cette demande, ignorez cet email.
                    </p>
                ';

                //$this->send_e($email, $objet_email, $messages);
                    $from = $this->config->item('smtp_user');
                    $to = $email;
                    $subject = $objet_email;

                    $this->email->set_newline("\r\n");
                    $this->email->from($from);
                    $this->email->to($to);
                    $this->email->subject($subject);
                    $this->email->message($messages);

                    
                    if ($this->email->send()){
                        $array = array(
                            'success' => '
                                <div class="alert alert-success fade show" role="alert">
                                    <div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
                                    <div class="alert-text">lien de modification envoyé</div>
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
                                <div class="alert alert-success fade show" role="alert">
                                    <div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
                                    <div class="alert-text">lien non envoyé. vérifier votre connexion internet</div>
                                    <div class="alert-close">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="la la-close"></i></span>
                                        </button>
                                    </div>
                                </div>
                            '
                        );
                        /*show_error($this->email->print_debugger());*/
                    }

            }else{
                $array = array(
                    'success' => '
                        <div class="alert alert-danger fade show" role="alert">
                            <div class="alert-icon"><i class="flaticon2-cross"></i></div>
                            <div class="alert-text">cet email n\'existe pas</div>
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
				'email_error' => form_error('email')
			);
        }
        echo json_encode($array);
    }


    /**modifier le mot de passe d'un groupe ou d'une entreprise */
    public function update_pass_en($matricul = NULL){

        $this->form_validation->set_rules('password', 'mot de passe', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni le %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('cpassword', 'confirmation mot de passe', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]|matches[password]',
            array(
                    'required'      => 'Tu n\'as pas fourni la %s.',
                    'regex_match'     => 'caractère(s) non autorisé!',
                    'matches'  => 'la confirmation de mot de passe doit être identique au mot de passe'
            )
        );

        if ($this->form_validation->run()){
            $input = array(
                'password_en'=> password_hash($this->input->post('password'),PASSWORD_BCRYPT),
                'modifier_le_en'=> dates()
            );
            $ok =  $this->users->update_pass_en($matricul,$input);
            if($ok){
                flash('success','mot de passe modifier! allez à la page de connexion');
                redirect('login');
            }else{
                flash('error','mot de passe non modifier');
            }
        }
        $this->load->view('users/users_ex/form_update_password');
    }
    
    /**affiche le profil d'un utilisateur */
    public function profil_user($matricule = NULL){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

             /**selectionne la liste des pays dans lla base des données */
            $data['pays']= $this->users->get_pays();

        /**modification des iformations d'un utilisateur(employé) */
        if(session('users')['status'] == "" && session('users')['matricule_ag'] == "" && session('users')['matricule_serv'] == ""){

            /**selection de la forme juridique de l'entreprise */
            $data['juridique']= $this->users->get_form_juridique();

           

            /**selectionne toutes les entreprises dont le matricule est egale a:*/
            $data['user'] = $this->users->getsingleentreprise($matricule);
            $data['telephone'] = explode(",", $data['user']['telephone_en']);



            /**validation du formulaire de la modification d'une entreprise */
            $this->form_validation->set_rules('nom', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
                array(
                    'required'      => 'Tu n\'as pas fourni le %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
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
            $this->form_validation->set_rules('juridique', 'form juridique', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
                array(
                        'regex_match'     => 'caractère(s) non autorisé!'
                )
            );

            $this->form_validation->set_rules('pays', 'pays', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
                array(
                        'required'      => 'Tu n\'as pas fourni le %s.',
                        'regex_match'     => 'caractère(s) non autorisé!'
                )
            );


            $this->form_validation->set_rules('telephone1', 'telephone1', 'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
                array(
                        'required'      => 'Tu n\'as pas fourni le %s.',
                        'regex_match'     => 'caractère(s) non autorisé!'
                )
            );

            $this->form_validation->set_rules('telephone2', 'telephone2', 'regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
                array(
                        'regex_match'     => 'caractère(s) non autorisé!'
                )
            );

            $this->form_validation->set_rules('site', 'site', 'valid_url',
                array(
                        'valid_url'     => 'url incorrect!'
                )
            );
            if($this->form_validation->run()){
                $matricule = session('users')['matricule'];
                $input = array(
                    'nom_en'=> $this->input->post('nom'),
                    'adresse_en'=> $this->input->post('adresse'),
                    'activite_en'=> $this->input->post('activite'),
                    'form_juridique_en'=> $this->input->post('juridique'),
                    'pays_en'=> $this->input->post('pays'),
                    'telephone_en'=> $this->input->post('telephone1').','.$this->input->post('telephone2'),
                    'site_internet_en'=> $this->input->post('site'),
                    'modifier_le_en'=> dates()
                ); 
                
               $requestOk = $this->users->update_entreprise($matricule, $input);
               if($requestOk){
                    flash('success','informations! modifiées.');
                    redirect('profil/'.$matricule);
               }else{
                    flash('error',"erreur survenu, verifiez votre connexion internet");
                    redirect('profil/'.$matricule);
               }
            }
            $this->load->view('users/users_in/profil', $data);
        }else{

            /**selectionne toutes les entreprises dont le matricule est egale a:*/
            $data['employe'] = $this->users->getsingleemploye($matricule);
            $data['telephone_emp'] = explode(",", $data['employe']['telephone_emp']);

            $this->form_validation->set_rules('nom', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
                array(
                        'required'      => 'Tu n\'as pas fourni le %s.',
                        'regex_match'     => 'caractère(s) non autorisé!'
                )
            );

            $this->form_validation->set_rules('adresse', 'adresse', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
                array(
                        'regex_match'     => 'caractère(s) non autorisé!'
                )
            );

            $this->form_validation->set_rules('email', 'email', 'valid_email',
                array(
                        'valid_email'     => 'email pas valide'
                )
            );

            $this->form_validation->set_rules('telephone1', 'telephone', 'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni le %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
                )
            );

            $this->form_validation->set_rules('telephone2', 'telephone', 'regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
                array(
                        'regex_match'     => 'caractère(s) non autorisé!'
                )
            );
            $this->form_validation->set_rules('date_naiss', 'date de naissance', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
                array(
                        'regex_match'     => 'caractère(s) non autorisé!'
                )
            );

            $this->form_validation->set_rules('fonction', 'fonction', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
                array(
                        'required'      => 'Tu n\'as pas fourni la %s.',
                        'regex_match'     => 'caractère(s) non autorisé!'
                )
            );

            if($this->form_validation->run()){
                $matricule = $this->input->post('matricule');
                $input = array(
                    'nom_emp' => $this->input->post('nom'),
                    'adresse_emp' => $this->input->post('adresse'),
                    'email_emp' => $this->input->post('email'),
                    'telephone_emp' => $this->input->post('telephone1').','.$this->input->post('telephone2'),
                    'date_naiss_emp' => $this->input->post('date_naiss'),
                    'fonction_emp' => $this->input->post('fonction'),
                    'modifier_le_emp' => dates()
                );
               
                /**inserer dans la base des données */
                $query = $this->users->update_employe($input, $matricule);

                if($query){
                    flash('success','informations modifiées.');
                    redirect('profil/'.$matricule);
               }else{
                    flash('error',"erreur survenu, verifiez votre connexion internet");
                    redirect('profil/'.$matricule);
               }
            }

            $this->load->view('users/users_in/profil',$data);

        }

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }

    /**modifier le mot de passe d'un employé*/
    public function update_pass_employe(){
        $this->logged_in();

        $this->form_validation->set_rules('ancientpassword', 'mot de passe actuel', 'required',
            array(
                'required' => 'vous n\'avez rien saisi',
                'matches' => 'la confirmation du mot de passe doit être identique au mot de passe'
            )
        );

        $this->form_validation->set_rules('password', 'mot de passe', 'required|regex_match[/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/]',
            array(
                'required' => 'vous n\'avez rien saisi',
                'regex_match'=> '<b>Attention Par sécurité, votre mot de passe doit respecter les conditions suivantes: </b> <br/>
                - de 8 à 15 caractères <br/>
                - au moins une lettre minuscule <br/>
                - au moins une lettre majuscule <br/>
                - au moins un chiffre <br/>
                - au moins un de ces caractères spéciaux: $ @ % * + - _ ! <br/>'
            )
        );

        $this->form_validation->set_rules('cpassword', 'confirmation du mot de passe', 'required|matches[password]',
            array(
                'required' => 'vous n\'avez rien saisi',
                'matches' => 'la confirmation du mot de passe doit être identique au mot de passe'
            )
        ); 

        if($this->form_validation->run()){

            $matricule = trim($this->input->post('matricule_emp_pass'));

            /**1: on verifie que le mot de passe saisi correspond au mot de passe en existant */
            $login_emp = $this->users->login_emp1($matricule);

            if(!empty($login_emp)){

                $userpassword = trim($this->input->post('ancientpassword'));
                if(password_verify($userpassword, $login_emp['password_emp'])){

                    $input = array(
                        'password_emp' => password_hash($this->input->post('password'),PASSWORD_BCRYPT),
                        'modifier_le_emp' => dates()
                    );
    
                    $validate = $this->users->update_employe($input, $matricule);
                    if($validate){
                        $array = array(
                            'success' => '
                                <div class="alert alert-success fade show" role="alert">
                                    <div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
                                    <div class="alert-text">
                                        <b>
                                            mot de passe modifier avec succès. 
                                            deconnectez-vous puis utiliser le nouveau mot de passe pour vous connecter
                                        </b>
                                    </div>
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
                            'loss' => '
                                <div class="alert alert-success fade show" role="alert">
                                    <div class="alert-icon"><i class="fa fa-angry"></i></div>
                                    <div class="alert-text">
                                        erreur survenu!... 
                                        vérifier votre connexion internet puis reéssayer
                                    </div>
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
                        'loss' => '
                                <div class="alert alert-danger fade show" role="alert">
                            <div class="alert-icon"><i class="fa fa-angry"></i></div>
                            <div class="alert-text">
                                <b>erreur survenu!... 
                                assurez-vous d\'avoir saisi le bon mot de passe actuel</b>
                            </div>
                            <div class="alert-close">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="la la-close"></i></span>
                                </button>
                            </div>
                        </div>'
                    ); 
                }
            }else{
                $array = array(
                    'loss' => '
                    <div class="alert alert-danger fade show" role="alert">
                        <div class="alert-icon"><i class="fa fa-angry"></i></div>
                        <div class="alert-text">
                            <b>erreur survenu!... 
                            le système ne retrouve plus votre profil contactez l\'administrateur </b>
                        </div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-close"></i></span>
                            </button>
                        </div>
                    </div>'
                );
            }
            
        }else{
            $array = array(
                'error'   => true,
                'update_ancientpassword_error'  => form_error('ancientpassword'),
                'update_password_error' => form_error('password'),
                'confirm_update_password_error' => form_error('cpassword')
            );
        }

        echo json_encode($array);
    }

  
    /**affiche la liste des agences */
	public function agence_user(){
		$this->logged_in();
		$mat_en = session('users')['matricule'];
        $mat_agence = session('users')['matricule_ag'];
        $output = '';
        if(!empty($mat_agence)){
            $singleagence = $this->agence->get_single_agence($mat_agence);
            if(!empty($singleagence)){
                if(empty($mat_agence)){
                    $output .='
                        <option value="'.$singleagence['matricule_ag'].'" selected>'.$singleagence['nom_ag'].'</option> 
                    ';
                }
            }
        }else{
            $agences = $this->agence->get_agence($mat_en);
            if(!empty($agences)){
                
                $output .='
                        <option value=""></option> 
                ';
                
                foreach ($agences as $key => $value){
                    $output .='
                        <option value="'.$value['matricule_ag'].'">'.$value['nom_ag'].'</option> 
                    ';
                }
            }
        }
        echo json_encode($output);
    }

    /*affiche la liste des services*/
	public function user_services(){
		$this->logged_in();
		$matricule_en = session('users')['matricule'];
		$service = $this->service->all_service($matricule_en);
		$output ='';
		if(!empty($service)){
            $output .='
                <option></option> 
            ';
			foreach ($service as $key => $value) {
                $output .='
                    <option value="'.$value['matricule_serv'].'">'.$value['nom_serv'].'</option> 
                ';
            }
        }
        echo json_encode($output);
    }

    /** affiche la liste des services d'une agence donnéé*
    public function getserviceagence(){
        $this->logged_in();
        $matricule_en = session('users')['matricule'];
        $matricule_agence = $this->input->post('matricule_ag');
        $service = $this->service->serviceagence($matricule_en,$matricule_agence);
        $output ='';
        if(!empty($service)){
                $output .='
                    <option></option> 
                ';
            foreach ($service as $key => $value) {
                $output .='
                    <option value="'.$value['matricule_serv'].'">'.$value['nom_serv'].'</option> 
                ';
            }
        }
        echo json_encode($output);
    }*/

    /**affiche le mot de passe provisoir */
    public function pass_provi(){
        $this->logged_in();
        $output ='';
        $output .=pass(10);
        echo json_encode($output);
    }

    /**affiche la page de création d'un nouvel utilisateur une fois connecté*/
    public function inscription_user(){
        $this->logged_in();
        /*$agence="";
        $service="";
        $filter = $this->users->filter_users('','',session('users')['matricule']);
        debug($filter); die();*/
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');
        $amtricule_en = session('users')['matricule'];

        $this->load->view('users/users_in/new_users');

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }

    /**désactivé un employé */
    public function desactive(){
        $this->logged_in();
        $matricule = $this->input->post('mat');
        $input = array(
            'etat_emp' => NULL
        );
        $desactive = $this->users->desactive_employe($matricule,$input);
        if($desactive){
            $array = array(
                'success' => 'ok'
            );
        }
        echo json_encode($array);
    }

    /**activé le compte d'un employé */
    public function active(){
        $this->logged_in();
        $matricule = $this->input->post('mat');
        $input = array(
            'etat_emp' => 'on'
        );
        $active = $this->users->active_employe($matricule,$input);
        if($active){
            $array = array(
                'success' => 'ok'
            );
        }
        echo json_encode($array);
    }

     /**afficher les informations d'un employé dans le formulaire de modification*/
     public function edit(){
        $this->logged_in(); 
        $matricule = trim($this->input->post('mat'));
        $output ="";

        $detail = $this->users->detail_employe($matricule);
        if(!empty($detail)){
            /*** on selectionne l'historique des changement dans la base des données*/
            $historique = $this->users->historique_mutation($matricule);
            if(!empty($historique)){
                foreach ($historique as $key => $value) {
                   $output .='
                        <tr>
                            <td>'.$value['nom_ag'].'</td>
                            <td>'.$value['nom_serv'].'</td>
                            <td>'.date('d-m-Y H:i:s',strtotime($value['date_change'])).'</td>
                        </tr>
                   ';
                }
            }else{
                $output .='pas d\'historique pour le moment';
            }

            $tel_emp = explode(",", $detail['telephone_emp']);
            $array = array(
                'tel1_emp' => $tel_emp[0],
                'tel2_emp' => $tel_emp[1],
                'employe'=>$detail,
                'historique'=>$output
            );
        }

        echo json_encode($array);
    }

    /**details sur un employé(utilisateur) */
    public function details(){
        $this->logged_in();
        $output = '';
        $matricule = $this->input->post('mat');
        $detail = $this->users->detail_employe($matricule);
        if(!empty($detail)){
            $output .='
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Matricule
                        <span class="badge"><h5>'.$detail['matricule_emp'].'</h5></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Nom
                        <span class="badge"><h5>'.$detail['nom_emp'].'</h5></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Adresse
                        <span class="badge"><h5>'.$detail['adresse_emp'].'</h5></span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Fonction
                        <span class="badge"><h5>'.$detail['fonction_emp'].'</h5></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Etat';
                        if ($detail['etat_emp']=="on") {
                            $output .='<span class="badge badge-primary">compte activé</span>';
                        }else{
                            $output .='<span class="badge badge-danger">compte désactivé</span>'; 
                        }
        $output .='</li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Date Naissance
                        <span class="badge"><h5>'.$detail['date_naiss_emp'].'</h5></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Contacts
                        <span class="badge"><h5>'.$detail['email_emp'].' / '.$detail['telephone_emp'].'</h5></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Date création
                        <span class="badge"><h5>'.date('d-m-Y H:i:s',strtotime($detail['creer_le_emp'])).'</h5></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Dernière Modification
                        <span class="badge"><h5>'.date('d-m-Y H:i:s',strtotime($detail['modifier_le_emp'])).'</h5></span>
                    </li>
                </ul>
            ';
        }
        echo json_encode($output);
    }

   

    /**afficher la liste des utilisateurs(employé) */
    public function get_all_employe(){
        $this->logged_in();
        $output = '';

        $amtricule_en = session('users')['matricule'];
        
        $recherches = trim($this->input->post('input'));
        
        if($recherches != ''){
            $employes = $this->users->recherche($amtricule_en,$recherches);
            if(!empty($employes)){

                foreach ($employes as $key => $value){
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
                                                       '.$value['nom_emp'].'
                                                    </a>
                                                    <span class="kt-widget__desc">
                                                        '.$value['matricule_emp'].'
                                                    </span>';
                                                   if($value['etat_emp'] == 'on'){
                                                       $output .= '
                                                        <span class="kt-widget__desc text-info">
                                                            activé
                                                        </span>
                                                       '; 
                                                   }else{
                                                        $output .= '
                                                        <span class="kt-widget__desc text-danger">
                                                            désactivé
                                                        </span>
                                                    ';  
                                                   }
                                $output .='</div>     
                                                <div class="kt-widget__toolbar">
                                                    <button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-facebook mute">
                                                        <i class="fab fa-ioxhost"></i>
                                                    </button>

                                                    <button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-facebook edit">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-twitter details">
                                                        <i class="fa fa-eye"></i>
                                                    </button>';
                                                    if($value['etat_emp'] == 'on'){
                                        $output .='<button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-instagram desactive">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </button>';
                                                    }else{
                                        $output .='<button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-instagram active">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </button>';
                                                    }
                                $output .='
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
                $output .='aucun employé ayant ce critère trouvé, il pourait être dans une autre agence';
            }
        }else{
            $employes = $this->users->get_employes($amtricule_en);
            if(!empty($employes)){
                $output = '';
                foreach ($employes as $key => $value) {
                    $output .='
                        <div class="col-xl-6 col-lg-6">
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
                                                    '.$value['nom_emp'].'
                                                    </a>
                                                    <span class="kt-widget__desc">
                                                        '.$value['matricule_emp'].'
                                                    </span>';
                                                if($value['etat_emp'] == 'on'){
                                                    $output .= '
                                                        <span class="kt-widget__desc text-info">
                                                            activé
                                                        </span>
                                                    '; 
                                                }else{
                                                        $output .= '
                                                        <span class="kt-widget__desc text-danger">
                                                            désactivé
                                                        </span>
                                                    ';  
                                                }
                                $output .='</div>     
                                                <div class="kt-widget__toolbar">
                                                    <button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-facebook mute">
                                                        <i class="fab fa-ioxhost"></i>
                                                    </button>

                                                    <button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-facebook edit">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-twitter details">
                                                        <i class="fa fa-eye"></i>
                                                    </button>';
                                                    if($value['etat_emp'] == 'on'){
                                        $output .='<button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-instagram desactive">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </button>';
                                                    }else{
                                        $output .='<button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-instagram active">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </button>';
                                                    }
                                $output .='
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

    /**filtrer les employés */
    public function filter_employe(){
        $this->logged_in();

        $this->form_validation->set_rules('agencefilter', 'agence', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );
        $this->form_validation->set_rules('servicefilter', 'service', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
        array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        if($this->form_validation->run()){
            $amtricule_en = session('users')['matricule'];

            $agence = trim($this->input->post('agencefilter'));
            $service = trim($this->input->post('servicefilter'));

            $output = ""; 

            /**on selectionne les employées en fonction de l'agence, du service et de l'entreprise */
            $filter = $this->users->filter_users($agence,$service,$amtricule_en);
            if(!empty($filter)){
                foreach ($filter as $key => $value){
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
                                                       '.$value['nom_emp'].'
                                                    </a>
                                                    <span class="kt-widget__desc">
                                                        '.$value['matricule_emp'].'
                                                    </span>';
                                                   if($value['etat_emp'] == 'on'){
                                                       $output .= '
                                                        <span class="kt-widget__desc text-info">
                                                            activé
                                                        </span>
                                                       '; 
                                                   }else{
                                                        $output .= '
                                                        <span class="kt-widget__desc text-danger">
                                                            désactivé
                                                        </span>
                                                    ';  
                                                   }
                                $output .='</div>     
                                                <div class="kt-widget__toolbar">
                                                    <button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-facebook mute">
                                                        <i class="fab fa-ioxhost"></i>
                                                    </button>

                                                    <button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-facebook edit">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-twitter details">
                                                        <i class="fa fa-eye"></i>
                                                    </button>';
                                                    if($value['etat_emp'] == 'on'){
                                        $output .='<button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-instagram desactive">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </button>';
                                                    }else{
                                        $output .='<button id="'.$value['matricule_emp'].'" class="btn btn-icon btn-circle btn-label-instagram active">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </button>';
                                                    }
                                $output .='
                                                </div>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Portlet-->
                        </div>
                    ';
                    $array = array(
                        'success' => $output,
                    );
                }
            }else{
                $array = array(
                    'success' => 'aucun employé trouvé ayant ces critères',
                );
            }

            
        }else{
            $array = array(
				'error'   => true,
				'agencefilter_error' => form_error('agencefilter'),
				'servicefilter_error' => form_error('servicefilter')
			);
        }
        echo json_encode($array);
    }

    /**eregistrer un employé */
    public function new_employe(){
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
        $this->form_validation->set_rules('service', 'service', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
        array(
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('nom', 'nom', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni le %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('adresse', 'adresse', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('email', 'email', 'valid_email',
            array(
                    'valid_email'     => 'email pas valide'
            )
        );
        $this->form_validation->set_rules('telephone1', 'telephone', 'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
        array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('telephone2', 'telephone', 'regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
            array(
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );
        $this->form_validation->set_rules('date_naiss', 'date de naissance', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('fonction', 'fonction', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni la %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );
        
        if($this->form_validation->run()){
            $matricule = code(10);
            if($this->input->post('agence') == '' && $this->input->post('service') != ''){
                
                $input = array(
                    'matricule_emp' => $matricule,
                    'mat_en' => $this->input->post('entreprise'),
                    'mat_serv' => $this->input->post('service'),
                    'nom_emp' => $this->input->post('nom'),
                    'adresse_emp' => $this->input->post('adresse'),
                    'email_emp' => $this->input->post('email'),
                    'telephone_emp' => $this->input->post('telephone1').','.$this->input->post('telephone2'),
                    'date_naiss_emp' => $this->input->post('date_naiss'),
                    'fonction_emp' => $this->input->post('fonction'),
                    'password_emp' => password_hash($this->input->post('password'),PASSWORD_BCRYPT),
                    'creer_le_emp' => dates()
                );
               
                /**inserer dans la base des données */
                $query = $this->users->save_employe($input);
    
                if($query){
                    $array = array(
                        'success' => '
                            <div class="alert alert-success fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
                                <div class="alert-text">
                                    Utilisateur crée avec succès<br>
                                    vous devez copiez et garder les informations suivantes: <br>
                                    matricule: <b>'.$input['matricule_emp'].'</b> <br>
                                    mot de passe provisoir: <b>'.$this->input->post('password').'</b>
                                </div>
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
                                <div class="alert-text">erreur survenu! utilisateur non crée</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                        '
                    );
                }
            }else if($this->input->post('service') == '' && $this->input->post('agence') != ''){
                $input = array(
                    'matricule_emp' => $matricule,
                    'mat_en' => $this->input->post('entreprise'),
                    'mat_ag' => $this->input->post('agence'),
                    'nom_emp' => $this->input->post('nom'),
                    'adresse_emp' => $this->input->post('adresse'),
                    'email_emp' => $this->input->post('email'),
                    'telephone_emp' => $this->input->post('telephone1').','.$this->input->post('telephone2'),
                    'date_naiss_emp' => $this->input->post('date_naiss'),
                    'fonction_emp' => $this->input->post('fonction'),
                    'password_emp' => password_hash($this->input->post('password'),PASSWORD_BCRYPT),
                    'creer_le_emp' => dates()
                );
               
                /**inserer dans la base des données */
                $query = $this->users->save_employe($input);
    
                if($query){
                    $array = array(
                        'success' => '
                            <div class="alert alert-success fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
                                <div class="alert-text">
                                    Utilisateur crée avec succès<br>
                                    vous devez copiez et garder les informations suivantes: <br>
                                    matricule: <b>'.$input['matricule_emp'].'</b> <br>
                                    mot de passe provisoir: <b>'.$this->input->post('password').'</b>
                                </div>
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
                                <div class="alert-text">erreur survenu! utilisateur non crée</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                        '
                    );
                }
            }else if($this->input->post('agence') == '' && $this->input->post('agence') == ''){
                $input = array(
                    'matricule_emp' => $matricule,
                    'mat_en' => $this->input->post('entreprise'),
                    'nom_emp' => $this->input->post('nom'),
                    'adresse_emp' => $this->input->post('adresse'),
                    'email_emp' => $this->input->post('email'),
                    'telephone_emp' => $this->input->post('telephone1').','.$this->input->post('telephone2'),
                    'date_naiss_emp' => $this->input->post('date_naiss'),
                    'fonction_emp' => $this->input->post('fonction'),
                    'password_emp' => password_hash($this->input->post('password'),PASSWORD_BCRYPT),
                    'creer_le_emp' => dates()
                );
               
                /**inserer dans la base des données */
                $query = $this->users->save_employe($input);
    
                if($query){
                    $array = array(
                        'success' => '
                            <div class="alert alert-success fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
                                <div class="alert-text">
                                    Utilisateur crée avec succès<br>
                                    vous devez copiez et garder les informations suivantes: <br>
                                    matricule: <b>'.$input['matricule_emp'].'</b> <br>
                                    mot de passe provisoir: <b>'.$this->input->post('password').'</b>
                                </div>
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
                                <div class="alert-text">erreur survenu! utilisateur non crée</div>
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
                    'matricule_emp' => $matricule,
                    'mat_en' => $this->input->post('entreprise'),
                    'mat_ag' => $this->input->post('agence'),
                    'mat_serv' => $this->input->post('service'),
                    'nom_emp' => $this->input->post('nom'),
                    'adresse_emp' => $this->input->post('adresse'),
                    'email_emp' => $this->input->post('email'),
                    'telephone_emp' => $this->input->post('telephone1').','.$this->input->post('telephone2'),
                    'date_naiss_emp' => $this->input->post('date_naiss'),
                    'fonction_emp' => $this->input->post('fonction'),
                    'password_emp' => password_hash($this->input->post('password'),PASSWORD_BCRYPT),
                    'creer_le_emp' => dates()
                );
               
                /**inserer dans la base des données */
                $query = $this->users->save_employe($input);
    
                if($query){
                    $array = array(
                        'success' => '
                            <div class="alert alert-success fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
                                <div class="alert-text">
                                    Utilisateur crée avec succès<br>
                                    vous devez copiez et garder les informations suivantes: <br>
                                    matricule: <b>'.$input['matricule_emp'].'</b> <br>
                                    mot de passe provisoir: <b>'.$this->input->post('password').'</b>
                                </div>
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
                                <div class="alert-text">erreur survenu! utilisateur non crée</div>
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
				'service_error' => form_error('service'),
                'nom_error' => form_error('nom'),
				'adresse_error' => form_error('adresse'),
				'email_error' => form_error('email'),
                'telephone1_error' => form_error('telephone1'),
                'telephone2_error' => form_error('telephone2'),
				'date_naiss_error' => form_error('date_naiss'),
				'fonction_error' => form_error('fonction')
			);
        }
        echo json_encode($array);
    }

    /** changé le service et ou l'agence d'un employé*/
    public function mute_employe(){
        $this->form_validation->set_rules('matricule11', 'matricule', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'remplire le %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('agence1', 'agence', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('service1', 'service', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        if($this->form_validation->run()){

            $matricule = trim($this->input->post('matricule11'));
            $agence = trim($this->input->post('agence1'));
            $service = trim($this->input->post('service1'));

            $agence = !empty($agence)?$agence:NULL;
            $service = !empty($service)?$service:NULL;

            $input = array(
                'mat_ag' => $agence,
                'mat_serv' => $service
            );
            /**modifier dans la base des données*/
            $query = $this->users->update_employe($input,$matricule);
            if($query){

                /**on enregistre le changement éffectué sur le client */
                $inputdata = array(
                    'id_agence' => $agence,
                    'id_service' => $service,
                    'id_employe' =>$matricule,
                    'date_change'=>dates()
                );
                $querys = $this->users->save_historique_mutation($inputdata);
                if($querys){
                    $array = array(
                        'success' => '
                            <div class="alert alert-success fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
                                <div class="alert-text">
                                    Utilisateur modifier... vous allez être déconnecté dans 5 secondes...</b>
                                </div>
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
                                <div class="alert-text">
                                    Erreur survenu, historique non pris en compte</b>
                                </div>
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
                                <div class="alert-text">erreur survenu! utilisateur non modifier</div>
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
                'matricule11_error' => form_error('matricule11'),
                'agence1_error' => form_error('agence1'),
                'service1_error' => form_error('service1')
            );
        }

        echo json_encode($array);
    }

    /**modifier un employé(utilisateur) */
    public function update(){
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

        $this->form_validation->set_rules('email1', 'email', 'valid_email',
            array(
                    'valid_email'     => 'email pas valide'
            )
        );
        $this->form_validation->set_rules('telephone11', 'telephone', 'required|regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
        array(
                'required'      => 'Tu n\'as pas fourni le %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('telephone21', 'telephone', 'regex_match[/^[0-9\-\(\)\/\+\s]*$/]',
            array(
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );
        $this->form_validation->set_rules('date_naiss1', 'date de naissance', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('fonction1', 'fonction', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                    'required'      => 'Tu n\'as pas fourni la %s.',
                    'regex_match'     => 'caractère(s) non autorisé!'
            )
        );
        
        if($this->form_validation->run()){

            $matricule =  $this->input->post('matricule');
                $input = array(
                    'nom_emp' => $this->input->post('nom1'),
                    'adresse_emp' => $this->input->post('adresse1'),
                    'email_emp' => $this->input->post('email1'),
                    'telephone_emp' => $this->input->post('telephone11').','.$this->input->post('telephone21'),
                    'date_naiss_emp' => $this->input->post('date_naiss1'),
                    'fonction_emp' => $this->input->post('fonction1'),
                    'modifier_le_emp' => dates()
                );
                
                /**inserer dans la base des données */
               $query = $this->users->update_employe($input,$matricule);
                if($query){
                    $array = array(
                        'success' => '
                            <div class="alert alert-success fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
                                <div class="alert-text">
                                    Utilisateur modifier avec succès
                                </div>
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
                                <div class="alert-text">erreur survenu! utilisateur non modifier</div>
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
				'entreprise1_error' => form_error('entreprise1'),
				'agence1_error' => form_error('agence1'),
				'service1_error' => form_error('service1'),
                'nom1_error' => form_error('nom1'),
				'adresse1_error' => form_error('adresse1'),
				'email1_error' => form_error('email1'),
                'telephone11_error' => form_error('telephone11'),
                'telephone21_error' => form_error('telephone21'),
				'date_naiss1_error' => form_error('date_naiss1'),
				'fonction1_error' => form_error('fonction1')
			);
        }
        echo json_encode($array);
    }

    /**muter un utilisateur */
    public function user_mutate(){
        $this->logged_in();

        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

        $this->load->view('users/users_in/mutate_users');

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }




    public function send_e($email, $objet_email, $messages){
		
        $from = $this->config->item('smtp_user');
        $to = $email;
        $subject = $objet_email;

        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($messages);

		
        if ($this->email->send()){
            $array = array(
                'success' => '
                    <div class="alert alert-success fade show" role="alert">
                        <div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
                        <div class="alert-text">lien de modification envoyé</div>
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
                    <div class="alert alert-success fade show" role="alert">
                        <div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
                        <div class="alert-text">lien non envoyé. vérifier votre connexion internet</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-close"></i></span>
                            </button>
                        </div>
                    </div>
                '
            );
            /*show_error($this->email->print_debugger());*/
        }
        echo json_encode($array);
    }



  /** liste des client d'une d'un utilisateur*/


    /**liste des clients d'un utilisateur*/
    public function costomuser(){
        $output = "";
       $this->logged_in(); 
       
       $matricule_emp = session('users')['matricule_emp'];
       $matricule_en = session('users')['matricule'];
       
       $recherche = trim($this->input->post('content'));
        if(!empty($recherche)){
            $costomesusearch = $this->users->costomus_search($matricule_en,$recherche,$matricule_emp);
            if(!empty($costomesusearch)){
                foreach($costomesusearch as $info){
                    $tel = !empty($info['telephone_cli'])?explode(',',$info['telephone_cli']):'';
                    $output.='
                        <tr>
                            <td>'.$info['nom_cli'].'</td>
                            <td><a href="tel:'.$tel[0].'">'.$tel[0].'</a> , <a href="tel:'.$tel[1].'">'.$tel[1].'</a></td>
                            <td><a href="mailto:'.$info['email_cli'].'">'.$info['email_cli'].'</a></td>
                            <td>'.$info['adresse_cli'].'</td>
                            <td>'.$info['date_naiss'].'</td>
                            <td>'.date('d-m-Y à H:i:s',strtotime($info['creer_le_cli'])).'</td>
                        </tr>
                    '; 
                }
            }else{
                $output.="aucun client trouver dans votre liste de client"; 
            }
        }else{
            $costomesu = $this->users->costomus($matricule_en,$matricule_emp);
            if(!empty($costomesu)){
                foreach($costomesu as $info){
                        $tel = !empty($info['telephone_cli'])?explode(',',$info['telephone_cli']):'';
                        $output.='
                            <tr>
                                <td>'.$info['nom_cli'].'</td>
                                <td><a href="tel:'.$tel[0].'">'.$tel[0].'</a> , <a href="tel:'.$tel[1].'">'.$tel[1].'</a></td>
                                <td><a href="mailto:'.$info['email_cli'].'">'.$info['email_cli'].'</a></td>
                                <td>'.$info['adresse_cli'].'</td>
                                <td>'.$info['date_naiss'].'</td>
                                <td>'.$info['creer_le_cli'].'</td>
                            </tr>
                        '; 
                }
            }else{
                $output.="vous n'avez pas encore de client";  
            }
        } 
       
       echo json_encode($output);
    }


    /**affiche les ventes d'un client*/
    public function showusersels(){
      $this->logged_in(); 
      $output = "";
        $this->form_validation->set_rules('start', 'debut', 'required',
            array(
                'required'=>'choisi le debut',
            )
        );
        
        $this->form_validation->set_rules('end', 'fin', 'required',
            array(
                'required'=>'choisi la fin',
            )
        );
        
		if($this->form_validation->run()){
		    $start = $this->input->post('start');
		    $end = $this->input->post('end');
		    $debut = date('Y-m-d', strtotime($start));
		    $fin = date('Y-m-d', strtotime($end));
		    
		    $mat_en = session('users')['matricule'];
		    $mat_emp = session('users')['matricule_emp'];
            $abrev = array("RT", "RC", "BR");

            $output ="";

            /**
             * 1: on selectionne les clients de l'utilisateurs qui apparaissent aumoins une fois dans les ventes
             * sur une periode en RT, RC, BR
            */
            $cliusers = $this->users->costomusvente_get($mat_en,$mat_emp,$abrev,$debut,$fin);
            if(!empty($cliusers)){
                $totaldette = 0;
                $totalcash = 0;
                foreach ($cliusers as $key => $value){
                    /**pour chaque client, ayant éffectuté les achats, on selectionne ses achats en question */
                    $sels = $this->users->selsuserperiod1($abrev,$mat_en,$value['code_client_document'],$debut,$fin);
                    if(!empty($sels)){
                        $rttotal = 0;
                        $rctotalreg = 0;
                        $rctotalnonreg = 0;
                        $brtotal = 0;
                        foreach ($sels as $key => $valuessels){
                            /**on test le type de document pour chaque client pour affectuer les calculs */
                            $typedoc = typedocdetect($valuessels['code_document']);
                            if($typedoc ==  "RT"){
                                $rttotal = (($valuessels['pt_net_document'] == $valuessels['pt_ht_document']) || empty($valuessels['pt_net_document']))?($rttotal + $valuessels['pt_ttc_document']):($rttotal + $valuessels['pt_net_document']);
                            }else if($typedoc ==  "RC"){
                                /**on verifie si c'est réglé ou pas */
                                $regornot = empty($valuessels['pt_net_document'])?$valuessels['pt_ht_document']:$valuessels['pt_net_document'];
                                if($valuessels['dette_regler'] != $regornot){
                                    $rctotalnonreg = ($rctotalnonreg + $valuessels['dette_restante']);
                                }else{
                                    $rctotalreg = ($rctotalreg + $valuessels['dette_regler']);
                                }
                            }else if($typedoc ==  "BR"){
                                $brtotal = (($valuessels['pt_net_document'] == $valuessels['pt_ht_document']) || empty($valuessels['pt_net_document']))?($rttotal + $valuessels['pt_ttc_document']):($rttotal + $valuessels['pt_net_document']);
                            }
                        }
                        $totaldette+=$rctotalnonreg;
                        $totalcash+=($rttotal + $rctotalreg - $brtotal);
                        $cash = (($rttotal + $rctotalreg + $brtotal)==0)?0:numberformat(($rttotal + $rctotalreg + $brtotal));
                        $dette = ($rctotalnonreg==0)?0:numberformat($rctotalnonreg);
                        $output .= '<tr><td>'.($value['nom_cli']).'</td><td>'.$cash.'</td><td>'.$dette.'</td></tr>';  
                    }
                }
                $output .='<tr><th>TOTAUX</th><th>'.numberformat($totalcash).'</th><th>'.numberformat($totaldette).'</th></tr>';
                $array = array(
                    'success' => $output,
                );
            }else{
                $array = array(
                    'success' => '<tr><td colspan="3"><b class="text-danger">vous n\'avez aucun client pour le moment OU aucun de vos client n\'a encore fait d\'achat</b></td></tr>',
                );  
            }
		    
        }else{
            $array = array(
				'error'   => true,
				'start_error' => form_error('start'),
				'end_error' => form_error('end')
			);
        }
      
      echo json_encode($array);
    }



    /**se deconnecter */
    public function logout(){
        //$this->session->sess_destroy('users');
        un_sess('users');
        flash("info","vous êtes déconnecté");
        redirect('login');
    }


}
