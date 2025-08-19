

<!DOCTYPE html>
<html>
<head>
    
    <link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/pace/pace.min.css" rel="stylesheet">
    <script src="<?=base_url();?>/public/assets/plugins/pace/pace.min.js"></script>
<style>

#print_watermark{
	background-color:#FFFFFF;
	/* background-image:url(<?=base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important; */
	background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb_mstr_name['ulb_mstr_id'];?>.png) !important;
	background-repeat:no-repeat;
	background-position:center;
	
}
li{
    font-size: 12px !important;
    font-family: Arial, Helvetica, sans-serif;
}
#printable *{
    /* color:#B9290A; */
    font-size: 12px !important;
}
#list tr :nth-child(1){
    width: 0.5rem;
    margin-right: 1pex;
}
.norap td{
        width: 15%;
        padding: 0.5rem;
    }
li

</style>
<style>
    @media print 
    {
        #content-container {padding-top: 0px;}
        #print_watermark {
            background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb_mstr_name['ulb_mstr_id'];?>.png) !important;
            background-repeat:no-repeat !important;
            background-position:center !important;
            -webkit-print-color-adjust: exact; 
        }
        #printable *{
            /* color:#B9290A !important; */
            /* font-size: 12px !important; */
            font-family: Arial, Helvetica, sans-serif !important;
        }   
        
    }
</style>   
</head>
<body>
		<div id='printable'>
               
			<div id="page-content" style="border: 2px dotted ; margin:2%;padding:1%; ">
				<div class="row">
					<div class="col-sm-12">
						<div class="panel" >
							
				            <!-- ======= Cta Section ======= -->
                            <div class="panel panel-dark">
                                
                                <div class="panel-body"  id="print_watermark">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-10" style="text-align: center;">
                                            <img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png');?>'>
                                        </div>
                                        <table width="99%" border="0" align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">

                                            <tbody>
                                                <tr>
                                                    <td height="71" colspan="4" align="center">
                                                        
                                                        <div style="width: 70%; padding: 8px; height: auto; font-family: Arial, Helvetica, sans-serif; font-size: 18px; ">
                                                            <strong style="text-transform: uppercase;"><?=$ulb["ulb_name"];?></strong>  <br/>  
                                                            <strong>Water Supply Section</strong><br/>
                                                            <label style="font-size:14px;">Section 592 of the Jharkhand Municipal Act-2011</label><br>
                                                            <span>"<?=$ulb['ulb_name']?>water charges and civil laws in 2015" </span><br><br>
                                                            <strong style="border: 2px solid; padding:2.5px 4rem;font-size:large;margin-top:1rem;">Water meter Connection with order form</strong><br><br>
                                                            <span>Sub:- Residential complex with regard to Meter Connection, including water .</span><br><br>
                                                        </div>
                                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br>
                                        <table width="100%">
                                            <tbody class="norap">
                                                <tr>
                                                    <td>Consumer ID</td>
                                                    <td>:</td>
                                                    <td><strong><?=$data['application_no']?></strong></td>
                                                    <td>Year</td>
                                                    <td>:</td>
                                                    <td><strong><?=$data['year']?></strong></td>                                        
                                                </tr>
                                                <tr>
                                                    <td>Receiving Date</td>
                                                    <td>:</td>
                                                    <td><strong><?=$data['recieved_date']?></strong></td>
                                                    <td>Approval Date</td>
                                                    <td>:</td>
                                                    <td><strong><?=$data['verify_date']?></strong></td>                                        
                                                </tr>
                                                <tr>
                                                    <td>Vide Receipt Number</td>
                                                    <td>:</td>
                                                    <td><strong><?=$data['transaction_no']?></strong></td>
                                                    <td>Payment Date</td>
                                                    <td>:</td>
                                                    <td><strong><?=$data['transaction_date']?></strong></td>                                        
                                                </tr>
                                                <tr class="title">
                                                    <td cols='6'><strong>1. .Applicant Details</strong></td>                                        
                                                </tr>
                                                <tr>
                                                    <td>Ward No</td>
                                                    <td>:</td>
                                                    <td cols=3><strong><?=$data['ward_no']?></strong></td>
                                                                                        
                                                </tr>
                                                <tr>
                                                    <td>Applicant Name</td>
                                                    <td>:</td>
                                                    <td><strong><?=$data['ownere_name']?></strong></td>
                                                    <td>Guardian Name</td>
                                                    <td>:</td>
                                                    <td><strong><?=$data['father_name']?></strong></td>                                        
                                                </tr>
                                                <tr>
                                                    <td>Correspondence Address</td>
                                                    <td>:</td>
                                                    <td><strong><?=$data['address']?></strong></td>
                                                    <td>Mobile No</td>
                                                    <td>:</td>
                                                    <td><strong><?=$data['mobile_no']?></strong></td>                                        
                                                </tr>
                                                <tr>
                                                    <td>e-Mail</td>
                                                    <td>:</td>
                                                    <td></td>
                                                    <td>Plot No</td>
                                                    <td>:</td>
                                                    <td></td>                                        
                                                </tr>
                                                <tr>
                                                    <td>Holding No(if any)</td>
                                                    <td>:</td>
                                                    <td><strong><?=$data['holding_no']?></strong></td>
                                                    <td>Suvidha Shulk No(If any)</td>
                                                    <td>:</td>
                                                    <td></td>                                        
                                                </tr>
                                                <tr>
                                                    <td>Built-up area in square</td>
                                                    <td>:</td>
                                                    <td><strong><?=$data['area_sqmt']?></strong></td>
                                                    <td>Connection Through</td>
                                                    <td>:</td>
                                                    <td><strong><?=$data['apply_from']?></strong></td>                                        
                                                </tr>                                    
                                            </tbody>
                                        </table>
                                        <table width="100%">
                                            <tbody class="norap">
                                                <tr class="title" >
                                                    <td cols=9 ><strong>2.	Water Connection as per the prescribed rate in the light of the By-By-2015<strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Water connection fee</td>
                                                    <td><strong><?=$data['conn_fee']?></strong></td>
                                                    <td>Category</td>
                                                    <td><strong><?=$data['category']?></strong></td>
                                                    <td>Extra Charge(During Inspection)</td>
                                                    <td><strong><?=$data['site_inspection']?></strong></td>                                        
                                                </tr>
                                                <tr>
                                                    
                                                    <td>Aggregate amount deposited</td>
                                                    <td><strong><?=$data['total_diposit']?></strong></td>  
                                                    <td>Total Amount</td>
                                                    <td><strong><?=$data['total_charge']?></strong></td>                                           
                                                </tr>
                                            
                                            </tbody>
                                        </table>                                        
                                        <br>
                                        <strong>Attachments:-Approved Plans</strong>
                                        <dd>notise:-</dd>
                                        <table id ='list'width="99%" border="0" style="font-family: Arial, Helvetica, sans-serif; ">
                                            <tbody>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>The Ferrule Size should be approved in the presence of pipeline <br>
                                                        Inspector /junior Engineer of <?=strtoupper($ulb['ulb_name'])?> by the <br>
                                                        applicant and the service pipe should not be laid in drain.</td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Applicant will have to pay Water Charges <br>from the date of the connection as 6 Rs per kg /(1000 ) liters</td>
                                                </tr>
                                                <tr>
                                                    <td>3.</td>
                                                    <td>Providing technical approval in accordance with<br> the Water Connection and water meter with ISI mark will make sure <br>to inform the by Assistant Engineer , Water Supply Branch ,<?=strtoupper($ulb['ulb_name'])?> <br>within 15 days otherwise 200 Fixed Rate (+ 10% Penalty) will be recovered<br> from the water charges.</td>
                                                </tr>
                                                <tr>
                                                    <td>4.</td>
                                                    <td>the consumer will have to provide Water Connection / meter <br>declaration information themselves in writing to <?=strtoupper($ulb['ulb_name'])?></td>
                                                </tr>
                                                <tr> 
                                                    <td>5.</td>
                                                    <td>(A)the consumer must pay Water tax bill within the due date <br>otherwise simple interest at the rate of 2% will be levied<br>
                                                         (B)if the consumer does not pay the bill on the due date, water connection will<br> be cut off and Reconnection will be charged with water tax</td>
                                                </tr>
                                                <tr> 
                                                    <td>6.</td>
                                                    <td>pipeline inspector will correspond to junior engineer <br>Water Supply Branch, <?=strtoupper($ulb['ulb_name'])?> with <br>the instructions that correspond to the order for <br>water Connection By Plumber within 15 days.</td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>

                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <span class="pull-right">signetur<br><br>
                                                    <strong>Executive Officer</strong><br>
                                                    <strong>Supply Section,</strong><br>
                                                    <strong style="text-transform: uppercase;"><?=$ulb['ulb_name']?>,</strong>
                                                </span><br><br>
                                            </div><br><br>
                                            <div class="col-md-12">
                                                <p><strong>Tilipi: -</strong> Pipe larine inspector / conveyor engineer, water supply branch, Ranchi municipal corporation, <br>Ranchi with this instruction, in accordance with the order approved before them, by combining water from Palembar, <br>will report it to the office within 15 days.</p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <span><img style="width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>'></span>
                                                <span class="pull-right">signetur<br><br>
                                                    <strong>Executive Officer</strong><br>
                                                    <strong>Supply Section,</strong><br>
                                                    <strong style="text-transform: uppercase;"><?=$ulb['ulb_name']?>,</strong>
                                                </span>
                                            </div><br><br>                                
                                        </div>
                                        
                                    
                                </div>
                                
                            </div>
                        </div>
					</div>
				</div>
			</div>
            <div style = "border: 2px dotted ; padding:1rem;  margin:2%;">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>OWNER NAME</td>
                            <td>:</td>
                            <td><strong><?=$data['ownere_name']?></strong></td>
                        </tr>
                        <tr>
                            <td>PURPOSE</td>
                            <td>:</td>
                            <td><strong>DOMESTIC WATER PIPELINE WITH METER CONNECTION</strong></td>
                        </tr>
                        <tr>
                            <td>ADDRESS</td>
                            <td>:</td>
                            <td><strong><?=$data['address']?></strong></td>
                        </tr>
                        <tr>
                            <td>WARD NO</td>
                            <td>:</td>
                            <td><strong><?=$data['ward_no']?></strong></td>
                        </tr>
                        <tr>
                            <td>HOLDING/PLOT NO.</td>
                            <td>:</td>
                            <td><strong><?=$data['holding_no']?></strong></td>
                        </tr>
                        <tr>
                            <td>MOBILE NO.</td>
                            <td>:</td>
                            <td><strong><?=$data['mobile_no']?></strong></td>
                        </tr>
                        <tr>
                            <td>PIPELINE</td>
                            <td>:</td>
                            <td><strong><?=$data['pipeline_type']?></strong></td>
                        </tr>
                        <tr>
                            <td>CATEGORY</td>
                            <td>:</td>
                            <td><strong><?=$data['category']?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
			<div class="container" id="print">
				<div class="col-sm-12 noprint text-right mar-top" style="text-align: center;">
					<button class="btn btn-mint btn-icon" onclick="printDiv('printable')" style="height:40px;width:60px;">PRINT</button>
				</div>				
			</div>
</body>	
	<script type="text/javascript">
		function printDiv(divName)
{
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
    //document.getElementById('print').remove();
	document.body.innerHTML = printContents;

	window.print();

	document.body.innerHTML = originalContents;
}
	</script>		
			
</html>