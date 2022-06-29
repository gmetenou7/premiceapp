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
                                <?php $this->load->view('parts/message');?>
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                        <div class="row">
                                            <div class="col-lg-5 ml-lg-auto">
                                                <span class="form-text text-muted">Banque</span>
                                                <select class="form-control kt-select2" id="kt_select2_1" name="param">
                                                    <option value="AK">Alaska</option>
                                                    <option value="HI">Hawaii</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-5 ml-lg-auto">
                                                <span class="form-text text-muted">Période</span>
                                                <select class="form-control kt-select2" id="kt_select2_2" name="param">
                                                    <option value="AK">Alaska</option>
                                                    <option value="HI">Hawaii</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-2 ml-lg-auto">
                                                <span class="form-text text-muted">...</span>
                                                <button type="submit" class="btn btn-dark">OK</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                            <th>Situation de Caisse</th>
                                            <th>
                                                Prix Total:
                                            </th>
                                            <th>
                                                Période: 
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
                                                <th>Désignation</th>
                                                <th>Quantité</th>
                                                <th>Date</th>
                                                <th>Prix Total(HT)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>1</th>
                                                <td>Jhon</td>
                                                <td>@jhon</td>
                                                <td>Stone</td>
                                                <td>
                                                    <a href="#" class="btn btn-icon btn-circle btn-label-linkedin">
                                                        <i class="fa fa-print"></i>
                                                    </a> 
                                                    <a href="#" class="btn btn-icon btn-circle btn-label-linkedin">
                                                        <i class="fa fa-print"></i>
                                                    </a> 
                                                </td>
                                            </tr>
                                        </tbody>
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

