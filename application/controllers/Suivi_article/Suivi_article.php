<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suivi_article extends CI_Controller {

    /**constructeur */
    public function __construct(){
        parent::__construct();
		$this->load->model('Stock/Stock_model','stock');
		$this->load->model('Suivi/Suivi_model','suivi');
		$this->load->model('Commerce/Commerce_model','commerce');
		date_default_timezone_set("Africa/Douala");
    }

    /**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}



    /**afficher la page de suivi d'article dans le stock */
	public function index(){
		$this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

        /**matricule de l'entreprise */
		$matricule = session('users')['matricule'];
		
        /**affiche la liste des articles */
		$data['articles'] = $this->stock->get_all_article($matricule);

		$this->load->view('suivi-article/suivi-article',$data);

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}
	
	/**afficher la page de suivi d'article dans la vente */
	public function viewpagevente(){
		$this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

        /**matricule de l'entreprise */
		$matricule = session('users')['matricule'];
		
        /**affiche la liste des articles */
		$data['articles'] = $this->stock->get_all_article($matricule);

		$this->load->view('suivi-article/suivi-article-vente',$data);

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}


    /***afficher l'historique des opérations sur un article en fonction de la période dans le stock*/
    public function story_suiviarticle_stock(){
        $this->logged_in();

        /**validation du formulaire */
        $this->form_validation->set_rules('article', 'article', 'required|regex_match[/^[a-zA-Z0-9]+$/]',array(
			'required' => 'l\' %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('start', 'periode', 'required',array(
			'required' => 'choisi la %s',
		));

        $this->form_validation->set_rules('end', 'periode', 'required',array(
			'required' => 'choisi la %s',
		));

        if ($this->form_validation->run()){
            
            $article = $this->input->post('article');
            $date1 = $this->input->post('start');
            $date2 = $this->input->post('end');
            $matricule = session('users')['matricule'];


            /**je converti la date au format Y-m-d*/
            $debut = date("Y-m-d", strtotime($date1));
            $fin = date("Y-m-d", strtotime($date2));
            
            $output = "";
            $outputs = "";

            
            /***historique d'un article en fonction sur une période */
            $arthist = $this->suivi->storystockart($article,$matricule,$debut,$fin);
            if(!empty($arthist)){
                
                $compteur = 0;
                foreach ($arthist as $key => $value){
                    /**information sur l'article avec le code de l'article et celui de l'entreprise */
                    $art_info = $this->suivi->singleinfoarticle($matricule,$value['code_article']);

                    /**je selectionne maintenant les informations sur l'eployé qui a effectué l'opération en fonction de son code et celui de l'entreprise */
                    $emp_info = $this->suivi->singleinfoemp($matricule,$value['code_emp_art_doc']);
                   
                   
                    /**je selectionne le document et son type de document en fonction de l'entreprise et son code */
                    $docandtyp_info = $this->suivi->singleinfodocandtype($matricule,$value['code_document']);
                    
                    
                    
                    /**en fonction du type de document trouvé, on selectionne le dépot correspondant, sourtout pour les transfert*/
                    $depotin = !empty($docandtyp_info['depot_init_doc'])?$this->suivi->singleinfodepot($matricule,$docandtyp_info['depot_init_doc']):'';
                    $depotini = !empty($depotin)?$depotin['nom_depot']:'';
                    
                    $depotrec = !empty($docandtyp_info['depot_recept_doc'])?$this->suivi->singleinfodepot($matricule,$docandtyp_info['depot_recept_doc']):'';
                    $depotrecep = !empty($depotrec)?$depotrec['nom_depot']:'';
                    
                    $dep = !empty($docandtyp_info['depot_doc'])?$this->suivi->singleinfodepot($matricule,$docandtyp_info['depot_doc']):'';
                    $depot = !empty($dep)?$dep['nom_depot']:'';
                    
                    
                    
                    if($docandtyp_info['categorie_doc'] == "stock"){
                        $compteur+=1;
                        $output .='
                            <tr>
                                <td>'.$compteur.' ( '.$docandtyp_info['abrev_doc'].')</td>
                                <td>'.$value['code_document'].'</td>
                                <td>'.$art_info['designation'].'</td>
                                <td>'.$value['qte_avant_inventaire'].'</td>
                                <td>'.$value['quantite'].'</td>
                                <td>'.$depot.'</td>
                                <td>'.$depotini.'</td>
                                <td>'.$depotrecep.'</td>
                                <td>'.$value['quantite_plus'].'</td>
                                <td>'.$value['quantite_moins'].'</td>
                                <td>'.dateformat($value['date_creer_art_doc']).'</td>
                                <td>'.dateformat($value['date_modifier_art_doc']).'</td>
                                <td>'.$emp_info['nom_emp'].'</td>
                            </tr>
                        ';
                    }   
                    
                }
                
                $array = array(
                    "success" => $output,
                );
            }else{
                $array = array(
                    "success" => '<tr><td colspan="13"><b class="text-danger">aucune opération pour cet article sur cette période trouvé</b></td></tr>'
                );
            }
        }else{
            $array = array(
                'error'   => true,
                'article_error' => form_error('article'),
                'periode_error' => form_error('start'),
                'periode_error' => form_error('end'),
            );
        }

        echo json_encode($array);
    }
    
    
    /***afficher l'historique des opérations sur un article en fonction de la période dans la vente*/
    public function story_suiviarticle_vente(){
        $this->logged_in();

        /**validation du formulaire */
        $this->form_validation->set_rules('article', 'article', 'required|regex_match[/^[a-zA-Z0-9]+$/]',array(
			'required' => 'l\' %s est nécessaire',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('start', 'periode', 'required',array(
			'required' => 'choisi la %s',
		));

        $this->form_validation->set_rules('end', 'periode', 'required',array(
			'required' => 'choisi la %s',
		));

        if ($this->form_validation->run()){
            
            $article = $this->input->post('article');
            $date1 = $this->input->post('start');
            $date2 = $this->input->post('end');
            $matricule = session('users')['matricule'];


            /**je converti la date au format Y-m-d*/
            $debut = date("Y-m-d", strtotime($date1));
            $fin = date("Y-m-d", strtotime($date2));
            
            $output = "";
            $outputs = "";

            
            /***historique d'un article en fonction sur une période */
            $arthist = $this->suivi->storystockart($article,$matricule,$debut,$fin);
            if(!empty($arthist)){
                
                $compteur = 0;
                foreach ($arthist as $key => $value){
                    /**information sur l'article avec le code de l'article et celui de l'entreprise */
                    $art_info = $this->suivi->singleinfoarticle($matricule,$value['code_article']);

                    /**je selectionne maintenant les informations sur l'eployé qui a effectué l'opération en fonction de son code et celui de l'entreprise */
                    $emp_info = $this->suivi->singleinfoemp($matricule,$value['code_emp_art_doc']);
                   
                   
                    /**je selectionne le document et son type de document en fonction de l'entreprise et son code */
                    $docandtyp_info = $this->suivi->singleinfodocandtype($matricule,$value['code_document']);
                    
                    
                    
                    /**en fonction du type de document trouvé, on selectionne le dépot correspondant, sourtout pour les transfert*/
                    $depotin = !empty($docandtyp_info['depot_init_doc'])?$this->suivi->singleinfodepot($matricule,$docandtyp_info['depot_init_doc']):'';
                    $depotini = !empty($depotin)?$depotin['nom_depot']:'';
                    
                    $depotrec = !empty($docandtyp_info['depot_recept_doc'])?$this->suivi->singleinfodepot($matricule,$docandtyp_info['depot_recept_doc']):'';
                    $depotrecep = !empty($depotrec)?$depotrec['nom_depot']:'';
                    
                    $dep = !empty($docandtyp_info['depot_doc'])?$this->suivi->singleinfodepot($matricule,$docandtyp_info['depot_doc']):'';
                    $depot = !empty($dep)?$dep['nom_depot']:'';
                    
                    
                    
                    if($docandtyp_info['categorie_doc'] == "vente"){
                        $compteur +=1;
                        $outputs .='
                            <tr>
                                <td>'.$compteur.' ('.$docandtyp_info['abrev_doc'].')</td>
                                <td>'.$value['code_document'].'</td>
                                <td>'.$art_info['designation'].'</td>
                                <td>'.$value['quantite'].'</td>
                                <td>'.$value['pu_HT'].'</td>
                                <td>'.$value['pt_HT'].'</td>
                                <td>'.$depot.'</td>
                                <td>'.dateformat($value['date_creer_art_doc']).'</td>
                                <td>'.dateformat($value['date_modifier_art_doc']).'</td>
                                <td>'.$emp_info['nom_emp'].'</td>
                            </tr>
                        ';
                    }   
                    
                }
                
                $array = array(
                    "success" => $outputs,
                );
            }else{
                $array = array(
                    "success" => '<tr><td colspan="13"><b class="text-danger">aucune opération pour cet article sur cette période trouvé</b></td></tr>'
                );
            }
        }else{
            $array = array(
                'error'   => true,
                'article_error' => form_error('article'),
                'periode_error' => form_error('start'),
                'periode_error' => form_error('end'),
            );
        }

        echo json_encode($array);
    }

}