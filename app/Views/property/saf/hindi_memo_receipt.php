<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<style>

#watermarklogo
{
	background-image:url('<?=base_url(); ?>/public/assets/img/rmcWATERlogo.png') !important;
	background-repeat:no-repeat;
	background-position:center;
	z-index: 100;
	color:#000;
	
}

</style>

<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->
                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">SAF</a></li>
					<li class="active">SAF Final Memo List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <div class="panel-control">
                        <a href="<?php echo base_url('documentverification/memo_receipt/'.$id);?>" class="btn btn-default">English Version</a>
                    </div>
                    <h3 class="panel-title">Memo Receipt </h3>

                </div>
                 <?php
                    $ulb_exp = explode(' ',trim($ulb['description']));
                    $ulb_short_nm=$ulb_exp[0]; 
                 ?>

                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                         <div id="printablediv" style="overflow:hidden; ">
						 <div id="watermarklogo">
                             <table width="100%">
								<tr>
									<td width="40%">
										<img style="height:60px;width:60px;float:right;" src='<?php echo base_url('public/assets/img/logo1.png');?>'>
									</td>
                                    <td width="60%">
                                        <label style="font-size:14px;text-align: center;"><strong><u>कार्यालय : <?=$ulb['description'].', '.$ulb_short_nm;?><br>( राजस्व शाखा  )</u></strong></label>
                                    </td>
                                 </tr>
                                 
                             </table>
                             <center>
                                 <table>
                                     <tr>
                                         <td>झारखण्ड नगरपालिका अधिनियम ,2011  की धरा 152 (B ) सह पठित झारखण्ड नगरपालिका संपत्ति कर (निर्धारण , संग्रहण और वसूली ) नियमावली के अंतर्गत निर्धारित किये गए संपत्ति कर की सूचना |<hr/></td>
                                     </tr>

                                 </table>
                             </center>
                             <table style="width:100%;">
                                 <tr>
									<td>श्री /श्रीमती /सुश्री  <br/><span style="font-weight:bold;" class="bol">
                                         <?php
                                                    if(isset($owner_list)):
                                                         if(empty($owner_list)):
                                                    ?>
                                                            <span class="text-danger">N/A</span>
                                                    <?php else:
                                                    $ownerArray = [];
                                                    foreach($owner_list as $value) { 
                                                        $ownerArray[] = strtoupper($value['owner_name']).' '.strtoupper($value['relation_type']).' '.strtoupper($value['guardian_name']); 
                                                    }
                                                    echo implode(', ', $ownerArray);
                                                      endif;  
                                                 endif;  ?>
                                         </span>
									</td>
                                    <td align="right">Memo No. : <span style="font-weight:bold;" class="bol"><?=(isset($memo_no['memo_no']))?$memo_no['memo_no']:'N/A';?></span></td>
                                 </tr>
                                 <?php
                                 if($prop_tax['qtr']=='1')
                                 {
                                     $qtr_nm='प्रथम';
                                 }
                                 else if($prop_tax['qtr']=='2')
                                 {
                                     $qtr_nm='द्वितीय';
                                 }
                                 else if($prop_tax['qtr']=='3')
                                 {
                                     $qtr_nm='तृतीय';
                                 }
                                 else if($prop_tax['qtr']=='4')
                                 {
                                     $qtr_nm='चतुर्थ';
                                 }
                                 ?>

                                 <tr>
									<td>पता : <span style="font-weight:bold;" class="bol"><?=strtoupper($holding_no['prop_address']).' '.strtoupper($holding_no['prop_city']);?></span>
									</td>
                                    <td align="right">प्रभावी : <span style="font-weight:bold;" class="bol"><?=$qtr_nm.' तिमाही  '.$fy['fy'];?></span></td>
                                 </tr>
                             </table>
                            
                             <table style="width:100%;">
                                 <tr>
                                     <td>&nbsp;&nbsp;<div  class="col-sm-4"><p>एतद् द्वारा आपको सूचित किया जाता है की आपका नया गृह सं०- </p>
                                        </div>
									 <div class="col-sm-6">
                                         <table cellpadding="0" cellspacing="0">
                                             <tr>
                                                 <?php
                                                 $nums = "";
                                                 $nums .= $holding_no['holding_no'];
                                                 $nums = $nums.$number;
                                                 $length = strlen($nums); // This is the length of your integer.
                                                 for($ivk=0;$ivk<$length;$ivk++)
                                                 {
                                                     ?>
                                                 <td style="width:20px;height:20px;border:2px solid #000;text-align:center;"><?=$nums[$ivk];?></td>
                                                 <?php
                                                 }
                                                 ?>
                                             </tr>
                                         </table>
                                     </div>

                                     </td>                                     
                                 </tr>
                                 <tr>
                                     <td> वार्ड  नं० - <?=(isset($form['ward_no']))?$form['ward_no']:'N/A';?> (पुराना गृह सं०-<?=$holding_no['old_holding_no'];?> ,वार्ड नं० -<?=$form['old_ward_no'];?>)के लिए घृति कर निर्धारण हेतु आपके द्वारा की गयी स्वकर निर्धारण घोषणा पत्र के अलोक में रांची नगर निगम द्वारा किये गए स्थानीय जांचोपरांत इस होल्डिंग का वार्षिक किराया मूल्य |
                                          <?=$saf_tax['arv'];?>/- रु०  के स्थान पर <?=$prop_tax['arv'];?>/- रु० निर्धारित किया जाता है |
                                     </td>
                                 </tr>
                                 <tr>
                                     <td>निगम द्वारा निर्धारित किये गए वार्षिक किराया मूल्य के आधार पर <?=$qtr_nm;?> तिमाही वर्ष <?=$fy['fy'];?> के प्रभाव से घृति कर अधोलिखित रूप से भुगतेय होगा |
                                     </td>
                                 </tr>
                             </table>
                             <table style="width:100%;" border="1px" cellpadding="0" cellspacing="0">
                                 <tr>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">क्रम सं० </span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">ब्यौरे</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">वित्तीय वर्ष</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">स्वकर निर्धारण घोषणा  पत्र के आधार पर</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">निगम स्तर से गणित की गयी के आधार पर</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">घृतिकर में पायी गयी अंतर राशि स्तम्भ <br/>(4-3)</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">स्तम्भ  0.5 का शास्ति 100%</span></td>
                                 </tr>
                                 <tr>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">1</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">2</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">3</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">4</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">5</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">6</span></td>
                                 </tr>
                                 <?php
                                 $saf_total=0;
                                 $prop_total=0;
                                 $diff_total=0;
                                 $i=1;
                                 foreach ($prop_tax_dtl as $value):
                                 $diff_tax=($value['holding_tax']*4)-($value['holding_tx']*4);
                                 //$totaldiff_tax=($value['holding_tax']*4)+($value['holding_tx']*4);
                                     if($diff_tax<0)
                                     {
                                        $diff_tax_amt=0;
                                     }
                                     else
                                     {
                                        $diff_tax_amt=$diff_tax;
                                     }
                                 //$diff_percent=($diff_tax/$totaldiff_tax)*100;
                                 ?>
                                 <tr>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=$i++;?></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">घृतिकर @ 2% के दर से </span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">तिमाही: <?=$value['qtr'];?>/ वित्तीय वर्ष: <?=$value['fyy'];?></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=(isset($value['holding_tx']))?$value['holding_tx']*4:'0';?></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=(isset($value['holding_tax']))?$value['holding_tax']*4:'0';?></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=$diff_tax_amt;?></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=$diff_tax_amt;?></span></td>
                                 </tr>
                                 <?php
                                 $saf_total=$saf_total+ ($value['holding_tx']*4);
                                 $prop_total=$prop_total+ ($value['holding_tax']*4);
                                 $diff_total=$diff_total+ $diff_tax_amt;
                                 endforeach;?>
                                 <tr>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">कुल राशि</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=$saf_total;?></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=$prop_total;?></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=$diff_total;?></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=$diff_total;?></span></td>
                                 </tr>

                             </table>
                            
                             <table style="width:100%;">
                                 <tr>
                                     <td>&nbsp;</td>
                                 </tr>
                                
                                 <tr>
                                    <td class="bg" style="padding-bottom:5px;font-size:12px;text-align:right;">
										<img style="margin-left:0px;width:100px;height:100px;float:left;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>'>
										<span style="font-weight:bold;" class="bol">सहायक कार्यपालक पदाधिकारी <br/><?=$ulb['description'].', '.$ulb_short_nm;?> | 
										</span>
									</td>	
                                 </tr>
                               
                                 <tr>
                                     <td><span style="font-weight:bold;">नोट:-</span></td>
                                 </tr>
                                 <tr>
                                    <td>
                                        <ol>
                                            <li>निगम द्वारा निर्धारित किये गए कर के विरूद्ध यदि कोई आपत्ति है ,तो झारखण्ड नगरपालिका अधिनियम , 2011 की धरा 167 (1 )  के विहित प्रावधान के अधीन इस मूल्यांकन Memo प्राप्ति के ३०(तीस) दिनों के अंदर विहित प्रपत्र में आपत्ति दाखिल कर सकते है | </li>
                                            <li>कर निर्धारण की सूची, रां<?=$ulb['description'];?> के वेबसाइट www.ranchimunicipal.com पर प्रदर्शित है |</li>
                                            <li>झारखण्ड नगरपालिका संपत्ति कर (निर्धारण , संग्रहण और वसूली ) नियमावली 2013 की कंडिका 13 .4 के अनुसार वास्तव में भुगतेय कर की अंतर राशि तथा उस पर एक सौ प्रतिशत शास्ति भी भुगतेय है |</li>
                                            <li><?=$ulb['description'];?> द्वारा संग्रहित इस संपत्ति कर इन इमारतों /ढाचों को कोई कानूनी हैसियत प्रदान नहीं करता है और /या न ही अपने मालिकों /दखलकारको कोई कानूनी अधिकार प्रदान करता है |</li>

                                        </ol>
                                    </td>
                                 </tr>

                             </table>
                             <br />
                        <center>
                            <div class="row"style="margin-bottom:1%;">
                                <div class="col-md-12">
                                    <img src="<?=base_url();?>/public/assets/img/swachh-bharat.PNG" style="height:50px; width:100px;" alt="rmc">
                                </div>
                            </div>
                        </center>
	                    </div>
	                    <br />
                        <center>
                            <div class="row"style="margin-bottom:1%;">
                                <div class="col-md-12">
                                    <button type="button" id="btnPrint" class="noprint btn btn-primary" onclick="printData()">Print</button>
                                </div>
                            </div>
                        </center>
						</div>
                    </div>
                </div>
            </div>
                </div>
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->

<?= $this->include('layout_vertical/footer');?>
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