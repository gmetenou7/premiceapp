
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
                            <h3 class="kt-portlet__head-title">Opération subit par un article sur une période donnée dans le commerce</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <form id="suivi_art_form">
                                <div class="row">
                                    <div class="col-lg-6 col-md-9 col-sm-12">
                                        <select class="form-control kt-select2 article" id="kt_select2_8" name="article" id="article">
                                            <?php if(!empty($articles)):?>
                                                <?php echo '<option></option>'; ?>
                                                <?php foreach ($articles as $key => $value):?>
                                                    <?php 
                                                        if(set_value('article') == $value['matricule_art']){
                                                            echo '<option value="'.set_value('article').'" selected>'.$value['matricule_art'].' / '.$value['code_bar'].' / '.$value['designation'].' / '.$value['reference'].'</option>'; 
                                                        }else{
                                                            echo '<option value="'.$value['matricule_art'].'">'.$value['matricule_art'].' / '.$value['code_bar'].' / '.$value['designation'].' / '.$value['reference'].'</option>'; 
                                                        }
                                                    ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <?php echo '<option selected disabled>aucun fournisseur trouvé</option>'; ?>
                                            <?php endif ?>
                                        </select>
                                        <span class="form-text text-danger" id="article_error"">article</span>
                                    </div>
                                    <div class="col-lg-5 col-md-9 col-sm-12">
                                        <div class="input-daterange input-group" id="kt_datepicker_5">
                                            <input type="text" class="form-control" name="start" placeholder="debut" />
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="end" placeholder="fin" />
                                        </div>
                                        <span class="form-text test-danger" id="periode_error">période</span>
                                    </div>
                                    <div class="col-lg-1 col-md-9 col-sm-12">
                                        <button type="submit" class="btn btn-primary" id="btnsuivi">Voir</button>
                                        <span class="form-text text-muted">voir</span>
                                    </div>
                                </div>
                            </form>

                            <div class="kt-section__content kt-section__content--border">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Doc</th>
                                                    <th>Code Doc</th>
                                                    <th>Article</th>
                                                    <th>Qté</th>
                                                    <th>Pu HT</th>
                                                    <th>Pt HT</th>
                                                    <th>Depot</th>
                                                    <th>Date</th>
                                                    <th>Dernière Modif</th>
                                                    <th>Effectué Par</th>
                                                </tr>
                                            </thead>
                                        <tbody id="artsuivi">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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

        /**voir l'historique des opérations sur un article donné debut*/
        $('#suivi_art_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('suiviarticlev'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btnsuivi').attr('disabled', 'disabled');
                    $('#btnsuivi').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data){
                    if(data.error){
                        if(data.article_error != ''){
                            $('#article_error').html(data.article_error);
                        }else{
                            $('#article_error').html('');
                        }
                        if(data.periode_error != ''){
                            $('#periode_error').html(data.periode_error);
                        }else{
                            $('#periode_error').html('');
                        }
                    }
                    if(data.success){
                        $('#article_error').html('');
                        $('#periode_error').html('');
                        $('#artsuivi').html(data.success);
                    }
                    $('#btnsuivi').attr('disabled', false);
                    $('#btnsuivi').html('Voir');
                }
            });
        });
        /**voir l'historique des opérations sur un article donné debut*/
      

    });
</script>