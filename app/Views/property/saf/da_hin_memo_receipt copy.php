<?= $this->include('layout_vertical/header');?>
<style>
@media print {
	#content-container {padding-top: 0px;}
	#print_watermark {
        background-image:url(<?=base_url("/public/assets/img/logo/").$ulb["id"].".png"; ?>) !important;
		background-repeat:no-repeat !important;
		background-position:center !important;
        -webkit-print-color-adjust: exact; 
    }
}
#print_watermark {
	background-color:#FFFFFF;
	background-image:url(<?=base_url("/public/assets/img/logo/").$ulb["id"].".png"; ?>) !important;
	background-repeat:no-repeat;
	background-position:center;
}
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">SAF</a></li>
            <li class="active">SAF Generated Memo List</li>
        </ol>
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <div class="panel-control">
                    <a href="<?php echo base_url('documentverification/da_eng_memo_receipt/');?>" class="btn btn-default">English Version</a>
                </div>
                <h3 class="panel-title">Memo Receipt </h3>
            </div>
            <?php
                $ulb_exp = explode(' ',trim($ulb['description']));
                $ulb_short_nm=$ulb_exp[0]; 
            ?>
            <div class="panel-body" id="print_watermark">
                <div class="row noprint">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-mint btn-icon" onclick="print()"><i class="demo-pli-printer icon-lg"></i></button>
                    </div>
                </div>
                <div class="row">
                    <table width="100%">
                        <tr>
                            <td width="39%">
                                <img style="height:60px;width:60px;float:right;" src='<?php echo base_url('public/assets/img/logo1.png');?>'>
                            </td>
                            <td width="2%"></td>
                            <td width="59%">
                                <label style="font-size:14px;"><strong><?=$ulb['ulb_name'].', '.$ulb_short_nm;?></strong></label>
                            </td>
                        </tr>
                    </table>
                    <br/>
                    <center>
                        <table>
                            <tr style="border-bottom:2px solid black;">
                                <td><b>झारखण्ड नगरपालिका अधिनियम -2011  की धरा 152 (3) के अंतर्गत स्वनिर्धारित किये गए संपत्ति कर की सूचना | </b></td>
                            </tr>
                        </table>
                    </center>
                    <table style="width:100%;">
                        <tr>
                            <td><br></td>
                        </tr>
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
                        <tr>
                            <td rowspan="2">पता : <span style="font-weight:bold;" class="bol"><?=strtoupper($holding_no['prop_address']).' '.strtoupper($holding_no['prop_city']);?>		</span><br/><br/>
                            </td>
                                <td align="right">Date : <span style="font-weight:bold;" class="bol"><?=date('d-m-Y');?></span></td>
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
                                <td align="right">प्रभावी : <span style="font-weight:bold;" class="bol"><?=$qtr_nm.' तिमाही  '.$fy['fy'];?></span></td>
                        </tr>
                        <tr>
                                <td > &nbsp;</td>
                        </tr>
                    </table>
                    <table style="width:100%;">
                        <tr>
                                <td>&nbsp;&nbsp; एतद् द्वारा आपको सूचित किया जाता है कि आपके <b> पुराना गृह सं०-   <span class="text-danger"><?=(isset($form['holding_no']))?$form['holding_no']:'N/A';?></span> एवं नया गृह सं०-   <span class="text-danger"><?=(isset($holding_no['holding_no']))?$holding_no['holding_no']:'N/A';?></span> एवं वार्ड  सं० - <span class="text-danger"><?=(isset($form['ward_no']))?$form['ward_no']:'N/A';?></span> </b>हुआ है , आपके स्व० निर्धारण घोषणा पत्र के आधार पर वार्षिक किराया मूल्य  
                                    <?=$prop_tax['arv'];?>/- रु० निर्धारित किया गया है |
                                </td>
                        </tr>
                        <tr>
                                <td>इसके अनुसार प्रति तिमाही कर निम्न  प्रकार होगा |  </td>
                        </tr>
                    </table>
                    <br/>
                    <table style="width:100%;" border="1px" cellpadding="0" cellspacing="0">

                            <tr>
                                <td colspan="3" style="width:50%;padding-bottom:5px;font-size:12px;"><center><b>स्व-निर्धारित कर की सूचना</b></center> </td>
                            </tr>
                            <tr>
                                <td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;" class="bol">क्रम स० </span></td>
                                <td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;" class="bol">ब्यौरे</span></td>
                                <td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;" class="bol">राशि  (in Rs.)</span></td>
                            </tr>

                            <tr>
                                <td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;">1.</td>
                                <td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;">गृह कर</td>
                                <td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;"><?=$prop_tax['holding_tax']*4;?></td>
                            </tr>

                            <tr>
                                <td colspan="2" style="width:70%;padding-bottom:5px;font-size:12px;"><b>कुल राशि (प्रति तिमाही  )</b> </td>
                                <td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;"><?=$prop_tax['holding_tax']*4;?></td>
                            </tr>

                    </table>
                    <br/>
                    <table style="width:100%;">
                            <tr>
                                <td align="right">
                                <img style="margin-left:0px;width:100px;height:100px;float:left;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>'>
                                <div style="width:180px;height: 30px;border:2px solid #000;"></div>
                                <span style="font-weight:bold;">To be signed by the Applicant</span>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><span style="font-weight:bold;">नोट:-</span></td>
                            </tr>
                            <tr>
                            <td>
                                <ol>
                                    <li>कर निर्धारण की सूची, <?=$ulb['description'];?> के वेबसाइट <span style="color:red;"> www.ranchimunicipal.com </span> पर प्रदर्शित है |</li>
                                    <li>नियमावली कंडिका 11.4 के अलोक में वर्षा जल संरक्षण कि व्यवस्था नहीं होने के कारण अतिरिक्त गृह कर लगाया जो संपत्ति कर का 50% होगा |<br/>हिदायत दी जाती है कि , वर्षा जल संरक्षण संरचना लगा कर निगम को सूचित करे तथा अतिरिक्त गृह कर से रहत पाये| </li>
                                    <li>प्रत्येक वित्तीय वर्ष में संपत्ति कर का भुकतान त्रैमासिक देय होगा |</li>
                                    <li>यदि किसी वर्ष के लिए संपूर्ण घृति कर का भुकतान वित्तीय वर्ष के 30 जून के पूर्व कर दिया जाता है , तो करदाता को 5% कि रियातय दी जाएगी |</li>
                                    <li>किसी देय घृति को निद्रिष्टि समयावधि (प्रत्येक  तिमाही ) के अंदर या उसके पूर्व नहीं चुकाया जाता है , तो  1% प्रतिमाह कि दर से साधारण ब्याज देय होगा |</li>
                                    <li>यह कर निर्धारण आपके स्व -निर्धारण एवं कि गयी घोषणा के आधार पर कि जा रही है,इस स्व -निर्धारण -सह-घोषणा पत्र कि स्थानीय जांच तथा समय निगम करा सकती है एवं तथ्य गलत पाये जाने पर नियमावली कंडिका  13.2 के अनुसार निर्धारित शास्ति (Fine) एवं अंतर राशि देय होगा |</li>
                                    <li><?=$ulb['description'];?> द्वारा संग्रहित इस संपत्ति कर इन इमारतों / ढांचों को कोई कानूनी हैसियत प्रदान नहीं करता है  और / या न ही अपने मालिकों / दखलकार को कानूनी अधिकार प्रदान करता है |</li>
                                    <li>अगर आपने नए होल्डिंग न०  का आखरी अंक 5/6/7/8 है तो यह विशिष्ट संरचनाओं कि श्रेणी के अंतर्गत माना जायेगा |</li>
                                </ol>
                            </td>
                            </tr>
                            <tr>
                                <td><span style="font-weight:bold;font-size:12px;">नोट :- यह एक कंप्यूटर जनित रसीद है। इस रसीद के लिए भौतिक हस्ताक्षर की आवश्यकता नहीं है।</span></td>
                            </tr>
                    </table>
                    <br />
                    <br />
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>