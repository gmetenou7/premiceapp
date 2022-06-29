<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:marchandise
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Welcome';

/**page de connexion */
$route['login'] = 'Users/User';
/**creer le compte d'un groupe ou d'une entreprise */
$route['register'] = 'Users/User/register_user';
/**page pour envoyer l'email devant servir a: modifier le mot de passe d'un groupe */
$route['password'] = 'Users/User/update_password';
$route['send_mail'] = 'Users/User/send_mail';

/**modifier le mot de passe d'un groupe ou d'une entreprise */
$route['pass/(:any)'] = 'Users/User/update_pass_en/$1';


/** page d'un utilisateur étant connecté entreprise ou groupe*/
$route['new_user'] = 'Users/User/inscription_user';
$route['useragence'] = 'Users/User/agence_user';
$route['userservice'] = 'Users/User/user_services';
$route['employe'] = 'Users/User/new_employe';
$route['all_employe'] = 'Users/User/get_all_employe';
$route['desactive_employe'] = 'Users/User/desactive';
$route['active_employe'] = 'Users/User/active';
$route['detail_employe'] = 'Users/User/details';
$route['edit_employe'] = 'Users/User/edit';
$route['update_employe'] = 'Users/User/update';
$route['mute_employe'] = 'Users/User/mute_employe';
$route['filteruser'] = 'Users/User/filter_employe';



$route['passProvi'] = 'Users/User/pass_provi';
$route['update_employe_password'] = 'Users/User/update_pass_employe';
//$route['get_service_agence'] = 'Users/User/getserviceagence';



$route['showusels'] = 'Users/User/showusersels';
$route['costomu'] = 'Users/User/costomuser';
$route['profil/(:any)'] = 'Users/User/profil_user/$1';
$route['mutate'] = 'Users/User/user_mutate';


/**client */
$route['costomers'] = 'Costomers/Costomers';
$route['new_client'] = 'Costomers/Costomers/new_costomers';
$route['get_all_client'] = 'Costomers/Costomers/list_costomers';
$route['get_all_client/(:num)'] = 'Costomers/Costomers/list_costomers/$1';
$route['get_single_client'] = 'Costomers/Costomers/single_costomers';
$route['getsingleclient'] = 'Costomers/Costomers/singlecostomers';
$route['update_client'] = 'Costomers/Costomers/update_costomers';
$route['employeattrib'] = 'Costomers/Costomers/employereattribut';
$route['optreatribution'] = 'Costomers/Costomers/reattribuer_client';
$route['hreattrib'] = 'Costomers/Costomers/historique_reattribuer_client';




/**gestion des messages debut*/

/**inbox */
$route['inbox'] = 'Inbox/Inbox';

/**config message */
$route['message'] = 'Inbox/Inbox/msg_config';
$route['nbr_msg'] = 'Inbox/Inbox/nbr_msg_config';
$route['nbrmsg_list'] = 'Inbox/Inbox/all_config_msg';
$route['delete_nbrmsg'] = 'Inbox/Inbox/delete_config_msg';

/**gestion des messages fin */
$route['sendmessage'] = 'Inbox/Inbox/send_groupe_message';
$route['sendmsg'] = 'Inbox/Inbox/all_send_message';
$route['sendmsg/(:any)'] = 'Inbox/Inbox/all_send_message/$1';
$route['infosmsg'] = 'Inbox/Inbox/infos_send_message';







/**stock */

    /**famille des produits */
$route['family'] = 'Stock/Stock';
$route['save_famille'] = 'Stock/Stock/save_famille_prod';
$route['all_famille'] = 'Stock/Stock/all_famille_prod';
$route['detail_famille'] = 'Stock/Stock/details_famille_prod';
$route['edit_famille'] = 'Stock/Stock/edit_famille_prod';
$route['update_famille'] = 'Stock/Stock/update_famille_prod';




/**gestion des articles debut*/
$route['article'] = 'Stock/Stock/new_article';
$route['new_article'] = 'Stock/Stock/save_article';
$route['get_all_article'] = 'Stock/Stock/all_article';
$route['get_all_article/(:num)'] = 'Stock/Stock/all_article/$1';
$route['get_details_article'] = 'Stock/Stock/details_article';
$route['edit_details_article'] = 'Stock/Stock/edit_article';
$route['update_article'] = 'Stock/Stock/updatearticle';
$route['get_tri_famil_article'] = 'Stock/Stock/tri_famil_article';
//$route['get_tri_four_article'] = 'Stock/Stock/tri_four_article';
$route['tri_article'] = 'Stock/Stock/filtre_article';
$route['percentmargefam'] = 'Stock/Stock/percentmargeperfam';





$route['lfournisseur'] = 'Stock/Stock/list_fournisseur';
$route['lfamille'] = 'Stock/Stock/list_famille_produit';

/**gestion des articles fin */

/**gestion des entré en stock debut */

$route['stock'] = 'Stock/Stock/enter_stock_article';
$route['stock/(:any)'] = 'Stock/Stock/enter_stock_article/$1';

/**gestion des entré en stock fin */

/**gestion des  depots debut*/
$route['depot'] = 'Stock/Stock/depot';
$route['new_depot'] = 'Stock/Stock/save_depot'; 
$route['all_depot'] = 'Stock/Stock/all_depot'; 
$route['all_depot/(:num)'] = 'Stock/Stock/all_depot/$1'; 
$route['get_detail_d'] = 'Stock/Stock/detail_depot'; 
$route['get_edit_depot'] = 'Stock/Stock/edit_depot';
$route['update_depot'] = 'Stock/Stock/update_depot'; 

/**gestion des  depots fin*/



/**gestion du sav */
$route['maintenance'] = 'Sav/Sav';
$route['maintenance/(:num)'] = 'Sav/Sav';
$route['lclient'] = 'Sav/Sav/list_costomers';

/**equipement */
$route['estatut'] = 'Sav/Sav/statut_equip';
$route['new_equip'] = 'Sav/Sav/save_equip';
$route['get_all_equip'] = 'Sav/Sav/all_equip';
$route['get_all_equip/(:num)'] = 'Sav/Sav/all_equip/$1';
$route['detail_equip'] = 'Sav/Sav/single_equipement';
$route['edit_equip'] = 'Sav/Sav/editequip';
$route['update_equip'] = 'Sav/Sav/updateequip';



/**diagnostique */
$route['new_diagnost'] = 'Sav/Sav/save_diagnost';
$route['get_equip'] = 'Sav/Sav/diagnostique_equip';
$route['get_all_diagnost'] = 'Sav/Sav/all_diagnostique';
$route['get_all_diagnost/(:num)'] = 'Sav/Sav/all_diagnostique/$1';
$route['detail_diagnostiq'] = 'Sav/Sav/single_diagnostique';
$route['edit_diagnost'] = 'Sav/Sav/edit_diagnostique';
$route['update_diagnostique'] = 'Sav/Sav/updatediagnostique';

/**maintenance */
$route['new_maint'] = 'Sav/Sav/save_maintenance';
$route['code_diag'] = 'Sav/Sav/code_diagnostique';
$route['get_all_maintenance'] = 'Sav/Sav/all_maintenance';
$route['get_all_maintenance/(:num)'] = 'Sav/Sav/all_maintenance/$1';
$route['detail_maint'] = 'Sav/Sav/single_maintenance';
$route['edit_maint'] = 'Sav/Sav/edit_maintenance';
$route['update_maint'] = 'Sav/Sav/update_maintenance';

/**rapport de maintenance */
$route['rapport'] = 'Sav/Sav/rapport_maintenance';


/**fournisseur */
$route['provider'] = 'Fournisseur/Fournisseur';
$route['new_fournisseur'] = 'Fournisseur/Fournisseur/newfournisseur';
$route['get_all_fournisseur'] = 'Fournisseur/Fournisseur/all_fournisseur';
$route['get_details'] = 'Fournisseur/Fournisseur/detail_fournisseur';
$route['get_detail'] = 'Fournisseur/Fournisseur/details_fournisseur';
$route['update_fournisseur'] = 'Fournisseur/Fournisseur/updatefournisseur';





/**agence */
$route['new_agence'] = 'Agence/Agence';
$route['get_pays'] = 'Agence/Agence/pays';
$route['agence_new'] = 'Agence/Agence/agence';
$route['get_agence'] = 'Agence/Agence/getagence';
$route['get_single_agence'] = 'Agence/Agence/singleeagence';
$route['get_single_agence1'] = 'Agence/Agence/edit';
$route['update_agence'] = 'Agence/Agence/update';





/**service */
$route['new_service'] = 'Service/Service';
$route['agence'] = 'Service/Service/agence';
$route['service_new'] = 'Service/Service/service';
$route['get_service'] = 'Service/Service/get_services';
$route['get_single_service'] = 'Service/Service/singleservices';
$route['get_single_service1'] = 'Service/Service/edit';
$route['service_update'] = 'Service/Service/update';



/**Home */
$route['home'] = 'Home/Home';
$route['caissevente'] = 'Home/Home/caisse_vente';
$route['caperiode'] = 'Home/Home/ca_periode_vente';
$route['artplusvendu'] = 'Home/Home/article_plus_vendu';







/**se deconnecter */
$route['logout'] = 'Users/User/logout';



/**anniversaire d'un client et d'un employé */
$route['anniv'] = 'Notifications/Notification/anniversaire';
$route['count_notification'] = 'Notifications/Notification/countnotification';
$route['get_hbd_notification'] = 'Notifications/Notification/hbd_notification';
$route['detailnotif'] = 'Notifications/Notification/detail_notification';
$route['notifications'] = 'Notifications/Notification/notification';



/**gestion des entré en stock debut */
$route['insert_stock'] = 'Stock/Stock/new_stock';
$route['show_form_stock'] = 'Stock/Stock/form_stock';
$route['op_form_stock'] = 'Stock/Stock/operation_stock';
$route['show_docs_typdoc'] = 'Stock/Stock/docs_typdoc';

$route['artsdoc'] = 'Stock/Stock/articles_docucment';
$route['print'] = 'Stock/Stock/print_arts_docuc';
$route['print/(:any)'] = 'Stock/Stock/print_arts_docuc/$1';

$route['delete_art'] = 'Stock/Stock/delete_art_document';

/**gestion des entré en stock fin */





    /**gestion des type de document debut */
    $route['document'] = 'Document/Document';
    $route['get_doc'] = 'Document/Document/all_doc';
    $route['doc_new'] = 'Document/Document/new_doc';
    $route['get_single_doc'] = 'Document/Document/edit_doc';
    $route['doc_update'] = 'Document/Document/update_doc';
    
    
/**gestion des type de document fin */


/**gestion des mouvement entrer ert sorti debut */
$route['mouvement'] = 'Stock/Stock/mouvement_article';
$route['mouvement/(:any)'] = 'Stock/Stock/mouvement_article/$1';
$route['show_form_mvt'] = 'Stock/Stock/form_mouvement';
$route['op_mvt_stock'] = 'Stock/Stock/operation_mouvement';


/**gestion des mouvement entrer ert sorti fin*/


/**gestion des transfert et reception de stock debut */
$route['transfert'] = 'Stock/Stock/transfert_stock';
$route['transfert/(:any)'] = 'Stock/Stock/transfert_stock/$1';
$route['show_form_transfert'] = 'Stock/Stock/form_transfert';
$route['op_transfert_stock'] = 'Stock/Stock/operation_transfert';
$route['transfert_attente'] = 'Stock/Stock/attente_transfert';

$route['transfert_recu'] = 'Stock/Stock/recu_transfert';
$route['annultransfert'] = 'Stock/Stock/cancel_transfert';



$route['art_doc_view'] = 'Stock/Stock/articles_docucment';
$route['confirmtransfert'] = 'Stock/Stock/reception_transfert';




/**gestion des transfert et reception de stock fin */


/**gestion des inventaires debut */
$route['inventaire'] = 'Stock/Stock/inventaire_stock';
$route['inventaire/(:any)'] = 'Stock/Stock/inventaire_stock/$1';
$route['inventaire_doc'] = 'Stock/Stock/inventaire_document';
$route['op_inventaire'] = 'Stock/Stock/op_inventaire_stock';
$route['plus_moins'] = 'Stock/Stock/qte_plus_moins';


/**gestion des inventaires fin */


/**affiche les marhandises pour une recherche debut */
$route['marchandise'] = 'Marchandise/Marchandise';
$route['all_articl'] = 'Marchandise/Marchandise/all_article';
$route['all_articl/(:num)'] = 'Marchandise/Marchandise/all_article/$1';
$route['get_details_marchandise'] = 'Marchandise/Marchandise/details_marchandise';

$route['rceherche_articl'] = 'Marchandise/Marchandise/recherche_article';

$route['rceherche_articl/(:num)'] = 'Marchandise/Marchandise/recherche_article/$1';
$route['alstock'] = 'Marchandise/Marchandise/alert_stock';
$route['printalertstock/(:any)'] = 'Marchandise/Marchandise/print_alert_stock/$1';

$route['inside'] = 'Marchandise/Marchandise/inside';
$route['inside_art'] = 'Marchandise/Marchandise/get_inside';


$route['artfamillestock'] = 'Marchandise/Marchandise/triartfamillestock';

$route['filter_article'] = 'Marchandise/Marchandise/filterarticle';



/**affiche les marhandises pour une recherche fin */


/**creation et gestion des factures debut */
$route['facture'] = 'Commerce/Commerce/facturer';
$route['getdocs'] = 'Commerce/Commerce/docs_vente_nonclo';

$route['proformat'] = 'Commerce/Commerce/proformat';
$route['bonlivraison'] = 'Commerce/Commerce/bon_livraison';
$route['bonretour'] = 'Commerce/Commerce/bon_retour';
$route['sorticaisse'] = 'Commerce/Commerce/sorti_caisse';
$route['entrecaisse'] = 'Commerce/Commerce/entre_caisse';
$route['situationcaisse'] = 'Commerce/Commerce/situation_caisse';
$route['situationbanque'] = 'Commerce/Commerce/situation_banque';
$route['rapportdette'] = 'Commerce/Commerce/rapport_dette';

$route['op_situationcaisse'] = 'Commerce/Commerce/operation_situationcaisse';


/**creation et gestion des factures fin */

/**gestion des caisses debut */
$route['caisse'] = 'Caisse/Caisse';
$route['new_caisse'] = 'Caisse/Caisse/newcaisse';
$route['all_caisse'] = 'Caisse/Caisse/allcaisse';
$route['get_single_caisse'] = 'Caisse/Caisse/singlecaisse';
$route['update_caisse'] = 'Caisse/Caisse/updatecaisse';
/**gestion des caisses fin */


/**gestion des taxes debut */
$route['taxe'] = 'Taxe/Taxe';
$route['new_taxe'] = 'Taxe/Taxe/newtaxe';
$route['all_taxe'] = 'Taxe/Taxe/alltaxe';
$route['get_single_taxe'] = 'Taxe/Taxe/getsingletaxe';
$route['update_taxe'] = 'Taxe/Taxe/updatetaxe';
/**gestion des taxes fin */



/**gestion des factures debut */
$route['form_facture'] = 'Commerce/Commerce/form_facturation';
$route['total_ht'] = 'Commerce/Commerce/totalhorstaxe';
$route['op_facturetiket'] = 'Commerce/Commerce/operationfacturetiket';
$route['delete_art_doc'] = 'Commerce/Commerce/deletearticledocument';
$route['facturer/(:any)'] = 'Commerce/Commerce/facture_pdf/$1';
$route['all_rt_cli'] = 'Commerce/Commerce/all_reglementclient';
$route['all_rt_cli/(:num)'] = 'Commerce/Commerce/all_reglementclient/$1';
$route['all_art_rt_cli'] = 'Commerce/Commerce/all_article_reglement_client';
$route['clo_doc'] = 'Commerce/Commerce/cloturer_document';
$route['edit_docs'] = 'Commerce/Commerce/show_edit_docs';

$route['rfacture'] = 'Commerce/Commerce/reglement_client';
$route['all_rc_cli'] = 'Commerce/Commerce/all_reglementrcclient';
$route['all_rc_cli/(:any)'] = 'Commerce/Commerce/all_reglementrcclient/$1';
$route['all_art_rc_cli'] = 'Commerce/Commerce/all_article_rc_reglement_client';
$route['form_facture_rc'] = 'Commerce/Commerce/form_facturation_rc';
$route['op_facturerc'] = 'Commerce/Commerce/operationfacturerc';


$route['allproformat'] = 'Commerce/Commerce/all_proformat';
$route['allproformat/(:any)'] = 'Commerce/Commerce/all_proformat/$1';
$route['form_facture_pf'] = 'Commerce/Commerce/form_facturation_proformat';
$route['op_facturepf'] = 'Commerce/Commerce/operationfacturepf';

$route['allbordereaul'] = 'Commerce/Commerce/all_bordereaul';
$route['allbordereaul/(:any)'] = 'Commerce/Commerce/all_bordereaul/$1';
$route['form_b_l'] = 'Commerce/Commerce/form_bordereau_livraison';
$route['operationfacture_bl'] = 'Commerce/Commerce/operation_bordereau_livraison';

$route['allbonretour'] = 'Commerce/Commerce/all_bon_retour';
$route['allbonretour/(:any)'] = 'Commerce/Commerce/all_bon_retour/$1';
$route['all_art_br'] = 'Commerce/Commerce/all_article_bon_retour';
$route['all_art_formbr'] = 'Commerce/Commerce/all_article_form_rt';
$route['opbr'] = 'Commerce/Commerce/operation_retour_article';




$route['form_sorti_caisse'] = 'Commerce/Commerce/op_sorti_caisse';
$route['all_sorti'] = 'Commerce/Commerce/all_sorti_caisse';
$route['all_sorti/(:any)'] = 'Commerce/Commerce/all_sorti_caisse/$1';
//$route['all_sorti'] = 'Commerce/Commerce/all_sorti_caisse';
$route['delete_s_c'] = 'Commerce/Commerce/delete_sorti_caisse';

$route['form_entrer_caisse'] = 'Commerce/Commerce/op_entrer_caisse';
$route['all_entrer'] = 'Commerce/Commerce/all_entrer_caisse';
$route['all_entrer/(:any)'] = 'Commerce/Commerce/all_entrer_caisse/$1';
$route['detail_enter'] = 'Commerce/Commerce/detail_entrer_caisse';
$route['delete_e_c'] = 'Commerce/Commerce/delete_entrer_caisse';



$route['ratache'] = 'Commerce/Commerce/ratache_fac';
$route['ratacheb'] = 'Commerce/Commerce/ratache_banque';
$route['reglementcli_cli'] = 'Commerce/Commerce/reglement_client_cli';
$route['op_ratache_fac'] = 'Commerce/Commerce/op_ratache_facture';
$route['storyratache'] = 'Commerce/Commerce/storyratache_ratache_facture';
$route['storyratachedoc'] = 'Commerce/Commerce/storyratachepardoc';
$route['storyratachedoc/(:any)'] = 'Commerce/Commerce/storyratachepardoc/$1';
$route['delete_ratach_doc'] = 'Commerce/Commerce/cancel_ratache_facture';

$route['op_ratache_cheq'] = 'Commerce/Commerce/op_ratache_cheque';



/**gestion des factures fin */


/**comptabilité*/
$route['selsemp'] = 'Comptabilite/Comptabilite';
$route['comptashowusels'] = 'Comptabilite/Comptabilite/comptashowusels';
$route['custompurchase'] = 'Comptabilite/Comptabilite/purchasecustom';
$route['comptacustompurchase'] = 'Comptabilite/Comptabilite/custompurchasecompta';

$route['alertinvoice'] = 'Comptabilite/Comptabilite/alert_facture';


/**points pour un prix (nombre de point par achat)*/
$route['tpoint'] = 'Points/Points';
$route['point_new'] = 'Points/Points/newpoint';
$route['get_pointprix'] = 'Points/Points/getconfig_pointprix';
$route['delete_point'] = 'Points/Points/delete_pointprix';


/***gestion des annonces*/
$route['annonce'] = 'Annonces/Annonce';
$route['newmsgsave'] = 'Annonces/Annonce/new_annonce';
$route['getmsgsave'] = 'Annonces/Annonce/get_annonce';
$route['delannonce'] = 'Annonces/Annonce/delete_annonce';
$route['getmsgsaveh'] = 'Annonces/Annonce/get_annonceh';


/**suivre un article dans la gestion de stock */
$route['suivi_article'] = 'Suivi_article/Suivi_article';
$route['suivi_article_vente'] = 'Suivi_article/Suivi_article/viewpagevente';
$route['suiviarticle'] = 'Suivi_article/Suivi_article/story_suiviarticle_stock';
$route['suiviarticlev'] = 'Suivi_article/Suivi_article/story_suiviarticle_vente';


/**ajoute l'heur qui vas servir a cloturer les factures */
$route['add_timeclo_fac'] = 'Taxe/Taxe/heur_clo_fact';
$route['show_timeclo_fac'] = 'Taxe/Taxe/show_clo_fact';
$route['clo_fact_time'] = 'Taxe/Taxe/clo_fact_in_time';




$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

