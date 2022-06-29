<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commerce extends CI_Controller {

    /**constructeur*/
	public function __construct(){
        parent::__construct();
        $this->load->model('Stock/Stock_model','stock');
        $this->load->model('Commerce/Commerce_model','commerce');
        $this->load->model('Document/Document_model','document');
        $this->load->model('Costomers/Costomer_model','costomer');
        $this->load->model('Users/Users_model','users');
    }

    /**connexion dabord */
	function logged_in(){
		if(!session('users') && $this->router->{'class'} !== 'index'){
			flash("warning","merci de te connecter");
			redirect('login');
		}
	}
    
    /**********************gestion des factures direct(pas de prêt)debut ************************/

    /**affiche la page de facturation debut */
    public function facturer(){
        $this->logged_in();
        
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');
		
            /**informations utile */
            $matricule = session('users')['matricule'];
            $agence = session('users')['matricule_ag'];
            
            if(!empty(session('users')['nom_emp'])){
                /** selectionne les depots en fonctions de l'entreprise et ou de l'agence*/
                $data['depots'] = $this->commerce->getalldepot($matricule,$agence);

                /**affiche la liste des clients d'une entreprise */
                $data['clients'] = $this->commerce->all_client($matricule);

                /**affiche les vendeur: c'est la personne connecté au momement de la vente */
                $data['vendeur'][] = array(
                    'matricule' => session('users')['matricule_emp'],
                    'nom' => session('users')['nom_emp']
                );

                /**affiche la liste des caisse en fonction de l'entreprise et ou de l'agence */
                $data['caisses'] = $this->commerce->allcaisseagence($matricule,$agence);
                
                /**liste des articles de l'entreprise*/
                $data['articles'] = $this->commerce->all_article_entreprise($matricule);

                /**liste des taxe */
                $data['taxes'] = $this->commerce->all_taxe_entreprise($matricule);

                /**affiche la liste des type de documents pour une entreprise donné */
                $data['docs'] = $this->commerce->type_doc($matricule);
                $this->load->view('commerce/facturer',$data);
            }else{
                flash('warning','Connecte toi en tant que utilisateur pour faire cette opération');
                redirect('home'); 
            }
        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }
    /**affiche la page de facturation fin */

    /**affiche les documents de vente RT non cloturer et le nouveau document */
    public function docs_vente_nonclo(){
        $this->logged_in();
        /**informations utile */
        $matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];
        $outputs = "";  

        $codedoc = 'RT-DOC-'.code(10);
        $nomdoc = 'RT-DOCUMENT-TKT-'.code(10).'-'.date('d-m-Y');
        /**afficher le code et le nom d'un document générer*/
        $output = '<option value='.$codedoc.'>'.$nomdoc.'</option>'; 

        /**liste des documents d'une entreprise et ou d'une agence en fonction ainsi que le client et le montant */
        $abrev = 'RT';
        $docus = $this->commerce->docu_cli_en_ag($matricule,$agence,$abrev);
        if(!empty($docus)){ 
            foreach($docus as $key=>$value){
                $outputs .= '<option value='.$value['code_document'].'>'.$value['code_document'].' / '.$value['nom_cli'].' / '.$value['pt_ttc_document'].'</option>'; 
            }
        }
        $array = array(
            'ndocu' => '<option value='.$codedoc.'>'.$nomdoc.'</option>',
            'exdocu' => $outputs
        );
        echo json_encode($array);
    }

    /**affiche le document pour modification dans le formualaire debut */
    public function show_edit_docs(){
        $this->logged_in();

        /**informations utile */
        $matricule = session('users')['matricule'];
        $code_cod = $this->input->post('code_doc');

        /**
         * je selectionne le document en fonction de:
         * 1: le type, 
         * 2: le client
         * 3: depot
         * 4: caisse
         * 5: commercial
         * 6: document
         * 7: les taxes
        */

        /**document en fonction du code et de l'entreprise */
        $docs = $this->commerce->docventeclientcommercial($code_cod,$matricule);
        $output =''; $artdoc = ''; $irres ='';
        $outputs =''; $tvares = '';
        if(!empty($docs)){
            if($docs['cloturer'] == 0){
                $outputs = $docs;
                $output .= "trouver";
                
                $tva = !empty($docs['pt_ttc_document'])?($docs['pt_ttc_document'] - $docs['pt_ht_document']):0;
                $ir = !empty($docs['pt_net_document'])?($docs['pt_net_document'] - $docs['pt_ht_document']):0;

                $tva = ($tva == 0)?0:19.25;
                $ir = ($ir == 0)?0:-2.2;

                /**liste des articles du document */
                $artdoc = $this->art_doc($code_cod,$matricule,$tva,$ir);

                if($tva == 0){$tvares='';}else{$tvares='checked';}
                if($ir == 0){$irres='';}else{$irres='checked';}
            }else{
                $output .= "OUPS, cette facture est déjà cloturer";
            }
        }else{
            $output .= "le systèmene trouve pas le document";
        }

        $array = array(
            'success' => $output,
            'datas' => $outputs,
            'art_doc' => $artdoc,
            'tva'=>$tvares,
            'ir'=>$irres
        );
        echo json_encode($array);
    }
    /**affiche le document pour modification dans le formualaire fin */



    /** ================ AFFICHE LE FORMULAIRE DE FACTURATION DIRECT DEBUT =================== */
    public function form_facturation(){
        $this->logged_in();

        /**informations utile */
        $matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];
        $this->form_validation->set_rules('tva', 'choisi la tva', 'regex_match[/^[a-z\ ]+$/]',array(
            //'required' => '%s.',  /*required|*/
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('ir', 'choisi la ir', 'regex_match[/^[a-z\ ]+$/]',array(
            //'required' => '%s.',  /*required|*/
            'regex_match' => 'caractère non pris en compte'
            )
        );
        $this->form_validation->set_rules('tdocument', 'choisi l\'article', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('depot', 'choisi le depot', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('client', 'choisi le client', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('caisse', 'choisi la caisse', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('commercial', 'choisi le commercial ou vendeur', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('document', 'choisi le document', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('article', 'choisi l\'article', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );
            

        if($this->form_validation->run()){
            
                
            /**assurons nous que pour le depot choisi, l'article soit au préalable en stock */
            $tva = trim($this->input->post('tva'));
            $ir = trim($this->input->post('ir'));
            $code_type_document = trim($this->input->post('tdocument'));
            $depot = trim($this->input->post('depot'));
            $code_client = trim($this->input->post('client'));
            $code_caisse = trim($this->input->post('caisse'));
            $code_commercial = trim($this->input->post('commercial'));
            $code_document = trim($this->input->post('document'));
            $code_art = trim($this->input->post('article'));
        
            $matricule_en = session('users')['matricule'];

            /**verifier de quel type de document il s'agit*/
            $type_document = $this->type_document($code_type_document, $matricule);
            if(!empty($type_document)){
                if($type_document == 'RT'){
                    $art_stock_depot = $this->stock->get_art_stock($code_art,$depot);
                    if(!empty($art_stock_depot)){

                        $infos = array(
                            'tva' => $tva,
                            'ir' => $ir,
                            'tdoc' => $code_type_document,
                            'depot' => $depot, 
                            'client' => $code_client,
                            'caisse' => $code_caisse,
                            'commercial' => $code_commercial,
                            'document' => $code_document,
                            'article' => $code_art
                        );

                        /**selectionne les taxes */
                        $prixht = 1;
                        $qte = 1;
                        $taxe = $this->taxe($tva,$ir,$matricule_en,$prixht,$qte);

                        /**liste des articles du document */
                        $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);

                        $array = array(
                            'form'   => 'okk... '.$taxe['percenttva'].' '.$taxe['percentir'],
                            'article' => $art_stock_depot,
                            'autres' => $infos,
                            'art_doc' => $output
                        );
                        
                    }else{
                        $array = array(
                            'success'   => '
                                <script>
                                    swal.fire("OUPS!","cet article n\'est pas en stock pour le depot choisi, procedez avant à son entré en stock","error")
                                </script>
                            '
                        );
                    }
                }else{
                    $array = array(
                        'success'   => '
                            <script>
                                swal.fire("OUPS!","ce type de document n\'est pas utile ici... utilise le menu de gauche","error")
                            </script>
                        '
                    );  
                }
            }else{
                $array = array(
                    'success'   => '
                        <script>
                            swal.fire("OUPS!","le système ne trouve pas ce type de document","error")
                        </script>
                    '
                );  
            }
                
            

        }else{
            $array = array(
                'error'   => true,
                //'modepayement_error' => form_error('modepayement'),
                //'accordpayement_error' => form_error('accordpayement'),
                'tva_error' => form_error('tva'),
                'ir_error' => form_error('ir'),
                'depot_error' => form_error('depot'),
                'client_error' => form_error('client'),
                'caisse_error' => form_error('caisse'),
                'commercial_error' => form_error('commercial'),
                'document_error' => form_error('document'),
                'article_error' => form_error('article'),
                'tdocument_error' => form_error('tdocument')
            );
        }
        echo json_encode($array);
    }
    /** ================ AFFICHE LE FORMULAIRE DE FACTURATION DIRECT FIN =================== */



    /** calculer et afficher le prix total hors taxe en fonction de la quantité d'un produit dans un depot debut*/
    public function totalhorstaxe(){
        $this->logged_in();

		$qte = $this->input->post('qte');
		$prixh = $this->input->post('prixh');
        if(!empty($qte)){
            if(preg_match('/^[0-9\ ]+$/',$qte) && preg_match('/^[0-9\ ]+$/',$prixh)){
                $code_article = $this->input->post('article');
                $code_depot = $this->input->post('depot');
                
                $article = $this->stock->get_article_stock($code_article,$code_depot);
                if(!empty($article)){
                    if($prixh == $article['prix_hors_taxe']){
                        if($qte > $article['quantite']){
                            $array = array(
                                'total' => 'quantité en stock insuffisante pour ce depot'
                            );
                        }else{
                            $array = array(
                                'total' => ($article['prix_hors_taxe'] * $qte)
                            );
                        }
                    }else if($prixh != $article['prix_hors_taxe']){
                        if($qte > $article['quantite']){
                            $array = array(
                                'total' => 'quantité en stock insuffisante pour ce depot'
                            );
                        }else{
                            $array = array(
                                'total' => ($prixh * $qte)
                            );
                        }
                    }
                }else{
                    $array = array(
                        'total' => ''
                    );	
                }
                
            }else{
                $array = array(
                    'total' => 'mauvais caractère'
                );	
            }
        }else{
            $array = array(
                'total' => ''
            ); 
        }
		echo json_encode($array); 
    }
    /** calculer et afficher le prix total hors taxe en fonction de la quantité d'un produit dans un depot fin*/

    /**operation sur la création d'une facture ticket debut */
    public function operationfacturetiket(){
        $this->logged_in();

        /**message d'erreur (connexion a la base des données perdu) debut*/
            $message_db = 	array(
                'success'   => '
                    <script>
                        swal.fire("OUPS!","connexion à la base des données perdu","info")
                    </script>
                '
            );
        /**message d'erreur (connexion a la base des données perdu) fin*/

        $this->form_validation->set_rules('quantes', 'quantité', 'required|regex_match[/^[0-9]+$/]',array(
			'required' => 'entre la %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));
		
		$this->form_validation->set_rules('prix_h', 'prix ht', 'required|is_natural',array(
			'required' => 'le %s ne doit pas être vide',
			'is_natural' => 'caractère(s) non autorisé(s)'
		));

        if($this->form_validation->run()){

            $output = "";
			$tva = trim($this->input->post('tva_doc'));
            $ir = trim($this->input->post('ir_doc'));
            $code_type_document = trim($this->input->post('t_doc'));
            $code_depot = trim($this->input->post('dep'));
            $code_client = trim($this->input->post('cli'));
            $code_caisse = trim($this->input->post('caiss'));
            $code_vendeur = trim($this->input->post('vendeur'));
            $code_document = trim($this->input->post('doc_m'));
			$code_article = trim($this->input->post('art'));
            $qte = trim($this->input->post('quantes'));
            $description_art = trim($this->input->post('description_art'));

            $prixh = trim($this->input->post('prix_h'));

			$matricule_en = session('users')['matricule'];
			$matricule_emp = session('users')['matricule_emp'];
            $agencecon = session('users')['matricule_ag'];
            
            /*selectionnons le code de l'agence à partir de la caisse si l'agence est vide*/
            $agencecaisse = $this->commerce->agencecaisse($code_caisse,$matricule_en);
            $agence = empty($agencecon)?$agencecaisse['code_agence']:$agencecon;
            
            
            /**effectuer l'opération selon selon le type de document et le code de l'entreprise, du pht et qte*/
			if(!empty($code_type_document) && !empty($matricule_en)){
                /**verifier de quel type de document il s'agit*/
                $type_document = $this->type_document($code_type_document, $matricule_en);
                if(!empty($type_document)){
                    if($type_document == 'RT'){
                        
                        /*on selectionne l'article dans le depot choisi pour l'entreprise encour*/
                        $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
                        if(!empty($art_stock_depot)){
                            
                            /*on verifie si le pht est >= au prix defini, si non on effectue une verification d'indentité*/
                            if($prixh >= $art_stock_depot['prix_hors_taxe']){
                                /*on s'assure que la qte est en stock pour l'article choisi*/
                                if($qte <= $art_stock_depot['quantite']){
    
                                    /**on test pour voir si le document existe déjà ou pas */
                                    $verify_document = $this->stock->doc_stock($code_type_document, $code_document, $matricule_en);
                                    if(empty($verify_document)){
                                        /***************************************** LE DOCUMENT N'EXISTE PAS DEBUT *************************************** */
                                        
                                        /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                        $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);

                                        /**on creer le document */
                                        $input_doc = array(
                                            'code_document'=>$code_document,
                                            'nom_document'=> 'RT-DOCUMENT-TKT-'.code(10).'-'.date('d-m-Y'),
                                            'date_creation_doc'=>dates(),
                                            'code_type_doc'=> $code_type_document,
                                            'depot_doc'=> $code_depot,
                                            'code_client_document'=> $code_client,
                                            'code_caisse'=> $code_caisse,
                                            'code_agence_doc' => $agence,
                                            'pt_ht_document'=> ($prixh * $qte),
                                            'pt_ttc_document'=> $taxe['prixttc'],
                                            'pt_net_document'=> $taxe['prixnet'],
                                            'cloturer'=>0,
                                            'code_employe'=> $matricule_emp,
                                            'code_entreprie'=> $matricule_en,
                                        );
                                        $new_document = $this->stock->new_document($input_doc);
                                        if($new_document){
                                            /**on enrégistre l'historique des articles de ce document dans la table article_document*/
                                            $input_art_doc = array(
                                                'code_article'=> $code_article,
                                                'code_document'=> $code_document,
                                                'quantite'=> $qte,
                                                'pu_HT'=> $prixh,
                                                'pt_HT'=> ($prixh * $qte),
                                                'code_emp_art_doc'=> $matricule_emp,
                                                'code_en_art_doc'=> $matricule_en,
                                                'description_art' => $description_art,
                                                'date_creer_art_doc'=> dates()
                                            );
                                            $save_art_from_doc = $this->stock->article_document($input_art_doc);
                                            if($save_art_from_doc){
                                                /**gestion du stock debut */
                                                $input_stock_val = array(
    												'code_employe'=> $matricule_emp,
    												'code_depot'=> $code_depot,
    												'code_entreprise'=> $matricule_en,
    												'quantite'=> (abs(($art_stock_depot['quantite'] - $qte))),
    												'date_modifier_stock'=> dates()
    											);
                                                $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
    											if($update_art_st){
    												/**liste des articles du document */
    												$output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
    												$array = array(
    													'success' => 'article ajouter au panier',
    													'art_doc' => $output
    												);
    											}else{
    												$array = $message_db;
    											}
    
                                                /**gestion du stock fin */
                                            }else{
                                                $array = $message_db;
                                            }
                                        }else{
                                            $array = $message_db;
                                        }
                                        /***************************************** LE DOCUMENT N'EXISTE PAS FIN *************************************** */
                                    }else{
                                        /***************************************** LE DOCUMENT EXISTE DEBUT *************************************** */
    
                                        /**on remet ceci ici pour s'assurer de la qualité de l'information */
                                        $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
    
                                        /**calcul du prixtotalttc en fonction de la tva et de la ir debut*/
                                        $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);
                                        /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
    
                                        /**pour le document encour, on verifi s'il contient l'article encour */
                                        $verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
                                        if(empty($verify_art_document)){
                                            /******************** l'article n'existe pas dans le document debut *******************/
                                            
                                            /**on selectionne le document en question  */
                                            $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                            if(!empty($document)){   
                                                $input_doc = array(
                                                    'date_modifier_doc' =>dates(),
                                                    'pt_ht_document' => ($document['pt_ht_document'] + ($prixh * $qte)),
                                                    'pt_ttc_document' => ($document['pt_ttc_document'] + $taxe['prixttc']),
                                                    'pt_net_document' => ($document['pt_net_document'] + $taxe['prixnet']),
                                                    'code_employe' => $matricule_emp,
                                                    'code_entreprie' => $matricule_en,
                                                );
                                                
                                                $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                if($update_doc){
    
                                                     /**on enrégistre l'historique des articles de ce document dans la table article_document*/
                                                    $input_art_doc = array(
                                                        'code_article'=> $code_article,
                                                        'code_document'=> $code_document,
                                                        'quantite'=> $qte,
                                                        'pu_HT'=> $prixh,
                                                        'pt_HT'=> ($prixh * $qte),
                                                        'code_emp_art_doc'=> $matricule_emp,
                                                        'code_en_art_doc'=> $matricule_en, 
                                                        'description_art' => $description_art,
                                                        'date_creer_art_doc'=> dates()
                                                    );
                                                    $save_art_from_doc = $this->stock->article_document($input_art_doc);
                                                    if($save_art_from_doc){
                                                        /**gestion du stock debut */
                                                        $input_stock_val = array(
                                                            'code_employe'=> $matricule_emp,
                                                            'code_depot'=> $code_depot,
                                                            'code_entreprise'=> $matricule_en,
                                                            'quantite'=> (abs(($art_stock_depot['quantite'] - $qte))),
                                                            'date_modifier_stock'=> dates()
                                                        );
                                                        $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
                                                        if($update_art_st){
                                                            /**liste des articles du document */
                                                            $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                            $array = array(
                                                                'success' => 'article ajouter au panier',
                                                                'art_doc' => $output
                                                            );
                                                        }else{
                                                            $array = $message_db;
                                                        }
                                                        /**gestion du stock fin */
                                                    }else{
                                                        $array = $message_db;
                                                    }
                                                }else{
                                                    $array = $message_db;
                                                }
    
                                            }else{
                                                $array = $message_db;
                                            }
                                            /*******************************l'article n'existe pas dans le document fin *******************/
                                        }else{
                                            /*******************************l'article existe  dans le document debut *******************/
                                            
                                            /**on remet ceci ici pour s'assurer de la qualité de l'information */
                                            $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
    
                                            /**on renvoit la quantité prélévé précedement en stock pour le depot choisi*/
                                            $qte_initial = ($verify_art_document['quantite'] + $art_stock_depot['quantite']);
                                            $input_stock_val = array(
                                                'code_employe'=> $matricule_emp,
                                                'code_depot'=> $code_depot,
                                                'code_entreprise'=> $matricule_en,
                                                'quantite'=>  $qte_initial,
                                                'date_modifier_stock'=> dates()
                                            );
                                            
                                           $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
    										if($update_art_st){
    
                                                
                                                /**on selectionne le document en question */
                                                $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                                if(!empty($document)){
                                                    
                                                    /**on modifie le document
                                                     * 1: on enleve le prix total ht de l'article sur le prix total ht du document
                                                     * 2: on enleve le prix total ttc de l'article sur le prix total ttc du document
                                                    */
    
                                                    /** selectionne le pourcentage de la tva dans la table taxe*/
                                                    $pourcentagetva = $this->commerce->specifictaxe($tva,$matricule_en);
                                                    $action1 = (!empty($pourcentagetva))?$pourcentagetva['pourcentage'] : 0;
    
                                                    //** selectionne le pourcentage de la ir dans la table taxe*
                                                    $pourcentageir = $this->commerce->specifictaxe($ir,$matricule_en);
                                                    $action2 = (!empty($pourcentageir))?$pourcentageir['pourcentage'] : 0;
    
                                                    /**taxes et prix ttc de l'article debut */
                                                    $tva_art = (($action1 * $verify_art_document['pt_HT'])/100);
                                                    $ir_art = (($action2 * $verify_art_document['pt_HT'])/100);
                                                    $prix_ttc_art =  ($verify_art_document['pt_HT'] + $tva_art);
                                                    $prix_net_art =  ($verify_art_document['pt_HT'] + ($ir_art));
                                                    /**taxes et prix ttc de l'article fint */
    
                                                    $input_doc = array(
                                                        'date_modifier_doc'=>dates(),
                                                        'pt_ht_document'=> ($document['pt_ht_document'] - $verify_art_document['pt_HT']),
                                                        'pt_ttc_document'=> ($document['pt_ttc_document'] - $prix_ttc_art),
                                                        'pt_net_document'=> ($document['pt_net_document'] - $prix_net_art),
                                                        'code_employe'=> $matricule_emp,
                                                        'code_entreprie'=> $matricule_en,
                                                    );
                                                    
                                                    $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                    if($update_doc){
    
                                                        /**on remet ici pour rafraichir les informations */
                                                        $document = $this->commerce->specificdocument($code_document,$matricule_en);
    
                                                        /**on remet ici pour rafraichir les informations */
                                                        $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
    
                                                        /**une fois que tout est en ordre, on met ajour le document avec les nouvelles valeurs */
                                                        $input_doc = array(
                                                            'date_modifier_doc'=>dates(),
                                                            'pt_ht_document'=> ($document['pt_ht_document'] + ($prixh * $qte)),
                                                            'pt_ttc_document'=> ($document['pt_ttc_document'] + $taxe['prixttc']),
                                                            'pt_net_document'=> ($document['pt_net_document'] + $taxe['prixnet']),
                                                            'code_employe'=> $matricule_emp,
                                                            'code_entreprie'=> $matricule_en,
                                                        );
                
                                                        $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                        if($update_doc){
    
                                                            /**une fois le document mis a jour, on modifi l'article dans le document en question */
                                                            /**on modifie l'article encour dans le document encour (table article_document)*/
                                                            
                                                            $input_art_doc = array(
                                                                'code_document'=> $code_document,
                                                                'quantite'=> $qte,
                                                                'pu_HT'=> $prixh,
                                                                'pt_HT'=> ($prixh * $qte),
                                                                'code_emp_art_doc'=> $matricule_emp,
                                                                'description_art' => $description_art,
                                                                'code_en_art_doc'=> $matricule_en, 
                                                                'date_creer_art_doc'=> dates()
                                                            );
                                                            $update_art_document = $this->stock->update_article_document($code_document,$code_article,$input_art_doc);
                                                            if($update_art_document){
                                                                /**gestion du stock debut */
                                                                $input_stock_val = array(
                                                                    'code_employe'=> $matricule_emp,
                                                                    'code_depot'=> $code_depot,
                                                                    'code_entreprise'=> $matricule_en,
                                                                    'quantite'=> (abs(($art_stock_depot['quantite'] - $qte))),
                                                                    'date_modifier_stock'=> dates()
                                                                );
                                                                $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
                                                                if($update_art_st){
                                                                    /**liste des articles du document */
                                                                    $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                                    $array = array(
                                                                        'success' => 'article ajouter au panier',
                                                                        'art_doc' => $output
                                                                    );
                                                                }else{
                                                                    $array = $message_db;
                                                                }
                                                                /**gestion du stock fin */
                                                            }else{
                                                                $array = $message_db;
                                                            }
                                                        }else{
                                                            $array = $message_db;
                                                        }
                                                    }else{
                                                        $array = $message_db;
                                                    }
                                                }else{
                                                    $array = $message_db;
                                                }
                                            }else{
                                                $array = $message_db;
                                            }
                                            /*******************************l'article existe  dans le document fin *******************/
                                        }
                                            
                                        
    
                                        /***************************************** LE DOCUMENT EXISTE FIN *************************************** */
                                    }
                                    
                                }else{
                                    $array = array(
                                        'success'   => '
                                            <script>
                                                swal.fire("OUPS!","quantité en stock insuffisante pour ce depot","error")
                                            </script>
                                        '
                                    );
                                }
                            }else if($prixh < $art_stock_depot['prix_hors_taxe']){
                                $this->form_validation->set_rules('login', 'login', 'required',array(
                        			'required' => 'le %s est necessaire',
                        		));
                        		$this->form_validation->set_rules('pass', 'mot de passe', 'required',array(
                        			'required' => 'le %s est necessaire',
                        		));
                                
                                if($this->form_validation->run()){
                                
                                    $user = trim($this->input->post('login'));
                                    $pass = trim($this->input->post('pass'));
                                    
                                    /**login employé */
                                    $login = $this->users->login_emp1($user);
                                    if($login){
                                        if(password_verify($pass, $login['password_emp'])){
                                            if($login['mat_ag'] == "" || $login['mat_serv']==""){
                                                $array = array(
                                                    'show'   => 'employé trouvé'
                                                ); 
                                                
                                                /*************==============================================================*******/
                                                
                                                    /*on s'assure que la qte est en stock pour l'article choisi*/
                                                    if($qte <= $art_stock_depot['quantite']){
                        
                                                        /**on test pour voir si le document existe déjà ou pas */
                                                        $verify_document = $this->stock->doc_stock($code_type_document, $code_document, $matricule_en);
                                                        if(empty($verify_document)){
                                                            /***************************************** LE DOCUMENT N'EXISTE PAS DEBUT *************************************** */
                                                            
                                                            /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                                            $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);

                                                            /**on creer le document */
                                                            $input_doc = array(
                                                                'code_document'=>$code_document,
                                                                'nom_document'=> 'RT-DOCUMENT-TKT-'.code(10).'-'.date('d-m-Y'),
                                                                'date_creation_doc'=>dates(),
                                                                'code_type_doc'=> $code_type_document,
                                                                'depot_doc'=> $code_depot,
                                                                'code_client_document'=> $code_client,
                                                                'code_caisse'=> $code_caisse,
                                                                'code_agence_doc' => $agence,
                                                                'pt_ht_document'=> ($prixh * $qte),
                                                                'pt_ttc_document'=> $taxe['prixttc'],
                                                                'pt_net_document'=> $taxe['prixnet'],
                                                                'cloturer'=>0,
                                                                'code_employe'=> $matricule_emp,
                                                                'code_entreprie'=> $matricule_en,
                                                            );
                                                            $new_document = $this->stock->new_document($input_doc);
                                                            if($new_document){
                                                                /**on enrégistre l'historique des articles de ce document dans la table article_document*/
                                                                $input_art_doc = array(
                                                                    'code_article'=> $code_article,
                                                                    'code_document'=> $code_document,
                                                                    'quantite'=> $qte,
                                                                    'pu_HT'=> $prixh,
                                                                    'pt_HT'=> ($prixh * $qte),
                                                                    'code_emp_art_doc'=> $matricule_emp,
                                                                    'code_en_art_doc'=> $matricule_en,
                                                                    'description_art' => $description_art,
                                                                    'date_creer_art_doc'=> dates()
                                                                );
                                                                $save_art_from_doc = $this->stock->article_document($input_art_doc);
                                                                if($save_art_from_doc){
                                                                    /**gestion du stock debut */
                                                                    $input_stock_val = array(
                                                                        'code_employe'=> $matricule_emp,
                                                                        'code_depot'=> $code_depot,
                                                                        'code_entreprise'=> $matricule_en,
                                                                        'quantite'=> (abs(($art_stock_depot['quantite'] - $qte))),
                                                                        'date_modifier_stock'=> dates()
                                                                    );
                                                                    $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
                                                                    if($update_art_st){
                                                                        /**liste des articles du document */
                                                                        $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                                        $array = array(
                                                                            'success' => 'article ajouter au panier',
                                                                            'art_doc' => $output
                                                                        );
                                                                    }else{
                                                                        $array = $message_db;
                                                                    }
                        
                                                                    /**gestion du stock fin */
                                                                }else{
                                                                    $array = $message_db;
                                                                }
                                                            }else{
                                                                $array = $message_db;
                                                            }
                                                            /***************************************** LE DOCUMENT N'EXISTE PAS FIN *************************************** */
                                                        }else{
                                                            /***************************************** LE DOCUMENT EXISTE DEBUT *************************************** */
                        
                                                            /**on remet ceci ici pour s'assurer de la qualité de l'information */
                                                            $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
                        
                                                            /**calcul du prixtotalttc en fonction de la tva et de la ir debut*/
                                                            $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);
                                                            /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                        
                                                            /**pour le document encour, on verifi s'il contient l'article encour */
                                                            $verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
                                                            if(empty($verify_art_document)){
                                                                /******************** l'article n'existe pas dans le document debut *******************/
                                                                
                                                                /**on selectionne le document en question  */
                                                                $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                                                if(!empty($document)){   
                                                                    $input_doc = array(
                                                                        'date_modifier_doc' =>dates(),
                                                                        'pt_ht_document' => ($document['pt_ht_document'] + ($prixh * $qte)),
                                                                        'pt_ttc_document' => ($document['pt_ttc_document'] + $taxe['prixttc']),
                                                                        'pt_net_document' => ($document['pt_net_document'] + $taxe['prixnet']),
                                                                        'code_employe' => $matricule_emp,
                                                                        'code_entreprie' => $matricule_en,
                                                                    );
                                                                    
                                                                    $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                                    if($update_doc){
                        
                                                                        /**on enrégistre l'historique des articles de ce document dans la table article_document*/
                                                                        $input_art_doc = array(
                                                                            'code_article'=> $code_article,
                                                                            'code_document'=> $code_document,
                                                                            'quantite'=> $qte,
                                                                            'pu_HT'=> $prixh,
                                                                            'pt_HT'=> ($prixh * $qte),
                                                                            'code_emp_art_doc'=> $matricule_emp,
                                                                            'code_en_art_doc'=> $matricule_en, 
                                                                            'description_art' => $description_art,
                                                                            'date_creer_art_doc'=> dates()
                                                                        );
                                                                        $save_art_from_doc = $this->stock->article_document($input_art_doc);
                                                                        if($save_art_from_doc){
                                                                            /**gestion du stock debut */
                                                                            $input_stock_val = array(
                                                                                'code_employe'=> $matricule_emp,
                                                                                'code_depot'=> $code_depot,
                                                                                'code_entreprise'=> $matricule_en,
                                                                                'quantite'=> (abs(($art_stock_depot['quantite'] - $qte))),
                                                                                'date_modifier_stock'=> dates()
                                                                            );
                                                                            $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
                                                                            if($update_art_st){
                                                                                /**liste des articles du document */
                                                                                $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                                                $array = array(
                                                                                    'success' => 'article ajouter au panier',
                                                                                    'art_doc' => $output
                                                                                );
                                                                            }else{
                                                                                $array = $message_db;
                                                                            }
                                                                            /**gestion du stock fin */
                                                                        }else{
                                                                            $array = $message_db;
                                                                        }
                                                                    }else{
                                                                        $array = $message_db;
                                                                    }
                        
                                                                }else{
                                                                    $array = $message_db;
                                                                }
                                                                /*******************************l'article n'existe pas dans le document fin *******************/
                                                            }else{
                                                                /*******************************l'article existe  dans le document debut *******************/
                                                                
                                                                /**on remet ceci ici pour s'assurer de la qualité de l'information */
                                                                $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
                        
                                                                /**on renvoit la quantité prélévé précedement en stock pour le depot choisi*/
                                                                $qte_initial = ($verify_art_document['quantite'] + $art_stock_depot['quantite']);
                                                                $input_stock_val = array(
                                                                    'code_employe'=> $matricule_emp,
                                                                    'code_depot'=> $code_depot,
                                                                    'code_entreprise'=> $matricule_en,
                                                                    'quantite'=>  $qte_initial,
                                                                    'date_modifier_stock'=> dates()
                                                                );
                                                                
                                                            $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
                                                                if($update_art_st){
                        
                                                                    
                                                                    /**on selectionne le document en question */
                                                                    $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                                                    if(!empty($document)){
                                                                        
                                                                        /**on modifie le document
                                                                        * 1: on enleve le prix total ht de l'article sur le prix total ht du document
                                                                        * 2: on enleve le prix total ttc de l'article sur le prix total ttc du document
                                                                        */
                        
                                                                        /** selectionne le pourcentage de la tva dans la table taxe*/
                                                                        $pourcentagetva = $this->commerce->specifictaxe($tva,$matricule_en);
                                                                        $action1 = (!empty($pourcentagetva))?$pourcentagetva['pourcentage'] : 0;
                        
                                                                        //** selectionne le pourcentage de la ir dans la table taxe*
                                                                        $pourcentageir = $this->commerce->specifictaxe($ir,$matricule_en);
                                                                        $action2 = (!empty($pourcentageir))?$pourcentageir['pourcentage'] : 0;
                        
                                                                        /**taxes et prix ttc de l'article debut */
                                                                        $tva_art = (($action1 * $verify_art_document['pt_HT'])/100);
                                                                        $ir_art = (($action2 * $verify_art_document['pt_HT'])/100);
                                                                        $prix_ttc_art =  ($verify_art_document['pt_HT'] + $tva_art);
                                                                        $prix_net_art =  ($verify_art_document['pt_HT'] + ($ir_art));
                                                                        /**taxes et prix ttc de l'article fint */
                        
                                                                        $input_doc = array(
                                                                            'date_modifier_doc'=>dates(),
                                                                            'pt_ht_document'=> ($document['pt_ht_document'] - $verify_art_document['pt_HT']),
                                                                            'pt_ttc_document'=> ($document['pt_ttc_document'] - $prix_ttc_art),
                                                                            'pt_net_document'=> ($document['pt_net_document'] - $prix_net_art),
                                                                            'code_employe'=> $matricule_emp,
                                                                            'code_entreprie'=> $matricule_en,
                                                                        );
                                                                        
                                                                        $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                                        if($update_doc){
                        
                                                                            /**on remet ici pour rafraichir les informations */
                                                                            $document = $this->commerce->specificdocument($code_document,$matricule_en);
                        
                                                                            /**on remet ici pour rafraichir les informations */
                                                                            $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
                        
                                                                            /**une fois que tout est en ordre, on met ajour le document avec les nouvelles valeurs */
                                                                            $input_doc = array(
                                                                                'date_modifier_doc'=>dates(),
                                                                                'pt_ht_document'=> ($document['pt_ht_document'] + ($prixh * $qte)),
                                                                                'pt_ttc_document'=> ($document['pt_ttc_document'] + $taxe['prixttc']),
                                                                                'pt_net_document'=> ($document['pt_net_document'] + $taxe['prixnet']),
                                                                                'code_employe'=> $matricule_emp,
                                                                                'code_entreprie'=> $matricule_en,
                                                                            );
                                    
                                                                            $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                                            if($update_doc){
                        
                                                                                /**une fois le document mis a jour, on modifi l'article dans le document en question */
                                                                                /**on modifie l'article encour dans le document encour (table article_document)*/
                                                                                
                                                                                $input_art_doc = array(
                                                                                    'code_document'=> $code_document,
                                                                                    'quantite'=> $qte,
                                                                                    'pu_HT'=> $prixh,
                                                                                    'pt_HT'=> ($prixh * $qte),
                                                                                    'code_emp_art_doc'=> $matricule_emp,
                                                                                    'description_art' => $description_art,
                                                                                    'code_en_art_doc'=> $matricule_en, 
                                                                                    'date_creer_art_doc'=> dates()
                                                                                );
                                                                                $update_art_document = $this->stock->update_article_document($code_document,$code_article,$input_art_doc);
                                                                                if($update_art_document){
                                                                                    /**gestion du stock debut */
                                                                                    $input_stock_val = array(
                                                                                        'code_employe'=> $matricule_emp,
                                                                                        'code_depot'=> $code_depot,
                                                                                        'code_entreprise'=> $matricule_en,
                                                                                        'quantite'=> (abs(($art_stock_depot['quantite'] - $qte))),
                                                                                        'date_modifier_stock'=> dates()
                                                                                    );
                                                                                    $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
                                                                                    if($update_art_st){
                                                                                        /**liste des articles du document */
                                                                                        $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                                                        $array = array(
                                                                                            'success' => 'article ajouter au panier',
                                                                                            'art_doc' => $output
                                                                                        );
                                                                                    }else{
                                                                                        $array = $message_db;
                                                                                    }
                                                                                    /**gestion du stock fin */
                                                                                }else{
                                                                                    $array = $message_db;
                                                                                }
                                                                            }else{
                                                                                $array = $message_db;
                                                                            }
                                                                        }else{
                                                                            $array = $message_db;
                                                                        }
                                                                    }else{
                                                                        $array = $message_db;
                                                                    }
                                                                }else{
                                                                    $array = $message_db;
                                                                }
                                                                /*******************************l'article existe  dans le document fin *******************/
                                                            }
                                                                
                                                            
                        
                                                            /***************************************** LE DOCUMENT EXISTE FIN *************************************** */
                                                        }
                                                        
                                                    }else{
                                                        $array = array(
                                                            'success'   => '
                                                                <script>
                                                                    swal.fire("OUPS!","quantité en stock insuffisante pour ce depot","error")
                                                                </script>
                                                            '
                                                        );
                                                    }
                                                
                                                /*************==============================================================*******/
                                            }else{
                                                $array = array(
                                                    'show'   => 'vous n\'êtes pas autoriser a éffectué cette opération'
                                                );  
                                            }
                                        }else{
                                           $array = array(
                                                'show'   => 'mot de passe incorrect ou mauvais... reessai'
                                            ); 
                                        }
                                    }else{
                                       $array = array(
                                            'show' => 'login incorrect ou inexistant'
                                        ); 
                                    }
                                }else{
                        			$array = array(
                        				'error'   => true,
                        				'login_error' => form_error('login'),
                        				'pass_error' => form_error('pass')
                        			);
                        		}
                                  
                            }
                        }else{
                            $array = array(
                                'success'   => '
                                    <script>
                                        swal.fire("OUPS!","cet article n\'est pas en stock pour le depot choisi, procedez avant à son entré en stock","error")
                                    </script>
                                '
                            );
                        }
                    }else{
                        $array = array(
                            'success'   => '
                                <script>
                                    swal.fire("OUPS!","ce type de document n\'est pas utile ici... utilise le menu de gauche","error")
                                </script>
                            '
                        );  
                    }
                }else{
                    $array = array(
                        'success'   => '
                            <script>
                                swal.fire("OUPS!","le système ne trouve pas ce type de document","error")
                            </script>
                        '
                    );  
                }
            }
        }else{
			$array = array(
				'error'   => true,
				'quantes_error' => form_error('quantes'),
				'prix_h_error' => form_error('prix_h')
			);
		}

		echo json_encode($array);
    }
    /**operation sur la création d'une facture ticket fin */
    
    /**cloturer une facture en foction du code de l'entreprise et celui du document debut */
    public function cloturer_document(){
        $this->logged_in();
        $document = $this->input->post('doc');
        $matricule_en = session('users')['matricule'];
        if(!empty($document)){
            $input = array(
                'cloturer'=> 1,
            );

            /**update document pour mettre le statut a cloturer */
            $clo = $this->commerce->cloturerdocu($matricule_en,$document,$input);
            if($clo){
                $array = array(
                    'success' => 'document cloturer avec succès'
                );
            }else{
                $array = array(
                    'success' => 'erreur survenu document non cloturer'
                );
            }
        }else{
            $array = array(
                'success' => 'le système ne retrouve pas le document'
            );  
        }
        $array = array(
            'success' => ''
        );
        echo json_encode($array);
    }
    /**cloturer une facture en foction du code de l'entreprise et celui du document fin */

    /**supprimer un article dans une facture encour de création debut */
    public function deletearticledocument(){
        $this->logged_in();

        $output = "";

        $code_document = $this->input->post('code_doc');
        $code_article = $this->input->post('code_art');
        $code_depot = $this->input->post('code_depot');

        $ir = $this->input->post('ir_doc');
        $tva = $this->input->post('tva_doc');

        $matricule_en = session('users')['matricule'];
        $matricule_emp = session('users')['matricule_emp'];

        if(!empty($code_document) && !empty($code_article)){
            /**on verifie le type de document */
            $verify_typ_doc = $this->stock->type_document($code_document,$matricule_en);
            if(!empty($verify_typ_doc)){
                if($verify_typ_doc['abrev_doc'] == 'RT' || $verify_typ_doc['abrev_doc'] == 'RC'){
                    
                    /** 
                     * 1: on selectionne l'article dans le document en question
                     * 2: on selectionne l'article dans le stock pour le depot
                     * 3: on ajoute les quantités: qte art dans le document + qte art dans le stock
                     * 4: on modifie le document 
                     * 5: on supprime l'article du document
                     * 6:on supprime le document au besion
                    */

                    /**1: on selectionne l'article dans le document en question */
                    $verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
                    if(!empty($verify_art_document)){

                        /** 2: on selectionne l'article dans le stock pour le depot choisi*/
                        $verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
                        if(!empty($verify_art_stock)){
                            /**on verifie si le document est encore modifiable*/
                            $diff = $this->nbr_jour($verify_typ_doc['date_creation_doc']);
                            if($diff <= 180){
                                /**le document est modifiable */

                                /**on compte le nombre d'article dans un document
                                 * si c'est >1 on supprime uniquement l'article dans le document
                                 * si c'est c'est ==1 on supprime l'article dans le document et le document lui mm
                                */
                                $nbr_artdocument = $this->stock->count_articles_docucment($code_document,$matricule_en);
								if($nbr_artdocument > 1){

                                    /**on ajoute au stock la quantité dans le document*/
                                    $input_stock_val = array(
                                        'code_employe'=> $matricule_emp,
                                        'code_depot'=> $code_depot,
                                        'code_entreprise'=> $matricule_en,
                                        'quantite'=> ($verify_art_stock['quantite'] + $verify_art_document['quantite']),
                                        'date_modifier_stock'=> dates()
                                    );
                                    $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
                                    if($update_art_st){
                                    /**on selectionne le document en question pour le modifier*/
                                        $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                        if(!empty($document)){

                                            /**on modifie le document
                                             * 1: on enleve le prix total ht de l'article sur le prix total ht du document
                                             * 2: on enleve le prix total ttc de l'article sur le prix total ttc du document
                                            */


                                            /** selectionne le pourcentage de la tva dans la table taxe*/
                                            $pourcentagetva = $this->commerce->specifictaxe($tva,$matricule_en);
                                            $action1 = (!empty($pourcentagetva)) ? $pourcentagetva['pourcentage'] : 0;

                                            //** selectionne le pourcentage de la ir dans la table taxe*
                                            $pourcentageir = $this->commerce->specifictaxe($ir,$matricule_en);
                                            $action2 = (!empty($pourcentageir)) ? $pourcentageir['pourcentage'] : 0;

                                            /**taxes et prix ttc de l'article debut */
                                                $tva_art = (($action1 * $verify_art_document['pt_HT'])/100);
                                                $ir_art = (($action2 * $verify_art_document['pt_HT'])/100);
                                                $prix_ttc_art =  ($verify_art_document['pt_HT'] + $tva_art);
                                                $prix_net_art =  ($verify_art_document['pt_HT'] + ($ir_art));
                                            /**taxes et prix ttc de l'article fint */
                                            $restant = NULL;
                                            if($verify_typ_doc['abrev_doc'] == 'RC'){
                                                $restant = ($document['pt_net_document'] == $document['pt_ht_document'])?$prix_ttc_art:$prix_net_art;
                                            }
                                            
                                            
                                            $input_doc = array(
                                                'date_modifier_doc'=>dates(),
                                                'pt_ht_document'=> ($document['pt_ht_document'] - $verify_art_document['pt_HT']),
                                                'pt_ttc_document'=> ($document['pt_ttc_document'] - $prix_ttc_art),
                                                'pt_net_document'=> ($document['pt_net_document'] - $prix_net_art),
                                                'dette_restante'=> ($document['dette_restante'] - $restant),
                                                'code_employe'=> $matricule_emp,
                                                'code_entreprie'=> $matricule_en,
                                            );
                                            $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                            if($update_doc){

                                                /**on supprimer l'article du document */
                                                $delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
                                                if($delete_art_doc){
                                                    /**liste des articles du document */
                                                    /**selectionne les taxes */
                                                    $prixht = 1;
                                                    $qte = 1;
                                                    $taxe = $this->taxe($tva,$ir,$matricule_en,$prixht,$qte);

                                                    /**liste des articles du document */
                                                    $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                    $array = array(
                                                        'success' => 'article supprimer du panier',
                                                        'art_doc' => $output
                                                    );
                                                }else{
                                                    $array = 	array(
                                                        'success'   => '
                                                            <script>
                                                                swal.fire("ERREUR!","l\'article selectionné n\'a pas été retirer du document! contactez l\'administrateur","info")
                                                            </script>
                                                        '
                                                    );	
                                                }
                                            }else{
                                                $array = 	array(
                                                    'success'   => '
                                                        <script>
                                                            swal.fire("success","le système ne trouve pas le document","info")
                                                        </script>
                                                    '
                                                );   
                                            }
                                        }else{
                                            $array = 	array(
                                                'success'   => '
                                                    <script>
                                                        swal.fire("ERREUR!","document non trouvé","error")
                                                    </script>
                                                '
                                            );
                                        }
                                    }else{
                                        $array = 	array(
                                            'success'   => '
                                                <script>
                                                    swal.fire("ERREUR!","le stock n\a pas été modifier","info")
                                                </script>
                                            '
                                        );
                                    }
                                }else if($nbr_artdocument == 1){
                                    /**on ajoute au stock la quantité dans le document*/
                                    $input_stock_val = array(
                                        'code_employe'=> $matricule_emp,
                                        'code_depot'=> $code_depot,
                                        'code_entreprise'=> $matricule_en,
                                        'quantite'=> ($verify_art_stock['quantite'] + $verify_art_document['quantite']),
                                        'date_modifier_stock'=> dates()
                                    );
                                    $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val, $code_depot);
                                    if($update_art_st){
                                        /**on supprimer l'article du document */
                                        $delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
                                        if($delete_art_doc){
                                            /**on supprime le document */
                                            $delete_doc = $this->stock->delete_document($code_document,$matricule_en,$verify_typ_doc['code_doc']);
                                            if($delete_doc){
                                                /**liste des articles du document */
                                                $output = $this->art_doc($code_document,$matricule_en,$tva,$ir);
                                                $array = array(
                                                    'success' => 'article supprimer du panier',
                                                    'art_doc' => $output
                                                );
                                            }else{
                                                $array = 	array(
                                                    'success'   => 'le document n\'a pas été supprimer! contactez l\'administrateur'
                                                );
                                            }
                                        }else{
                                            $array = 	array(
                                                'success'   => 'erreur survenu, article non supprimer. contactez l\'admin'
                                            );  
                                        }

                                    }else{
                                        $array = 	array(
                                            'success'   => 'erreur survenu, article non retourné en stock. contactez l\'admin'
                                        );   
                                    }
                                }
                            }else{
                                $array = 	array(
                                    'success'   => '
                                        <script>
                                            swal.fire("OUPS!","ce document n\'est plus modifiable. <b>la modification est uniquement possible le jour de l\'enregistrement</b>","info")
                                        </script>
                                    '
                                );  
                            }
                        }else{
                            $array = 	array(
                                'success'   => '
                                    <script>
                                        swal.fire("ERREUR!","cette article n\'est pas en stock pour ce depot","info")
                                    </script>
                                '
                            );
                        }
                    }else{
                        $array = 	array(
                            'success'   => '
                                <script>
                                    swal.fire("ERREUR!","l\'article n\'existe pas dans ce document","info")
                                </script>
                            '
                        );
                    }
                    
                }else if($verify_typ_doc['abrev_doc'] == 'FP' || $verify_typ_doc['abrev_doc'] == 'BL'){

                    /** 
                     * 1: on selectionne l'article dans le document en question
                     * 2: on selectionne l'article dans le stock pour le depot
                     * 3: on modifie le document 
                     * 4: on supprime l'article du document
                     * 5:on supprime le document au besion
                    */

                    /**1: on selectionne l'article dans le document en question */
                    $verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
                    if(!empty($verify_art_document)){

                        /** 2: on selectionne l'article dans le stock pour le depot choisi*/
                        $verify_art_stock = $this->stock->get_article_stock($code_article,$code_depot);
                        if(!empty($verify_art_stock)){
                            /**on verifie si le document est encore modifiable*/
                            $diff = $this->nbr_jour($verify_typ_doc['date_creation_doc']);
                            if($diff <= 30){
                                /**le document est modifiable */

                                /**on compte le nombre d'article dans un document
                                 * si c'est >1 on supprime uniquement l'article dans le document
                                 * si c'est c'est ==1 on supprime l'article dans le document et le document lui mm
                                */
                                $nbr_artdocument = $this->stock->count_articles_docucment($code_document,$matricule_en);
								if($nbr_artdocument > 1){

                                    /**on selectionne le document en question pour le modifier*/
                                    $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                    if(!empty($document)){

                                        /**on modifie le document
                                         * 1: on enleve le prix total ht de l'article sur le prix total ht du document
                                         * 2: on enleve le prix total ttc de l'article sur le prix total ttc du document
                                        */


                                        /** selectionne le pourcentage de la tva dans la table taxe*/
                                        $pourcentagetva = $this->commerce->specifictaxe($tva,$matricule_en);
                                        $action1 = (!empty($pourcentagetva)) ? $pourcentagetva['pourcentage'] : 0;

                                        //** selectionne le pourcentage de la ir dans la table taxe*
                                        $pourcentageir = $this->commerce->specifictaxe($ir,$matricule_en);
                                        $action2 = (!empty($pourcentageir)) ? $pourcentageir['pourcentage'] : 0;

                                        /**taxes et prix ttc de l'article debut */
                                            $tva_art = (($action1 * $verify_art_document['pt_HT'])/100);
                                            $ir_art = (($action2 * $verify_art_document['pt_HT'])/100);
                                            $prix_ttc_art =  ($verify_art_document['pt_HT'] + $tva_art + ($ir_art));
                                        /**taxes et prix ttc de l'article fint */

                                        $input_doc = array(
                                            'date_modifier_doc'=>dates(),
                                            'pt_ht_document'=> ($document['pt_ht_document'] - $verify_art_document['pt_HT']),
                                            'pt_ttc_document'=> ($document['pt_ttc_document'] - $prix_ttc_art),
                                            'code_employe'=> $matricule_emp,
                                            'code_entreprie'=> $matricule_en,
                                        );
                                        $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                        if($update_doc){

                                            /**on supprimer l'article du document */
                                            $delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
                                            if($delete_art_doc){
                                                /**liste des articles du document */
                                                /**selectionne les taxes */
                                                $prixht = 1;
                                                $qte = 1;
                                                $taxe = $this->taxe($tva,$ir,$matricule_en,$prixht,$qte);

                                                /**liste des articles du document */
                                                $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                $array = array(
                                                    'success' => 'article supprimer du panier',
                                                    'art_doc' => $output
                                                );
                                            }else{
                                                $array = 	array(
                                                    'success'   => '
                                                        <script>
                                                            swal.fire("ERREUR!","l\'article selectionné n\'a pas été retirer du document! contactez l\'administrateur","info")
                                                        </script>
                                                    '
                                                );	
                                            }
                                        }else{
                                            $array = 	array(
                                                'success'   => '
                                                    <script>
                                                        swal.fire("success","le système ne trouve pas le document","info")
                                                    </script>
                                                '
                                            );   
                                        }
                                    }else{
                                        $array = 	array(
                                            'success'   => '
                                                <script>
                                                    swal.fire("ERREUR!","document non trouvé","error")
                                                </script>
                                            '
                                        );
                                    }
                                }else if($nbr_artdocument == 1){
                                    /**on supprimer l'article du document */
                                    $delete_art_doc = $this->stock->delete_art_stock($code_article, $code_document, $matricule_en);
                                    if($delete_art_doc){
                                        /**on supprime le document */
                                        $delete_doc = $this->stock->delete_document($code_document,$matricule_en,$verify_typ_doc['code_doc']);
                                        if($delete_doc){
                                            /**liste des articles du document */
                                            $output = $this->art_doc($code_document,$matricule_en,$tva,$ir);
                                            $array = array(
                                                'success' => 'article supprimer du panier',
                                                'art_doc' => $output
                                            );
                                        }else{
                                            $array = 	array(
                                                'success'   => 'le document n\'a pas été supprimer! contactez l\'administrateur'
                                            );
                                        }
                                    }else{
                                        $array = 	array(
                                            'success'   => 'erreur survenu, article non supprimer. contactez l\'admin'
                                        );  
                                    }
                                }
                            }else{
                                $array = 	array(
                                    'success'   => '
                                        <script>
                                            swal.fire("OUPS!","ce document n\'est plus modifiable. <b>la modification est uniquement possible le jour de l\'enregistrement</b>","info")
                                        </script>
                                    '
                                );  
                            }
                        }else{
                            $array = 	array(
                                'success'   => '
                                    <script>
                                        swal.fire("ERREUR!","cette article n\'est pas en stock pour ce depot","info")
                                    </script>
                                '
                            );
                        }
                    }else{
                        $array = 	array(
                            'success'   => '
                                <script>
                                    swal.fire("ERREUR!","l\'article n\'existe pas dans ce document","info")
                                </script>
                            '
                        );
                    }

                }else if($verify_typ_doc['abrev_doc'] == 'BR'){

                    /**on selectionne le document de l'article ou les articles a retourné en fonction de son code et celui de l'entreprise */
                    $document = $this->commerce->specificdocument($code_document,$matricule_en);
                    if(!empty($document)){

                        $diff = $this->nbr_jour($document['date_creation_doc']);
                        if($diff <= 180){

                            $nbr_artdocument = $this->stock->count_articles_docucment($document['code_document'],$matricule_en);
							if($nbr_artdocument > 1){
                                /**on selectionne l'article dans le document en question */
                                $verify_art_document = $this->stock->get_art_doc($code_article, $document['code_document'], $matricule_en);
                                if(!empty($verify_art_document)){
                                    /**on selectionne l'article en stock en fonction du depot */
                                    $verify_art_stock = $this->stock->get_article_stock($verify_art_document['code_article'],$document['depot_doc']);
                                    if(!empty($verify_art_stock)){

                                        /**on retir du stock les quantés précédement ajouter*/
                                        $input_stock_val = array(
                                            'code_employe'=> $matricule_emp,
                                            'code_depot'=> $document['depot_doc'],
                                            'code_entreprise'=> $matricule_en,
                                            'quantite'=> ($verify_art_stock['quantite'] - $verify_art_document['quantite']),
                                            'date_modifier_stock'=> dates()
                                        );
                                        $update_art_st = $this->stock->update_stock2($verify_art_document['code_article'], $input_stock_val, $document['depot_doc']);
                                        if($update_art_st){
                                            /**on modifie le document en question */

                                            /**on determine les différents prix du document en fonction des taxes appliquées a l'article*/
                                            $prixtaxes = $this->taxe($tva,$ir,$matricule_en,$verify_art_document['pu_HT'],$verify_art_document['quantite']);

                                            /**on met a jour le doument */
                                            $inputdataupdate = array(
                                                'pt_ht_document'=> ($document['pt_ht_document'] - ($verify_art_document['pt_HT'])),
                                                'pt_ttc_document'=>($document['pt_ttc_document'] - ($prixtaxes['prixttc'])),
                                                'pt_net_document'=>($document['pt_net_document'] - ($prixtaxes['prixnet'])),
                                                'date_modifier_doc'=>dates()
                                            );
                                            /***on modifie le document*/
                                            $updatedoc = $this->commerce->updatedocument($document['code_document'],$matricule_en,$inputdataupdate);
                                            if($updatedoc){
                                                
                                                /**on supprimer l'article du document */
                                                $delete_art_doc = $this->stock->delete_art_stock($verify_art_document['code_article'], $document['code_document'], $matricule_en);
                                                if($delete_art_doc){
                                                    /**liste des articles du document */
                                                    /**selectionne les taxes */
                                                    $prixht = 1;
                                                    $qte = 1;
                                                    $taxe = $this->taxe($tva,$ir,$matricule_en,$prixht,$qte);

                                                    /**liste des articles du document */
                                                    $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                    $array = array(
                                                        'success' => 'annulation du retour de cette article éffectué',
                                                        'art_doc' => $output
                                                    );
                                                }else{
                                                    $array = 	array(
                                                        'success'   => '
                                                            <script>
                                                                swal.fire("ERREUR!","l\'article selectionné n\'a pas été retirer du document! contactez l\'administrateur","info")
                                                            </script>
                                                        '
                                                    );	
                                                }
                                            }else{
                                                $array = array(
                                                    'success'   => '
                                                        <script>
                                                            swal.fire("ERREUR!","Document non mis à jour, contactez l\'administrateur","info")
                                                        </script>
                                                    '
                                                );   
                                            }
                                        }else{
                                            $array = 	array(
                                                'success'   => '
                                                    <script>
                                                        swal.fire("ERREUR!","Stock non régularisé, contactez l\'administrateur","info")
                                                    </script>
                                                '
                                            );
                                        }
                                    }else{
                                        $array = 	array(
                                            'success'   => '
                                                <script>
                                                    swal.fire("ERREUR!","le système ne trouve pas l\'article dans le stock","info")
                                                </script>
                                            '
                                        );
                                    }
                                    
                                }else{
                                    $array = 	array(
                                        'success'   => '
                                            <script>
                                                swal.fire("ERREUR!","le système ne retrouve pas l\'article dans le document","info")
                                            </script>
                                        '
                                    );
                                }
                            }else if($nbr_artdocument = 1){
                                /**on selectionne l'article dans le document en question */
                                $verify_art_document = $this->stock->get_art_doc($code_article, $document['code_document'], $matricule_en);
                                if(!empty($verify_art_document)){
                                    /**on selectionne l'article en stock en fonction du depot */
                                    $verify_art_stock = $this->stock->get_article_stock($verify_art_document['code_article'],$document['depot_doc']);
                                    if(!empty($verify_art_stock)){

                                        /**on retir du stock les quantés précédement ajouter*/
                                        $input_stock_val = array(
                                            'code_employe'=> $matricule_emp,
                                            'code_depot'=> $document['depot_doc'],
                                            'code_entreprise'=> $matricule_en,
                                            'quantite'=> ($verify_art_stock['quantite'] - $verify_art_document['quantite']),
                                            'date_modifier_stock'=> dates()
                                        );
                                        $update_art_st = $this->stock->update_stock2($verify_art_document['code_article'], $input_stock_val, $document['depot_doc']);
                                        if($update_art_st){
                                            /**on modifie le document en question */

                                            /**on determine les différents prix du document en fonction des taxes appliquées a l'article*/
                                            $prixtaxes = $this->taxe($tva,$ir,$matricule_en,$verify_art_document['pu_HT'],$verify_art_document['quantite']);

                                            /**on met a jour le doument */
                                            $inputdataupdate = array(
                                                'pt_ht_document'=> ($document['pt_ht_document'] - ($verify_art_document['pt_HT'])),
                                                'pt_ttc_document'=>($document['pt_ttc_document'] - ($prixtaxes['prixttc'])),
                                                'pt_net_document'=>($document['pt_net_document'] - ($prixtaxes['prixnet'])),
                                                'date_modifier_doc'=>dates()
                                            );
                                            /***on modifie le document*/
                                            $updatedoc = $this->commerce->updatedocument($document['code_document'],$matricule_en,$inputdataupdate);
                                            if($updatedoc){
                                                
                                                /**on supprimer l'article du document */
                                                $delete_art_doc = $this->stock->delete_art_stock($verify_art_document['code_article'], $document['code_document'], $matricule_en);
                                                if($delete_art_doc){

                                                    /**on supprime le document */
                                                    $delete_doc = $this->stock->delete_document($document['code_document'],$matricule_en,$document['code_doc']);
                                                    if($delete_doc){
                                                        /**liste des articles du document */
                                                        /**selectionne les taxes */
                                                        $prixht = 1;
                                                        $qte = 1;
                                                        $taxe = $this->taxe($tva,$ir,$matricule_en,$prixht,$qte);

                                                        /**liste des articles du document */
                                                        $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                        $array = array(
                                                            'success' => 'annulation du retour de cette article éffectué',
                                                            'art_doc' => $output
                                                        );
                                                    }else{
                                                        $array = 	array(
                                                            'success'   => 'le document n\'a pas été supprimer! contactez l\'administrateur'
                                                        );
                                                    }
                                                }else{
                                                    $array = 	array(
                                                        'success'   => '
                                                            <script>
                                                                swal.fire("ERREUR!","l\'article selectionné n\'a pas été retirer du document! contactez l\'administrateur","info")
                                                            </script>
                                                        '
                                                    );	
                                                }
                                            }else{
                                                $array = array(
                                                    'success'   => '
                                                        <script>
                                                            swal.fire("ERREUR!","Document non mis à jour, contactez l\'administrateur","info")
                                                        </script>
                                                    '
                                                );   
                                            }
                                        }else{
                                            $array = 	array(
                                                'success'   => '
                                                    <script>
                                                        swal.fire("ERREUR!","Stock non régularisé, contactez l\'administrateur","info")
                                                    </script>
                                                '
                                            );
                                        }
                                    }else{
                                        $array = 	array(
                                            'success'   => '
                                                <script>
                                                    swal.fire("ERREUR!","le système ne trouve pas l\'article dans le stock","info")
                                                </script>
                                            '
                                        );
                                    }
                                    
                                }else{
                                    $array = 	array(
                                        'success'   => '
                                            <script>
                                                swal.fire("ERREUR!","le système ne retrouve pas l\'article dans le document","info")
                                            </script>
                                        '
                                    );
                                }
                            }
                        }else{
                            $array = 	array(
                                'success'   => '
                                    <script>
                                        swal.fire("OUPS!","le document n\'est plus modifiable","warning")
                                    </script>
                                '
                            ); 
                        }
                    }else{
                        $array = 	array(
                            'success'   => '
                                <script>
                                    swal.fire("ERREUR!","le système ne retourve pas le document de l\'article","info")
                                </script>
                            '
                        );  
                    }
                }else{
                    $array = 	array(
                        'success'   => '
                            <script>
                                swal.fire("ERREUR!","'.$verify_typ_doc['abrev_doc'].' ce type de document n\'est pas utile ici, utilise le menu de gauche","info")
                            </script>
                        '
                    );
                }
            }else{
                $array = 	array(
                    'success'   => '
                        <script>
                            swal.fire("ERREUR!","le systeme ne trouve pas le type de document. contactez l\'administrateur","info")
                        </script>
                    '
                );
            }
        }else{
            $array = 	array(
                'success'   => '
                    <script>
                        swal.fire("ERREUR!","contactez l\'administrateur","info")
                    </script>
                '
            );
        }

        echo json_encode($array);
    }
    /**supprimer un article dans une facture encour de création fin */


    /*** ========================GENERER UN PDF D'UN DOCUMENT DE VENTE DEBUT========================*/
    public function facture_pdf($code_document){
        $this->logged_in(); 

        $matricule_en = session('users')['matricule'];
        $code_document = trim($code_document);

        /**j'affiche la liste des article d'un document en fonction du code du document et celui de l'entreprise
         * on sortie de la, on aura, 
         * 1: la liste des article, 2:le client concerné, 3: la personne qui a créé la facture, le montants
        */
        $artdocuvente = $this->commerce->artdocumentvente($matricule_en,$code_document);
        if(!empty($artdocuvente)){

            // Create an instance of the class:
            $mpdf = new \Mpdf\Mpdf();
            //$mpdf->SetJS('this.print();');
            $mpdf->SetJS('print();');
            $mpdf->SetHTMLFooter('
                <table>
                    <tr>
                        <td width="66%">Imprimé le : {DATE j-m-Y}</td>
                        <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                    </tr>
                </table>
            ');
            $mpdf->WriteHTML('
                <table>
                    <tr>
                        <td colspan="2"><img src="'.assets_dir().'/media/baniere/banier_header.jpeg" width="670" alt=""></td>
                    </tr>
                </table>
                <style>
                    table {
                        font-family: arial, sans-serif;
                        border-collapse: collapse;
                        width: 100%;
                    }
                    
                    td, th {
                        border: 1px solid #dddddd;
                        text-align: left;
                        padding: 8px;
                    }
                    
                    tr:nth-child(even) {
                        background-color: #dddddd;
                    }
                </style>
            ');

            $clientcommercialdoc = $this->commerce->docventeclientcommercial($code_document,$matricule_en);
            if(!empty($clientcommercialdoc)){
                $verificationtypedoc = substr($clientcommercialdoc['code_document'], 0, 2);
                if($verificationtypedoc == 'RT'){
                    $mpdf->WriteHTML('<h6>FACTURE AU COMPTANT : '.$clientcommercialdoc['code_document'].'</h6>'); 
                }else if($verificationtypedoc == 'RC'){
                    $mpdf->WriteHTML('<h6>FACTURE : '.$clientcommercialdoc['code_document'].'</h6>');
                }else if($verificationtypedoc == 'FP'){
                    $mpdf->WriteHTML('<h6>FACTURE PROFORMAT: '.$clientcommercialdoc['code_document'].'</h6>');
                }else if($verificationtypedoc == 'BL'){
                    $mpdf->WriteHTML('<h6>BORDEREAU DE LIVRAISON: '.$clientcommercialdoc['code_document'].'</h6>');
                }else if($verificationtypedoc == 'BR'){
                    $mpdf->WriteHTML('<h6>BON DE RETOUR: '.$clientcommercialdoc['code_document'].'</h6>');
                }

                $mpdf->WriteHTML('<p><b>DOIT : '.strtoupper($clientcommercialdoc['nom_cli']).' </b><br/> Commercial: '.$clientcommercialdoc['nom_emp'].' / '.$clientcommercialdoc['telephone_emp'].' / '.$clientcommercialdoc['email_emp'].' <br/> <span style="font-size:10;">Enrégistré le: '.dateformat($clientcommercialdoc['date_creation_doc']).'</span> </p>');
            }

            $mpdf->WriteHTML('
            <table>
                <tr>
                    <th>DESIGNATION / DESCRIPTION</th>
                    <th>QUANTITE</th>
                    <th>PRIX UNIT</th>
                    <th>PRIX TOTAL</th>
                </tr>
            ');

            foreach ($artdocuvente as $key => $value) {
                $mpdf->WriteHTML('
                    <tr>
                        <td><b>'.$value['designation'].'</b> <br>'.$value['description_art'].'</td>
                        <td>'.$value['quantite'].'</td>
                        <td>'.numberformat($value['pu_HT']).'</td>
                        <td>'.numberformat($value['pt_HT']).'</td>
                    </tr>
                ');
            }

            $lettre=new ChiffreEnLettre();
            if(!empty($clientcommercialdoc)){
                $nbr = !empty($clientcommercialdoc['pt_net_document'])?abs($clientcommercialdoc['pt_net_document'] - $clientcommercialdoc['pt_ht_document']):0;
                $ttir = $nbr>0?numberformat($nbr):0;

                $nbrttva = abs($clientcommercialdoc['pt_ttc_document'] - $clientcommercialdoc['pt_ht_document']);
                $ttva = $nbrttva>0?numberformat($nbrttva):0;

                $ttc = "";
                $net = "";
                $decimal = "";
                if($ttir==0){
                    $ttc .=numberformat($clientcommercialdoc['pt_ttc_document']);

                    /******************** */
                    $prixtts = $clientcommercialdoc['pt_ttc_document']; 
                    $nbrsaparer = explode('.', $clientcommercialdoc['pt_ttc_document']);
                    $decimal = strpos($prixtts, "." ) !== false?$lettre->Conversion($nbrsaparer[0]).' (virgule) '.$lettre->Conversion($nbrsaparer[1]):$lettre->Conversion($prixtts);
                    /********************* */
                }else if($ttir !=0){
                    $ttc .=numberformat($clientcommercialdoc['pt_ttc_document']);
                    $net .=numberformat($clientcommercialdoc['pt_net_document']);

                    /*********************** */
                    $prixtt = $clientcommercialdoc['pt_net_document']; 
                    $nbrsaparer = explode('.', $clientcommercialdoc['pt_net_document']);
                    $decimal = strpos($prixtt, "." ) !== false?$lettre->Conversion($nbrsaparer[0]).' (virgule) '.$lettre->Conversion($nbrsaparer[1]):$lettre->Conversion($prixtt);
                    /********************** */
                }
                $mpdf->WriteHTML('
                <tr>
                    <th rowspan="6" colspan="2"></th>
                </tr>
                <tr>
                    <th class="badge badge-info">HT: </th>
                    <th class="badge badge-danger">'.numberformat($clientcommercialdoc['pt_ht_document']).'</th>
                </tr>
                <tr>
                    <th class="badge badge-info">Tva:</th>
                    <th class="badge badge-info">'.$ttva.'</th>
                </tr>
                <tr>
                    <th class="badge badge-info">TTC</th>
                    <th class="badge badge-info">'.$ttc.'</th>
                </tr>');
                if($ttir != 0){
                    $mpdf->WriteHTML('
                    <tr>
                        <th class="badge badge-info">Ir</th>
                        <th class="badge badge-info">'.$ttir.'</th>
                    </tr>
                    <tr>
                        <th class   ="badge badge-info">Net à payé</th>
                        <th class="badge badge-info">'.$net.'</th>
                    </tr>');
                }
                
                $mpdf->WriteHTML('</table> <hr>');

                $delai = $clientcommercialdoc['delais_reg_doc'];
                $delais = !empty($delai)?'Offre vallable: '.$delai.' jour(s)':'';
                $mpdf->WriteHTML('
                    <span> <b>ce present document est arrêté à la somme de:'.strtoupper($decimal).'</b></span>
                    <p>'.$delais.'</p>
                    <p style="font-size:8;">
                        Moyen de paiement: virement bancaire, chèque, espèce<br>
                        AfrilandFirstBank 10005 0001 06321321001-75 <br>
                        UBA 100033 05207 0701100820-34
                    </p>
                    <span>Client</span> 
                    ---------------------------
                    -----------------------------
                    -----------------------
                    --------------------------------------
                    <span>Commercial</span>
                    <br><br>
                ');

            }
            $mpdf->WriteHTML('
                <table>
                    <tr>
                        <td colspan="4"><img src="'.assets_dir().'/media/baniere/banier_footer.jpeg" width="670" alt=""></td>
                    </tr>
                </table>
            ');
            // Output a PDF file directly to the browser
            //$mpdf->Output();
            $mpdf->Output('facture_'.$code_document.'_.pdf', \Mpdf\Output\Destination::INLINE);
        }else{
            flash('error','le système ne retrouve pas les articles de ce document');
            redirect('facture'); 
        }
    }
    /*** ========================GENERER UN PDF D'UN DOCUMENT DE VENTE FIN========================*/


    /**** ================================ AFFICHE LES REGLEMENTS TICKET EN FONCTION DE L'ENTREPRISE ET OU DE L'AGENCE DEBUT =========== */
    public function all_reglementclient(){
        $this->logged_in();

        $matricule_en = session('users')['matricule'];
        $agencert = session('users')['matricule_ag'];
        $abrev = "RT";
        $output = "";
        $recherche = trim($this->input->post('rechercher'));
        if(!empty($recherche)){
            /**on cherche une facture en fonction du client ou du code du client du type de document, de l'entreprise et ou de l'agence*/
            $docrtvente = $this->commerce->documentrtventerecherche($abrev,$matricule_en,$agencert,$recherche); 
            if(!empty($docrtvente)){
                foreach ($docrtvente as $key => $value) {
                    $output .='<div class="row"> <div class="col-md-11"><button type="button" id="'.$value['code_document'].'" class="list-group-item list-group-item-action art_rt"><b>Code:</b> '.$value['code_document'].' | <b>Nom:</b> '.$value['nom_document'].' | <b>Date:</b> '.dateformat($value['date_creation_doc']).' | <b class="badge badge-primary">Caisse: '.$value['libelle_caisse'].'</b> <b class="badge badge-success">Client: '.$value['nom_cli'].'</b> | <b class="badge badge-warning">'.$value['nom_ag'].'</b> | </button></div><div class="col-md-1"><button class="btn btn-icon btn-circle btn-label-linkedin editdoc" id="'.$value['code_document'].'"><i class="fa fa-edit"></i></button></div></div>';  
                }
            }else{
                $output .='<b class="text-danger">aucune facture ne correspond à ce client</b>';
            }
            $array = array(
                'infos' => $output,
            );
        }else{
            /**
             * 1: si l'agence est connecté, on cherche pour une agence donné
             * 2: si non on cherche pour une entreprise
             */
            /**on compte le nombre de reglément ticket en fonction de l'entreprise et ou de l'agence */
            $nbrdocrt = $this->commerce->countdocrtagence($matricule_en,$agencert,$abrev);
            
            /** PAGINATION DEBUT */
            $config = array();
            $config["base_url"] = "#";
            $config["total_rows"] = $nbrdocrt;
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
            $config["cur_tag_open"] = "<li class='page-item active'><a href='#'><span class='page-link'><span class='sr-only'>(current)</span>";
            $config["cur_tag_close"] = "</span></a></li>";
            $config["num_tag_open"] = '<li class="page-item page-link">';
            $config["num_tag_close"] = "</li>";
            $config["num_links"] = 1;
            $this->pagination->initialize($config);
            
            $page = (($this->uri->segment(2) - 1) * $config["per_page"]);
            /** PAGINATION FIN */
            
            /**on selectionne les documents en fonction du type de document, de l'entreprise et ou de l'agence
             * au sorti on aura tous les informations sur le documents le client et la caisse
            */
            $docrtvente = $this->commerce->documentrtvente($abrev,$matricule_en,$agencert,$config["per_page"],$page); 
            if(!empty($docrtvente)){
                foreach ($docrtvente as $key => $value) {
                    $output .='<div class="row"> <div class="col-md-11"><button type="button" id="'.$value['code_document'].'" class="list-group-item list-group-item-action art_rt"><b>Code:</b> '.$value['code_document'].' | <b>Nom:</b> '.$value['nom_document'].' | <b>Date:</b> '.dateformat($value['date_creation_doc']).' | <b class="badge badge-primary">Caisse: '.$value['libelle_caisse'].'</b> <b class="badge badge-success">Client: '.$value['nom_cli'].'</b> | <b class="badge badge-warning">'.$value['nom_ag'].'</b> </button></div><div class="col-md-1"><button class="btn btn-icon btn-circle btn-label-linkedin editdoc" id="'.$value['code_document'].'"><i class="fa fa-edit"></i></button></div></div>';  
                }
            }else{
                $output .='<b class="text-danger">aucune facture enrégistrer pour le moment</b>';
            }

            $array = array(
                'infos' => $output,
                'pagination_link' => $this->pagination->create_links()
            );
        }

        echo json_encode($array);
    }
    /**** ================================ AFFICHE LES REGLEMENTS TICKET EN FONCTION DE L'ENTREPRISE ET OU DE L'AGENCE FIN =========== */

    /** =========================== LISTE DES ARTICLE D'UN DOCUMENT DE VENTE DEBUT ================================== */
    public function all_article_reglement_client(){
        $this->logged_in();
        $code_document = trim($this->input->post('code_rt'));
        $matricule_en = session('users')['matricule'];

        $code_document = trim($this->input->post('code_rt'));
        $matricule_en = session('users')['matricule'];

        $output ="";

        /**j'affiche la liste des article d'un document en fonction du code du document et celui de l'entreprise
         * on sortie de la, on aura, 
         * 1: la liste des article, 2:le client concerné, 3: la personne qui a créé la facture, le montants
        */
        $artdocuvente = $this->commerce->artdocumentvente($matricule_en,$code_document);
        if(!empty($artdocuvente)){
            $output .='
            <style type="text/css">
                table{
                    width:100%; 
                    border-collapse: collapse;
                }
                td, th{
                    border: 1px solid black;
                }
            </style>';
            /**je selectionne un document en fonction de son code et celui de l'entreprise 
             * au sorti, on aura, le code du document, le client, le commercial
            */
            $clientcommercialdoc = $this->commerce->docventeclientcommercial($code_document,$matricule_en);
            if(!empty($clientcommercialdoc)){
                $output .='<h6>FACTURE AU COMPTANT : '.$clientcommercialdoc['code_document'].'</h6>
                <p>DOIT : '.strtoupper($clientcommercialdoc['nom_cli']).' <br> Commercial: '.$clientcommercialdoc['nom_emp'].' / '.$clientcommercialdoc['telephone_cli'].' <br> <span style="font-size:10;">Enrégistré le: '.dateformat($clientcommercialdoc['date_creation_doc']).'</span> </p>
                ';
            }else{
                $output .='<b class="text-danger">informations manquante</b>'; 
            }
            $output .='
            <table>
                <tr>
                    <th>DESIGNATION / DESCRIPTION</th>
                    <th>QUANTITE</th>
                    <th>PRIX UNIT</th>
                    <th>PRIX TOTAL</th>
                </tr>
            ';

            foreach ($artdocuvente as $key => $value) {
                $output .='
                    <tr>
                        <td><b>'.$value['designation'].'</b> <br>'.$value['description_art'].'</td>
                        <td>'.$value['quantite'].'</td>
                        <td>'.numberformat($value['pu_HT']).'</td>
                        <td>'.numberformat($value['pt_HT']).'</td>
                    </tr>
                ';
            }
            if(!empty($clientcommercialdoc)){

                $nbr = !empty($clientcommercialdoc['pt_net_document'])?abs($clientcommercialdoc['pt_net_document'] - $clientcommercialdoc['pt_ht_document']):0;
                $ttir = $nbr>0?numberformat($nbr):0;

                $nbrttva = abs($clientcommercialdoc['pt_ttc_document'] - $clientcommercialdoc['pt_ht_document']);
                $ttva = $nbrttva>0?numberformat($nbrttva):0;

                $ttc = "";
                $net = "";
                if($ttir==0){
                    $ttc .='Montant TTC: '.numberformat($clientcommercialdoc['pt_ttc_document']);
                }else if($ttir !=0 ){
                    $ttc .='Montant TTC: '.numberformat($clientcommercialdoc['pt_ttc_document']);
                    $net .='Net à payé: '.numberformat($clientcommercialdoc['pt_net_document']);
                }
                $output .='
                    <tr>
                        <th class="badge badge-info">Total HT: '.numberformat($clientcommercialdoc['pt_ht_document']).'</th>
                        <th class="badge badge-danger">Total Tva: '.$ttva.'</th>
                        <th class="badge badge-danger">Total Ir: '.$ttir.'</th>
                        <th class="badge badge-success">'.$ttc.'</th>
                        <th class="badge badge-success">'.$net.'</th>
                    </tr>
                ';
            }else{
                $output .='<b class="text-danger">informations manquante</b>'; 
            }
            $output .='</table>';
        }else{
            $output .= '<b class="text-danger">le système ne retrouve pas les articles de ce document</b>';
        }

        $array= array(
            'infos' => $output,
            'link' => '<a href="'.base_url('facturer/'.$code_document.'').'" type="button" class="btn btn-outline-success" target="_blink">IMPRIMER</a>'
        );
        echo json_encode($array);
    }
    /** =========================== LISTE DES ARTICLE D'UN DOCUMENT DE VENTE FIN ================================== */

    /*********************gestion des factures direct(pas de prêt) fin */

    /*********************gestion des règlements client(prêt) debut*********************************/

    /**affiche la page des règlement client */
    public function reglement_client(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

		/**informations utile */
		$matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];
        $output = "";
        if(!empty(session('users')['nom_emp'])){
            /** selectionne les depots en fonctions de l'entreprise et ou de l'agence*/
            $data['depots'] = $this->commerce->getalldepot($matricule,$agence);


            /**affiche la liste des clients d'une entreprise */
            $data['clients'] = $this->commerce->all_client($matricule);

            
            /**affiche les vendeur: c'est la personne connecté au momement de la vente */
            $data['vendeur'][] = array(
                'matricule' => session('users')['matricule_emp'],
                'nom' => session('users')['nom_emp']
            );


            /**affiche la liste des caisse en fonction de l'entreprise et ou de l'agence */
            $data['caisses'] = $this->commerce->allcaisseagence($matricule,$agence);
                
            
            
            /**afficher le code et le nom d'un document générer*/
            $data['code_doc'] = array(
                'RC-DOC-'.code(10) => 'RC-DOCUMENT-TRC-'.code(10).'-'.date('d-m-Y'),
            );



            /**liste des articles de l'entreprise*/
            $data['articles'] = $this->commerce->all_article_entreprise($matricule);

            /**liste des taxe */
            $data['taxes'] = $this->commerce->all_taxe_entreprise($matricule);

            /**affiche la liste des type de documents pour une entreprise donné */
            $data['docs'] = $this->commerce->type_doc($matricule);
            
            /**selection de la list des documents en fonction du type de document */
        
            $abrev = 'RC';
            $docus = $this->commerce->docu_cli_en_ag($matricule,$agence,$abrev);
            if(!empty($docus)){
                $output = $docus; 
            }
            $data['get_docs'] = $output;
            
            $this->load->view('commerce/reglement_client',$data);

        }else{
            flash('warning','Connecte toi en tant que utilisateur pour faire cette opération');
            redirect('home'); 
        }

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }

    /**afficher la liste de tous les règlement client d'une entreprise debut */
    public function all_reglementrcclient(){
        $this->logged_in();

        $matricule_en = session('users')['matricule'];
        $agencert = session('users')['matricule_ag'];
        $abrev = "RC";
        $output = "";

        $recherche = trim($this->input->post('rechercher'));
        if(!empty($recherche)){
            /**on cherche une facture en fonction du client ou du code du client du type de document, de l'entreprise et ou de l'agence*/
            $docrtvente = $this->commerce->documentrtventerecherche($abrev,$matricule_en,$agencert,$recherche); 
            if(!empty($docrtvente)){
                foreach ($docrtvente as $key => $value) {
                    $output .='<div class="row"> <div class="col-md-11"><button type="button" id="'.$value['code_document'].'" class="list-group-item list-group-item-action art_rc"><b>Code:</b> '.$value['code_document'].' | <b>Nom:</b> '.$value['nom_document'].' | <b>Date:</b> '.dateformat($value['date_creation_doc']).' | <b class="badge badge-primary">Caisse: '.$value['libelle_caisse'].'</b> <b class="badge badge-success">Client: '.$value['nom_cli'].'</b> | <b class="badge badge-warning">'.$value['nom_ag'].'</b> | <b class="text-primary">Dette Réglé: '.numberformat($value['dette_regler']).'</b> | <b class="text-danger">Dette Restante: '.numberformat($value['dette_restante']).'</b> </button></div><div class="col-md-1"><button class="btn btn-icon btn-circle btn-label-linkedin editdocrc" id="'.$value['code_document'].'"><i class="fa fa-edit"></i></button></div></div>';  
                }
            }else{
                $output .='<b class="text-danger">aucune facture ne correspond à ce client</b>';
            }
            $array = array(
                'infos' => $output,
            );
        }else{
            /**
             * 1: si l'agence est connecté, on cherche pour une agence donné
             * 2: si non on cherche pour une entreprise
             */
            /**on compte le nombre de reglément ticket en fonction de l'entreprise et ou de l'agence */
            $nbrdocrt = $this->commerce->countdocrtagence($matricule_en,$agencert,$abrev);
            
            /** PAGINATION DEBUT */
            $config = array();
            $config["base_url"] = "#";
            $config["total_rows"] = $nbrdocrt;
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
            $config["cur_tag_open"] = "<li class='page-item active'><a href='#'><span class='page-link'><span class='sr-only'>(current)</span>";
            $config["cur_tag_close"] = "</span></a></li>";
            $config["num_tag_open"] = '<li class="page-item page-link">';
            $config["num_tag_close"] = "</li>";
            $config["num_links"] = 1;
            $this->pagination->initialize($config);
            
            $page = (($this->uri->segment(2) - 1) * $config["per_page"]);
            /** PAGINATION FIN */
            
            /**on selectionne les documents en fonction du type de document, de l'entreprise et ou de l'agence
             * au sorti on aura tous les informations sur le documents le client et la caisse
            */
            $docrtvente = $this->commerce->documentrtvente($abrev,$matricule_en,$agencert,$config["per_page"],$page); 
            if(!empty($docrtvente)){
                foreach ($docrtvente as $key => $value) {
                    $output .='<div class="row"> <div class="col-md-11"><button type="button" id="'.$value['code_document'].'" class="list-group-item list-group-item-action art_rc"><b>Code:</b> '.$value['code_document'].' | <b>Nom:</b> '.$value['nom_document'].' | <b>Date:</b> '.dateformat($value['date_creation_doc']).' | <b class="badge badge-primary">Caisse: '.$value['libelle_caisse'].'</b> <b class="badge badge-success">Client: '.$value['nom_cli'].'</b> | <b class="badge badge-warning">'.$value['nom_ag'].'</b> | <b class="text-primary">Dette Réglé: '.numberformat($value['dette_regler']).'</b> | <b class="text-danger">Dette Restante: '.numberformat($value['dette_restante']).'</b> </button></div><div class="col-md-1"><button class="btn btn-icon btn-circle btn-label-linkedin editdocrc" id="'.$value['code_document'].'"><i class="fa fa-edit"></i></button></div></div>';  
                }
            }else{
                $output .='<b class="text-danger">aucune facture enrégistrer pour le moment</b>';
            }

            $array = array(
                'infos' => $output,
                'pagination_link' => $this->pagination->create_links()
            );
        }
        echo json_encode($array);
    }
    /**afficher la liste de tous les règlement client d'une entreprise debut */

    /**liste des article d'un reglement client debut */
    public function all_article_rc_reglement_client(){
        $this->logged_in();

        $this->logged_in();
        $code_document = trim($this->input->post('code_rt'));
        $matricule_en = session('users')['matricule'];

        $code_document = trim($this->input->post('code_rt'));
        $matricule_en = session('users')['matricule'];

        $output ="";

         /**j'affiche la liste des article d'un document en fonction du code du document et celui de l'entreprise
         * on sortie de la, on aura, 
         * 1: la liste des article, 2:le client concerné, 3: la personne qui a créé la facture, le montants
        */
        $artdocuvente = $this->commerce->artdocumentvente($matricule_en,$code_document);
        if(!empty($artdocuvente)){
            $output .='
            <style type="text/css">
                table{
                    width:100%; 
                    border-collapse: collapse;
                }
                td, th{
                    border: 1px solid black;
                }
            </style>';
            /**je selectionne un document en fonction de son code et celui de l'entreprise 
             * au sorti, on aura, le code du document, le client, le commercial
            */
            $clientcommercialdoc = $this->commerce->docventeclientcommercial($code_document,$matricule_en);
            if(!empty($clientcommercialdoc)){
                $output .='<h6>FACTURE AU COMPTANT : '.$clientcommercialdoc['code_document'].'</h6>
                <p>DOIT : '.strtoupper($clientcommercialdoc['nom_cli']).' <br> Commercial: '.$clientcommercialdoc['nom_emp'].' / '.$clientcommercialdoc['telephone_cli'].' <br> <span style="font-size:10;">Enrégistré le: '.dateformat($clientcommercialdoc['date_creation_doc']).'</span> </p>
                ';
            }else{
                $output .='<b class="text-danger">informations manquante</b>'; 
            }
            $output .='
            <table>
                <tr>
                    <th>DESIGNATION / DESCRIPTION</th>
                    <th>QUANTITE</th>
                    <th>PRIX UNIT</th>
                    <th>PRIX TOTAL</th>
                </tr>
            ';

            foreach ($artdocuvente as $key => $value) {
                $output .='
                    <tr>
                        <td><b>'.$value['designation'].'</b> <br>'.$value['description_art'].'</td>
                        <td>'.$value['quantite'].'</td>
                        <td>'.numberformat($value['pu_HT']).'</td>
                        <td>'.numberformat($value['pt_HT']).'</td>
                    </tr>
                ';
            }
            if(!empty($clientcommercialdoc)){

                $nbr = !empty($clientcommercialdoc['pt_net_document'])?abs($clientcommercialdoc['pt_net_document'] - $clientcommercialdoc['pt_ht_document']):0;
                $ttir = $nbr>0?numberformat($nbr):0;

                $nbrttva = abs($clientcommercialdoc['pt_ttc_document'] - $clientcommercialdoc['pt_ht_document']);
                $ttva = $nbrttva>0?numberformat($nbrttva):0;

                $ttc = "";
                $net = "";
                if($ttir==0){
                    $ttc .='Montant TTC: '.numberformat($clientcommercialdoc['pt_ttc_document']);
                }else if($ttir !=0 ){
                    $ttc .='Montant TTC: '.numberformat($clientcommercialdoc['pt_ttc_document']);
                    $net .='Net à payé: '.numberformat($clientcommercialdoc['pt_net_document']);
                }
                $output .='
                    <tr>
                        <th class="badge badge-info">Total HT: '.numberformat($clientcommercialdoc['pt_ht_document']).'</th>
                        <th class="badge badge-danger">Total Tva: '.$ttva.'</th>
                        <th class="badge badge-danger">Total Ir: '.$ttir.'</th>
                        <th class="badge badge-success">'.$ttc.'</th>
                        <th class="badge badge-success">'.$net.'</th>
                    </tr>
                ';
            }else{
                $output .='<b class="text-danger">informations manquante</b>'; 
            }
            $output .='</table>';
        }else{
            $output .= '<b class="text-danger">le système ne retrouve pas les articles de ce document</b>';
        }

        $array= array(
            'infos' => $output,
            'link' => '<a href="'.base_url('facturer/'.$code_document.'').'" type="button" class="btn btn-outline-success" target="_blink">IMPRIMER</a>'
        );

        echo json_encode($array);
    }
    /**liste des article d'un reglement client  fin */


    /**afficher le formulaire de facturation de facturation debut */
    public function form_facturation_rc(){
        $this->logged_in();

        /**informations utile */
        $matricule = session('users')['matricule'];

        /*$this->form_validation->set_rules('modepayement', 'choisi le mode de payement', 'required|regex_match[/^[a-z\ ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );*/

        /*$this->form_validation->set_rules('accordpayement', 'choisi l\'accord de payement', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.'
            )
        );*/

        $this->form_validation->set_rules('tva', 'choisi la tva', 'regex_match[/^[a-z\ ]+$/]',array(
            //'required' => '%s.',  /*required|*/
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('ir', 'choisi la ir', 'regex_match[/^[a-z\ ]+$/]',array(
            //'required' => '%s.',  /*required|*/
            'regex_match' => 'caractère non pris en compte'
            )
        );
        
        $this->form_validation->set_rules('tdocument', 'choisi l\'article', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('depot', 'choisi le depot', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('client', 'choisi le client', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('delais', 'saisi le delais', 'required|regex_match[/^[0-9]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('commercial', 'choisi le commercial ou vendeur', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('document', 'choisi le document', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('article', 'choisi l\'article', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );
        
        $this->form_validation->set_rules('caisse', 'choisi la caisse', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );
        

        if($this->form_validation->run()){

            /**assurons nous que pour le depot choisi, l'article soit au préalable en stock */
            $tva = trim($this->input->post('tva'));
            $ir = trim($this->input->post('ir'));
            $code_type_document = trim($this->input->post('tdocument'));
            $depot = trim($this->input->post('depot'));
            $code_client = trim($this->input->post('client'));
            $delais = trim($this->input->post('delais'));
            $code_commercial = trim($this->input->post('commercial'));
            $code_document = trim($this->input->post('document'));
            $code_art = trim($this->input->post('article'));
            $code_caiss = trim($this->input->post('caisse'));
           
            $matricule_en = session('users')['matricule'];

            /**verifier de quel type de document il s'agit*/
			$type_document = $this->type_document($code_type_document, $matricule);
            if(!empty($type_document)){
                if($type_document == 'RC'){
                    $art_stock_depot = $this->stock->get_art_stock($code_art,$depot);
                    if(!empty($art_stock_depot)){

                        $infos = array(
                            'tva' => $tva,
                            'ir' => $ir,
                            'tdoc' => $code_type_document,
                            'depot' => $depot, 
                            'client' => $code_client,
                            'delais' => $delais,
                            'commercial' => $code_commercial,
                            'document' => $code_document,
                            'article' => $code_art,
                            'caiss' => $code_caiss
                        );

                        $prixht = 1;
                        $qte = 1;
                        $taxe = $this->taxe($tva,$ir,$matricule_en,$prixht,$qte);

                        $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);

                        $array = array(
                            'form'   => 'okk...',
                            'article' => $art_stock_depot,
                            'autres' => $infos,
                            'art_doc' => $output
                        );
                        
                    }else{
                        $array = array(
                            'success'   => '
                                <script>
                                    swal.fire("OUPS!","cet article n\'est pas en stock pour le depot choisi, procedez avant à son entré en stock","error")
                                </script>
                            '
                        );
                    }
                }else{
                    $array = array(
                        'success'   => '
                            <script>
                                swal.fire("OUPS!","ce type de document n\'est pas utile ici... utilise le menu de gauche","error")
                            </script>
                        '
                    );  
                }
            }else{
                $array = array(
                    'success'   => '
                        <script>
                            swal.fire("OUPS!","le système ne trouve pas ce type de document","error")
                        </script>
                    '
                );  
            }
                
            

        }else{
            $array = array(
                'error'   => true,
                //'modepayement_error' => form_error('modepayement'),
                //'accordpayement_error' => form_error('accordpayement'),
                'tva_error' => form_error('tva'),
                'ir_error' => form_error('ir'),
                'depot_error' => form_error('depot'),
                'client_error' => form_error('client'),
                'delais_error' => form_error('delais'),
                'commercial_error' => form_error('commercial'),
                'document_error' => form_error('document'),
                'article_error' => form_error('article'),
                'tdocument_error' => form_error('tdocument')
            );
        }

        echo json_encode($array);
    }
    /**afficher le formulaire de facturation de facturation fin */

    /**operation sur la création d'une facture reglement client debut */
    public function operationfacturerc(){
        $this->logged_in();

        /**message d'erreur (connexion a la base des données perdu) debut*/
            $message_db = 	array(
                'success'   => '
                    <script>
                        swal.fire("OUPS!","connexion à la base des données perdu","info")
                    </script>
                '
            );
        /**message d'erreur (connexion a la base des données perdu) fin*/

        $this->form_validation->set_rules('quantes', 'quantité', 'required|regex_match[/^[0-9]+$/]',array(
			'required' => 'entre la %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));
		
		$this->form_validation->set_rules('prix_h', 'prix ht', 'required|is_natural',array(
			'required' => 'le %s ne doit pas être vide',
			'is_natural' => 'caractère(s) non autorisé(s)'
		));

        if($this->form_validation->run()){

            $output = "";
			$tva = trim($this->input->post('tva_doc'));
            $ir = trim($this->input->post('ir_doc'));
            $code_type_document = trim($this->input->post('t_doc'));
            $code_depot = trim($this->input->post('dep'));
            $code_client = trim($this->input->post('cli'));
            $delais = trim($this->input->post('delai'));
            $code_vendeur = trim($this->input->post('vendeur'));
            $code_caisse = trim($this->input->post('caiss'));
            
            $code_document = trim($this->input->post('doc_m'));
			$code_article = trim($this->input->post('art'));
            $qte = trim($this->input->post('quantes'));
            $description_art = trim($this->input->post('description_art'));
            
            $prixh = trim($this->input->post('prix_h'));

			$matricule_en = session('users')['matricule'];
			$matricule_emp = session('users')['matricule_emp'];
            $agencecon = session('users')['matricule_ag'];
            
            /*selectionnons le code de l'agence à partir de la caisse si l'agence est vide*/
            $agencecaisse = $this->commerce->agencecaisse($code_caisse,$matricule_en);
            $agence = empty($agencecon)?$agencecaisse['code_agence']:$agencecon;
            
            /**effectuer l'opération selon selon le type de document et le code de l'entreprise*/
			if(!empty($code_type_document) && !empty($matricule_en)){
                /**verifier de quel type de document il s'agit*/
                $type_document = $this->type_document($code_type_document, $matricule_en);
                if(!empty($type_document)){
                    if($type_document == 'RC'){
                        $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
                        if(!empty($art_stock_depot)){
                            
                             /*on verifie si le pht est >= au prix defini, si non on effectue une verification d'indentité*/
                            if($prixh >= $art_stock_depot['prix_hors_taxe']){
                            
                                /*on s'assure que la qte est en stock pour l'article choisi*/
                                if($qte <= $art_stock_depot['quantite']){
    
                                    /**on test pour voir si le document existe déjà ou pas */
                                    $verify_document = $this->stock->doc_stock($code_type_document, $code_document, $matricule_en);
                                    if(empty($verify_document)){
                                        /***************************************** LE DOCUMENT N'EXISTE PAS DEBUT *************************************** */
                                       
                                        /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                        $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);
                                        /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                        
                                        $detterestante = ($taxe['prixnet']==$prixh)?$taxe['prixttc']:$taxe['prixnet'];
                                        /**on creer le document */
                                        $input_doc = array(
                                            'code_document'=>$code_document,
                                            'nom_document'=> 'RC-DOCUMENT-TRC-'.code(10).'-'.date('d-m-Y'),
                                            'date_creation_doc'=>dates(),
                                            'code_type_doc'=> $code_type_document,
                                            'depot_doc'=> $code_depot,
                                            'code_client_document'=> $code_client,
                                            'code_caisse' => $code_caisse,
                                            'code_agence_doc' => $agence,
                                            'delais_reg_doc'=> $delais,
                                            'pt_ht_document'=> ($prixh * $qte),
                                            'pt_ttc_document'=> $taxe['prixttc'],
                                            'pt_net_document'=> $taxe['prixnet'],
                                            'dette_restante'  => $detterestante,
                                            'dette_regler' => 0,
                                            'code_employe'=> $matricule_emp,
                                            'code_entreprie'=> $matricule_en,
                                        );
                                        $new_document = $this->stock->new_document($input_doc);
                                        if($new_document){
    
                                            /**on enrégistre l'historique des articles de ce document dans la table article_document*/
                                            $input_art_doc = array(
                                                'code_article'=> $code_article,
                                                'code_document'=> $code_document,
                                                'quantite'=> $qte,
                                                'pu_HT'=> $prixh,
                                                'pt_HT'=> ($prixh * $qte),
                                                'description_art' => $description_art,
                                                'code_emp_art_doc'=> $matricule_emp,
                                                'code_en_art_doc'=> $matricule_en, 
                                                'date_creer_art_doc'=> dates()
                                            );
                                            $save_art_from_doc = $this->stock->article_document($input_art_doc);
                                            if($save_art_from_doc){
                                                /**gestion du stock debut */
                                                $input_stock_val = array(
    												'code_employe'=> $matricule_emp,
    												'code_depot'=> $code_depot,
    												'code_entreprise'=> $matricule_en,
    												'quantite'=> (abs(($art_stock_depot['quantite'] - $qte))),
    												'date_modifier_stock'=> dates()
    											);
                                                $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
    											if($update_art_st){
    												/**liste des articles du document */
    												$output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
    												$array = array(
    													'success' => 'article ajouter au panier',
    													'art_doc' => $output
    												);
    											}else{
    												$array = $message_db;
    											}
    
                                                /**gestion du stock fin */
                                            }else{
                                                $array = $message_db;
                                            }
                                        }else{
                                            $array = $message_db;
                                        }
    
                                        /***************************************** LE DOCUMENT N'EXISTE PAS FIN *************************************** */
                                    }else{
                                        /***************************************** LE DOCUMENT EXISTE DEBUT *************************************** */
    
                                        /**on remet ceci ici pour s'assurer de la qualité de l'information */
                                        $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
    
                                        /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                        $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);
                                        /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
    
                                        /**pour le document encour, on verifi s'il contient l'article encour */
                                        $verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
                                        if(empty($verify_art_document)){
                                            /*******************************l'article n'existe pas dans le document debut *******************/
                                            
                                            /**on selectionne le document en question  */
                                            $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                            if(!empty($document)){ 
                                                $detterestante = ($document['pt_net_document']==$document['pt_ht_document'])?($document['pt_ttc_document'] - $document['dette_regler'])+$taxe['prixttc']:($document['pt_net_document'] - $document['dette_regler'])+$taxe['prixnet'];  
                                                
                                                $input_doc = array(
                                                    'date_modifier_doc'=>dates(),
                                                    'pt_ht_document'=> ($document['pt_ht_document'] + ($prixh * $qte)),
                                                    'pt_ttc_document'=> ($document['pt_ttc_document'] + $taxe['prixttc']),
                                                    'pt_net_document'=> ($document['pt_net_document'] + $taxe['prixnet']),
                                                    'dette_restante'  => $detterestante,
                                                    'dette_regler' => $document['dette_regler'],
                                                    'code_employe'=> $matricule_emp,
                                                    'code_entreprie'=> $matricule_en,
                                                );
        
                                                $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                if($update_doc){
    
                                                     /**on enrégistre l'historique des articles de ce document dans la table article_document*/
                                                    $input_art_doc = array(
                                                        'code_article'=> $code_article,
                                                        'code_document'=> $code_document,
                                                        'quantite'=> $qte,
                                                        'pu_HT'=> $prixh,
                                                        'pt_HT'=> ($prixh * $qte),
                                                        'description_art' => $description_art,
                                                        'code_emp_art_doc'=> $matricule_emp,
                                                        'code_en_art_doc'=> $matricule_en, 
                                                        'date_creer_art_doc'=> dates()
                                                    );
                                                    $save_art_from_doc = $this->stock->article_document($input_art_doc);
                                                    if($save_art_from_doc){
                                                        /**gestion du stock debut */
                                                        $input_stock_val = array(
                                                            'code_employe'=> $matricule_emp,
                                                            'code_depot'=> $code_depot,
                                                            'code_entreprise'=> $matricule_en,
                                                            'quantite'=> (abs(($art_stock_depot['quantite'] - $qte))),
                                                            'date_modifier_stock'=> dates()
                                                        );
                                                        $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
                                                        if($update_art_st){
                                                            /**liste des articles du document */
                                                            $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                            $array = array(
                                                                'success' => 'article ajouter au panier',
                                                                'art_doc' => $output
                                                            );
                                                        }else{
                                                            $array = $message_db;
                                                        }
    
                                                        /**gestion du stock fin */
                                                    }else{
                                                        $array = $message_db;
                                                    }
    
    
                                                }else{
                                                    $array = $message_db;
                                                }
    
                                            }else{
                                                $array = $message_db;
                                            }
                                            /*******************************l'article n'existe pas dans le document fin *******************/
                                        }else{
                                            /*******************************l'article existe  dans le document debut *******************/
                                            
           
                                            /**on remet ceci ici pour s'assurer de la qualité de l'information */
                                            $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
    
                                            /**on renvoit la quantité prélévé précedement en stock pour le depot choisi*/
                                            $qte_initial = ($verify_art_document['quantite'] + $art_stock_depot['quantite']);
                                            $input_stock_val = array(
                                                'code_employe'=> $matricule_emp,
                                                'code_depot'=> $code_depot,
                                                'code_entreprise'=> $matricule_en,
                                                'quantite'=>  $qte_initial,
                                                'date_modifier_stock'=> dates()
                                            );
                                            
                                           $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
    										if($update_art_st){
    
                                                
                                                /**on selectionne le document en question */
                                                $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                                if(!empty($document)){
                                                    
                                                    /**on modifie le document
                                                     * 1: on enleve le prix total ht de l'article sur le prix total ht du document
                                                     * 2: on enleve le prix total ttc de l'article sur le prix total ttc du document
                                                     * 3: on enleve le prix total net de l'article sur le prix total net du document
                                                     * 4: on test qui porte la tva ou ir sur le document 
                                                     * 5: on enleve le total ttc et ou net de l'article sur la dette restante
                                                    */


                                                    /** selectionne le pourcentage de la tva dans la table taxe*/
                                                    $pourcentagetva = $this->commerce->specifictaxe($tva,$matricule_en);
                                                    $action1 = (!empty($pourcentagetva))?$pourcentagetva['pourcentage']:0;
    
                                                    //** selectionne le pourcentage de la ir dans la table taxe*
                                                    $pourcentageir = $this->commerce->specifictaxe($ir,$matricule_en);
                                                    $action2 = (!empty($pourcentageir))?$pourcentageir['pourcentage']:0;
    
                                                    /**taxes et prix ttc de l'article debut */
                                                    $tva_art = (($action1 * $verify_art_document['pt_HT'])/100);
                                                    $ir_art = (($action2 * $verify_art_document['pt_HT'])/100);
                                                    $prix_ttc_art =  ($verify_art_document['pt_HT'] + $tva_art);
                                                    $prix_net_art =  ($verify_art_document['pt_HT'] + ($ir_art));
                                                    /**taxes et prix ttc de l'article fint */

                                                    $detterestante1 = ($document['pt_net_document']==$document['pt_ht_document'])?($document['pt_ttc_document'] - $document['dette_regler'])-$prix_ttc_art:($document['pt_net_document'] - $document['dette_regler'])-$prix_net_art;
                                                    $input_doc = array(
                                                        'date_modifier_doc'=>dates(),
                                                        'pt_ht_document'=> ($document['pt_ht_document'] - $verify_art_document['pt_HT']),
                                                        'pt_ttc_document'=> ($document['pt_ttc_document'] - $prix_ttc_art),
                                                        'pt_net_document'=> ($document['pt_net_document'] - $prix_net_art),
                                                        'dette_restante'  => $detterestante1,
                                                        'dette_regler' => $document['dette_regler'],
                                                        'code_employe'=> $matricule_emp,
                                                        'code_entreprie'=> $matricule_en,
                                                    );
                                                    
                                                    $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                    if($update_doc){
    
                                                        /**on remet ici pour rafraichir les informations */
                                                        $documents = $this->commerce->specificdocument($code_document,$matricule_en);
    
                                                        /**on remet ici pour rafraichir les informations */
                                                        $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);

                                                        /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                                        $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);
                                                        /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/

    
                                                        /**une fois que tout est en ordre, on met ajour le document avec les nouvelles valeurs */
                                                        //$detterestante2 = ($taxe['prixnet'] == ($prixh * $qte))?$taxe['prixttc']:$taxe['prixnet']; 
                                                        $detterestante2 = ($taxe['prixnet'] == ($prixh * $qte))?($documents['pt_ttc_document'] - $documents['dette_regler'])+$taxe['prixttc']:($documents['pt_net_document'] - $documents['dette_regler'])+$taxe['prixnet'];
                                                        $input_doc = array(
                                                            'date_modifier_doc'=>dates(),
                                                            'pt_ht_document'=> ($documents['pt_ht_document'] + ($prixh * $qte)),
                                                            'pt_ttc_document'=> ($documents['pt_ttc_document'] + $taxe['prixttc']),
                                                            'pt_net_document'=> ($documents['pt_net_document'] + $taxe['prixnet']),
                                                            'dette_restante'  => $detterestante2,
                                                            'dette_regler' => $documents['dette_regler'],
                                                            'code_employe'=> $matricule_emp,
                                                            'code_entreprie'=> $matricule_en,
                                                        );
                
                                                        $update_doc = $this->commerce->updatedocument($documents['code_document'],$documents['code_entreprie'],$input_doc);
                                                        if($update_doc){
    
                                                            /**une fois le document mis a jour, on modifi l'article dans le document en question */
                                                            /**on modifie l'article encour dans le document encour (table article_document)*/
                                                            
                                                            $input_art_doc = array(
                                                                'code_document'=> $code_document,
                                                                'quantite'=> $qte,
                                                                'pu_HT'=> $prixh,
                                                                'pt_HT'=> ($prixh * $qte),
                                                                'description_art' => $description_art,
                                                                'code_emp_art_doc'=> $matricule_emp,
                                                                'code_en_art_doc'=> $matricule_en, 
                                                                'date_creer_art_doc'=> dates()
                                                            );
                                                            $update_art_document = $this->stock->update_article_document($code_document,$code_article,$input_art_doc);
                                                            if($update_art_document){
                                                                /**gestion du stock debut */
                                                                $input_stock_val = array(
                                                                    'code_employe'=> $matricule_emp,
                                                                    'code_depot'=> $code_depot,
                                                                    'code_entreprise'=> $matricule_en,
                                                                    'quantite'=> (abs(($art_stock_depot['quantite'] - $qte))),
                                                                    'date_modifier_stock'=> dates()
                                                                );
                                                                $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
                                                                if($update_art_st){
                                                                    /**liste des articles du document */
                                                                    $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                                    $array = array(
                                                                        'success' => 'article ajouter au panier '.$documents['pt_ht_document'],
                                                                        'art_doc' => $output
                                                                    );
                                                                }else{
                                                                    $array = $message_db;
                                                                }
    
                                                                /**gestion du stock fin */
                                                            }else{
                                                                $array = $message_db;
                                                            }
                                                        }else{
                                                            $array = $message_db;
                                                        }
                                                    }else{
                                                        $array = $message_db;
                                                    }
                                                }else{
                                                    $array = $message_db;
                                                }
                                            }else{
                                                $array = $message_db;
                                            }
                                            /*******************************l'article existe  dans le document fin *******************/
                                        }
                                            
                                        
    
                                        /***************************************** LE DOCUMENT EXISTE FIN *************************************** */
                                    }
                                    
                                }else{
                                    $array = array(
                                        'success'   => '
                                            <script>
                                                swal.fire("OUPS!","quantité en stock insuffisante pour ce depot","error")
                                            </script>
                                        '
                                    );
                                }
                                
                            }else if($prixh < $art_stock_depot['prix_hors_taxe']){
                                	$this->form_validation->set_rules('login', 'login', 'required',array(
                        			    'required' => 'le %s est necessaire',
                            		));
                            		$this->form_validation->set_rules('pass', 'mot de passe', 'required',array(
                            			'required' => 'le %s est necessaire',
                            		));
                                    
                                    if($this->form_validation->run()){
                                    
                                            $user = trim($this->input->post('login'));
                                            $pass = trim($this->input->post('pass'));
                                            
                                            /**login employé */
                                            $login = $this->users->login_emp1($user);
                                            if($login){
                                                if(password_verify($pass, $login['password_emp'])){
                                                    if($login['mat_ag'] == "" || $login['mat_serv']==""){
                                                        $array = array(
                                                            'show'   => 'employé trouvé'
                                                        );
                                                        
                                                        /***==========================================================================================================*/
                                                        
                                                        
                                                         /*on s'assure que la qte est en stock pour l'article choisi*/
                                                           /*on s'assure que la qte est en stock pour l'article choisi*/
                                                            if($qte <= $art_stock_depot['quantite']){
                                
                                                                /**on test pour voir si le document existe déjà ou pas */
                                                                $verify_document = $this->stock->doc_stock($code_type_document, $code_document, $matricule_en);
                                                                if(empty($verify_document)){
                                                                    /***************************************** LE DOCUMENT N'EXISTE PAS DEBUT *************************************** */
                                                                
                                                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                                                    $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);
                                                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                                                    
                                                                    $detterestante = ($taxe['prixnet']==$prixh)?$taxe['prixttc']:$taxe['prixnet'];
                                                                    /**on creer le document */
                                                                    $input_doc = array(
                                                                        'code_document'=>$code_document,
                                                                        'nom_document'=> 'RC-DOCUMENT-TRC-'.code(10).'-'.date('d-m-Y'),
                                                                        'date_creation_doc'=>dates(),
                                                                        'code_type_doc'=> $code_type_document,
                                                                        'depot_doc'=> $code_depot,
                                                                        'code_client_document'=> $code_client,
                                                                        'code_caisse' => $code_caisse,
                                                                        'code_agence_doc' => $agence,
                                                                        'delais_reg_doc'=> $delais,
                                                                        'pt_ht_document'=> ($prixh * $qte),
                                                                        'pt_ttc_document'=> $taxe['prixttc'],
                                                                        'pt_net_document'=> $taxe['prixnet'],
                                                                        'dette_restante'  => $detterestante,
                                                                        'dette_regler' => 0,
                                                                        'code_employe'=> $matricule_emp,
                                                                        'code_entreprie'=> $matricule_en,
                                                                    );
                                                                    $new_document = $this->stock->new_document($input_doc);
                                                                    if($new_document){
                                
                                                                        /**on enrégistre l'historique des articles de ce document dans la table article_document*/
                                                                        $input_art_doc = array(
                                                                            'code_article'=> $code_article,
                                                                            'code_document'=> $code_document,
                                                                            'quantite'=> $qte,
                                                                            'pu_HT'=> $prixh,
                                                                            'pt_HT'=> ($prixh * $qte),
                                                                            'description_art' => $description_art,
                                                                            'code_emp_art_doc'=> $matricule_emp,
                                                                            'code_en_art_doc'=> $matricule_en, 
                                                                            'date_creer_art_doc'=> dates()
                                                                        );
                                                                        $save_art_from_doc = $this->stock->article_document($input_art_doc);
                                                                        if($save_art_from_doc){
                                                                            /**gestion du stock debut */
                                                                            $input_stock_val = array(
                                                                                'code_employe'=> $matricule_emp,
                                                                                'code_depot'=> $code_depot,
                                                                                'code_entreprise'=> $matricule_en,
                                                                                'quantite'=> (abs(($art_stock_depot['quantite'] - $qte))),
                                                                                'date_modifier_stock'=> dates()
                                                                            );
                                                                            $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
                                                                            if($update_art_st){
                                                                                /**liste des articles du document */
                                                                                $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                                                $array = array(
                                                                                    'success' => 'article ajouter au panier',
                                                                                    'art_doc' => $output
                                                                                );
                                                                            }else{
                                                                                $array = $message_db;
                                                                            }
                                
                                                                            /**gestion du stock fin */
                                                                        }else{
                                                                            $array = $message_db;
                                                                        }
                                                                    }else{
                                                                        $array = $message_db;
                                                                    }
                                
                                                                    /***************************************** LE DOCUMENT N'EXISTE PAS FIN *************************************** */
                                                                }else{
                                                                    /***************************************** LE DOCUMENT EXISTE DEBUT *************************************** */
                                
                                                                    /**on remet ceci ici pour s'assurer de la qualité de l'information */
                                                                    $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
                                
                                                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                                                    $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);
                                                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                
                                                                    /**pour le document encour, on verifi s'il contient l'article encour */
                                                                    $verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
                                                                    if(empty($verify_art_document)){
                                                                        /*******************************l'article n'existe pas dans le document debut *******************/
                                                                        
                                                                        /**on selectionne le document en question  */
                                                                        $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                                                        if(!empty($document)){ 
                                                                            $detterestante = ($document['pt_net_document']==$document['pt_ht_document'])?($document['pt_ttc_document'] - $document['dette_regler'])+$taxe['prixttc']:($document['pt_net_document'] - $document['dette_regler'])+$taxe['prixnet'];  
                                                                            
                                                                            $input_doc = array(
                                                                                'date_modifier_doc'=>dates(),
                                                                                'pt_ht_document'=> ($document['pt_ht_document'] + ($prixh * $qte)),
                                                                                'pt_ttc_document'=> ($document['pt_ttc_document'] + $taxe['prixttc']),
                                                                                'pt_net_document'=> ($document['pt_net_document'] + $taxe['prixnet']),
                                                                                'dette_restante'  => $detterestante,
                                                                                'dette_regler' => $document['dette_regler'],
                                                                                'code_employe'=> $matricule_emp,
                                                                                'code_entreprie'=> $matricule_en,
                                                                            );
                                    
                                                                            $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                                            if($update_doc){
                                
                                                                                /**on enrégistre l'historique des articles de ce document dans la table article_document*/
                                                                                $input_art_doc = array(
                                                                                    'code_article'=> $code_article,
                                                                                    'code_document'=> $code_document,
                                                                                    'quantite'=> $qte,
                                                                                    'pu_HT'=> $prixh,
                                                                                    'pt_HT'=> ($prixh * $qte),
                                                                                    'description_art' => $description_art,
                                                                                    'code_emp_art_doc'=> $matricule_emp,
                                                                                    'code_en_art_doc'=> $matricule_en, 
                                                                                    'date_creer_art_doc'=> dates()
                                                                                );
                                                                                $save_art_from_doc = $this->stock->article_document($input_art_doc);
                                                                                if($save_art_from_doc){
                                                                                    /**gestion du stock debut */
                                                                                    $input_stock_val = array(
                                                                                        'code_employe'=> $matricule_emp,
                                                                                        'code_depot'=> $code_depot,
                                                                                        'code_entreprise'=> $matricule_en,
                                                                                        'quantite'=> (abs(($art_stock_depot['quantite'] - $qte))),
                                                                                        'date_modifier_stock'=> dates()
                                                                                    );
                                                                                    $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
                                                                                    if($update_art_st){
                                                                                        /**liste des articles du document */
                                                                                        $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                                                        $array = array(
                                                                                            'success' => 'article ajouter au panier',
                                                                                            'art_doc' => $output
                                                                                        );
                                                                                    }else{
                                                                                        $array = $message_db;
                                                                                    }
                                
                                                                                    /**gestion du stock fin */
                                                                                }else{
                                                                                    $array = $message_db;
                                                                                }
                                
                                
                                                                            }else{
                                                                                $array = $message_db;
                                                                            }
                                
                                                                        }else{
                                                                            $array = $message_db;
                                                                        }
                                                                        /*******************************l'article n'existe pas dans le document fin *******************/
                                                                    }else{
                                                                        /*******************************l'article existe  dans le document debut *******************/
                                                                        
                                    
                                                                        /**on remet ceci ici pour s'assurer de la qualité de l'information */
                                                                        $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
                                
                                                                        /**on renvoit la quantité prélévé précedement en stock pour le depot choisi*/
                                                                        $qte_initial = ($verify_art_document['quantite'] + $art_stock_depot['quantite']);
                                                                        $input_stock_val = array(
                                                                            'code_employe'=> $matricule_emp,
                                                                            'code_depot'=> $code_depot,
                                                                            'code_entreprise'=> $matricule_en,
                                                                            'quantite'=>  $qte_initial,
                                                                            'date_modifier_stock'=> dates()
                                                                        );
                                                                        
                                                                    $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
                                                                        if($update_art_st){
                                
                                                                            
                                                                            /**on selectionne le document en question */
                                                                            $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                                                            if(!empty($document)){
                                                                                
                                                                                /**on modifie le document
                                                                                * 1: on enleve le prix total ht de l'article sur le prix total ht du document
                                                                                * 2: on enleve le prix total ttc de l'article sur le prix total ttc du document
                                                                                * 3: on enleve le prix total net de l'article sur le prix total net du document
                                                                                * 4: on test qui porte la tva ou ir sur le document 
                                                                                * 5: on enleve le total ttc et ou net de l'article sur la dette restante
                                                                                */


                                                                                /** selectionne le pourcentage de la tva dans la table taxe*/
                                                                                $pourcentagetva = $this->commerce->specifictaxe($tva,$matricule_en);
                                                                                $action1 = (!empty($pourcentagetva))?$pourcentagetva['pourcentage']:0;
                                
                                                                                //** selectionne le pourcentage de la ir dans la table taxe*
                                                                                $pourcentageir = $this->commerce->specifictaxe($ir,$matricule_en);
                                                                                $action2 = (!empty($pourcentageir))?$pourcentageir['pourcentage']:0;
                                
                                                                                /**taxes et prix ttc de l'article debut */
                                                                                $tva_art = (($action1 * $verify_art_document['pt_HT'])/100);
                                                                                $ir_art = (($action2 * $verify_art_document['pt_HT'])/100);
                                                                                $prix_ttc_art =  ($verify_art_document['pt_HT'] + $tva_art);
                                                                                $prix_net_art =  ($verify_art_document['pt_HT'] + ($ir_art));
                                                                                /**taxes et prix ttc de l'article fint */

                                                                                $detterestante1 = ($document['pt_net_document']==$document['pt_ht_document'])?($document['pt_ttc_document'] - $document['dette_regler'])-$prix_ttc_art:($document['pt_net_document'] - $document['dette_regler'])-$prix_net_art;
                                                                                $input_doc = array(
                                                                                    'date_modifier_doc'=>dates(),
                                                                                    'pt_ht_document'=> ($document['pt_ht_document'] - $verify_art_document['pt_HT']),
                                                                                    'pt_ttc_document'=> ($document['pt_ttc_document'] - $prix_ttc_art),
                                                                                    'pt_net_document'=> ($document['pt_net_document'] - $prix_net_art),
                                                                                    'dette_restante'  => $detterestante1,
                                                                                    'dette_regler' => $document['dette_regler'],
                                                                                    'code_employe'=> $matricule_emp,
                                                                                    'code_entreprie'=> $matricule_en,
                                                                                );
                                                                                
                                                                                $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                                                if($update_doc){
                                
                                                                                    /**on remet ici pour rafraichir les informations */
                                                                                    $documents = $this->commerce->specificdocument($code_document,$matricule_en);
                                
                                                                                    /**on remet ici pour rafraichir les informations */
                                                                                    $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);

                                                                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                                                                    $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);
                                                                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/

                                
                                                                                    /**une fois que tout est en ordre, on met ajour le document avec les nouvelles valeurs */
                                                                                    //$detterestante2 = ($taxe['prixnet'] == ($prixh * $qte))?$taxe['prixttc']:$taxe['prixnet']; 
                                                                                    $detterestante2 = ($taxe['prixnet'] == ($prixh * $qte))?($documents['pt_ttc_document'] - $documents['dette_regler'])+$taxe['prixttc']:($documents['pt_net_document'] - $documents['dette_regler'])+$taxe['prixnet'];
                                                                                    $input_doc = array(
                                                                                        'date_modifier_doc'=>dates(),
                                                                                        'pt_ht_document'=> ($documents['pt_ht_document'] + ($prixh * $qte)),
                                                                                        'pt_ttc_document'=> ($documents['pt_ttc_document'] + $taxe['prixttc']),
                                                                                        'pt_net_document'=> ($documents['pt_net_document'] + $taxe['prixnet']),
                                                                                        'dette_restante'  => $detterestante2,
                                                                                        'dette_regler' => $documents['dette_regler'],
                                                                                        'code_employe'=> $matricule_emp,
                                                                                        'code_entreprie'=> $matricule_en,
                                                                                    );
                                            
                                                                                    $update_doc = $this->commerce->updatedocument($documents['code_document'],$documents['code_entreprie'],$input_doc);
                                                                                    if($update_doc){
                                
                                                                                        /**une fois le document mis a jour, on modifi l'article dans le document en question */
                                                                                        /**on modifie l'article encour dans le document encour (table article_document)*/
                                                                                        
                                                                                        $input_art_doc = array(
                                                                                            'code_document'=> $code_document,
                                                                                            'quantite'=> $qte,
                                                                                            'pu_HT'=> $prixh,
                                                                                            'pt_HT'=> ($prixh * $qte),
                                                                                            'description_art' => $description_art,
                                                                                            'code_emp_art_doc'=> $matricule_emp,
                                                                                            'code_en_art_doc'=> $matricule_en, 
                                                                                            'date_creer_art_doc'=> dates()
                                                                                        );
                                                                                        $update_art_document = $this->stock->update_article_document($code_document,$code_article,$input_art_doc);
                                                                                        if($update_art_document){
                                                                                            /**gestion du stock debut */
                                                                                            $input_stock_val = array(
                                                                                                'code_employe'=> $matricule_emp,
                                                                                                'code_depot'=> $code_depot,
                                                                                                'code_entreprise'=> $matricule_en,
                                                                                                'quantite'=> (abs(($art_stock_depot['quantite'] - $qte))),
                                                                                                'date_modifier_stock'=> dates()
                                                                                            );
                                                                                            $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
                                                                                            if($update_art_st){
                                                                                                /**liste des articles du document */
                                                                                                $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                                                                $array = array(
                                                                                                    'success' => 'article ajouter au panier '.$documents['pt_ht_document'],
                                                                                                    'art_doc' => $output
                                                                                                );
                                                                                            }else{
                                                                                                $array = $message_db;
                                                                                            }
                                
                                                                                            /**gestion du stock fin */
                                                                                        }else{
                                                                                            $array = $message_db;
                                                                                        }
                                                                                    }else{
                                                                                        $array = $message_db;
                                                                                    }
                                                                                }else{
                                                                                    $array = $message_db;
                                                                                }
                                                                            }else{
                                                                                $array = $message_db;
                                                                            }
                                                                        }else{
                                                                            $array = $message_db;
                                                                        }
                                                                        /*******************************l'article existe  dans le document fin *******************/
                                                                    }
                                                                        
                                                                    
                                
                                                                    /***************************************** LE DOCUMENT EXISTE FIN *************************************** */
                                                                }
                                                                
                                                            }else{
                                                                $array = array(
                                                                    'success'   => '
                                                                        <script>
                                                                            swal.fire("OUPS!","quantité en stock insuffisante pour ce depot","error")
                                                                        </script>
                                                                    '
                                                                );
                                                            }
                                                        
                                                      /***==========================================================================================================*/ 
                                                    }else{
                                                    $array = array(
                                                        'show'   => 'vous n\'êtes pas autoriser a éffectué cette opération'
                                                    );  
                                                }
                                            }else{
                                               $array = array(
                                                    'show'   => 'mot de passe incorrect ou mauvais... reessai'
                                                ); 
                                            }
                                        }else{
                                           $array = array(
                                                'show' => 'login incorrect ou inexistant'
                                            ); 
                                        }
                                    }else{
                            			$array = array(
                            				'error'   => true,
                            				'login_error' => form_error('login'),
                            				'pass_error' => form_error('pass')
                            			);
                            		}                
                                                    
                            }
                        }else{
                            $array = array(
                                'success'   => '
                                    <script>
                                        swal.fire("OUPS!","cet article n\'est pas en stock pour le depot choisi, procedez avant à son entré en stock","error")
                                    </script>
                                '
                            );
                        }
                    }else{
                        $array = array(
                            'success'   => '
                                <script>
                                    swal.fire("OUPS!","ce type de document n\'est pas utile ici... utilise le menu de gauche","error")
                                </script>
                            '
                        );  
                    }
                }else{
                    $array = array(
                        'success'   => '
                            <script>
                                swal.fire("OUPS!","le système ne trouve pas ce type de document","error")
                            </script>
                        '
                    );  
                }
            }

        }else{
			$array = array(
				'error'   => true,
				'quantes_error' => form_error('quantes'),
				'prix_h_error' => form_error('prix_h')
			);
		}

		echo json_encode($array);
    }
    /**operation sur la création d'une facture reglement client fin */
    

    /************************gestion des règlements client(prêt) fin*************************************/

    /****************************gestion des proformats debut************************** */

    /**affiche la page de proformat */
    public function proformat(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

		/**informations utile */
		$matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];

        //if(!empty($agence)){
            if(!empty(session('users')['nom_emp'])){
                /** selectionne les depots en fonctions de l'entreprise et ou de l'agence*/
                $data['depots'] = $this->commerce->getalldepot($matricule,$agence);


                /**affiche la liste des clients d'une entreprise */
                $data['clients'] = $this->commerce->all_client($matricule);

                /**affiche les vendeur: c'est la personne connecté au momement de la vente */
                $data['vendeur'][] = array(
                    'matricule' => session('users')['matricule_emp'],
                    'nom' => session('users')['nom_emp']
                );


                /**affiche la liste des caisse en fonction de l'entreprise et ou de l'agence */
                $data['caisses'] = $this->commerce->allcaisseagence($matricule,$agence);
                
                
                
                /**afficher le code et le nom d'un document générer*/
                $data['code_doc'] = array(
                    'FP-DOC-'.code(10) => 'FP-DOCUMENT-TFP-'.code(10).'-'.date('d-m-Y'),
                );



                /**liste des articles de l'entreprise*/
                $data['articles'] = $this->commerce->all_article_entreprise($matricule);

                /**liste des taxe */
                $data['taxes'] = $this->commerce->all_taxe_entreprise($matricule);

                /**affiche la liste des type de documents pour une entreprise donné */
                $data['docs'] = $this->commerce->type_doc($matricule);
                
                /*afficher les proformat déjà creer pour une potentiel modification futur*/
                $abrev = 'FP';
                $output ="";
                $docus = $this->commerce->docu_cli_en_ag($matricule,$agence,$abrev);
                if(!empty($docus)){
                    $output = $docus; 
                }
                $data['get_docs'] = $output;
                
                //$data['get_docs'] = $this->commerce->document2("FP",$matricule); 
                
                $this->load->view('commerce/proformat',$data);
            }else{
                flash('warning','Connecte toi en tant que utilisateur pour faire cette opération');
                redirect('home'); 
            }
        /*}else{
            flash('info','Connecte toi à une agence pour faire cette opération');
            redirect('home');
        }*/

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }


    /**afficher la liste de tous les règlement client d'une entreprise debut */
    public function all_proformat(){
        $this->logged_in();

        $matricule_en = session('users')['matricule'];
        $agencert = session('users')['matricule_ag'];
        $abrev = "FP";
        $output = "";
        $recherche = trim($this->input->post('rechercher'));
        if(!empty($recherche)){
            /**on cherche une facture en fonction du client ou du code du client du type de document, de l'entreprise et ou de l'agence*/
            $docrtvente = $this->commerce->documentrtventerecherche($abrev,$matricule_en,$agencert,$recherche); 
            if(!empty($docrtvente)){
                foreach ($docrtvente as $key => $value) {
                    $output .='<div class="row"> <div class="col-md-11"><button type="button" id="'.$value['code_document'].'" class="list-group-item list-group-item-action art_fp"><b>Code:</b> '.$value['code_document'].' | <b>Nom:</b> '.$value['nom_document'].' | <b>Date:</b> '.dateformat($value['date_creation_doc']).' | <b class="badge badge-primary">Caisse: '.$value['libelle_caisse'].'</b> <b class="badge badge-success">Client: '.$value['nom_cli'].'</b> | <b class="badge badge-warning">'.$value['nom_ag'].'</b> | </button></div><div class="col-md-1"><button class="btn btn-icon btn-circle btn-label-linkedin editdocfp" id="'.$value['code_document'].'"><i class="fa fa-edit"></i></button></div></div>';  
                }
            }else{
                $output .='<b class="text-danger">aucune facture ne correspond à ce client</b>';
            }
            $array = array(
                'infos' => $output,
            );
        }else{
            /**
             * 1: si l'agence est connecté, on cherche pour une agence donné
             * 2: si non on cherche pour une entreprise
             *
            /**on compte le nombre de reglément ticket en fonction de l'entreprise et ou de l'agence */
            $nbrdocrt = $this->commerce->countdocrtagence($matricule_en,$agencert,$abrev);
            
            /** PAGINATION DEBUT */
            $config = array();
            $config["base_url"] = "#";
            $config["total_rows"] = $nbrdocrt;
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
            $config["cur_tag_open"] = "<li class='page-item active'><a href='#'><span class='page-link'><span class='sr-only'>(current)</span>";
            $config["cur_tag_close"] = "</span></a></li>";
            $config["num_tag_open"] = '<li class="page-item page-link">';
            $config["num_tag_close"] = "</li>";
            $config["num_links"] = 1;
            $this->pagination->initialize($config);
            
            $page = (($this->uri->segment(2) - 1) * $config["per_page"]);
            /** PAGINATION FIN */
            
            /**on selectionne les documents en fonction du type de document, de l'entreprise et ou de l'agence
             * au sorti on aura tous les informations sur le documents le client et la caisse
            */
            $docrtvente = $this->commerce->documentrtvente($abrev,$matricule_en,$agencert,$config["per_page"],$page); 
            if(!empty($docrtvente)){
                foreach ($docrtvente as $key => $value) {
                    $output .='<div class="row"> <div class="col-md-11"><button type="button" id="'.$value['code_document'].'" class="list-group-item list-group-item-action art_fp"><b>Code:</b> '.$value['code_document'].' | <b>Nom:</b> '.$value['nom_document'].' | <b>Date:</b> '.dateformat($value['date_creation_doc']).' | <b class="badge badge-primary">Caisse: '.$value['libelle_caisse'].'</b> <b class="badge badge-success">Client: '.$value['nom_cli'].'</b> | <b class="badge badge-warning">'.$value['nom_ag'].'</b> </button></div><div class="col-md-1"><button class="btn btn-icon btn-circle btn-label-linkedin editdocfp" id="'.$value['code_document'].'"><i class="fa fa-edit"></i></button></div></div>';  
                }
            }else{
                $output .='<b class="text-danger">aucune facture enrégistrer pour le moment</b>';
            }

            $array = array(
                'infos' => $output,
                'pagination_link' => $this->pagination->create_links()
            );
        }

        echo json_encode($array);
    }
    /**afficher la liste de tous les règlement client d'une entreprise debut */


    /**afficher le formulaire de facturation de facturation debut */
    public function form_facturation_proformat(){
        $this->logged_in();

        /**informations utile */
        $matricule = session('users')['matricule'];

        /*$this->form_validation->set_rules('modepayement', 'choisi le mode de payement', 'required|regex_match[/^[a-z\ ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );*/

        /*$this->form_validation->set_rules('accordpayement', 'choisi l\'accord de payement', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.'
            )
        );*/

        $this->form_validation->set_rules('tva', 'choisi la tva', 'regex_match[/^[a-z\ ]+$/]',array(
            //'required' => '%s.',  /*required|*/
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('ir', 'choisi la ir', 'regex_match[/^[a-z\ ]+$/]',array(
            //'required' => '%s.',  /*required|*/
            'regex_match' => 'caractère non pris en compte'
            )
        );
        
        $this->form_validation->set_rules('tdocument', 'choisi l\'article', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('depot', 'choisi le depot', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('client', 'choisi le client', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('delais', 'saisi le delais', 'required|regex_match[/^[0-9]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('commercial', 'choisi le commercial ou vendeur', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('document', 'choisi le document', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('article', 'choisi l\'article', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );
        

        if($this->form_validation->run()){

            /**assurons nous que pour le depot choisi, l'article soit au préalable en stock */
            $tva = trim($this->input->post('tva'));
            $ir = trim($this->input->post('ir'));
            $code_type_document = trim($this->input->post('tdocument'));
            $depot = trim($this->input->post('depot'));
            $code_client = trim($this->input->post('client'));
            $delais = trim($this->input->post('delais'));
            $code_commercial = trim($this->input->post('commercial'));
            $code_document = trim($this->input->post('document'));
            $code_art = trim($this->input->post('article'));
           
            $matricule_en = session('users')['matricule'];

            /**verifier de quel type de document il s'agit*/
			$type_document = $this->type_document($code_type_document, $matricule);
            if(!empty($type_document)){
                if($type_document == 'FP'){
                    $art_stock_depot = $this->stock->get_art_stock($code_art,$depot);
                    if(!empty($art_stock_depot)){

                        $infos = array(
                            'tva' => $tva,
                            'ir' => $ir,
                            'tdoc' => $code_type_document,
                            'depot' => $depot, 
                            'client' => $code_client,
                            'delais' => $delais,
                            'commercial' => $code_commercial,
                            'document' => $code_document,
                            'article' => $code_art
                        );

                        $prixht = 1;
                        $qte = 1;
                        $taxe = $this->taxe($tva,$ir,$matricule_en,$prixht,$qte);

                        $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);

                        $array = array(
                            'form'   => 'okk...',
                            'article' => $art_stock_depot,
                            'autres' => $infos,
                            'art_doc' => $output
                        );
                        
                    }else{
                        $array = array(
                            'success'   => '
                                <script>
                                    swal.fire("OUPS!","cet article n\'est pas en stock pour le depot choisi, procedez avant à son entré en stock","error")
                                </script>
                            '
                        );
                    }
                }else{
                    $array = array(
                        'success'   => '
                            <script>
                                swal.fire("OUPS!","ce type de document n\'est pas utile ici... utilise le menu de gauche","error")
                            </script>
                        '
                    );  
                }
            }else{
                $array = array(
                    'success'   => '
                        <script>
                            swal.fire("OUPS!","le système ne trouve pas ce type de document","error")
                        </script>
                    '
                );  
            }
                
            

        }else{
            $array = array(
                'error'   => true,
                //'modepayement_error' => form_error('modepayement'),
                //'accordpayement_error' => form_error('accordpayement'),
                'tva_error' => form_error('tva'),
                'ir_error' => form_error('ir'),
                'depot_error' => form_error('depot'),
                'client_error' => form_error('client'),
                'delais_error' => form_error('delais'),
                'commercial_error' => form_error('commercial'),
                'document_error' => form_error('document'),
                'article_error' => form_error('article'),
                'tdocument_error' => form_error('tdocument')
            );
        }

        echo json_encode($array);
    }
    /**afficher le formulaire de facturation de facturation fin */

    /**operation sur la création d'une facture reglement client debut */
    public function operationfacturepf(){
        $this->logged_in();

        /**message d'erreur (connexion a la base des données perdu) debut*/
            $message_db = 	array(
                'success'   => '
                    <script>
                        swal.fire("OUPS!","connexion à la base des données perdu","info")
                    </script>
                '
            );
        /**message d'erreur (connexion a la base des données perdu) fin*/

        $this->form_validation->set_rules('quantes', 'quantité', 'required|regex_match[/^[0-9]+$/]',array(
			'required' => 'entre la %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));
		
		$this->form_validation->set_rules('prix_h', 'prix ht', 'required|is_natural',array(
			'required' => 'le %s ne doit pas être vide',
			'is_natural' => 'caractère(s) non autorisé(s)'
		));

        if($this->form_validation->run()){

            $output = "";
			$tva = trim($this->input->post('tva_doc'));
            $ir = trim($this->input->post('ir_doc'));
            $code_type_document = trim($this->input->post('t_doc'));
            $code_depot = trim($this->input->post('dep'));
            $code_client = trim($this->input->post('cli'));
            $delais = trim($this->input->post('delai'));
            $code_vendeur = trim($this->input->post('vendeur'));
            $code_document = trim($this->input->post('doc_m'));
			$code_article = trim($this->input->post('art'));
            $qte = trim($this->input->post('quantes'));
            $description_art = trim($this->input->post('description_art'));
            
            $prixh = trim($this->input->post('prix_h'));
            
			$matricule_en = session('users')['matricule'];
			$matricule_emp = session('users')['matricule_emp'];
            $agence = session('users')['matricule_ag'];
            
            /**effectuer l'opération selon selon le type de document et le code de l'entreprise*/
			if(!empty($code_type_document) && !empty($matricule_en)){
                /**verifier de quel type de document il s'agit*/
                $type_document = $this->type_document($code_type_document, $matricule_en);
                if(!empty($type_document)){
                    if($type_document == 'FP'){
                        $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
                        if(!empty($art_stock_depot)){
                            //if($qte <= $art_stock_depot['quantite']){

                                /**on test pour voir si le document existe déjà ou pas */
                                $verify_document = $this->stock->doc_stock($code_type_document, $code_document, $matricule_en);
                                if(empty($verify_document)){
                                    /***************************************** LE DOCUMENT N'EXISTE PAS DEBUT *************************************** */
                                   
                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                    $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);
                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                    
                                    /**on creer le document */
                                    $input_doc = array(
                                        'code_document'=>$code_document,
                                        'nom_document'=> 'FP-DOCUMENT-TFP-'.code(10).'-'.date('d-m-Y'),
                                        'date_creation_doc'=>dates(),
                                        'code_type_doc'=> $code_type_document,
                                        'depot_doc'=> $code_depot,
                                        'code_client_document'=> $code_client,
                                        'cloturer'=>0,
                                        'code_agence_doc' => $agence,
                                        'delais_reg_doc'=> $delais,
                                        'pt_ht_document'=> ($prixh * $qte),
                                        'pt_ttc_document'=> $taxe['prixttc'],
                                        'pt_net_document'=> $taxe['prixnet'],
                                        'code_employe'=> $matricule_emp,
                                        'code_entreprie'=> $matricule_en,
                                    );
                                    $new_document = $this->stock->new_document($input_doc);
                                    if($new_document){
                                        /**on enrégistre l'historique des articles de ce document dans la table article_document*/
                                        $input_art_doc = array(
                                            'code_article'=> $code_article,
                                            'code_document'=> $code_document,
                                            'quantite'=> $qte,
                                            'pu_HT'=> $prixh,
                                            'pt_HT'=> ($prixh * $qte),
                                            'description_art' => $description_art,
                                            'code_emp_art_doc'=> $matricule_emp,
                                            'code_en_art_doc'=> $matricule_en, 
                                            'date_creer_art_doc'=> dates()
                                        );
                                        $save_art_from_doc = $this->stock->article_document($input_art_doc);
                                        if($save_art_from_doc){
                                            
                                            /**liste des articles du document */
                                            $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                            $array = array(
                                                'success' => 'article ajouter au panier',
                                                'art_doc' => $output
                                            );

                                        }else{
                                            $array = $message_db;
                                        }
                                    }else{
                                        $array = $message_db;
                                    }

                                    /***************************************** LE DOCUMENT N'EXISTE PAS FIN *************************************** */
                                }else{
                                    /***************************************** LE DOCUMENT EXISTE DEBUT *************************************** */

                                    /**on remet ceci ici pour s'assurer de la qualité de l'information */
                                    $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);

                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                    $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);
                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/

                                    /**pour le document encour, on verifi s'il contient l'article encour */
                                    $verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
                                    if(empty($verify_art_document)){
                                        /*******************************l'article n'existe pas dans le document debut *******************/
                                        
                                        /**on selectionne le document en question  */
                                        $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                        if(!empty($document)){   
                                            $input_doc = array(
                                                'date_modifier_doc'=>dates(),
                                                'pt_ht_document'=> ($document['pt_ht_document'] + ($prixh * $qte)),
                                                'pt_ttc_document'=> ($document['pt_ttc_document'] + $taxe['prixttc']),
                                                'pt_net_document'=> ($document['pt_net_document'] + $taxe['prixnet']),
                                                'code_employe'=> $matricule_emp,
                                                'code_entreprie'=> $matricule_en,
                                            );
    
                                            $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                            if($update_doc){

                                                 /**on enrégistre l'historique des articles de ce document dans la table article_document*/
                                                $input_art_doc = array(
                                                    'code_article'=> $code_article,
                                                    'code_document'=> $code_document,
                                                    'quantite'=> $qte,
                                                    'pu_HT'=> $prixh,
                                                    'pt_HT'=> ($prixh * $qte),
                                                    'description_art' => $description_art,
                                                    'code_emp_art_doc'=> $matricule_emp,
                                                    'code_en_art_doc'=> $matricule_en, 
                                                    'date_creer_art_doc'=> dates()
                                                );
                                                $save_art_from_doc = $this->stock->article_document($input_art_doc);
                                                if($save_art_from_doc){
                                                    /**liste des articles du document */
                                                    $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                    $array = array(
                                                        'success' => 'article ajouter au panier',
                                                        'art_doc' => $output
                                                    );
                                                }else{
                                                    $array = $message_db;
                                                }

                                            }else{
                                                $array = $message_db;
                                            }

                                        }else{
                                            $array = $message_db;
                                        }
                                        /*******************************l'article n'existe pas dans le document fin *******************/
                                    }else{
                                        /*******************************l'article existe  dans le document debut *******************/
                                        
                                        /**on remet ceci ici pour s'assurer de la qualité de l'information */
                                        $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);

                                        /**on renvoit la quantité prélévé précedement en stock pour le depot choisi*
                                        $qte_initial = ($verify_art_document['quantite'] + $art_stock_depot['quantite']);
                                        $input_stock_val = array(
                                            'code_employe'=> $matricule_emp,
                                            'code_depot'=> $code_depot,
                                            'code_entreprise'=> $matricule_en,
                                            'quantite'=>  $qte_initial,
                                            'date_modifier_stock'=> dates()
                                        );*/
                                        
                                       /*$update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);
										if($update_art_st){*/

                                            
                                            /**on selectionne le document en question */
                                            $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                            if(!empty($document)){
                                                
                                                /**on modifie le document
                                                 * 1: on enleve le prix total ht de l'article sur le prix total ht du document
                                                 * 2: on enleve le prix total ttc de l'article sur le prix total ttc du document
                                                */

                                                /** selectionne le pourcentage de la tva dans la table taxe*/
                                                $pourcentagetva = $this->commerce->specifictaxe($tva,$matricule_en);
                                                $action1 = (!empty($pourcentagetva)) ? $pourcentagetva['pourcentage'] : 0;

                                                //** selectionne le pourcentage de la ir dans la table taxe*
                                                $pourcentageir = $this->commerce->specifictaxe($ir,$matricule_en);
                                                $action2 = (!empty($pourcentageir)) ? $pourcentageir['pourcentage'] : 0;

                                                /**taxes et prix ttc de l'article debut */
                                                $tva_art = (($action1 * $verify_art_document['pt_HT'])/100);
                                                $ir_art = (($action2 * $verify_art_document['pt_HT'])/100);
                                                $prix_ttc_art =  ($verify_art_document['pt_HT'] + $tva_art);
                                                $prix_net_art =  ($verify_art_document['pt_HT'] + $ir_art);
                                                /**taxes et prix ttc de l'article fint */

                                                $input_doc = array(
                                                    'date_modifier_doc'=>dates(),
                                                    'pt_ht_document'=> ($document['pt_ht_document'] - $verify_art_document['pt_HT']),
                                                    'pt_ttc_document'=> ($document['pt_ttc_document'] - $prix_ttc_art),
                                                    'pt_net_document'=> ($document['pt_net_document'] - $prix_net_art),
                                                    'code_employe'=> $matricule_emp,
                                                    'code_entreprie'=> $matricule_en,
                                                );
                                                
                                                $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                if($update_doc){

                                                    /**on remet ici pour rafraichir les informations */
                                                    $document = $this->commerce->specificdocument($code_document,$matricule_en);

                                                    /**on remet ici pour rafraichir les informations */
                                                    $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);

                                                    /**une fois que tout est en ordre, on met ajour le document avec les nouvelles valeurs */
                                                    $input_doc = array(
                                                        'date_modifier_doc'=>dates(),
                                                        'pt_ht_document'=> ($document['pt_ht_document'] + ($prixh * $qte)),
                                                        'pt_ttc_document'=> ($document['pt_ttc_document'] + $taxe['prixttc']),
                                                        'pt_net_document'=> ($document['pt_net_document'] + $taxe['prixnet']),
                                                        'code_employe'=> $matricule_emp,
                                                        'code_entreprie'=> $matricule_en,
                                                    );
            
                                                    $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                    if($update_doc){

                                                        /**une fois le document mis a jour, on modifi l'article dans le document en question */
                                                        /**on modifie l'article encour dans le document encour (table article_document)*/
                                                        
                                                        $input_art_doc = array(
                                                            'code_document'=> $code_document,
                                                            'quantite'=> $qte,
                                                            'pu_HT'=> $prixh,
                                                            'pt_HT'=> ($prixh * $qte),
                                                            'description_art' => $description_art,
                                                            'code_emp_art_doc'=> $matricule_emp,
                                                            'code_en_art_doc'=> $matricule_en, 
                                                            'date_creer_art_doc'=> dates()
                                                        );
                                                        $update_art_document = $this->stock->update_article_document($code_document,$code_article,$input_art_doc);
                                                        if($update_art_document){
                                                            /**liste des articles du document */
                                                            $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                            $array = array(
                                                                'success' => 'article ajouter au panier',
                                                                'art_doc' => $output
                                                            );
                                                        }else{
                                                            $array = $message_db;
                                                        }
                                                    }else{
                                                        $array = $message_db;
                                                    }
                                                }else{
                                                    $array = $message_db;
                                                }
                                            }else{
                                                $array = $message_db;
                                            }
                                        /*}else{
                                            $array = $message_db;
                                        }*/
                                        /*******************************l'article existe  dans le document fin *******************/
                                    }
                                        
                                    

                                    /***************************************** LE DOCUMENT EXISTE FIN *************************************** */
                                }
                                
                            /*}else{
                                $array = array(
                                    'success'   => '
                                        <script>
                                            swal.fire("OUPS!","quantité en stock insuffisante pour ce depot","error")
                                        </script>
                                    '
                                );
                            }*/
                            
                        }else{
                            $array = array(
                                'success'   => '
                                    <script>
                                        swal.fire("OUPS!","cet article n\'est pas en stock pour le depot choisi, procedez avant à son entré en stock","error")
                                    </script>
                                '
                            );
                        }
                    }else{
                        $array = array(
                            'success'   => '
                                <script>
                                    swal.fire("OUPS!","ce type de document n\'est pas utile ici... utilise le menu de gauche","error")
                                </script>
                            '
                        );  
                    }
                }else{
                    $array = array(
                        'success'   => '
                            <script>
                                swal.fire("OUPS!","le système ne trouve pas ce type de document","error")
                            </script>
                        '
                    );  
                }
            }

        }else{
			$array = array(
				'error'   => true,
				'quantes_error' => form_error('quantes'),
				'prix_h_error' => form_error('prix_h')
			);
		}

		echo json_encode($array);
    }
    /**operation sur la création d'une facture reglement client fin */



    /**************************************gestion des proformats fin***************************** */

    /******************************* gestion des bon de livraison debut *********************** */

    /**affiche la page de bon de livraison */
    public function bon_livraison(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');
		
		/**informations utile */
		$matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];
       
            
                /** selectionne les depots en fonctions de l'entreprise et ou de l'agence*/
                $data['depots'] = $this->commerce->getalldepot($matricule,$agence);

                /**affiche la liste des clients d'une entreprise */
                $data['clients'] = $this->commerce->all_client($matricule);

                /**affiche les vendeur: c'est la personne connecté au momement de la vente */
                $data['vendeur'][] = array(
                    'matricule' => session('users')['matricule_emp'],
                    'nom' => session('users')['nom_emp']
                );


            /**affiche la liste des caisse en fonction de l'entreprise et ou de l'agence */
            $data['caisses'] = $this->commerce->allcaisseagence($matricule,$agence);
                
                
                
            /**afficher le code et le nom d'un document générer*/
            $data['code_doc'] = array(
                'BL-DOC-'.code(10) => 'BL-DOCUMENT-TBL-'.code(10).'-'.date('d-m-Y'),
            );



            /**liste des articles de l'entreprise*/
            $data['articles'] = $this->commerce->all_article_entreprise($matricule);

                /**liste des taxe */
                $data['taxes'] = $this->commerce->all_taxe_entreprise($matricule);

            /**affiche la liste des type de documents pour une entreprise donné */
            $data['docs'] = $this->commerce->type_doc($matricule);

            /*afficher les proformat déjà creer pour une potentiel modification futur*/
            $abrev = 'BL';
            $output ="";
            $docus = $this->commerce->docu_cli_en_ag($matricule,$agence,$abrev);
            if(!empty($docus)){
                $output = $docus; 
            }
            $data['get_docs'] = $output;
                
            $this->load->view('commerce/bonlivraison',$data);


        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }

    /**afficher la liste de tous les règlement client d'une entreprise debut */
    public function all_bordereaul(){
        $this->logged_in();
        $this->logged_in();

        $matricule_en = session('users')['matricule'];
        $agencert = session('users')['matricule_ag'];
        $abrev = "BL";
        $output = "";
        $recherche = trim($this->input->post('rechercher'));
        if(!empty($recherche)){
            /**on cherche une facture en fonction du client ou du code du client du type de document, de l'entreprise et ou de l'agence*/
            $docrtvente = $this->commerce->documentrtventerecherche($abrev,$matricule_en,$agencert,$recherche); 
            if(!empty($docrtvente)){
                foreach ($docrtvente as $key => $value) {
                    $output .='<div class="row"> <div class="col-md-11"><button type="button" id="'.$value['code_document'].'" class="list-group-item list-group-item-action art_bl"><b>Code:</b> '.$value['code_document'].' | <b>Nom:</b> '.$value['nom_document'].' | <b>Date:</b> '.dateformat($value['date_creation_doc']).' | <b class="badge badge-primary">Caisse: '.$value['libelle_caisse'].'</b> <b class="badge badge-success">Client: '.$value['nom_cli'].'</b> | <b class="badge badge-warning">'.$value['nom_ag'].'</b> | </button></div><div class="col-md-1"><button class="btn btn-icon btn-circle btn-label-linkedin editdocbl" id="'.$value['code_document'].'"><i class="fa fa-edit"></i></button></div></div>';  
                }
            }else{
                $output .='<b class="text-danger">aucune facture ne correspond à ce client</b>';
            }
            $array = array(
                'infos' => $output,
            );
        }else{
            /**
             * 1: si l'agence est connecté, on cherche pour une agence donné
             * 2: si non on cherche pour une entreprise
             *
            /**on compte le nombre de reglément ticket en fonction de l'entreprise et ou de l'agence */
            $nbrdocrt = $this->commerce->countdocrtagence($matricule_en,$agencert,$abrev);
            
            /** PAGINATION DEBUT */
            $config = array();
            $config["base_url"] = "#";
            $config["total_rows"] = $nbrdocrt;
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
            $config["cur_tag_open"] = "<li class='page-item active'><a href='#'><span class='page-link'><span class='sr-only'>(current)</span>";
            $config["cur_tag_close"] = "</span></a></li>";
            $config["num_tag_open"] = '<li class="page-item page-link">';
            $config["num_tag_close"] = "</li>";
            $config["num_links"] = 1;
            $this->pagination->initialize($config);
            
            $page = (($this->uri->segment(2) - 1) * $config["per_page"]);
            /** PAGINATION FIN */
            
            /**on selectionne les documents en fonction du type de document, de l'entreprise et ou de l'agence
             * au sorti on aura tous les informations sur le documents le client et la caisse
            */
            $docrtvente = $this->commerce->documentrtvente($abrev,$matricule_en,$agencert,$config["per_page"],$page); 
            if(!empty($docrtvente)){
                foreach ($docrtvente as $key => $value) {
                    $output .='<div class="row"> <div class="col-md-11"><button type="button" id="'.$value['code_document'].'" class="list-group-item list-group-item-action art_bl"><b>Code:</b> '.$value['code_document'].' | <b>Nom:</b> '.$value['nom_document'].' | <b>Date:</b> '.dateformat($value['date_creation_doc']).' | <b class="badge badge-primary">Caisse: '.$value['libelle_caisse'].'</b> <b class="badge badge-success">Client: '.$value['nom_cli'].'</b> | <b class="badge badge-warning">'.$value['nom_ag'].'</b> </button></div><div class="col-md-1"><button class="btn btn-icon btn-circle btn-label-linkedin editdocbl" id="'.$value['code_document'].'"><i class="fa fa-edit"></i></button></div></div>';  
                }
            }else{
                $output .='<b class="text-danger">aucune facture enrégistrer pour le moment</b>';
            }

            $array = array(
                'infos' => $output,
                'pagination_link' => $this->pagination->create_links()
            );
        }
        echo json_encode($array);
    }
    /**afficher la liste de tous les règlement client d'une entreprise debut */

    /**afficher le formulaire de facturation de facturation debut */
    public function form_bordereau_livraison(){
        $this->logged_in();

        /**informations utile */
        $matricule = session('users')['matricule'];

        /*$this->form_validation->set_rules('modepayement', 'choisi le mode de payement', 'required|regex_match[/^[a-z\ ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );*/

        /*$this->form_validation->set_rules('accordpayement', 'choisi l\'accord de payement', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.'
            )
        );*/

        $this->form_validation->set_rules('tva', 'choisi la tva', 'regex_match[/^[a-z\ ]+$/]',array(
            //'required' => '%s.',  /*required|*/
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('ir', 'choisi la ir', 'regex_match[/^[a-z\ ]+$/]',array(
            //'required' => '%s.',  /*required|*/
            'regex_match' => 'caractère non pris en compte'
            )
        );
        
        $this->form_validation->set_rules('tdocument', 'choisi l\'article', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('depot', 'choisi le depot', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('client', 'choisi le client', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('delais', 'saisi le delais', 'required|regex_match[/^[0-9]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('commercial', 'choisi le commercial ou vendeur', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('document', 'choisi le document', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('article', 'choisi l\'article', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );
        

        if($this->form_validation->run()){

            /**assurons nous que pour le depot choisi, l'article soit au préalable en stock */
            $tva = trim($this->input->post('tva'));
            $ir = trim($this->input->post('ir'));
            $code_type_document = trim($this->input->post('tdocument'));
            $depot = trim($this->input->post('depot'));
            $code_client = trim($this->input->post('client'));
            $delais = trim($this->input->post('delais'));
            $code_commercial = trim($this->input->post('commercial'));
            $code_document = trim($this->input->post('document'));
            $code_art = trim($this->input->post('article'));
           
            $matricule_en = session('users')['matricule'];

            /**verifier de quel type de document il s'agit*/
			$type_document = $this->type_document($code_type_document, $matricule);
            if(!empty($type_document)){
                if($type_document == 'BL'){
                    $art_stock_depot = $this->stock->get_art_stock($code_art,$depot);
                    if(!empty($art_stock_depot)){

                        $infos = array(
                            'tva' => $tva,
                            'ir' => $ir,
                            'tdoc' => $code_type_document,
                            'depot' => $depot, 
                            'client' => $code_client,
                            'delais' => $delais,
                            'commercial' => $code_commercial,
                            'document' => $code_document,
                            'article' => $code_art
                        );

                        $prixht = 1;
                        $qte = 1;
                        $taxe = $this->taxe($tva,$ir,$matricule_en,$prixht,$qte);

                        $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);

                        $array = array(
                            'form'   => 'okk...',
                            'article' => $art_stock_depot,
                            'autres' => $infos,
                            'art_doc' => $output
                        );
                        
                    }else{
                        $array = array(
                            'success'   => '
                                <script>
                                    swal.fire("OUPS!","cet article n\'est pas en stock pour le depot choisi, procedez avant à son entré en stock","error")
                                </script>
                            '
                        );
                    }
                }else{
                    $array = array(
                        'success'   => '
                            <script>
                                swal.fire("OUPS!","ce type de document n\'est pas utile ici... utilise le menu de gauche","error")
                            </script>
                        '
                    );  
                }
            }else{
                $array = array(
                    'success'   => '
                        <script>
                            swal.fire("OUPS!","le système ne trouve pas ce type de document","error")
                        </script>
                    '
                );  
            }

        }else{
            $array = array(
                'error'   => true,
                //'modepayement_error' => form_error('modepayement'),
                //'accordpayement_error' => form_error('accordpayement'),
                'tva_error' => form_error('tva'),
                'ir_error' => form_error('ir'),
                'depot_error' => form_error('depot'),
                'client_error' => form_error('client'),
                'delais_error' => form_error('delais'),
                'commercial_error' => form_error('commercial'),
                'document_error' => form_error('document'),
                'article_error' => form_error('article'),
                'tdocument_error' => form_error('tdocument')
            );
        }

        echo json_encode($array);
    }
    /**afficher le formulaire de facturation de facturation fin */

    /**operation sur la création d'une facture reglement client debut */
    public function operation_bordereau_livraison(){
        $this->logged_in();

        /**message d'erreur (connexion a la base des données perdu) debut*/
            $message_db = 	array(
                'success'   => '
                    <script>
                        swal.fire("OUPS!","connexion à la base des données perdu","info")
                    </script>
                '
            );
        /**message d'erreur (connexion a la base des données perdu) fin*/

        $this->form_validation->set_rules('quantes', 'quantité', 'required|regex_match[/^[0-9]+$/]',array(
			'required' => 'entre la %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));
		$this->form_validation->set_rules('prix_h', 'prix ht', 'required|is_natural',array(
			'required' => 'le %s ne doit pas être vide',
			'is_natural' => 'caractère(s) non autorisé(s)'
		));

        if($this->form_validation->run()){

            $output = "";
            
			$tva = trim($this->input->post('tva_doc'));
            $ir = trim($this->input->post('ir_doc'));
            $code_type_document = trim($this->input->post('t_doc'));
            $code_depot = trim($this->input->post('dep'));
            $code_client = trim($this->input->post('cli'));
            $delais = trim($this->input->post('delai'));
            $code_vendeur = trim($this->input->post('vendeur'));
            $code_document = trim($this->input->post('doc_m'));
			$code_article = trim($this->input->post('art'));
            $qte = trim($this->input->post('quantes'));
            
            //$description_art = trim($this->input->post('description_art'));
            $prixh = trim($this->input->post('prix_h'));

			$matricule_en = session('users')['matricule'];
			$matricule_emp = session('users')['matricule_emp'];
			$agence = session('users')['matricule_ag'];
            
            /**effectuer l'opération selon selon le type de document et le code de l'entreprise*/
			if(!empty($code_type_document) && !empty($matricule_en)){
                /**verifier de quel type de document il s'agit*/
                $type_document = $this->type_document($code_type_document, $matricule_en);
                if(!empty($type_document)){
                    if($type_document == 'BL'){
                        
                        $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
                        if(!empty($art_stock_depot)){
                            if($qte <= $art_stock_depot['quantite']){

                                /**on test pour voir si le document existe déjà ou pas */
                                $verify_document = $this->stock->doc_stock($code_type_document, $code_document, $matricule_en);
                                if(empty($verify_document)){
                                    /***************************************** LE DOCUMENT N'EXISTE PAS DEBUT *************************************** */
                                   
                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                    $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);
                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                    
                                    /**on creer le document */
                                    $input_doc = array(
                                        'code_document'=>$code_document,
                                        'nom_document'=> 'BL-DOCUMENT-TBL-'.code(10).'-'.date('d-m-Y'),
                                        'date_creation_doc'=>dates(),
                                        'code_type_doc'=> $code_type_document,
                                        'depot_doc'=> $code_depot,
                                        'code_client_document'=> $code_client,
                                        'code_agence_doc' => $agence,
                                        'delais_reg_doc'=> $delais,
                                        'cloturer'=>0,
                                        'pt_ht_document'=> ($prixh * $qte),
                                        'pt_ttc_document'=> $taxe['prixttc'],
                                        'pt_net_document'=> $taxe['prixnet'],
                                        'code_employe'=> $matricule_emp,
                                        'code_entreprie'=> $matricule_en,
                                    );
                                    $new_document = $this->stock->new_document($input_doc);
                                    if($new_document){
                                            
                                        /**on enrégistre l'historique des articles de ce document dans la table article_document*/
                                        $input_art_doc = array(
                                            'code_article'=> $code_article,
                                            'code_document'=> $code_document,
                                            'quantite'=> $qte,
                                            'pu_HT'=> $prixh,
                                            'pt_HT'=> ($prixh * $qte),
                                            //'description_art' => $description_art,
                                            'code_emp_art_doc'=> $matricule_emp,
                                            'code_en_art_doc'=> $matricule_en, 
                                            'date_creer_art_doc'=> dates()
                                        );
                                        $save_art_from_doc = $this->stock->article_document($input_art_doc);
                                        if($save_art_from_doc){
                                            
                                            /**liste des articles du document */
                                            $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                            $array = array(
                                                'success' => 'article ajouter au panier',
                                                'art_doc' => $output
                                            );

                                        }else{
                                            $array = $message_db;
                                        }
                                          
                                    }else{
                                        $array = $message_db;
                                    }

                                    /***************************************** LE DOCUMENT N'EXISTE PAS FIN *************************************** */
                                }else{
                                    /***************************************** LE DOCUMENT EXISTE DEBUT *************************************** */

                                    /**on remet ceci ici pour s'assurer de la qualité de l'information */
                                    $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);

                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/
                                    $taxe = $this->taxe($tva,$ir,$matricule_en,$prixh,$qte);
                                    /**calcul du prixtotatttc en fonction de la tva et de la ir debut*/

                                    /**pour le document encour, on verifi s'il contient l'article encour */
                                    $verify_art_document = $this->stock->get_art_doc($code_article, $code_document, $matricule_en);
                                    if(empty($verify_art_document)){
                                        /*******************************l'article n'existe pas dans le document debut *******************/
                                        
                                        /**on selectionne le document en question  */
                                        $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                        if(!empty($document)){   
                                            $input_doc = array(
                                                'date_modifier_doc'=>dates(),
                                                'pt_ht_document'=> ($document['pt_ht_document'] + ($prixh * $qte)),
                                                'pt_ttc_document'=> ($document['pt_ttc_document'] + $taxe['prixttc']),
                                                'pt_net_document'=> ($document['pt_net_document'] + $taxe['prixnet']),
                                                'code_employe'=> $matricule_emp,
                                                'code_entreprie'=> $matricule_en,
                                            );
    
                                            $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                            if($update_doc){

                                                 /**on enrégistre l'historique des articles de ce document dans la table article_document*/
                                                $input_art_doc = array(
                                                    'code_article'=> $code_article,
                                                    'code_document'=> $code_document,
                                                    'quantite'=> $qte,
                                                    'pu_HT'=> $prixh,
                                                    'pt_HT'=> ($prixh * $qte),
                                                    //'description_art' => $description_art,
                                                    'code_emp_art_doc'=> $matricule_emp,
                                                    'code_en_art_doc'=> $matricule_en, 
                                                    'date_creer_art_doc'=> dates()
                                                );
                                                $save_art_from_doc = $this->stock->article_document($input_art_doc);
                                                if($save_art_from_doc){
                                                    /**liste des articles du document */
                                                    $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                    $array = array(
                                                        'success' => 'article ajouter au panier',
                                                        'art_doc' => $output
                                                    );
                                                }else{
                                                    $array = $message_db;
                                                }
                                            }else{
                                                $array = $message_db;
                                            }

                                        }else{
                                            $array = $message_db;
                                        }
                                        /*******************************l'article n'existe pas dans le document fin *******************/
                                    }else{
                                        /*******************************l'article existe  dans le document debut *******************/
                                        
                                        /**on remet ceci ici pour s'assurer de la qualité de l'information */
                                        $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);

                                        /**on renvoit la quantité prélévé précedement en stock pour le depot choisi
                                        $qte_initial = ($verify_art_document['quantite'] + $art_stock_depot['quantite']);
                                        $input_stock_val = array(
                                            'code_employe'=> $matricule_emp,
                                            'code_depot'=> $code_depot,
                                            'code_entreprise'=> $matricule_en,
                                            'quantite'=>  $qte_initial,
                                            'date_modifier_stock'=> dates()
                                        );
                                        
                                       $update_art_st = $this->stock->update_stock2($code_article, $input_stock_val,$code_depot);*/
										//if($update_art_st){
                                            
                                            /**on selectionne le document en question */
                                            $document = $this->commerce->specificdocument($code_document,$matricule_en);
                                            if(!empty($document)){
                                                
                                                /**on modifie le document
                                                 * 1: on enleve le prix total ht de l'article sur le prix total ht du document
                                                 * 2: on enleve le prix total ttc de l'article sur le prix total ttc du document
                                                */

                                                /** selectionne le pourcentage de la tva dans la table taxe*/
                                                $pourcentagetva = $this->commerce->specifictaxe($tva,$matricule_en);
                                                $action1 = (!empty($pourcentagetva)) ? $pourcentagetva['pourcentage'] : 0;

                                                //** selectionne le pourcentage de la ir dans la table taxe*
                                                $pourcentageir = $this->commerce->specifictaxe($ir,$matricule_en);
                                                $action2 = (!empty($pourcentageir)) ? $pourcentageir['pourcentage'] : 0;

                                                /**taxes et prix ttc de l'article debut */
                                                $tva_art = (($action1 * $verify_art_document['pt_HT'])/100);
                                                $ir_art = (($action2 * $verify_art_document['pt_HT'])/100);
                                                $prix_ttc_art =  ($verify_art_document['pt_HT'] + $tva_art);
                                                $prix_net_art =  ($verify_art_document['pt_HT'] + $ir_art);
                                                /**taxes et prix ttc de l'article fint */

                                                $input_doc = array(
                                                    'date_modifier_doc'=>dates(),
                                                    'pt_ht_document'=> ($document['pt_ht_document'] - $verify_art_document['pt_HT']),
                                                    'pt_ttc_document'=> ($document['pt_ttc_document'] - $prix_ttc_art),
                                                    'pt_net_document'=> ($document['pt_net_document'] - $prix_net_art),
                                                    'code_employe'=> $matricule_emp,
                                                    'code_entreprie'=> $matricule_en,
                                                );
                                                
                                                $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                if($update_doc){

                                                    /**on remet ici pour rafraichir les informations */
                                                    $document = $this->commerce->specificdocument($code_document,$matricule_en);

                                                    /**on remet ici pour rafraichir les informations */
                                                    $art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);

                                                    /**une fois que tout est en ordre, on met ajour le document avec les nouvelles valeurs */
                                                    $input_doc = array(
                                                        'date_modifier_doc'=>dates(),
                                                        'pt_ht_document'=> ($document['pt_ht_document'] + ($prixh * $qte)),
                                                        'pt_ttc_document'=> ($document['pt_ttc_document'] + $taxe['prixttc']),
                                                        'pt_net_document'=> ($document['pt_net_document'] + $taxe['prixnet']),
                                                        'code_employe'=> $matricule_emp,
                                                        'code_entreprie'=> $matricule_en,
                                                    );
            
                                                    $update_doc = $this->commerce->updatedocument($document['code_document'],$document['code_entreprie'],$input_doc);
                                                    if($update_doc){

                                                        /**une fois le document mis a jour, on modifi l'article dans le document en question */
                                                        /**on modifie l'article encour dans le document encour (table article_document)*/
                                                        
                                                        $input_art_doc = array(
                                                            'code_document'=> $code_document,
                                                            'quantite'=> $qte,
                                                            'pu_HT'=> $prixh,
                                                            'pt_HT'=> ($prixh * $qte),
                                                            //'description_art' => $description_art,
                                                            'code_emp_art_doc'=> $matricule_emp,
                                                            'code_en_art_doc'=> $matricule_en, 
                                                            'date_creer_art_doc'=> dates()
                                                        );
                                                        $update_art_document = $this->stock->update_article_document($code_document,$code_article,$input_art_doc);
                                                        if($update_art_document){
                                                            /**liste des articles du document */
                                                            $output = $this->art_doc($code_document,$matricule_en,$taxe['percenttva'],$taxe['percentir']);
                                                            $array = array(
                                                                'success' => 'article ajouter au panier',
                                                                'art_doc' => $output
                                                            );
                                                        }else{
                                                            $array = $message_db;
                                                        }
                                                    }else{
                                                        $array = $message_db;
                                                    }
                                                }else{
                                                    $array = $message_db;
                                                }
                                            }else{
                                                $array = $message_db;
                                            }
                                        /*}else{
                                            $array = $message_db;
                                        }*/
                                        /*******************************l'article existe  dans le document fin *******************/
                                    }
                                    /***************************************** LE DOCUMENT EXISTE FIN *************************************** */
                                }
                            }else{
                                $array = array(
                                    'success'   => '
                                        <script>
                                            swal.fire("OUPS!","quantité en stock insuffisante pour ce depot","error")
                                        </script>
                                    '
                                );
                            }
                        }else{
                            $array = array(
                                'success'   => '
                                    <script>
                                        swal.fire("OUPS!","cet article n\'est pas en stock pour le depot choisi, procedez avant à son entré en stock","error")
                                    </script>
                                '
                            );
                        }
                    }else{
                        $array = array(
                            'success'   => '
                                <script>
                                    swal.fire("OUPS!","ce type de document n\'est pas utile ici... utilise le menu de gauche","error")
                                </script>
                            '
                        );  
                    }
                }else{
                    $array = array(
                        'success'   => '
                            <script>
                                swal.fire("OUPS!","le système ne trouve pas ce type de document","error")
                            </script>
                        '
                    );  
                }
            }

        }else{
			$array = array(
				'error'   => true,
				'quantes_error' => form_error('quantes'),
				'prix_h_error' => form_error('prix_h')
			);
		}

		echo json_encode($array);
    }
    /**operation sur la création d'une facture reglement client fin */


    /******************************* gestion des bon de livraison fin *********************** */

    /************************************gestion des bon de retour debut ******************** */

    /**affiche la page des bons de retour */
    public function bon_retour(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

        /**informations utile */
		$matricule = session('users')['matricule'];
        $matricule_emp = session('users')['matricule_emp'];
        $agence = session('users')['matricule_ag'];
        
                /**afficher le code et le nom d'un document générer*/
                $data['code_doc'] = array(
                    'BR-DOC-'.code(10) => 'BR-DOCUMENT-TBR-'.code(10).'-'.date('d-m-Y'),
                );

                /**affiche les vendeur: c'est la personne connecté au momement de la vente */
                $data['vendeur'][] = array(
                    'matricule' => session('users')['matricule_emp'],
                    'nom' => session('users')['nom_emp']
                );

                /**affiche la liste des type de documents pour une entreprise donné */
                $data['docs'] = $this->commerce->type_doc($matricule);

                $abrev = array('RT'); //,'RC'
                /**afficher la liste des factures (BR, BL...) */
                $data['facture'] = $this->commerce->documents_i($abrev,$matricule);  
                $this->load->view('commerce/bonretour',$data);


        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }

    /**afficher la liste de tous les bons de retours d'une entreprise debut */
    public function all_bon_retour(){
        $this->logged_in();
        $this->logged_in();

        $matricule_en = session('users')['matricule'];
        $agencert = session('users')['matricule_ag'];
        $abrev = "BR";
        $output = "";
        $recherche = trim($this->input->post('rechercher'));
        if(!empty($recherche)){
            /**on cherche une facture en fonction du client ou du code du client du type de document, de l'entreprise et ou de l'agence*/
            $docrtvente = $this->commerce->documentrtventerecherche($abrev,$matricule_en,$agencert,$recherche); 
            if(!empty($docrtvente)){
                foreach ($docrtvente as $key => $value) {
                    $output .='<div class="row"> <div class="col-md-11"><button type="button" id="'.$value['code_document'].'" class="list-group-item list-group-item-action art_br"><b>Code:</b> '.$value['code_document'].' | <b>Nom:</b> '.$value['nom_document'].' | <b>Date:</b> '.dateformat($value['date_creation_doc']).' | <b class="badge badge-primary">Caisse: '.$value['libelle_caisse'].'</b> <b class="badge badge-success">Client: '.$value['nom_cli'].'</b> | <b class="badge badge-warning">'.$value['nom_ag'].'</b> | </button></div><div class="col-md-1"><button class="btn btn-icon btn-circle btn-label-linkedin editdocbr" id="'.$value['code_document'].'"><i class="fa fa-edit"></i></button></div></div>';  
                }
            }else{
                $output .='<b class="text-danger">aucune facture ne correspond à ce client</b>';
            }
            $array = array(
                'infos' => $output,
            );
        }else{
            /**
             * 1: si l'agence est connecté, on cherche pour une agence donné
             * 2: si non on cherche pour une entreprise
             *
            /**on compte le nombre de reglément ticket en fonction de l'entreprise et ou de l'agence */
            $nbrdocrt = $this->commerce->countdocrtagence($matricule_en,$agencert,$abrev);
            
            /** PAGINATION DEBUT */
            $config = array();
            $config["base_url"] = "#";
            $config["total_rows"] = $nbrdocrt;
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
            $config["cur_tag_open"] = "<li class='page-item active'><a href='#'><span class='page-link'><span class='sr-only'>(current)</span>";
            $config["cur_tag_close"] = "</span></a></li>";
            $config["num_tag_open"] = '<li class="page-item page-link">';
            $config["num_tag_close"] = "</li>";
            $config["num_links"] = 1;
            $this->pagination->initialize($config);
            
            $page = (($this->uri->segment(2) - 1) * $config["per_page"]);
            /** PAGINATION FIN */
            
            /**on selectionne les documents en fonction du type de document, de l'entreprise et ou de l'agence
             * au sorti on aura tous les informations sur le documents le client et la caisse
            */
            $docrtvente = $this->commerce->documentrtvente($abrev,$matricule_en,$agencert,$config["per_page"],$page); 
            if(!empty($docrtvente)){
                foreach ($docrtvente as $key => $value) {
                    $output .='<div class="row"> <div class="col-md-11"><button type="button" id="'.$value['code_document'].'" class="list-group-item list-group-item-action art_br"><b>Code:</b> '.$value['code_document'].' | <b>Nom:</b> '.$value['nom_document'].' | <b>Date:</b> '.dateformat($value['date_creation_doc']).' | <b class="badge badge-primary">Caisse: '.$value['libelle_caisse'].'</b> <b class="badge badge-success">Client: '.$value['nom_cli'].'</b> | <b class="badge badge-warning">'.$value['nom_ag'].'</b> </button></div><div class="col-md-1"><button class="btn btn-icon btn-circle btn-label-linkedin editdocbr" id="'.$value['code_document'].'"><i class="fa fa-edit"></i></button></div></div>';  
                }
            }else{
                $output .='<b class="text-danger">aucune facture enrégistrer pour le moment</b>';
            }

            $array = array(
                'infos' => $output,
                'pagination_link' => $this->pagination->create_links()
            );
        }
        echo json_encode($array);
    }
    /**afficher la liste de tous les bons de retours d'une entreprise fin*/

    /**liste des article d'un bon de retour debut */
    public function all_article_bon_retour(){
        $this->logged_in();
        $code_document = trim($this->input->post('code_rt'));
        $matricule_en = session('users')['matricule'];

        $code_document = trim($this->input->post('code_rt'));
        $matricule_en = session('users')['matricule'];

        $output ="";

         /**j'affiche la liste des article d'un document en fonction du code du document et celui de l'entreprise
         * on sortie de la, on aura, 
         * 1: la liste des article, 2:le client concerné, 3: la personne qui a créé la facture, le montants
        */
        $artdocuvente = $this->commerce->artdocumentvente($matricule_en,$code_document);
        if(!empty($artdocuvente)){
            $output .='
            <style type="text/css">
                table{
                    width:100%; 
                    border-collapse: collapse;
                }
                td, th{
                    border: 1px solid black;
                }
            </style>';
            /**je selectionne un document en fonction de son code et celui de l'entreprise 
             * au sorti, on aura, le code du document, le client, le commercial
            */
            $clientcommercialdoc = $this->commerce->docventeclientcommercial($code_document,$matricule_en);
            if(!empty($clientcommercialdoc)){
                $output .='<h6>FACTURE AU COMPTANT : '.$clientcommercialdoc['code_document'].'</h6>
                <p>DOIT : '.strtoupper($clientcommercialdoc['nom_cli']).' <br> Commercial: '.$clientcommercialdoc['nom_emp'].' / '.$clientcommercialdoc['telephone_cli'].' <br> <span style="font-size:10;">Enrégistré le: '.dateformat($clientcommercialdoc['date_creation_doc']).'</span> </p>
                ';
            }else{
                $output .='<b class="text-danger">informations manquante</b>'; 
            }
            $output .='
            <table>
                <tr>
                    <th>DESIGNATION / DESCRIPTION</th>
                    <th>QUANTITE</th>
                    <th>PRIX UNIT</th>
                    <th>PRIX TOTAL</th>
                </tr>
            ';

            foreach ($artdocuvente as $key => $value) {
                $output .='
                    <tr>
                        <td><b>'.$value['designation'].'</b> <br>'.$value['description_art'].'</td>
                        <td>'.$value['quantite'].'</td>
                        <td>'.numberformat($value['pu_HT']).'</td>
                        <td>'.numberformat($value['pt_HT']).'</td>
                    </tr>
                ';
            }
            if(!empty($clientcommercialdoc)){

                $nbr = !empty($clientcommercialdoc['pt_net_document'])?abs($clientcommercialdoc['pt_net_document'] - $clientcommercialdoc['pt_ht_document']):0;
                $ttir = $nbr>0?numberformat($nbr):0;

                $nbrttva = abs($clientcommercialdoc['pt_ttc_document'] - $clientcommercialdoc['pt_ht_document']);
                $ttva = $nbrttva>0?numberformat($nbrttva):0;

                $ttc = "";
                $net = "";
                if($ttir==0){
                    $ttc .='Montant TTC: '.numberformat($clientcommercialdoc['pt_ttc_document']);
                }else if($ttir !=0 ){
                    $ttc .='Montant TTC: '.numberformat($clientcommercialdoc['pt_ttc_document']);
                    $net .='Net à payé: '.numberformat($clientcommercialdoc['pt_net_document']);
                }
                $output .='
                    <tr>
                        <th class="badge badge-info">Total HT: '.numberformat($clientcommercialdoc['pt_ht_document']).'</th>
                        <th class="badge badge-danger">Total Tva: '.$ttva.'</th>
                        <th class="badge badge-danger">Total Ir: '.$ttir.'</th>
                        <th class="badge badge-success">'.$ttc.'</th>
                        <th class="badge badge-success">'.$net.'</th>
                    </tr>
                ';
            }else{
                $output .='<b class="text-danger">informations manquante</b>'; 
            }
            $output .='</table>';
        }else{
            $output .= '<b class="text-danger">le système ne retrouve pas les articles de ce document</b>';
        }

        $array= array(
            'infos' => $output,
            'link' => '<a href="'.base_url('facturer/'.$code_document.'').'" type="button" class="btn btn-outline-success" target="_blink">IMPRIMER</a>'
        );

        echo json_encode($array);
    }
    /**liste des article d'un bon de retour fin */


    /**afficher la liste des articles contenu dans un document pour faire un retour debut */

    /**afficher la liste des articles contenu dans un document pour faire un retour debut */
    public function all_article_form_rt(){
        $this->logged_in();
        
        $this->form_validation->set_rules('tdocument', 'choisi le type de document', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('document', 'choisi le document', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('commercial', 'choisi le commercial ou vendeur', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        $this->form_validation->set_rules('facture', 'choisi la facture', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
            'required' => '%s.',
            'regex_match' => 'caractère non pris en compte'
            )
        );

        if($this->form_validation->run()){

            /**informations utile */
            $matricule = session('users')['matricule'];
            $matricule_emp = session('users')['matricule_emp'];
            $agence = session('users')['matricule_ag'];

            $code_type_document = trim($this->input->post('tdocument'));
            $doc = trim($this->input->post('document'));
            $commercial = trim($this->input->post('commercial'));
            $code_facture = trim($this->input->post('facture'));

            /**verifier de quel type de document il s'agit*/
            $type_documents = $this->type_document($code_type_document, $matricule);
            if(!empty($type_documents)){
                if($type_documents == 'BR'){
                    $output ="";
                    /**
                     * la on connais le type de document, alors 
                     * 
                     * 1: on selectionne la facture en question
                     * 2: on selectionne la liste des produit de cette facture
                    */
                    /**1: je selectionne le document en question dans la table document */
                    $document = $this->commerce->specificdocument($code_facture,$matricule);
                    if(!empty($document)){
                        /**on selectionne la liste des articles du document*/
                        $artdocuvente = $this->commerce->artdocumentvente($matricule,$document['code_document']);
                        if(!empty($artdocuvente)){
                            $output .='
                            <table class="table table-head-noborder">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="mt-checkbox-list tout"></th>
                                        <th>DESIGNATION / DESCRIPTION</th>
                                        <th>QUANTITE</th>
                                        <th>PRIX UNIT</th>
                                        <th>PRIX TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <input type="hidden" name="docs" id="docs" value="'.$document['code_document'].'">
                                <input type="hidden" name="matdocret" id="matdocret" value="'.$doc.'">
                                <input type="hidden" name="typedoc" id="typedoc" value="'.$code_type_document.'">
                                ';  

                            foreach ($artdocuvente as $key => $value) {
                                $output .='
                                    <tr>
                                        <td><input type="checkbox" name="returnlist[]" value="'.$value['code_article'].'"></td>
                                        <td>'.$value['designation'].'</td>
                                        <td>'.$value['quantite'].'</td>
                                        <td>'.$value['pu_HT'].'</td>
                                        <td>'.$value['pt_HT'].'</td>
                                    </tr>';
                            }
                            $output .='
                                    <tr>
                                        <td colspan="5">
                                            <input type="submit" id="retour_artopbtn" name="retour_artopbtn" value="Retourner" class="btn btn-success retour_artopbtn">
                                        </td> 
                                    </tr>
                            
                                </tbody>
                            </table>';
                            $array = array(
                                'success' => $output
                            );

                        }else{
                            $array = array(
                                'success' => 'aucun article trouvé dans cette facture'
                            );
                        }
                    }else{
                        $array = array(
                            'success' => 'le système ne retrouve pas cette facture'
                        ); 
                    }
                }else{
                    $array = array(
                        'success' => 'ce type de document n\'est pas utile ici... utilise le menu de gauche'
                    );
                }
            }else{
                $array = array(
                    'success' => 'le système ne trouve pas ce type de document pour le retour'
                ); 
            } 
        }else{
            $array = array(
                'error'   => true,
                'commercial_error' => form_error('commercial'),
                'document_error' => form_error('document'),
                'facture_error' => form_error('facture'),
                'tdocument_error' => form_error('tdocument')
            );
        }
        echo json_encode($array);
    }

    /**opération de retour d'article dans le stock */
    public function operation_retour_article(){
        $this->logged_in();

        /**informations utile */
		$matricule = session('users')['matricule'];
        $matricule_emp = session('users')['matricule_emp'];
        $abrev ="BR";

        
        $code_facture = $this->input->post('docs');  
        $codedocugene = $this->input->post('matdocret');
        $returnlist = $this->input->post('returnlist');
        $typedoc = $this->input->post('typedoc');
        $output ="";
        if(!empty($returnlist)){

            /**on selectionne le document de l'article ou les articles a retourné en fonction de son code et celui de l'entreprise */
            $document = $this->commerce->specificdocument($code_facture,$matricule);
            if(!empty($document)){
               
                /**on verifie si le document a ou pas la tva et ou la ir */
                $ctva = ($document['pt_ttc_document'] - $document['pt_ht_document']);
                $cir = !empty($document['pt_net_document'])?($document['pt_net_document'] - $document['pt_ht_document']):0;
                $tva = ($ctva !=0)?'tva':'';
                $ir = ($cir !=0)?'ir':'';


                /**on parcour la liste des articles selectionné et on test a chaque fois */
                foreach ($returnlist as $key => $value){

                    /**on test si le document de retour est déjà créer ou pas */
                    $docbrtest = $this->commerce->documentbrtest($abrev,$matricule,$document['nom_document']);
                    if(empty($docbrtest)){
                        /** ======== DOCUMENT PAS ENCORE CREER =================*/

                        /**on selectionne l'article en particulier en fonction de son code, celui du document et celui de l'entreprise 
                         * pour pouvoir effectuer les opérations sur le br à creer
                        */
                        $ardocusingle = $this->commerce->art_document_single($matricule,$value,$document['code_document']);
                        
                        /**on determine les différents prix du document en fonction des taxes appliquées a l'article*/
                        $prixtaxes = $this->taxe($tva,$ir,$matricule,$ardocusingle['pu_HT'],$ardocusingle['quantite']);
                        
                        /**on le crer */
                        $input_doc = array(
                            'code_document'=>$codedocugene,
                            'nom_document'=> $document['nom_document'],
                            'date_creation_doc'=>dates(),
                            'code_type_doc'=> $typedoc,
                            'code_employe'=> $matricule_emp,
                            'code_entreprie'=> $matricule,
                            'depot_doc'=> $document['depot_doc'],
                            'code_agence_doc' => $document['code_agence_doc'],
                            'code_client_document'=> $document['code_client_document'],
                            'code_caisse'=> $document['code_caisse'],
                            'pt_ht_document'=> (-$ardocusingle['pt_HT']),
                            'pt_ttc_document'=> (-$prixtaxes['prixttc']),
                            'pt_net_document'=> (-$prixtaxes['prixnet'])
                        );

                        $new_document = $this->stock->new_document($input_doc);
                        if($new_document){

                            /**CE QUI SE PASSE SI LES RC SONT ACTIVE DEBUT */
                                /**
                                 * 1: on test le type de document avec la fonction typedocdetect($document['code_document'])
                                 * si c'est une dette, allors on regularise la dette sur le document en question
                                 * on a donc 5sénario: 
                                 * 1: la dette n'est pas payé
                                 * 2: la dette est totalement payé (100%)
                                 * 3: la dette est payé a 25%
                                 * 4: la dette est payé a 50%
                                 * 5: la dette est payé a 75%
                                 * 
                                 * une fois terminé, on met a jour le document puis on stock les articles du br, et on regularise le stock
                                 */
                            /**CE QUI SE PASSE SI LES RC SONT ACTIVE FIN */
                           
                            /**on enregistre les articles du document */
                            $input_art_doc = array(
                                'code_article'=> $value,
                                'code_document'=> $codedocugene,
                                'quantite'=> ($ardocusingle['quantite']),
                                'pu_HT'=> (-$ardocusingle['pu_HT']),
                                'pt_HT'=> (-$ardocusingle['pt_HT']),
                                'code_emp_art_doc'=> $matricule_emp,
                                'code_en_art_doc'=> $matricule, 
                                'date_creer_art_doc'=> dates()
                            );
                            $save_art_from_doc = $this->stock->article_document($input_art_doc);
                            if($save_art_from_doc){
                                    /**on met a jour le stock, en fonction du depot et de l'entreprise 
                                     * on retour les quantité dans le stock du dépot
                                     * 
                                     * 1: on selectionne d'abord l'article pour le depot en stock (pour pouvoir additionner les quantité)
                                     * 2: on met a jour le stock en fonction du depot
                                    */
                                    $verify_art_stock = $this->stock->get_article_stock($value,$document['depot_doc']);
                                    $input_stock_val = array(
                                        'code_employe'=> $matricule_emp,
                                        'code_depot'=> $document['depot_doc'],
                                        'code_entreprise'=> $matricule,
                                        'quantite'=> ($ardocusingle['quantite'] + $verify_art_stock['quantite']),
                                        'date_modifier_stock'=> dates()
                                    );
                                    $update_art_st = $this->stock->update_stock2($value, $input_stock_val, $document['depot_doc']);
                                    if($update_art_st){
                                        $output = 'retour de l\'article éffectué';
                                    }else{
                                        $output = "ATTENTION, stock non régularisé contactez l'administrateur";
                                    }
                            }else{
                                $output = "erreur survenu. article du document non enrégistrer, vérifiez votre connexion internet";
                            }
                        }else{
                            $output = "erreur survenu. document non créer, vérifiez votre connexion internet";   
                        }
                    }else{
                        /**==================DOCUMENT DE RETOUR DEJA CREER =================== */

                        /**on test pour voir s'il contient déjà l'article a rétourné */
                        $ardocusingle = $this->commerce->art_document_single($matricule,$value,$docbrtest['code_document']);
                        if(empty($ardocusingle)){
                            /**l'article a retourné n'existe pas 
                             * 1: on met a jour le document de retour
                             * 2: on ajoute l'article a ca liste
                             * 3: on met a jour le stock
                            */

                            /**on selectionne l'article en particulier en fonction de son code, celui du document et celui de l'entreprise 
                             * pour pouvoir effectuer les opérations sur le br
                            */
                            $ardocusingle = $this->commerce->art_document_single($matricule,$value,$document['code_document']);
                        
                            /**on determine les différents prix du document en fonction des taxes appliquées a l'article*/
                            $prixtaxes = $this->taxe($tva,$ir,$matricule,$ardocusingle['pu_HT'],$ardocusingle['quantite']);

                            $inputdataupdate = array(
                                'pt_ht_document'=> ($docbrtest['pt_ht_document'] - $ardocusingle['pt_HT']),
                                'pt_ttc_document'=>($docbrtest['pt_ttc_document'] - $prixtaxes['prixttc']),
                                'pt_net_document'=>($docbrtest['pt_net_document'] - $prixtaxes['prixnet']),
                                'date_modifier_doc'=>dates()
                            );
                            /***on modifie le document de dette pour le marquer comme réglé*/
                            $updatedoc = $this->commerce->updatedocument($docbrtest['code_document'],$matricule,$inputdataupdate);
                            if($updatedoc){
                                /**on ajoute l'article dans la liste du ducument de retour*/

                                $input_art_doc = array(
                                    'code_article'=> $value,
                                    'code_document'=> $docbrtest['code_document'],
                                    'quantite'=> ($ardocusingle['quantite']),
                                    'pu_HT'=> (-$ardocusingle['pu_HT']),
                                    'pt_HT'=> (-$ardocusingle['pt_HT']),
                                    'code_emp_art_doc'=> $matricule_emp,
                                    'code_en_art_doc'=> $matricule, 
                                    'date_creer_art_doc'=> dates()
                                );
                                $save_art_from_doc = $this->stock->article_document($input_art_doc);
                                if($save_art_from_doc){
                                    /**on met a jour le stock, en fonction du depot et de l'entreprise 
                                     * on retour les quantité dans le stock du dépot
                                     * 
                                     * 1: on selectionne d'abord l'article pour le depot en stock (pour pouvoir additionner les quantité)
                                     * 2: on met a jour le stock en fonction du depot
                                    */
                                    $verify_art_stock = $this->stock->get_article_stock($value,$docbrtest['depot_doc']);
                                    $input_stock_val = array(
                                        'code_employe'=> $matricule_emp,
                                        'code_depot'=> $docbrtest['depot_doc'],
                                        'code_entreprise'=> $matricule,
                                        'quantite'=> ($ardocusingle['quantite'] + $verify_art_stock['quantite']),
                                        'date_modifier_stock'=> dates()
                                    );
                                    $update_art_st = $this->stock->update_stock2($value, $input_stock_val, $docbrtest['depot_doc']);
                                    if($update_art_st){
                                        $output = 'retour de l\'article éffectué';
                                    }else{
                                        $output = "ATTENTION, stock non régularisé contactez l'administrateur";
                                    }
                                }else{
                                    $output = "erreur survenu. article du document non enrégistrer, vérifiez votre connexion internet";
                                }
                            }else{
                                $output ='document non mis à jour... verifiez votre connexion internet';
                            }
                        }else{
                            /**l'article existe déjà dans le document de retour
                             * 1: on test si ca quantité saisi dans le document de retour original correspond a sa quantite de retour déjà retourné
                             *  si oui, article déjà retourné
                             * si non
                             * 1: on compare:
                             * si qte saisi est <= a quantité manquante alors on mete a jour (le document de retour, l'article du document, le stock)
                             * si qte saisi >a qte manquante, envoie le message: pour cet article, il manqute qtemanquante pour annuler ca sortie de stock
                            */

                            $qtedejaretourner = $this->commerce->art_document_single($matricule,$value,$docbrtest['code_document']);
                            $qteinitial = $this->commerce->art_document_single($matricule,$value,$document['code_document']);
                            if($qtedejaretourner['quantite'] == $qteinitial['quantite']){
                                $output ='article déjà retourné';
                            } 
                        } 
                    }
                }
                $array = array(
                    'success'=> $output
                );
            }else{
                $output .= 'le système ne trouve pas le document';
            }
        }else{
            $array = array(
                'success'=>'choisi aumoins un article à rétourner'
            );
        }
        echo json_encode($array);
    }



    /************************** gestion des bons de retour fin ******************/

    /***********************************gestion des sorti de caisse debut ***********************************/

    /**page de sorti de caisse */
    public function sorti_caisse(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

        /**informations utile */
		$matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];
        
        /**affiche la liste des type de documents pour une entreprise donné */
        $data['docs'] = $this->commerce->type_doc($matricule);

        /**affiche la liste des caisse en fonction de l'entreprise et ou de l'agence */
        $data['caisses'] = $this->commerce->allcaisseagence($matricule,$agence);
                

        /**affiche les vendeur: c'est la personne connecté au momement de la vente */
        $data['vendeur'][] = array(
            'matricule' => session('users')['matricule_emp'],
            'nom' => session('users')['nom_emp']
        );

        /**affiche la liste des agence en fonction de l'entreprise et ou de l'agence*/
        $data['agences'] = $this->commerce->all_agences($matricule,$agence);

        /**afficher le code et le nom d'un document générer*/
        $data['code_doc'] = array(
            'SC-DOC-'.code(10) => 'SC-DOCUMENT-TSC-'.code(10).'-'.date('d-m-Y'),
        );
        
        $this->load->view('commerce/sorticaisse',$data);

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }

    /**opération de sorti de caisse */
    public function op_sorti_caisse(){
        $this->logged_in();
        $this->form_validation->set_rules('tdocument', 'type document', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'entre le %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('agence', 'agence', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			//'required' => 'choisi l\'agence',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('concerne', 'concerne', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'a qui on donne?',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('montant', 'montant', 'required|regex_match[/^[0-9]+$/]',array(
			'required' => 'quel est le %s?',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('motif', 'motif', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'quel est le %s?',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('caisse', 'caisse', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'quel est la %s?',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('commercial', 'commercial', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'quel est le %s?',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('document', 'document', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]|is_unique[sorti_caisse.matricule_sorti]',array(
			'required' => 'choisi le %s?',
			'regex_match' => 'caractère(s) non autorisé(s)',
            'is_unique' =>'document déjà utilisé actualise la page'
		));

        if($this->form_validation->run()){

            /**informations utile */
		    $matricule_en = session('users')['matricule'];
            $agencecon = session('users')['matricule_ag'];

            $code_type_document = trim($this->input->post('tdocument'));
            $code_agence = trim($this->input->post('agence'));
            $concerne = trim($this->input->post('concerne'));
            $montant = trim($this->input->post('montant'));
            $motif = trim($this->input->post('motif'));
            $code_caisse = trim($this->input->post('caisse'));
            $code_commercial = trim($this->input->post('commercial'));
            $code_document = trim($this->input->post('document'));

           
            if(!empty($code_document) && !empty($code_type_document)){
                /**on verifie le type de document */
                $verify_typ_doc = $this->type_document($code_type_document,$matricule_en);
                if(!empty($verify_typ_doc)){
                    if($verify_typ_doc == 'SC'){

                        /*selectionnons le code de l'agence à partir de la caisse si l'agence est vide*/
                        $agencecaisse = $this->commerce->agencecaisse($code_caisse,$matricule_en);
                        $agence = empty($agencecon)?$agencecaisse['code_agence']:$agencecon;
                        
                        if($code_agence == $agence){
            
                            $input_data = array(
                                'matricule_sorti' => $code_document,
                                'nom_sorti' => 'SC-DOCUMENT-TSC-'.code(10).'-'.date('d-m-Y'),
                                'type_doc_sorti' => $code_type_document,
                                'agence_sorti' => $agence,
                                'entreprise_sorti' => $matricule_en,
                                'concerne_sorti' => $concerne,
                                'montatnt_sorti' => $montant,
                                'motif_sorti' => $motif,
                                'caisse_sorti' => $code_caisse,
                                'employe_sorti' => $code_commercial,
                                'creer_le_sorti' => dates() 
                            );

                            /**inserer une sorti de caisse dans la base des données */
                            $new_sorti = $this->commerce->new_sorti_caisse($input_data);
                            if($new_sorti){
                                $array = 	array(
                                    'success'   => '
                                        <script>
                                            swal.fire("Bravo!","nouvelle sorti de caisse enrégistrer","success")
                                        </script>
                                    '
                                );
                            }else{
                                $array = 	array(
                                    'success'   => '
                                        <script>
                                            swal.fire("ERREUR!","sorti de caisse non enrégistrer","error")
                                        </script>
                                    '
                                );
                            }
                        }else{
                            $array = 	array(
                                'success'   => '
                                    <script>
                                        swal.fire("ERREUR!","assurez-vous d\'avoir choisi l\'agence de la caisse sélectionné","error")
                                    </script>
                                '
                            );
                        }
                    }else{
                        $array = 	array(
                            'success'   => '
                                <script>
                                    swal.fire("ERREUR!","ce type de document n\'est pas utile ici, utilise le menu de gauche","info")
                                </script>
                            '
                        );
                    } 
                }else{
                    $array = 	array(
                        'success'   => '
                            <script>
                                swal.fire("ERREUR!","le systeme ne trouve pas le type de document. contactez l\'administrateur","info")
                            </script>
                        '
                    );
                }
            }else{
                $array = 	array(
                    'success'   => '
                        <script>
                            swal.fire("ERREUR!","contactez l\'administrateur","info")
                        </script>
                    '
                );
            }
        }else{
			$array = array(
				'error'   => true,
				'tdocument_error' => form_error('tdocument'),
                'agence_error' => form_error('agence'),
                'concerne_error' => form_error('concerne'),
                'montant_error' => form_error('montant'),
                'motif_error' => form_error('motif'),
                'caisse_error' => form_error('caisse'),
                'commercial_error' => form_error('commercial'),
                'document_error' => form_error('document')
			);
		}

		echo json_encode($array);
    }

    /**afficher la liste des sorti de caisse */
    public function all_sorti_caisse(){
        $this->logged_in();

        $output = "";

        /**informations utile */
		$matricule_en = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];


            $nbrdocsc = $this->commerce->countdocsorticaisse($matricule_en,$agence);
            
            /** PAGINATION DEBUT */
            $config = array();
            $config["base_url"] = "#";
            $config["total_rows"] = $nbrdocsc;
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
            $config["cur_tag_open"] = "<li class='page-item active'><a href='#'><span class='page-link'><span class='sr-only'>(current)</span>";
            $config["cur_tag_close"] = "</span></a></li>";
            $config["num_tag_open"] = '<li class="page-item page-link">';
            $config["num_tag_close"] = "</li>";
            $config["num_links"] = 1;
            $this->pagination->initialize($config);
            
            $page = (($this->uri->segment(2) - 1) * $config["per_page"]);
            /** PAGINATION FIN */

        /**liste des sorti de caisse */
        $all_sorti = $this->commerce->all_sorti_caisse($matricule_en,$agence,$config["per_page"],$page);
        $montanttotal=0;
        $nbrsorti=0;
        if(!empty($all_sorti)){
            foreach ($all_sorti as $key => $value){
                $montanttotal+=$value['montatnt_sorti'];
                $nbrsorti+=1;
                $output .='
                    <tr>
                        <th>'.$value['matricule_sorti'].'</th>
                        <!--<td></td>-->
                        <td>'.numberformat($value['montatnt_sorti']).'</td>
                        <th>'.$value['concerne_sorti'].'</th>
                        <td>'.$value['nom_emp'].'</td>
                        <td>'.$value['nom_ag'].'</td>
                        <th>'.$value['motif_sorti'].'</th>
                        <td>'.dateformat($value['creer_le_sorti']).'</td>
                        <td>
                            <button id="'.$value['matricule_sorti'].'" class="btn btn-icon btn-circle btn-label-linkedin delete_sort_c">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                ';
                $lastsorti = dateformat($value['creer_le_sorti']);
            }
        }else{
            $output .='aucune sortie de caisse trouvé';
        }
        
        $array = array(
            'success'=>$output,
            'total'=>numberformat($montanttotal),
            'nbrsorti'=>numberformat($nbrsorti),
            'pagination_link' => $this->pagination->create_links()
        );
        echo json_encode($array);
    }

    /**supprimer une sortie de caisse */
    public function delete_sorti_caisse(){
        $this->logged_in();
        $output = "";
        $code_s = trim($this->input->post('code_s'));

        /**informations utile */
		$matricule_en = session('users')['matricule'];

        /**supression d'une sortie de caisse en fonction de son code et de l'entreprise */
        $isOk = $this->commerce->delete_sorti($code_s,$matricule_en);
        if($isOk){

            $output .='
                <script>
                    swal.fire("PARFAIT!","suppression effectué avec succès","succes")
                </script>
            ';
        }else{
            $output .='
                <script>
                    swal.fire("erreur!","erreur survenu, sorti de caisse non supprimer. CONTACTEZ l\'ADMINISTRATEUR","error")
                </script>
            ';
        }
        
        echo json_encode($output);
    }

    /**********************************gestion des sorti ed caisse fin ***************************** */

    /******************************************gestion des entrer en caisse debut ****************** */

    /**affiche la page des entrer en caisse */
    public function entre_caisse(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');
        
        /**informations utile */
		$matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];
        
        /**affiche la liste des type de documents pour une entreprise donné */
        $data['docs'] = $this->commerce->type_doc($matricule);

        /**affiche la liste des caisse en fonction de l'entreprise et ou de l'agence */
        $data['caisses'] = $this->commerce->allcaisseagence($matricule,$agence);


        /**affiche les vendeur: c'est la personne connecté au momement de la vente */
        $data['vendeur'][] = array(
            'matricule' => session('users')['matricule_emp'],
            'nom' => session('users')['nom_emp']
        );

        /**affiche la liste des clients d'une entreprise */
        $data['clients'] = $this->commerce->all_client($matricule);

        /**affiche la liste des agence en fonction de l'entreprise et ou de l'agence*/
        $data['agences'] = $this->commerce->all_agences($matricule,$agence);

        /**afficher le code et le nom d'un document générer*/
        $data['code_doc'] = array(
            'EC-DOC-'.code(10) => 'EC-DOCUMENT-TEC-'.code(10).'-'.date('d-m-Y'),
        );
        
        $this->load->view('commerce/entrecaisse',$data);

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }

    /**opération sur une entrer en caisse */
    public function op_entrer_caisse(){
        $this->logged_in();
        $this->form_validation->set_rules('client', 'client', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'choisi le %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('caisse', 'caisse', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'choisi la %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('montant', 'montant', 'required|regex_match[/^[0-9]+$/]',array(
			'required' => 'entre le %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('commercial', 'commercial', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'quel est le %s?',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('document', 'document', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]|is_unique[entrer_caisse.matricule_enter]',array(
			'required' => 'choisi le %s?',
			'regex_match' => 'caractère(s) non autorisé(s)',
            'is_unique' =>'document déjà utilisé actualise la page'
		));

        $this->form_validation->set_rules('tdocument', 'type document', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'entre le %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('agence', 'agence', 'regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'regex_match' => 'caractère(s) non autorisé(s)'
		));
		
		$this->form_validation->set_rules('motif', 'motif', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
		    'required' => 'saisi le %s?',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        if($this->form_validation->run()){
            /**informations utile */
		    $matricule_en = session('users')['matricule'];

            $code_client = trim($this->input->post('client'));
            $code_caisse = trim($this->input->post('caisse'));
            $montant = trim($this->input->post('montant'));
            $code_type_document = trim($this->input->post('tdocument'));
            $code_commercial = trim($this->input->post('commercial'));
            $code_document = trim($this->input->post('document'));
            $code_agence = trim($this->input->post('agence'));
            $motif = trim($this->input->post('motif'));



            /**=============== OPERATION ENTRE EN CAISSE DEBUT =========*/

            /**ON S'assure que c'est le bon type de document qui est choisi */
            $verify_typ_doc = $this->type_document($code_type_document,$matricule_en);
            if(!empty($verify_typ_doc)){
                if($verify_typ_doc == 'EC'){

                    /** si une caisse est choisi, on verifie si elle a une agence
                     * si oui... on reseigne nous même l'agence dans le champs de la base
                     * des données correspondant
                    */

                    $agencecaisse = $this->commerce->agencecaisse($code_caisse,$matricule_en);
                    $agence = !empty($agencecaisse)?$agencecaisse['code_agence']:NULL;

                    /**on verifie si le client existe déjà dans l'entrer en caisse pour la caisse choisi, l'agence et l'entreprise*/
                    $verify_cli = $this->commerce->verify_client($code_caisse,$code_client,$matricule_en);
                    if(empty($verify_cli)){
                        /** =================LE CLIENT N'EXISTE PAS =======================*/

                        /**on creer son document d'entrer en caisse */
                        $input_data = array(
                            'matricule_enter' => $code_document,
                            'nom_enter' => 'EC-DOCUMENT-TEC-'.code(10).'-'.date('d-m-Y'),
                            'type_doc_enter' => $code_type_document,
                            'agence_enter' => $agence,
                            'entreprise_enter' => $matricule_en,
                            'client_enter' => $code_client,
                            'montant_enter' => $montant,
                            'caisse_enter' => $code_caisse,
                            'employe_enter' => $code_commercial,
                            'creer_le_enter' => dates() 
                        );
                        /**inserer une entrer de caisse dans la base des données */
                        $new_enter = $this->commerce->new_enter_caisse($input_data);
                        if($new_enter){
                            /**on enregistrer l'historique de l'entrer en caisse*/
                            $inputdatah = array(
                                'codeentrercaisse' => $code_document,
                                'montant' => $montant,
                                'employe' => $code_commercial,
                                'motif_entre_caisse' => $motif,
                                'dateentrer' => dates() 
                            );
                            $newenterh = $this->commerce->historique_enter_caisse($inputdatah);
                            if($newenterh){
                                $array = array(
                                    'success' => '<script>
                                        swal.fire({
                                            position:"top-right",
                                            type:"success",
                                            title:"Bravo! nouvelle entrer en caisse enrégistrer avec success",
                                            showConfirmButton:!1,timer:5000})
                                    </script>'
                                );
                            }else{
                                $array = array(
                                    'success' => '<script>
                                        swal.fire({
                                            position:"top-right",
                                            type:"error",
                                            title:"erreur survenu, historique non enrégistrer, contactez l\'administrateur",
                                            showConfirmButton:!1,timer:5000})
                                    </script>'
                                );
                            }
                        }else{
                            $array = array(
                                'success' => '<script>
                                    swal.fire({
                                        position:"top-right",
                                        type:"error",
                                        title:"erreur survenu, entrer en caisse non creer, reéssayez",
                                        showConfirmButton:!1,timer:5000})
                                </script>'
                            );
                        }
                    }else{
                        /** =================LE CLIENT EXISTE =======================*/

                        /**on met a jour le document d'entrer en caisse du client */
                        $input_data = array(
                            'montant_enter' => ($montant + $verify_cli['montant_enter']),
                            'employe_enter' => $code_commercial,
                            'modifier_le_enter' => dates() 
                        );
                        /**on met a jour l'entrer de caisse dans la base des données */
                        $update_enter = $this->commerce->update_enter_caisse($input_data,$code_client,$code_caisse);
                        if($update_enter){
                            /**on enregistre l'entrer en caisse dans l'historique */
                            $inputdatah = array(
                                'codeentrercaisse' => $verify_cli['matricule_enter'],
                                'montant' => $montant,
                                'motif_entre_caisse' => $motif,
                                'employe' => $code_commercial,
                                'dateentrer' => dates() 
                            );
                            $newenterh = $this->commerce->historique_enter_caisse($inputdatah);
                            if($newenterh){
                                $array = array(
                                    'success' => '<script>
                                        swal.fire({
                                            position:"top-right",
                                            type:"success",
                                            title:"Bravo! nouvelle entrer en caisse enrégistrer avec success",
                                            showConfirmButton:!1,timer:5000})
                                    </script>'
                                );
                            }else{
                                $array = array(
                                    'success' => '<script>
                                        swal.fire({
                                            position:"top-right",
                                            type:"error",
                                            title:"ERREUR!, historique non enrégistrer, contactez l\'administrateur",
                                            showConfirmButton:!1,timer:5000})
                                    </script>'
                                );
                            }
                        }else{
                            $array = array(
                                'success' => '<script>
                                    swal.fire({
                                        position:"top-right",
                                        type:"error",
                                        title:"ERREUR!  entrer de caisse non enrégistrer",
                                        showConfirmButton:!1,timer:5000})
                                </script>'
                            );
                        }
                    }
                }else{
                    $array =    array(
                        'success'   => '
                            <script>
                                swal.fire("ERREUR!","ce type de document n\'est pas utile ici, utilise le menu de gauche","warning")
                            </script>
                        '
                    );
                }
            }else{
                $array =    array(
                    'success'   => '
                        <script>
                            swal.fire("ERREUR!","le systeme ne trouve pas le type de document. contactez l\'administrateur","error")
                        </script>
                    '
                );
            }


            /**=============== OPERATION ENTRE EN CAISSE FIN =========*/

            
        }else{
			$array = array(
				'error'   => true,
                'tdocument_error' => form_error('tdocument'),
                'agence_error' => form_error('agence'),
				'montant_error' => form_error('montant'),
                'client_error' => form_error('client'),
                'caisse_error' => form_error('caisse'),
                'motif_error' => form_error('motif'),
                'commercial_error' => form_error('commercial'),
                'document_error' => form_error('document')
			);
		}

		echo json_encode($array); 
    }

    /**toutes les entrer en caisse */
    public function all_entrer_caisse(){
        $this->logged_in();
        $output = "";
        
        /**informations utile */
		$matricule_en = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];


        $recherche = trim($this->input->post('recherche'));
        if(!empty($recherche)){
            /**on cherche dans la base des donné un entrer en caisse en fonction du client de l'entreprise et ou de l'agence */
            $rechercheentr = $this->commerce->recherche_entrer_caisse($matricule_en,$agence,$recherche);
            if(!empty($rechercheentr)){
                foreach ($rechercheentr as $key => $values) {
                    $output .='
                        <tr>
                            <th><i class="fas fa-info-circle text-danger details" id="'.$values['matricule_enter'].'"></i> '.$values['matricule_enter'].'</th>
                            <td>'.$values['montant_enter'].'</td>
                            <th>'.$values['nom_cli'].'</th>
                            <td>'.$values['nom_emp'].'</td>
                            <td>'.$values['nom_ag'].'</td>
                            <th>'.$values['libelle_caisse'].'</th>
                            <td>'.dateformat($values['creer_le_enter']).'</td>
                            <td>
                                '.dateformat($values['modifier_le_enter']).'
                                <!--<button id="" class="btn btn-icon btn-circle btn-label-linkedin delete_sort_c">
                                    <i class="fa fa-trash-alt"></i>
                                </button>-->
                            </td>
                        </tr>
                    ';
                }
            }else{
                $output .='<tr><td colspan="8"><b class="text-danger">ce client n\'a pas encre d\'entré en caisse, verifier puis reéssayez</b></td></tr>';
            }
            $array = array(
                'infos' => $output,
            );
        }else{
            
            /**nombre d'entrer en caisse */
            $nbrentrecaisse = $this->commerce->count_all_entrer_caisse($matricule_en,$agence);

            /** PAGINATION DEBUT */
            $config = array();
            $config["base_url"] = "#";
            $config["total_rows"] = $nbrentrecaisse;
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
            $config["cur_tag_open"] = "<li class='page-item active'><a href='#'><span class='page-link'><span class='sr-only'>(current)</span>";
            $config["cur_tag_close"] = "</span></a></li>";
            $config["num_tag_open"] = '<li class="page-item page-link">';
            $config["num_tag_close"] = "</li>";
            $config["num_links"] = 1;
            $this->pagination->initialize($config);
            
            $page = (($this->uri->segment(2) - 1) * $config["per_page"]);
            /** PAGINATION FIN */


            /**liste des sorti de caisse */
            $all_entrer = $this->commerce->all_entrer_caisse($matricule_en,$agence,$config["per_page"],$page);
            if(!empty($all_entrer)){
                foreach ($all_entrer as $key => $value) {
                    $output .='
                        <tr>
                            <th><i class="fas fa-info-circle text-danger details" id="'.$value['matricule_enter'].'"></i> '.$value['matricule_enter'].'</th>
                            <td>'.$value['montant_enter'].'</td>
                            <th>'.$value['nom_cli'].'</th>
                            <td>'.$value['nom_emp'].'</td>
                            <td>'.$value['nom_ag'].'</td>
                            <th>'.$value['libelle_caisse'].'</th>
                            <td>'.dateformat($value['creer_le_enter']).'</td>
                            <td>
                                '.dateformat($value['modifier_le_enter']).'
                                <!--<button id="" class="btn btn-icon btn-circle btn-label-linkedin delete_sort_c">
                                    <i class="fa fa-trash-alt"></i>
                                </button>-->
                            </td>
                        </tr>
                    ';
                }
            }else{
                $output .='aucune sortie de caisse trouvé';
            }

            $array = array(
                'infos' => $output,
                'pagination_link' => $this->pagination->create_links()
            );
        }
        echo json_encode($array);
    }
    
    /*details(historique de l'entrer en caisse****/
    public function detail_entrer_caisse(){
        $this->logged_in();
        $output = "";
        
        $matenter = $this->input->post('code');
        if(!empty($matenter)){
            /**liste des opérations d'entrer en caisse pour un client donner*/
            $historique = $this->commerce->historique_entrer_caisse($matenter);
            if(!empty($historique)){
                $datemodifier = !empty($value['dateentrer_modifier'])?date('d-m-Y H:i:sa',strtotime($value['dateentrer_modifier'])):'';
                foreach($historique as $value){
                    $output .= '
                        <tr>
                          <th>'.$value['nom_emp'].'</th>
                          <td>'.numberformat($value['montant']).'</td>
                          <td>'.dateformat($value['dateentrer']).'</td>
                          <td>'.$datemodifier.'</td>
                          <td>
                            <button class="btn btn-icon btn-circle btn-label-linkedin delete_entre_c" id="'.$value['id'].'">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                            </td>
                          <td>'.$value['motif_entre_caisse'].'</td>
                        </tr>
                    '; 
                }
            }else{
              $output .= 'le système ne trouve pas d\'historique pour ce client';   
            }
        }else{
           $output .= 'code vide, contactez l\'administrateur'; 
        }
        
        echo json_encode($output);
    }
    

    /**annuler une entrer en caisse en la supprimant */
    public function delete_entrer_caisse(){
        $this->logged_in();

        /**informations utile */
		$matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];
        $output ="";

        $codehist = trim($this->input->post('code_e'));

        /**on selectionne l'historique de l'entrer en caisse en fonction de son id*/
        $specifichistoric_e_c = $this->commerce->single_historique_entrercaisse($codehist);
        if(!empty($specifichistoric_e_c)){

            /**on selectionne le document d'entrer en caisse dans la table entrer_caisse en fonction
             * de son code et celui de l'entreprise
            */
            $single_entrer_caisse = $this->commerce->single__entrer_caisse($specifichistoric_e_c['codeentrercaisse'],$matricule);
            if(!empty($single_entrer_caisse)){

                /**avant de supprimer l'historique de l'entrer en caisse, on met a jour, les entrer du client */
                
                if($single_entrer_caisse['montant_enter'] >= $specifichistoric_e_c['montant']){
                    
                    /**on met a jour le document d'entrer en caisse */
                    $inputupdate = array(
                        'montant_enter' => ($single_entrer_caisse['montant_enter'] - $specifichistoric_e_c['montant']),
                        'modifier_le_enter' => dates()
                    );
                    $update_entrer_caisse = $this->commerce->updatespecific__entrer_caisse($single_entrer_caisse['matricule_enter'],$inputupdate);
                    if($update_entrer_caisse){

                        /**on supprimer maintenant l'historique de l'entrer en caisse en fonctiion de l'id */
                        $deletehist = $update_entrer_caisse = $this->commerce->deletespecific_hist_entrer_caisse($specifichistoric_e_c['id']);
                        if($deletehist){
                            $output .='
                            <script>
                                swal.fire({
                                    position:"top-right",
                                    type:"success",
                                    title:"entrer en caisse annuler avec succès",
                                    showConfirmButton:!1,timer:5000})
                            </script>';
                        }else{
                            $output .='
                            <script>
                                swal.fire({
                                    position:"top-right",
                                    type:"error",
                                    title:"erreur surnu, entrer en caisse non annuler",
                                    showConfirmButton:!1,timer:5000})
                            </script>';
                        }
                    }else{
                        $output .='
                        <script>
                            swal.fire({
                                position:"top-right",
                                type:"error",
                                title:"erreur survenu, document non mis à jours",
                                showConfirmButton:!1,timer:5000})
                        </script>';
                    }
                }else{
                    $output .='
                    <script>
                        swal.fire({
                            position:"top-right",
                            type:"warning",
                            title:"on ne peux annuler un montant déjà dépensé",
                            showConfirmButton:!1,timer:5000})
                    </script>';
                }
            }else{
                $output .='
                <script>
                    swal.fire({
                        position:"top-right",
                        type:"error",
                        title:"le système ne retrouve pas le document d\'entrer en caisse",
                        showConfirmButton:!1,timer:5000})
                </script>';
            }
        }else{
            $output .='
            <script>
                swal.fire({
                    position:"top-right",
                    type:"error",
                    title:"le système ne trouve pas ce mouvement",
                    showConfirmButton:!1,timer:5000})
            </script>';
        }
        echo json_encode($output);
    }





    /******************gestion des entrer en caisse fin ***********************/


    /*******************************gestion des ratachement debut  de facture caisse*********************************/

    /**affiche la page de ratachement de facture caisse*/
    public function ratache_fac(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

        /**informations utile */
		$matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];

        /**affiche la liste des clients d'une entreprise */
        $data['clients'] = $this->commerce->all_client($matricule);

        $this->load->view('commerce/ratache_reglement_client',$data);

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');

    }

    /**afficher la liste des reglements client(dettes) d'un client pour pouvoir faire le ratachement*/
    public function reglement_client_cli(){
        $this->logged_in();
        $output = "";

        $code_client = trim($this->input->post('code_cli'));
        $matricule_en = session('users')['matricule'];
        $abrev = "RC";

        /**on selectionne les reglement client non réglé en fonction du client*/
        $dettecli = $this->commerce->reglementcli_cli($abrev,$matricule_en,$code_client);  
        if(!empty($dettecli)){
            foreach ($dettecli as $key => $value){
                if($value['dette_restante'] != 0){
                    $output .='<option value="'.$value['code_document'].'" selected>'.$value['code_document'].' --> '.numberformat($value['pt_ttc_document']).' --> '.dateformat($value['date_creation_doc']).'</option>';  
                } 
            }
        }else{
            $output .='<option selected disabled>aucune dette pour ce client</option>';
        }
        $array = array(
            'success'=>$output
        );
        echo json_encode($array);
    }

    /**operation de ratachement */
    public function op_ratache_facture(){
        $this->logged_in();
        $this->form_validation->set_rules('client', 'client', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'choisi le %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        $this->form_validation->set_rules('facture', 'facture', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
			'required' => 'choisi la %s',
			'regex_match' => 'caractère(s) non autorisé(s)'
		));

        if($this->form_validation->run()){

            /**inofrmations utilent */
            $matricule_en = session('users')['matricule'];
            $matricule_emp  = session('users')['matricule_emp'];
            $output="";
            //$abrev = "RC";

            $code_client = trim($this->input->post('client'));
            $code_facture = trim($this->input->post('facture'));

            $caisse = trim($this->input->post('caisse'));

            /**on test pour voir si la caisse est choisi ou pas, dans le cas des ratachement de banque */
            if(empty($caisse)){

                /**on selectionne le document en question(la dette) */
                $documentdette = $this->commerce->specificdocument($code_facture,$matricule_en);
                if(!empty($documentdette)){

                    /**on met à jour le document de 
                     * 1: on selectionne les versements, du client en fonction du code de client, de la caisse, et l'entreprise
                     * 2: on met à jour le document, 
                     * 3: on met à jour la l'entrer en caisse
                     * 4: on enregistre l'historique de ratachement
                    */

                    /**versement du client */
                    $montantcaisse = $this->commerce->entrer_caisse_cli($documentdette['code_entreprie'],$documentdette['code_client_document'],$documentdette['code_caisse']);
                    if(!empty($montantcaisse)){

                        /**si le montant en caisse est supérieur a 0, alors on procède au ratachement */
                        if($montantcaisse['montant_enter']>0){

                            /** on verifie si la dette est soldé ou pas avant de faire les opérations de solde de dette*/
                            if($documentdette['dette_restante'] != 0){

                                /**on met a jour la dette restante et la dette réglé du client dans son document de dette */
                                
                                /**montant en caisse > au montant du document? */
                                if($montantcaisse['montant_enter'] >= $documentdette['dette_restante']){
                                
                                    /*($montantcaisse['montant_enter']$documentdette['dette_regler'] - ),*/
                                    $inputdataupdate = array(
                                        'dette_regler'=> ($documentdette['dette_regler'] + $documentdette['dette_restante']),
                                        'dette_restante'=>0,
                                        'date_modifier_doc'=>dates()
                                    );
                                    
                                    /***on modifie le document de dette pour le marquer comme réglé*/
                                    $updatedoc = $this->commerce->updatedocument($documentdette['code_document'],$matricule_en,$inputdataupdate);
                                    if($updatedoc){

                                        /**une fois le document mis a jour, on creer son historique */
                                        $inputdoc = array(
                                            'montantregler' => $documentdette['dette_restante'],
                                            'dateop' => dates(),
                                            'codeclient' => $documentdette['code_client_document'],
                                            'codedocument' => $documentdette['code_document'],
                                            'inititeurop' => $matricule_emp,
                                            'codeenh' => $matricule_en,
                                            'codecaisseh' => $montantcaisse['caisse_enter'], 
                                        );
                                        $resultgive = $this->commerce->historiqueratachement($inputdoc);
                                        if($resultgive){

                                            /**on met a jour le montant en caisse du client */
                                            $input_doc = array(
                                                'modifier_le_enter'=>dates(),
                                                'montant_enter'=> ($montantcaisse['montant_enter'] - $documentdette['dette_restante']),
                                                'employe_enter'=> $matricule_emp,
                                            );
                                            $update_doc = $this->commerce->update_entrer_caisse($montantcaisse['matricule_enter'],$matricule_en,$code_client,$input_doc);
                                            if($update_doc){
                                                $output .="facture totalement réglé";
                                            }else{
                                                $output .='erreur survenu, le montant en caisse du client n\a pas été mis à jour'; 
                                            }
                                        }else{
                                            $output .="erreur survenu, historique non enrégistré. contactez l'administrateur"; 
                                        }
                                    }else{
                                        $output .='erreur survenu, document non mis à jour';
                                    }
                                }else if($montantcaisse['montant_enter'] < $documentdette['dette_restante']){

                                    /*($montantcaisse['montant_enter']$documentdette['dette_regler'] - ),*/
                                    $inputdataupdate = array(
                                        'dette_regler'=> ($documentdette['dette_regler'] + $montantcaisse['montant_enter']),
                                        'dette_restante'=>($documentdette['dette_restante'] - $montantcaisse['montant_enter']),
                                        'date_modifier_doc'=>dates()
                                    );
                                    
                                    /***on modifie le document de dette pour le marquer comme réglé*/
                                    $updatedoc = $this->commerce->updatedocument($documentdette['code_document'],$matricule_en,$inputdataupdate);
                                    if($updatedoc){

                                        /**une fois le document mis a jour, on creer son historique */
                                        $inputdoc = array(
                                            'montantregler' => $montantcaisse['montant_enter'],
                                            'dateop' => dates(),
                                            'codeclient' => $documentdette['code_client_document'],
                                            'codedocument' => $documentdette['code_document'],
                                            'inititeurop' => $matricule_emp,
                                            'codeenh' => $matricule_en,
                                            'codecaisseh' => $montantcaisse['caisse_enter'], 
                                        );
                                        $resultgive = $this->commerce->historiqueratachement($inputdoc);
                                        if($resultgive){

                                            /**on met a jour le montant en caisse du client */
                                            $input_doc = array(
                                                'modifier_le_enter'=>dates(),
                                                'montant_enter'=> 0,
                                                'employe_enter'=> $matricule_emp,
                                            );
                                            $update_doc = $this->commerce->update_entrer_caisse($montantcaisse['matricule_enter'],$matricule_en,$code_client,$input_doc);
                                            if($update_doc){
                                                $output .="facture partielement réglé";
                                            }else{
                                                $output .='erreur survenu, le montant en caisse du client n\a pas été mis à jour'; 
                                            }
                                        }else{
                                            $output .="erreur survenu, historique non enrégistré. contactez l'administrateur"; 
                                        }
                                    }else{
                                        $output .='erreur survenu, document non mis à jour';
                                    }
                                }

                            }else{
                                $output .='la facture sélectionné pour ce client a déjà été soldé'; 
                            }
                        }else{
                            $output .='ce client n\'a plus d\'argent dans son compte';
                        }
                    }else{
                        $output .="le système ne trouve pas de versement pour ce client";
                    }
                }else{
                    $output .="le système ne trouve pas cette facture pour ce client";
                }
            }else{
                /**on selectionne le document en question(la dette) */
                $documentdette = $this->commerce->specificdocument($code_facture,$matricule_en);
                if(!empty($documentdette)){

                    /**on met à jour le document de 
                     * 1: on selectionne les versements, du client en fonction du code de client, de la caisse, et l'entreprise
                     * 2: on met à jour le document, 
                     * 3: on met à jour la l'entrer en caisse
                     * 4: on enregistre l'historique de ratachement
                    */

                    /**versement du client */
                    $montantcaisse = $this->commerce->entrer_caisse_cli($documentdette['code_entreprie'],$documentdette['code_client_document'],$caisse);
                    if(!empty($montantcaisse)){

                        /**si le montant en caisse est supérieur a 0, alors on procède au ratachement */
                        if($montantcaisse['montant_enter']>0){

                            /** on verifie si la dette est soldé ou pas avant de faire les opérations de solde de dette*/
                            if($documentdette['dette_restante'] != 0){

                                /**on met a jour la dette restante et la dette réglé du client dans son document de dette */
                                
                                /**montant en caisse > au montant du document? */
                                if($montantcaisse['montant_enter'] >= $documentdette['dette_restante']){
                                
                                    /*($montantcaisse['montant_enter']$documentdette['dette_regler'] - ),*/
                                    $inputdataupdate = array(
                                        'dette_regler'=> ($documentdette['dette_regler'] + $documentdette['dette_restante']),
                                        'dette_restante'=>0,
                                        'date_modifier_doc'=>dates()
                                    );
                                    
                                    /***on modifie le document de dette pour le marquer comme réglé*/
                                    $updatedoc = $this->commerce->updatedocument($documentdette['code_document'],$matricule_en,$inputdataupdate);
                                    if($updatedoc){

                                        /**une fois le document mis a jour, on creer son historique */
                                        $inputdoc = array(
                                            'montantregler' => $documentdette['dette_restante'],
                                            'dateop' => dates(),
                                            'codeclient' => $documentdette['code_client_document'],
                                            'codedocument' => $documentdette['code_document'],
                                            'inititeurop' => $matricule_emp,
                                            'codeenh' => $matricule_en,
                                            'codecaisseh' => $montantcaisse['caisse_enter'], 
                                        );
                                        $resultgive = $this->commerce->historiqueratachement($inputdoc);
                                        if($resultgive){

                                            /**on met a jour le montant en caisse du client */
                                            $input_doc = array(
                                                'modifier_le_enter'=>dates(),
                                                'montant_enter'=> ($montantcaisse['montant_enter'] - $documentdette['dette_restante']),
                                                'employe_enter'=> $matricule_emp,
                                            );
                                            $update_doc = $this->commerce->update_entrer_caisse($montantcaisse['matricule_enter'],$matricule_en,$code_client,$input_doc);
                                            if($update_doc){
                                                $output .="facture totalement réglé";
                                            }else{
                                                $output .='erreur survenu, le montant en caisse du client n\a pas été mis à jour'; 
                                            }
                                        }else{
                                            $output .="erreur survenu, historique non enrégistré. contactez l'administrateur"; 
                                        }
                                    }else{
                                        $output .='erreur survenu, document non mis à jour';
                                    }
                                }else if($montantcaisse['montant_enter'] < $documentdette['dette_restante']){

                                    /*($montantcaisse['montant_enter']$documentdette['dette_regler'] - ),*/
                                    $inputdataupdate = array(
                                        'dette_regler'=> ($documentdette['dette_regler'] + $montantcaisse['montant_enter']),
                                        'dette_restante'=>($documentdette['dette_restante'] - $montantcaisse['montant_enter']),
                                        'date_modifier_doc'=>dates()
                                    );
                                    
                                    /***on modifie le document de dette pour le marquer comme réglé*/
                                    $updatedoc = $this->commerce->updatedocument($documentdette['code_document'],$matricule_en,$inputdataupdate);
                                    if($updatedoc){

                                        /**une fois le document mis a jour, on creer son historique */
                                        $inputdoc = array(
                                            'montantregler' => $montantcaisse['montant_enter'],
                                            'dateop' => dates(),
                                            'codeclient' => $documentdette['code_client_document'],
                                            'codedocument' => $documentdette['code_document'],
                                            'inititeurop' => $matricule_emp,
                                            'codeenh' => $matricule_en,
                                            'codecaisseh' => $montantcaisse['caisse_enter'], 
                                        );
                                        $resultgive = $this->commerce->historiqueratachement($inputdoc);
                                        if($resultgive){

                                            /**on met a jour le montant en caisse du client */
                                            $input_doc = array(
                                                'modifier_le_enter'=>dates(),
                                                'montant_enter'=> 0,
                                                'employe_enter'=> $matricule_emp,
                                            );
                                            $update_doc = $this->commerce->update_entrer_caisse($montantcaisse['matricule_enter'],$matricule_en,$code_client,$input_doc);
                                            if($update_doc){
                                                $output .="facture partielement réglé";
                                            }else{
                                                $output .='erreur survenu, le montant en caisse du client n\a pas été mis à jour'; 
                                            }
                                        }else{
                                            $output .="erreur survenu, historique non enrégistré. contactez l'administrateur"; 
                                        }
                                    }else{
                                        $output .='erreur survenu, document non mis à jour';
                                    }
                                }

                            }else{
                                $output .='la facture sélectionné pour ce client a déjà été soldé'; 
                            }
                        }else{
                            $output .='ce client n\'a plus d\'argent dans son compte';
                        }
                    }else{
                        $output .="le système ne trouve pas de versement pour ce client";
                    }
                }else{
                    $output .="le système ne trouve pas cette facture pour ce client";
                }
            }
            /**reponse a la vue */
            $array = array(
				'success'   => $output
			);
        }else{
			$array = array(
				'error'   => true,
                'client_error' => form_error('client'),
                'facture_error' => form_error('facture')
			);
		}

		echo json_encode($array); 

    }
    
    /***historique de ratachement d'une facture afficher par document*/
    public function storyratachepardoc(){
        $this->logged_in();
        
        /**informations utile */
        $output = "";
		$matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];

        /** affiche les ratachement en fonction de l'entreprise et ou de l'agence
         * a partir de la caisse on test et affiches les informations pour l'agence en question ou l'entreprise
        */

        /*je selectionne la caisse en fonction de l'agence 
        
        */

        $caisse = !empty($agence)?$this->commerce->caisseagence($agence, $matricule):'';
        $caisses = !empty($caisse)?$caisse['code_caisse']:'';


        $rechercher = trim($this->input->post('recherche'));

        if(!empty($rechercher)){

            $storyratachementdocrecherche = $this->commerce->storyratachementpardocrecherche($matricule,$agence,$caisses,$rechercher);
            if(!empty($storyratachementdocrecherche)){
                $compteur = 0;
                foreach($storyratachementdocrecherche as $value){
                    $compteur+=1;
                    $output .='
                        <tr>
                            <td>'.$compteur.'  '.$value['nom_cli'].'</td>
                            <td>'.$value['libelle_caisse'].'</td>
                            <td><button type="button" class="btn btn-link detailsdoc" id="'.$value['codeclient'].'">+</button></td>
                        </tr>
                    ';  
                }
            }else{
                $output .='<tr><td colspan="5"><b class="text-danger">aucun ratachement éffectué</b></td></tr>';
            }


            $array = array(
                'success'=>$output
            );
        }else{
            /**nombre ratachement */
            $nbrratachcaisse = $this->commerce->countratachcaisse($matricule,$agence,$caisses);
            
            /** PAGINATION DEBUT */
            $config = array();
            $config["base_url"] = "#";
            $config["total_rows"] = $nbrratachcaisse;
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
            $config["cur_tag_open"] = "<li class='page-item active'><a href='#'><span class='page-link'><span class='sr-only'>(current)</span>";
            $config["cur_tag_close"] = "</span></a></li>";
            $config["num_tag_open"] = '<li class="page-item page-link">';
            $config["num_tag_close"] = "</li>";
            $config["num_links"] = 1;
            $this->pagination->initialize($config);
            
            $page = (($this->uri->segment(2) - 1) * $config["per_page"]);
            /** PAGINATION FIN */

            $storyratachementdoc = $this->commerce->storyratachementpardoc($matricule,$agence,$caisses,$config["per_page"],$page);
            if(!empty($storyratachementdoc)){
                $compteur = 0;
                foreach($storyratachementdoc as $value){
                    $compteur+=1;
                    $output .='
                        <tr>
                            <td>'.$compteur.'  '.$value['nom_cli'].'</td>
                            <td>'.$value['libelle_caisse'].'</td>
                            <td><button type="button" class="btn btn-link detailsdoc" id="'.$value['codeclient'].'">+</button></td>
                        </tr>
                    ';  
                }
            }else{
                $output .='<tr><td colspan="5"><b class="text-danger">aucun ratachement éffectué</b></td></tr>';
            }

            $array = array(
                'success'=>$output,
                'pagination_link' => $this->pagination->create_links()
            );
        }
        
        echo json_encode($array);
    }
    
    
    /*historique de ratachement d'une facture dette donné*/
    public function storyratache_ratache_facture(){
        $this->logged_in();
        
        /**informations utile */
        $output = "";
		$matricule = session('users')['matricule'];
		$codeclient = $this->input->post('code');

        /**je selectionne les ratachement du client 
         * 1: on selectionne d'une manière unique les documents de ratachement d'un client
         * 2: pour chaque document, on selectionne le processus de ratachement(par exemple, on peut ratacher plusieurs fois un document)
         * 3: je fais le total de chaque document avec son statut (soldé ou non)
        */

        /**selectionne de manière unique un document en fonction du client et de l'entreprise */
        $distinctdoc = $this->commerce->distinct_docrc_ratachement_client($matricule,$codeclient);
        if(!empty($distinctdoc)){
            foreach ($distinctdoc as $key => $values) {

                /**pour chaque document on selectionne les informations sur lui dans la table document en fonction du code de l'entreprise et de son code a lui*/
                $document = $this->commerce->specificdocument($values['codedocument'],$matricule);
                if(!empty($document)){
                    /**on determinque si c'est la dette en ttc ou en net qu'on vas afficher */
                    $detteini = empty($document['pt_net_document'])?$document['pt_ttc_document']:$document['pt_net_document'];
                    $output .= '
                        <ul class="text-black">
                            <li><b class="text-danger">Document: '.$document['code_document'].' </b></li>
                            <li>Dette Initial: '.numberformat($detteini).'</li>
                            <li>Caisse d\'enregistrement: '.strtoupper($document['libelle_caisse']).'</li>
                            <li><small>Creer le: '.dateformat($document['date_creation_doc']).'  Par: '.strtoupper($document['nom_emp']).'</small></li>
                        </ul>
                    ';

                    /**on selectionne les different ratachement du document du client
                     * en fonction de l'entreprise, du document
                     * comme informations, on a besion de: nom client, initiateur du ratachement, caisse, ayant fait le payement, montant payé, montant restant
                     */

                    $docratachecli = $this->commerce->documentratacheinformationsall($matricule, $document['code_document']);
                    if(!empty($docratachecli)){
                        $output .='<table class="table">';
                        $output .= '<tr><th>Initiateur</th><th>Caisse</th><th>Montant Réglé</th><th>Montant Restant</th><th>Date</th><th>/</th></tr>';
                        $totalregler= 0;
                        $restdette = $detteini;
                        foreach ($docratachecli as $key => $value){
                            $totalregler+=$value['montantregler'];
                            $restdette = (abs(($restdette - $value['montantregler'])));
                            $rest = ($restdette==0)?0:numberformat($restdette);
                            $output .= '
                            <tr>
                                <td>'.$value['nom_emp'].'</td>
                                <td>'.$value['libelle_caisse'].'</td>
                                <td>'.numberformat($value['montantregler']).'</td>
                                <td>'.$rest.'</td>
                                <td>'.dateformat($value['dateop']).'</td>
                                <td>
                                    <button class="btn btn-icon btn-circle btn-label-linkedin delete_hist_ratach" id="'.$value['idratache'].'">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>';
                        }
                        $totaldetterest = (abs($detteini - $totalregler)==0)?'<b class="text-success">soldé</b>':numberformat(abs($detteini - $totalregler));
                        $output .='
                            <tr><th>Total</th><th></th><th>'.numberformat($totalregler).'</th><th>'.$totaldetterest.'</th><th></th></tr>
                            </table>
                            <hr class="text-primary">
                        ';
                    }else{
                        $output = 'le système ne trouve pas les détails sur le document'; 
                    }
                }else{
                    $output = 'il existe dans cette liste des documents dont le système ne retrouve plus';
                }
            }
            
        }else{
            $output .="non trouver";
        }
        
        $array = array(
            'success'=>$output
        );

        echo json_encode($array);
    }

    /**annuler un ratachement de facture */
    public function cancel_ratache_facture(){
        $this->logged_in();

        /**informations utile */
        $output = "";
		$matricule = session('users')['matricule'];
		$coderatach = $this->input->post('code_rat');
        $matricule_emp  = session('users')['matricule_emp'];

        /**on veut annuler un ratachement, pour cela, on a besion de:
         * 1: le ratachement en question (son code + celui de l'entreprise)
         * 2: le document de sur le quel est issu le ratachement
         * 3: l'entrer en caisse du client
         */

        /**on selectionne l'historique de ratachement en fonction de son code et celui de l'entreprise */
        $specific_hist_ratachement = $this->commerce->single_hist_ratachement($coderatach,$matricule);
        if($specific_hist_ratachement){

            /** on selectionne le document concerné*/
            $documentdette = $this->commerce->specificdocument($specific_hist_ratachement['codedocument'],$matricule);
            if(!empty($documentdette)){

                /**on met a jour le document */
                $inputdataupdate = array(
                    'dette_regler'=> ($documentdette['dette_regler'] - $specific_hist_ratachement['montantregler']),
                    'dette_restante'=>($documentdette['dette_restante'] + $specific_hist_ratachement['montantregler']),
                    'date_modifier_doc'=>dates()
                );
                $updatedoc = $this->commerce->updatedocument($documentdette['code_document'],$matricule,$inputdataupdate);
                if($updatedoc){

                    /**on selectionne l'entrer en caisse de la caisse qui a réglé la facture */
                    $montantcaisse = $this->commerce->entrer_caisse_cli($matricule,$specific_hist_ratachement['codeclient'],$specific_hist_ratachement['codecaisseh']);
                    if(!empty($montantcaisse)){

                        /**on met a jour le montant en caisse du client */
                        $input_doc = array(
                            'modifier_le_enter'=>dates(),
                            'montant_enter'=> ($montantcaisse['montant_enter'] + $specific_hist_ratachement['montantregler']),
                            'employe_enter'=> $matricule_emp,
                        );
                        
                        $update_doc = $this->commerce->update_entrer_caisse($montantcaisse['matricule_enter'],$matricule,$specific_hist_ratachement['codeclient'],$input_doc);
                        if($update_doc){

                            /**on supprimer l'historique de ratachement en particulier
                             * en fonction de son code et celui de l'entreprise
                             */

                            $delehist_ratach = $this->commerce->delete_specific_historatach($specific_hist_ratachement['idratache'],$matricule);
                            if($delehist_ratach){
                                $output .= '
                                    <script>
                                        swal.fire({position:"top-right",type:"success",title:"ratachement annuler avac succès",showConfirmButton:!1,timer:5000})
                                    </script>
                                ';

                                $array = array(
                                    'success'=> $output
                                );
                            }else{
                                $array = array(
                                    'error'=> '
                                        <script>
                                            swal.fire({position:"top-right",type:"error",title:"erreur survenu, ratachement non annuler, contactez l\'administrateur",showConfirmButton:!1,timer:1500})
                                        </script>
                                    '
                                );
                            }
                            
                        }else{
                            $array = array(
                                'error'=> '
                                    <script>
                                        swal.fire({position:"top-right",type:"error",title:"erreur survenu, caisse client non mis à jour, contactez l\'administrateur",showConfirmButton:!1,timer:1500})
                                    </script>
                                '
                            );
                        }
                    }else{
                        $array = array(
                            'error'=> '
                                <script>
                                    swal.fire({position:"top-right",type:"error",title:"erreur survenu, le système ne retrouve pas l\'entrer en caisse du client, contactez l\'administrateur",showConfirmButton:!1,timer:1500})
                                </script>
                            '
                        );
                    }
                }else{
                    $array = array(
                        'error'=> '
                            <script>
                                swal.fire({position:"top-right",type:"error",title:"erreur survenu, document non mis à jour, contactez l\'administrateur",showConfirmButton:!1,timer:1500})
                            </script>
                        '
                    );
                }
            }else{
                $array = array(
                    'error'=> '
                        <script>
                            swal.fire({position:"top-right",type:"error",title:"erreur survenu, le système ne retrouve pas la facture ce ratachement",showConfirmButton:!1,timer:1500})
                        </script>
                    '
                );
            }
        }else{
            $array = array(
                'error'=> '
                    <script>
                        swal.fire({position:"top-right",type:"error",title:"erreur survenu, le système ne retrouve pas ce ratachement",showConfirmButton:!1,timer:1500})
                    </script>
                '
            );
        }
        echo json_encode($array);
    }

    /****************************gestion des ratachement fin  de facture caisse **********************/
    
    
    /***********************************gestion des ratachement de facture banaque debut********************/
    /**affiche la page de ratachement de facture caisse banque cheque*/
    public function ratache_banque(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

        /**informations utile */
		$matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];

        /**affiche la liste des clients d'une entreprise */
        $data['clients'] = $this->commerce->all_client($matricule);
        
        /**affiche la liste des caisse en fonction de l'entreprise et ou de l'agence */
        $data['caisses'] = $this->commerce->allcaisseagence($matricule,$agence);

        /***liste des agences d'une entreprise**/
        //$data['agences'] = $this->commerce->all_agence_entreprise($matricule);
        
        $this->load->view('commerce/ratache_facture_cheque',$data);

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');

    }
    
    /***********************************gestion des ratachement de facture banaque fin********************/
    
    

    /****************gestion des situation de caisse debut ************/

    /**affiche la page de situation de caisse */
    public function situation_caisse(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

        /**informations utile */
        $matricule = session('users')['matricule'];
        $agence = session('users')['matricule_ag'];
        $outputs =""; 
        $output="";
        
        /**array utile*/
        $agencearray = array();  $avantperiodearray = array();
        
        $valdocrcarray = array(); $totalrcarray = array();
        $valdocrtarray = array(); $totalsrtarray = array();
        $valdocbrarray = array(); $totalsbrarray = array();
        $valdocscarray = array(); $totalsscarray = array();
        $totalsecarray = array(); $valdocecarray = array();
        
        
        /*autres variable utile*/
        $valdocrc =""; $valdocbr ="";
        $valdocrt ="";  $valdocsc ="";
        $valdocec ="";
        
        /*si on clique sur le bouton voir*/
        if(isset($_POST['btn_view_situ_caisse'])){
            
            /**on valide le formulaire*/
            $this->form_validation->set_rules('start', 'debut', 'required',array(
                'required' => 'choisi le %s',
            ));
            $this->form_validation->set_rules('end', 'fin', 'required',array(
                'required' => 'choisi la %s',
            ));

            $this->form_validation->set_rules('caisse', 'caisse', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
                    'required' => 'choisi la %s.',
                    'regex_match' => 'caractère(s) non autorisé(s).'
                )
            );
            if($this->form_validation->run()){
                
                $date1 = $this->input->post('start');
                $date2 = $this->input->post('end');
                $code_caisse = $this->input->post('caisse');

                $debut = date("Y-m-d", strtotime($date1));
                $fin = date("Y-m-d", strtotime($date2));
                
                if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$debut) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$fin)){
                   
                    $debut = $debut.' 00:00:00';
                    $fin =  $fin.' 23:59:59';
                    
                    $abrev = array('RC','RT','BR'); 
                    //$abrev = array('SC','RC','RT','BR');
                    $totalrc=0; $totalrt=0; $totalbr=0; $totalsc=0;
                    $totalsrc=0; $totalsrt=0; $totalsbr=0; $totalssc=0;
                    $totalsec=0;
                    
                    /**on selectionne l'agance de la caisse*/
                    $agence_caisse = $this->commerce->agence_caisse($code_caisse,$matricule);
                    if(!empty($agence_caisse)){
                        
                        /**informations sur l'agence pour la caisse selectionné*/
                        $agencearray = $agence_caisse;
                        
                        /**selectionnons tous les documents de vente dans la table document pour une caisse, un type de document dans une agence
                        pour avoir le total en caisse avant la période de la situaltion
                        */
                        $docagencecaisse1 = $this->commerce->docventecaisseagence1($code_caisse,$matricule,$agencearray['matricule_ag'],$abrev,$debut);
                        if(!empty($docagencecaisse1)){
                            foreach($docagencecaisse1 as $value){
                                /**on verifi que c'est un reglement client ou pas*/
                                $rest = substr($value['code_document'], 0, 2);
                                if($rest == "RC"){
                                    /**si c'est un reglement client, on s'assure que c'est pas une dette*/
                                    if($value['dette_restante'] == 0){
                                        $totalrc += $value['pt_ttc_document'];
                                    }
                                }else if($rest == "RT"){
                                    /**si ce n'est pas un reglement client alors c'est une facture direct, reglement ticket*/
                                    $totalrt += $value['pt_ttc_document'];
                                }else if($rest == "BR"){
                                    /**si ce n'est pas un reglement client alors c'est une facture direct, reglement ticket*/
                                    $totalbr += $value['pt_ttc_document'];
                                }
                            }
                            /**prix total en caisse avant la période de situation de caisse*/
                            $avantperiodearray = ($totalrc + $totalrt + $totalbr);
                        }    
                            /*********************************************************/
                            /**selectionnons tous les documents de vente dans la table document pour une caisse, un type de document dans une agence  pour le sitiation a une période donné*/
                        $docagencecaisse = $this->commerce->docventecaisseagence2($code_caisse,$matricule,$agencearray['matricule_ag'],$abrev);
                        if(!empty($docagencecaisse)){  
                            
                            /**on selectionne les document d'une entreprise pour une caisse d'une agence sur une période donné*/
                            $docagencecaisseperiode = $this->commerce->docventecaisseagenceperiode($code_caisse,$matricule,$agencearray['matricule_ag'],$abrev, $debut,$fin);
                            if(!empty($docagencecaisseperiode)){
                                foreach($docagencecaisseperiode as $values){
                                    
                                    /**on verifi que c'est un reglement client ou pas*/
                                    $rest = substr($values['code_document'], 0, 2);
                                    if($rest == "RC"){
                                        /**si c'est un reglement client, on s'assure que c'est pas une dette*/
                                        if($values['dette_restante'] == 0){
                                            $totalsrc += $values['pt_ttc_document'];
                                            $valdocrc = array(
                                                'date' => $values['date_creation_doc'],
                                                'codedoc' => $values['code_document'],
                                                'client' => $values['nom_cli'],
                                                'nomdoc' => $values['nom_document'],
                                                'montant' => $values['pt_ttc_document']
                                            );
                                           $valdocrcarray[] = $valdocrc; 
                                        }  
                                    }else if($rest == "RT"){
                                        $totalsrt += $values['pt_ttc_document'];
                                        $valdocrt = array(
                                            'date' => $values['date_creation_doc'],
                                            'codedoc' => $values['code_document'],
                                            'client' => $values['nom_cli'],
                                            'nomdoc' => $values['nom_document'],
                                            'montant' => $values['pt_ttc_document']
                                        );
                                        $valdocrtarray[] = $valdocrt;
                                    }else if($rest == "BR"){
                                        $totalsbr += $values['pt_ttc_document'];
                                        $valdocbr = array(
                                            'date' => $values['date_creation_doc'],
                                            'codedoc' => $values['code_document'],
                                            'client' => $values['nom_cli'],
                                            'nomdoc' => $values['nom_document'],
                                            'montant' => $values['pt_ttc_document']
                                        ); 
                                        $valdocbrarray[] = $valdocbr; 
                                    }
                                }
                                
                                $totalrcarray = $totalsrc;
                                $totalsrtarray = $totalsrt;
                                $totalsbrarray = $totalsbr; 
                                
                            }
                            
                            /*on selectionne les sortie de caisse d'une agence d'une entreprise, sur une peériode donné*/
                            $sortieagencecaisseperiode = $this->commerce->sortiecaisseagenceperiode($code_caisse,$matricule,$agencearray['matricule_ag'], $debut,$fin);
                            
                            if(!empty($sortieagencecaisseperiode)){
                               foreach($sortieagencecaisseperiode as $value){
                                   $totalssc += $value['montatnt_sorti'];
                                   $valdocsc = array(
                                        'date' => $value['creer_le_sorti'],
                                        'codedoc' => $value['matricule_sorti'],
                                        'client' => $value['concerne_sorti'],
                                        'nomdoc' => $value['nom_sorti'],
                                        'motif' => $value['motif_sorti'],
                                        'montant' => $value['montatnt_sorti']
                                    ); 
                                    $valdocscarray[] = $valdocsc;
                               } 
                               $totalsscarray = $totalssc;
                            }
                            
                            
                            /*on selectionne les entrées de caisse d'une agence d'une entreprise, d'un client, sur une période donné*/
                            $entreragencecaisseperiode = $this->commerce->entrercaisseagenceperiode($code_caisse,$matricule,$agencearray['matricule_ag'], $debut,$fin);
                            //debug($entreragencecaisseperiode); die();
                            if(!empty($entreragencecaisseperiode)){
                               foreach($entreragencecaisseperiode as $value){
                                   $totalsec += $value['montant'];
                                   
                                   /**ON SELECTIONNE LE CLIENT EN FONCTION DU code du client et de l'entreprise**/
                                   $client = $this->costomer->single_client($value['client_enter']);
                                   $valdocec = array(
                                        'date' => $value['dateentrer'],
                                        'codedoc' => $value['matricule_enter'],
                                        'client' => $client['nom_cli'],
                                        'nomdoc' => $value['nom_enter'],
                                        'montant' => $value['montant']
                                    ); 
                                    $valdocecarray[] = $valdocec;
                               } 
                               $totalsecarray =  $totalsec;
                            }
                            
                            /*********************************************************/
                        }
                    }else{
                        flash('error','erreur survenu. Contactez l\'administrateur!!!'); 
                    }
                }else{
                    flash('error','date(s) pas correct');
                } 
            }
        }
        
        
        if($agence == ""){
            /**affiche la liste des caisses en fonction de l'entreprise*/
		    $outputs = $this->commerce->all_caisse_entreprise($matricule);
        }else{
            /**affiche la caisse en fonction de l'entreprise et de l'agence*/
		    $output = $this->commerce->all_caisse_agence($matricule,$agence);
        }

        /******************************************** ELEMENTS A AFFICHER A LA VUE DEBUT et dans le pdf***************************************/
        $data['caisses'] =$outputs;
        $data['caisse'] = $output;
        $data['agence'] = $agencearray;
        
        /*affiche le montant total en caisse avant la période sélectionné*/
        $data['situavanperiod'] = $avantperiodearray;
        
        
        /*affiche le table de situation avec les différents type de document et les documents de ses types de document**/
        $data['valdocrcarray'] = $valdocrcarray;            $data['totalrcarray'] = $totalrcarray;
        $data['valdocrtarray'] = $valdocrtarray;            $data['totalsrtarray'] = $totalsrtarray;
        $data['valdocbrarray'] = $valdocbrarray;            $data['totalsbrarray'] = $totalsbrarray;
        $data['valdocscarray'] = $valdocscarray;            $data['totalsscarray'] = $totalsscarray;
        $data['valdocecarray'] = $valdocecarray;            $data['totalsecarray'] = $totalsecarray;
        
        /******************************************** ELEMENTS A AFFICHER A LA VUE FIN***************************************/
        
        
        
        /****************************************IMPRIMER LA SITUATION DES CAISSE DEBUT et dans le pdf**************************************/
        /*si on clique sur le bouton imprimer*/
        if(isset($_POST['btn_situ_caisse'])){
            
            /**on valide le formulaire*/
            $this->form_validation->set_rules('start', 'debut', 'required',array(
                'required' => 'choisi le %s',
            ));
            $this->form_validation->set_rules('end', 'fin', 'required',array(
                'required' => 'choisi la %s',
            ));

            $this->form_validation->set_rules('caisse', 'caisse', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
                    'required' => 'choisi la %s.',
                    'regex_match' => 'caractère(s) non autorisé(s).'
                )
            );
            if($this->form_validation->run()){
                
                $date1 = $this->input->post('start');
                $date2 = $this->input->post('end');
                $code_caisse = $this->input->post('caisse');

                $debut = date("Y-m-d", strtotime($date1));
                $fin = date("Y-m-d", strtotime($date2));
                
                if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$debut) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$fin)){
                   
                    $debut = $debut.' 00:00:00';
                    $fin =  $fin.' 23:59:59';
                    
                    $abrev = array('RC','RT','BR'); 
                    //$abrev = array('SC','RC','RT','BR');
                    $totalrc=0; $totalrt=0; $totalbr=0; $totalsc=0;
                    $totalsrc=0; $totalsrt=0; $totalsbr=0; $totalssc=0;
                    
                    /**on selectionne l'agance de la caisse*/
                    $agence_caisse = $this->commerce->agence_caisse($code_caisse,$matricule);
                    if(!empty($agence_caisse)){
                        
                        /**informations sur l'agence pour la caisse selectionné*/
                        $agencearray = $agence_caisse;
                        
                        /**selectionnons tous les documents de vente dans la table document pour une caisse, un type de document dans une agence
                        pour avoir le total en caisse avant la période de la situaltion
                        */
                        $docagencecaisse1 = $this->commerce->docventecaisseagence1($code_caisse,$matricule,$agencearray['matricule_ag'],$abrev,$debut);
                        if(!empty($docagencecaisse1)){
                            foreach($docagencecaisse1 as $value){
                                /**on verifi que c'est un reglement client ou pas*/
                                $rest = substr($value['code_document'], 0, 2);
                                if($rest == "RC"){
                                    /**si c'est un reglement client, on s'assure que c'est pas une dette*/
                                    if($value['dette_restante'] == 0){
                                        $totalrc += $value['pt_ttc_document'];
                                    }
                                }else if($rest == "RT"){
                                    /**si ce n'est pas un reglement client alors c'est une facture direct, reglement ticket*/
                                    $totalrt += $value['pt_ttc_document'];
                                }else if($rest == "BR"){
                                    /**si ce n'est pas un reglement client alors c'est une facture direct, reglement ticket*/
                                    $totalbr += $value['pt_ttc_document'];
                                }
                            }
                            /**prix total en caisse avant la période de situation de caisse*/
                            $avantperiodearray = ($totalrc + $totalrt + $totalbr);
                        }    
                            /*********************************************************/
                            /**selectionnons tous les documents de vente dans la table document pour une caisse, un type de document dans une agence  pour le sitiation a une période donné*/
                        $docagencecaisse = $this->commerce->docventecaisseagence2($code_caisse,$matricule,$agencearray['matricule_ag'],$abrev);
                        if(!empty($docagencecaisse)){  
                            /**on selectionne les document d'une entreprise pour une caisse d'une agence sur une période donné*/
                            $docagencecaisseperiode = $this->commerce->docventecaisseagenceperiode($code_caisse,$matricule,$agencearray['matricule_ag'],$abrev, $debut,$fin);
                            if(!empty($docagencecaisseperiode)){
                                foreach($docagencecaisseperiode as $values){
                                    $rest = substr($values['code_document'], 0, 2);
                                    /**on verifi que c'est un reglement client ou pas*/
                                    $rest = substr($values['code_document'], 0, 2);
                                    if($rest == "RC"){
                                        /**si c'est un reglement client, on s'assure que c'est pas une dette*/
                                        if($values['dette_restante'] == 0){
                                            $totalsrc += $values['pt_ttc_document'];
                                            $valdocrc = array(
                                                'date' => $values['date_creation_doc'],
                                                'codedoc' => $values['code_document'],
                                                'client' => $values['nom_cli'],
                                                'nomdoc' => $values['nom_document'],
                                                'montant' => $values['pt_ttc_document']
                                            );
                                           $valdocrcarray[] = $valdocrc; 
                                        }  
                                    }else if($rest == "RT"){
                                        $totalsrt += $values['pt_ttc_document'];
                                        $valdocrt = array(
                                            'date' => $values['date_creation_doc'],
                                            'codedoc' => $values['code_document'],
                                            'client' => $values['nom_cli'],
                                            'nomdoc' => $values['nom_document'],
                                            'montant' => $values['pt_ttc_document']
                                        );
                                        $valdocrtarray[] = $valdocrt;
                                    }else if($rest == "BR"){
                                        $totalsbr += $values['pt_ttc_document'];
                                        $valdocbr = array(
                                            'date' => $values['date_creation_doc'],
                                            'codedoc' => $values['code_document'],
                                            'client' => $values['nom_cli'],
                                            'nomdoc' => $values['nom_document'],
                                            'montant' => $values['pt_ttc_document']
                                        ); 
                                        $valdocbrarray[] = $valdocbr; 
                                    }
                                }
                                
                                $totalrcarray = $totalsrc;
                                $totalsrtarray = $totalsrt;
                                $totalsbrarray = $totalsbr; 
                                
                            }
                            
                            /*on selectionne les sortie de caisse d'une agence d'une entreprise, sur une peériode donné*/
                            $sortieagencecaisseperiode = $this->commerce->sortiecaisseagenceperiode($code_caisse,$matricule,$agencearray['matricule_ag'], $debut,$fin);
                            
                            if(!empty($sortieagencecaisseperiode)){
                               foreach($sortieagencecaisseperiode as $value){
                                   $totalssc += $value['montatnt_sorti'];
                                   $valdocsc = array(
                                        'date' => $value['creer_le_sorti'],
                                        'codedoc' => $value['matricule_sorti'],
                                        'client' => $value['concerne_sorti'],
                                        'nomdoc' => $value['nom_sorti'],
                                        'motif' => $value['motif_sorti'],
                                        'montant' => $value['montatnt_sorti']
                                    ); 
                                    $valdocscarray[] = $valdocsc;
                               } 
                               $totalsscarray = $totalssc;
                            }
                            
                            /*on selectionne les entrées de caisse d'une agence d'une entreprise, d'un client, sur une période donné*/
                            $entreragencecaisseperiode = $this->commerce->entrercaisseagenceperiode($code_caisse,$matricule,$agencearray['matricule_ag'], $debut,$fin);
                            
                            if(!empty($entreragencecaisseperiode)){
                               foreach($entreragencecaisseperiode as $value){
                                   $totalsec += $value['montant'];
                                   
                                   /**ON SELECTIONNE LE CLIENT EN FONCTION DU code du client et de l'entreprise**/
                                   $client = $this->costomer->single_client($value['client_enter']);
                                   $valdocec = array(
                                        'date' => $value['dateentrer'],
                                        'codedoc' => $value['matricule_enter'],
                                        'client' => $client['nom_cli'],
                                        'nomdoc' => $value['nom_enter'],
                                        'montant' => $value['montant']
                                    ); 
                                    $valdocecarray[] = $valdocec;
                               } 
                               $totalsecarray =  $totalsec;
                            }
                            
                            /*********************************************************/
                        }
                    }else{
                        flash('error','erreur survenu. Contactez l\'administrateur!!!'); 
                    }
                }else{
                    flash('error','date(s) pas correct');
                }
                
                    $valdocrcarray;             $totalrcarray;
                    $valdocrtarray;            $totalsrtarray;
                    $valdocbrarray;            $totalsbrarray;
                    $valdocscarray;            $totalsscarray;
                    $valdocecarray;            $totalsecarray;
                /**========================================================================================*/
                // Create an instance of the class:
                $mpdf = new \Mpdf\Mpdf();
                
                $mpdf->SetHTMLHeader('
                <div style="text-align: right; font-weight: bold;">
                    <h1><b>'.strtoupper(session('users')['nom']).'</b></h1>
                </div>
                <center><h3>situatin de caisse</h3></center>
                ');
                
                $mpdf->SetHTMLFooter('
                    <table width="100%">
                        <tr>
                            <td width="66%">Imprimé le : {DATE j-m-Y à H:m:s}</td>
                            <td width="66%">situation de caisse / Lignes de documents de vente</td>
                            <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                        </tr>
                    </table>
                ');
                
                $mpdf->WriteHTML('
                    <style>
                        table {
                          font-family: arial, sans-serif;
                          border-collapse: collapse;
                          width: 100%;
                        }
                        
                        td, th {
                          border: 1px solid #dddddd;
                          text-align: left;
                          padding: 8px;
                        }
                        
                        tr:nth-child(even) {
                          background-color: #dddddd;
                        }
                    </style>
                    <br><br><br><br>
                    du '.date("d-m-Y", strtotime($date1)).' 00:00:00 au '.date("d-m-Y", strtotime($date2)).' 23:59:59
                    <hr>
                    <table border="1" style="border-collapse: collapse; width: 100%; border: 1px solid black; padding: 10px;">
                        <tr>
                            <th>Date</th>
                            <th>Numero</th>
                            <th>Client</th>
                            <th>Libelle</th>
                            <th>Débit</th>
                            <th>Credit</th>
                        </tr>
                ');
                
                $mpdf->WriteHTML('
                <tr>
                ');
                    if(!empty($agencearray)){
                        $mpdf->WriteHTML('
                            <td colspan="4">Rapport de caisse <b>'.strtoupper($agencearray['nom_ag']).'</b></td>
                        ');
                    }
                    
                $result = !empty($avantperiodearray)?$avantperiodearray:0;
                $mpdf->WriteHTML('
                    <th>'.number_format($result, 2, '.', ' ').'</th>
                    <td></td>
                </tr>
                ');
                
                if(!empty($valdocrcarray)){
                    $totalc =!empty($totalrcarray)?$totalrcarray:0;
                    $mpdf->WriteHTML('
                    <tr>
                        <td colspan="4">Type de mouvement: <b>REGLEMENT CLIENT</b> (Nb: les dettes ne sont la qu\'a titre historique)</td>
                        <th>'.$totalc.'</th>
                        <td></td>
                    </tr>
                    ');
                    foreach ($valdocrcarray as $key => $value) {
                        $mpdf->WriteHTML('
                        <tr>
                            <td>'.$value['date'].'</td>
                            <td>'.$value['codedoc'].'</td>
                            <td>'.$value['client'].'</td>
                            <td>'.$value['nomdoc'].'</td>
                            <td>'.number_format($value['montant'], 2, ',', ' ').'</td>
                            <td></td>
                        </tr>
                        ');
                    } 
                }
                
                if(!empty($valdocrtarray)){
                    $totalt = !empty($totalsrtarray)?$totalsrtarray:0;
                    $mpdf->WriteHTML('
                    <tr>
                        <td colspan="4">Type de mouvement: <b>REGLEMENT TICKET</b></td>
                        <tH>'.number_format($totalt, 2, '.', ' ').'</tH>
                        <td></td>
                    </tr>
                    ');
                    foreach ($valdocrtarray as $key => $value) {
                        $mpdf->WriteHTML('
                        <tr>
                            <td>'.$value['date'].'</td>
                            <td>'.$value['codedoc'].'</td>
                            <td>'.$value['client'].'</td>
                            <td>'.$value['nomdoc'].'</td>
                            <td>'.number_format($value['montant'], 2, '.', ' ').'</td>
                            <td></td>
                        </tr>
                        ');
                    }
                }
                
                if(!empty($valdocbrarray)){
                    $totalbr = !empty($totalsbrarray)?$totalsbrarray:0;
                    $mpdf->WriteHTML('
                    <tr>
                        <td colspan="4">Type de mouvement: <b>RETOUR ARTICLE</b></td>
                        <tH>'.number_format($totalbr, 2, '.', ' ').'</tH>
                        <td></td>
                    </tr>
                    ');
                    foreach ($valdocbrarray as $key => $value) {
                        $mpdf->WriteHTML('
                        <tr>
                            <td>'.$value['date'].'</td>
                            <td>'.$value['codedoc'].'</td>
                            <td>'.$value['client'].'</td>
                            <td>'.$value['nomdoc'].'</td>
                            <td>'.number_format($value['montant'], 2, '.', ' ').'</td>
                            <td></td>
                        </tr>
                        ');
                    }
                }
                
                if(!empty($valdocscarray)){
                    $totals = !empty($totalsscarray)?$totalsscarray:0;
                    $mpdf->WriteHTML('
                    <tr>
                        <td colspan="4">Type de mouvement: <b>SORTIE DE CAISSE</b></td>
                        <tH></tH>
                        <th>'.number_format($totals, 2, '.', ' ').'</th>
                    </tr>
                    ');
                    foreach ($valdocscarray as $key => $value) {
                        $mpdf->WriteHTML('
                        <tr>
                            <td>'.$value['date'].'</td>
                            <td>'.$value['codedoc'].'</td>
                            <td>'.$value['client'].'</td>
                            <td>'.$value['nomdoc'].' <br><b>'.$value['motif'].'</b></td>
                            <td></td>
                            <td>'.number_format($value['montant'], 2, '.', ' ').'</td>
                        </tr>
                        ');
                    }
                }
                
                if(!empty($valdocecarray)){
                    $totale = !empty($totalsecarray)?$totalsecarray:0;
                    $mpdf->WriteHTML('
                    <tr>
                        <td colspan="4">Type de mouvement: <b>ENCAISSEMENT</b></td>
                        <th>'.number_format($totale, 2, '.', ' ').'</th>
                        <tH></tH>
                    </tr>
                    ');
                    foreach ($valdocecarray as $key => $value) {
                        $mpdf->WriteHTML('
                        <tr>
                            <td>'.$value['date'].'</td>
                            <td>'.$value['codedoc'].'</td>
                            <td>'.$value['client'].'</td>
                            <td>'.$value['nomdoc'].'</b></td>
                            <td>'.number_format($value['montant'], 2, '.', ' ').'</td>
                            <td></td>
                        </tr>
                        ');
                    }
                }
                
                $mpdf->WriteHTML('
                <tr>
                    <td colspan="4">Total général des mouvements de la période</td>
                 '); 
                    $totalc1 =!empty($totalrcarray)?$totalrcarray:0;
                    $totale1 =!empty($totalsecarray)?$totalsecarray:0;
                    $totalt1 = !empty($totalsrtarray)?$totalsrtarray:0;
                    $totalbr1 = !empty($totalsbrarray)?$totalsbrarray:0;
                    $totals1 = !empty($totalsscarray)?$totalsscarray:0;
                    $result1 = !empty($avantperiodearray)?$avantperiodearray:0;
                    
                    $total1 = ($totalt1 + $totalbr1 + $totale1);
                    $total2 =($total1 - $totals1);
                    $total3 = ($result1 + $total2);
                $mpdf->WriteHTML('    
                    <th>'.number_format( $total1, 2, '.', ' ').'</th>;
                    <th>'.number_format($totals1, 2, '.', ' ').'</th>;
                </tr>
                <tr>
                    <td colspan="4">Solde de la période</td>
                    <th colspan="2">'.number_format($total2, 2, '.', ' ').'</td>>
                </tr>
                <tr>
                    <td colspan="4">Solde Total en caisse</td>
                        <th colspan="2">'.number_format($total3, 2, '.', ' ').'</th>
                </tr>
                ');
               $mpdf->WriteHTML('
                    </table>
               ');
               
               $mpdf->AddPage();
               
                $mpdf->WriteHTML('
                    <br><br><br><br>
                        Lignes de documents de vente du '.date("d-m-Y", strtotime($date1)).' 00:00:00 au '.date("d-m-Y", strtotime($date2)).' 23:59:59
                    <hr>
                    <table border="1" style="border-collapse: collapse; width: 100%; border: 1px solid black;">
                        <tr>
                            <th>Date</th>
                            <th>Num. Art.</th>
                            <th>Client</th>
                            <th>Désignation</th>
                            <th>Quantité</th>
                            <th>Prix U.</th>
                            <th>Prix Total HT</th>
                        </tr>
                ');
                
                /*****************************variables utile debut****************************/
                $totalqte = 0; $totalpht = 0;
                /*****************************variables utile fin****************************/
                if(!empty($valdocrcarray)){
                    $totalc =!empty($totalrcarray)?$totalrcarray:0;
                    $mpdf->WriteHTML('
                    <tr>
                        <td colspan="5">Type de mouvement: <b>REGLEMENT CLIENT</b> <span>(Nb: la dette n\'est pas pris en compte lors du calcul des lignes de vente)</span></td>
                        <th colspan="2">'.$totalc.' ttc</th>
                    </tr>
                    ');
                    foreach ($valdocrcarray as $key => $value){
                        
                        /*liste des articles du document*/
                        $query_art_doc = $this->commerce->art_doc_cli_en($matricule,$value['codedoc']);
                        
                        if(!empty($query_art_doc)){
                            foreach ($query_art_doc as $key => $value){
                                /**pour le document encour selectionne les informations sur le client */
                                $query_client = $this->commerce->client($matricule,$value['matricule_cli']);
                                $mpdf->WriteHTML('
                                    <tr>
                                        <th>'.$value['date_creer_art_doc'].'</th>
                                        <th>'.$value['code_article'].'</th>
                                        <td>'.strtoupper($query_client['nom_cli']).'</td>
                                        <td>'.$value['designation'].'</td>
                                        <td>'.$value['quantite'].'</td>
                                        <td> -'.$value['pu_HT'].'</td>
                                        <td> -'.$value['pt_HT'].'</td>
                                    </tr>
                               ');
                               
                               $totalqte+=$value['quantite'];
                               //$totalpht+=$value['pt_HT'];
                            }
                        }
                    }
                }
                
                if(!empty($valdocrtarray)){
                    $totalt = !empty($totalsrtarray)?$totalsrtarray:0;
                    $mpdf->WriteHTML('
                    <tr>
                        <td colspan="5">Type de mouvement: <b>REGLEMENT TICKET</b></td>
                        <th colspan="2">'.number_format($totalt, 2, '.', ' ').' ttc</th>
                    </tr>
                    ');
                    foreach ($valdocrtarray as $key => $value) {
                        /*liste des articles du document*/
                        $query_art_doc = $this->commerce->art_doc_cli_en($matricule,$value['codedoc']);
                        
                        if(!empty($query_art_doc)){
                            foreach ($query_art_doc as $key => $value){
                                /**pour le document encour selectionne les informations sur le client */
                                $query_client = $this->commerce->client($matricule,$value['matricule_cli']);
                                $mpdf->WriteHTML('
                                    <tr>
                                        <th>'.$value['date_creer_art_doc'].'</th>
                                        <th>'.$value['code_article'].'</th>
                                        <td>'.strtoupper($query_client['nom_cli']).'</td>
                                        <td>'.$value['designation'].'</td>
                                        <td>'.$value['quantite'].'</td>
                                        <td>'.$value['pu_HT'].'</td>
                                        <td>'.$value['pt_HT'].'</td>
                                    </tr>
                               ');
                               $totalqte+=$value['quantite'];
                               $totalpht+=$value['pt_HT'];
                            }
                        }
                    }
                }
                
                if(!empty($valdocbrarray)){
                    $totalbr = !empty($totalsbrarray)?$totalsbrarray:0;
                    $mpdf->WriteHTML('
                    <tr>
                        <td colspan="5">Type de mouvement: <b>RETOUR ARTICLE</b></td>
                        <th colspan="2">'.number_format($totalbr, 2, '.', ' ').' ttc</th>
                    </tr>
                    ');
                    foreach ($valdocbrarray as $key => $value) {
                        /*liste des articles du document*/
                        $query_art_doc = $this->commerce->art_doc_cli_en($matricule,$value['codedoc']);
                        
                        if(!empty($query_art_doc)){
                            foreach ($query_art_doc as $key => $value){
                                /**pour le document encour selectionne les informations sur le client */
                                $query_client = $this->commerce->client($matricule,$value['matricule_cli']);
                                $mpdf->WriteHTML('
                                    <tr>
                                        <th>'.$value['date_creer_art_doc'].'</th>
                                        <th>'.$value['code_article'].'</th>
                                        <td>'.strtoupper($query_client['nom_cli']).'</td>
                                        <td>'.$value['designation'].'</td>
                                        <td>'.$value['quantite'].'</td>
                                        <td>'.number_format($value['pu_HT'], 2, '.', ' ').'</td>
                                        <td>'.number_format($value['pt_HT'], 2, '.', ' ').'</td>
                                    </tr>
                               ');
                               $totalqte+=$value['quantite'];
                               $totalpht+=$value['pt_HT'];
                            }
                        }
                    }
                }
                
                $mpdf->WriteHTML('
                    <tr>
                        <th colspan="4">Total Général</th>
                        <th>'.$totalqte.'</th>
                        <th></th>
                        <th>'.number_format($totalpht, 2, '.', ' ').'</th>
                    </tr>
               ');
               
                $mpdf->WriteHTML('
                    </table>
               ');
                
                $mpdf->Output('situation_de_caisse_'.code(2).'_.pdf', \Mpdf\Output\Destination::INLINE);
                
                /**========================================================================================*/
            }
        }
        
        /****************************************IMPRIMER LA SITUATION DES CAISSE FIN **************************************/
       
        
        
        
		$this->load->view('commerce/situationcaisse',$data);

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }


    /**opération de situation de caisse SERA UTILISER DANS LES VERSIONS FUTURES POUR TRANSMETTRE LES DONNEES AVEC JQUERY*/
    public function operation_situationcaisse(){
        $this->logged_in();

        /**informations utile */
        $output = "";


        /**on valide le formulaire*/
        $this->form_validation->set_rules('start', 'debut', 'required',array(
            'required' => 'choisi le %s',
        ));
        $this->form_validation->set_rules('end', 'fin', 'required',array(
            'required' => 'choisi la %s',
        ));

        $this->form_validation->set_rules('caisse', 'caisse', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
                'required' => 'choisi la %s.',
                'regex_match' => 'caractère(s) non autorisé(s).'
            )
        );

        if($this->form_validation->run()){


            /**données du formulaire */
            $date1 = $this->input->post('start');
            $date2 = $this->input->post('end');


            /**on veut sortir toutes les situations de caisse en fonction de la caisse pour une période
             * 1: pour chaque type de document, chaque caisse, et période, on sélectionne les clients concernés
             * 2: pour chaque client de chaque type de document on selectionne les document de chacun d'eux
             * 3: on fait le total de chaque document ainique que sa liste d'opération
             * 4: pour les rc, rt, br on selectionne les lignes de ventes associé 
            */

            $output.= '
                <span class="text-muted text-small"> Période du '.date('d-m-Y H:i:s', strtotime($date1)).' au '.date('d-m-Y', strtotime($date1)).' 23:59:59 </span>
                <table class="table table-striped" border="1" style="border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Date</th><th>Numero</th><th>Libelle</th><th>Débit</th><th>Credit</th>
                        </tr>
                    </thead>
                    <tbody>';

            $debut = date('Y-m-d H:i:s', strtotime($date1));
            $fin = date('Y-m-d', strtotime($date1)).' 23:59:59';
            $code_caisse = $this->input->post('caisse');
            $matricule = session('users')['matricule'];
            $agence = session('users')['matricule_ag'];
            $abrev = array('RC','RT','BR','SC','EC');

            /**on selectionne l'agance de la caisse pour afficher ses mouvements*/
            $agence_caisse = $this->commerce->agence_caisse($code_caisse,$matricule);
            if(!empty($agence_caisse)){

                $output.= '
                    <tr>
                        <td colspan="3">
                            Rapport de <b>'.strtoupper($agence_caisse['libelle_caisse']).'</b>
                        </td>
                        <th></th>
                        <th></th>
                    </tr>
                ';

                /** on selectionne les clients en fonction du type de document, la caisse et la période*/
                $getclitypedoccaissperiode = $this->commerce->getcli_typedoc_caisse_periode($matricule,$code_caisse,$abrev[0],$debut,$fin);
                if(!empty($getclitypedoccaissperiode)){
                    $output.= 'ok';
                }

            }else{
                $output.= '<tr><td colspan="5"><b class="text-danger">le système ne retrouve pas l\'agence. contanctez l\'administrateur</b></td></tr>';
            }



            $output.= '</tbody>
                </table>';

            $array = array(
                'success'=> $output
            );

        }else{
            $array = array(
                'error'=> true,
                'caisse_error'=> form_error('caisse'),
                'end_error'=> form_error('end'),
                'start_error'=> form_error('start'),
            );
        }



        echo json_encode($array);
    }
    /**opération de situation de caisse */

    


    /************gestion des stituation de caisse fin *********************/


    public function situation_banque(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');

		$this->load->view('commerce/situationbanque');

        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }


    /**********gestion des dettes suivant une périonde******************debut */

    /**affiche la page des dettes */
    public function rapport_dette(){
        $this->logged_in();
        $this->load->view('parts/header');
		$this->load->view('parts/sidebar_manu');
        
        /**informations utiles */
        $mpdf = new \Mpdf\Mpdf();
        $matricule_en = session('users')['matricule'];
        $output = "";
        $abrev = array('RC');
        $totalrc=0;
        $debut = dates();
        
        $avantperiodearray = array();
        setlocale(LC_TIME, 'fr_FR'); date_default_timezone_set('Africa/Douala'); 
        
        /** =============================== AFFICHE TOTAL DETTES SUR LA PAGE DES RAPPORTS DE DETTES DEBUT ========================*/
            $totaldette = $this->commerce->docventecaisseagence($matricule_en);
            $totaldetteen = 0;
            if(!empty($totaldette)){
                foreach($totaldette as $value){
                    $rest = substr($value['code_document'], 0, 2);
                    if($rest == "RC"){
                        /**si c'est un reglement client, on s'assure que c'est une dette*/
                        if($value['dette_restante'] != 0){ 
                            $totaldetteen += $value['dette_restante'];
                        }
                    }
                }
            }
            $data['alldettes'] =  $totaldetteen;
        /** =============================== AFFICHE TOTAL DETTES SUR LA PAGE DES RAPPORTS DE DETTES FIN ========================*/

        
        /*______________________________________________________________________________*/
        if(isset($_POST['dettes_btn'])){
            $this->form_validation->set_rules('start', 'debut', 'required',array(
                'required' => 'choisi le %s',
            ));
            $this->form_validation->set_rules('end', 'fin', 'required',array(
                'required' => 'choisi la %s',
            ));
            
            if($this->form_validation->run()){
                $date1 = $this->input->post('start');
                $date2 = $this->input->post('end');

                $debut = date("Y-m-d", strtotime($date1));
                $fin = date("Y-m-d", strtotime($date2));
                
                if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$debut) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$fin)){
                        /**entete et pied de page du pdf*/
                        $mpdf->SetHTMLFooter('
                            <table width="100%">
                                <tr>
                                    <td width="66%">Imprimé le : '.utf8_encode(strftime('%A %d %B %Y, %H:%M')).'</td>
                                    <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                                </tr>
                            </table>
                        ');
                        
                        
                        $mpdf->WriteHTML('
                            <style type="text/css">
                                table{
                                    width:100%; 
                                    border-collapse: collapse;
                                }
                                td, th{
                                    border: 1px solid black;
                                }
                            </style>
                            <h1>RAPPORT DE LA DETTE</h1>
                            <p>DROIT : '.strtoupper(session('users')['nom']).' | <span style="font-size:10;">Imprimer le: '.utf8_encode(strftime('%A %d %B %Y, %H:%M')).'</span> | Periode du '.date('d-m-Y',strtotime($debut)).' au '.date('d-m-Y',strtotime($fin)).'</p>
                        ');
                        
                        /****1: liste des employés ou commerciaux d'une entreprise donné****/
                        $commerciaux =$this->users->get_employes($matricule_en);
                        foreach($commerciaux as $commer){
                            /*pour chaque commercial, on selectionne la liste de ses clients**/
                            $costomesu = $this->users->costomus($matricule_en,$commer['matricule_emp']);
                            if(!empty($costomesu)){
                                $mpdf->WriteHTML('
                                    <h6>Commercial: '.strtoupper($commer['nom_emp']).'</h6>
                                ');
                                foreach($costomesu as $costomesus){
                                    
                                    /**vas contenir la dette total de chaque client*/
                                    $totaldettecli =0;
                                    
                                    /**pour chaque client, on veux avoir la liste des document en dette*/
                                    $dettepercli = $this->commerce->dette_clientrc($costomesus['matricule_cli'],'RC',$matricule_en,$debut,$fin);
                                    if(!empty($dettepercli)){
                                        
                                        $mpdf->WriteHTML('
                                        <table>
                                            <tr>
                                                <th colspan="7">Client: '.strtoupper($costomesus['nom_cli']).'</th>
                                            </tr>
                                        ');
                                        
                                        $mpdf->WriteHTML('
                                            <tr>
                                                <td>Dette Réglé</td>
                                                <td>Dette Restante</td>
                                                <td>Delai en jour(s)</td>
                                                <td>Date</td>
                                                <td>Etat</td>
                                            </tr>
                                        ');
                                        
                                        foreach($dettepercli as $key => $val){
                                            if($val['dette_restante'] !=0){
                                                /*total dette*/
                                                $totaldettecli+=$val['dette_restante'];
                                                
                                                $datedb = date('Y-m-d',strtotime($val['date_creation_doc']));
                                                $now = date('Y-m-d');
                                                $datetime1 = new DateTime($datedb);
                                                $datetime2 = new DateTime($now);
                                                /*nombre de jour entre 2 dates*/
                                                $difference = $datetime1->diff($datetime2);
                                                
                                                $delais = $difference->d > $val['delais_reg_doc']?'Délais passé de '.($difference->d - $val['delais_reg_doc']).'jour(s)':'Expire dans '.($val['delais_reg_doc'] - $difference->d).'jour(s)';
                                                
                                                $mpdf->WriteHTML('
                                                    <tr>
                                                        <td>'.numberformat($val['dette_regler']).'</td>
                                                        <td>'.numberformat($val['dette_restante']).'</td>
                                                        <td>'.$val['delais_reg_doc'].'</td>
                                                        <td>'.date('d-m-Y',strtotime($val['date_creation_doc'])).'</td>
                                                        <td>'.$delais.'</td>
                                                    </tr>
                                                ');
                                            }
                                        }
                                        $mpdf->WriteHTML('
                                            <tr>
                                                <th colspan="7">Total Dette '.numberformat($totaldettecli).'</th>
                                            </tr>
                                        ');
                                        
                                        /*total dette de l'entreprise (somme des dettes de chaque cleint)*
                                        $totaldetteen+=$totaldettecli;
                                        
                                        $mpdf->WriteHTML('
                                            <tr>
                                                <th colspan="7">Total Dette De L\'entreprise '.$totaldetteen.'</th>
                                            </tr>
                                        ');*/
                                        $mpdf->WriteHTML('
                                            </table>
                                            <hr>
                                        '); 
                                    }
                                }
                            }
                        }
                        $mpdf->Output('RAPPORT DETTE_'.utf8_encode(strftime('%A %d %B %Y, %H:%M')).'_.pdf', \Mpdf\Output\Destination::INLINE);
                }
                
            }
        }
        /*______________________________________________________________________________*/




        /**afficher la liste des dettes d'un client particulier debut*/

        

        /**traitement des dettes d'un client en particulier */
        if(isset($_POST['btn_dette_cli'])){

            $this->form_validation->set_rules('debut', 'debut', 'required',array(
                'required' => 'choisi le %s',
            ));
            $this->form_validation->set_rules('fin', 'fin', 'required',array(
                'required' => 'choisi la %s',
            ));

            $this->form_validation->set_rules('client', 'client', 'required|regex_match[/^[a-zA-Z0-9._éèêÉÈÊàôÀÔïÏ\'\- ]+$/]',array(
                    'required' => 'choisi le %s.',
                    'regex_match' => 'caractère(s) non autorisé(s).'
                )
            );
            if($this->form_validation->run()){

                $date1 = trim($this->input->post('debut'));
                $date2 = trim($this->input->post('fin'));
                $client = trim($this->input->post('client'));

                $debut = date("Y-m-d", strtotime($date1));
                $fin = date("Y-m-d", strtotime($date2));

                if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$debut) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$fin)){
                    
                    /**1 je selectionne le commercial du client en question dans une entreprise*/
                    $commercialcli = $this->commerce->commercial_client($client); /****c'est un row**/
                    if(!empty($commercialcli)){
                        
                        /**pour le client, on veux avoir la liste des document en dette*/
                        $dettepercli = $this->commerce->dette_clientrc($client,'RC',$matricule_en,$debut,$fin); /***c'est un result*/
                        if(!empty($dettepercli)){
                            
                            $mpdf->SetHTMLHeader('
                                <img src="'.assets_dir().'/media/baniere/banier_header.jpeg" alt="">
                            ');
                            $mpdf->SetHTMLFooter('
                                <img src="'.assets_dir().'/media/baniere/banier_footer.jpeg" alt="">
                                <table width="100%">
                                    <tr>
                                        <td width="66%">Imprimé le : '.utf8_encode(strftime('%A %d %B %Y, %H:%M')).'</td>
                                        <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                                    </tr>
                                </table>
                            ');
                            
                            
                            $mpdf->WriteHTML('
                                <br/><br/><br/><br/><br/><br/><br/><br/>
                                <style type="text/css">
                                    table{
                                        width:100%; 
                                        border-collapse: collapse;
                                    }
                                    td, th{
                                        border: 1px solid black;
                                    }
                                </style>
                                <h1>RAPPORT DE LA DETTE DE: '.strtoupper($commercialcli['nom_cli']).'</h1>
                                <p><span style="font-size:10;">Imprimer le: '.utf8_encode(strftime('%A %d %B %Y, %H:%M')).'</span> | Periode du '.date('d-m-Y',strtotime($debut)).' au '.date('d-m-Y',strtotime($fin)).'</p>
                            ');
                            
                            $mpdf->WriteHTML('
                            <table>
                                <tr>
                                    <td>Dette Réglé</td>
                                    <td>Dette Restante</td>
                                    <td>Delai en jour(s)</td>
                                    <td>Date</td>
                                    <td>Etat</td>
                                </tr>
                            ');
                            
                            foreach($dettepercli as $key => $val){
                                if($val['dette_restante'] !=0){
                                    /*total dette*/
                                    $totaldettecli+=$val['dette_restante'];
                                    
                                    $datedb = date('Y-m-d',strtotime($val['date_creation_doc']));
                                    $now = date('Y-m-d');
                                    $datetime1 = new DateTime($datedb);
                                    $datetime2 = new DateTime($now);
                                    /*nombre de jour entre 2 dates*/
                                    $difference = $datetime1->diff($datetime2);
                                    
                                    $delais = $difference->d > $val['delais_reg_doc']?'Délais passé de '.($difference->d - $val['delais_reg_doc']).'jour(s)':'Expire dans '.($val['delais_reg_doc'] - $difference->d).'jour(s)';
                                    
                                    $mpdf->WriteHTML('
                                        <tr>
                                            <td>'.$val['nom_document'].'</td>
                                            <td>'.numberformat($val['dette_regler']).'</td>
                                            <td>'.numberformat($val['dette_restante']).'</td>
                                            <td>'.$val['delais_reg_doc'].'</td>
                                            <td>'.date('d-m-Y',strtotime($val['date_creation_doc'])).'</td>
                                            <td>'.dateformat($delais).'</td>
                                        </tr>
                                    ');
                                }
                            }
                            $mpdf->WriteHTML('
                                <tr>
                                    <th colspan="7">Total Dette '.numberformat($totaldettecli).'</th>
                                </tr>
                            ');
                            
                            $mpdf->WriteHTML('
                            </table>
                            ');
                            $mpdf->Output('RAPPORT DETTE_'.$commercialcli['nom_cli'].'_'.utf8_encode(strftime('%A %d %B %Y, %H:%M')).'_.pdf', \Mpdf\Output\Destination::INLINE);
                        }else{
                            flash('infos','ce client n\'a pas de dettes');
                        }
                    }else{
                        flash('error','le système ne trouve pas le commercial de ce client');
                    }
                }else{
                    flash('error','date(s) pas correct');
                }   
            }
        }
        /**afficher la liste des dettes d'un client particulier fin*/

        /**on selectionne la liste des  clients*/
        $data['client'] = $this->commerce->all_client($matricule_en);
		$this->load->view('commerce/rapportdette',$data);



        $this->load->view('parts/subfooter');
		$this->load->view('parts/footer_assets');
    }

    /**********gestion des dettes suivant une périonde******************fin */


    

    
    
/****************************************** MES FONCTIONS DEBUT******************************** */
    /**methode qui return la liste des articles d'un document debut */
	public function art_doc($document,$matricule,$tva,$ir){

        /**on recalcule les prix du document en cas de changement 
         * 1: on selectionne le document en question
         * 2: on recupére sont prix HT
         * 3: on recalcule son prix ttc et net a partir du prix ht
         * 4: on modifie le document
         * 5: on affiche a l'interface
        */
        $output = "";
        $matricule_emp = session('users')['matricule_emp'];
        /**on selectionne le document en question */
        $documentdb = $this->commerce->specificdocument($document,$matricule);
        if(!empty($documentdb)){

            /**taxes et prix ttc de l'article debut */
            $tva_art = (($tva * $documentdb['pt_ht_document'])/100);
            $ir_art = (($ir * $documentdb['pt_ht_document'])/100);
            $prix_ttc_art =  ($documentdb['pt_ht_document'] + $tva_art);
            $prix_net_art =  ($documentdb['pt_ht_document'] + ($ir_art));
            /**taxes et prix ttc de l'article fint */

            $input_doc = array(
                'date_modifier_doc'=>dates(),
                'pt_ht_document'=> $documentdb['pt_ht_document'],
                'pt_ttc_document'=> $prix_ttc_art,
                'pt_net_document'=> $prix_net_art,
                'code_employe'=> $matricule_emp,
                'code_entreprie'=> $matricule,
            );
            $update_doc = $this->commerce->updatedocument($documentdb['code_document'],$documentdb['code_entreprie'],$input_doc);
            if($update_doc){
                /**affichage debut */
                /**selectionne un document spécifique */
                $documentdb = $this->commerce->specificdocument($document,$matricule);
                if(!empty($documentdb)){
                    $output .='
                        <table class="table table-striped m-table">
                            <thead>
                                <tr>
                                    <th>Document encour de création </th>
                                    <th>
                                        <a href="'.base_url('facturer/'.$documentdb['code_document']).'" class="btn btn-icon btn-circle btn-label-linkedin">
                                            <i class="fa fa-print"></i>
                                        </a> 
                                    </th>
                                    <th>
                                        <button class="btn btn-icon btn-circle btn-label-linkedin closedoc" id="'.$documentdb['code_document'].'">
                                            <i class="fa fa-check-circle"></i>
                                        </button> 
                                    </th>
                                    <th>
                                        <input type="hidden" name="doc" id="doc" class="doc" value="'.$document.'">
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    ';
                    $output .='
                        <table class="table table-striped m-table">
                            <thead>
                                <tr>
                                    <th>Prix total HT:'.numberformat($documentdb['pt_ht_document']).'</th>
                                    <th>TVA: '.$tva.'</th>
                                    <th>IR: '.$ir.'</th>
                                    <th>Prix total TTC: '.numberformat($documentdb['pt_ttc_document']).'</th>
                                    <th>Net à payé: '.numberformat($documentdb['pt_net_document']).'</th>
                                </tr>
                            </thead>
                        </table>
                    ';

                    $output .='
                    <table class="table table-striped m-table">
                    <thead>
                        <tr>
                            <th>Code à barre</th>
                            <th>Désignation</th>
                            <th>Référence</th>
                            <th>Prix Unitaire</th>
                            <th>Quantité</th>
                            <th>Prix Total(HT)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    ';

                    $artdocument = $this->stock->articles_docucment($document,$matricule);   
                    if(!empty($artdocument)){
                        foreach ($artdocument as $key => $value){
                            $output .='
                                <tr>
                                    <td>'.$value['code_bar'].'</td>
                                    <td>'.$value['designation'].'</td>
                                    <td>'.$value['reference'].'</td>
                                    <td>'.numberformat($value['pu_HT']).'</td>
                                    <td>'.$value['quantite'].'</td>
                                    <td>'.numberformat($value['pt_HT']).'</td>
                                    <td>
                                        <button id="'.$value['matricule_art'].'" class="btn btn-icon btn-circle btn-label-linkedin delete_art_fac">
                                            <i class="fa fa-times-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            ';
                        }
                    }else{
                        $output .='il n\'y a pas encore d\'article dans ce document';
                    }  
                    $output .='
                    </tbody>
                    </table>
                    ';
                    
                }else{
                    $output .='le système ne retrouve pas le document.';
                }
                /**afficharge fin */
            }else{
                $output .='erreur survenu, certains changement on entrainé de problèmes.';
            }
        }else{
            $output .='le système ne retrouve pas le document.';
        }
        
		return $output;
	}
	/**methode qui return la liste des articles d'un document fin */


    /**methode qui calcul et affiche le prix en fonction de la taxe debut */
    public function taxe($tva,$ir,$matricule_en,$prixht,$qte){
        if(!empty($tva) && !empty($ir)){
            if($tva == "tva" && $ir == "ir"){

                /** selectionne le pourcentage de la tva dans la table taxe*/
                $pourcentagetva = $this->commerce->specifictaxe($tva,$matricule_en);

                //** selectionne le pourcentage de la ir dans la table taxe*
                $pourcentageir = $this->commerce->specifictaxe($ir,$matricule_en);

                /**calcul du prix avec tva et ir debut*/
                if(!empty($pourcentagetva) && !empty($pourcentageir)){
                    //$art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
                    $prixtotal = ($prixht * $qte);
                    $tva = (($prixtotal * $pourcentagetva['pourcentage']) / 100);
                    $ir = (($prixtotal * $pourcentageir['pourcentage']) / 100);
                    $prixttc = ($prixtotal + $tva);
                    $prixnet = ($prixtotal + ($ir));
                    $values = array(
                        'tva' => $tva,
                        'ir' => $ir,
                        'percenttva' => $pourcentagetva['pourcentage'],
                        'percentir' => $pourcentageir['pourcentage'],
                        'prixttc' => $prixttc,
                        'prixnet' => $prixnet,
                    );
                    return  $values;
                }else{
                    return false;
                }
                /**calcul du prix avec tva et ir fin*/
            }else{  
                 return false;
            }
        }else if(!empty($tva) && empty($ir)){
            /** selectionne le pourcentage de la tva dans la table taxe*/
            $pourcentagetva = $this->commerce->specifictaxe($tva,$matricule_en);
            /**calcul du prix avec tva et ir debut*/
            if(!empty($pourcentagetva)){
                //$art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
                $prixtotal = ($prixht * $qte);
                $tva = (($prixtotal * $pourcentagetva['pourcentage']) / 100);
                $prixttc = ($prixtotal + $tva);
                $values = array(
                    'tva' => $tva,
                    'ir' => 0,
                    'percenttva' => $pourcentagetva['pourcentage'],
                    'percentir' => 0,
                    'prixttc' => $prixttc,
                    'prixnet' => $prixtotal,
                );
                return  $values;
            }else{
                return false;
            }
            /**calcul du prix avec tva et ir fin*/
        }else if(empty($tva) && !empty($ir)){
            //** selectionne le pourcentage de la ir dans la table taxe*
            $pourcentageir = $this->commerce->specifictaxe($ir,$matricule_en);

            /**calcul du prix avec tva et ir debut*/
            if(!empty($pourcentageir)){
                //$art_stock_depot = $this->stock->get_art_stock($code_article,$code_depot);
                $prixtotal = ($prixht * $qte);
                $ir = (($prixtotal * $pourcentageir['pourcentage']) / 100);
                $prixnet = ($prixtotal + ($ir));
                $values = array(
                    'tva' => 0,
                    'ir' => $ir,
                    'percenttva' => 0,
                    'percentir' => $pourcentageir['pourcentage'],
                    'prixttc' => $prixtotal,
                    'prixnet' => $prixnet,
                );
                return  $values;
            }else{
                return false;
            }
            /**calcul du prix avec tva et ir fin*/
        }else if(empty($tva) && empty($ir)){
            $prixtotal = ($prixht * $qte);
            $values = array(
                'tva' => 0,
                'ir' => 0,
                'percenttva' => 0,
                'percentir' => 0,
                'prixttc' => $prixtotal,
                'prixnet' => $prixtotal,
            );
            return  $values;
        }
    }
    /**methode qui calcul et affiche le prix en fonction de la taxe fin */

    /**methode qui return le type de document debut */
    public function type_document($code_type_doc, $code_en){
        $output = "";
        /**verifier le type de document */
        $verify_typ_doc = $this->stock->type_doc_stock($code_type_doc, $code_en);
        if(!empty($verify_typ_doc)){
            $output .= $verify_typ_doc['abrev_doc'];
        }
        return $output;
    }
    /**methode qui return le type de document fin */

    /**methode qui calcule le nombre de jour entre deux dates debut  */
	public function nbr_jour($date_document){
		$datebd = date("Y-m-d", strtotime($date_document));
		$dateactu = date("Y-m-d", strtotime(date('Y-m-d')));
		$earlier = new DateTime($datebd);
		$later = new DateTime($dateactu);
		$diff = $later->diff($earlier)->format("%a");
		return $diff;
	}
	/**methode qui calcule le nombre de jour entre deux dates fin  */

    
/****************************************** MES FONCTIONS FIN ******************************************** */
    
    


    

    





}