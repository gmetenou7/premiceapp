</div>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
            <!-- begin:: Content -->

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="kt-section">
                            <h3 class="kt-section__title">Rapport des dettes</h3>
                            <div class="kt-section__info">Utilise les paramètres suivants pour voir et imprimer le rapport des dettes</div>
                            <div class="kt-section__content kt-section__content--border">
                                <p><b>Total dettes jusqu'au <?php setlocale(LC_TIME, 'fr_FR'); date_default_timezone_set('Africa/Douala'); echo utf8_encode(strftime('%A %d %B %Y, %H:%M'));?></b>: <h4><?php $dettes = !empty($alldettes)? $alldettes: 0; echo  number_format($dettes, 2, '.', ' ');?></h4></p>
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                        <?php $this->load->view('parts/message');?>
                                        <form method="post" action="<?php echo base_url('rapportdette');?>"> <!--target="_blank"-->
                                        <div class="form-group row">
                                            <div class="col-lg-10 col-md-9 col-sm-12">
                                                <span class="form-text">PERIODE</span>
                                                <div class="input-daterange input-group" id="kt_datepicker_5">
                                                    <input type="text" placeholder="debut" class="form-control" name="start" value="<?php echo set_value('start'); ?>"/>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="fin" name="end" value="<?php echo set_value('end'); ?>" />
                                                </div>
                                                <?php echo form_error('start', '<span class="text-danger">', '</span> /'); ?> 
                                                <?php echo form_error('end', '<span class="text-danger">', '</span>'); ?>
                                            </div>
                                            <div class="col-lg-2 ml-lg-auto">
                                                <span class="form-text text-muted">...</span>
                                                <button type="submit" name="dettes_btn" id="dettes_btn" class="btn btn-success">Voir</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                    <hr>
                                    <div class="kt-form__actions">
                                        <form method="post" action="<?php echo base_url('rapportdette');?>">
                                        <div class="row">
                                            <div class="col-lg-5 ml-lg-auto">
                                                <span class="form-text text-muted">Client</span>
                                                <select class="form-control kt-select2" id="kt_select2_1" name="client">
                                                    <?php if(!empty($client)){?>
                                                        <?php echo '<option></option>'; ?>
                                                        <?php foreach($client as $value){?>
                                                            <?php if($value['matricule_cli'] == set_value('client')){?>
                                                                <?php echo '<option value="'.set_value('client').'" selected>'.$value['nom_cli'].'</option>'; ?>
                                                            <?php }else{?>
                                                                <?php echo '<option value="'.$value['matricule_cli'].'">'.$value['nom_cli'].'</option>'; ?>
                                                            <?php }?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <?php echo form_error('client', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                            <div class="col-lg-5 ml-lg-auto">
                                                <span class="form-text text-muted">Période</span>
                                                <div class="form-group row">
                                                    <div class="col-lg-12 col-md-9 col-sm-12">
                                                        <div class="input-daterange input-group" id="kt_datepicker_1">
                                                            <input type="text" class="form-control" name="debut" value="<?php echo set_value('debut'); ?>" />
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control" name="fin" value="<?php echo set_value('fin'); ?>"  />
                                                        </div>
                                                        <?php echo form_error('debut', '<span class="text-danger">', '</span> /'); ?>
                                                        <?php echo form_error('fin', '<span class="text-danger">', '</span>'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 ml-lg-auto">
                                                <span class="form-text text-muted">...</span>
                                                <button type="submit" name="btn_dette_cli"  class="btn btn-warning">OK</button>
                                            </div>
                                        </div>
                                        </form>
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

