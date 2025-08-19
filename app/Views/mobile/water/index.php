<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">       
        <div class="panel">
     
        <div class="row-fluid">
            <?php
            if($user_type_mstr_id==13)
            {
               ?>
                    <div class="col-xs-6">
                        <a href="<?php echo base_url('WaterMobileIndex/search_consumer') ?>">
                            <div class="panel panel-mint panel-colorful media middle pad-all">
                                <div class="media-left">
                                    <i class="demo-pli-camera-2 icon-2x"></i>
                                </div>
                                <div class="media-body">
                                    <p class="mar-no">Site Inspection</p>
                                </div>
                            </div>
                    </a>
                    </div> 
                <?php 
            }
            ?>
              <?php
            if($user_type_mstr_id==5)
            {
               ?>
                    <div class="col-xs-6">
                        <a href="<?php echo base_url('WaterApplyNewConnection') ?>">
                            <div class="panel panel-mint panel-colorful media middle pad-all">
                                <div class="media-left">
                                    <i class="demo-pli-camera-2 icon-2x"></i>
                                </div>
                                <div class="media-body">
                                    <p class="mar-no">Apply Water Connection</p>
                                </div>
                            </div>
                        </a>
                    </div> 
                <?php 
            }
            ?>
            
      </div>
    </div>

        
     
    </div>
    </div>
    <!--End page content-->
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
