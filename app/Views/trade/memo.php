<?php ////echo  $this->include('layout_vertical/popupHeader');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?php ////echo base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?php ////echo base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?php ////echo base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<style>
@media print {
	#content-container {padding-top: 0px;color: #000;}
	#print_watermark {
        /* background-image:url(<?php ////echo base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important; */
        background-image:url(<?php ////echo base_url(); ?>/public/assets/img/logo/<?php ////echo $ulb['ulb_mstr_id']?>.png) !important;
		background-repeat:no-repeat !important;
		background-position:center !important;
        -webkit-print-color-adjust: exact; 
    }
    #print_watermark,#print_watermark *
    {
        color:#000 !important;
    }
}



#print_watermark{
	background-color:#fff;
	/* background-image:url(<?php ////echo base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important; */
    background-image:url(<?php ////echo base_url(); ?>/public/assets/img/logo/<?php ////echo $ulb['ulb_mstr_id']?>.png) !important;
	background-repeat:no-repeat;
	background-position:center;
	
}
#emailInput{
	border-top: none;
	border-left: none;
	border-right: none;
	background: #fff;
	border-bottom: dotted;
	opacity: .4;
}
#patrank{
	margin-left: 20px;
}
#para{
	color: #000;
}
</style>
<!--CONTENT CONTAINER-->
            <!--===================================================-->
                  
                <!--Page content-->
                <!--===================================================-->
               <div id="page-content">
				<div class="panel panel-bordered panel-dark">
                <div class="panel-body" style="padding-bottom: 0px;">
                    <center>
						<div class="panel-body" id="print_watermark" style="outline-style: groove;padding:5px; ">
							<div class="col-sm-1"></div>
							<div class="col-sm-10" style="text-align: center;">
								<img style="height:80px;width:80px; border-radius: 50px;" src='<?= base_url()."/public/assets/".$ulb_details['logo_path']; ?>'><br>
								<label style="color:#000;">
									<strong style="font-size:24px;">कार्यालय: <?= $ulb_details['ulb_name_hindi']; ?></strong><br/>
									<strong>
									<small>(राजस्व शाखा)</small><br>
									<small>कचहरी रोड, रांची, पिन नंबर -834001</small>
									<br>
									<small>E-mail ID - <span style="border-bottom:1.5px dotted black;">
                                                <?php
                                            if(isset($prop_owner_detail)) {
                                                foreach($prop_owner_detail as $owner){ ?>
                                                    <?= $owner['email'].", " ?>
                                                <?php }
                                            }
                                             ?></span></small>
									</strong>
									</label><br/>
							</div>
                             <table width="100%">
                                 
                                 <tr>
                                     <td>
                                        <label style="font-size:14px;"><strong id="patrank">पत्रांक : /</strong><br/></label><br/>
                                     </td>
                                     <td>
                                        <label style="font-size:14px;">
                                        	<strong>दिनांक : <span style="border-bottom:1.5px dotted black;"><?= date('d-m-y')   ?></span>/</strong>
                                        </label><br/>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td colspan="3">
                                     	<center>
                                            <strong>
                                             	<label style="font-size:20px;text-decoration: underline;">सूचना <br/>
                                            </strong>
                                        </center>
                                     </td>
                                 </tr>
                                 <tr>
                                    <td colspan="3">
                                        <strong>
                                            <label style="font-size:14px;">श्री / श्रीमती /मेसर्स : <span style="border-bottom:1.5px dotted black;">
                                                <?php
                                            if(isset($prop_owner_detail)) {
                                                foreach($prop_owner_detail as $owner){ ?>
                                                    <?= $owner['owner_name'].", " ?>
                                                <?php }
                                            }
                                             ?></span></label>
                                        </strong>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="3">
                                        <strong>
                                            <label style="font-size:14px;">पिता/पति का नाम : <span style="border-bottom:1.5px dotted black;">
                                                <?php
                                            if(isset($prop_owner_detail)) {
                                                foreach($prop_owner_detail as $owner){ ?>
                                                    <?= $owner['guardian_name'].", " ?>
                                                <?php }
                                            }
                                             ?></span></label>
                                        </strong>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                        <strong>
                                            <label style="font-size:14px;">होल्डिंग नंबर : <span style="border-bottom:1.5px dotted black;"><?= $new_holding_no ?></span></label><br/>
                                        </strong>
                                    </td>
                                    <td><label>वार्ड नं. : <span style="border-bottom:1.5px dotted black;"><?= $ward_no ?></span></label></td>
                                 </tr>
                                 <tr>
                                    <td colspan="3">
                                        <strong>
                                            <label style="font-size:14px;">पता :  <span style="border-bottom:1.5px dotted black;"><?= $prop_address ?></span></label><br/>
                                        </strong>
                                    </td>
                                 </tr>
                             </table>

							
                             <table style="width:100%;">
								<tr>
									<p id="para" style="text-align: justify;">
										झारखण्ड नगरपालिका अधिनियम 2011 की धारा 590 द्वारा प्रदत्त शक्तियों का प्रयोग करते हुए झारखण्ड के राज्यपाल नगर विकास एवं आवास विभाग, झारखण्ड, राँची की अधिसूचना 1511 दिनांक 29.04.2022 के अनुसार झारखण्ड नगरपालिका सम्पत्ति कर (निर्धारण, संग्रहण और वसूली) में संशोधन किया गया है. इस संशोधन के अनुसार झारखण्ड राज्य के सभी नगर निगम, परिषद और पंचायत में रहने वाले आम नागिरिकों और व्यवसायियों को सुचित किया जाता है कि अब से होल्डिंग टैक्स कि वसुली सर्किलरेट के लिए नियमों के आधार पर वित्तीय वर्ष 2022 के अप्रैल महीने से की जाएगी.
									</p>
									<p id="para" style="text-align: justify;">
										सम्पत्तिकर नियम 2013 में संशोधन किया गया है, अतः वित्तिय वर्ष 2022-23 से सम्पत्तिकर का मूल्याकन वार्षिक किराया मूल्य के स्थान पर पूजीगत मूल्य के आधार पर किया जायेगा। आपके भवन (होल्डिंग नं० - .) के घृतिकर की गणना 01.04.2022 से सर्किलरेट के अनुसार किया जायेगा।
									</p>
									<strong>
										<p id="para" style="text-align: justify;">
											झारखण्ड नगरपालिका कर भुगतान (समय, प्रक्रिया तथा वसूली) विनियम संशोधन 2022 के विहित प्रावधान के अनुसार आपको उक्त अवधि का धृति कर का भुगतान करना है।
										</p>
									</strong>
									<p id="para" style="text-align: justify;">
										घृतिकर कि वार्षिक किरायादर एव पूजीगत मूल्य के आधार पर तुलनात्मक घृतिकर की गणना निम्नवत है :		
									</p>
								</tr>
								<tr>
									<br>
									<br>
									<br>
									<br>
									<br>
								</tr>

                             </table>

                             <table style="width:100%;color:#B9290A;">
                                 
                             </table>
                             <br />

	                    </div>
					</center>
					<br />
                </div>
            </div>
		</div>
		<!--===================================================-->
		<!--End page content-->

             <!--===================================================-->
            <!--END CONTENT CONTAINER-->

<script>
function printData()
{
   var divToPrint=document.getElementById("printablediv");
   newWin= window.open("");
   newWin.document.write(divToPrint.outerHTML);
   newWin.print();
   newWin.close();
}
</script>
