</div>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
            <!-- begin:: Content -->

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="kt-section">
                            <h3 class="kt-section__title">Sorti de caisse</h3>
                            <div class="kt-section__info">Utilise les paramètres suivants pour faire une sortie de caisse</div>
                            <form id="new_sorti_caisse_form">
                            <div class="kt-section__content kt-section__content--border">
                                <?php $this->load->view('parts/message'); ?>
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                        <div class="row">
                                            <div class="col-lg-6 ml-lg-auto">
                                                <span class="form-text text-muted">Type de document</span>
                                                <select class="form-control kt_select2 tdocument" id="kt_select2_1" name="tdocument">
                                                    <?php echo '<option></option>'; ?>
                                                    <?php if(!empty($docs)){ ?>
                                                        <?php foreach($docs as $value){?> 
                                                            <?php 
                                                                if(set_value('tdocument') == $value['code_doc']){
                                                                    echo '<option value="'.set_value('tdocument').'" selected>'.set_value('tdocument').' / '.$value['intiutle_doc'].' ('.$value['abrev_doc'].')</option>'; 
                                                                }else{
                                                                    echo '<option value='.$value['code_doc'].'>'.$value['code_doc'].' / '.$value['intiutle_doc'].' ('.$value['abrev_doc'].')</option>';
                                                                }   
                                                            ?>
                                                        <?php }?> 
                                                    <?php }?> 
                                                </select>
                                                <span id="tdocument_error" class="text-danger"></span>
                                            </div>
                                            <div class="col-lg-6 ml-lg-auto">
                                                <span class="form-text text-muted">Document</span>
                                                <select class="form-control kt-select2 document" id="kt_select2_" name="document">
                                                    <?php if(!empty($code_doc)){?> 
                                                        <?php foreach($code_doc as $key => $value){?> 
                                                            <?php echo '<option value='.$key.'>'.$value.'</option>'; ?>
                                                        <?php }?> 
                                                    <?php }?>
                                                </select>
                                                <span id="document_error" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-4 ml-lg-auto">
                                                <span class="form-text text-muted">Agence</span>
                                                <select class="form-control kt-select2 agence" id="kt_select2_" name="agence">
                                                    <?php 
                                                        if(!empty($agences)){ 
                                                            echo '<option></option>';
                                                    ?>
                                                        <?php foreach($agences as $value){?> 
                                                            <?php echo '<option value='.$value['matricule_ag'].'>'.$value['nom_ag'].'</option>'; ?>
                                                        <?php }?> 
                                                    <?php }?>
                                                </select>
                                                <span id="agence_error" class="text-danger"></span>
                                            </div>
                                            <div class="col-lg-4 ml-lg-auto">
                                                <span class="form-text text-muted">Nom du concerné</span>
                                                <input type="text" name="concerne" id="concerne" class="form-control" placeholder="qui la prend?">
                                                <span id="concerne_error" class="text-danger"></span>
                                            </div>
                                            <div class="col-lg-4 ml-lg-auto">
                                                <span class="form-text text-muted">Motif</span>
                                                <input type="text" name="motif" id="motif" class="form-control">
                                                <span id="motif_error" class="text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                        <div class="row">
                                            <div class="col-lg-3 ml-lg-auto">
                                                <span class="form-text text-muted">Montant</span>
                                                <input type="text" name="montant" id="montant" class="form-control" placeholder="montant">
                                                <span id="montant_error" class="text-danger"></span>
                                            </div>
                                            <div class="col-lg-5 ml-lg-auto">
                                                <span class="form-text text-muted">Caissier</span>
                                                <select class="form-control caisse" id="caisse" name="caisse">
                                                    <?php if(!empty($caisses)){ ?>
                                                        <?php foreach($caisses as $value){?> 
                                                            <?php echo '<option value='.$value['code_caisse'].'>'.$value['nom_emp'].' ---> '.$value['libelle_caisse'].'</option>'; ?>
                                                        <?php }?> 
                                                    <?php }?>
                                                </select>
                                                <span id="caisse_error" class="text-danger"></span>
                                            </div>
                                            <div class="col-lg-3 ml-lg-auto">
                                                <span class="form-text text-muted">Commercial / Vendeur</span>
                                                <select class="form-control commercial" id="commercial" name="commercial">
                                                    <?php if(!empty($vendeur)){ ?>
                                                        <?php foreach($vendeur as $key=>$values){?> 
                                                            <?php echo '<option value='.$values['matricule'].'>'.$values['nom'].'</option>'; ?>
                                                        <?php }?> 
                                                    <?php }?> 
                                                </select>
                                                <span id="commercial_error" class="text-danger"></span>
                                            </div>
                                            <div class="col-lg-1 ml-lg-auto">
                                                <span class="form-text text-muted">...</span>
                                                <button type="submit" id="new_sorti_btn" class="btn btn-dark">OK</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>                        
                            </div>
                            </form>
                            <span id="message"></span> 
                        </div>
                    </div>
                </div>
                <!--end::Portlet-->

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="kt-section">
                            <table class="table table-striped m-table">
                                <thead>
                                    <tr>
                                        <th>Document De Sortie de Caisse</th>
                                        <th>
                                            Montant TOTAL: <b class="text-danger" id="totalsorti"></b>
                                        </th>
                                        <th>
                                            Nombre Opération: <b class="text-danger" id="nbrs"></b>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="kt-section__content kt-section__content--border">
                                <div class="table-responsive">
                                    <table class="table table-striped m-table">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <!--<th>Désignation</th>-->
                                                <th>Montant</th>
                                                <th>Concerné</th>
                                                <th>Initiateur</th>
                                                <th>Agence</th>
                                                <th>Motif</th>
                                                <th>Date</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="all_sorti">
                                            
                                        </tbody>
                                        <span id="paginationshow"></span>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Portlet-->


            <!-- end:: Content -->				

        </div>

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->

<script>
    $(document).ready(function () {
        /**new sorti de caisse debut*/
        $('#new_sorti_caisse_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('form_sorti_caisse'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#new_sorti_btn').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        
                        if(data.document_error != ''){
                            $('#document_error').html(data.document_error);
                        }else{
                            $('#document_error').html('');
                        }
                        
                        if(data.tdocument_error != ''){
                            $('#tdocument_error').html(data.tdocument_error);
                        }else{
                            $('#tdocument_error').html('');
                        }

                        if(data.agence_error != ''){
                            $('#agence_error').html(data.agence_error);
                        }else{
                            $('#agence_error').html('');
                        }

                        if(data.concerne_error != ''){
                            $('#concerne_error').html(data.concerne_error);
                        }else{
                            $('#concerne_error').html('');
                        }

                        if(data.montant_error != ''){
                            $('#montant_error').html(data.montant_error);
                        }else{
                            $('#montant_error').html('');
                        }

                        if(data.motif_error != ''){
                            $('#motif_error').html(data.motif_error);
                        }else{
                            $('#motif_error').html('');
                        }

                        if(data.caisse_error != ''){
                            $('#caisse_error').html(data.caisse_error);
                        }else{
                            $('#caisse_error').html('');
                        }

                        if(data.commercial_error != ''){
                            $('#commercial_error').html(data.commercial_error);
                        }else{
                            $('#commercial_error').html('');
                        }
                        
                    }

                    if(data.success){
                        $('.tdocument_error').html('');
                        $('.agence_error').html('');
                        $('#concerne_error').html('');
                        $('#montant_error').html('');
                        $('#motif_error').html('');
                        $('#caisse_error').html('');
                        $('#commercial_error').html('');
                        all_sorti(1);
                        $('#message').html(data.success);
                        //$('#new_sorti_caisse_form')[0].reset();
                        //$('#accordpayement_error').html('');
                        //$('#new_sorti_caisse_form')[0].reset();
                    }
                   $('#new_sorti_btn').attr('disabled', false);
                }
            });
        });
        /**new sorti de caisse fin*/

        /**liste sortie de caisse debut */
        all_sorti(1);
        function all_sorti(page){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('all_sorti/');?>"+page,
                dataType: "json",
                success: function(data){
                    if(data.success){
                        $("#all_sorti").html(data.success);
                        $("#totalsorti").html(data.total);
                        $("#nbrs").html(data.nbrsorti);
                        $("#paginationshow").html(data.pagination_link);
                    }  
                }
            });
        }
        /**liste sortie de caisse fin */
        /**gerer le click sur la pagination article*/
        $(document).on("click", ".pagination li a", function(event){
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            all_sorti(page);
        });
        /**selectionne tous les articles dans la base des données fin */

        /**supprimer une sorti de caisse debut */
        $(document).on("click", ".delete_sort_c", function(event){
            event.preventDefault();
            var code_s = $(this).attr('id');
            swal.fire({
                title:"Es-tu sûr?",
                text: 'vous êtes sur le point de supprimer cette sortie de caisse...',
                type:"warning",
                showCancelButton:!0,
                confirmButtonText: `Oui`,
            }).then(function(e){
                if(e.value){
                    $.ajax({
                        method: "POST",
                        url: "<?php echo base_url('delete_s_c');?>",
                        data: {code_s:code_s},
                        dataType: "json",
                        success: function(data){
                            all_sorti(1);
                            $("#message").html(data);
                        }
                    });
                }else{
                    Swal.fire('Les modifications ne sont pas enregistrées', '', 'info')
                }
            })

        })
        /**supprimer une sorti de caisse fin */
    });
</script>

