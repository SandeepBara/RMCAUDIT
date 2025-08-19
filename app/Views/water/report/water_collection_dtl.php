<?php

//print_var($transaction);

$demand = array_filter($transaction['result'],function($val){
                    //print_var($val['transaction_type']);
                    return('Demand Collection'==trim($val['transaction_type']));
                    });
$new_connection=array_filter($transaction['result'],function($val){
    return(in_array(trim($val['transaction_type']),['New Connection','Site Inspection','Penlaty Instalment']));
    });

//print_var(count($transaction['result']));

?>



<?php
	//session_start();

 echo $this->include('layout_vertical/popup_header');
 
  

?>
<!--<style type="text/css">
  .row{ font-weight: bold; color: #000000; }
  .label{ font-weight: bold; color: #000000; }
  .table td{font-weight: bold; color: #000000; font-size: 12px;}
  .bolder{font-weight: bold;}
  
</style>-->

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
                if(!empty($new_connection))
                {
                ?>
                <div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"> New connection Transaction</h3>
					</div>
					<div class="panel-body table-responsive">
						<table class="table table-bordered table-responsive">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>S. No.</th>
									<th>Collection Type</th>
									<th>Application No. </th>
                                    <th>Date</th>
									<th>Amount</th>
									<th>Payment Mode</th>                                    
								</tr>
							
							</thead>
							<tbody>
								<?php
                				//print_r($site_inspection_details);
								if($transaction)
								{
                                    $i=0;
                                    $sum=0;
                                    foreach($new_connection as $val)
                                    {
                                        $sum+=$val['paid_amount'];
                                        ?>
                                        <tr>
                                            <td><?=++$i?></td>
                                            <td class="bolder"><?=$val['transaction_type']?></td>
                                            <td class="bolder"><?=trim($val['transaction_type'])=='Demand Collection'? $val['consumer_no'] : $val['application_no']?></td>
                                            <td class="bolder"><?=$val['created_on']?></td>
                                            <td class="bolder"><?=$val['paid_amount']?></td>
                                            <td class="bolder"><?=$val['payment_mode']?></td>
                                        </tr>
                                        
                                        <?php
                                    }
                                    ?>
                                        <tr>
                                            <td colspan="4" class='text-center bolder'><b>Total</b></td>
                                            <td class="bolder"><?= sprintf("%.2f",$sum)//$sum?></td>
                                            
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
                if(!empty($demand))
                {
                ?>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"> Demand Transaction Details</h3>
					</div>
					<div class="panel-body table-responsive">
						<table class="table table-bordered table-responsive">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>S. No.</th>
									<th>Collection Type</th>
									<th>Consumer. No. </th>
                                    <th>Date</th>
									<th>Amount</th>
									<th>Payment Mode</th>                                    
								</tr>
							
							</thead>
							<tbody>
								<?php
                				//print_r($site_inspection_details);
								if($transaction)
								{
                                    $i=0;
                                    $sum=0;
                                    foreach($demand as $val)
                                    {
                                        $sum+=$val['paid_amount'];
                                        ?>
                                        <tr>
                                            <td><?=++$i?></td>
                                            <td class="bolder"><?=$val['transaction_type']?></td>
                                            <td class="bolder"><?=trim($val['transaction_type'])=='Demand Collection'? $val['consumer_no'] : $val['application_no']?></td>
                                            <td class="bolder"><?=$val['created_on']?></td>
                                            <td class="bolder"><?=$val['paid_amount']?></td>
                                            <td class="bolder"><?=$val['payment_mode']?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                        <tr>
                                            <td colspan="4" class='text-center bolder'><b>Total</b></td>
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
<?php 		
		//echo $this->include('layout_vertical/footer');		
  		
 ?>
