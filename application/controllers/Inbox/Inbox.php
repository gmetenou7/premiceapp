<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbox extends CI_Controller
{
    /**constructeur*/
	public function __construct(){
        parent::__construct();
        $this->load->model('Stock/Stock_model','stock');
        $this->load->model('Commerce/Commerce_model','commerce');
        $this->load->model('Document/Document_model','document');
        $this->load->model('Costomers/Costomer_model','costomer');
        $this->load->model('Inbox/Inbox_model','inbox');

        $this->load->config('email');
    }

    /**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}

    /**page inbox */
    public function index(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

        /**informations utile */
        $matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];

        /**affiche la liste des clients d'une entreprise */
        $data['clients'] = $this->inbox->get_all_client($matricule);

        $this->load->view('inbox/inbox',$data);

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }


    /**envoyer un groupe de message debut */
    public function send_groupe_message(){
        $this->logged_in();


        $destinataire =  $this->input->post('destinataire');

		$this->form_validation->set_rules('newobjet', 'objet', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'required'      => 'saisi l\' %s.',
                'regex_match'     => 'caractère(s) non autorisé!'
            )
        );

        $this->form_validation->set_rules('newmessgroupe', 'message', 'required',
            array(
                'required'      => 'saisi le %s à envoyer', 
            )
        );

		if($this->form_validation->run()){

            $matricule_en = session('users')['matricule'];
            $matricule_emp = session('users')['matricule_emp'];

            $objet = $this->input->post('newobjet');
            $message = $this->input->post('newmessgroupe',TRUE);

            $code = code(5);

            $output="";
            if(!empty($destinataire)){

                /**enregistrer le message dans la base des données */
                $inputmsg = array(
                    'code_message' => $code,
                    'objet_message' => $objet,
                    'contenu_message' => $message,
                    'creer_le_message' =>dates(),
                    'code_emp_message ' => $matricule_emp,
                    'code_en_message ' => $matricule_en
                );
                $query = $this->inbox->savenewmsg($inputmsg);
                if($query){
                   

                    


                    foreach ($destinataire as $key => $value){

                        /**une fois on a les information du formulaire,
                         * 1: on selection le client en question à partir de son code
                         * : si il n'est pas dans la liste des clients, 
                         * 2: on le cherche dans la liste des employés
                         * : en cas d'abscence, la
                         * 3: on considère que la personne cherché n'existe pas
                        */
    
                        $querys = $this->inbox->getsingleuserinformation($value,$matricule_en);
                        if(!empty($querys)){
    
                            $telephone = explode(",", $querys['telephone_cli']);
                            //strlen();
                            $tel1 = $telephone[0];
                            $tel2 = $telephone[1];
                            $email = $querys['email_cli'];
                            $nomcli = $querys['nom_cli'];
                            $codecli = $querys['matricule_cli'];
                            
                            if(!empty($email)){

                                $inputstorymsg = array(
                                    'code_msg' => $code,
                                    'code_cli' => $codecli,
                                    'creer_le_recevoir' => dates()
                                );
                                /**save historique de message */
                                $query = $this->inbox->historiquedesmsg($inputstorymsg);
                                if($query){
                                   
                                    $to =  $email;  // User email pass here
                                    $subject = $objet;
                                    $from = $this->config->item('smtp_user');
                                    $emailContent = $message;

                                    $result = $this->sendmail(session('users')['nom'],$nomcli,$from,$to,$subject,$emailContent);
                                    if($result){
                                        $array = array(
                                            'success' => '<b class="text-danger">message envoyé</b>'
                                        );
                                    }else{
                                        $array = array(
                                            'success' => '<b class="text-danger">message non envoyé ERREUR:</b>'
                                        );
                                    }
                                }else{
                                    $array = array(
                                        'success' => '<b class="text-danger">message non enrégistrer en base des données</b>'
                                    );
                                }
                            }
    
                        }
                    }

                }else{
                    $array = array(
                        'success'   => '<div class="alert alert-danger fade show" role="alert">
                            <div class="alert-icon"><i class="fa fa-angry"></i></div>
                            <div class="alert-text">Erreur survenu, actuliser puis reéssayer</div>
                            <div class="alert-close">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="la la-close"></i></span>
                                </button>
                            </div>
                        </div>',
                    );
                }

            }else{
                $array = array(
                    'success'   => '<div class="alert alert-danger fade show" role="alert">
                        <div class="alert-icon"><i class="fa fa-angry"></i></div>
                        <div class="alert-text">choisi un destinataire</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-close"></i></span>
                            </button>
                        </div>
                    </div>',
                );
            }
            
        }else{
			$array = array(
				'error'   => true,
				'destinataire_error' => form_error('destinataire'),
				'newobjet_error' => form_error('newobjet'),
				'newmessgroupe_error' => form_error('newmessgroupe')
			);
		}
		echo json_encode($array);

    }
    /**envoyer un groupe de message fin */


    /**liste des messages envoyé */
    public function all_send_message(){
        $this->logged_in();

        /**informations utile */
        $matricule = session('users')['matricule'];

        /**
         * 1: liste des messages en fonction de l'entreprise
         * 2: la personne qui à envoyé
         * 3: une personne sur la liste des personnes qui ont recu au hazard
         * 4: le nombre de personne à qui on envoyé
         * 5: la pagination
        */


        /**************pagination debut************************* */
			$config = array();
			$config["base_url"] = "#";
			$config["total_rows"] = $this->inbox->count_all_message($matricule);
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


         
        /**on selectionne tous les messages d'une entreprise */
        $listofmsg = $this->inbox->selectallsendmessages($matricule,$config["per_page"], $page);
        $output = "";
        if(!empty($listofmsg)){
            
            foreach ($listofmsg as $key => $value) {
                /**information sur un employé en fonction de son code et de l'entreprise*/
                $sender = $this->inbox->sendermsg($matricule,$value['code_emp_message']);

                /**on selectionne les personnes qui ont recu le message et on prend au hazard 1 personne en fonction du code de message */
                $personn = $this->inbox->allreceivermessage($value['code_message']);
                $count = 0;
                if(!empty($personn)){
                    foreach ($personn as $key => $values){
                        $person = $values['nom_cli'];
                        $count+=1;
                    }
                    $output .='
                        <span class="list-group-item list-group-item-action flex-column align-items-start detail_msg" id="'.$value['code_message'].'">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">'.$value['objet_message'].'</h5>
                                <small class="text-muted">'.date('d-m-y H:i:s',strtotime($value['creer_le_message'])).'</small>
                            </div>
                            <p class="mb-1">'.$value['contenu_message'].'</p>
                            <small class="text-muted">envoyer par: '.$sender['nom_emp'].' à '.$person.' et '.($count - 1).' autre(s) personne(s) </small>
                        </span>
                    ';
                    
                }else{
                    $output .='<b class="text-danger">Erreur survenu</b>';
                }

                $array = array(
                    'success'=> $output,
                    'pagination'=> $this->pagination->create_links()
                );
            }
        }else{
            $array = array(
                'success'=>'<b class="text-danger">Aucun message envoyé pour le moment</b>'
            ); 
        }
        echo json_encode($array); //$array
    }

    /** afficher les details d'un message */
    public function infos_send_message(){
        $this->logged_in();
        /**informations utile */
        $matricule = session('users')['matricule'];
        $codemsg = $this->input->post('id');
        $output ="";
        if(!empty($codemsg)){

            /**1: on selectionne le message en question 
             * 2: on selectionne l'envoyeur du message
             * 3: on compte le nombre de personnes ayant recu le message
             * 4: on liste les personnes qui l'on recu
            */

            /**liste des messages en fonction du code du message et de l'entreprise */
            $messagess  = $this->inbox->getsignlemessage($matricule,$codemsg);
            if(!empty($messagess)){

                /**information sur un employé en fonction de son code et de l'entreprise*/
                $sender = $this->inbox->sendermsg($matricule,$messagess['code_emp_message']);

                 /**on selectionne les personnes qui ont recu le message*/
                $personns = $this->inbox->allreceivermessage($messagess['code_message']);
                if(!empty($personns)){
                    $output .='
                        <h5 class="card-title">'.$messagess['objet_message'].'</h5>
                        <small class="text-muted">le '.date('d-m-y H:i:s',strtotime($messagess['creer_le_message'])).'</small>
                        <small class="text-muted"> | par: '.$sender['nom_emp'].'</small>
                        <p class="card-text">'.$messagess['contenu_message'].'</p>
                        <div class="kt-separator kt-separator--space-md kt-separator--border-dashed"></div>
                    ';
                    $counts = 0;
                    foreach ($personns as $key => $values){
                        $output .='<small class="text-muted">'.$values['nom_cli'].'</small> . ';
                        $counts+=1;
                    }
                    $output .='<small class="text-muted"> | Total: '.$counts.' personne(s)</small> . ';
                    
                }else{
                    $output .='<b class="text-danger">Erreur survenu</b>';
                }
            }else{
                $output .='<b class="text-danger">le système ne retrouve pas ce message actualisez puis reéssayé</b>';
            }
        }else{
            $output .='<b class="text-danger">le système ne retrouve pas ce message</b>';
        }

        $array = array(
            'infos' => $output
        );

        echo json_encode($array); //$array
    }

    /**afficher la page de config message */
    public function msg_config(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

        $this->load->view('inbox/config_msg');

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }

    /**enregistrer le nombre de message limite pour une entreprise */
    public function nbr_msg_config(){
        
        $this->logged_in();

        $this->form_validation->set_rules('nbr_msg', 'Nombre de message', 'required|regex_match[/^[0-9]+$/]',
            array(
                'required'      => 'saisi le %s', 
                'regex_match'     => 'seul les chiffres et nombres entier autorisé'
            )
        );

        if($this->form_validation->run()){
            /**informations utile */
            $matricule_en = session('users')['matricule'];
            $matricule_emp = session('users')['matricule_emp'];

            $nbr_msg = trim($this->input->post('nbr_msg'));

            $inputval = array(
                'nbr_msg_config' => $nbr_msg,
                'en_config_msg' => $matricule_en,
                'emp_config_msg' => $matricule_emp,
                'date_creation_config_msg' => dates()
            );

            $issaving = $this->inbox->savenbrmsg($inputval);
            if($issaving){
                $array = array(
                    'success' => '<span class="text-success"><i class="flaticon2-checkmark"></i></span> Nombre de message enrégistrer avec succès'
                );
            }else{
                $array = array(
                    'success' => '<span class="text-danger"><i class="fa fa-angry"></i></span> erreur survenu. actualiser puis reéssayez'
                );
            }
        }else{
			$array = array(
				'error'   => true,
				'nbr_msg_error' => form_error('nbr_msg'),
			);
		}
        echo json_encode($array);
    }


    /**affiche tous les messages de configuration en fonction de l'entreprise */
    public function all_config_msg(){
        $this->logged_in();
        $output ="";
        $matricule_en = session('users')['matricule'];
        $infos = $this->inbox->selectconfigsmsg($matricule_en);
        if(!empty($infos)){
            foreach ($infos as $key => $value) {
                $output .='
                    <tr>
                        <td>'.strtoupper($value['nom_en']).'</td>
                        <td>'.$value['nbr_msg_config'].'</td>
                        <td>'.strtoupper($value['nom_emp']).'</td>
                        <td>'.dateformat($value['date_creation_config_msg']).'</td>
                        <td>
                            <i class="fa fa-angry text-danger deletenbrmsg" id="'.$value['id_msg_config'].'"></i>
                        </td>
                    </tr>';
                $array = array(
                    'success'=>$output
                );
            }
        }else{
            $array = array(
                'success'=>'<tr><td colspan="5">aucune config pour le nombre de message</td></tr>'
            );
        }

        echo json_encode($array);
    }

    /**supprimer un message configurer */
    public function delete_config_msg(){
        $this->logged_in();
        $matricule_en = session('users')['matricule'];
        $id = trim($this->input->post('id'));
        $query = $this->inbox->deletenbrmsg($id,$matricule_en);
        if($query){
            $array = array(
                'success'=>'<span class="text-success"><i class="flaticon2-checkmark"></i></span> suppression éffectué avec succès'
            );
        }else{
            $array = array(
                'success'=>'<span class="text-danger"><i class="fa fa-angry"></i></span> erreur survenu lors de la suppression'
            );
        }

        echo json_encode($array);
    }



   
    

    /**fonction pour envoyer les mails */
    public function sendmail($name_en,$name_sender,$sender,$to,$subject,$emailContent){

        /**trouve la salutation en fonction de l'heur de la journée */
        $heur = date('H');
        if($heur < 12){
            $salutation = 'Bonjour';
        }else if($heur > 11 || $heur < 17){
            $salutation = 'Bon après-midi';
        }else if($heur > 16 || $heur <= 23){
            $salutation = 'Bonsoir';
        }

        if($heur < 12){
            $aurevoir = 'Bonne journée';
        }else if($heur > 11 || $heur < 17){
            $aurevoir = 'Bon après-midi';
        }else if($heur > 16 || $heur < 22){
            $aurevoir = 'Bonne soirée';
        }else if($heur > 21 || $heur <= 23){
            $aurevoir = 'Bonne nuit';
        }

        
        //$salutation = ;


        $message = '<!DOCTYPE html>
            <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width,initial-scale=1">
            <meta name="x-apple-disable-message-reformatting">
            <title></title>
            <!--[if mso]>
            <noscript>
                <xml>
                <o:OfficeDocumentSettings>
                    <o:PixelsPerInch>96</o:PixelsPerInch>
                </o:OfficeDocumentSettings>
                </xml>
            </noscript>
            <![endif]-->
            <style>
                table, td, div, h1, p {font-family: Arial, sans-serif;}
            </style>
            </head>
            <body style="margin:0;padding:0;">
            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
                <tr>
                <td align="center" style="padding:0;">
                    <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                    <tr>
                        <td align="center" style="padding:40px 0 30px 0;background:#ffffff;"><!--70bbd9-->
                        <h2>'.strtoupper($name_en).'</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 30px 42px 30px;">
                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                            <tr>
                            <td style="padding:0 0 36px 0;color:#153643;">
                                <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">
                                    '.$salutation.' '.strtoupper($name_sender).',
                                </h1>
                                <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                                    '.$emailContent.' <br><br><br>'.$aurevoir.'
                                </p>
                            </td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px;background:#ee4c50;">
                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                            <tr>
                            <td style="padding:0;width:50%;" align="left">
                                <p style="margin:0;font-size:10px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                &reg; '.$name_en.' '.date('Y').'<br/><a href="http://www.premicecomputer.com" style="color:#ffffff;text-decoration:underline;">Suivre</a><br/>
                                Whatsapp / Appel: <br/><b>Douala(+237 695-590-431),</b><br/> <b>Yaoundé(+237 697-589-885 / +237 676-118-946) </b><br/>
                                - Douala Akwa - Daoula Bercy <br/>
                                - Yaounde Face CAMAIR-CO <br/>
                                - Yaounde - Monté Anne Rouge <br/>
                                </p>
                            </td>
                            <td style="padding:0;width:50%;" align="right">
                                <table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    <td style="padding:0 0 0 10px;width:38px;">
                                        <a href="https://www.linkedin.com/company/premice-computer/" style="color:#ffffff;"><img src="https://cdn1.iconfinder.com/data/icons/logotypes/32/square-linkedin-128.png" alt="Twitter" width="38" style="height:auto;display:block;border:0;" /></a>
                                    </td>
                                    <td style="padding:0 0 0 10px;width:38px;">
                                        <a href="https://www.youtube.com/channel/UCdtzGKuEY9yupan5CLtJIEg" style="color:#ffffff;"><img src="https://cdn2.iconfinder.com/data/icons/social-media-2285/512/1_Youtube_colored_svg-128.png" alt="Twitter" width="38" style="height:auto;display:block;border:0;" /></a>
                                    </td>
                                    <td style="padding:0 0 0 10px;width:38px;">
                                        <a href="facebook.com/premicecomputer" style="color:#ffffff;"><img src="https://cdn0.iconfinder.com/data/icons/social-flat-rounded-rects/512/facebook-128.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;" /></a>
                                    </td>
                                </tr>
                                </table>
                            </td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    </table>
                </td>
                </tr>
            </table>
            </body>
            </html>';



        //$this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->from($sender);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        return $this->email->send();
    }


}