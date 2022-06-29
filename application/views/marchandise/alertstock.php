
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
                                        <h3 class="kt-portlet__head-title">Alert du stock</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-section">
                                        <?php if(!empty($critique)){?>
                                            <div class="kt-section__info">
                                                <h4>
                                                    <u><b>CRITIQUE DE STOCK</b></u>
                                                    <a href="<?php echo base_url('printalertstock/critique')?>" target="_blank" class="btn btn-light btn-elevate-hover btn-circle btn-icon"><i class="fa fa-print"></i></a>
                                                </h4>
                                                <form class="row g-3" method="post" target="_blank">
                                                  <div class="col-auto">
                                                    <label for="inputPassword2" class="visually-hidden">Famille</label>
                                                    <select class="form-control" id="perfam" name="perfam">
                                                        <option selected disabled> choisi la famille</option>
                                                        <?php if(!empty($famille)){?>
                                                            <?php foreach($famille as $fam){?>
                                                                <?= '<option value='.$fam['matricule_fam'].'>'.$fam['nom_fam'].'</option>' ?>
                                                            <?php } ?>
                                                        <?php }?>
                                                    </select>
                                                    <?php echo form_error('perfam', '<div class="text-danger">', '</div>'); ?>
                                                  </div>
                                                  <div class="col-auto">
                                                    <hr>
                                                    <button type="submit" name="printperfam" class="btn btn-primary mb-3">Imprimer</button>
                                                  </div>
                                                </form>
                                            </div>

                                            <div class="table-responsive" style="height: 300px; width: 100%; overflow:auto;">
                                                <table class="table">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Code à bar</th>
                                                            <th>Désignation</th>
                                                            <th>Référence</th>
                                                            <th>Qté total en stock</th>
                                                            <th>Critique</th>
                                                            <th>Qantité manquante</th>
                                                            <th>Famille</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($critique as $key => $value) { ?>
                                                            <?php 
                                                                echo '
                                                                    <tr>
                                                                        <td>'.$value['codebar'].'</td>
                                                                        <td>'.$value['designation'].'</td>
                                                                        <td>'.$value['reference'].'</td>
                                                                        <td>'.$value['qtetotal'].'</td>
                                                                        <td>'.$value['critique'].'</td>
                                                                        <td>'.$value['manque'].'</td>
                                                                        <td>'.$value['famille'].'</td>
                                                                    </tr>
                                                                ';
                                                            ?>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } ?>
                                        
                                        <?php if(!empty($peremption)){?>
                                            <div class="kt-section__info">
                                                <h4>
                                                    <u><b>ALERT PEREMPTION</b></u>
                                                    <a href="<?php echo base_url('printalertstock/premption')?>" target="_blank" class="btn btn-light btn-elevate-hover btn-circle btn-icon"><i class="fa fa-print"></i></a>
                                                </h4>
                                            </div>
                                            <div class="table-responsive" style="height: 300px; width: 100%; overflow:auto;">
                                            <?php foreach ($peremption as $key => $value) { ?>
                                                <ul class="list-group">
                                                    <li class="list-group-item list-group-item-warning">l'article <b><?php echo $value['designation'] ?></b> vas se périmé dans <b><?php echo $value['jours'] ?> jour(s)</b> quatité total restante: <b><?php echo $value['qtetotal'] ?></b></li>
                                                </ul>
                                            <?php } ?>
                                            </div>
                                        <?php } ?>

                                        <?php if(!empty($perime)){?>

                                            <div class="kt-section__info">
                                                <h4>
                                                    <u><b>ARTICLE PERIME</b></u>
                                                    <a href="<?php echo base_url('printalertstock/perime')?>" target="_blank" class="btn btn-light btn-elevate-hover btn-circle btn-icon"><i class="fa fa-print"></i></a>
                                                </h4>
                                            </div>
                                            <div class="table-responsive" style="height: 300px; width: 100%; overflow:auto;">
                                            <?php foreach ($perime as $key => $value) { ?>
                                                <?php if($value['date_premption'] != "0000-00-00") { ?>
                                                    <ul class="list-group">
                                                        <li class="list-group-item list-group-item-danger">l'article <b><?php echo $value['designation'] ?></b> a commencé être périmé il y'a. <b><?php echo $value['jours'] ?> jour(s)</b> quatité total restante: <b><?php echo $value['qtetotal'] ?></b></li>
                                                    </ul>
                                                <?php } ?>
                                            <?php } ?>
                                            </div>
                                        <?php } ?>
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
