<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Apply Trade Licence</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form method="post" action="<?=base_url('tradeapplylicence_excel/index');?>" enctype="multipart/form-data">
            
            <?php
            if(isset($validation)){
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-10 text-danger">
                        <?php 
                        foreach ($validation as $errMsg) {
                            echo $errMsg; echo ".<br />";
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">                   
                    <h3 class="panel-title">Apply Trade Licence</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-1">Apply For</label>
                        <div class="col-md-4 pad-btm">
                            <input type="file" id="profile_image" name="profile_image"  />
               
                        </div>
                        </label>
                        <div class="col-md-3 pad-btm">
                           <input type="submit" name="submit" value="Upload" />
                    </div>
                 </div>
            </div>
       
        
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>

