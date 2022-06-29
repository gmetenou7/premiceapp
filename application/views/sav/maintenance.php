</div>
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">


        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        SAV
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-default btn-bold btn-upper btn-font-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="la la-download"></i> Action
                        </button>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                            <!--begin::Nav-->
                            <ul class="kt-nav">
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link" data-toggle="modal" data-target=".equipement">
                                        <i class="la la-plus"></i> 
                                        <span class="kt-nav__link-text">EQUIPEMENT</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link" data-toggle="modal" data-target=".diagnostique">
                                        <i class="la la-plus"></i> 
                                        <span class="kt-nav__link-text">DIAGNOSTIQUE</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link" data-toggle="modal" data-target=".maintenance">
                                        <i class="la la-plus"></i> 
                                        <span class="kt-nav__link-text">MAINTENANCE</span>
                                    </a>
                                </li>
                            </ul>
                            <!--end::Nav-->
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <div class="kt-portlet__body">
                <!--begin: Search Form -->
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <!-- ***************************** -->
                    <h5>Rapport des maintenances</h5>
                    <!--begin::Portlet-->
                    <div class="form-group row">
                        <form action="<?php echo base_url('rapport')?>" method="post" id="rapportform">
                            <div class="col-lg-12 col-md-9 col-sm-12">
                                <div class="input-daterange input-group" id="kt_datepicker_5">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span class="form-text text-muted text-danger">Debut</span>
                                            <input type="text" class="form-control" name="start" />
                                            <span class="form-text text-muted text-danger" id="start_error"></span>
                                        </div>
                                        <div class="col-md-5">
                                            <span class="form-text text-muted text-danger">fin</span>
                                            <input type="text" class="form-control" name="end" />
                                            <span class="form-text text-muted text-danger" id="end_error"></span>
                                        </div>
                                        <div class="col-md-2">
                                            <hr>
                                            <button type="submit" id="btn_rapport" class="btn btn-secondary btn-sm"><i class="fa fa-eye"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>  
                    <!--end::Portlet-->
                    <!--***************/******** */-->
                </div>
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__desc">Sections</div>
                            <div class="kt-section__content kt-section__content--border">
                                <button type="button" class="btn btn-primary btn-wide" id="equipementbtn">EQUIPEMENTS</button>
                                <button type="button" class="btn btn-secondary btn-wide" id="diagnostiquebtn">DIAGNOSTIQUES</button>
                                <button type="button" class="btn btn-success btn-wide" id="maintenancebtn">MAINTENANCE</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>	</div>
            <!-- end:: Content -->



                        </div>
                    </div>
                    <!--end::Portlet-->
                </div>
            </div>	
        </div>
        <!-- end:: Content -->	

        <!-- ========================= GESTION DES QUIPEMENTS DEBUT =================================-->

        <!-- liste des equipements modal debut -->
        <div class="modal" tabindex="-1" role="dialog" id="equipement_modal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Liste des équipements</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <input type="search" name="search_equip" id="search_equip" class="form-control" placeholder="saisi le nom du client ici pour trouver son équipemet...">
                            <span id="textsearchequip"></span>
                            <hr>
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Code</th>
                                        <th>Nom Equip</th>
                                        <th>Ref. Equip.</th>
                                        <th>Creer par</th>
                                        <th>/</th>
                                    </tr>
                                </thead>
                                <tbody id="list_equip">
                                </tbody>
                            </table>
                            <div align="right" id="pagination_link_equip"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- liste des equipements modal fin -->

        <!-- Large Modal enregistrer un équipement debut -->
        <div class="modal fade equipement" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informations de l'équipement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!--begin::Form-->
                     <form class="kt-form" id="form_equip">
                        <div class="modal-body">
                            <div class="kt-portlet__body">
                                <span id="message"></span>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nom de l'équipement</label>
                                            <input type="text" class="form-control" name="nom" id="nom"  placeholder="nom de l'appareil">
                                            <span class="form-text text-danger" id="nom_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Numéro de série</label>
                                            <input type="text" class="form-control" id="num_serie" name="num_serie" placeholder="son numéro de série">
                                            <span class="form-text text-danger" id="num_serie_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>la référence</label>
                                            <input type="text" class="form-control" id="ref_equip" name="ref_equip" placeholder="sa référence">
                                            <span class="form-text text-danger" id="ref_equip_error"> </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">client</label>
                                            <select class="form-control kt_select2" id="kt_select2_1" name="client" style="width:370px">
                                                
                                            </select>
                                            <span class="form-text text-danger" id="client_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-last">
                                    <label for="exampleTextarea">Description de l'quipement</label>
                                    <textarea class="form-control" id="desciption" name="desciption" rows="3" placeholder="décrit ici l'état de l'équipement..."></textarea>
                                    <span class="form-text text-danger" id="desciption_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                            <button type="submit" id="btn_new_equip"  class="btn btn-outline-primary">Enrégistrer</button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!-- Large Modal enregistrer un équipement fin -->

        <!----modal pour voir les détails sur un équipement donnée donné debut-->
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="details_modal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails Equipement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id='sectionAimprimer'>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 img-responsive">
                                <!--begin::Portlet-->
                                <img src="<?php echo assets_dir();?>media/baniere/banier_header.jpeg" width="1200" height="200" alt="" >
                                <!--end::Portlet-->
                            </div>
                        </div>
                        <hr>
                        <h3>TICKET D'ENREGISTREMENT DE L'EQUIPEMENT</h3>
                        <hr>
                        <span id="details">
                            
                        </span>
                        <hr>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <!--begin::Portlet-->
                                <img src="<?php echo assets_dir();?>media/baniere/banier_footer.jpeg" width="1200" height="200" alt="">
                                <!--end::Portlet-->
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" onClick="imprimer('sectionAimprimer')"><i class="fa fa-print"></i></button>
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <!----modal pour voir les détails sur un équipement donnée donné fin-->

        <!-- Large Modal modifier un équipement debut -->
        <div class="modal fade" tabindex="-1" role="dialog" id="edit_modal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier l'équipement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <span id="message1"></span>
                    <!--begin::Form-->
                        <form class="kt-form" id="form_equip_edit">
                            <input type="hidden" id="matricule" name="matricule">
                            <div class="modal-body">
                                <div class="kt-portlet__body">
                                    <span id="message"></span>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nom de l'équipement</label>
                                                <input type="text" class="form-control" name="nom1" id="nom1"  placeholder="nom de l'appareil">
                                                <span class="form-text text-danger" id="nom1_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Numéro de série</label>
                                                <input type="text" class="form-control" id="num_serie1" name="num_serie1" placeholder="son numéro de série">
                                                <span class="form-text text-danger" id="num_serie1_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>la référence</label>
                                                <input type="text" class="form-control" id="ref_equip1" name="ref_equip1" placeholder="sa référence">
                                                <span class="form-text text-danger" id="ref_equip1_error"> </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleSelect1">client</label>
                                                <span id="clients"></span>
                                                <select class="form-control kt-select2" id="kt_select2_2" name="client1" style="width:370px">

                                                </select>
                                                <span class="form-text text-danger" id="client1_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-last">
                                        <label for="exampleTextarea">Description de l'quipement</label>
                                        <textarea class="form-control" id="desciption1" name="desciption1" rows="3" placeholder="décrit ici l'état de l'équipement..."></textarea>
                                        <span class="form-text text-danger" id="desciption1_error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                                <button type="submit" id="btn_update_equip"  class="btn btn-outline-primary">Modifier</button>
                            </div>
                        </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!-- Large Modal modifier un équipement fin -->

        <!-- ========================= GESTION DES QUIPEMENTS FIN =================================-->

        <!-- ========================= GESTION DES DIAGNOSTIQUES DEBUT =================================-->

        <!----nouveau diagnostique debut-->
        <div class="modal fade diagnostique" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Diagnostiquer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form" id="new_dignostique_form">
                        <div class="modal-body">
                            <span id="diagnost_message"></span>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleSelect1">Choisi l'équipement concerné(code, Nom)</label>
                                        <select class="form-control" id="kt_select2_3" name="equipement" style="width:370px">
                                            
                                        </select>
                                        <span class="form-text text-danger" id="equipement_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Prix total du diagnostique (FCFA)</label>
                                        <input type="text" class="form-control" name="prix" placeholder="prix du diagnostique">
                                        <span class="form-text text-danger" id="prix_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-last">
                                        <label for="exampleTextarea">Quel est ton Diagnostique</label>
                                        <textarea class="form-control" id="diagnostique" name="diagnostique" rows="6" placeholder="écrire ici ce qui en ressort de la prospection de l'équipement..."></textarea>
                                        <span class="form-text text-danger" id="diagnostique_error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                            <button type="submit" id="btn_save_diagnostique" class="btn btn-outline-primary">Enregistrer</button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!----nouveau diagnostique fin-->

        <!----liste diagnostique debut-->  
        <div class="modal" id="alldiagnostiquemodal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Liste des diagnostiques</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="search" name="searchdiag" id="searchdiag" class="form-control" placeholder="saisi le nom du client ici pour trouver son diagnostique">
                        <span id="textdiagnostique"></span>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Code.</th>
                                        <th scope="col">Equipement</th>
                                        <th scope="col">Prix (FCFA)</th>
                                        <th scope="col">Par</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="list_diagnost">
                                    
                                </tbody>
                            </table>
                            <div align="right" id="pagination_link_diag"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <!----liste diagnostique fin-->                                   

        <!----modal pour voir les détails sur un diagnostique donnée donné debut-->
        <div class="modal" tabindex="-1" role="dialog" aria-hidden="true" id="details_diagnostique_modal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails Diagnostique</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id='imprimerdiagnostiq'>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <!--begin::Portlet-->
                                <img src="<?php echo assets_dir();?>media/baniere/banier_header.jpeg"  width="1200" height="200" alt="">
                                <!--end::Portlet-->
                            </div>
                        </div>
                        <hr>
                        <h3>TICKET D'ENREGISTREMENT DE DIAGNOSTIQUE</h3>
                        <hr>
                        <span id="details_diagnot">
                            
                        </span>
                        <hr>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <!--begin::Portlet-->
                                <img src="<?php echo assets_dir();?>media/baniere/banier_footer.jpeg"  width="1200" height="200" alt="">
                                <!--end::Portlet-->
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" onClick="imprimer('imprimerdiagnostiq')"><i class="fa fa-print"></i></button>
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <!----modal pour voir les détails sur un diagnostique donnée donné fin-->

        <!----modifier diagnostique debut-->
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="edit_diagnostique_modal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier Un Diagnostique</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form" id="update_dignostique_form">
                        <div class="modal-body">
                            <span id="diagnost_message1"></span>
                            <input type="hidden" id="matricule_dias" name="matricule_dias">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleSelect1">équipement concerné(code, Nom)</label>
                                        <select class="form-control" id="kt_select2_8" name="equipement1" style="width:370px">
                                            
                                        </select>
                                        <span class="form-text text-danger" id="equipement1_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Prix total du diagnostique (FCFA)</label>
                                        <input type="text" class="form-control" name="prix1" id="prix1" placeholder="prix du diagnostique">
                                        <span class="form-text text-danger" id="prix1_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-last">
                                        <label for="exampleTextarea">Quel est ton Diagnostique</label>
                                        <textarea class="form-control" id="diagnostique1" name="diagnostique1" rows="6" placeholder="écrire ici ce qui en ressort de la prospection de l'équipement..."></textarea>
                                        <span class="form-text text-danger" id="diagnostique1_error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                            <button type="submit" id="btn_update_diagnostique" class="btn btn-outline-primary">Modifier</button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!----modifier diagnostique debut-->

       <!-- ========================= GESTION DES DIAGNOSTIQUES FIN =================================-->

       <!-- ========================= GESTION DES MAINTENANCE DEBUT =================================-->

        <!----nouvelle maintenance debut-->
        <div class="modal fade maintenance" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Maintenance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form" id="new_maintenance_form">
                        <div class="modal-body">
                            <span id="maint_message"></span>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleSelect1">Choisi le Diagnostique</label>
                                        <select class="form-control kt-select2" id="kt_select2_9" name="code_diag" style="width:370px">
                                            
                                        </select>
                                        <span class="form-text text-danger" id="code_diag_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelect1">Statut Actuel</label>
                                        <select class="form-control" id="statut" name="statut">
                                            
                                        </select>
                                        <span class="form-text text-danger" id="statut_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Prix total du service (FCFA)</label>
                                        <input type="text" class="form-control" name="prix_maint" id="prix_maint" placeholder="prix du diagnostique">
                                        <span class="form-text text-danger" id="prix_maint_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-last">
                                        <label for="exampleTextarea">Description</label>
                                        <textarea class="form-control" id="description_maint" name="description_maint" rows="11" placeholder="écrire ici ce qui en ressort de la prospection de l'équipement..."></textarea>
                                        <span class="form-text text-danger" id="description_maint_error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                            <button type="submit" id="btn_save_maintenance" class="btn btn-outline-primary">Enregistrer</button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!----nouvelle maintenance debut-->

        <!---- liste des maintenances debut-->
        <div class="modal" id="listmaintenancemodal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Liste des maintenances</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <input type="search" name="searchmaintenance" id="searchmaintenance" class="form-control" placeholder="saisi le nom du client ici pour voir ses maintenance">
                            <span id="textmaint"></span>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Code.</th>
                                        <th scope="col">Nom équi.</th>
                                        <th scope="col">Client</th>
                                        <th scope="col">Montant</th>
                                        <th scope="col">Statut</th>
                                        <th scope="col">Maintenancier</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="list_maintenance">
                                    
                                </tbody>
                            </table>
                            <div align="right" id="pagination_link_maint"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <!---- liste des maintenances fin-->

        <!----detail d'une maintenance debut-->
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="details_maintenance_modal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails Maintenance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id='imprimermaintenance'>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <!--begin::Portlet-->
                                <img src="<?php echo assets_dir();?>media/baniere/banier_header.jpeg" width="1200" height="200" alt="">
                                <!--end::Portlet-->
                            </div>
                        </div>
                        <hr>
                        <h3>TICKET D'ENREGISTREMENT DE MAINTENANCE</h3>
                        <hr>
                        <span id="details_maintenance">
                            
                        </span>
                        <hr>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <!--begin::Portlet-->
                                <img src="<?php echo assets_dir();?>media/baniere/banier_footer.jpeg" width="1200" height="200" alt="">
                                <!--end::Portlet-->
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" onClick="imprimer('imprimermaintenance')"><i class="fa fa-print"></i></button>
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <!----detail d'une maintenance fin-->

        <!----modifier une maintenance debut-->
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="edit_maintenance_modal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier Une Maintenance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!--begin::Form-->
                    <form class="kt-form" id="update_maintenance_form">
                        <div class="modal-body">
                            <span id="maint_message1"></span>
                            <input type="hidden" name="matricule_maint" id="matricule_maint">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleSelect1">Code Diagnostique</label>
                                        <select class="form-control kt_select2_2" id="kt_select2_11" name="code_diag1" style="width:370px">
                                            
                                        </select>
                                        <span class="form-text text-danger" id="code_diag1_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelect1">Statut Actuel</label>
                                        <select class="form-control" id="statut1" name="statut1">
                                            
                                        </select>
                                        <span class="form-text text-danger" id="statut1_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Prix total du service (FCFA)</label>
                                        <input type="text" class="form-control" name="prix_maint1" id="prix_maint1" placeholder="prix du diagnostique">
                                        <span class="form-text text-danger" id="prix_maint1_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-last">
                                        <label for="exampleTextarea">Description</label>
                                        <textarea class="form-control" id="description_maint1" name="description_maint1" rows="11" placeholder="écrire ici ce qui en ressort de la prospection de l'équipement..."></textarea>
                                        <span class="form-text text-danger" id="description_maint1_error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                            <button type="submit" id="btn_update_maintenance" class="btn btn-outline-primary">Modifier</button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!----modifier une maintenance fin-->

        <!----gestion des maintenances fin-->


        <!-- Large Modal rapport de maintenance debut -->
        <div class="modal" id="modalrapport">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rapport de maintenance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="imprimerrapport">
                        <center><h3><u>RAPPORT MAINTENANCE <?php echo strtoupper(session('users')['nom']); ?></u></h3></center>
                        <hr>
                        <div class="table-responsive">
                            <span class="text-muted">Rapport Maintenance</span>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Code.</th>
                                        <th scope="col">Nom équi.</th>
                                        <th scope="col">Client</th>
                                        <th scope="col">Montant</th>
                                        <th scope="col">Statut</th>
                                        <th scope="col">Maintenancier</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="rapport_maintenance">
                                    
                                </tbody>
                            </table>
                            <hr>
                            <span class="text-muted">Rapport Diagnostique</span>
                            <table class="table table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Code.</th>
                                        <th scope="col">Equipement</th>
                                        <th scope="col">Client</th>
                                        <th scope="col">Prix (FCFA)</th>
                                        <th scope="col">Par</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="rapport_diagnost">
                                    
                                </tbody>
                            </table>
                            <hr>
                            <h4>TOTAL: <span id="totalrapport"></span></h4>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-outline-primary" onClick="imprimer('imprimerrapport')"><i class="fa fa-print"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Large Modal rapport de maintenance fin -->



    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->

<script>
    $(document).ready(function(){

        /*** ======================= GESTION DES EQUIPEMENTS DEBUT =================================* */

        /**insertion  dans la base des données d'un équipement*/
        $('#form_equip').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('new_equip'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_new_equip').attr('disabled', 'disabled');
                },
                success: function (data) {
                    if(data.error){
                        $('#message').html('');
                        if(data.nom_error != ''){
                            $('#nom_error').html(data.nom_error);
                        }else{
                            $('#nom_error').html('');
                        }

                        if(data.num_serie_error != ''){
                            $('#num_serie_error').html(data.num_serie_error);
                        }else{
                            $('#num_serie_error').html('');
                        }

                        if(data.ref_equip_error!= ''){
                            $('#ref_equip_error').html(data.ref_equip_error);
                        }else{
                            $('#ref_equip_error').html('');
                        }

                        if(data.client_error!= ''){
                            $('#client_error').html(data.client_error);
                        }else{
                            $('#client_error').html('');
                        }

                        if(data.desciption_error!= ''){
                            $('#desciption_error').html(data.desciption_error);
                        }else{
                            $('#desciption_error').html('');
                        }

                    }
                    if(data.success){
                        $('#nom_error').html('');
                        $('#num_serie_error').html('');
                        $('#ref_equip_error').html('');
                        $('#prix_error').html('');
                        $('#statut_error').html('');
                        $('#client_error').html('');
                        $('#desciption_error').html('');
                        client();
                        list_equipement(1);
                        $('#message').html(data.success);
                        $('#form_equip')[0].reset();
                    }
                   $('#btn_new_equip').attr('disabled', false);
                }
            });
        });

        /**affiche la liste des quipements */
        $("#equipementbtn").click(function (e) { 
            e.preventDefault();
            list_equipement(1);
            $("#equipement_modal").modal('show');
        });
        /**liste des équipements enrégistrer*/
        list_equipement(1);
        function list_equipement(page){
            $.ajax({
                method: "GET",
                url:"<?php echo base_url('get_all_equip/');?>"+page,
                dataType: "json",
                success: function(data){
                    $("#list_equip").html(data.list_equip);
                    $("#pagination_link_equip").html(data.links);
                }
            }); 
        }
        /**gerer le click sur la pagination d'un equipement*/
        $(document).on("click", ".pagination li a", function(event){
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            list_equipement(page);
        });
        /**afficher les détails d'un equipement */
        $(document).on('click', '.view', function(){
            var equip_code = $(this).attr("id");
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('detail_equip'); ?>",
                data: {equip_code:equip_code},
                dataType: "JSON",
                success:function(data) {
                    $("#details").html(data);
                    $("#details_modal").modal("show");
                }
            });
        });

        /**afficher les informations dans le formulaire d'édition pour modification d'un equipement*/
        $(document).on('click', '.edit', function(){
           var equip_mat = $(this).attr("id");
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('edit_equip'); ?>",
                data: {equip_mat:equip_mat},
                dataType: "JSON",
                success:function(data) {
                    $("#matricule").val(data.code_equip);
                    $("#nom1").val(data.nom_equip);
                    $("#num_serie1").val(data.numero_serie_equip);
                    $("#ref_equip1").val(data.reference_equip);
                    $("#desciption1").val(data.description_equip);
                    $("#kt_select2_2").val(data.code_client);
                    $("#edit_modal").modal("show");
                }
            });
            
        });

        /**modifier un équipement*/
        $('#form_equip_edit').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('update_equip'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_update_equip').attr('disabled', 'disabled');
                },
                success: function (data) {
                    if(data.error){
                        $('#message1').html('');
                        if(data.nom1_error != ''){
                            $('#nom1_error').html(data.nom1_error);
                        }else{
                            $('#nom1_error').html('');
                        }

                        if(data.num_serie1_error != ''){
                            $('#num_serie1_error').html(data.num_serie1_error);
                        }else{
                            $('#num_serie1_error').html('');
                        }

                        if(data.ref_equip1_error!= ''){
                            $('#ref_equip1_error').html(data.ref_equip1_error);
                        }else{
                            $('#ref_equip1_error').html('');
                        }

                        if(data.client1_error!= ''){
                            $('#client1_error').html(data.client1_error);
                        }else{
                            $('#client1_error').html('');
                        }

                        if(data.desciption1_error!= ''){
                            $('#desciption1_error').html(data.desciption1_error);
                        }else{
                            $('#desciption1_error').html('');
                        }
                    }
                    if(data.success){
                        $('#nom1_error').html('');
                        $('#num_serie1_error').html('');
                        $('#ref_equip1_error').html('');
                        $('#client1_error').html('');
                        $('#desciption1_error').html('');
                        
                        $('#message1').html(data.success);
                        list_equipement(1);
                    }
                   $('#btn_update_equip').attr('disabled', false);
                }
            });
        });

        /**rechercher un equipement */
        $("#search_equip").keyup(function (e) { 
            var val = $("#search_equip").val();
            $("#textsearchequip").html(val);
            if(val != ""){
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('get_all_equip'); ?>",
                    data: {val:val},
                    dataType: "json",
                    success: function(data) {
                        if(data.result){
                            $("#list_equip").html(data.result);
                        }
                    }
                });
            }else{
                list_equipement(1);
            }
        });

        /*** ======================= GESTION DES EQUIPEMENTS FIN =================================* */
        
        /*** ==========================LISTE DES CLIENTS DEBUT ================================== */
        client();
        function client(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('lclient'); ?>",
                dataType: "JSON",
                success: function(data){
                    $('#kt_select2_1').append(data);
                    $('#kt_select2_2').append(data);
                }
            });
        }
        /*** ==========================LISTE DES CLIENTS FIN ================================== */


        /*** ==========================GESTION DES DIAGNISTIQUES DEBUT ================================== */

        /**liste des equipement d'une entreprise donnée pour les diagnostiques */
        get_equip();
        function get_equip(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('get_equip'); ?>",
                dataType: "JSON",
                success: function (data){
                    $("#kt_select2_3").append(data);
                    $("#kt_select2_8").append(data);
                }
            });
        }

        /**insertion du diagnostique dans la base des données*/
        $('#new_dignostique_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('new_diagnost'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_save_diagnostique').attr('disabled', 'disabled');
                },
                success: function (data) {
                    if(data.error){
                        $('#diagnost_message').html('');
                        if(data.equipement_error != ''){
                            $('#equipement_error').html(data.equipement_error);
                        }else{
                            $('#equipement_error').html('');
                        }

                        if(data.prix_error != ''){
                            $('#prix_error').html(data.prix_error);
                        }else{
                            $('#prix_error').html('');
                        }

                        if(data.diagnostique_error!= ''){
                            $('#diagnostique_error').html(data.diagnostique_error);
                        }else{
                            $('#diagnostique_error').html('');
                        }
                    }
                    if(data.success){
                        $('#equipement_error').html('');
                        $('#prix_error').html('');
                        $('#diagnostique_error').html('');
                        $('#new_dignostique_form')[0].reset();
                        $('#diagnost_message').html(data.success);
                        list_diagnostique(1);
                    }
                   $('#btn_save_diagnostique').attr('disabled', false);
                }
            });
        });

        /**affiche les diagnostiques */
        $("#diagnostiquebtn").click(function (e) { 
            e.preventDefault();
            list_diagnostique(1);
            $("#alldiagnostiquemodal").modal('show');
        });

        /**liste des diagnostiques */
        list_diagnostique(1);
        function list_diagnostique(page){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('get_all_diagnost/');?>"+page,
                dataType: "json",
                success: function(data){
                    $("#list_diagnost").html(data.list_diag);
                    $("#pagination_link_diag").html(data.links);
                }
            }); 
        }
        /**gerer le click sur la pagination d'un diagnostique*/
        $(document).on("click", ".pagination li a", function(event){
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            list_diagnostique(page);
        });

        /**afficher les détails d'un diagnostique */
        $(document).on('click', '.view_diagnost', function(){
            var diagnostique_code = $(this).attr("id");
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('detail_diagnostiq'); ?>",
                data: {diagnostique_code:diagnostique_code},
                dataType: "JSON",
                success:function(data) {
                    $("#details_diagnot").html(data);
                }
            });
            $("#details_diagnostique_modal").modal("show");
        });

        /**afficher les informations du diagnostique dans le formulaire d'édition pour modification */
        $(document).on('click', '.edit_diagnost', function(){
            var diagnostique_code = $(this).attr("id");
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('edit_diagnost'); ?>",
                data: {diagnostique_code:diagnostique_code},
                dataType: "JSON",
                success:function(data) {
                    $("#matricule_dias").val(data.matricule_diagnostique);
                    $("#prix1").val(data.prix_diagnostique);
                    $("#diagnostique1").val(data.diagnostique);
                    $("#kt_select2_8").val(data.code_equipement); 
                    $("#edit_diagnostique_modal").modal("show");
                }
            });
            
        });


        /**modifier un diagnostique*/
        $('#update_dignostique_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('update_diagnostique'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_update_diagnostique').attr('disabled', 'disabled');
                },
                success: function (data) {
                    if(data.error){
                        $('#diagnost_message1').html('');
                        if(data.equipement1_error != ''){
                            $('#equipement1_error').html(data.equipement1_error);
                        }else{
                            $('#equipement1_error').html('');
                        }

                        if(data.prix1_error != ''){
                            $('#prix1_error').html(data.prix1_error);
                        }else{
                            $('#prix1_error').html('');
                        }

                        if(data.diagnostique1_error!= ''){
                            $('#diagnostique1_error').html(data.diagnostique1_error);
                        }else{
                            $('#diagnostique1_error').html('');
                        }
                    }
                    if(data.success){
                        $('#equipement1_error').html('');
                        $('#prix1_error').html('');
                        $('#diagnostique1_error').html('');
                        $('#diagnost_message1').html(data.success);
                        list_diagnostique(1);
                    }
                   $('#btn_update_diagnostique').attr('disabled', false);
                }
            });
        });

        /**chercher un diagnostique avec le nom du client*/
        $("#searchdiag").keyup(function (e) { 
            e.preventDefault();
            var val = $("#searchdiag").val();
            $("#textdiagnostique").html(val);
            if(val !=""){
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('get_all_diagnost'); ?>",
                    data: {val:val},
                    dataType: "json",
                    success: function (data) {
                        $("#list_diagnost").html(data.list_diag);
                    }
                });
            }else{
                list_diagnostique(1);
            }
        });

        /*** ==========================GESTION DES DIAGNISTIQUES FIN ================================== */


        /*** ==========================GESTION DES MAINTENANCE DEBUT ================================== */

        /**selectionne tout les code de diagnostique debut */
        code_diagnostique();
        function code_diagnostique(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('code_diag'); ?>",
                dataType: "JSON",
                success: function(data){
                    $('#kt_select2_9').append(data);
                    $('#kt_select2_11').append(data);
                }
            });
        }
        /**selectionne tout les code de diagnostique debut */

        /**statut de l'équipement */
        statut();
        function statut(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('estatut'); ?>",
                dataType: "JSON",
                success: function(data){
                    $('#statut').html(data);
                    $('#statut1').html(data);
                }
            });
        }
        /**statut de l'équipement fin*/

        /*new maintanances debut */
        $('#new_maintenance_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('new_maint'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_save_maintenance').attr('disabled', 'disabled');
                },
                success: function (data) {
                    if(data.error){
                        $('#maint_message').html('');
                        if(data.code_diag_error != ''){
                            $('#code_diag_error').html(data.code_diag_error);
                        }else{
                            $('#code_diag_error').html('');
                        }

                        if(data.statut_error != ''){
                            $('#statut_error').html(data.statut_error);
                        }else{
                            $('#statut_error').html('');
                        }

                        if(data.prix_maint_error!= ''){
                            $('#prix_maint_error').html(data.prix_maint_error);
                        }else{
                            $('#prix_maint_error').html('');
                        }

                        if(data.description_maint_error!= ''){
                            $('#description_maint_error').html(data.description_maint_error);
                        }else{
                            $('#description_maint_error').html('');
                        }
                    }
                    if(data.success){
                        $('#code_diag_error').html('');
                        $('#statut_error').html('');
                        $('#prix_maint_error').html('');
                        $('#description_maint_error').html('');
                        $('#new_maintenance_form')[0].reset();
                        $('#maint_message').html(data.success);
                        all_maintenance(1);
                        statut();
                        code_diagnostique();
                        get_date_maintenance();
                    }
                   $('#btn_save_maintenance').attr('disabled', false);
                }
            });
        });
        /*new maintanances fin */

        /**liste des maintenances */
        $("#maintenancebtn").click(function (e) { 
            e.preventDefault();
            all_maintenance(1);
            $("#listmaintenancemodal").modal('show');
        });

        /**afficher tous les maintenances debut */
        all_maintenance(1);
        function all_maintenance(page){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('get_all_maintenance/');?>"+page,
                dataType: "JSON",
                success: function (data) {
                    $("#list_maintenance").html(data.list_maintenance);
                    $("#pagination_link_maint").html(data.links);
                }
            });
        }
        /**gerer le click sur la pagination d'une maintenance*/
        $(document).on("click", ".pagination li a", function(event){
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            all_maintenance(page);
        });
        /**afficher tous les maintenances fin */

        /**detail d'une maintenance debut */
        $(document).on('click', '.view_maint', function(){
            var maint_code = $(this).attr("id");
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('detail_maint'); ?>",
                data: {maint_code:maint_code},
                dataType: "JSON",
                success:function(data) {
                    $("#details_maintenance").html(data);
                    $("#details_maintenance_modal").modal("show");
                }
            });
        });
        /**detail d'une maintenance fin */

        /**afficher les details d'une maintenance dans un formulaire pour modification debut */
        $(document).on('click', '.edit_maint', function(){
            var maint_code = $(this).attr("id");
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('edit_maint'); ?>",
                data: {maint_code:maint_code},
                dataType: "JSON",
                success:function(data) {
                    $("#matricule_maint").val(data.code_reparation);
                    $("#code_diag1").val(data.matricule_diagnostique);
                    $("#statut1").val(data.statut_reparation);
                    $("#prix_maint1").val(data.prix_reparation);
                    $("#description_maint1").val(data.description_reparation);
                    $("#edit_maintenance_modal").modal("show");
                }
            });
        });
        /**afficher les details d'une maintenance dans un formulaire pour modification fin */


        /**modifier une maintenance debut */
        $('#update_maintenance_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('update_maint'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_update_maintenance').attr('disabled', 'disabled');
                },
                success: function (data) {
                    if(data.error){
                        $('#maint_message1').html('');
                        if(data.code_diag1_error != ''){
                            $('#code_diag1_error').html(data.code_diag1_error);
                        }else{
                            $('#code_diag1_error').html('');
                        }

                        if(data.statut1_error != ''){
                            $('#statut1_error').html(data.statut1_error);
                        }else{
                            $('#statut1_error').html('');
                        }

                        if(data.prix_maint1_error!= ''){
                            $('#prix_maint1_error').html(data.prix_maint1_error);
                        }else{
                            $('#prix_maint1_error').html('');
                        }

                        if(data.description_maint1_error!= ''){
                            $('#description_maint1_error').html(data.description_maint1_error);
                        }else{
                            $('#description_maint1_error').html('');
                        }
                    }
                    if(data.success){
                        $('#code_diag1_error').html('');
                        $('#statut1_error').html('');
                        $('#prix_maint1_error').html('');
                        $('#description_maint1_error').html('');
                        $('#maint_message1').html(data.success);
                        all_maintenance(1);
                    }
                   $('#btn_update_maintenance').attr('disabled', false);
                }
            });
        });
        /**modifier une maintenance fin */

        /**chercher une maintenace */
        $("#searchmaintenance").keyup(function (e) { 
           var val = $("#searchmaintenance").val(); 
           $("#textmaint").html(val);
           if(val != ""){
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('get_all_maintenance');?>",
                    data: {val:val},
                    dataType: "json",
                    success: function (data) {
                        $("#list_maintenance").html(data.list_maintenance); 
                    }
                });
           }else{
            all_maintenance(1); 
           }
        });
        /*** ==========================GESTION DES MAINTENANCE FIN ================================== */

        /** ===================================RAPPORT DE MAINTENANCE DEBUT ========================== */
        $('#rapportform').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: $(this).attr("method"),
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_rapport').attr('disabled', 'disabled');
                },
                success: function (data) {
                    if(data.error){
                        if(data.start_error != ''){
                            $('#start_error').html(data.start_error);
                        }else{
                            $('#start_error').html('');
                        }
                        if(data.end_error != ''){
                            $('#end_error').html(data.end_error);
                        }else{
                            $('#end_error').html('');
                        }
                    }
                    if(data){
                        $('#start_error').html('');
                        $('#end_error').html('');
                        $("#rapport_maintenance").html(data.maintenance);
                        $("#rapport_diagnost").html(data.diagnostique);
                        $("#totalrapport").html(data.total);
                        $("#modalrapport").modal('show');
                    }
                   $('#btn_rapport').attr('disabled', false);
                }
            });
        });
        /** ===================================RAPPORT DE MAINTENANCE DEBUT ========================== */

    });
</script>

<script>
/**fonction pour imprimer */
    function imprimer(divName){
        var printContents = document.getElementById(divName).innerHTML;    
        var originalContents = document.body.innerHTML;      
        document.body.innerHTML = printContents;     
        window.print();     
        document.body.innerHTML = originalContents;
        window.location.reload();
   }
</script>
