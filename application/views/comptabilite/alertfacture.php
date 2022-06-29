
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
                            <h3 class="kt-portlet__head-title">Alerte FActure  dette</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">

                                <div class="row">
                                    <div class="col_md-6">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-outline-brand" onClick="imprimer('sectionAimprimer')"><i class="fa fa-file-pdf"></i> Imprimer</button>
                                        <hr>
                                    </div>
                                </div>
                                <div id='sectionAimprimer' class="table-responsive">
                                    <table class=" table-striped m-table" width="100%">
            						  	<thead>
            						    	<tr>
            						    	    <th>Client</th>
            						    	    <th><small>Initiateur</small></th>
            						      		<th><small>Code Facture</small></th>
            						      		<th><small>Nom Facture</small></th>
            						      		<th width="10%">Dette totale</th>
												<th width="10%">Dette Soldé</th>
            						      		<th width="10%">Dette restante</th>
            						      		<th>Delais (jours)</th>
            						      		<th>Etat</th>
            						      		<th><small>Date de Création</small></th>
            						    	</tr>
            						  	</thead>
            						  	<tbody>
            						  	    <?php 
											  if(!empty($allertrc)){ 
												  $totaldete =0;
												  $totalreg=0;
												  $totalrest=0;
											?>
            						  	        <?php foreach($allertrc as $value){ ?>
                						  	        <?php if($value['dette_restante'] != 0){ 
                						  	            $datedbb = date('Y-m-d',strtotime($value['date_creation_doc']));
                						  	            $datenowb = date('Y-m-d');

														  $debut = strtotime($datedbb);
														  $fin = strtotime($datenowb);
														  $dif = ceil(abs($fin - $debut) / 86400);
                                                        	$delais = $dif <= $value['delais_reg_doc'] ?'reste : '. ($value['delais_reg_doc'] - $dif).' jr(s)':'passé : '.($dif - $value['delais_reg_doc']).' jr(s)';
                                                        
														$totaldette = empty($value['pt_net_document'])?$value['pt_ttc_document']:$value['pt_net_document'];
														$totaldete+=$totaldette;
														$totalreg+=$value['dette_regler'];
														$totalrest+=$value['dette_restante'];
													  ?>
                        						    	<tr>
                        							      	<th scope="row"><?= $value['nom_cli'] ?></th>
                        							      	<td><small><?= $value['nom_emp'] ?></small></td>
                        							      	<td><small><?= $value['code_document'] ?></small></td>
                        							      	<td><small><?= $value['nom_document'] ?></small></td>
                        							      	<th><?= numberformat($totaldette) ?></th>
															  <th><?= numberformat($value['dette_regler']) ?></th>
            						      		            <th><?= numberformat($value['dette_restante']) ?></th>
                        							      	<td><?= $value['delais_reg_doc'] ?></td>
                        							      	<th class="text-danger"><?= $delais ?></th>
                        							      	<td><small><?= dateformat($value['date_creation_doc']); ?></small></td>
                        						    	</tr>
                        						    <?php } ?>
                    						    <?php } ?>
												<tr>
													<th colspan="4">Totaux</th>
													<th><b><?= numberformat($totaldete); ?></b></th>
													<th><b><?= numberformat($totalreg); ?></b></th>
													<th><b><?= numberformat($totalrest); ?></b></th>
													<th colspan="3"></th>
												</tr>
            						    	<?php } ?>
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
    function imprimer(divName) {
          var printContents = document.getElementById(divName).innerHTML;    
       var originalContents = document.body.innerHTML;      
       document.body.innerHTML = printContents;     
       window.print();     
       document.body.innerHTML = originalContents;
    }
</script>