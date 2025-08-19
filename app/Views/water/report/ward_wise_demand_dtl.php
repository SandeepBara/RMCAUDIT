
<?=$this->include('layout_vertical/popup_header');?>
<style>
    #footer{
        display: none;
    }
</style>   
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Team Summary</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <?php
            if(!empty($summary))
            {
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Consumer Details</h3>
                </div>
                <div class="panel-body table-responsive">
                    <form method="post">
                        <button class="btn btn-primary" onclick="" type="submit" id="Export" name="Export" value="Export" >Export</button>
                    </form>
                    <table class="table table-striped table-bordered table-responsive">
                        <thead class="bg-trans-dark text-dark">
                            <tr>
                                <th>SL. No.</th>
                                <th>Ward. No. </th>
                                <th>Consumer. No. </th>
                                <th>Owner Name</th>
                                <th>Father Name</th>
                                <th>Mobile No</th> 
                                <th>Connection Type</th> 
                                <th>View</th>                                
                            </tr>
                        
                        </thead>
                        <tbody>
                            <?php
                            if($summary)
                            {
                                $i=0;
                                $sum=0; 
                                foreach($summary as $val)
                                {
                                    $sum+=1;
                                    ?>
                                    <tr>
                                        <td><?=++$i?></td>
                                        <td class="bolder"><?=$val['ward_no']?></td>
                                        <td class="bolder"><?=$val['consumer_no']?></td>
                                        <td class="bolder"><?=$val['applicant_name']?></td>
                                        <td class="bolder"><?=$val['father_name']?></td>
                                        <td class="bolder"><?=$val['mobile_no']?></td>
                                        <td class="bolder"><?=$val['connection_type']?></td>
                                        <td class="bolder" ><button class="btn btn-primary" onclick="openWindowcus('<?=md5($val['id'])?>');">View</button></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                    <tr>
                                        <td colspan="3" class='text-center bolder'><b>Total</b></td>
                                        <td class="bolder"><?=$sum;?></td>                                        
                                    </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
            }
        ?>       
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?='';//$this->include('layout_vertical/footer');?>
<script type="text/javascript">
    function openWindowcus(id)
    {
        if(id!='')
        {
            window.opener.location.assign("<?=base_url()?>/WaterViewConsumerDueDetails/index/"+id);            
            window.close();
        }
    }
</script>
