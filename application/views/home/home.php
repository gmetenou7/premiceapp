</div>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
    
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
            
            <!-- begin:: Content -->
            <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                <?php $this->load->view('parts/message');?>
                    <!--begin::Dashboard 6-->
                    
                    <nav class="navbar navbar-light bg-light">
                      <div class="container-fluid">
                            <button type="button" class="btn btn-primary">
                              Annonce(s) <span class="badge bg-secondary">4</span>
                            </button>
                            <h6 class="text-danger btninfoalert">AssureZ-vous d'avoir parcouru toutes les alerts? disponiblent dans les onglets suivants: COMPTABILITE, STOCK, COMMERCE </h6>
                            <div class="alert alert-success" role="alert">
							<div class="alert-text">
							  	<h4 class="alert-heading">Nouveauté !</h4>
							  	<p>
                                  Bienvenu sur la version 2.0 de PREMICEAPP en travaillant, vous verrez des nouveautés par rapport au système existant précédemment
                                </p>
							  	<hr>
							  	<p class="mb-0"><small>Uploader à 2h08</small></p>
							</div>
						</div>
                      </div>
                    </nav>
                <!--begin::Row--> 
                
                    <div class="row">

                        <?php if (session('users')['nom_ag'] == "" && session('users')['nom_serv'] == ""){ ?>
                            <div class="col-lg-6 col-xl-6 order-lg-1 order-xl-1">
                                <!--begin::Portlet-->
                                <div class="kt-portlet kt-portlet--height-fluid-half">
                                    <div class="kt-portlet__head kt-portlet__head--noborder">
                                        <div class="kt-portlet__head-label">
                                            <h3 class="kt-portlet__head-title">CHIFFRE D'AFFAIRE DU JOURS -> <?= strtoupper(session('users')['nom']);?></h3>
                                        </div>
                                        <div class="kt-portlet__head-toolbar">
                                            <div class="kt-portlet__head-toolbar-wrapper">
                                                <div class="dropdown dropdown-inline">
                                                    <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="flaticon-more-1"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="kt-nav">
                                                            <li class="kt-nav__section kt-nav__section--first">
                                                                <span class="kt-nav__section-text">Détails</span>
                                                            </li>
                                                            <li class="kt-nav__item">
                                                                <a href="#" class="kt-nav__link shocamodal">
                                                                    <i class="kt-nav__link-icon la la-print"></i>
                                                                    <span class="kt-nav__link-text">Tout voir</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>		
                                        </div>
                                    </div>
                                    <div class="kt-portlet__body kt-portlet__body--fluid">
                                        <div class="kt-widget-19">
                                            <div class="kt-widget-19__title">
                                                <?php $caen = !empty($caen)?$caen:0; ?>
                                                <div class="kt-widget-19__label"><small>XAF </small><?= number_format($caen,2,",","."); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Portlet-->	  
                            </div>
                        
                            
                            <div class="col-lg-6 col-xl-6 order-lg-1 order-xl-1">
                                <!--begin::Portlet-->
                                <div class="kt-portlet kt-portlet--height-fluid-half">
                                    <div class="kt-portlet__head kt-portlet__head--noborder">
                                        <div class="kt-portlet__head-label">
                                            <h3 class="kt-portlet__head-title">VERSEMENT PAR CHEQUE</h3>
                                        </div>
                                        <div class="kt-portlet__head-toolbar">
                                            <div class="kt-portlet__head-toolbar-wrapper">
                                                <div class="dropdown dropdown-inline">
                                                    <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="flaticon-more-1"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="kt-nav">
                                                            <li class="kt-nav__section kt-nav__section--first">
                                                                <span class="kt-nav__section-text">Détails</span>
                                                            </li>
                                                            <li class="kt-nav__item">
                                                                <a href="#" class="kt-nav__link shocamodal">
                                                                    <i class="kt-nav__link-icon la la-print"></i>
                                                                    <span class="kt-nav__link-text">Tout voir</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>		
                                        </div>
                                    </div>
                                    <div class="kt-portlet__body kt-portlet__body--fluid">
                                        <div class="kt-widget-19">
                                            <div class="kt-widget-19__title">
                                                <?php $cabanque1 = !empty($cabanque)?$cabanque:0; ?>
                                                <div class="kt-widget-19__label"><small>XAF </small><?= number_format($cabanque1,2,",","."); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Portlet-->	  
                            </div>
                        <?php } ?>
                        <?php if ((session('users')['nom_ag'] != "" || session('users')['nom_ag'] == "") && session('users')['nom_serv'] == ""){ ?>
                            <?php if(!empty($caag)){ ?>
                                <?php foreach ($caag as $key => $value) { ?>
                                    <div class="col-lg-6 col-xl-6 order-lg-1 order-xl-1">
                                        <!--begin::Portlet-->
                                        <div class="kt-portlet kt-portlet--height-fluid-half">
                                            <div class="kt-portlet__head kt-portlet__head--noborder">
                                                <div class="kt-portlet__head-label">
                                                    <h3 class="kt-portlet__head-title">CHIFFRE D'AFFAIRE DU JOUR <?= strtoupper($value['agence']);?></h3>
                                                </div>
                                                <div class="kt-portlet__head-toolbar">
                                                    <div class="kt-portlet__head-toolbar-wrapper">
                                                        <div class="dropdown dropdown-inline">
                                                            <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="flaticon-more-1"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="kt-nav">
                                                                    <li class="kt-nav__section kt-nav__section--first">
                                                                        <span class="kt-nav__section-text">Détails</span>
                                                                    </li>
                                                                    <li class="kt-nav__item">
                                                                        <a href="#" class="kt-nav__link shocamodal">
                                                                            <i class="kt-nav__link-icon la la-print"></i>
                                                                            <span class="kt-nav__link-text">Tout voir</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>		
                                                </div>
                                            </div>
                                            <div class="kt-portlet__body kt-portlet__body--fluid">
                                                <div class="kt-widget-19">
                                                    <div class="kt-widget-19__title">
                                                        <div class="kt-widget-19__label"><small id="caag">XAF</small> <?= number_format($value['totalca'],2,",",".");?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Portlet-->
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                            
                            
                        <div class="col-lg-6 col-xl-6 order-lg-2 order-xl-1">
                                    <!--begin::Portlet-->
                            <div class="kt-portlet kt-portlet--height-fluid kt-widget ">
                                <div class="kt-portlet__body">
                                    <div id="kt-widget-slider-13-1" class="kt-slider carousel slide" data-ride="carousel" data-interval="8000">
                                        <div class="kt-slider__head">
                                            <div class="kt-slider__label">ANONCE(S)</div>
                                            <div class="kt-slider__nav">
                                                <a class="kt-slider__nav-prev carousel-control-prev" href="#kt-widget-slider-13-1" role="button" data-slide="prev">
                                                    <i class="fa fa-angle-left"></i>
                                                </a>
                                                <a class="kt-slider__nav-next carousel-control-next" href="#kt-widget-slider-13-1" role="button" data-slide="next">
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="carousel-inner" id="annonce">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Portlet-->	
                        </div>
                        <div class="col-lg-6 col-xl-6 order-lg-2 order-xl-1">
                                    <!--begin::Portlet-->
                            <div class="kt-portlet kt-portlet--height-fluid">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">PRODUITS LES PLUS VENDU SUR UNE PERIODE</h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                        <div class="kt-portlet__head-toolbar-wrapper">
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-widget-1">
                                        <form method="post" action="<?php echo base_url('artplusvendu'); ?>" id="formartplusvendu">
                                            <div class="mb-3 col-lg-12 col-md-9 col-sm-12">
                                                liste des produits les plus vendu par famille, sur une periode ainsi que le chiffre d'affaire et le bénéfice moyen engendré en fonction de la quantité
                                                <hr class="bg-primary">
                                                <span class="form-text">Agence</span>
                                                <div class="input-daterange input-group" id="kt_datepicker_5">
                                                    <select class="form-control" name="plusvenduag" id="plusvenduag">
                                                        <?php
                                                            echo '<option></option>';
                                                            if(!empty($agences)){
                                                                foreach ($agences as $key => $value) {
                                                                    echo '<option value='.$value['matricule_ag'].'>'.strtoupper($value['nom_ag']).'</option>';
                                                                }
                                                            }else{
                                                                echo '<option selected disabled>Aucune agence trouvé</option>'; 
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <span class="form-text text-danger" id="plusvenduag_error"></span>
                                                <span class="form-text">PERIODE</span>
                                                <div class="input-daterange input-group" id="kt_datepicker_1">
                                                    <input type="text" class="form-control" name="debutplusv" id="debutplusv" placeholder="date debut" />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="finplusv" name="finplusv" placeholder="date fin" />
                                                </div>
                                                <span class="form-text text-danger" id="periode_error"></span>
                                            </div>
                                            <button type="submit" class="btn btn-primary" id="btnartplusvendu">Voir</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!--end::Portlet-->	
                        </div>

                        <!--<div class="col-lg-6 col-xl-4 order-lg-1 order-xl-1">
                            <!--begin::Portlet--
                            <div class="kt-portlet kt-portlet--height-fluid">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">			
                                        <h3 class="kt-portlet__head-title">Le Meilleur Client Sur Une Période</h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                        <div class="kt-portlet__head-wrapper">
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-widget-18">
                                        <div class="kt-widget-18__summary">
                                            <div class="kt-widget-18__label">liste des clients qui achètent le plus, leurs Chiffires et articles par famille</div>
                                        </div>
                                        <div class="kt-widget-18__progress">
                                            <div class="progress">
                                                <div class="progress-bar bg-brand" role="progressbar" style="width: 100%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="kt-widget-18__item">
                                            <form method="post" action="<?php echo base_url('artplusvendu'); ?>" id="formartplusvendu">
                                                <div class="mb-3 col-lg-12 col-md-9 col-sm-12">
                                                    <span class="form-text">PERIODE</span>
                                                    <div class="input-daterange input-group" id="kt_datepicker_3">
                                                        <input type="text" class="form-control" name="debutplusv" id="debutplusv" placeholder="date debut" />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" name="finplusv" name="finplusv" placeholder="date fin" />
                                                    </div>
                                                    <span class="form-text text-danger" id="periode_error"></span>
                                                </div>
                                                <button type="submit" class="btn btn-primary" id="btnartplusvendu">Voir</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>	 
                            </div>
                            <!--end::Portlet--
                        </div>-->
                
                        <!--
                        <div class="col-lg-6 col-xl-4 order-lg-2 order-xl-1">
                            <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">
                                            Points Cumuler Atteint par Chaque client et sur une période                     
                                        </h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade active show" id="kt_portlet_tabs_1_1_1_content" role="tabpanel">
                                            <div class="kt-scroll" data-scroll="true" style="height: 420px;" data-mobile-height="350">
                                                <!--Begin::Timeline --
                                                <div class="kt-timeline">

                                                    <form method="post" action="<?php echo base_url('artplusvendu'); ?>" id="formartplusvendu">
                                                        <div class="mb-3 col-lg-12 col-md-9 col-sm-12">
                                                            points Cumuler Par Chaque client
                                                            <hr class="bg-primary">
                                                            <span class="form-text">PERIODE</span>
                                                            <div class="input-daterange input-group" id="kt_datepicker_2">
                                                                <input type="text" class="form-control" name="debutplusv" id="debutplusv" placeholder="date debut" />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                                                </div>
                                                                <input type="text" class="form-control" name="finplusv" name="finplusv" placeholder="date fin" />
                                                            </div>
                                                            <span class="form-text text-danger" id="periode_error"></span>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary" id="btnartplusvendu">Voir</button>
                                                    </form>
                                                    <hr class="bg-primary">
                                                    Client
                                                    <ul class="list-group">
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <span class="badge">14</span>
                                                            A list item
                                                            <span class="badge rounded-pill">14</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!--End::Timeline 1 --  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>-->

                    </div>
                
            </div>


            <!-- voir le ca sur une période -->
            <div class="modal fade bd-example-modal-xl camodal">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Chiffre d'affaire sur une période</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php if (session('users')['nom_ag'] == "" && session('users')['nom_serv'] == ""){ ?>
                            <form id="formcaperiode">
                                <div class="form-group row">
                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                        <span class="form-text">CAISSE (click pour choisir la caisse. si rien n'est choisi, l'ensemble des caisse sera choisi)</span>
                                        <div class="input-daterange input-group">
                                            <select class="form-control" name="caisse" id="caisse">
                                            </select>
                                        </div>
                                        <span class="form-text text-danger" id="caisse_error"></span>
                                    </div>
                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                        <span class="form-text">PERIODE</span>
                                        ***
                                        <div class="input-daterange input-group" id="kt_datepicker_1">
                                            <input type="text" class="form-control" name="start" id="start" placeholder="date debut" />
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="end" name="end" placeholder="date fin" />
                                        </div>
                                        <span class="form-text text-danger" id="periode_error"></span>
                                    </div>
                                    <label class="col-form-label col-lg-3 col-sm-12">
                                        <span class="form-text"></span>
                                        <button type="submit" id="btncaperiode" class="btn btn-primary">Voir</button>
                                    </label>
                                </div>
                            </form>
                            <?php } ?>
                            <hr>
                            <div class="row" data-masonry='{"percentPosition": true }' id="contentca">
                            </div>
                            <div class="row" data-masonry='{"percentPosition": true }' id="agenceca">
                            </div>
                            <hr>
                            <b>variation du CA</b>
                            <span class="variation"></span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- voir les articles les plus vendu sur une période -->
            <div class="modal fade bd-example-modal-xl artplusvendumodal">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Chiffre d'affaire sur une période</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="sectionAimprimer">
                            <b id="periodeartv"></b>
                            <hr>
                            <h3 id="familleartv"></h3>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>/</th>
                                        <th>Code</th>
                                        <th>Désignation</th>
                                        <th>Qte</th>
                                        <th>Prix Total HT</th>
                                        <th>Bénéfiche Moyen</th>
                                    </tr>
                                </thead>
                                <tbody id="plusvendu">
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-outline-danger" onClick="imprimer('sectionAimprimer')" target="_blank">Imprimer</a>
                        </div>
                    </div>
                </div>
            </div>



            <!-- end:: Content -->				

        </div>

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->
<script>
    function imprimer(divName) {
        var printContents = document.getElementById(divName).innerHTML;    
    var originalContents = document.body.innerHTML;      
    document.body.innerHTML = printContents;     
    window.print();     
    document.body.innerHTML = originalContents;
    }
</script>

<script>
    $(document).ready(function () {

        /**liste des annonces */
        annonce();
        function annonce(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('getmsgsaveh'); ?>",
                dataType: "json",
                success: function (data){
                    if(data.success){
                        $('#annonce').html(data.success);
                    }
                }
            });  
        }

        /**ca sur une periode */
        $(".shocamodal").click(function (e) { 
            e.preventDefault();
            $(".camodal").modal('show');
        });

        /**ca sur une periode op */
        $('#formcaperiode').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('caperiode'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btncaperiode').attr('disabled', 'disabled');
                    $('#btncaperiode').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data){
                    if(data.error){
                        if(data.caisse_error != ''){
                            $('#caisse_error').html(data.caisse_error);
                        }else{
                            $('#caisse_error').html('');
                        }

                        if(data.periode_error != ''){
                            $('#periode_error').html(data.periode_error);
                        }else{
                            $('#periode_error').html('');
                        }
                    }
                    if(data.success){
                        $('#caisse_error').html('');
                        $('#periode_error').html('');
                        $('#contentca').html(data.success);
                        
                        
                    }
                    if(data.caisseca){
                        $('#caisse_error').html('');
                        $('#periode_error').html('');
                        $('#agenceca').html(data.caisseca);
                    }
                    if(data.variations){
                        $('#caisse_error').html('');
                        $('#periode_error').html('');
                        $('.variation').html(data.variations);
                    }
                    
                    $('#btncaperiode').attr('disabled', false);
                    $('#btncaperiode').html('Voir');
                }
            });
        });

        
        
        
        /**caisse ayant participer aumoins une fois a une vente*/
        caissvente();
        function caissvente(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('caissevente'); ?>",
                dataType: "json",
                success: function (data){
                    if(data.result){
                        $('#caisse').html(data.result);
                    }
                }
            });
        }

        /****afficher les articles les plus vendu par famille*/
        $('#formartplusvendu').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btnartplusvendu').attr('disabled', 'disabled');
                    $('#btnartplusvendu').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> préparation du résultat, cela peut prendre un certain temps... MERCI DE PATIENTER');
                    $('.fa-spin').addClass('active');
                },
                success: function (data){
                    if(data.error){
                        if(data.plusvenduag_error != ''){
                            $('#plusvenduag_error').html(data.plusvenduag_error);
                        }else{
                            $('#plusvenduag_error').html('');
                        }

                        if(data.periode_error != ''){
                            $('#periode_error').html(data.periode_error);
                        }else{
                            $('#periode_error').html('');
                        }
                    }
                    if(data.success){
                        $('#plusvenduag_error').html('');
                        $('#periode_error').html('');
                        $('#plusvendu').html(data.success); 
                        $('#periodeartv').html(data.periode);
                        $('#familleartv').html(data.famille);

                        $(".artplusvendumodal").modal('show');
                    }
                    if(data.artvide){
                        $('#plusvendu').html(data.artvide);
                        $(".artplusvendumodal").modal('show');
                    }
                    $('#btnartplusvendu').attr('disabled', false);
                    $('#btnartplusvendu').html('Voir');
                }
            });
        });

        $(".close").click(function (e) { 
            e.preventDefault();
            $(".artplusvendumodal").modal('hide');
        });
    });
</script>

