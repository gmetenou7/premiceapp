</div>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
            <!-- begin:: Content -->

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="kt-section">
                            <h3 class="kt-section__title">Situation De Caisse</h3>
                            <div class="kt-section__info">Utilise les paramètres suivants pour voir et imprimer une situation de caisse</div>
                            <div class="kt-section__content kt-section__content--border">
                                <?php $this->load->view('parts/message'); ?>
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                        <form method="post" action="<?php echo base_url('situationcaisse');?>">
                                        <div class="row">
                                            <div class="col-lg-6 ml-lg-auto">
                                                <span class="form-text text-muted">Caisse</span>
                                                <select class="form-control kt-select2 caisse"  name="caisse">
                                                    <?php if(!empty($caisses)){?>
                                                        <?php 
                                                            foreach($caisses as $value){
                                                                if(!empty($value['code_agence'])){
                                                        ?>
                                                                    <?php if(set_value('caisse') == $value['code_caisse']){?>
                                                                        <?php echo '<option value="'.set_value('caisse').'" selected>'.$value['libelle_caisse'].'</option>'; ?>
                                                                    <?php }else{?>
                                                                        <?php echo '<option value="'.$value['code_caisse'].'">'.$value['libelle_caisse'].'</option>'; ?>
                                                                    <?php }?>
                                                        <?php 
                                                                } 
                                                            }
                                                        ?>
                                                    <?php } ?>  
                                                    <?php if(!empty($caisse)){?>
                                                        <?php if(set_value('caisse') == $caisse['code_caisse']){?>
                                                            <?php echo '<option value="'.set_value('caisse').'" selected>'.$caisse['libelle_caisse'].'</option>'; ?>
                                                        <?php }else{?>
                                                            <?php echo '<option value="'.$caisse['code_caisse'].'">'.$caisse['libelle_caisse'].'</option>'; ?>
                                                        <?php }?>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error('caisse', '<span class="text-danger">', '</span>'); ?>
                                            </div>
                                            <div class="col-lg-6 ml-lg-auto">
                                                <span class="form-text text-muted">Période</span>
                                                <div class="form-group row">
                                                    <div class="col-lg-12 col-md-9 col-sm-12">
                                                        <div class="input-daterange input-group" id="kt_datepicker_5">
                                                            <input type="text" class="form-control" name="start" value="<?php echo set_value('start'); ?>" />
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control" name="end" value="<?php echo set_value('end'); ?>" />
                                                        </div>
                                                        <?php echo form_error('start', '<span class="text-danger">', '</span> /'); ?> 
                                                        <?php echo form_error('end', '<span class="text-danger">', '</span>'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 ml-lg-auto">
                                                <?php if ((session('users')['nom_serv'] == "" && session('users')['nom_ag'] != "") || (session('users')['nom_ag'] == "" && session('users')['nom_serv'] == "")){ ?>
                                                    <button type="submit" name="btn_view_situ_caisse" class="btn btn-dark">Voir</button>
                                                    <button type="submit" name="btn_situ_caisse" class="btn btn-dark" target="_blank">Print</button>
                                                <?php }else{ ?>
                                                    <button type="submit" name="btn_view_situ_caisse" class="btn btn-dark">Voir</button>
                                                <?php } ?>
                                            </div>
                                        <div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-section__content kt-section__content--border">
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                    <?php 
                                        if(set_value('start')){
                                            $start = date('d-m-Y', strtotime(set_value('start')));
                                    ?>
                                        <?php echo 'du '.$start.' 00:00:00 au'; ?>
                                    <?php } ?>
                                    <?php 
                                        if(set_value('end')){
                                        $end = date('d-m-Y', strtotime(set_value('end')));
                                    ?>
                                        <?php echo $end.' 23:59:59'; ?>
                                    <?php } ?>
                                    <div class="table-responsive">
                                    <table class="table table-striped" border="1" style="border-collapse: collapse;">
                                        <tr>
                                            <th>Date</th>
                                            <th>Numero</th>
                                            <th>Client</th>
                                            <th>Libelle</th>
                                            <th>Débit</th>
                                            <th>Credit</th>
                                        </tr>
                                        <tr>
                                            <?php if(!empty($agence)){?>
                                                <td colspan="4">Rapport de caisse <b><?php echo strtoupper($agence['nom_ag']); ?></b></td>
                                            <?php }?>
                                            
                                            <th><?php $result = !empty($situavanperiod)?$situavanperiod:0; echo number_format($result, 2, '.', ' '); ?></th>
                                            <td></td>
                                        </tr>
                                        <?php  if(!empty($valdocrcarray)){ ?>
                                            <tr>
                                                <td colspan="4">Type de mouvement: <b>REGLEMENT CLIENT</b> <span class="text-danger">(Nb: les dettes ne sont la qu'a titre historique)</span></td>
                                                <th><?php  $totalc =!empty($totalrcarray)?$totalrcarray:0; echo $totalc; ?></th>
                                                <td></td>
                                            </tr>
                                            <?php foreach ($valdocrcarray as $key => $value) {?>
                                                <tr>
                                                    <td><?php echo $value['date']; ?></td>
                                                    <td><?php echo $value['codedoc']; ?></td>
                                                    <td><?php echo $value['client']; ?></td>
                                                    <td><?php echo $value['nomdoc']; ?></td>
                                                    <td><?php echo number_format($value['montant'], 2, ',', ' '); ?></td>
                                                    <td></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php if(!empty($valdocrtarray)){ ?>
                                            <tr>
                                                <td colspan="4">Type de mouvement: <b>REGLEMENT TICKET</b></td>
                                                <tH><?php $totalt = !empty($totalsrtarray)?$totalsrtarray:0; echo number_format($totalt, 2, '.', ' '); ?></tH>
                                                <td></td>
                                            </tr>
                                            <?php foreach ($valdocrtarray as $key => $value) { ?>
                                                <tr>
                                                    <td><?php echo $value['date']; ?></td>
                                                    <td><?php echo $value['codedoc']; ?></td>
                                                    <td><?php echo $value['client']; ?></td>
                                                    <td><?php echo $value['nomdoc']; ?></td>
                                                    <td><?php echo number_format($value['montant'], 2, '.', ' '); ?></td>
                                                    <td></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                        
                                        <?php if(!empty($valdocbrarray)){ ?>
                                            <tr>
                                                <td colspan="4">Type de mouvement: <b>RETOUR ARTICLE</b></td>
                                                <tH><?php $totalbr = !empty($totalsbrarray)?$totalsbrarray:0; echo number_format($totalbr, 2, '.', ' '); ?></tH>
                                                <td></td>
                                            </tr>
                                            <?php foreach ($valdocbrarray as $key => $value) { ?>
                                                <tr>
                                                    <td><?php echo $value['date']; ?></td>
                                                    <td><?php echo $value['codedoc']; ?></td>
                                                    <td><?php echo $value['client']; ?></td>
                                                    <td><?php echo $value['nomdoc']; ?></td>
                                                    <td><?php echo number_format($value['montant'], 2, '.', ' '); ?></td>
                                                    <td></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php if(!empty($valdocscarray)){ ?>
                                            <tr>
                                                <td colspan="4">Type de mouvement: <b>SORTIE DE CAISSE</b></td>
                                                <tH></tH>
                                                <th><?php $totals = !empty($totalsscarray)?$totalsscarray:0; echo number_format($totals, 2, '.', ' '); ?></th>
                                            </tr>
                                            <?php foreach ($valdocscarray as $key => $value) {?>
                                                <tr>
                                                    <td><?php echo $value['date']; ?></td>
                                                    <td><?php echo $value['codedoc']; ?></td>
                                                    <td><?php echo $value['client']; ?></td>
                                                    <td><?php echo $value['nomdoc'] .' <br><b>'.$value['motif'].'</b>'; ?></td>
                                                    <td></td>
                                                    <td><?php echo number_format($value['montant'], 2, '.', ' '); ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                        
                                        <?php if(!empty($valdocecarray)){ ?>
                                            <tr>
                                                <td colspan="4">Type de mouvement: <b>ENCAISSEMENT</b></td>
                                                <th><?php $totale = !empty($totalsecarray)?$totalsecarray:0; echo number_format($totale, 2, '.', ' '); ?></th>
                                                <tH></tH>
                                            </tr>
                                            <?php foreach ($valdocecarray as $key => $value) {?>
                                                <tr>
                                                    <td><?php echo $value['date']; ?></td>
                                                    <td><?php echo $value['codedoc']; ?></td>
                                                    <td><?php echo $value['client']; ?></td>
                                                    <td><?php echo $value['nomdoc']; ?></td>
                                                    <td><?php echo number_format($value['montant'], 2, '.', ' '); ?></td>
                                                    <td></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                        
                                            <tr>
                                                <td colspan="4">Total général des mouvements de la période</td>
                                                    
                                                <?php 
                                                    $totalc1 =!empty($totalrcarray)?$totalrcarray:0;
                                                    $totalt1 = !empty($totalsrtarray)?$totalsrtarray:0;
                                                    $totalbr1 = !empty($totalsbrarray)?$totalsbrarray:0;
                                                    $totals1 = !empty($totalsscarray)?$totalsscarray:0;
                                                    $result1 = !empty($situavanperiod)?$situavanperiod:0;
                                                    $totale1 = !empty($totalsecarray)?$totalsecarray:0;
                                                    
                                                    
                                                    $total1 = ($totalt1 + $totalbr1 + $totale1);
                                                    $total2 =($total1 - $totals1);
                                                    $total3 = ($result1 + $total2);
                                                    
                                                    echo '<th>'.number_format( $total1, 2, '.', ' ').' XAF</th>';
                                                    echo '<th>'.number_format($totals1, 2, '.', ' ').' XAF</th>';
                                                ?>
                                            </tr>
                                            <tr>
                                                <td colspan="4">Solde de la période</td>
                                                <th colspan="2"><?php echo number_format($total2, 2, '.', ' ').' XAF'; ?></td>>
                                            </tr>
                                            <tr>
                                                <td colspan="4">Solde Total en caisse</td>
                                                    <th colspan="2"><?php echo number_format($total3, 2, '.', ' ').' XAF';  ?></th>
                                            </tr>
                                        </table>
                                        </div>
                                    </div>
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

