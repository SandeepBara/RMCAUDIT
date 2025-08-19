
<?=$this->include('layout_vertical/popup_header');?>
<?php

$Fixed = array_filter($transaction,function($val){
                    //print_var($val['transaction_type']);
                    return('Fixed'==trim($val['connection_type']));
                    });
$Meter=array_filter($transaction,function($val){
    return(in_array(trim($val['connection_type']),['Meter','Metered']));
    });
?>
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
            if(!empty($Fixed))
            {
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Fixed Demand</h3>
                </div>
                <div class="panel-body table-responsive">
                    <table class="table table-bordered table-responsive">
                        <thead class="bg-trans-dark text-dark">
                            <tr>
                                <th>SL. No.</th>
                                <th>Consumer. No. </th>
                                <th>Amount</th>
                                <th>Connection Type</th>
                                <th>Demand From</th>  
                                <th>Demand Upto</th>   
                                <th>Date</th>                                
                            </tr>
                        
                        </thead>
                        <tbody>
                            <?php
                            //print_r($site_inspection_details);
                            if($transaction)
                            {
                                $i=0;
                                $sum=0;
                                foreach($Fixed as $val)
                                {
                                    $sum+=$val['amount'];
                                    ?>
                                    <tr>
                                        <td><?=++$i?></td>
                                        <td class="bolder"><?=$val['consumer_no']?></td>
                                        <td class="bolder"><?=$val['amount']?></td>
                                        <td class="bolder"><?=$val['connection_type']?></td>
                                        <td class="bolder"><?=$val['demand_from']?></td>
                                        <td class="bolder"><?=$val['demand_upto']?></td>
                                        <td class="bolder"><?=$val['generation_date']?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                    <tr>
                                        <td colspan="2" class='text-center bolder'><b>Total</b></td>
                                        <td class="bolder"><?=sprintf("%.2f",$sum)?></td>
                                        
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
            if(!empty($Meter))
            {
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Meter Demand</h3>
                </div>
                <div class="panel-body table-responsive">
                    <table class="table table-bordered table-responsive">
                        <thead class="bg-trans-dark text-dark">
                            <tr>
                                <th>SL. No.</th>
                                <th>Consumer. No. </th>
                                <th>Amount</th>
                                <th>Connection Type</th>
                                <th>Demand From</th>  
                                <th>Demand Upto</th> 
                                <th>Initial Reading</th>  
                                <th>Final Reading</th>   
                                <th>Date</th>
                                <th>Meter Image</th>                                          
                            </tr>                        
                        </thead>
                        <tbody>
                            <?php
                            //print_r($site_inspection_details);
                            if($transaction)
                            {
                                $i=0;
                                $sum=0;
                                foreach($Meter as $val)
                                {
                                    $sum+=$val['amount'];
                                    ?>
                                    <tr>
                                        <td><?=++$i?></td>
                                        <td class="bolder"><?=$val['consumer_no']?></td>
                                        <td class="bolder"><?=$val['amount']?></td>
                                        <td class="bolder"><?=$val['connection_type']?></td>
                                        <td class="bolder"><?=$val['demand_from']?></td>
                                        <td class="bolder"><?=$val['demand_upto']?></td>
                                        <td class="bolder"><?=$val['initial_reading']?></td>
                                        <td class="bolder"><?=$val['final_reading']?></td>
                                        <td class="bolder"><?=$val['generation_date']?></td>
                                        <td class="bolder"><div style="height:50px;width: 50px;"><a target = "_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$val['file_name']?>"><img style="height:100%;width: 100%;" src="<?=base_url();?>/getImageLink.php?path=<?=$val['file_name']?>" alt="No File"/></a></div></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                    <tr>
                                        <td colspan="2" class='text-center bolder'><b>Total</b></td>
                                        <td class="bolder"><?=sprintf("%.2f",$sum)?></td>
                                        
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
<?=''//$this->include('layout_vertical/footer');?>
