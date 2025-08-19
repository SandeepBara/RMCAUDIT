<?=$this->include('layout_vertical/header');?>
<style>
@media print {
	#content-container {padding-top: 0px;}
	#print_watermark {
        background-image:url(<?=base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important;
		background-repeat:no-repeat !important;
		background-position:center !important;
        -webkit-print-color-adjust: exact;
    }
}
#print_watermark{
	background-color:#FFFFFF;
	background-image:url(<?=base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important;
	background-repeat:no-repeat;
	background-position:center;
}
#tbl tr th {
  text-align: center;
}

</style>
<!--CONTENT CONTAINER-->

<div id="content-container">
	<div id="page-head">
		<div id="page-title">
			<!--<h1 class="page-header text-overflow">Designation List</h1>//-->
		</div>
		<ol class="breadcrumb">
			<li><a href="#"><i class="demo-pli-home"></i></a></li>
			<li><a href="#">Government SAF</a></li>
			<li class="active"> Print Self Assessment Form Demand</li>
		</ol>
	</div>
	<div id="page-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel">
					<div class="panel panel-dark">
						<div class="panel-body" id="print_watermark">
							<div class="col-sm-1"></div>
							<div class="col-sm-10" style="text-align: center;">
								<img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png');?>'>
							</div>
              <a  class="noprint" href="<?php echo base_url('safDemand/saf_demand_details/'.md5( $id));?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>
              Back</a>
             <br><br>
							<div class="col-sm-1 noprint text-right">
								<button class="btn btn-mint btn-icon" onclick="print()"><i class="demo-pli-printer icon-lg"></i></button>
							</div>
							<div class="col-sm-12" style="text-align: center; text-transform: uppercase; font-size: 33px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 60px;">
							Ranchi Municipal Corporation
							</div>
							<table width="99%" border="0" align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
								<tbody>
									<tr>
										<td height="71" colspan="4" align="center">
											<div style="width: 50%; padding: 8px; height: auto; border: #000000 solid 2px; font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: bold">Govt. Building Self-Assessment Form Demand </div>
										</td>
									</tr>


									<tr>
										<td colspan="3">Acknowledgement No. : &nbsp;<b><?= $ac; ?></b></td>
										<td >Date : &nbsp;<b><?php echo  date("d-m-Y"); ?></b></td>
									</tr>
									<tr>
										<td colspan="3">Department / Section : Revenue Section<br>
									Account Description : Holding Tax &amp; Others
                   </td>
										<td>
											<div >Ward No : &nbsp;<b><?= $ward_no; ?></b> </div>
											<div >Holding No. : <b><?=($holding_no!="")?$holding_no:"N/A";?></b></div>
										</td>
									</tr>
                  <tr>
										<td colspan="3">Colony / Building Name  :  <b><?=($building_name!="")?$building_name:"N/A";?></b></td>
									</tr>
                  <tr>
                    <td colspan="3">Address  :  <b><?=($address!="")?$address:"N/A";?></b></td>
                  </tr>
								</tbody>
							</table>
							<br>
							<br>
              <div class="row">
      <div class="table-responsive">
           <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; text-align:center;">

          <thead id="tbl">
            <tr>
              <th>SI No.</th>
              <th>Demand From</th>
              <th>Demand Upto</th>
              <th>Quarterly Tax(In Rs.)</th>
              <th>Demand(In Rs.)</th>
              <th>Already Paid(In Rs.)</th>
              <th>Total(In Rs.)</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if(isset($demand)){
              $sn = 0 ;
              for ($i=0;$i<sizeof($demand);$i++) {
                $sn++;
                $fy_upto = $upto_year;
                $qtr_upto = $quarter;
                if(sizeof($demand) ==$i+1) {
                  $fy_upto = $upto_year;
                  $qtr_upto = $quarter;
                } else {
                  if ($demand[$i+1]['qtr']==1) {
                    $qtr_upto = 4;
                    $fy_upto_arr = $demand[$i+1]['fy'];
                    $fy_upto_arr = explode('-', $fy_upto_arr);
                    $fy_upto = ($fy_upto_arr[0]-1)."-".($fy_upto_arr[1]-1);
                  } else {
                    $fy_upto = $demand[$i+1]['fy'];
                    $qtr_upto = $demand[$i+1]['qtr']-1;
                  }
                }
            ?>
            <tr>
              <td><?=$sn?></td>
              <td><?=$demand[$i]['financial_year']."/".$demand[$i]['qtrs'];?></td>
              <td><?=$fy_upto."/".$qtr_upto;?></td>
              <td><?=$demand[$i]['quarter'];?></td>
              <td><?=number_format($demand[$i]['sum'], 2, '.', '');?></td>
              <td>0</td>
              <td><?=number_format($demand[$i]['sum'], 2, '.', '');?></td>
            </tr>
            <?php
            $quartersum += $demand[$i]['quarter'];
            $demandsum += $demand[$i]['sum'];
                }
            }
            ?>
            <tr>
              <td colspan="3" class="text-right"><b>Total &nbsp;</b></td>
              <td><b><?=number_format($quartersum, 2, '.', '');?></b></td>
              <td><b><?=number_format($demandsum, 2, '.', '');?></b></td>
              <td><b>0</b></td>
              <td><b><?=number_format($demandsum, 2, '.', '');?></b></td>
             </tr>
             <tr>
              <td colspan="3"><b>Penalty</b></td>
              <td colspan="5"><b>Rs. <?=number_format($laf, 2, '.', '');?></b></td>
             </tr>
             <tr>
             <td colspan="3"><b>1% Interest</b></td>
              <?php if(isset($penalty)){
                    $sum=0;
                     for ($i=0;$i<sizeof($penalty);$i++) {?>
                    <?php
                    $demandamt=$penalty[$i]["amount"];
                    $fine_months=0;
                    $penaltyamt=0;
                    $from_quarter = $penalty[$i]["qtr"];
                    $from_date = $penalty[$i]["fy"];
                    $from_date = explode('-',$from_date);
                    $from_date = $from_date[0];
                    if($from_quarter=="1")
                    {
                      $month = "06";
                      $enddays = "30";
                    }
                    elseif($from_quarter=="2")
                    {
                      $month = "09";
                      $enddays = "30";
                    }
                    elseif($from_quarter=="3")
                    {
                      $month = "12";
                      $enddays = "31";
                    }
                    else
                     {
                      $from_date = $from_date + "1";
                      $month = "03";
                      $enddays = "31";
                    }

                    $date1 = $from_date."-".$month."-".$enddays;
                    $date2 = date("Y-m-d");
                  //  echo $date1;
                    $year = $penalty[$i]["fy"];
                    $year = explode('-',$year);
                    $year = $year[0];

                    if($date2 > $date1 &&  $year >="2018")
                     {
                    $ts1 = strtotime($date1);
                    $ts2 = strtotime($date2);

                    $year1 = date('Y', $ts1);
                    $year2 = date('Y', $ts2);

                    $month1 = date('m', $ts1);
                    $month2 = date('m', $ts2);

                    $fine_months = (($year2 - $year1) * 12) + ($month2 - $month1);
                   $penaltyamt = round($demandamt * ($fine_months*0.01),2);

                   $sum+= $penaltyamt;
                  }
                    ?>
                <?php
              }
                      }
                     ?>
               <td colspan="5"><b>Rs.<?=number_format($sum, 2, '.', '');?></b></td>
             </tr>
             <tr>
             <td colspan="3"><b>Total Amount</b></td>
              <?php
              $add = array($demandsum,$laf,$sum);
              $add =array_sum($add);
             ?>
               <td colspan="5"><b>Rs. <?=number_format($add, 2, '.', '');?></b></td>
             </tr>
             <?php $Payable = number_format(round($add), 2, '.', '');
                   $Amount = number_format($add, 2, '.', '');
                   $roundoff = $Payable - $Amount;
             ?>
             <tr>
              <td colspan="3"><b>Round Off Amount</b></td>
               <td colspan="5"><b>Rs. <?=number_format($roundoff, 2, '.', '') ?></b></td>
             </tr>
             <tr>
              <td colspan="3"><b>Total Payable</b></td>
               <td colspan="5"><b>Rs. <?=number_format(round($add), 2, '.', '');?></b></td>
             </tr>
             <?php  $rettxt = getIndianCurrency(round($add));?>
             <tr>
             <td colspan="3"><b>Total Demand(in words)</b></td>
               <td colspan="5"><b><?php   echo  $rettxt; ?></b></td>
             </tr>
                </tbody>
              </table>
            </div>
          </div>
          <br>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->include('layout_vertical/footer');?>
