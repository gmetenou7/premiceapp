<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct(){
		parent:: __construct();
		$this->load->model('Commerce/Commerce_model','commerce');
		$this->load->model('Home/Home_model','home');
		$this->load->model('Stock/Stock_model','stock');
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

		/**informations utile */
        $matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];
	

		$sommeglobal = 0;
		$somca=0;
		$valdoc="";
		$valdocarray = array();
		/**----------chiffre d'affaire d'une entreprise et ou des agences d'une entreprise sur une péeriode debut --------------*/
		/**affiche la liste des caisses en fonction de l'entreprise*/
		$caisses = $this->commerce->all_caisse_entreprise($matricule);
		if(!empty($caisses)){
			$debut = date('Y-m-d').' 00:00:00';
			$fin =  date('Y-m-d').' 23:59:59';
			$dateentredebut = $debut;
			$dateentrefin = $fin;
			foreach ($caisses as $key => $value) {
				/**informations nécessaires */
				$abrev = array('RC','RT','BR'); 
				//$abrev = array('SC','RC','RT','BR');
				$totalrc=0; $totalrt=0; $totalbr=0; $totalsc=0;
				$totalsrc=0; $totalsrt=0; $totalsbr=0; $totalssc=0;
				$totalsec=0;
				
				/**on selectionne l'agance de la caisse*/
				$agence_caisse = $this->commerce->agence_caisse($value['code_caisse'],$matricule);
				if(!empty($agence_caisse)){

					$sommesc=0;
					$sommev=0;
					$sommeec=0;
					/**informations sur l'agence pour la caisse selectionné*/
					$agencearray = $agence_caisse;
					$codecaisse = $value['code_caisse'];
					/**on selectionne les document d'une entreprise pour une caisse d'une agence sur une période donné*/
					$docagencecaisseperiode = $this->commerce->docventecaisseagenceperiode($value['code_caisse'],$matricule,$agencearray['matricule_ag'],$abrev, $debut,$fin);
					if(!empty($docagencecaisseperiode)){
						foreach ($docagencecaisseperiode as $key => $values) {
							/**on verifi que c'est un reglement client ou pas*/
							$rest = substr($values['code_document'], 0, 2);
							if($rest == "RC"){
								/**si c'est un reglement client, on s'assure que c'est pas une dette*/
								if($values['dette_restante'] == 0){
									$totalsrc += $values['pt_ttc_document'];
								} 
							}else if($rest == "RT"){
								$totalsrt += $values['pt_ttc_document'];
							}else if($rest == "BR"){
								$totalsbr += $values['pt_ttc_document'];
							} 
						}
						$sommev = ($totalsrt + $totalsbr); // + $totalsrc
					}
					
					/*on selectionne les sortie de caisse d'une agence d'une entreprise, sur une peériode donné*/
					$sortieagencecaisseperiode = $this->commerce->sortiecaisseagenceperiode($value['code_caisse'],$matricule,$agencearray['matricule_ag'], $debut,$fin);			
					if(!empty($sortieagencecaisseperiode)){
						foreach($sortieagencecaisseperiode as $value){
							$totalssc += $value['montatnt_sorti'];
						} 
						$sommesc = $totalssc;
					}
					
					/*on selectionne les entrées de caisse d'une agence d'une entreprise, d'un client, sur une période donné*/
                    $entreragencecaisseperiode = $this->commerce->entrercaisseagenceperiode($codecaisse,$matricule,$agencearray['matricule_ag'], $debut,$fin);
                    //debug($entreragencecaisseperiode); die();
                    if(!empty($entreragencecaisseperiode)){
                       foreach($entreragencecaisseperiode as $value){
                           $totalsec += $value['montant'];
                       } 
                       $sommeec =  $totalsec;
                    }

					/**ca total dans chaque caisse */
					$total = ($sommev - $sommesc + $sommeec);

					$valdoc = array(
						'agence' => $agencearray['nom_ag'],
						'totalca' => $total
					);
					$valdocarray[] = $valdoc;
					$sommeglobal += $total;
				}
			}

			/**on selectionne le total en banque sur cette période*/
			$cabanqueperiode = $this->home->cabanque($matricule,$dateentredebut,$dateentrefin);
			if(!empty($cabanqueperiode)){
				foreach ($cabanqueperiode as $key => $val) {
					if($val['agence_enter'] == ""){
						$somca+=$val['montant'];
					}
				}	
			}
		}
		/**----------chiffre d'affaire d'une entreprise et ou des agences d'une entreprise sur une péeriode fin --------------*/

		/**chiffre d'affaire total de l'entreprise(somme des chiffres d'affaire de chaque agence) */
		
		$data['caen'] = ($sommeglobal + $somca);
		
		/**chiffre d'affaire de chaque agence de l'entreprise */
		$data['caag'] = $valdocarray;

		/**ca total des banques sur la période */
		$data['cabanque'] = $somca; //$somca

		/**liste des agences pour afficher les articles les plus vendu */
		$agenceslist = $this->home->agence_entreprise($matricule);
		if(empty($agence) && !empty($agenceslist)){
			foreach ($agenceslist as $key => $value) {
				$array[] = array(
					'matricule_ag' => $value['matricule_ag'],
					'nom_ag' => $value['nom_ag']
				);
			}
        }else if(!empty($agence) && !empty($agenceslist)){
            foreach ($agenceslist as $key => $values){
				if($values['matricule_ag'] == $agence){				
					$array[] = array(
						'matricule_ag' => $values['matricule_ag'],
						'nom_ag' => $values['nom_ag']
					);
				}
			}
        }

		$data['agences'] = $array;
		$this->load->view('home/home',$data);
		

		$this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
	}
	
	
	
	/**selectionne une fois la caisse qui a déjà participer a 
	une vente puis afficher dans la page home pour permettre plus tard d'afficher 
	le chiffre d'affaire générer par: une agence, une entreprise sur une periode donné*/
    public function caisse_vente(){
        $this->logged_in();
        
        /**informations utile*/
        $matricule = session('users')['matricule'];
		$agence = session('users')['matricule_ag'];
        $output = "";
        
        /**caisse ayant participer aumoins a une vente*/
        $caisse_vente = $this->home->vente_caisse($matricule,$agence);
        if(!empty($caisse_vente)){
            foreach($caisse_vente as $val){
                $output .= '<option value='.$val['code_caisse'].'>'.$val['libelle_caisse'].'</option>';
                $array = array( 
                    'result' => $output    
                );  
            }
            
        }else{
            $output .= '<option selected>aucune caisse trouvé</option>';
            $array = array(
                'result' => $output    
            );
        }
        echo json_encode($array);
    }
    
    /**afficher le chiffre d'affaire de l'entreprise et ou des caisses sur une periode donnée*/
    public function ca_periode_vente(){
        $this->logged_in();
        
        $this->form_validation->set_rules('caisse', 'caisse','regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
                'regex_match' => 'Caractère non autorisé.'
            )
        );
        
        $this->form_validation->set_rules('start', 'période','required',array(
                'required'  => 'choisi la %s.',
            )
        );
        
        $this->form_validation->set_rules('end', 'période','required',array(
                'required'  => 'choisi la %s.',
            )
        );
        
        if($this->form_validation->run()){
            
            $output ="";
			$outputs ="";
            
            /***SI ON NE CHOISI PAS LA CAISSE ALORS, ON AFFICHE LE CA DE L'ENTREPRISE ET TOUTES LES AGENCE A CETTE PERIODE*/
            $caisse = $this->input->post('caisse');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            
            $debut = date("Y-m-d", strtotime($start));
            $fin = date("Y-m-d", strtotime($end));
            
            if(empty($caisse)){
                
                if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$debut) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$fin)){
                    
				    /**informations utile */
                    $matricule = session('users')['matricule'];
                    $agence = session('users')['matricule_ag'];
            
            		$sommeglobal = 0;
            		$valdoc="";
            		$valdocarray = array();
            		/**----------chiffre d'affaire d'une entreprise et ou des agences d'une entreprise sur une péeriode debut --------------*/
            		/**affiche la liste des caisses en fonction de l'entreprise*/
            		$caisses = $this->commerce->all_caisse_entreprise($matricule);
            		if(!empty($caisses)){
            			foreach ($caisses as $key => $value) {
            
            				$debut = $debut.' 00:00:00';
				            $fin =  $fin.' 23:59:59';
            
            				/**informations nécessaires */
            				$abrev = array('RC','RT','BR'); 
            				//$abrev = array('SC','RC','RT','BR');
            				$totalrc=0; $totalrt=0; $totalbr=0; $totalsc=0;
            				$totalsrc=0; $totalsrt=0; $totalsbr=0; $totalssc=0;
            				$totalsec=0;
            				
            				/**on selectionne l'agance de la caisse*/
            				$agence_caisse = $this->commerce->agence_caisse($value['code_caisse'],$matricule);
            				if(!empty($agence_caisse)){
            					$sommesc=0;
            					$sommev=0;
            					$sommeec=0;
            					/**informations sur l'agence pour la caisse selectionné*/
            					$agencearray = $agence_caisse;
            					$codecaisse = $value['code_caisse'];
            					/**on selectionne les document d'une entreprise pour une caisse d'une agence sur une période donné*/
            					$docagencecaisseperiode = $this->commerce->docventecaisseagenceperiode($value['code_caisse'],$matricule,$agencearray['matricule_ag'],$abrev, $debut,$fin);
            					if(!empty($docagencecaisseperiode)){
            						foreach ($docagencecaisseperiode as $key => $values) {
            							/**on verifi que c'est un reglement client ou pas*/
            							$rest = substr($values['code_document'], 0, 2);
            							if($rest == "RC"){
            								/**si c'est un reglement client, on s'assure que c'est pas une dette*/
            								if($values['dette_restante'] == 0){
            									$totalsrc += $values['pt_ttc_document'];
            								} 
            							}else if($rest == "RT"){
            								$totalsrt += $values['pt_ttc_document'];
            								
            								/************************************************ variation du ca sur une periode RT debut********************************/
            								
            								
            								
            								/************************************************ variation du ca sur une periode RT fin********************************/
            								
            							}else if($rest == "BR"){
            								$totalsbr += $values['pt_ttc_document'];
            								
            								/************************************************ variation du ca sur une periode BR debut********************************/
            								
            								
            								
            								/************************************************ variation du ca sur une periode BR debut********************************/
            							} 
            						}
            						$sommev = ($totalsrt + $totalsbr); //$totalsrc + 
            					}
            					
            					/*on selectionne les sortie de caisse d'une agence d'une entreprise, sur une peériode donné*/
            					$sortieagencecaisseperiode = $this->commerce->sortiecaisseagenceperiode($value['code_caisse'],$matricule,$agencearray['matricule_ag'], $debut,$fin);
            								
            					if(!empty($sortieagencecaisseperiode)){
            						foreach($sortieagencecaisseperiode as $value){
            							$totalssc += $value['montatnt_sorti'];
            						} 
            						$sommesc = $totalssc;
            					}
            					
            					/*on selectionne les entrées de caisse d'une agence d'une entreprise, d'un client, sur une période donné*/
                                $entreragencecaisseperiode = $this->commerce->entrercaisseagenceperiode($codecaisse,$matricule,$agencearray['matricule_ag'], $debut,$fin);
                                //debug($entreragencecaisseperiode); die();
                                if(!empty($entreragencecaisseperiode)){
                                   foreach($entreragencecaisseperiode as $value){
                                       $totalsec += $value['montant'];
                                   } 
                                   $sommeec =  $totalsec;
                                }
            					
            					/**ca total dans chaque caisse */
            					$total = ($sommev - $sommesc + $sommeec);
            
            					$valdoc = array(
            						'agence' => $agencearray['nom_ag'],
            						'totalca' => $total
            					);
            					$valdocarray[] = $valdoc;
            					$sommeglobal += $total;
            				}
            
            			}
            		}
            		/**----------chiffre d'affaire d'une entreprise et ou des agences d'une entreprise sur une péeriode fin --------------*/

					$debut1 = date("Y-m-d", strtotime($start));
                    $fin1 = date("Y-m-d", strtotime($end));

					$somca=0;
					$dateentredebut = $debut1.' 00:00:00';
					$dateentrefin = $fin1.' 23:59:59';
					/**on selectionne le total en banque sur cette période*/
					$cabanqueperiode = $this->home->cabanque($matricule,$dateentredebut,$dateentrefin);
					if(!empty($cabanqueperiode)){
						foreach ($cabanqueperiode as $key => $val) {
							if($val['agence_enter'] == ""){
								$somca+=$val['montant'];
							}
						}	
					}


            
            		/**chiffre d'affaire total de l'entreprise(somme des chiffres d'affaire de chaque agence) */
            		$data['caen'] = ($sommeglobal + $somca);
            		
            		/**chiffre d'affaire de chaque agence de l'entreprise */
            		$data['caag'] = $valdocarray;
				    
				    
                    
                    foreach($data['caag'] as $caval){
                        $output .= '
                            <div class="col-sm-6 col-lg-4 mb-4">
                              <div class="card text-center">
                                <div class="card-body">
                                  <h5 class="card-title">'.strtoupper($caval['agence']).'</h5>
                                  <p class="card-text"><small class="text-muted"> Période du '.$debut1.' 00:00:00 au '.$fin1.' 23:59:59</small></p>
                                  <h1 class="card-text">XAF '.number_format($caval['totalca'],2,",",".").'</h1>
                                </div>
                              </div>
                            </div>
                        ';
                    }
				    
					
					/***VARIATION DU CA SUR UNE PERIODE DANS L'ENTREPRISE EN GENERAL DEBUT */

					/** liste des caisse de l'entreprise pour afficher les variation du ca de chaque caisse le jour le jour*/
					$caisses = $this->commerce->all_caisse_entreprise($matricule);
					if(!empty($caisses)){
						foreach ($caisses as $key => $value){

							/**informations nécessaires */
							$abrev = array('RC','RT','BR'); 

							/**liste des dates entre 2 dates */
							$date1= strtotime($debut1); //Premiere date
							$date2= strtotime($fin1); //Deuxieme date
							$nbjour=($date2-$date1)/60/60/24;//Nombre de jours entre les deux

							/**on selectionne l'agance de la caisse*/
							$agence_caisse = $this->commerce->agence_caisse($value['code_caisse'],$matricule);
							if(!empty($agence_caisse)){

								$outputs .= '
								<ul class="list-group">
									<li class="list-group-item active" aria-current="true">'.$agence_caisse['nom_ag'].' '.$value['code_caisse'].' </li>
								</ul>
								<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th scope="col">Date</th>
										<th scope="col">CA</th>
										<th scope="col">Taux de variation</th>
										<th scope="col">Observation</th>
									</tr>
								</thead>
								<tbody>
								';

								/**initialiser la ca pour trouver le taux de variation du CA */
								$cainica = 0;
								for($i=0;$i<=$nbjour;$i++){

									/**données utile */
									$date = date('Y-m-d',$date1);

									$cadaterc = 0; $cadatert = 0;
									$cadatebr = 0; $cadatesc = 0;
									$cadateec = 0;

									/**on selectionne les document d'une entreprise pour une caisse d'une agence sur une période donné*/
									$docagencecaisseperiodes = $this->home->docventecaisseagenceperiode($value['code_caisse'],$matricule,$agence_caisse['matricule_ag'],$abrev, $date);
									if(!empty($docagencecaisseperiodes)){
										foreach ($docagencecaisseperiodes as $key => $values){
											$dbdateca = date("Y-m-d", strtotime($values['date_creation_doc']));
											
											$initial = substr($values['code_document'], 0, 2);
											if($initial == 'RT'){
												if($date == $dbdateca){
													$cadatert += $values['pt_ttc_document']; 
												}
											}

											if($initial == 'RC'){
												if($date == $dbdateca){
													if($values['dette_restante'] == 0){
														$cadaterc += $values['pt_ttc_document'];
													}
												}
											}

											if($initial == 'BR'){
												if($date == $dbdateca){
													$cadatebr += $values['pt_ttc_document'];
												}
											}
										}
									}


									/*on selectionne les sortie de caisse d'une agence d'une entreprise, sur une peériode donné*/
									$sortieagencecaisseperiode = $this->home->sortiecaisseagenceperiode($value['code_caisse'],$matricule,$agence_caisse['matricule_ag'], $date);
									if(!empty($sortieagencecaisseperiode)){
										foreach($sortieagencecaisseperiode as $content){
											$dbdateca = date("Y-m-d", strtotime($content['creer_le_sorti']));
											if($date == $dbdateca){
												$cadatesc += $content['montatnt_sorti'];
											}
										}
									}


									/*on selectionne les entrées de caisse d'une agence d'une entreprise, d'un client, sur une période donné*/
									$entreragencecaisseperiode = $this->home->entrercaisseagenceperiode($value['code_caisse'],$matricule,$agence_caisse['matricule_ag'], $date);
									if(!empty($entreragencecaisseperiode)){
										foreach($entreragencecaisseperiode as $valenter){
											$dbdateca = date("Y-m-d", strtotime($valenter['dateentrer']));
											if($date == $dbdateca){
												$cadateec += $valenter['montant'];
												//$outputs .= $valenter['montant'].' -- '.$valenter['dateentrer'].'<br>';
											}
										}
									}
									
									/***variriation du ca. formule = (CA HT N - CA HT N-1) * 100 / CA HT N-1 */
									$caini = (($cadateec + $cadatert + $cadatebr) - $cadatesc);
									$formule = ($cainica == 0)? '0':($caini - $cainica) * 100 / $cainica;
									

									if($caini!=0){

										$vari = $formule<0?'<span class="badge bg-danger">'.number_format($formule,2,",",".").' %</span>':'<span class="badge bg-success">'.number_format($formule,2,",",".").'  %</span>';
										$observ = $formule<0?'<span class="badge bg-danger">le CA à diminué par rapport au mois précédent</span>':'<span class="badge bg-success">le CA à augmenté par rapport au mois précédent</span>';

										$outputs .= '<tr>
														<th scope="row">'.$date.'</th>
														<td>'.number_format($caini,2,",",".").'</td>
														<td>'.$vari.'</td>
														<td>'.$observ.'</td>
													</tr>';
									}
											
										
									

									$cainica = (($cadateec + $cadatert + $cadatebr) - $cadatesc);


									$date1+=60*60*24; //On additionne d'un jour (en seconde)
								}

								$outputs .= '
									</tbody>
								</table>';
							}
						}
					}else{
						$outputs .="le système ne trouve aucune caisse pour l'entreprise";
					}
				
					/***VARIATION DU CA SUR UNE PERIODE DANS L'ENTREPRISE EN GENERAL FIN */
					
					$array = array(
                        'success' => '
							<div class="col-sm-6 col-lg-4 mb-4">
                              <div class="card text-center">
                                <div class="card-body">
                                  <h5 class="card-title">'.strtoupper(session('users')['nom']).'</h5>
                                  <p class="card-text"><small class="text-muted"> Période du '.$debut1.' 00:00:00 au '.$fin1.' 23:59:59</small></p>
                                  <h1 class="card-text">XAF '.number_format($data['caen'],2,",",".").'</h1>
                                </div>
                              </div>
                            </div>
							
							<div class="col-sm-6 col-lg-4 mb-4">
								<div class="card text-center">
								<div class="card-body">
									<h5 class="card-title">'.strtoupper('VERSEMENT PAR CHEQUE').'</h5>
									<p class="card-text"><small class="text-muted"> Période du '.$debut1.' 00:00:00 au '.$fin1.' 23:59:59</small></p>
									<h1 class="card-text">XAF '.number_format($somca,2,",",".").'</h1>
								</div>
								</div>
							</div>',
                        'caisseca' => $output,
						'variations' => $outputs
                    );


                    
                }else{
                    $array = array(
                        'success' => 'format de date incorrect'
                    );
                }
                

            /***SI ON CHOISI LA CAISSE ALORS, ON AFFICHE LE CA DE L'ENTREPRISE ET DE LA CAISSE EN QUESTION A CETTE PERIODE*/
            }else if(!empty($caisse)){
                
				/**determine le CA EN FONCTION DE LA CAISSE SUR UNE PERIODE */
                if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$debut) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$fin)){
                    
                    /**informations utile */
                    $matricule = session('users')['matricule'];
                    $agence = session('users')['matricule_ag'];
                    
                    $sommeglobal = 0;
            		$valdoc="";
            		$valdocarray = array();
            		/**----------chiffre d'affaire d'une entreprise et ou des agences d'une entreprise sur une péeriode debut --------------*/
                    
                    /**on s'assure une fois de plus que la caisse n'est pas vide*/
            		if(!empty($caisse)){
            		    
            		    /**période*/
            		    $debut = $debut.' 00:00:00';
				        $fin =  $fin.' 23:59:59';
            		    
            		    
            		    /**informations nécessaires */
        				$abrev = array('RC','RT','BR'); 
        				//$abrev = array('SC','RC','RT','BR');
        				$totalrc=0; $totalrt=0; $totalbr=0; $totalsc=0;
        				$totalsrc=0; $totalsrt=0; $totalsbr=0; $totalssc=0;
        				$totalsec=0;
        				
        				/**on selectionne l'agance de la caisse*/
        				$agence_caisse = $this->commerce->agence_caisse($caisse,$matricule);
        				if(!empty($agence_caisse)){
        				    
        				        $sommesc=0;
            					$sommev=0;
            					$sommeec=0;
            					/**informations sur l'agence pour la caisse selectionné*/
            					$agencearray = $agence_caisse;
            					
            					/**on selectionne les document d'une entreprise pour une caisse d'une agence sur une période donné*/
            					$docagencecaisseperiode = $this->commerce->docventecaisseagenceperiode($caisse,$matricule,$agencearray['matricule_ag'],$abrev, $debut,$fin);
            					if(!empty($docagencecaisseperiode)){
            					    foreach ($docagencecaisseperiode as $key => $values) {
            							/**on verifi que c'est un reglement client ou pas*/
            							$rest = substr($values['code_document'], 0, 2);
            							if($rest == "RC"){
            								/**si c'est un reglement client, on s'assure que c'est pas une dette*/
            								if($values['dette_restante'] == 0){
            									$totalsrc += $values['pt_ttc_document'];
            								} 
            							}else if($rest == "RT"){
            								$totalsrt += $values['pt_ttc_document'];
            							}else if($rest == "BR"){
            								$totalsbr += $values['pt_ttc_document'];
            							} 
            						}
            						$sommev = ($totalsrt + $totalsbr); //$totalsrc + 
            					}
            					
            					/*on selectionne les sortie de caisse d'une agence d'une entreprise, sur une peériode donné*/
            					$sortieagencecaisseperiode = $this->commerce->sortiecaisseagenceperiode($caisse,$matricule,$agencearray['matricule_ag'], $debut,$fin);
            					if(!empty($sortieagencecaisseperiode)){
            						foreach($sortieagencecaisseperiode as $value){
            							$totalssc += $value['montatnt_sorti'];
            						} 
            						$sommesc = $totalssc;
            					}
            					
            					/*on selectionne les entrées de caisse d'une agence d'une entreprise, d'un client, sur une période donné*/
                                $entreragencecaisseperiode = $this->commerce->entrercaisseagenceperiode($caisse,$matricule,$agencearray['matricule_ag'], $debut,$fin);
                                //debug($entreragencecaisseperiode); die();
                                if(!empty($entreragencecaisseperiode)){
                                   foreach($entreragencecaisseperiode as $value){
                                       $totalsec += $value['montant'];
                                   } 
                                   $sommeec =  $totalsec;
                                }
            					
            					/**ca total dans chaque caisse */
            					$total = ($sommev - $sommesc + $sommeec);
            
            					$valdoc = array(
            						'agence' => $agencearray['nom_ag'],
            						'totalca' => $total
            					);
            					$valdocarray[] = $valdoc;
            					$sommeglobal += $total;
        				}else{
        				    $array = array(
                                'success' => 'le système ne trouve pas l\'agence de cette caisse... contactez l\'administrateur',
                            ); 
        				}
            		}else{
            		    $array = array(
                            'success' => 'la valeur de la caisse semble vide... contactez l\'administrateur',
                        ); 
            		}
            		
            		/**----------chiffre d'affaire d'une entreprise et ou des agences d'une entreprise sur une péeriode fin --------------*/
            
            		/**chiffre d'affaire total de l'entreprise(somme des chiffres d'affaire de chaque agence) */
            		$data['caen'] = $sommeglobal;
            		
            		/**chiffre d'affaire de chaque agence de l'entreprise */
            		$data['caag'] = $valdocarray;
            		
            		$debut1 = date("Y-m-d", strtotime($start));
                    $fin1 = date("Y-m-d", strtotime($end));

					/***VARIATION DU CA SUR UNE PERIODE DANS L'ENTREPRISE EN GENERAL DEBUT */
					if(!empty($caisse)){
							/**informations nécessaires */
							$abrev = array('RC','RT','BR'); 

							/**liste des dates entre 2 dates */
							$date1= strtotime($debut1); //Premiere date
							$date2= strtotime($fin1); //Deuxieme date
							$nbjour=($date2-$date1)/60/60/24;//Nombre de jours entre les deux

							/**on selectionne l'agance de la caisse*/
							$agence_caisse = $this->commerce->agence_caisse($caisse,$matricule);
							if(!empty($agence_caisse)){

								$outputs .= '
								<ul class="list-group">
									<li class="list-group-item active" aria-current="true">'.$agence_caisse['nom_ag'].' '.$caisse.' </li>
								</ul>
								<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th scope="col">Date</th>
										<th scope="col">CA</th>
										<th scope="col">Taux de variation</th>
										<th scope="col">Observation</th>
									</tr>
								</thead>
								<tbody>
								';

								/**initialiser la ca pour trouver le taux de variation du CA */
								$cainica = 0;
								for($i=0;$i<=$nbjour;$i++){

									/**données utile */
									$date = date('Y-m-d',$date1);

									$cadaterc = 0; $cadatert = 0;
									$cadatebr = 0; $cadatesc = 0;
									$cadateec = 0;

									/**on selectionne les document d'une entreprise pour une caisse d'une agence sur une période donné*/
									$docagencecaisseperiodes = $this->home->docventecaisseagenceperiode($caisse,$matricule,$agence_caisse['matricule_ag'],$abrev, $date);
									if(!empty($docagencecaisseperiodes)){
										foreach ($docagencecaisseperiodes as $key => $values){
											$dbdateca = date("Y-m-d", strtotime($values['date_creation_doc']));
											
											$initial = substr($values['code_document'], 0, 2);
											if($initial == 'RT'){
												if($date == $dbdateca){
													$cadatert += $values['pt_ttc_document']; 
												}
											}

											if($initial == 'RC'){
												if($date == $dbdateca){
													if($values['dette_restante'] == 0){
														$cadaterc += $values['pt_ttc_document'];
													}
												}
											}

											if($initial == 'BR'){
												if($date == $dbdateca){
													$cadatebr += $values['pt_ttc_document'];
												}
											}
										}
									}


									/*on selectionne les sortie de caisse d'une agence d'une entreprise, sur une peériode donné*/
									$sortieagencecaisseperiode = $this->home->sortiecaisseagenceperiode($caisse,$matricule,$agence_caisse['matricule_ag'], $date);
									if(!empty($sortieagencecaisseperiode)){
										foreach($sortieagencecaisseperiode as $content){
											$dbdateca = date("Y-m-d", strtotime($content['creer_le_sorti']));
											if($date == $dbdateca){
												$cadatesc += $content['montatnt_sorti'];
											}
										}
									}


									/*on selectionne les entrées de caisse d'une agence d'une entreprise, d'un client, sur une période donné*/
									$entreragencecaisseperiode = $this->home->entrercaisseagenceperiode($caisse,$matricule,$agence_caisse['matricule_ag'], $date);
									if(!empty($entreragencecaisseperiode)){
										foreach($entreragencecaisseperiode as $valenter){
											$dbdateca = date("Y-m-d", strtotime($valenter['dateentrer']));
											if($date == $dbdateca){
												$cadateec += $valenter['montant'];
												//$outputs .= $valenter['montant'].' -- '.$valenter['dateentrer'].'<br>';
											}
										}
									}
									
									/***variriation du ca. formule = (CA HT N - CA HT N-1) * 100 / CA HT N-1 */
									$caini = (($cadateec + $cadatert + $cadatebr) - $cadatesc);
									$formule = ($cainica == 0)? '0':($caini - $cainica) * 100 / $cainica;
									

									if($caini!=0){

										$vari = $formule<0?'<span class="badge bg-danger">'.number_format($formule,2,",",".").' %</span>':'<span class="badge bg-success">'.number_format($formule,2,",",".").'  %</span>';
										$observ = $formule<0?'<span class="badge bg-danger">le CA à diminué par rapport au mois précédent</span>':'<span class="badge bg-success">le CA à augmenté par rapport au mois précédent</span>';
										
										$outputs .= '<tr>
														<th scope="row">'.$date.'</th>
														<td>'.number_format($caini,2,",",".").'</td>
														<td>'.$vari.'</td>
														<td>'.$observ.'</td>
													</tr>';
									}
											
										
									

									$cainica = (($cadateec + $cadatert + $cadatebr) - $cadatesc);


									$date1+=60*60*24; //On additionne d'un jour (en seconde)
								}

								$outputs .= '
									</tbody>
								</table>';
							}
					}else{
						$outputs .="le système ne trouve aucune caisse pour l'entreprise";
					}
				
					/***VARIATION DU CA SUR UNE PERIODE DANS L'ENTREPRISE EN GENERAL FIN */
                    
                    foreach($data['caag'] as $caval){
                        $output .= '
                            <div class="col-sm-6 col-lg-4 mb-4">
                              <div class="card text-center">
                                <div class="card-body">
                                  <h5 class="card-title">'.strtoupper($caval['agence']).'</h5>
                                  <p class="card-text"><small class="text-muted"> Période du '.$debut1.' 00:00:00 au '.$fin1.' 23:59:59</small></p>
                                  <h1 class="card-text">XAF '.number_format($caval['totalca'],2,",",".").'</h1>
                                </div>
                              </div>
                            </div>
                        ';
                        
                        $array = array(
                            'caisseca' => $output,
							'success' => '',
							'variations'=>$outputs
                        );
                    }
                }else{
                    $array = array(
                        'success' => 'format de date incorrect',
                    );
                }

				

            }
        }else{
            $array = array(
				'error'   => true,
				'caisse_error' => form_error('caisse'),
				'periode_error' => form_error('start'),
                'periode_error' => form_error('end'),
			);
        }
        echo json_encode($array);
    }
    
	
	/**les articles les plus vendu sur une periode et par famille*/
	public function article_plus_vendu(){
	    $this->logged_in();

		$agence = session('users')['matricule_ag'];
		if(empty($agence)){
			$this->form_validation->set_rules('plusvenduag', 'agence','regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
					'regex_match' => 'Caractère non autorisé.'
				)
			);
		}else{
			$this->form_validation->set_rules('plusvenduag', 'agence','required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
					'regex_match' => 'Caractère non autorisé.',
					'required' => 'champ obligatiore'
				)
			);
		}

		$this->form_validation->set_rules('debutplusv', 'période','required',array(
			'required'  => 'choisi la %s.',
			)
		);
		
		$this->form_validation->set_rules('finplusv', 'période','required',array(
				'required'  => 'choisi la %s.',
			)
		);

		if($this->form_validation->run()){

			/*** ON COLLECTE D'ABORD TOUS LES ARTICLES VENDU (RC,RT,BR) qu'on VAS GENERALISE DANS UN TABLEAU DEBUT */
			$abrev = array('RC','RT','BR'); 
			
			/**matricule de l'entreprise */
			$matricule = session('users')['matricule'];

			$begin = $this->input->post('debutplusv');
			$end = $this->input->post('finplusv');

			$agenceplusvendu = trim($this->input->post('plusvenduag'));

			$debut = date('Y-m-d',strtotime($begin));
			$fin =  date('Y-m-d',strtotime($end));

			/*** ON COLLECTE D'ABORD TOUS LES ARTICLES VENDU (RC,RT,BR) qu'on VAS GENERALISE DANS UN TABLEAU DEBUT */

			/**liste des documents de vente pour une entreprise et pour un type de document*/
			
			//$query = !empty($agenceplusvendu)?$this->home->documentventeperiode2($abrev,$matricule,$debut,$fin,$agenceplusvendu):$this->home->documentventeperiode($abrev,$matricule,$debut,$fin);
			

			$output = "";
			$documentsvente = $this->home->documentventeperiode2($abrev,$matricule,$debut,$fin,$agenceplusvendu);
			if(!empty($documentsvente)){
				/**on vide la table article comptable */
				$deleteinformations = $this->home->deletearticlecomptableinformation($matricule);
				if($deleteinformations){
					
					foreach ($documentsvente as $key => $value) {
						/**pour chaque document, on selectionne la liste de ses articles */
						$artdocument = $this->home->articledocumentvente($value['code_document']);
						if($artdocument){
							/**on parcour les articles du document */
							foreach ($artdocument as $key => $values){
								
								/**on complete les informations sur les artiles en allant les prendre dans la table article*/
								$articlecomplement = $this->home->singlearticleshow($values['code_article']);
								if(!empty($articlecomplement)){
									if(!empty($articlecomplement)){
										//on enregistre les articles dans la table "articlecomptable" de la BD pour traitement
										$valinput = array(
											"codearticle" => $values['code_article'],
											"nomarticlecomptable" => $articlecomplement['designation'],
											"famillearticlecomptable" => $articlecomplement['mat_famille_produit'],
											"codedocumentarticle" => $values['code_document'],
											"quantitearticle" => $values['quantite'],
											"prixuhtarticle" => $values['pu_HT'],
											"prixthtarticle" => $values['pt_HT'],
											"nomdocarticle" => $values['nom_document'],
											"matriculeentreprisearticle" => $values['code_en_art_doc'],
											"depotdocarticle" => $values['depot_doc'],
											"clientdocarticle" => $values['code_client_document'],
											"agencedocarticle" => $values['code_agence_doc'],
											"prixthtdocarticle" => $values['pt_ht_document'],
											"prixtttcdocarticle" => $values['pt_ttc_document'],
											"detterestantearticle" => $values['dette_restante'],
											"datecreationarticle" => $values['date_creer_art_doc'],
											"dateac" => dates()
										);
										$response = $this->home->saveinformation($valinput);
										if($response){
											$output = '<tr><td colspan="6"><b class="text-danger">...</b></td></tr>';
										}else{
											$output = '<tr><td colspan="6"><b class="text-danger">article(s) non inseré(s) dans la base des données</b></td></tr>';
										}
									}else{
										$output = '<tr><td colspan="6"><b class="text-danger">informations non trouvée(s) pour cet article</b></td></tr>';
									}
								}else{
									$output = '<tr><td colspan="6"><b class="text-danger">article correspondant non trouvé</b></td></tr>';
								}
							}
						}else{
							$output .= '<tr><td colspan="6"><b class="text-danger">article(s) non trouvé(s) pour ce document '.$value['code_document'].'</b></td></tr>';
						}
					}
				}else{
					$output = '<tr><td colspan="6"><b class="text-danger">erreur survenu, initialisation du processus suspendu</b></td></tr>';
				}
			}else{
				$output = '<tr><td colspan="6"><b class="text-danger">aucun article trouvé</b></td></tr>';
			}


			/**PROCESSUS DE TRAITEMENT DES ARTICLES LES PLUS VENDU DEBUT PAR FAMILLE ET PAR AGENCE*/
			if(!empty($documentsvente)){

				/**liste des artricles de la table articlecomptable en fonction de l'entreprise et de la période */
                $articlecomptable = $this->home->articlescomptable($matricule,$debut,$fin);
                if(!empty($articlecomptable)){
                    foreach ($articlecomptable as $key => $artscomptable) {
                        
                        /**on verifie le type de l'article grace au code du document*/
                        $typedoc = substr($artscomptable['codedocumentarticle'], 0, 2);
                        if($typedoc == "RT"){
                            $artcomptablert[] = $artscomptable;
                        }

                        if($typedoc == "RC"){
                            if($artscomptable['detterestantearticle']>0){
                                $artcomptablercdette[] = $artscomptable;
                            }
                            
                            if($artscomptable['detterestantearticle']==0){
                                $artcomptablercsansdette[] = $artscomptable;
                            }
                        }
                        if($typedoc == "BR"){
                            /**pour mieux faire les calculs par la suite, on vas diviser les bon de retour en deux (RT et RC) */
                            $typedocbr = substr($artscomptable['nomdocarticle'], 0, 2);
                            if($typedocbr == "RT"){
                                $artcomptablebrrt[] = $artscomptable;
                            }
                            if($typedocbr == "RC"){
                                $artcomptablebrrc[] = $artscomptable;
                            }
                        }
                    }


					/**apres avoir reuni tous les articles, on les classe puis on les a mis dans les tableau suivant */
					/**1: liste des articles en regelement ticket 
					 * 2: liste des articles en reglement client et qui sont des dettes
					 * 3: liste des articles en reglement client et qui ne sont pasles dettes
					 * 4: liste des articles en bon de retour qui sont issu des reglement ticket
					 * 5: liste des articles en bon de retour qui sont des reglement client
					*/
					$artcomptablert;
					$artcomptablercdette;
					$artcomptablercsansdette;
					$artcomptablebrrt;
					$artcomptablebrrc;


					/**liste des articles de l'entreprise  qui vas servir a 
					 * compter le nombre de fois qu'un article apparait dans la liste des articles vendu ainsi que les details dessus
					 * */
					$articles = $this->home->articleentreprise($matricule);
					if(!empty($articles)){

						foreach ($articles as $key => $arts){
							/**RT */
							$qte=0;
							$prixtht=0;
							if(!empty($artcomptablert)){
								foreach ($artcomptablert as $key => $artsrt){
									if($artsrt['codearticle'] == $arts['matricule_art']){
										$qte+=$artsrt['quantitearticle'];
										$prixtht+=$artsrt['prixthtarticle'];
									}
								}
								$rt =  array(
									'codearticle'=>$arts['matricule_art'],
									'famillearticle'=>$arts['mat_famille_produit'],
									'designationarticle'=>$arts['designation'],
									'qtearticle'=>$qte,
									'prixthtarticle'=>$prixtht,
								);
							}
							if(!empty($rt) && $rt['qtearticle'] !=0){
								$statrt[] = $rt;
							}
	
	
							/**BR RT*/
							$qte_brrt=0;
							$prixthtbrrt=0;
							if(!empty($artcomptablebrrt)){
								foreach($artcomptablebrrt as $key => $artsbrrt){
									if($artsbrrt['codearticle'] == $arts['matricule_art']){
										$qte_brrt+=$artsbrrt['quantitearticle'];
										$prixthtbrrt+=$artsbrrt['prixthtarticle'];
									}
								}
								$brrt =  array(
									'codearticle'=>$arts['matricule_art'],
									'famillearticle'=>$arts['mat_famille_produit'],
									'designationarticle'=>$arts['designation'],
									'qtearticle'=>$qte_brrt,
									'prixthtarticle'=>$prixthtbrrt,
								);
							}
							if(!empty($brrt) && $brrt['qtearticle'] !=0){
								$statbrrt[] = $brrt;
							}
	
	
							/**BR RC*/
							$qtebrrc=0;
							$prixthtbrrc=0;
							if(!empty($artcomptablebrrc)){
								foreach($artcomptablebrrc as $key => $artsbrrc){
									if($artsbrrc['codearticle'] == $arts['matricule_art']){
										$qtebrrc+=$artsbrrc['quantitearticle'];
										$prixthtbrrc+=$artsbrrc['prixthtarticle'];
									}
								}
								$brrc =  array(
									'codearticle'=>$arts['matricule_art'],
									'famillearticle'=>$arts['mat_famille_produit'],
									'designationarticle'=>$arts['designation'],
									'qtearticle'=>$qtebrrc,
									'prixthtarticle'=>$prixthtbrrc,
								);
							}
							if(!empty($brrc) && $brrc['qtearticle'] !=0){
								$statbrrc[] = $brrc;
							}
	
	
							/**RC sans dette*/
							$qtercsd=0;
							$prixthtrcsd=0;
							if(!empty($artcomptablercsansdette)){
								foreach($artcomptablercsansdette as $key => $artsrcsd){
									if($artsrcsd['codearticle'] == $arts['matricule_art']){
										$qtercsd+=$artsrcsd['quantitearticle'];
										$prixthtrcsd+=$artsrcsd['prixthtarticle'];
									}
								}
								$rcsd =  array(
									'codearticle'=>$arts['matricule_art'],
									'famillearticle'=>$arts['mat_famille_produit'],
									'designationarticle'=>$arts['designation'],
									'qtearticle'=>$qtercsd,
									'prixthtarticle'=>$prixthtrcsd,
								);
							}
							if(!empty($rcsd) && $rcsd['qtearticle'] !=0){
								$statrcsd[] = $rcsd;
							}
	
	
							/**RC avec dette*/
							$qtercad=0;
							$prixthtrcad=0;
							if(!empty($artcomptablercdette)){
								foreach($artcomptablercdette as $key => $artsrcad){
									if($artsrcad['codearticle'] == $arts['matricule_art']){
										$qtercad+=$artsrcad['quantitearticle'];
										$prixthtrcad+=$artsrcad['prixthtarticle'];
									}
								}
								$rcad =  array(
									'qtearticle'=>$qtercad,
									'codearticle'=>$arts['matricule_art'],
									'famillearticle'=>$arts['mat_famille_produit'],
									'designationarticle'=>$arts['designation'],
									'prixthtarticle'=>$prixthtrcad,
								);
							}
							if(!empty($rcad) && $rcad['qtearticle'] !=0){
								$statrcad[] = $rcad;
							}
						}

						/**liste des articles les plus vendu en fonction du type de document et la nature(dette ou credit) 
						 * 1: total ainsi que les informations des articles les plus vendu en reglement ticket
						 * 2: total ainsi que les informations des articles les plus vendu en bon de retour - reglement ticket
						 * 3: total ainsi que les informations des articles les plus vendu en bon de retour - reglement client
						 * 4: total ainsi que les informations des articles les plus vendu en reglement client - sans dette
						 * 5: total ainsi que les informations des articles les plus vendu en reglement client - avec dette
						*/
						
						$statrt;
						$statbrrt;
						$statbrrc;
						$statrcsd;
						$statrcad;


						/**a partir d'ici on a deja les quantité et les montant totales de chaque article quelque soit sont type de document
						 * 1: si c'est le br en rt on soustrait dans le rt
						 * 2: si c'est le br en rc on soustrait dans le rc sans dette(rcsd)
						 * 3: si c'est le rc en réglé on additionne dans le rt
						 * 4: les rc en dette sont déjà prêt a être affiche en article les plus vendu en dette
						 */

						/**1 */
						if(!empty($statrt)){
							foreach ($statrt as $key => $valuertfin){
								$qtefinrt = "";
								$prixthtfinrt = "";
								if(!empty($statbrrt)){
									foreach ($statbrrt as $key => $valuebrrt){
										if($valuebrrt['codearticle'] == $valuertfin['codearticle']){
											$qtefinrt = ($valuertfin['qtearticle'] - $valuebrrt['qtearticle']);
											$prixthtfinrt = ($valuertfin['prixthtarticle'] + $valuebrrt['prixthtarticle']);
										}
									}
								}
								$qtebrrt1 = !empty($qtefinrt)?$qtefinrt:$valuertfin['qtearticle'];
								$prixbrrt1 = !empty($prixthtfinrt)?$prixthtfinrt:$valuertfin['prixthtarticle'];
								$rtbr[] =  array(
									'codearticle'=>$valuertfin['codearticle'],
									'designationarticle'=>$valuertfin['designationarticle'],
									'famillearticle'=>$valuertfin['famillearticle'],
									'qtearticle'=>$qtebrrt1,
									'prixthtarticle'=>$prixbrrt1,
								);
							}
						}else{
							$output = '<tr><td colspan="6"><b class="text-danger">...</b></td></tr>';
						}


						/**liste des articles les plus vendu en enlevant le bon retour en reglement ticket sur les reglement ticket */
						$rtbr;

						/**2 */
						if(!empty($statrcsd)){
							foreach ($statrcsd as $key => $valuercfin){
								$qtefinrc="";
								$prixthtfinrc="";
								if(!empty($statbrrc)){
									foreach($statbrrc as $key => $valuebrrc){
										if($valuebrrc['codearticle'] == $valuercfin['codearticle']){
											$qtefinrc = ($valuercfin['qcearticle'] - $valuebrrc['qtearticle']);
											$prixthtfinrc=($valuercfin['prixthtarticle'] + $valuebrrc['prixthtarticle']);
										}
									}
								}
								$qtebrrc = !empty($qtefinrc)?$qtefinrc:$valuercfin['qtearticle'];
								$prixbrrc = !empty($prixthtfinrc)?$prixthtfinrc:$valuercfin['prixthtarticle'];
								$rcbr[] =  array(
									'codearticle'=>$valuercfin['codearticle'],
									'designationarticle'=>$valuercfin['designationarticle'],
									'famillearticle'=>$valuercfin['famillearticle'],
									'qtearticle'=>$qtebrrc,
									'prixthtarticle'=>$prixbrrc,
								);
							}
						}else{
							$output = '<tr><td colspan="6"><b class="text-danger">..</b></td></tr>';
						}

						/**liste des articles les plus vendu en enlevant le bon retour en reglement client sur les reglement client */
						$rcbr;

						/**a l'issu de ce qui precède, on a a present:
						 * 1: la liste des articles les plus vendu en dette ($statrcad)
						 * 2: la liste des articles les plus vendu en reglement ticket qui n'ont plus de bon de retour en rt
						 * 3: la liste des articles les plus vendu en reglement client qui n'ont plus de bon de retour en rc
						 */
		
						/**
						 * maintenant il est question d'additionner les articles qui sont à la fois dans 
						 * les articles les plus vendu en reglement ticket et ceux en reglement client réglé
						 */
		
						if(!empty($rtbr)){
							foreach ($rtbr as $key => $valuertrcfin){
								$qtefinrtrc="";
								$prixthtfinrtrc="";
								if(!empty($rcbr)){
									foreach($rcbr as $key => $valuertrc){
										if($valuertrc['codearticle'] == $valuertrcfin['codearticle']){
											$qtefinrtrc = ($valuertrcfin['qtearticle'] + $valuertrc['qtearticle']);
											$prixthtfinrtrc=($valuertrcfin['prixthtarticle'] + $valuertrc['prixthtarticle']);
										}
									}
								}
								$qtertrc = !empty($qtefinrtrc)?$qtefinrtrc:$valuertrcfin['qtearticle'];
								$prixrtrc = !empty($prixthtfinrtrc)?$prixthtfinrtrc:$valuertrcfin['prixthtarticle'];
								$rtrc[] =  array(
									'qtearticle'=>$qtertrc,
									'codearticle'=>$valuertrcfin['codearticle'],
									'designationarticle'=>$valuertrcfin['designationarticle'],
									'famillearticle'=>$valuertrcfin['famillearticle'],
									'prixthtarticle'=>$prixrtrc,
								);
							}
						}else{
							$output = '<tr><td colspan="6"><b class="text-danger">...</b></td></tr>';
						}

						/**liste des articles les plus vendu ayant associer les articles les plus vendu qui sont a la fois dans le rt et le rc réglé */
						$rtrc;

						/**on a desormais la liste des rt additionné des rc réglé qui apparaissent egalement dans les rt
						 * 1: ******liste des rcbr qui qui ne sont pas dans les rtrc
						 * 2: liste des articles les plus vendu en dette: $statrcad
						*/
		
						/*maintenant il faut la liste des rc réglé qui ne sont pas dans les rt additionné au rc*/
						function findMissing( $a, $b, $n, $m){
							for ( $i = 0; $i < $n; $i++){
								for ($j = 0; $j < $m; $j++)
									if ($a[$i]['codearticle'] == $b[$j]['codearticle'])
										break;
								if ($j == $m)
									$rcnonrtrc[] =  array(
										'qtearticle'=>$a[$i]['qtearticle'],
										'codearticle'=>$a[$i]['codearticle'],
										'designationarticle'=>$a[$i]['designationarticle'],
										'famillearticle'=>$a[$i]['famillearticle'],
										'prixthtarticle'=>$a[$i]['prixthtarticle'],
									);
							}
		
							return $rcnonrtrc;
						}
						if(!empty($rcbr) && !empty($rtrc)){
							$a =$rcbr;
							$b=$rtrc;
							$n = count($a);
							$m = count($b);
		
							/**liste des rc qui ne sont pas dans les rt additionné(rtrc) */
							$rcnonrt = findMissing($a, $b, $n, $m);
						}else{
							$output = '<tr><td colspan="6"><b class="text-danger">...</b></td></tr>';
						}


						/**LISTE DES FAMILLE D'ARTICLE QUI VAS PERMETTRE D'AFFICHER LES ARTICLES LES PLUS VENDU PAR FAMILLE */
						$famille = $this->stock->all_famille($matricule);
						if(!empty($famille)){


                            $totalqtefin=0;
                            $totalcafin=0;
                            $totalbeneffin=0;
                            
							if(!empty($rtrc)){
								arsort($rtrc); 
								$output .= '
									<tr class="table-info">
										<td colspan="6"><h1>1</h1><b>ARTICLE LES PLUS VENDU SUR LA PERIODE DU  '.dateformat($debut).' AU  '.date('d-m-Y',strtotime($fin)).' 23:59:59</b></td>
									</tr>
								';
								
								foreach ($famille as $key => $valuefamille){
									$output .= '
										<tr>
											<th colspan="6">FAMILLE ARTICLE: '.$valuefamille['nom_fam'].'</th>
										</tr>
									';
		
										$num1=0;
										$qtetotalrtrc=0;
										$prixtrtrc=0;
										$totalbenefice=0;
										$beneficemoyen = 0;
									foreach ($rtrc as $key => $valuertrc){
										$puM = 0;
										$benefice = 0;
										if($valuefamille['matricule_fam'] == $valuertrc['famillearticle']){
											/**trouver le benefice moyen de chaque article debut */
		
												/**1: prix unitaire moyen de l'article */
												$puM = ($valuertrc['prixthtarticle'] / $valuertrc['qtearticle']);
		
												/**on selectionne l'article pour avoir le prix de revient */
												$getsinglearticle=$this->stock->get_details_articles($valuertrc['codearticle']);
												if(!empty($getsinglearticle)){
												    if($puM > $getsinglearticle['prix_revient']){
												      $benefice = ($valuertrc['qtearticle'] * ($puM - $getsinglearticle['prix_revient']));  
												    }else{
												        $benefice = 0;
												    }
													
												}else{
													$output = '<tr bgcolor="#CD5C5C"><th colspan="6"><b class="text-danger">ERREUR 500 survenu contactez l\'administrateur</b></th></tr>';
												}
											/**trouver le benefice moyen de chaque article fin */
		
											$num1+=1;
											$qtetotalrtrc += $valuertrc['qtearticle'];
											$prixtrtrc += $valuertrc['prixthtarticle'];
											$totalbenefice +=$benefice;
											$beneficemoyen += $benefice;
											$output .= '
												<tr>
													<th>'.$num1.'</th>
													<td>'.$valuertrc['codearticle'].'</td>
													<td>'.$valuertrc['designationarticle'].'</td>
													<td>'.$valuertrc['qtearticle'].'</td>
													<td>'.number_format($valuertrc['prixthtarticle'],2,",",".").'</td>
													<td>'.number_format($benefice,2,",",".").'</td>
												</tr>
											';
										}
									}
									$output .= '
										<tr bgcolor="#CD5C5C">
											<th colspan="3">TOTAL</th>
											<th>'.number_format($qtetotalrtrc,2,",",".").'</th>
											<th>'.number_format($prixtrtrc,2,",",".").'</th>
											<th>'.number_format($totalbenefice,2,",",".").'</th>
										</tr>
									';
		
									$rapport1[] = array(
										'famille' => $valuefamille['nom_fam'],
										'qtetotal' => $qtetotalrtrc,
										'ca' => $prixtrtrc,
										'benefice' => $beneficemoyen
									);
								}
		
								if(!empty($rapport1)){
									$qtetotal1 = 0;
									$catotal1 = 0;
									$beneficetotal1 = 0;
									$output .='
										<tr>
											<th colspan="6"><h1>RAPPORT FINAL PARTIE 1</h1></th>
										</tr>
										<tr>
											<th>FAMILLE D\'ARTICLE</th>
											<th>QTE</th>
											<th>CA</th>
											<th>BENEFICE MOYEN</th>
										</tr>
									';
									foreach ($rapport1 as $key => $valuerapport1) {
										$qtetotal1 += $valuerapport1['qtetotal'];
										$catotal1 += $valuerapport1['ca'];
										$beneficetotal1 += $valuerapport1['benefice'];
										$output .='
											<tr>
												<td>'.$valuerapport1['famille'].'</td>
												<td>'.$valuerapport1['qtetotal'].'</td>
												<td>'.number_format($valuerapport1['ca'],2,",",".").'</td>
												<th>'.number_format($valuerapport1['benefice'],2,",",".").'</th>
											</tr>
										';
									}
									$totalqtefin+=$qtetotal1;
									$totalcafin+=$catotal1;
									$totalbeneffin+=$beneficetotal1;
									$output .='
										<tr>
											<th>TOTAL</th>
											<th>'.$qtetotal1.'</th>
											<th>'.number_format($catotal1,2,",",".").'</th>
											<th>'.number_format($beneficetotal1,2,",",".").'</th>
										</tr>
									';
								}else{
									$output = '<tr><td colspan="6"><b class="text-danger">...</b></td></tr>';
								}
								/**message ici */
								
							}


							$output .= '
                                <tr>
                                    <td colspan="6"></b></td>
                                </tr>
                            ';
                            if(!empty($rcnonrt)){
								arsort($rcnonrt);
                                $output .= '
                                    <tr class="table-warning">
                                        <td colspan="6"><h1>2</h1><b>ARTICLE LES PLUS VENDU EN REGLEMENT CLIENT REGLE SUR LA PERIODE DU  '.date('d-m-Y',strtotime($debut)).' AU  '.date('d-m-Y',strtotime($fin)).'</b></td>
                                    </tr>
                                ';

                                foreach ($famille as $key => $valuefamille1){
                                        $output .= '
                                            <tr>
                                                <th colspan="6">FAMILLE ARTICLE: '.$valuefamille1['nom_fam'].'</th>
                                            </tr>
                                        ';
                                        $num2=0;
                                        $qtetotalrcnonrt=0;
                                        $prixtrcnonrt=0;

                                        $totalbeneficerc=0;
                                        $beneficemoyenrc=0;
                                        
                                    foreach ($rcnonrt as $key => $valuercnonrt){
                                        $puMrc = 0;
                                        $beneficerc = 0;

                                        if($valuefamille1['matricule_fam'] == $valuercnonrt['famillearticle']){

                                            /**trouver le benefice moyen de chaque article debut */

											/**1: prix unitaire moyen de l'article */
											$puMrc = ($valuercnonrt['prixthtarticle'] / $valuercnonrt['qtearticle']);

											/**on selectionne l'article pour avoir le prix de revient */
											$getsinglearticlerc=$this->stock->get_details_articles($valuercnonrt['codearticle']);
											if(!empty($getsinglearticlerc)){
										        if($puMrc > $getsinglearticlerc['prix_revient']){
											      $beneficerc = ($valuercnonrt['qtearticle'] * ($puMrc - $getsinglearticlerc['prix_revient']));  
											    }else{
											        $beneficerc = 0;
											    }
												//$beneficerc = ($valuercnonrt['qtearticle'] * ($puMrc - $getsinglearticlerc['prix_revient']));
											}else{
												$output .= '
													<tr bgcolor="#CD5C5C">
														<th colspan="6">ERREUR 500 survenu contactez l\'administrateur</th>
													</tr>
												';
											}

                                            /**trouver le benefice moyen de chaque article fin */

                                            $num2+=1;
                                            $qtetotalrcnonrt += $valuercnonrt['qtearticle'];
                                            $prixtrcnonrt += $valuercnonrt['prixthtarticle'];

                                            $totalbeneficerc +=$beneficerc;
                                            $beneficemoyenrc +=$beneficerc;

                                            $output .= '
                                                <tr>
                                                    <th>'.$num2.'</th>
                                                    <td>'.$valuercnonrt['codearticle'].'</td>
                                                    <td>'.$valuercnonrt['designationarticle'].'</td>
                                                    <td>'.$valuercnonrt['qtearticle'].'</td>
                                                    <td>'.number_format($valuercnonrt['prixthtarticle'],2,",",".").'</td>
                                                    <td>'.number_format($beneficerc,2,",",".").'</td>
                                                </tr>
                                            ';
                                        }
                                    }
                                    $output .= '
                                        <tr bgcolor="#f00020">
                                            <th colspan="3">TOTAL</th>
                                            <th>'.number_format($qtetotalrcnonrt,2,",",".").'</th>
                                            <th>'.number_format($prixtrcnonrt,2,",",".").'</th>
                                            <th>'.number_format($totalbeneficerc,2,",",".").'</th>
                                        </tr>
                                    ';

                                    $rapport2[] = array(
                                        'famille' => $valuefamille1['nom_fam'],
                                        'qtetotal' => $qtetotalrcnonrt,
                                        'ca' => $prixtrcnonrt,
                                        'benefice' => $beneficemoyenrc
                                    );
                                }
                                if(!empty($rapport2)){

                                    $qtetotal2 = 0;
                                    $catotal2 = 0;
                                    $beneficetotal2 = 0;

                                    $output .='
                                        <tr>
                                            <th colspan="6"><h1>RAPPORT FINAL PARTIE 2</h1></th>
                                        </tr>

                                        <tr>
                                            <th>FAMILLE D\'ARTICLE</th>
                                            <th>QTE</th>
                                            <th>CA</th>
                                            <th>BENEFICE MOYEN</th>
                                        </tr>
                                    ';
                                    foreach ($rapport2 as $key => $valuerapport2) {

                                        $qtetotal2 += $valuerapport2['qtetotal'];
                                        $catotal2 += $valuerapport2['ca'];
                                        $beneficetotal2 += $valuerapport2['benefice'];

                                        $output .='
                                            <tr>
                                                <td>'.$valuerapport2['famille'].'</td>
                                                <td>'.$valuerapport2['qtetotal'].'</td>
                                                <td>'.number_format($valuerapport2['ca'],2,",",".").'</td>
                                                <td>'.number_format($valuerapport2['benefice'],2,",",".").'</td>
                                            </tr>
                                        ';
                                    }
                                    
                                    $totalqtefin+=$qtetotal2;
                                    $totalcafin+=$catotal2;
                                    $totalbeneffin+=$beneficetotal2;
                                    $output .='
                                            <tr>
                                                <th>TOTAL</th>
                                                <th>'.$qtetotal2.'</th>
                                                <th>'.number_format($catotal2,2,",",".").'</th>
                                                <th>'.number_format($beneficetotal2,2,",",".").'</th>
                                            </tr>
                                        ';
                                }else{
                                    $output = '<tr><td colspan="6"><b class="text-danger">...</b></td></tr>';
                                }
                            }
							

							$output .= '
                                <tr>
                                    <td colspan="6"></b></td>
                                </tr>
                            ';
                            if(!empty($statrcad)){
								arsort($statrcad);
                                $output .= '
                                    <tr class="table-success">
                                        <td colspan="6"><h1>3</h1><b>ARTICLE LES PLUS VENDU EN DETTE SUR LA PERIODE DU  '.date('d-m-Y',strtotime($debut)).' AU  '.date('d-m-Y',strtotime($fin)).'</b></td>
                                    </tr>
                                ';
        
                                foreach ($famille as $key => $valuefamille2){
                                        $output .= '
                                            <tr>
                                                <th colspan="6">FAMILLE ARTICLE: '.$valuefamille2['nom_fam'].'</th>
                                            </tr>
                                        ';
        
                                        $num3=0;
                                        $qtetotalstatrcad=0;
                                        $prixtstatrcad=0;
        
                                        $totalbeneficercc=0;
                                        $beneficemoyenrcc = 0;
        
                                    foreach ($statrcad as $key => $valuestatrcad){
        
                                        $puMrcc = 0;
                                        $beneficercc = 0;
        
                                        if($valuefamille2['matricule_fam'] == $valuestatrcad['famillearticle']){
        
        
                                            /**trouver le benefice moyen de chaque article debut */
        
                                                /**1: prix unitaire moyen de l'article */
                                                $puMrcc = ($valuestatrcad['prixthtarticle'] / $valuestatrcad['qtearticle']);
        
                                                /**on selectionne l'article pour avoir le prix de revient */
                                                $getsinglearticlercc=$this->stock->get_details_articles($valuestatrcad['codearticle']);
                                                if(!empty($getsinglearticlercc)){
                                                    
                                                    if($puMrcc > $getsinglearticlercc['prix_revient']){
    											      $beneficercc = ($valuestatrcad['qtearticle'] * ($puMrcc - $getsinglearticlercc['prix_revient']));  
    											    }else{
    											        $beneficercc = 0;
    											    }
    											    
                                                    //$beneficercc = ($valuestatrcad['qtearticle'] * ($puMrcc - $getsinglearticlercc['prix_revient']));
                                                }else{
                                                    $output .= '
                                                        <tr bgcolor="#CD5C5C">
                                                            <th colspan="6">ERREUR 500 survenu contactez l\'administrateur</th>
                                                        </tr>
                                                    ';
                                                }
        
                                            /**trouver le benefice moyen de chaque article fin */
                                            
                                            
                                            $num3+=1;
                                            $qtetotalstatrcad += $valuestatrcad['qtearticle'];
                                            $prixtstatrcad += $valuestatrcad['prixthtarticle'];
        
                                            $totalbeneficercc +=$beneficercc;
                                            $beneficemoyenrcc +=$beneficercc;
        
                                            $output .= '
                                                <tr>
                                                    <th>'.$num3.'</th>
                                                    <td>'.$valuestatrcad['codearticle'].'</td>
                                                    <td>'.$valuestatrcad['designationarticle'].'</td>
                                                    <td>'.$valuestatrcad['qtearticle'].'</td>
                                                    <td>'.number_format($valuestatrcad['prixthtarticle'],2,",",".").'</td>
                                                    <td>'.number_format($beneficercc,2,",",".").'</td>
                                                </tr>
                                            ';
                                        }
                                    }
                                    $output .= '
                                        <tr bgcolor="#FFFF00">
                                            <th colspan="3">TOTAL</th>
                                            <th>'.number_format($qtetotalstatrcad,2,",",".").'</th>
                                            <th>'.number_format($prixtstatrcad,2,",",".").'</th>
                                            <th>'.number_format($totalbeneficercc,2,",",".").'</th>
                                        </tr>
                                    ';
        
        
                                    $rapport3[] = array(
                                        'famille' => $valuefamille2['nom_fam'],
                                        'qtetotal' => $qtetotalstatrcad,
                                        'ca' => $prixtstatrcad,
                                        'benefice' => $beneficemoyenrcc
                                    );
                                }
        
                                if(!empty($rapport3)){
        
                                    $qtetotal3 = 0;
                                    $catotal3 = 0;
                                    $beneficetotal3 = 0;
        
                                    $output .='
                                        <tr>
                                            <th colspan="6"><h1>RAPPORT FINAL PARTIE 3</h1></th>
                                        </tr>
        
                                        <tr>
                                            <th>FAMILLE D\'ARTICLE</th>
                                            <th>QTE</th>
                                            <th>CA</th>
                                            <th>BENEFICE MOYEN</th>
                                        </tr>
                                    ';
                                    foreach ($rapport3 as $key => $valuerapport3) {
        
                                        $qtetotal3 += $valuerapport3['qtetotal'];
                                        $catotal3 += $valuerapport3['ca'];
                                        $beneficetotal3 += $valuerapport3['benefice'];
        
                                        $output .='
                                            <tr>
                                                <td>'.$valuerapport3['famille'].'</td>
                                                <td>'.$valuerapport3['qtetotal'].'</td>
                                                <td>'.number_format($valuerapport3['ca'],2,",",".").'</td>
                                                <th>'.number_format($valuerapport3['benefice'],2,",",".").'</th>
                                            </tr>
                                        ';
                                    }
                                    $totalqtefin+=$qtetotal3;
                                    $totalcafin+=$catotal3;
                                    $totalbeneffin+=$beneficetotal3;
                                    $output .='
                                        <tr>
                                            <th>TOTAL</th>
                                            <th>'.$qtetotal3.'</th>
                                            <th>'.number_format($catotal3,2,",",".").'</th>
                                            <td>'.number_format($beneficetotal3,2,",",".").'</td>
                                        </tr>
                                    ';
                                }else{
                                    $output = '<tr><td colspan="6"><b class="text-danger">...</b></td></tr>';
                                }
                            }
                            
                            
                            
                            $output .='
                                <tr>
                                    <th><h3 colspan="4">TOTAL CALCULS</h3></th>
                                </tr>
                                <tr>
                                    <th>TOTAL</th>
                                    <th>'.numberformat($totalqtefin).'</th>
                                    <th>'.numberformat($totalcafin).'</th>
                                    <td>'.numberformat($totalbeneffin).'</td>
                                </tr>
                            ';
                            
                            
                            
							
						}else{
							$output = '<tr><td colspan="6"><b class="text-danger">le système ne trouve pas les familles d\article</b></td></tr>';
						}
					}else{
						$output = '<tr><td colspan="6"><b class="text-danger">aucun article enregistrer dans le systeme</b></td></tr>';
					}
                }else{
                    $output = '<tr><td colspan="6"><b class="text-danger">Aucun article trouvé pour faire des statistiques sur la période du  '.dateformat($debut).' au  '.dateformat($fin).'</b></td></tr>';
                }
			}else{
                $output = '<tr><td colspan="6"><b class="text-danger">aucun article trouvé</b></td></tr>';
            }
			/**processus de traitement des articles les plus vendu debut */



			$array = array(
				'success'=>$output
			);
	    }else{
            $array = array(
				'error'   => true,
				'plusvenduag_error' => form_error('plusvenduag'),
				'periode_error' => form_error('debutplusv'),
                'periode_error' => form_error('finplusv'),
			);
        }
        echo json_encode($array);
	}



}