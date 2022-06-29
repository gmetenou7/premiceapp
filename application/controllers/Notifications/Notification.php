<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('Notifications/Notification_model','notification');
        $this->load->model('Costomers/Costomer_model','costomer');
	}

	/**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}

    
	public function index(){
		

	}

    /**anniversaire d'un employé et ou d'un client */
    public function anniversaire(){
        $this->logged_in();

        $output = "";   
        /**matricule de l'entreprise */
        $amtricule_en = session('users')['matricule'];

        /**liste des employés de l'entreprise connecté */
        $employes = $this->notification->get_employe_informations($amtricule_en);

        /**liste des clients de l'entreprise connecté */
        $clients = $this->costomer->all_client($amtricule_en);
        
        /**decomposition de la date du jour */
        $date = date('Y-m-d');
        $jour_actuel = date('d', strtotime($date));
        $mois_actuel = date('m', strtotime($date));
        $annee_actuel = date('Y', strtotime($date));

        if(!empty($employes)){
            /*********************************ANNIVERSAIRE D'UN EMPLOYER DEBUT ********************* */
            foreach ($employes as $key => $value) {

                $jourdb = date('d', strtotime($value['date_naiss_emp']));
                $moisdb = date('m', strtotime($value['date_naiss_emp']));
                $anneedb = date('Y', strtotime($value['date_naiss_emp']));

                if(!empty($value['date_naiss_emp']) && $value['date_naiss_emp'] !="--"){
                    /**on veut savoir si l'anniv d'un employer est ce mois*/
                    if($moisdb == $mois_actuel){
    
                        /**on veut savoir si l'anniv d'un employer est le jour encour*/
                        if($jourdb == $jour_actuel){
                            $message = $value['nom_emp'].' fête son anniversaire aujourd\'hui';
                            $matricul_emp = $value['matricule_emp'];
                            /**verifie si la notification d'anniversaire est deja dans la bd pour eviter d'inserer plusieurs fois*/
                            $verify =  $this->notification->verify_notification($mois_actuel,$matricul_emp);
                            if(!empty($verify)){
                                $input = array(
                                    'message_notification' => $message,
                                    'date_mis_a_jour' => dates()
                                );
                                $query =  $this->notification->update_notification($matricul_emp, $input);
                                if(!$query){
                                    $output .= '<span class="text-danger text-uppercase font-weight-bold"><i class="fa fa-calendar-times"></i> erreur survenu contactez l\'administrateur</span>';
                                }
                            }else{
                                $input = array(
                                    'code_notification' => code(5),
                                    'type_notification' =>  'HBD',
                                    'message_notification' => $message,
                                    'etat_notification' => 1, 
                                    'matricule_concerner' => $matricul_emp,
                                    'entreprise' => session('users')['matricule'],
                                    'date_notification' => dates()
                                );
                                $query =  $this->notification->save_notification($input);
                                if(!$query){
                                    $output .= '<span class="text-danger text-uppercase font-weight-bold"><i class="fa fa-calendar-times"></i> erreur survenu contactez l\'administrateur</span>';
                                }
                            }
                        }
    
                        /**on veut savoir si l'anniv d'un employer est déja passer */
                        if($jourdb < $jour_actuel){
                            $message = 'L\'anniversaire de '.$value['nom_emp'].' est passé il y\'a '.abs($jour_actuel - $jourdb).' jour(s)</br>';
                            $matricul_emp = $value['matricule_emp'];
                            /**verifie si la notification d'anniversaire est deja dans la bd pour eviter d'inserer plusieurs fois*/
                            $verify =  $this->notification->verify_notification($mois_actuel,$matricul_emp);
                            if(!empty($verify)){
                                $input = array(
                                    'message_notification' => $message,
                                    'date_mis_a_jour' => dates()
                                );
                                $query =  $this->notification->update_notification($matricul_emp, $input);
                                if(!$query){
                                    $output .= '<span class="text-danger text-uppercase font-weight-bold"><i class="fa fa-calendar-times"></i> erreur survenu contactez l\'administrateur</span>';
                                }
                            }else{
                                $input = array(
                                    'code_notification' => code(5),
                                    'type_notification' =>  'HBD',
                                    'message_notification' => $message,
                                    'etat_notification' => 1, 
                                    'matricule_concerner' => $matricul_emp,
                                    'entreprise' => session('users')['matricule'],
                                    'date_notification' => dates()
                                );
                                $query =  $this->notification->save_notification($input);
                                if(!$query){
                                    $output .= '<span class="text-danger text-uppercase font-weight-bold"><i class="fa fa-calendar-times"></i> erreur survenu contactez l\'administrateur</span>';
                                }
                            }
    
                        }
    
                        /**on veut savoir si l'anniv d'un employé n'est pas encore passé */
                        if($jourdb > $jour_actuel){
                            $message = 'L\'anniversaire de '.$value['nom_emp'].' est dans '.abs($jourdb - $jour_actuel).' jour(s)</br>';
                            $matricul_emp = $value['matricule_emp'];
                            /**verifie si la notification d'anniversaire est deja dans la bd pour eviter d'inserer plusieurs fois*/
                            $verify =  $this->notification->verify_notification($mois_actuel,$matricul_emp);
                            if(!empty($verify)){
                                $input = array(
                                    'message_notification' => $message,
                                    'date_mis_a_jour' => dates()
                                );
                                $query =  $this->notification->update_notification($matricul_emp, $input);
                                if(!$query){
                                    $output .= '<span class="text-danger text-uppercase font-weight-bold"><i class="fa fa-calendar-times"></i> erreur survenu contactez l\'administrateur</span>';
                                }
                            }else{
                                $input = array(
                                    'code_notification' => code(5),
                                    'type_notification' =>  'HBD',
                                    'message_notification' => $message,
                                    'etat_notification' => 1, 
                                    'matricule_concerner' => $matricul_emp,
                                    'entreprise' => session('users')['matricule'],
                                    'date_notification' => dates()
                                );
                                $query =  $this->notification->save_notification($input);
                                if(!$query){
                                    $output .= '<span class="text-danger text-uppercase font-weight-bold"><i class="fa fa-calendar-times"></i> erreur survenu contactez l\'administrateur</span>';
                                }
                            }
                        }
                    }
                }
            }
            /*********************************ANNIVERSAIRE D'UN EMPLOYER FIN ********************* */
        }

        if(!empty($clients)){
            /*********************************ANNIVERSAIRE D'UN CLIENT DEBUT ********************* */
            foreach ($clients as $key => $value) {
                if(!empty($value['date_naiss']) && $value['date_naiss'] !="--"){
                  
                    $jourdb = date('d', strtotime($value['date_naiss']));
                    $moisdb = date('m', strtotime($value['date_naiss']));
                    $anneedb = date('Y', strtotime($value['date_naiss']));
    
                    /**on veut savoir si l'anniv d'un employer est ce mois*/
                    if($moisdb == $mois_actuel){
    
                        /**on veut savoir si l'anniv d'un employer est le jour encour*/
                        if($jourdb == $jour_actuel){
                            $message = $value['nom_cli'].' fête son anniversaire aujourd\'hui';
                            $matricul_cli = $value['matricule_cli'];
                            /**verifie si la notification d'anniversaire est deja dans la bd pour eviter d'inserer plusieurs fois*/
                            $verify =  $this->notification->verify_notification($mois_actuel,$matricul_cli);
                            if(!empty($verify)){
                                $input = array(
                                    'message_notification' => $message,
                                    'date_mis_a_jour' => dates()
                                );
                                $query =  $this->notification->update_notification($matricul_cli, $input);
                                if(!$query){
                                    $output .= '<span class="text-danger text-uppercase font-weight-bold"><i class="fa fa-calendar-times"></i> erreur survenu contactez l\'administrateur</span>';
                                }
                            }else{
                                $input = array(
                                    'code_notification' => code(5),
                                    'type_notification' =>  'HBD',
                                    'message_notification' => $message,
                                    'etat_notification' => 1, 
                                    'matricule_concerner' => $matricul_cli,
                                    'entreprise' => session('users')['matricule'],
                                    'date_notification' => dates()
                                );
                                $query =  $this->notification->save_notification($input);
                                if(!$query){
                                    $output .= '<span class="text-danger text-uppercase font-weight-bold"><i class="fa fa-calendar-times"></i> erreur survenu contactez l\'administrateur</span>';
                                }
                            }
                        }
    
                        /**on veut savoir si l'anniv d'un employer est déja passer */
                        if($jourdb < $jour_actuel){
                            $message = 'L\'anniversaire de '.$value['nom_cli'].' est passé il y\'a '.abs($jour_actuel - $jourdb).' jour(s)</br>';
                            $matricule_cli = $value['matricule_cli'];
                            /**verifie si la notification d'anniversaire est deja dans la bd pour eviter d'inserer plusieurs fois*/
                            $verify =  $this->notification->verify_notification($mois_actuel,$matricule_cli);
                            if(!empty($verify)){
                                $input = array(
                                    'message_notification' => $message,
                                    'date_mis_a_jour' => dates()
                                );
                                $query =  $this->notification->update_notification($matricule_cli, $input);
                                if(!$query){
                                    $output .= '<span class="text-danger text-uppercase font-weight-bold"><i class="fa fa-calendar-times"></i> erreur survenu contactez l\'administrateur</span>';
                                }
                            }else{
                                $input = array(
                                    'code_notification' => code(5),
                                    'type_notification' =>  'HBD',
                                    'message_notification' => $message,
                                    'etat_notification' => 1, 
                                    'matricule_concerner' => $matricule_cli,
                                    'entreprise' => session('users')['matricule'],
                                    'date_notification' => dates()
                                );
                                $query =  $this->notification->save_notification($input);
                                if(!$query){
                                    $output .= '<span class="text-danger text-uppercase font-weight-bold"><i class="fa fa-calendar-times"></i> erreur survenu contactez l\'administrateur</span>';
                                }
                            }
    
                        }
    
                        /**on veut savoir si l'anniv d'un employé n'est pas encore passé */
                        if($jourdb > $jour_actuel){
                            $message = 'L\'anniversaire de '.$value['nom_cli'].' est dans '.abs($jourdb - $jour_actuel).' jour(s)</br>';
                            $matricule_cli = $value['matricule_cli'];
                            /**verifie si la notification d'anniversaire est deja dans la bd pour eviter d'inserer plusieurs fois*/
                            $verify =  $this->notification->verify_notification($mois_actuel,$matricule_cli);
                            if(!empty($verify)){
                                $input = array(
                                    'message_notification' => $message,
                                    'date_mis_a_jour' => dates()
                                );
                                $query =  $this->notification->update_notification($matricule_cli, $input);
                                if(!$query){
                                    $output .= '<span class="text-danger text-uppercase font-weight-bold"><i class="fa fa-calendar-times"></i> erreur survenu contactez l\'administrateur</span>';
                                }
                            }else{
                                $input = array(
                                    'code_notification' => code(5),
                                    'type_notification' =>  'HBD',
                                    'message_notification' => $message,
                                    'etat_notification' => 1, 
                                    'matricule_concerner' => $matricule_cli,
                                    'entreprise' => session('users')['matricule'],
                                    'date_notification' => dates()
                                );
                                $query =  $this->notification->save_notification($input);
                                if(!$query){
                                    $output .= '<span class="text-danger text-uppercase font-weight-bold"><i class="fa fa-calendar-times"></i> erreur survenu contactez l\'administrateur</span>';
                                }
                            }
                        }
                    }
                
                }
            }
            /*********************************ANNIVERSAIRE D'UN CLIENT FIN ********************* */
        }
        echo json_encode($output);
    }


    /**compte et affiche le nombre de notification non lu */
    public function countnotification(){
        $this->logged_in();
        $output = ""; 

        $amtricule_en = session('users')['matricule'];
        $query =  $this->notification->count_notification($amtricule_en);
        if($query){
            $output .= '<span class="kt-badge kt-badge--danger">'.$query.'</span>';
        }
        echo json_encode($output);
    }

    /**affiche les messages d'anniversaire */
    public function hbd_notification(){
        $this->logged_in();
        $output = ""; 
        $matricule_en = session('users')['matricule'];

        /**decomposition de la date du jour */
        $date = date('Y-m-d');
        $jour_actuel = date('d', strtotime($date));
        $mois_actuel = date('m', strtotime($date));
        $annee_actuel = date('Y', strtotime($date));

        $query =  $this->notification->hbd_notification($matricule_en);
        if($query){
            foreach ($query as $key => $value){
                $moisdb = date('m', strtotime($value['date_notification']));
                if($moisdb == $mois_actuel){

                    if($value['etat_notification'] == 1){
                        $output .= '
                            <a id="'.$value['code_notification'].'" class="kt-notification-v2__item detail">
                                    <div class="kt-notification-v2__item-icon">
                                        <i class="fa fa-birthday-cake"></i>
                                    </div>
                                    <div class="kt-notification-v2__item-wrapper">
                                        <div class="kt-notification-v2__item-title text-primary">
                                            '.$value['message_notification'].'
                                        </div>
                                        <div class="kt-notification-v2__item-desc">
                                            '.$value['date_notification'].'
                                        </div>
                                    </div>	
                                </a>
                            ';
                    }else if($value['etat_notification'] == 0){
                        $output .= '
                        <a id="'.$value['code_notification'].'" class="kt-notification-v2__item detail">
                                <div class="kt-notification-v2__item-icon">
                                    <i class="fa fa-birthday-cake"></i>
                                </div>
                                <div class="kt-notification-v2__item-wrapper">
                                    <div class="kt-notification-v2__item-title">
                                        '.$value['message_notification'].'
                                    </div>
                                    <div class="kt-notification-v2__item-desc">
                                        '.$value['date_notification'].'
                                    </div>
                                </div>	
                            </a>
                        ';
                    }
                }
                
            }
        }else{
            $output .= '<span>aucun anniversaire ce mois</span>'; 
        }
        echo json_encode($output);  
    }

    /**afficher les details d'une notification */
    public function detail_notification(){
        $this->logged_in();
        $output = "";
        $codenotif = $this->input->post('codenotif');
        $query =  $this->notification->get_single_notification($codenotif);
        if($query){
            /**quand on clique, modifie l'etat a "0" pour dire que c'est déjà lu */
            $input = array(
                'etat_notification' => 0
            );
            $code = $query['code_notification'];
            $requette = $this->notification->update_etat_notification($code,$input);
            if($requette){
                if($query['type_notification'] == "HBD"){
                    $output = '<h4>'.$query['message_notification'].'</h4><hr>
                        <div class="form-group form-group-last">
                            <label for="exampleTextarea">Dites-lui quelques mots</label>
                            <textarea class="form-control" rows="3"></textarea>
                            <br>
                            <button type="button" class="btn btn-primary">Envoyer</button>
                        </div>
                    ';
                }else{
                    $output .= $query['message_notification'];
                }
            }else{
                $output .= "erreur survenu! contactez l'administrateur";
            } 
        }else{
            $output .= "erreur survenu! contactez l'administrateur";
        }

        echo json_encode($output);
    }

    /**affiche toutes les notifications lu et non lu */
    public function notification(){
        $this->logged_in();
        $output = "";
        $matricule_en = session('users')['matricule'];

        /**decomposition de la date du jour */
        $date = date('Y-m-d');
        $jour_actuel = date('d', strtotime($date));
        $mois_actuel = date('m', strtotime($date));
        $annee_actuel = date('Y', strtotime($date));

        $date_util = date('-m-Y', strtotime($date));

        $query = $this->notification->notifications($matricule_en);
        if($query){

           $output .= '
            <div class="list-group col-xl-4 col-lg-12">
                <span class="list-group-item list-group-item-action active">
                    CE MOIS '.$date_util.'
                </span>';

            foreach ($query as $key => $value) {
                $moisdb = date('m', strtotime($value['date_notification']));
                if($moisdb == $mois_actuel){
                    if($value['etat_notification'] == 1){
                        $output .= '
                            <a id="'.$value['code_notification'].'" class="list-group-item list-group-item-action detail text-primary"><b>'.$value['message_notification'].'('.$value['date_notification'].')</b></a>
                        ';
                    }else if($value['etat_notification'] == 0){
                        $output .= '
                            <a id="'.$value['code_notification'].'" class="list-group-item list-group-item-action detail">'.$value['message_notification'].'('.$value['date_notification'].')</a>
                        ';
                    }
                }
            }
            $output .= '
                </div>
            ';


            $output .= '
            <div class="list-group col-xl-4 col-lg-12">
                <span class="list-group-item list-group-item-action active">
                    MOIS PASSE(S)
                </span>';
                foreach ($query as $key => $value) {
                    $moisdb = date('m', strtotime($value['date_notification']));
                    $anneedb = date('Y', strtotime($value['date_notification']));
                    if($moisdb !=12 && $moisdb == ($mois_actuel - 1)){
                        if($value['etat_notification'] == 1){
                            $output .= '
                                <a id="'.$value['code_notification'].'" class="list-group-item list-group-item-action detail text-primary"><b>'.$value['message_notification'].'('.$value['date_notification'].')</b></a>
                            ';
                        }else if($value['etat_notification'] == 0){
                            $output .= '
                                <a id="'.$value['code_notification'].'" class="list-group-item list-group-item-action detail">'.$value['message_notification'].'('.$value['date_notification'].')</a>
                            ';
                        }
                    }else if($moisdb == 12 && $moisdb == ($moisdb + ($mois_actuel - $mois_actuel))){
                        if($value['etat_notification'] == 1){
                            $output .= '
                                <a id="'.$value['code_notification'].'" class="list-group-item list-group-item-action detail text-primary"><b>'.$value['message_notification'].'('.$value['date_notification'].')</b></a>
                            ';
                        }else if($value['etat_notification'] == 0){
                            $output .= '
                                <a id="'.$value['code_notification'].'" class="list-group-item list-group-item-action detail">'.$value['message_notification'].'('.$value['date_notification'].')</a>
                            ';
                        }
                    }
                }
            $output .= '
                </div>
            ';

        }else{
            $output = "aucune notification pour le moment";
        }

        echo json_encode($output);       
    }

		

	



}