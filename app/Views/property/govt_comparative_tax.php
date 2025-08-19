<?php
    $session = session();
    if($session->has("emp_details") && !empty($session->get("emp_details"))) {
        echo $this->include('layout_vertical/header'); 
    } else {
        echo $this->include('layout_home/header');
    }
?>
<style>
@media print {
	.print_watermark {
        background-image:url(<?=base_url()."/public/assets".$ulb_mstr_name["watermark_path"];?>) !important;
		background-repeat:no-repeat !important;
		background-position:center !important;
        -webkit-print-color-adjust: exact; 
        z-index: 999;
    }
}
.print_watermark{
	background-color:#FFFFFF;
	background-image:url(<?=base_url()."/public/assets".$ulb_mstr_name["watermark_path"];?>) !important ;
	background-repeat:no-repeat;
	background-position:center;
    z-index: 999;
}
#para {
    color: #000;
}
</style>
<!--CONTENT CONTAINER-->
<!--Page content-->
<div id="content-container">
    <div id="page-content"  style="margin: 0px 0px; padding: 0px  0px; ">
        <div class="panel panel-bordered panel-mint">
            <div class="panel-body">
                <div class="col-sm-12 noprint text-right">
                    <button type="button" class="btn btn-info btn_wait_load" onclick="history.back()">Back</button>
                    <button class="btn btn-mint btn-icon" onclick="print();"><i class="demo-pli-printer icon-lg"></i></button>
                </div>
                <div class="print_watermark">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center;">
                            <img style="height:80px;width:80px; border-radius: 50px;" src='<?= base_url() . "/public/assets/" . $ulb_details['logo_path']; ?>'><br>
                            <label style="color:#000;">
                                <strong style="font-size:24px;">कार्यालय: <?= $ulb_details['ulb_name_hindi']; ?></strong><br />
                                <strong>
                                    <small>(राजस्व शाखा)</small><br>
                                    <small>कचहरी रोड, रांची, पिन नंबर -834001</small>
                                    <br>
                                    <small>E-mail ID - <span style="border-bottom:1.5px dotted black;">
                                            <?php
                                            if (isset($prop_owner_detail)) {
                                                foreach ($prop_owner_detail as $owner) { ?>
                                                    <?= $owner['email'] . ", " ?>
                                            <?php }
                                            }
                                            ?></span></small>
                                </strong>
                            </label><br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mar-lft">
                            <label style="font-size:14px; font-weight: bold;">पत्रांक : </label>
                        </div>
                        <div class="col-md-3">
                            <label style="font-size:14px; font-weight: bold;">दिनांक : <span style="border-bottom:1.5px dotted black;"><?= date('d-m-y')   ?></span></label>
                        </div>
                        <div class="col-md-12" style="text-align: center;">
                            <label style="font-weight: bold; font-size:20px;text-decoration: underline;">सूचना</label> <br />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>
                                <label style="font-size:14px;">श्री / श्रीमती /मेसर्स : 
                                    <span style="border-bottom:1.5px dotted black;">
                                        <?=$office_name??"";?>
                                    </span>
                                </label>
                            </strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>
                                <label style="font-size:14px;">पिता/पति का नाम : 
                                    <span style="border-bottom:1.5px dotted black;">
                                        N/A
                                    </span>
                                </label>
                            </strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>
                                <label style="font-size:14px;">एप्लीकेशन नंबर : 
                                    <span style="border-bottom:1.5px dotted black;">
                                        <?= $application_no ?>
                                    </span> 

                                    <span style="margin-left: 60px;">वार्ड नं. :</span>
                                    <span style="border-bottom:1.5px dotted black;">
                                        <?= $ward_no ?>
                                    </span>
                                </label>
                            </strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>
                                <label style="font-size:14px;">पता :
                                    <span style="border-bottom:1.5px dotted black;">
                                        <?= $prop_address ?>
                                    </span>
                                </label>
                            </strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p id="para" style="text-align: justify;">
                                झारखण्ड नगरपालिका अधिनियम 2011 की धारा 590 द्वारा प्रदत्त शक्तियों का प्रयोग करते हुए झारखण्ड के राज्यपाल नगर विकास एवं आवास विभाग, झारखण्ड, राँची की अधिसूचना 1511 दिनांक 29.04.2022 के अनुसार झारखण्ड नगरपालिका सम्पत्ति कर (निर्धारण, संग्रहण और वसूली) में संशोधन किया गया है. इस संशोधन के अनुसार झारखण्ड राज्य के सभी नगर निगम, परिषद और पंचायत में रहने वाले आम नागिरिकों और व्यवसायियों को सुचित किया जाता है कि अब से सेवा शुल्क कर कि वसुली सर्किलरेट के लिए नियमों के आधार पर वित्तीय वर्ष 2022 के अप्रैल महीने से की जाएगी.
                            </p>
                        </div>
                        <div class="col-md-12">
                            <p id="para" style="text-align: justify;">
                                सम्पत्तिकर नियम 2013 में संशोधन किया गया है, अतः वित्तिय वर्ष 2022-23 से सम्पत्तिकर का मूल्याकन वार्षिक किराया मूल्य के स्थान पर पूजीगत मूल्य के आधार पर किया जायेगा। आपके भवन (होल्डिंग नं० - <?=$new_holding_no??"N/A";?>) के घृतिकर की गणना 01.04.2022 से सर्किलरेट के अनुसार किया जायेगा।
                            </p>
                        </div>
                        <div class="col-md-12">
                            <p id="para" style="text-align: justify;">
                                झारखण्ड नगरपालिका कर भुगतान (समय, प्रक्रिया तथा वसूली) विनियम संशोधन 2022 के विहित प्रावधान के अनुसार आपको उक्त अवधि का धृति कर का भुगतान करना है।
                            </p>
                        </div>
                        <div class="col-md-12">
                            <p id="para" style="text-align: justify;">
                                सेवा शुल्क कर कि वार्षिक किरायादर एव पूजीगत मूल्य के आधार पर तुलनात्मक सेवा शुल्क कर की गणना निम्नवत है :
                            </p>
                        </div>


                    </div>
                
                    <br />
                    <div class="row">
                        <h3 class="panel-title" style="font-weight: bold;">Annual Rental Value - As ARV Rule (Effect From 01-04-2016 to 31-03-2022)</h3>
                        <div class="col-md-12 mar-lft pad-btm">
                            <label>a. Carpet area for residential - 70% of buildup area</label><br />
                            <label>b. Carpet area for commercial - 80% of buildup area</label>
                        </div>
                        <div class="col-md-12">
                            <b>Annual Rental Value (ARV) = Carpet Area X Usage Factor X Occupancy Factor X Rental Rate</b>
                        </div>
                    </div>
                    <div class="row mar-top">
                        <h3 class="panel-title" style="font-weight: bold;">Capital Value - As Per Current Rule (Effect From 01-04-2022)</h3>
                        <div class="col-md-12 mar-lft">
                            <label>a. Residential - 0.075%</label>
                        </div>
                        <div class="col-md-12 mar-lft">
                            <label>b. Commercial - 0.150%</label>
                        </div>
                        <div class="col-md-12 mar-lft">
                            <label>c. Commercial & greater than 25000 sqft - 0.20%</label>
                        </div>
                    </div>
                    <div class="row mar-btm">
                        <div class="col-md-12">
                            <b>Service Tax = Circle Rate X Buildup Area X Occupancy Factor X Tax Percentage X Calculation Factor X Matrix Factor Rate (<span class="text-danger text-xs">Only in case of 100% residential property</span>)</b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                            <?php if (isset($floorDtlArr)) { ?>
                            <table class="table table-bordered text-xs">
                                <thead class="bg-trans-dark text-dark text-xs">
                                    <tr>
                                        <th>Floor</th>
                                        <th>Built-up-area</th>
                                        <th>Usage Factor</th>
                                        <th>Occ Factor </th>
                                        <th>Carpet area (70%/80%)</th>
                                        <th>Rental Rate / Matrix Factor</th>
                                        <th>Tax Perc.</th>
                                        <th>Calculation Factor</th>
                                        <th>(Annual Rental Values) p.s.f</th>
                                        <th>Circle Rate</th>
                                        <th>ARV Total Service Tax</th>
                                        <th>CV Total Service Tax</th>
                                        <th>CV 2024 Total Service Tax</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                            <?php
                                $totalArv = 0;
                                $totalCV = 0;
                                foreach ($floorDtlArr AS $key=>$floorTaxDtl) {
                            ?>
                                    <tr>
                                        <td><?=$floorTaxDtl["floor_name"];?></td>
                                        <td><?=$floorTaxDtl["builtup_area"];?></td>
                                        <td><?=$floorTaxDtl["new_arv_cal_method"]["usage_factor"];?></td>
                                        <td><?=$floorTaxDtl["new_arv_cal_method"]["occupancy_factor"];?></td>
                                        <td><?=$floorTaxDtl["new_arv_cal_method"]["carper_area"];?></td>
                                        <td><?=$floorTaxDtl["new_arv_cal_method"]["rental_rate"];?></td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td><?=$floorTaxDtl["new_arv"];?></td>
                                        <td>N/A</td>
                                        <td><?=$floorTaxDtl["new_arv"]*0.02;?> <?php $totalArv += $floorTaxDtl["new_arv"]*0.02;?></td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <td><?=$floorTaxDtl["floor_name"];?></td>
                                        <td><?=$floorTaxDtl["builtup_area"];?></td>
                                        <td>N/A</td>
                                        <td><?=$floorTaxDtl["cv_2022_cal_method"]["occupancy_rate"];?></td>
                                        <td>N/A</td>
                                        <td><?=$floorTaxDtl["cv_2022_cal_method"]["matrix_factor_rate"];?></td>
                                        <td><?=$floorTaxDtl["cv_2022_cal_method"]["resi_comm_type_rate"]*100;?>%</td>
                                        <td><?=$floorTaxDtl["cv_2022_cal_method"]["calculation_factor"];?></td>
                                        <td>N/A</td>
                                        <td><?=$floorTaxDtl["cv_2022_cal_method"]["cvr"];?></td>
                                        <td>N/A</td>
                                        <td><?=$floorTaxDtl["cv"];?> <?php $totalCV += $floorTaxDtl["cv"];?></td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <td><?=$floorTaxDtl["floor_name"];?></td>
                                        <td><?=$floorTaxDtl["builtup_area"];?></td>
                                        <td>N/A</td>
                                        <td><?=$floorTaxDtl["cv_2024_cal_method"]["occupancy_rate"];?></td>
                                        <td>N/A</td>
                                        <td><?=$floorTaxDtl["cv_2024_cal_method"]["matrix_factor_rate"];?></td>
                                        <td><?=$floorTaxDtl["cv_2024_cal_method"]["resi_comm_type_rate"]*100;?>%</td>
                                        <td><?=$floorTaxDtl["cv_2024_cal_method"]["calculation_factor"];?></td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td><?=$floorTaxDtl["cv_2024_cal_method"]["cvr"];?>
                                </td>
                                        <td>N/A</td>
                                        <td><?=$floorTaxDtl["cv24"];?> <?php $totalCV24 += $floorTaxDtl["cv24"];?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="10"></td>
                                    </tr>
                            <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Total</td>
                                        <td><?=round($totalArv, 2);?></td>
                                        <td><?=round($totalCV, 2);?></td>
                                        <td><?=round($totalCV24, 2);?></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <?php } ?>
                    </div>

            </div>
            <br />
        </div>
    </div>
</div>
<?php 
if($session->has("emp_details") && !empty($session->get("emp_details"))) {
    echo $this->include('layout_vertical/footer'); 
} else {
    echo $this->include('layout_home/footer');
}
?>