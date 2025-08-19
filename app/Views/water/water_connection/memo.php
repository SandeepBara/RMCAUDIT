<?php
//print_var($data);
?>

<?php
	//session_start();

 echo $this->include('layout_vertical/popup_header');
?>

<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
<style>

    .HindiFont{
        display: none;
    }
    .EnglishFont{
        display: '';
    }
    
    .norap td{
        width: 15%;
        padding: 0.5rem;
    }
    #printable{
        padding:1rem;    
    }
    .title{
        width: 18% !important;
    }
    .title td{
        padding: 1rem 0 !important;
    }
    .mini td{
        padding: 0 !important;
    }
    .water_mark {
    display: inline-block;
    width: 99%;
    position: absolute;
    top: 33%;
    /*z-index: -1;*/
    text-align: center;
    }
    .water_mark img {
        opacity: 0.31;
    }
    @media print {
         #txt-right-dtl {
            text-align: right;
            margin-right: 200px;
         }
         #txt-left-dtl {
            text-align: left;
         }
    }


</style>

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">    
    
    <div id="page-head" style="display:<?=isset($citize)?'none':''?>">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Memo For Water Connection</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
            
				<div class="panel panel-bordered panel-dark">                    
					<div class="panel-heading">                    
						<div >
                        <!-- onclick="showHide('HindiFont','EnglishFont');" -->
                        <!-- onclick="showHide('EnglishFont','HindiFont');" -->
                            <p class="panel-control "> 
                                <button class="btn-warning" id="HindiFontBtn">Hindi</button>
                                <button class="btn-warning" id="EnglishFontBtn">English</button>
                                
                            </p> 
                            <h3 class="panel-title"> Memo For Water Connection</h3>      
                        </div>
					</div>
                    
					<div class="panel-body" style = "border: 2px solid ;">
                      
                        <div id="printable" >
                                <style>
                                    @page {
                                        margin-top: 13rem;
                                        margin-bottom: 0;
                                        size: portrait;
                                        size: A4;
                                    }
                                    @media print {
                                                    #content-container {padding-top: 0px;}
                                                    #printable {page-break-before: always;}
                                                    #printable {
                                                        background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb["ulb_mstr_id"];?>.png) !important;
                                                        background-repeat:repeat !important;
                                                        background-position:center !important;
                                                        color:#B9290A !important;
                                                        
                                                        -webkit-print-color-adjust: exact; 
                                                    }
                                                    .panel-body, .panel-body *{
                                                        color:#B9290A!important;
                                                        
                                                    }
                                                    .panel-body{
                                                            margin-top: 3rem;
                                                    }
                                                    .water_mark_cover span {font-size: 80px;}
                                                    .water_mark {
                                                        display: inline-block;
                                                        mask-repeat: repeat-y;
                                                        width: 99%;
                                                        position: absolute;
                                                        top: 33%;
                                                        /*z-index: -1;*/
                                                        text-align: center;
                                                    }
                                                    .water_mark img {
                                                        opacity: 0.31;
                                                    }
                                                }
                                </style>
                            <div class="EnglishFont">
                                <div style = "border: 2px dotted ; padding:1rem;">                                
                                    <div style="text-align:center;  line-height: 2rem; margin-top:1rem;">
                                        <img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png');?>'><br>
                                        <strong style="text-transform: uppercase;"><?=$ulb['ulb_name']?></strong><br>
                                        <span>Water Supply Section</span><br>
                                        <span>Ranchi Muncipal Water Work</span><br>
                                        <span>Water overloaded, and Water Consumer Rules - <?=(isset($data['verify_date']) && ($data['verify_date'])>='2020-12-31')?'2020':'2020'?></span><br><br>
                                        <strong style="border: 2px solid; padding:2.5px 4rem;font-size:large;margin-top:1rem;">Water meter Connection with order form</strong><br><br>
                                        <span>Sub:- Residential complex with regard to Meter Connection, including water .</span><br><br>
                                    </div>
                                    <table width="100%">
                                        <tbody class="norap">
                                            <tr>
                                                <td>Consumer ID</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['consumer_no'])?$data['consumer_no']:''?></strong></td>
                                                <td>Application No.</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['application_no'])?$data['application_no']:''?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Year</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['year'])?$data['year']:''?></strong></td>                                        
                                            </tr>
                                            <tr>
                                                <td>Receiving Date</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['recieved_date'])?$data['recieved_date']:''?></strong></td>
                                                <td>Approval Date</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['verify_date'])?$data['verify_date']:''?></strong></td>                                        
                                            </tr>
                                            <tr>
                                                <td>Vide Receipt Number</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['transaction_no'])?$data['transaction_no']:''?></strong></td>
                                                <td>Payment Date</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['transaction_date'])?$data['transaction_date']:''?></strong></td>                                        
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>Connection date  </td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['apply_date'])?$data['apply_date']:'N/A';?></strong></td></td>                                        
                                            </tr>
                                            <tr class="title">
                                                <td cols='6'><strong>1. .Applicant Details</strong></td>                                        
                                            </tr>
                                            <tr>
                                                <td>Ward No</td>
                                                <td>:</td>
                                                <td cols=3><strong><?=isset($data['ward_no'])?$data['ward_no']:''?></strong></td>
                                                                                    
                                            </tr>
                                            <tr>
                                                <td>Applicant Name</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['ownere_name'])?$data['ownere_name']:''?></strong></td>
                                                <td>Guardian Name</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['father_name'])?$data['father_name']:''?></strong></td>                                        
                                            </tr>
                                            <tr>
                                                <td>Correspondence Address</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['address'])?$data['address']:''?></strong></td>
                                                <td>Mobile No</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['mobile_no'])?$data['mobile_no']:''?></strong></td>                                        
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
                                                <td><strong><?=isset($data['holding_no'])?$data['holding_no']:''?></strong></td>
                                                <td>Suvidha Shulk No(If any)</td>
                                                <td>:</td>
                                                <td></td>                                        
                                            </tr>
                                            <tr>
                                                <td>Built-up area in square Sqmt.</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['area_sqmt'])?$data['area_sqmt']:''?></strong>&nbsp; <span>SqMtr.</span></td>
                                                <td>Connection Through</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['apply_from'])?$data['apply_from']:''?></strong></td>                                        
                                            </tr>                                    
                                        </tbody>
                                    </table>
                                    <table width="100%">
                                        <tbody class="norap">
                                            <tr class="title" >
                                                <td cols=9 ><strong>2.	Water Connection as per the prescribed rate in the light of the Rules 2020.<strong></td>
                                            </tr>
                                            <tr>
                                                <td>Water connection fee</td>
                                                <td><strong><?=isset($data['conn_fee'])?$data['conn_fee']:''?></strong></td>
                                                <td>Category</td>
                                                <td><strong><?=isset($data['category'])?$data['category']:''?></strong></td>
                                                <td>Extra Charge(During Inspection)</td>
                                                <td><strong><?=isset($data['site_inspection'])?$data['site_inspection']:''?></strong></td>                                        
                                            </tr>
                                            <tr>
                                                
                                                <td>Aggregate amount deposited</td>
                                                <td><strong><?=isset($data['total_diposit'])?$data['total_diposit']:''?></strong></td>  
                                                <td>Total Amount</td>
                                                <td><strong><?=isset($data['total_charge'])?$data['total_charge']:''?></strong></td>                                           
                                            </tr>
                                           
                                        </tbody>
                                    </table><br>
                                    <div class="row">
                                        <div class="col-md-12 mt-3">
                                            <p>map of water Connection is approved.In which 1 Add Tap And size of ferrule is 9 mm</p>
                                        </div>
                                    </div>
                                    <br>
                                    <table width="100%">
                                        <tbody class="norap">
                                            <tr class="tital">
                                                <td>
                                                    <strong>Attachments:-Approved Plans</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <dl>
                                                        <dd>notice:-</dd>
                                                        <dt>
                                                            <ol>
                                                                <li>The Ferrule Size should be approved in the presence of pipeline Inspector /junior Engineer of <?=strtoupper($ulb['ulb_name'])?> by the applicant and the service pipe should not be laid in drain.</li>
                                                                <li>Applicant will have to pay Water Charges from the date of the connection as <?=(isset($data['verify_date']) && ($data['verify_date'])>='2020-12-31')?'9':'9';?> Rs per kg /(1000 ) liters</li>
                                                                <li>Providing technical approval in accordance with the Water Connection and water meter with ISI mark will make sure to inform the by Assistant Engineer , Water Supply Branch ,<?=strtoupper($ulb['ulb_name'])?> within 15 days otherwise Rate As Per Sqmtr (+ 10% Penalty) will be recovered from the water charges.</li>
                                                                <li>the consumer will have to provide Water Connection / meter declaration information themselves in writing to <?=strtoupper($ulb['ulb_name'])?></li>
                                                                <li>(A)the consumer must pay Water tax bill within the due date otherwise simple interest at the rate of 1.5% will be levied
                                                                        <br>(B)if the consumer does not pay the bill on the due date, water connection will be cut off and Reconnection will be charged with water tax
                                                                </li>
                                                                <li>pipeline inspector will correspond to junior engineer Water Supply Branch, <?=strtoupper($ulb['ulb_name'])?> with the instructions that correspond to the order for water Connection By Plumber within 15 days.</li>
                                                            </ol>
                                                        </dt>
                                                    </dl>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <span class="pull-right">
                                                <!-- <img style="width:100px;height:100px;" src='<?php echo base_url('public/assets/img/water/'.$ulb['city'].'/signetur/water_sig.png');?>'><br> -->
                                                <img style="width:100px;height:100px;" src='<?=$eo_signatur;?>'><br>
                                                <!-- signetur<br><br> -->
                                                <strong>Executive Officer</strong><br>
                                                <strong>Water Supply Section,</strong><br>
                                                <strong style="text-transform: uppercase;"><?=$ulb['ulb_name']?>,</strong>
                                            </span>
                                        </div><br><br>
                                        <div class="col-md-12">
                                            <p><strong>Tilipi: -</strong> Pipe larine inspector / conveyor engineer, water supply branch, <?=$ulb['ulb_name']?>, <?=$ulb['city']?> with this instruction, in accordance with the order approved before them, by combining water from Palembar, will report it to the office within 15 days.</p>
                                        </div>
                                    </div>
    
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <span><img style="width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>'></span>
                                            <span class="pull-right">
                                                <!-- <img style="width:100px;height:100px;" src='<?php echo base_url('public/assets/img/water/'.$ulb['city'].'/signetur/water_sig.png');?>'><br> -->
                                                <img style="width:100px;height:100px;" src='<?=$eo_signatur;?>'><br>
                                                <!-- signetur<br><br> -->
                                                <strong>Executive Officer</strong><br>
                                                <strong>Water Supply Section,</strong><br>
                                                <strong style="text-transform: uppercase;"><?=$ulb['ulb_name']?>,</strong>
                                            </span>
                                        </div><br><br>                                
                                    </div>
                                </div>
                                <br>
                                
                                <!-- <div class="water_mark"><img src="<?=base_url();?>/public/assets/img/logo/<?=$ulb['ulb_mstr_id']?>.png"/></div> -->

                            </div>

                            <div class="HindiFont">
                                  <div style = "border: 2px dotted ; padding:1rem;">                                
                                    <div style="text-align:center;  line-height: 2rem; margin-top:1rem;">
                                        <img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png');?>'><br>
                                        <strong style="text-transform: uppercase;"><?=isset($ulb['ulb_name_hindi'])?$ulb['ulb_name_hindi']:$ulb['ulb_name']?></strong><br>
                                        <span>जल आपूर्ति शाखा </span><br>
                                        <span>झारखंड नगरपालिका जलकार्य , जल अधिभार </span><br>
                                        <span>एव जल संयोजन नियमावली -  <?=(isset($data['verify_date']) && ($data['verify_date'])>='2020-12-31')?'2020':'2020'?></span><br><br>
                                        <strong style="border: 2px solid; padding:2.5px 4rem;font-size:large;margin-top:1rem;">मीटर सहित जल संयोजन आदेश प्रपत्र</strong><br><br>
                                        <span>विषय:- Residential परिसर में मीटर सहित जल संयोजन प्राप्त करने के सम्बन्ध में।</span><br><br>
                                    </div>
                                    <table width="100%">
                                        <tbody class="norap">
                                            <tr>
                                                <td>Consumer ID</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['consumer_no'])?$data['consumer_no']:''?></strong></td>
                                                <td>Application No.</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['application_no'])?$data['application_no']:''?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Year</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['year'])?$data['year']:''?></strong></td>                                        
                                            </tr>
                                            <tr>
                                                <td>Receiving Date</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['recieved_date'])?$data['recieved_date']:''?></strong></td>
                                                <td>Approval Date</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['verify_date'])?$data['verify_date']:''?></strong></td>                                        
                                            </tr>
                                            <tr>
                                                <td>Vide Receipt Number</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['transaction_no'])?$data['transaction_no']:''?></strong></td>
                                                <td>Payment Date</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['transaction_date'])?$data['transaction_date']:''?></strong></td>                                        
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>Connection date  </td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['apply_date'])?$data['apply_date']:'N/A';?></strong></td></td>                                        
                                            </tr>
                                            <tr class="title">
                                                <td cols='6'><strong>1. आवेदक की विवरणी</strong></td>                                        
                                            </tr>
                                            <tr>
                                                <td>वार्ड संख्या</td>
                                                <td>:</td>
                                                <td cols=3><strong><?=isset($data['ward_no'])?$data['ward_no']:''?></strong></td>
                                                                                    
                                            </tr>
                                            <tr>
                                                <td>आवेदक का नाम</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['ownere_name'])?$data['ownere_name']:''?></strong></td>
                                                <td>पिता / पति का नाम</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['father_name'])?$data['father_name']:''?></strong></td>                                        
                                            </tr>
                                            <tr>
                                                <td>पत्राचार का पता</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['address'])?$data['address']:''?></strong></td>
                                                <td>मोबाइल नंबर</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['mobile_no'])?$data['mobile_no']:''?></strong></td>                                        
                                            </tr>
                                            <tr>
                                                <td>ईमेल</td>
                                                <td>:</td>
                                                <td></td>
                                                <td>प्लाट संख्या</td>
                                                <td>:</td>
                                                <td></td>                                        
                                            </tr>
                                            <tr>
                                                <td>होल्डिंग संख्या अगर है तो</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['holding_no'])?$data['holding_no']:''?></strong></td>
                                                <td>सुविधा शुल्क मेमो संख्या अगर है तो</td>
                                                <td>:</td>
                                                <td></td>                                        
                                            </tr>
                                            <tr>
                                                <td>निर्मित क्षेत्रफल वर्ग मीटर में</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['area_sqmt'])?$data['area_sqmt']:''?></strong>&nbsp; SqMtr.</td>
                                                <td>संयोजन का माध्यम</td>
                                                <td>:</td>
                                                <td><strong><?=isset($data['apply_from'])?$data['apply_from']:''?></strong></td>                                        
                                            </tr>                                    
                                        </tbody>
                                    </table>
                                    <table width="100%">
                                        <tbody class="norap">
                                            <tr class="title" >
                                                <td cols=9 ><strong>2.	नियमावली - 2020 के आलोक में निर्धारित दर के अनुसार<strong></td>
                                            </tr>
                                            <tr>
                                                <td>जल संयोजन शुल्क</td>
                                                <td><strong><?=isset($data['conn_fee'])?$data['conn_fee']:''?></strong></td>
                                                <td>श्रेणी</td>
                                                <td><strong><?=isset($data['category'])?$data['category']:''?></strong></td>
                                                <td>अतिरिक्त प्रभार (निरीक्षण के दौरान)</td>
                                                <td><strong><?=isset($data['site_inspection'])?$data['site_inspection']:''?></strong></td>                                        
                                            </tr>
                                            <tr>
                                                
                                                <td>जमा की गई कुल राशि</td>
                                                <td><strong><?=isset($data['total_diposit'])?$data['total_diposit']:''?></strong></td>  
                                                <td>कुल राशि</td>
                                                <td><strong><?=isset($data['total_charge'])?$data['total_charge']:''?></strong></td>                                           
                                            </tr>
                                           
                                        </tbody>
                                    </table><br>
                                    <div class="row">
                                        <div class="col-md-12 mt-3">
                                            <p>पानी का नक्शा कनेक्शन स्वीकृत है। जिसमें 1 टैप जोड़ें और फेरूल का आकार 9 mm. है</p>
                                        </div>
                                    </div>
                                    <br>
                                    <table width="100%">
                                        <tbody class="norap">
                                            <tr class="tital">
                                                <td>
                                                    <strong>अनुलग्नक :- स्वीकृत लाईन प्लान ।</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <dl>
                                                        <dd>नोट :-</dd>
                                                        <dt>
                                                            <ol>
                                                                <li>उपभोक <?=isset($ulb['ulb_name_hindi'])?$ulb['ulb_name_hindi']:$ulb['ulb_name']?> पाईप लाईन निरीक्षक / कनीय अभियंता की उपस्थिति में स्वीकृत फेरूल संयोजन करायेंगे तथा सर्विस पाईप नाली में नहीं बिछाएंगे।</li>
                                                                <li>जल संयोजन की तिथि से जल भार (जल चार्ज) रूo  <?=(isset($data['verify_date']) && ($data['verify_date'])>='2020-12-31')?'9':'9';?> प्रति किलो (1000) लीटर भुगतान करना होगा।</li>
                                                                <li> तकनीकी स्वीकृती के अनुरुप जल संयोजन कराने एवं 15 दिनों के अंदर ISI मार्क वाटर मीटर लगाकर सहायक अभियंता, <?=isset($ulb['ulb_name_hindi'])?$ulb['ulb_name_hindi']:$ulb['ulb_name']?> जलापूर्ति शाखा, को सूचित करना सुनिश्चित करेंगे अन्यथा वर्ग मीटर के आधार पर दर (+10% Penalty) से जल शुल्क की वसूली की जाएगी ।</li>
                                                                <li>जल संयोजन / मीटर लगाने की सूचना उपभोक्ता को स्वयं लिखित रूप में <?=isset($ulb['ulb_name_hindi'])?$ulb['ulb_name_hindi']:$ulb['ulb_name']?> को देनी होगी।</li>
                                                                <li>(A)उपभोक्ता को निर्धारित तिथि के अंदर जल कर विपत्र का भुगतान करना अनिवार्य है अन्यथा 1.5% की दर से साधारण ब्याज लगाया जायेगा।
                                                                        <br>(B) निर्धारित तिथि तक विपत्र का भुगतान नहीं करने पर जल संयोजन काट दिया जायेगा तथा पुर्नसंयोजन (Reconnection) हेतु अध्यतन जलकर भुगतान के साथ रूo 1000/ - अधिभार देना होगा ।
                                                                </li>
                                                                <li>प्रतिलिपि पाइपलाइन निरीक्षक कनीय अभियंता जलापूर्ति शाखा <?=isset($ulb['ulb_name_hindi'])?$ulb['ulb_name_hindi']:$ulb['ulb_name']?> को इस निर्देष के साथ कि अपने समक्ष स्वीकृत आदेश के अनुरूप पलम्बर से जल संयोजन कराकर इस कार्यालय को 15 दिनों के अन्दर रतिवेदित् करेंगे।</li>
                                                            </ol>
                                                        </dt>
                                                    </dl>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <span class="pull-right">
                                                <!-- <img style="width:100px;height:100px;" src='<?php echo base_url('public/assets/img/water_sig.png');?>'><br> -->
                                                <img style="width:100px;height:100px;" src='<?=$eo_signatur;?>'><br>
                                                <!-- signetur<br><br> -->
                                                <strong>कार्यपालक अभियंता</strong><br>
                                                <strong>जलापूर्ति शाखा,</strong><br>
                                                <strong style="text-transform: uppercase;"><?=isset($ulb['ulb_name_hindi'])?$ulb['ulb_name_hindi']:$ulb['ulb_name']?>,</strong>
                                            </span>
                                        </div><br><br>
                                        <div class="col-md-12">
                                            <p><strong>प्रतिलिपि : -</strong> पाइपलाइन निरीक्षक / कनीय अभियंता, जलापूर्ति शाखा, <?=isset($ulb['ulb_name_hindi'])?$ulb['ulb_name_hindi']:$ulb['ulb_name']?>, <?=$ulb['city']?> को इस निर्देष के साथ कि अपने समक्ष स्वीकृत आदेश के अनुरूप पलम्बर से जल संयोजन कराकर इस कार्यालय को 15 दिनों के अन्दर प्रतिवेदित् करेंगे।</p>
                                        </div>
                                    </div>
    
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <span><img style="width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>'></span>
                                            <span class="pull-right">
                                                <!-- <img style="width:100px;height:100px;" src='<?php echo base_url('public/assets/img/water_sig.png');?>'><br> -->
                                                <img style="width:100px;height:100px;" src='<?=$eo_signatur;?>'><br>
                                                <!-- signetur<br><br> -->
                                                <strong>कार्यपालक अभियंता</strong><br>
                                                <strong>जलापूर्ति शाखा,</strong><br>
                                                <strong style="text-transform: uppercase;"><?=isset($ulb['ulb_name_hindi'])?$ulb['ulb_name_hindi']:$ulb['ulb_name']?>,</strong>
                                            </span>
                                        </div><br><br>                                
                                    </div>
                                </div>
                                <br>              
                            </div>

                            <div style = "border: 2px dotted ; padding:1rem;">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>OWNER NAME</td>
                                            <td>:</td>
                                            <td><strong><?=isset($data['ownere_name'])?$data['ownere_name']:''?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>PURPOSE</td>
                                            <td>:</td>
                                            <td><strong>DOMESTIC WATER PIPELINE WITH METER CONNECTION</strong></td>
                                        </tr>
                                        <tr>
                                            <td>ADDRESS</td>
                                            <td>:</td>
                                            <td><strong><?=isset($data['address'])?$data['address']:''?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>WARD NO</td>
                                            <td>:</td>
                                            <td><strong><?=isset($data['ward_no'])?$data['ward_no']:''?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>HOLDING/PLOT NO.</td>
                                            <td>:</td>
                                            <td><strong><?=isset($data['holding_no'])?$data['holding_no']:''?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>MOBILE NO.</td>
                                            <td>:</td>
                                            <td><strong><?=isset($data['mobile_no'])?$data['mobile_no']:''?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>PIPELINE</td>
                                            <td>:</td>
                                            <td><strong><?=isset($data['pipeline_type'])?$data['pipeline_type']:''?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>CATEGORY</td>
                                            <td>:</td>
                                            <td><strong><?=isset($data['category'])?$data['category']:''?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">   
                                <div class="col-md-12">
                                    <?php
                                        if(!isset($je_ts_map))
                                        {
                                            ?>
                                            <img src="<?=base_url();?>/public/assets/img/water/<?=$ulb['city']?>/Map-4.png" width="100%" />
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <img src="<?=$je_ts_map?>" width="100%" />
                                            <?php
                                        }
                                    ?>
                                    <div id="txt-left-dtl" class='col-sm-5 col-md-5 col-lg-5 col-xl-5 text-center' >Distribution Pipe:- <?=isset($data['pipeline_size_type'])?$data['pipeline_size_type']:''?>  <?=isset($data['pipeline_size'])?$data['pipeline_size']:''?></div>
                                    <div id="txt-right-dtl" class="col-sm-5 col-md-5 col-lg-5 col-xl-5 text-center">Permissible Pipe :- <?=isset($data['pipe_type'])?$data['pipe_type']:''?>  <?=isset($data['pipe_size'])?$data['pipe_size']:''?>
                                        <br />
                                        Ferule:-  <?=isset($data['ferrule_type'])?$data['ferrule_type']:''?> MM</div>
                                
                                    <div class="col-md-2">                                        
                                        <span class="pull-right">
                                            <!-- <img style="width:100px;height:100px;" src='<?php echo base_url('public/assets/img/water/'.$ulb['city'].'//signetur/'.(isset($data['emp_details_id'])?$data['emp_details_id']:'').'.png');?>'><br> -->
                                            <img style="width:100px;height:100px;" src='<?=$je_signatur;?>'><br>
                                            <!-- signetur<br><br> -->
                                            <strong class="EnglishFont" >Junior Engineer</strong><strong class="HindiFont" >कनीय अभियंता</strong>
                                            <br>
                                            <strong class="EnglishFont" >Water Supply Section,</strong>
                                            <strong class="HindiFont">जलापूर्ति शाखा,</strong>
                                            <br>
                                            <strong style="text-transform: uppercase;" class="EnglishFont" ><?=$ulb['ulb_name']?>,</strong><strong style="text-transform: uppercase;" class="HindiFont" ><?=isset($ulb['ulb_name'])?$ulb['ulb_name_hindi']:$ulb['ulb_name']?>,</strong>
                                        </span>
                                    </div><br><br>
                                </div>

                            </div>
                                
                        </div>
                        
                        <div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button onclick="printDiv('printable')">print</button>
                                    
                                </div>
                            </div>
                        </div>
					</div>
				</div>




    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php 		
		//echo $this->include('layout_vertical/footer');		
  		
 ?>
 <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script>
    function printDiv(divName) { //alert('asfasdf'); return false;
	var printData = document.getElementById(divName).innerHTML;
	var data = document.body.innerHTML;
	
	document.body.innerHTML = printData;
	window.print();
	window.location.reload();
	document.body.innerHTML = data;
	}

    $('#HindiFontBtn').click(function(){
        $(".HindiFont").css('display','inline-block');
        $(".EnglishFont").css('display','none');          
    });

    $('#EnglishFontBtn').click(function(){
        $(".EnglishFont").css('display','inline-block');
        $(".HindiFont").css('display','none'); 
         
    });
</script>