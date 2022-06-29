
</div>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                 <!-- begin:: Content -->


                <!--begin::Row-->
                <div class="row">			
                    <div class="col-xl-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <div class="kt-portlet__body">
                                <div class="kt-section">
                                    <h3 class="kt-section__title">GESTION DES TAXES ET HEUR DE CLOTURE DE FACTURE</h3>
                                    <div class="kt-section__content kt-section__content--border">
                                        

                                        <div class="form-group row">
                                            <div class="col-lg-4 col-md-9 col-sm-12">
                                                <button type="button" class="btn btn-outline-brand" data-toggle="modal" data-target=".modalnewtaxe">+ TAXE</button>
                                            </div>
                                           
                                            <div class="col-lg-4 col-md-9 col-sm-12">
                                                <span class="form-text">Choisissez l'heur à laquelles les factures seront clôturées</span>
                                                <div class="input-group timepicker">
                                                    <input class="form-control time_clo_fact" readonly placeholder="choisi l'heur" id="kt_timepicker_2" type="text"/>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="la la-clock-o"></i>
                                                        </span>
                                                    </div>
                                                    <span class="form-text text-danger" id="msg"></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Portlet-->
                    </div>
                </div>

                <div class="row" id="taxes">
                    
                </div>



                <!-- Large Modal -->
                <div class="modal fade modalnewtaxe" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Nouvelle Taxe</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <span id="message"></span>

                            <form id="new_caisse_form">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Taxe en pourcentage(%)</label>
                                                <input type="text" class="form-control" id="pourcentage" name="pourcentage" placeholder="exemple 19.25 ...">
                                                <span class="form-text text-danger" id="pourcentage_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Libele</label>
                                                <input type="text" class="form-control" id="libele" name="libele" placeholder="nom de la Taxe">
                                                <span class="form-text text-danger" id="libele_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">FERMER</button>
                                    <button type="submit" id="new_btn_taxe" class="btn btn-outline-brand">CREER</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Large Modal -->
                <div class="modal fade modaledittaxe" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modifier Une Taxe</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <span id="message1"></span>

                            <form id="update_caisse_form">
                                <div class="modal-body">
                                    <div class="row">
                                        <input type="hidden" name="matriculetaxe" id="matriculetaxe">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Taxe en pourcentage(%)</label>
                                                <input type="text" class="form-control" id="pourcentage1" name="pourcentage1" placeholder="exemple 19.25 ...">
                                                <span class="form-text text-danger" id="pourcentage1_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Libele</label>
                                                <input type="text" class="form-control" id="libele1" name="libele1" placeholder="nom de la Taxe">
                                                <span class="form-text text-danger" id="libele1_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">FERMER</button>
                                    <button type="submit" id="update_btn_taxe" class="btn btn-outline-brand">MODIFIER</button>
                                </div>
                            </form>
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
        
        /**creer une nouvelle taxe debut */
        $('#new_caisse_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('new_taxe'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#new_btn_taxe').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        if(data.pourcentage_error != ''){
                            $('#pourcentage_error').html(data.pourcentage_error);
                        }else{
                            $('#pourcentage_error').html('');
                        }

                        if(data.libele_error != ''){
                            $('#libele_error').html(data.libele_error);
                        }else{
                            $('#libele_error').html('');
                        }
                    }
                    if(data.success){
                        $('#pourcentage_error').html('');
                        $('#libele_error').html('');
                        $('#new_caisse_form')[0].reset();
                        $('#message').html(data.success);
                        list_taxe();
                    }
                    $('#new_btn_taxe').attr('disabled', false);
                }
            });
        });
        /**creer une nouvelle taxe fin */

        /**afficher la liste des taxes debut */
        list_taxe();
        function list_taxe(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('all_taxe'); ?>",
                dataType: "json",
                success: function(data) {
                    $("#taxes").html(data);
                }
            });
        }
        /**afficher la liste des taxes fin */

        /**afficher les informations dans le formulaire de modification debut */
        $(document).on('click', '.viewtaxe', function(){
            var mat_taxe = $(this).attr("id");
            $.ajax({
                method:"POST",
                url: "<?php echo base_url('get_single_taxe'); ?>",
                data:{mat_taxe:mat_taxe},
                dataType: "json",
                success: function(data){
                    $("#matriculetaxe").val(data.code);
                    $("#pourcentage1").val(data.pourcentage);
                    $('#libele1').val(data.libelle);
                }
            }); 
            $(".modaledittaxe").modal('show');
        });
        /**afficher les informations dans le formulaire de modification fin */
        
        /**modifier une nouvelle taxe debut */
        $('#update_caisse_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('update_taxe'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#update_btn_taxe').attr('disabled', 'disabled');
                },
                success: function(data){
                    if(data.error){
                        if(data.pourcentage1_error != ''){
                            $('#pourcentage1_error').html(data.pourcentage1_error);
                        }else{
                            $('#pourcentage1_error').html('');
                        }

                        if(data.libele1_error != ''){
                            $('#libele1_error').html(data.libele1_error);
                        }else{
                            $('#libele1_error').html('');
                        }
                    }
                    if(data.success){
                        $('#pourcentage1_error').html('');
                        $('#libele1_error').html('');
                        $('#message1').html(data.success);
                        list_taxe();
                    }
                    $('#update_btn_taxe').attr('disabled', false);
                }
            });
        });
        /**modifier une nouvelle taxe fin */

        /**ajoute la date de cloture d'une facture debut*/
        $(".time_clo_fact").change(function (e) { 
            e.preventDefault();
            
            var valtime = $(".time_clo_fact").val();
            $.ajax({
                type: "post",
                url: "<?php echo base_url('add_timeclo_fac'); ?>",
                data: {valtime:valtime},
                dataType: "json",
                success: function(data){
                    if(data.success){
                        $("#msg").html(data.success);
                    }
                }
            });
        });
        /**ajoute la date de cloture d'une facture fin*/

        /**affiche la date de cloture selectionné debut */
        timecloshow();
        function timecloshow(){
            $.ajax({
                type: "get",
                url: "<?php echo base_url('show_timeclo_fac'); ?>",
                dataType: "json",
                success: function (data) {
                    if(data.success){
                        $(".time_clo_fact").val(data.success);
                    }
                }
            });
        }
        /**affiche la date de cloture selectionné fin */


    });
</script>