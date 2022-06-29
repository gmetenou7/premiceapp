<?php if($this->session->has_userdata('success')):?> 
    <div class="alert alert-success fade show" role="alert">
        <div class="alert-icon"><i class="flaticon2-checkmark"></i></div>
        <div class="alert-text"><?php echo $this->session->flashdata('success');?></div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div>
<?php endif ?>

<?php if($this->session->has_userdata('error')):?> 
    <div class="alert alert-danger fade show" role="alert">
        <div class="alert-icon"><i class="flaticon2-cross"></i></div>
        <div class="alert-text"><?php echo $this->session->flashdata('error');?></div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div>
<?php endif ?>

<?php if($this->session->has_userdata('info')):?> 
    <div class="alert alert-info fade show" role="alert">
        <div class="alert-icon"><i class="flaticon2-information"></i></div>
        <div class="alert-text"><?php echo $this->session->flashdata('info');?></div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div>
<?php endif ?>

<?php if($this->session->has_userdata('warning')):?>
    <div class="alert alert-warning fade show" role="alert">
        <div class="alert-icon"><i class="flaticon2-warning"></i></div>
        <div class="alert-text"><?php echo $this->session->flashdata('warning');?></div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div> 
<?php endif ?>