<?= $this->include('layout_home/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title"> Apply for <?=ucfirst(strtolower($application_type['application_type']));?> </h5>
            </div>
            <div class="panel-body">  
                 <form method="POST"> 
                    <?php if(isset($validation)){ $validation->listErrors();  } ?>
                    <?php if($application_type["id"]>1){?>            
                        <div class="row">
                            <label class="col-md-2 text-bold">Search License</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="text" id="Searchlicense" name="Searchlicense" value="<?php echo isset($Searchlicense)?$Searchlicense:'';?>" class="form-control" />
                            </div>

                            <div class="col-md-3 has-success pad-btm">
                               <input type="Submit" id="btn_search" class="btn btn-primary" value="SEARCH" />
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-12 has-success pad-btm text-info">
                                <?php echo $msg; ?>
                            </div>
                        </div>
                    <?php }?>                      
                </form> 
            </div>
        </div>

        
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer');?>
<!--DataTables [ OPTIONAL ]-->
