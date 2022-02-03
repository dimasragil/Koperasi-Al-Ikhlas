<?php if ($this->session->has_userdata('success_msg')){ ?>
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fa fa-check"></i> <?=$this->session->flashdata('success_msg');?> 
</div>
<?php } ?>

<?php if ($this->session->has_userdata('error_msg')){ ?>
<div class="alert alert-error alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fa fa-ban"></i> <?=strip_tags(str_replace('</p>', '', $this->session->flashdata('error_msg')));?> 
</div>
<?php } ?>

