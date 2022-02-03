<section class="content-header">
      <h1>
        Units
        <small>Satuan Barang</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-user"></i></a></li>
        <li class="active">Units</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-header">
            <h3 class="box-tittle"><?=ucfirst($page)?> Units</h3>
            <div class="pull-right">
            <a href="<?=site_url('unit')?>" class="btn btn-warning btn-flat">
                <i class="fa fa-undo"></i> Back
            </a>
        
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                <?php //echo validation_errors(); ?>
                    <form action="<?=site_url('unit/process')?>" method="post">
                        <div class="form-grup">
                            <label> Unit Name *</label>
                            <input type="hidden" name="id" value="<?=$row->unit_id?>">
                            <input type="text" name="unit_name" value="<?=$row->name?>" class="form-control" required>
                        </div>
                        <br>
                        <div class="form-grup">
                            <button type="submit" name="<?=$page?>" class="btn btn-success btn-flat">
                                <i class="fa fa-paper-plane"></i> Save</button>
                            <button type="Reset" class="btn btn-flat">Reset</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
      </div>
</section>