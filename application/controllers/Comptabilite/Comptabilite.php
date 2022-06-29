<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comptabilite extends CI_Controller {

    /**constructeur*/
	public function __construct(){
        parent::__construct();
        $this->load->model('Stock/Stock_model','stock');
        $this->load->model('Commerce/Commerce_model','commerce');
        $this->load->model('Document/Document_model','document');
        $this->load->model('Costomers/Costomer_model','costomer');
        $this->load->model('Users/Users_model','users');
        $this->load->model('Comptabilite/Comptabilite_model','compta');
       
    }

    /**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}


    public function index(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');
		
		$matricule_en = session('users')['matricule'];

		/*liste des employé d'une entreprise donné*/
		$data['employes'] = $this->users->get_employes($matricule_en);
        
        $this->load->view('comptabilite/venteemploye',$data);
        
        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }
    
    /**afficher le chiffre réalisé par un ou plusieur utilisateur d'une entreprise donné pour une période donnee*/
    public function comptashowusels(){
        $this->logged_in();
        
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
        
        $this->form_validation->set_rules('employe', 'commercial', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'regex_match'=>'caractère non autorisé',
            )
        );
        
        if($this->form_validation->run()){
            
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $debut = date('Y-m-d', strtotime($start));
            $fin = date('Y-m-d', strtotime($end));
            
            $mat_en = session('users')['matricule'];
            $mat_emp = $this->input->post('employe');
            $abrev = array("RT", "RC", "BR");
            $output = "";
            
            /*si on choisi l'employé*/
            if(!empty($mat_emp)){

                /**
                 * 1: on selectionne les clients de l'utilisateurs qui apparaissent aumoins une fois dans les ventes
                 * sur une periode en RT, RC, BR
                */
                $cliusers = $this->users->costomusvente_get($mat_en,$mat_emp,$abrev,$debut,$fin);
                if(!empty($cliusers)){
                    $totaldette = 0;
                    $totalcash = 0;
                    /**on selectionne le nom du commercial */
                    $employe_en = $this->users->getsingleemploye($mat_emp);
                    $output .='<tr><th colspan="3">Commercial : '.$employe_en['nom_emp'].'</th></tr>';

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
                /*si on ne choisi pas l'employé*/
            }else if(empty($mat_emp)){
                /**on selectionne tous les commerciaux en fonction du compte qui est connecter */
                $employes = $this->users->get_employes($mat_en);
                if(!empty($employes)){
                    foreach ($employes as $key => $comm) {
                        /**
                         * 1: on selectionne les clients de l'utilisateurs qui apparaissent aumoins une fois dans les ventes
                         * sur une periode en RT, RC, BR
                        */
                        $cliusers = $this->users->costomusvente_get($mat_en,$comm['matricule_emp'],$abrev,$debut,$fin);
                        if(!empty($cliusers)){
                            $totaldette = 0;
                            $totalcash = 0;
                            /**on selectionne le nom du commercial */
                            $employe_en = $this->users->getsingleemploye($comm['matricule_emp']);
                            $output .='<tr><th colspan="3">Commercial : '.$employe_en['nom_emp'].'</th></tr>';
        
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
                        }else{
                            $output .= '.';
                        }
                    }
                    $array = array(
                        'success' => $output,
                    );
                }else{
                    $array = array(
                        'success' => '<tr><td colspan="3"><b class="text-danger">aucun commercial trouvé</b></td></tr>',
                    );  
                } 
            }
        }else{
            $array = array(
                'error'   => true,
                'start_error' => form_error('start'),
                'end_error' => form_error('end'),
                'employe_error' => form_error('employe')
            );
        }
        
        
        
        
        echo json_encode($array);
    }
    
    /*determiner le chiffre généré par  les clients d'un ou de plusieurs utilisateurs sur une periode donné*/
    public function purchasecustom(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');
		
		$matricule_en = session('users')['matricule'];

        
		/**affiche la liste des clients d'une entreprise */
        $data['clients'] = $this->commerce->all_client($matricule_en);
		
		$this->load->view('comptabilite/achatclient',$data);
		
		$this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }
    
    /*chiffre générer par chaque client d'un ou de tous les users(employe ou commercial)*/
    public function custompurchasecompta(){
        $this->logged_in();
        
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
        
        $this->form_validation->set_rules('client', 'client', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',
            array(
                'regex_match'=>'caractère non autorisé',
            )
        );
        
        if($this->form_validation->run()){
            
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            
            $debut = date('Y-m-d', strtotime($start));
            $fin = date('Y-m-d', strtotime($end));
            
            $mat_en = session('users')['matricule'];
            $mat_cli = $this->input->post('client');
            $abrev = array("RT", "RC", "BR");

            $output = "";

            if(!empty($mat_cli)){

                /**pour chaque client, ayant éffectuté les achats, on selectionne ses achats en question */
                $sels = $this->users->selsuserperiod1($abrev,$mat_en,$mat_cli,$debut,$fin);
                if(!empty($sels)){

                    /**on selectionne le commercial en fonction du client pour éffectuer les opérations*/
                    $employercli = $this->users->getempcostomers($mat_cli,$mat_en);
                    $output .='<tr><th colspan="5">Commercial : '.$employercli['nom_emp'].'</th></tr>';

                    $rttotal = 0;
                    $rctotalreg = 0;
                    $rctotalnonreg = 0;
                    $brtotal = 0;

                    $venteunique = array();
                    $ventes = array();

                    foreach ($sels as $key => $valuessels){
                    
                        /**affiche la liste des articles du document encours*/
                        $artdocuvente = $this->commerce->artdocumentvente($mat_en,$valuessels['code_document']);
                        if(!empty($artdocuvente)){
                            foreach ($artdocuvente as $key => $values) {
                                $venteunique[] = $values['code_article'];
                                $ventes[] = $values;
                            }
                        }


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

                    /**CA EN CASH ET LES DETTES DU CLIENT DEBUT */
                    $cash = (($rttotal + $rctotalreg + $brtotal)==0)?0:numberformat(($rttotal + $rctotalreg + $brtotal));
                    $dette = ($rctotalnonreg==0)?0:numberformat($rctotalnonreg);
                    /**CA EN CASH ET LES DETTES DU CLIENT DEBUT */

                    $output .= '
                    <tr>
                        <td colspan="5">
                            <b>'.strtoupper($employercli['nom_cli']).'</b> |
                            <b>CASH :</b> '.$cash.' |
                            <b>DETTE :</b> '.$dette.'
                        </td>
                    </tr>';

                    /**ARTICLES LES PLUS ACHETE PAR UN CLIENT DEBUT */
                    $uniquevaluessels = array_unique($venteunique);
                    if(!empty($uniquevaluessels) && !empty($ventes)){
                        foreach ($uniquevaluessels as $keys => $valu){
                            $qtetotal = 0;
                            $designation = "";
                            $pht=0;

                            foreach ($ventes as $key => $valus){
                                $qte_t=0; $ph_t=0; $p_u=0;
                                if($valu == $valus['code_article']){
                                    if($valus['pt_HT'] < 0 ){
                                        $qte_t=(-$valus['quantite']);
                                        $p_u=$valus['pu_HT'];
                                        $ph_t=$valus['pt_HT'];
                                    }else{
                                        $qte_t=$valus['quantite'];
                                        $p_u=$valus['pu_HT'];
                                        $ph_t=$valus['pt_HT'];
                                    }

                                    $qtetotal+=$qte_t;
                                    $pht+=$ph_t;
                                    $designation = $valus['designation'];
                                }
                            }
                            
                            $qtetotal_trait = $qtetotal!=0?$qtetotal:1;
                            $pmoyen_u = ($pht / $qtetotal_trait);
                            $valuesfinal[] = array(
                                'qtetotal'=>$qtetotal,
                                'designation'=>$designation,
                                'pu_moyen'=>numberformat($pmoyen_u),
                                'pht'=>numberformat($pht),
                            );
                        }
                        arsort($valuesfinal);
                        foreach ($valuesfinal as $key => $valfinal) {
                            $output .= '<tr><td></td><td>'.$valfinal['designation'].'</td><td>'.$valfinal['qtetotal'].'</td><td>'.$valfinal['pu_moyen'].'</td><td>'.$valfinal['pht'].'</td></tr>';   
                        }
                        
                    }else{
                        $output .= '<tr><td colspan="5"><b class="text-danger">Article du client non trouvé</b></td></tr>';   
                    }
                    /**ARTICLES LES PLUS ACHETE PAR UN CLIENT DEBUT */
                    
                }else{
                    $output .= '<tr><td colspan="5"><b class="text-danger">ce client n\'a pas d\'achat sur cette période</b></td></tr>';  
                }

                
            }else if(empty($mat_cli)){
                /**ici on selectionne la liste des clients en fonction du compte connecté pour éffectuer les opérations */
                
                /**liste des clients de l'entreprise */ 
                $clients = $this->commerce->all_client($mat_en);
                if(!empty($clients)){
                    foreach ($clients as $key => $cli) {
                        /**pour chaque client, ayant éffectuté les achats, on selectionne ses achats en question */
                        $sels = $this->users->selsuserperiod1($abrev,$mat_en,$cli['matricule_cli'],$debut,$fin);
                        /**pour chaque client, ayant éffectuté les achats, on selectionne ses achats en question */
                        $sels = $this->users->selsuserperiod1($abrev,$mat_en,$cli['matricule_cli'],$debut,$fin);
                        if(!empty($sels)){
                            /**on selectionne le commercial en fonction du client pour éffectuer les opérations*/
                            $employercli = $this->users->getempcostomers($cli['matricule_cli'],$mat_en);
                            $output .='<tr><th colspan="5">Commercial : '.$employercli['nom_emp'].'</th></tr>';

                            $rttotal = 0;
                            $rctotalreg = 0;
                            $rctotalnonreg = 0;
                            $brtotal = 0;

                            $venteunique = array();
                            $ventes = array();


                            foreach ($sels as $key => $valuessels){
                                /**affiche la liste des articles du document encours*/
                                $artdocuvente = $this->commerce->artdocumentvente($mat_en,$valuessels['code_document']);
                                if(!empty($artdocuvente)){
                                    foreach ($artdocuvente as $key => $values) {
                                        $venteunique[] = $values['code_article'];
                                        $ventes[] = $values;
                                    }
                                }


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

                            /**CA EN CASH ET LES DETTES DU CLIENT DEBUT */
                            $cash = (($rttotal + $rctotalreg + $brtotal)==0)?0:numberformat(($rttotal + $rctotalreg + $brtotal));
                            $dette = ($rctotalnonreg==0)?0:numberformat($rctotalnonreg);
                            /**CA EN CASH ET LES DETTES DU CLIENT DEBUT */


                            $output .= '<tr><td colspan="5"><b>'.strtoupper($employercli['nom_cli']).'</b> | <b>CASH :</b> '.$cash.' | <b>DETTE :</b> '.$dette.'</td></tr>';


                            /**ARTICLES LES PLUS ACHETE PAR UN CLIENT DEBUT */
                            $uniquevaluessels = array_unique($venteunique);
                            if(!empty($uniquevaluessels) && !empty($ventes)){
                                
                                foreach ($uniquevaluessels as $keys => $valu){
                                    $qtetotal = 0;
                                    $designation = "";
                                    $pht=0;

                                    foreach ($ventes as $key => $valus){
                                        $qte_t=0; $ph_t=0; $p_u=0;
                                        
                                        if($valu == $valus['code_article']){
                                            if($valus['pt_HT'] < 0 ){
                                                $qte_t=(-$valus['quantite']);
                                                $p_u=$valus['pu_HT'];
                                                $ph_t=$valus['pt_HT'];
                                            }else{
                                                $qte_t=$valus['quantite'];
                                                $p_u=$valus['pu_HT'];
                                                $ph_t=$valus['pt_HT'];
                                            }

                                            $qtetotal+=$qte_t;
                                            $pht+=$ph_t;
                                            $designation = $valus['designation'];
                                        }
                                    }
                                    
                                    $qtetotal_trait = $qtetotal!=0?$qtetotal:1;
                                    $pmoyen_u = ($pht/$qtetotal_trait);
                                    $valuesfinal[] = array(
                                        'qtetotal'=>$qtetotal,
                                        'designation'=>$designation,
                                        'pu_moyen'=>numberformat($pmoyen_u),
                                        'pht'=>numberformat($pht),
                                    );
                                }
                                arsort($valuesfinal);
                                foreach ($valuesfinal as $key => $valfinal) {
                                    $output .= '<tr><td></td><td>'.$valfinal['designation'].'</td><td>'.$valfinal['qtetotal'].'</td><td>'.$valfinal['pu_moyen'].'</td><td>'.$valfinal['pht'].'</td></tr>';   
                                }
                                
                            }else{
                                $output .= '<tr><td colspan="5"><b class="text-danger">Article du client non trouvé</b></td></tr>';   
                            }
                            /**ARTICLES LES PLUS ACHETE PAR UN CLIENT DEBUT */



                        }
                    }
                }else{
                    $output .= '<tr><td colspan="5"><b class="text-danger">le système ne retrouve aucun client</b></td></tr>';  
                }
            } 
            
            $array = array(
                'infos' => $output,
            );
        }else{
            $array = array(
                'error'   => true,
                'start_error' => form_error('start'),
                'end_error' => form_error('end'),
                'employe_error' => form_error('employe')
            );
        }   
        
        
       echo json_encode($array);
    }


   /**affiche les alert factures et les client concerné*/ 
    public function alert_facture(){
      $this->logged_in();
        $this->load->view('parts/header');
    	$this->load->view('parts/sidebar_manu');
    	
    	$matricule_en = session('users')['matricule'];
    	$abrev = 'RC';
    	
    	/**on selectionne toutes les factures en dette pour faire des alerts et etat des dettes*/
        $data['allertrc'] = $this->compta->alertdettedb($matricule_en,$abrev);
        
        $this->load->view('comptabilite/alertfacture',$data);
        
        $this->load->view('parts/subfooter');
    	$this->load->view('parts/footer_assets');
    }
    





}