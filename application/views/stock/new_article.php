
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
                            <h3 class="kt-portlet__head-title">Administré les articles</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-brand" data-toggle="modal" data-target=".bd-example-modal-xl">Nouvel Article</button>
                                <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target=".modal_margperfam">% de Marge Par Famille</button>
                                
                                
                                <hr>
                                <form action="<?php echo base_url('article');?>" method="post">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="form-text text-muted">afficher par famille de produit / Imprimer par famille</span>
                                            <select name="tri_famille_produit" id="tri_famille_produit" class="form-control">
                                                
                                            </select>
                                            <?php echo form_error('tri_famille_produit1', '<div class="error text-danger">', '</div>'); ?>
                                            <span class="form-text text-danger" id="tri_famille_produit_error"></span>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="form-text text-muted">.</span>
                                            <button type="submit" class="btn btn-outline-brand btn-outline-success"><i class="fa fa-file-excel"></i> GENERER</button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <span class="form-text text-muted">chercher un produit avec son code, sa désignation, sa référence, son prix de revient, son prix hors taxe, son pourcentage de marge, son fournisseur, sa famille, le nom de l'employé l'ayant enrégistré</span>
                                <input type="search" name="chercher" id="chercher" class="form-control" placeholder="saisi ici...">
                                <span id="text"></span>

                                <!-- Large Modal pour creer un article debut-->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Creer Un article</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <!--begin::Form-->
                                        <form class="kt-form" id="new_article_form">
                                            <div class="modal-body">
                                                <div class="kt-portlet__body">
                                                    <div class="form-group form-group-last">
                                                        <div class="alert alert-secondary" role="alert">
                                                            <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                                                            <div class="alert-text">
                                                                informations: les champs ayants <span class="text-danger">*</span> 
                                                                sont obligatoire
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span id="message_new_article"></span>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Code à barre <span class="text-danger"></span></label>
                                                                <input type="text" class="form-control" name="codebar" id="codebar" placeholder="code à barre">
                                                                <span class="form-text text-danger" id="codebar_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Désignation <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="design" id="design"  placeholder="désignation">
                                                                <span class="form-text text-danger" id="design_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Référence <span class="text-danger"></span></label>
                                                                <input type="text" class="form-control" name="ref" id="ref"ss placeholder="référence">
                                                                <span class="form-text text-danger" id="ref_error"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Fournisseur <span class="text-danger">*</span></label>
                                                                <select name="fournisseur" id="fournisseur" class="form-control">
                                                                    
                                                                </select>
                                                                <span class="form-text text-danger" id="fournisseur_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Famille produit <span class="text-danger">*</span></label>
                                                                <select name="famille_prod" id="famille_prod" class="form-control">
                                                                    
                                                                </select>
                                                                <span class="form-text text-danger" id="famille_prod_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Prix de revient total <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="prix_revient" id="prix_revient" placeholder="Prix de revient total">
                                                                <span class="form-text text-danger" id="prix_revient_error"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Prix HT en % <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="pourcentage_marge" id="pourcentage_marge" placeholder="entre le pourcentage de marge">
                                                                <span class="form-text text-danger" id="pourcentage_marge_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Quantité critique <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="critique" id="critique" placeholder="Quantité seuil ou critique">
                                                                <span class="form-text text-danger" id="critique_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Date de peremption <span class="text-danger"></span></label>
                                                                <input type="date" class="form-control" name="date_perem" id="date_perem" placeholder="Date de peremption">
                                                                <span class="form-text text-danger" id="date_perem_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>alert peremption(en jrs)<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="delais_perem" id="delais_perem" placeholder="delais d'alert">
                                                                <span class="form-text text-danger" id="delais_perem_error"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                                                <button type="submit" id="btn_new_article" class="btn btn-outline-primary">Créer</button>
                                            </div>
                                        </form>
                                            <!--end::Form-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="text-primary">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Code Article</th>
                                        <th scope="col">Désignation</th>
                                        <th scope="col">Prix revient</th>
                                        <th scope="col">Pourcentage Marge</th>
                                        <th scope="col" width="20%">Prix HT</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody id="all_article">
                                    
                                </tbody>
                            </table>
                            <div align="right" id="pagination_link"></div>
                        </div>



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

                        <!-- moadal pour les detais d'un articles dans un formulaire pour modification debut -->
						<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_edit_article">
							<div class="modal-dialog modal-xl">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Modifier Un article</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
                                    <form class="kt-form" id="update_article_form">
                                        <div class="modal-body">
                                            <div class="kt-portlet__body">
                                                <div class="form-group form-group-last">
                                                    <div class="alert alert-secondary" role="alert">
                                                        <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                                                        <div class="alert-text">
                                                            informations: les champs ayants <span class="text-danger">*</span> 
                                                            sont obligatoire
                                                        </div>
                                                    </div>
                                                </div>
                                                <span id="message_update_article"></span> 
                                                <input type="hidden" name="matricule" id="matricule">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Code à barre <span class="text-danger"></span></label>
                                                            <input type="text" class="form-control" name="codebar1" id="codebar1" placeholder="code à barre">
                                                            <span class="form-text text-danger" id="codebar1_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Désignation <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="design1" id="design1"  placeholder="désignation">
                                                            <span class="form-text text-danger" id="design1_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Référence <span class="text-danger"></span></label>
                                                            <input type="text" class="form-control" name="ref1" id="ref1"ss placeholder="référence">
                                                            <span class="form-text text-danger" id="ref1_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Fournisseur <span class="text-danger">*</span></label>
                                                            <select name="fournisseur1" id="fournisseur1" class="form-control">
                                                                
                                                            </select>
                                                            <span class="form-text text-danger" id="fournisseur1_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Famille produit <span class="text-danger">*</span></label>
                                                            <select name="famille_prod1" id="famille_prod1" class="form-control">
                                                                
                                                            </select>
                                                            <span class="form-text text-danger" id="famille_prod1_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Prix de revient total <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="prix_revient1" id="prix_revient1" placeholder="Prix de revient total">
                                                            <span class="form-text text-danger" id="prix_revient1_error"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Prix HT en % <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="pourcentage_marge1" id="pourcentage_marge1" placeholder="entre le pourcentage de marge">
                                                            <span class="form-text text-danger" id="pourcentage_marge1_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Quantité critique <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="critique1" id="critique1" placeholder="Quantité seuil ou critique">
                                                            <span class="form-text text-danger" id="critique1_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Date de peremption <span class="text-danger"></span></label>
                                                            <input type="date" class="form-control" name="date_perem1" id="date_perem1" placeholder="Date de peremption">
                                                            <span class="form-text text-danger" id="date_perem1_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>alert peremption(en jrs)<span class="text-danger"></span></label>
                                                            <input type="text" class="form-control" name="delais_perem1" id="delais_perem1" placeholder="delais d'alert">
                                                            <span class="form-text text-danger" id="delais_perem1_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                                            <button type="submit" id="btn_update_article" class="btn btn-outline-primary">Modifier</button>
                                        </div>
                                    </form>
								</div>
							</div>
						</div>
                        <!-- moadal pour afficher les detais d'un articles fin -->

                        
                        <!-- moadal pour attribuer les marges par famille d'article debut -->
						<div class="modal fade modal_margperfam" tabindex="-1" role="dialog" aria-hidden="true" id="modal_margperfam">
							<div class="modal-dialog modal-xl">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Poucentage de marge par famille d'article</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										Appliquer un pourcentage de marge à une famille d'article
                                        <span class="text-danger" id="message_percent" style="height: 5px; overflow: auto;"></span>
                                        <form id="formpercentmarge">
                        					<div class="row">
                        					    <div class="form-group col-md-5">
                            						<label>Famille</label>
                            						<div></div>
                            						<select class="custom-select form-control" name="percentfamilleproduit" id="percentfamilleproduit">
                            						</select>
                            						<span class="text-danger" id="percentfamilleproduit_error"></span>
                            					</div>
                            					<div class="form-group col-md-5">
                            						<label>Pourcentage de marge (%)</label>
                            						<div></div>
                            						<input type="text" class="form-control" name="margefamilleproduit" id="margefamilleproduit" placeholder="pourcentage de marge de la famille">
                            						<span class="text-danger" id="margefamilleproduit_error"></span>
                            					</div>
                            					<div class="form-group col-md-2">
                            						<label>...</label>
                            						<div></div>
                            						<button class="btn btn-primary" id="btnfampercent" type="submit">Appliquer</button>
                            					</div>
                        					</div>
                    					</form>
                    					<hr>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
									</div>
								</div>
							</div>
						</div>
                        <!-- moadal pour attribuer les marges par famille d'article fin -->


			        </div>
		        </div>
		        <!--end::Portlet-->
	        </div>
        </div>	
    </div>
<!-- end:: Content -->				
            <!-- end:: Content -->
        </div>

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->

<script>
    $(document).ready(function () {

        /**afficle la liste des fournisseurs debut*/
        fournisseur();
        function fournisseur(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('lfournisseur');?>",
                dataType: "JSON",
                success:function (data){
                    $("#fournisseur").html(data);
                    $("#fournisseur1").html(data);
                }
            });
        }
        /**afficle la liste des fournisseurs fin*/

        /**afficle la liste des familles d'article debut*/
        famille_article();
        function famille_article(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('lfamille');?>",
                dataType: "JSON",
                success:function (data){
                    $("#famille_prod").html(data);
                    $("#famille_prod1").html(data);
                    $("#percentfamilleproduit").html(data);
                    
                }
            });
        }
        /**afficle la liste des familles d'article fin*/

        /**insertion dans la base des données d'un nouvel article debut*/
        $('#new_article_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('new_article'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_new_article').attr('disabled', 'disabled');
                    $('#btn_new_article').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Encours...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data) {
                    if(data.error){
                        $('#message_new_article').html('');
                        if(data.codebar_error != ''){
                            $('#codebar_error').html(data.codebar_error);
                        }else{
                            $('#codebar_error').html('');
                        }

                        if(data.design_error != ''){
                            $('#design_error').html(data.design_error);
                        }else{
                            $('#design_error').html('');
                        }

                        if(data.ref_error != ''){
                            $('#ref_error').html(data.ref_error);
                        }else{
                            $('#ref_error').html('');
                        }

                        if(data.fournisseur_error != ''){
                            $('#fournisseur_error').html(data.fournisseur_error);
                        }else{
                            $('#fournisseur_error').html('');
                        }

                        if(data.famille_prod_error != ''){
                            $('#famille_prod_error').html(data.famille_prod_error);
                        }else{
                            $('#famille_prod_error').html('');
                        }

                        if(data.prix_revient_error != ''){
                            $('#prix_revient_error').html(data.prix_revient_error);
                        }else{
                            $('#prix_revient_error').html('');
                        }

                        if(data.pourcentage_marge_error != ''){
                            $('#pourcentage_marge_error').html(data.pourcentage_marge_error);
                        }else{
                            $('#pourcentage_marge_error').html('');
                        }

                        if(data.critique_error != ''){
                            $('#critique_error').html(data.critique_error);
                        }else{
                            $('#critique_error').html('');
                        }

                        if(data.date_perem_error != ''){
                            $('#date_perem_error').html(data.date_perem_error);
                        }else{
                            $('#date_perem_error').html('');
                        }

                        if(data.delais_perem_error != ''){
                            $('#delais_perem_error').html(data.delais_perem_error);
                        }else{
                            $('#delais_perem_error').html('');
                        }

                    }
                    if(data.success){
                        $('#codebar_error').html('');
                        $('#design_error').html('');
                        $('#ref_error').html('');
                        $('#fournisseur_error').html('');
                        $('#famille_prod_error').html('');
                        $('#prix_revient_error').html('');
                        $('#pourcentage_marge_error').html('');
                        $('#critique_error').html('');
                        $('#date_perem_error').html('');
                        $('#delais_perem_error').html('');
                        fournisseur();
                        famille_article();
                        all_article(1);
                        famille_tri();
                        four_tri();
                        $('#new_article_form')[0].reset();
                        $('#message_new_article').html(data.success);                   
                    }
                    $('#btn_new_article').attr('disabled', false);
                    $('#btn_new_article').html('Créer');
                }
            });
        });
        /**insertion dans la base des données d'un nouvel article fin*/

        /**selectionne tous les articles dans la base des données debut */
        all_article(1);
        function all_article(page){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('get_all_article/');?>"+page,
                dataType: "JSON",
                beforeSend:function(){
                    $('#all_article').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data){
                    $("#all_article").html(data.articles);
                    $('#pagination_link').html(data.pagination_link);
                    $('.has-spinner').removeAttr("disabled");
                }
            });
        }

        /**gerer le click sur la pagination article*/
        $(document).on("click", ".pagination li a", function(event){
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            all_article(page);
        });
        /**selectionne tous les articles dans la base des données fin */


        /**afficher les details d'un articles debut */
        $(document).on('click', '.view', function(){
            var article_mat = $(this).attr("id");
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('get_details_article');?>",
                data: {article_mat:article_mat},
                dataType: "JSON",
                success: function(data) {
                    $("#datails").html(data.details); 
                    $("#modal_details").modal("show"); 
                }
            });
        });
        /**afficher les details d'un articles fin */


        /**afficher les details d'un articles dans le formulaire debut */
        edit();
       function edit(){
            $(document).on('click', '.edit', function(){
                var article_mat = $(this).attr("id");
                $.ajax({
                    method: "POST",
                    url: "<?php echo base_url('edit_details_article');?>",
                    data: {article_mat:article_mat},
                    dataType: "JSON",
                    success: function(data) {
                        $("#codebar1").val(data.details.code_bar);
                        $("#design1").val(data.details.designation);
                        $("#ref1").val(data.details.reference);
                        $("#fournisseur1").val(data.details.mat_fournisseur);
                        $("#famille_prod1").val(data.details.mat_famille_produit);
                        $("#prix_revient1").val(data.details.prix_revient);
                        $("#pourcentage_marge1").val(data.details.pourcentage_marge);
                        $("#critique1").val(data.details.critique);
                        $("#delais_perem1").val(data.details.delais_alert_peremption);
                        $("#date_perem1").val(data.details.date_peremption);
                        $("#matricule").val(data.details.matricule_art);

                        $("#modal_edit_article").modal("show"); 
                    }
                });
            });
       }
        /**afficher les details d'un articles dans le formulaire fin */

        /**modifier les informations d'un articles debut*/
        $('#update_article_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('update_article'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_update_article').attr('disabled', 'disabled');
                    $('#btn_update_article').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Encours...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data) {
                    if(data.error){
                        $('#message_update_article').html('');
                        if(data.codebar1_error != ''){
                            $('#codebar1_error').html(data.codebar1_error);
                        }else{
                            $('#codebar1_error').html('');
                        }

                        if(data.design1_error != ''){
                            $('#design1_error').html(data.design1_error);
                        }else{
                            $('#design1_error').html('');
                        }

                        if(data.ref1_error != ''){
                            $('#ref1_error').html(data.ref1_error);
                        }else{
                            $('#ref1_error').html('');
                        }

                        if(data.fournisseur1_error != ''){
                            $('#fournisseur1_error').html(data.fournisseur1_error);
                        }else{
                            $('#fournisseur1_error').html('');
                        }

                        if(data.famille_prod1_error != ''){
                            $('#famille_prod1_error').html(data.famille_prod1_error);
                        }else{
                            $('#famille_prod1_error').html('');
                        }

                        if(data.prix_revient1_error != ''){
                            $('#prix_revient1_error').html(data.prix_revient1_error);
                        }else{
                            $('#prix_revient1_error').html('');
                        }

                        if(data.pourcentage_marge1_error != ''){
                            $('#pourcentage_marge1_error').html(data.pourcentage_marge1_error);
                        }else{
                            $('#pourcentage_marge1_error').html('');
                        }

                        if(data.critique1_error != ''){
                            $('#critique1_error').html(data.critique1_error);
                        }else{
                            $('#critique1_error').html('');
                        }

                        if(data.date_perem1_error != ''){
                            $('#date_perem1_error').html(data.date_perem1_error);
                        }else{
                            $('#date_perem1_error').html('');
                        }

                        if(data.delais_perem1_error != ''){
                            $('#delais_perem1_error').html(data.delais_perem1_error);
                        }else{
                            $('#delais_perem1_error').html('');
                        }

                    }
                    if(data.success){
                        $('#codebar1_error').html('');
                        $('#design1_error').html('');
                        $('#ref1_error').html('');
                        $('#fournisseur1_error').html('');
                        $('#famille_prod1_error').html('');
                        $('#prix_revient1_error').html('');
                        $('#pourcentage_marge1_error').html('');
                        $('#critique1_error').html('');
                        $('#date_perem1_error').html('');
                        $('#delais_perem1_error').html('');
                        //fournisseur();
                        //famille_article();
                        all_article(1);
                        famille_tri();
                        $('#message_update_article').html(data.success);                 
                    }
                    $('#btn_update_article').attr('disabled', false);
                    $('#btn_update_article').html('Modifier');
                }
            });
        });
        /**modifier les informations d'un article fin */

        /**ajouter le pourcentage de marge a une famille de produit donnée debut*/
         $('#formpercentmarge').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('percentmargefam'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btnfampercent').attr('disabled', 'disabled');
                    $('#btnfampercent').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Encours...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data) {
                    if(data.error){
                        $('#message_percent').html('');
                        if(data.percentfamilleproduit_error != ''){
                            $('#percentfamilleproduit_error').html(data.percentfamilleproduit_error);
                        }else{
                            $('#percentfamilleproduit_error').html('');
                        }

                        if(data.margefamilleproduit_error != ''){
                            $('#margefamilleproduit_error').html(data.margefamilleproduit_error);
                        }else{
                            $('#margefamilleproduit_error').html('');
                        }

                    }
                    if(data.success){
                        $('#percentfamilleproduit_error').html('');
                        $('#margefamilleproduit_error').html('');
                        $('#message_percent').html(data.success);                 
                    }
                    $('#btnfampercent').attr('disabled', false);
                    $('#btnfampercent').html('Appliquer');
                }
            });
        });
        /**ajouter le pourcentage de marge a une famille de produit donnée fin*/
        
        
        
        /**faire la recherche d'un article debut */
        $("#chercher").keyup(function (e) { 
            var recherche =  $("#chercher").val();
            
            if(recherche != ''){
                $("#text").html(recherche);
                $.ajax({
                    method: "POST",
                    url: "<?php echo base_url('get_all_article');?>",
                    data: {recherche:recherche},
                    dataType: "json",
                    beforeSend:function(){
                        $('#all_article').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                        $('.fa-spin').addClass('active');
                    },
                    success: function(data){
                        $("#all_article").html(data.articles); 
                        $('.has-spinner').removeAttr("disabled");
                    }
                });

            }else{
                $("#text").html('');
                all_article(1);
            }
        });
        /**faire la recherche d'un article fin */
       
        /**affiche les familles d'article pour facilté le tri d'article debut*/
        famille_tri();
        function famille_tri(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('get_tri_famil_article');?>",
                dataType: "JSON",
                success: function (data) {
                    $("#tri_famille_produit").html(data.famille);
                }
            });
        }
        /*four_tri();
        function four_tri(){
            $.ajax({
                method: "GET",
                url: "<?php //echo base_url('get_tri_four_article');?>",
                dataType: "JSON",
                success: function (data){
                    $("#tri_fournisseur_produit").html(data.fournisseur);
                    $("#tri_fournisseur_produit1").html(data.fournisseur);
                }
            });
        }*/
       /**affiche les familles d'article pour facilté le tri d'article fin*/

        /**effectué le tri et afficher les informations du tri debut */
        $("#tri_famille_produit").change(function (e) { 
            e.preventDefault();
            var famille = $("#tri_famille_produit").val();
            if(famille != ''){
                $.ajax({
                    method: "POST",
                    url: "<?php echo base_url('tri_article'); ?>",
                    data: {famille:famille},
                    dataType: "JSON",
                    beforeSend:function(){
                        $('#all_article').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                        $('.fa-spin').addClass('active');
                    },
                    success: function (data) {
                        $("#all_article").html(data.articles);
                        $('.has-spinner').removeAttr("disabled");
                    }
                });
            }else{
                all_article(1);
            }
        });
        /**effectué le tri et afficher les informations du tri fin */




       
       
    });
</script>