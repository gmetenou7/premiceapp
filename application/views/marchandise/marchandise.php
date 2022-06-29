
</div>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

            <!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <!--begin::Row-->
        <div class="row">			
	        <div class="col-xl-12">
		        <!--begin::Portlet-->
		        <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Articles</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <!-- Button trigger modal -->
                                <form id="form_filter">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-10">
                                            <label>Famille d'article</label>
                                            <div></div>
                                            <select class="custom-select form-control famille" name="famille" id="famille">
                                                <option selected disabled>tri par famille</option>
                                                <?php if(!empty($famille)){?>
                                                    <?php echo '<option selected></option>'; ?>
                                                    <?php foreach ($famille as $key => $value) {?> 
                                                        <?php echo '<option value="'.$value['matricule_fam'].'">'.$value['matricule_fam'].' / '.$value['nom_fam'].'</option>'; ?>
                                                    <?php } ?>
                                                <?php }else{ ?>
                                                    <?php echo '<option selected disabled>Aucune famille d\'article trouvé</option>'; ?>
                                                <?php }?>
                                            </select>
                                            <span class="text-danger" id="famille_error"></span>
                                            </div>
                                            <div class="col-md-2">
                                                <label>...</label>
                                                <div></div>
                                                <button type="submit" class="btn btn-primary" id="filter">Filtrer</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <!-- 
                                    barre de recherche permettant d'entrer juste le nom de 
                                    l'agence ou du service pour connaitre quel employé est dans 
                                    quel agence ou service
                                 -->
                                <div class="form-group">
                                    <span class="form-text text-muted">
                                        <b>saisi le code ou le code à barres ou la désignation ou le stock pour voir la liste des articles correspondants</b>
                                    </span>
                                    <input class="form-control" type="search" name="search" id="search" placeholder="saisi ici...">
                                    <span id="saisi"></span>
                                </div>
                            </div>
                        </div>
                        <span id="test"></span>
                        <hr class="text-primary">
                        <div class="table-responsive">
                            <table class="table table-striped m-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code à barre</th>
                                        <th>Désignation</th>
                                        <th>Qté en stock</th>
                                        <th>Dépot</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="articles">
                                   
                                </tbody>
                            </table>
                            <span id="pagination"></span>
                        </div>
			        </div>
		        </div>
		        <!--end::Portlet-->
	        </div>
        </div>	
    </div>
<!-- end:: Content -->	


    <!-- moadal pour afficher les detais d'un articles debut -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_details">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="datails">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <!-- moadal pour afficher les detais d'un articles fin -->




            <!-- end:: Content -->
        </div>

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->

<script>
    $(document).ready(function () {
        
        /**function qui affiche la liste des article */
        all_articl(1);
        function all_articl(page){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('all_articl/');?>"+page,
                dataType: "json",
                beforeSend:function(){
                    $('#articles').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data){
                    $("#articles").html(data.values);
                    $("#pagination").html(data.pagination);
                    
                    $('.has-spinner').removeAttr("disabled");
                }
            });
        }

        /**gerer le click sur la pagination article des articles en générale*/
        $(document).on("click", ".pagination li a", function(event){
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            all_articl(page);
        });

        /**gerer le click sur la pagination article des articles rechercher*/
        $(document).on("click", ".paginat li a", function(event){
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            recherche(page);
        });

        /**afficher les details d'un articles debut */
        $(document).on('click', '.view', function(){
            var article_mat = $(this).attr("id");
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('get_details_marchandise');?>",
                data: {article_mat:article_mat},
                dataType: "JSON",
                beforeSend:function(){
                    $('.view').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                    $('.fa-spin').addClass('active');
                },
                success: function(data) {
                    $("#datails").html(data.details);
                    $("#modal_details").modal("show"); 
                    $('.spinner').html('<span class="spinner"><i class="fa fa-eye"></i></span>');
                }
            });
        });
        /**afficher les details d'un articles fin */

         /**on fait la recherche pour avoir un article en particulier */
        $("#search").keyup(function (e) { 
            recherche(1);
        });

        function recherche(page){
            var recherche = $("#search").val(); 
            if(recherche !== ""){
                var famille = $("#famille").val();
                $.ajax({
                    method: "POST",
                    url: "<?php echo base_url('rceherche_articl/');?>"+page,
                    data: {recherche:recherche,famille:famille},
                    dataType: "JSON",
                    beforeSend:function(){
                        $('#articles').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                        $('.fa-spin').addClass('active');
                    },
                    success: function(data){
                        $("#articles").html(data.values);
                        $("#pagination").html(data.pagination);
                        
                        $('.has-spinner').removeAttr("disabled");
                    }
                });
            }else{
                $("#saisi").html('');
                all_articl(1);
            } 
        }
        
        /*filtrer les article par famille*/
        $('#form_filter').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('filter_article'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#filter').attr('disabled', 'disabled');
                    $('#filter').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data) {
                    if(data.error){
                       
                        if(data.famille_error!= ''){
                            $('#famille_error').html(data.famille_error);
                        }else{
                            $('#famille_error').html('');
                        }
                        
                    }
                    if(data.success){
                        $('#famille_error').html('');

                        $('#articles').html(data.success);
                    }
                    $('#filter').html('<span>filtrer</span>');
                    $('#filter').attr('disabled', false);
                }
            });
        });
        
        

    });
</script>