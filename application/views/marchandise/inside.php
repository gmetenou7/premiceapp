
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
                            <h3 class="kt-portlet__head-title">Situation de l'entreprise</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <div class="row">
                                    <input type="button" value="Voir" class="seeinside btn btn-dark">
                                    <hr>
                                    <form method="post" action="<?= base_url('inside'); ?>" target="_blank">
                                        <button type="submit" name="printinside" class="btn btn-danger"><i class="fa fa-file-pdf"></i>Imprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <hr class="text-primary">
                       
			        </div>
		        </div>
		        <!--end::Portlet-->
	        </div>
        </div>	
    </div>


    <div class="modal fade bd-example-modal-xl insidemodal">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Capital de l'entreprise</h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover" width="200%">
                                <thead>
                                    <tr>
                                        <th>Code art.</th>
                                        <th>Code à barre</th>
                                        <th>Désignation</th>
                                        <th>Référence</th>
                                        <th width="10%">Qte Total</th>
                                        <th>Critique</th>
                                        <th width="15%">Prix R. x Qte T.</th>
                                        <th>% marge</th>
                                        <th width="15%">Prix HT</th>
                                        <!--<th>Prix TTC</th>
                                        <th>Prix G. HT</th>
                                        <th>Prix G. TTC</th>-->
                                    </tr>
                                </thead>
                                <tbody id="inside_art">
                                </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal" data-bs-dismiss="modal">Fermer</button>
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
    $(document).ready(function () {
        
        /**function qui affiche la liste des article */
        //inside();
        function inside(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('inside_art');?>",
                dataType: "json",
                beforeSend:function(){
                    $('#inside_art').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data){
                    if(data.success){
                        $("#inside_art").html(data.success);  
                        $(".insidemodal").modal("show");
                    }
                    $('.has-spinner').removeAttr("disabled");
                }
            });
        }

        $(".seeinside").click(function (e) { 
            e.preventDefault();
            $('.seeinside').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
            $('.fa-spin').addClass('active');
            inside();
            $('.has-spinner').removeAttr("disabled");
        });

    });
</script>





