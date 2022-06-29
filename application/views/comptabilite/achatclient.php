
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
                            <h3 class="kt-portlet__head-title">C.A et cumul des points par chaque client.</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">

                                <form id="showuselsform" method="post">
                                    <div class="form-group row">
                                            <div class="col-lg-4 col-md-9 col-sm-12">
                                                <label class="visually-hidden">Client</label>
                                                <select class="form-control kt-select2" id="kt_select2_8" name="client" id="client">
                                                    
                                                    <?php if(!empty($clients)){ ?>
                                                        <?php echo '<option></option>'; ?>
                                                        <?php foreach($clients as $val){ ?>
                                                            
                                                            
                                                            <?php if(set_value('client') == $val['matricule_cli']){ ?>
                                                                <?php echo '<option selected value='.$val['matricule_cli'].'>'.$val['nom_cli'].'</option>'; ?>
                                                            <?php }else{ ?>
                                                                <?php echo '<option value='.$val['matricule_cli'].'>'.$val['nom_cli'].'</option>'; ?>
                                                            <?php } ?>
                                                            
                                                            
                                                        <?php } ?>
                                                    <?php }else{ ?>
                                                        <?php echo "<option selected disabled>aucun employé trouvé</option>"; ?>
                                                    <?php } ?>
                                                    
                                                </select>
                                                <span class="form-text text-danger" id="employe_error"></span>
                                            </div>
                        				<div class="col-lg-4 col-md-9 col-sm-12">
                        				    <label class="visually-hidden">...</label>
                        					<div class="input-daterange input-group" id="kt_datepicker_5">
                        						<input type="text" class="form-control" name="start" placeholder="debut" />
                        						<div class="input-group-append">
                        							<span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                        						</div>
                        						<input type="text" class="form-control" name="end" placeholder="fin" />
                        					</div>
                                            <span class="row"><span class="form-text text-danger col-md-6" id="start_error"></span> <span class="form-text text-danger  col-md-6" id="end_error"></span></span>
                        				</div>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                        				    <label class="visually-hidden">...</label>
                        					<div class="input-daterange input-group" id="kt_datepicker_5">
                                                <button class="btn btn-primary" type="submit" id="btnshowusels"><i class="fa fa-eye"></i> Voir</button>
                        				        <button class="btn btn-outline-danger" onClick="imprimer('sectionAimprimer')"><i class="fa fa-file-pdf"></i> Imprimer</button>
                                            </div>
                                        </div>
                        			</div>
                    			</form>
                            </div>
                        </div>


                        <hr class="text-primary">
                        <div class="table-responsive" class="kt-scroll" data-scroll="true" style="height: 1000px;"> 
                            <div id='sectionAimprimer'>
                                <table class="table table-striped">
                                    <thead>
                                      <th>CLIENT</th>
                                      <th>PRODUIT</th>
                                      <th>QTE TOTAL</th>
                                      <th>PRIX U. Moyen</th>
                                      <th>PRIX T. HT.</th>
                                    </thead>
                                    <tbody id="infossels">
                                    </tbody>
                                </table>
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
        $('#showuselsform').on('submit', function(event){
            event.preventDefault();
           $.ajax({
               method: "POST",
                url: "<?php echo base_url('comptacustompurchase'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btnshowusels').attr('disabled', 'disabled');
                },
                success: function (data){
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
                        if(data.employe_error != ''){
                            $('#employe_error').html(data.employe_error);
                        }else{
                            $('#employe_error').html('');
                        }
                    }
                    
                    if(data.infos){
                        $('#start_error').html('');
                        $('#end_error').html('');
                        $('#employe_error').html('');
                        $('#infossels').html(data.infos);
                    }
                    
                    $('#btnshowusels').attr('disabled', false);
                }
           });
        });
    });
</script>

<script>
    function imprimer(divName) {
          var printContents = document.getElementById(divName).innerHTML;    
       var originalContents = document.body.innerHTML;      
       document.body.innerHTML = printContents;     
       window.print();     
       document.body.innerHTML = originalContents;
    }
</script>

