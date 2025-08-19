<?=$this->include("layout_mobi/header");

?>
<!-- <script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script>  -->

<script type="text/javascript">
    function OperateDropDown(radio, control, hidden)
	{
        var rdo = document.getElementById(radio.trim());
        var ctrl = document.getElementById(control.trim());
        var hid = document.getElementById(hidden.trim());
        var hid_val = hid.value;
        if (rdo.value == "1") {
            var opt = ctrl.options;
            var pos = 0;
            for (var j = 0; j < opt.length; j++) {
                if (opt[j].value == hid_val) {
                    pos = j;
                    break;
                }
            }
            ctrl.selectedIndex = pos;
            ctrl.disabled = true;
        }
        else
		{
            ctrl.selectedIndex = 0;
            ctrl.disabled = false;
        }
    }


    function OperateTexBox(radio, control, hidden)
	{
        var rdo = document.getElementById(radio.trim());
        var ctrl = document.getElementById(control.trim());
        var hid = document.getElementById(hidden.trim());
        var hid_val = hid.value;
        if (rdo.value == "1")
		{
            ctrl.value = hid_val;
            ctrl.readOnly = true;
        }
        else
		{
            ctrl.value = "";
            ctrl.readOnly = false;
        }
    }

    function HideUnhide(test,target,targetdate,tragetarea)
    {
		//alert(targetdate);
        var ddl=document.getElementById(test);

        var val = ddl.options[ddl.selectedIndex].value;
        var tw_details=document.getElementsByClassName(target);
        // alert(val);
        if(val=="1")
        {
			//alert(tw_details.length);
            for(var i=0;i<tw_details.length;i++)
            {   //alert(targetdate);
                tw_details[i].style.display="";
                var tdate = document.getElementById('assess_'+targetdate).value;

                var tarea =  document.getElementById('assess_'+tragetarea).value;
                //alert(tdate);

                document.getElementById(targetdate).value = tdate;
                document.getElementById(tragetarea).value = tarea;
            }
        }
        else
        {
            for(var i=0;i<tw_details.length;i++)
            {
                tw_details[i].style.display="none";
                /*document.getElementById(targetdate).value = "";
                document.getElementById(tragetarea).value = "";*/
            }
        }
    }



  
		
	
	var occ=<?=$_POST["countarea"] ?? 1;?>;
    function AddOccupancy()
	{
		++occ;
		$str='<div class="panel panel-bordered panel-dark container-fluid">\
					<div class="panel-body">\
					<div class="row">\
						<div class="col-sm-12">\
							Floor No \
						</div>\
						<div class="col-sm-12">\
							<select name="floor_id[]" id="floor_id'+occ+'" class="form-control">\
								<option value="">Select</option>\
								<?php
								foreach ($floor_list as  $valfloor)
								{
									?>
									<option value="<?=$valfloor["id"]?>"><?=$valfloor["floor_name"]?></option>\
									<?php 
								}
								?>
								</select>\
						</div>\
					</div>\
					<div class="row">\
						<div class="col-sm-12">\
							Use Type\
						</div>\
						<div class="col-sm-12">\
							<select name="use_type_id[]" id="use_type_id'+occ+'" class="form-control">\
								<option value="">Select</option>\
								<?php
								foreach ($usage_list as  $valuse)
								{
									?>
									<option value="<?=$valuse["id"]?>"><?=$valuse["usage_type"]?></option>\
									<?php 
								}
								?>
							</select>\
						</div>\
					</div>\
					<div class="row">\
						<div class="col-sm-12">\
							Occupancy Type\
						</div>\
						<div class="col-sm-12">\
							<select name="occupancy_type_id[]" id="occupancy_type_id'+occ+'" class="form-control">\
								<option value="">Select</option>\
								<?php
								foreach ($occupancy_list as  $valoccu)
								{
									?>
									<option value="<?=$valoccu["id"]?>"><?=$valoccu["occupancy_name"]?></option>\
									<?php 
								}
								?>
								</select>\
						</div>\
					</div>\
					<div class="row">\
						<div class="col-sm-12">\
							Construction Type\
						</div>\
						<div class="col-sm-12">\
							<select name="construction_type_id[]" id="construction_type_id'+occ+'" class="form-control">\
								<option value="">Select</option>\
								<?php
								foreach ($const_type_list as  $valcons)
								{
									?>
									<option value="<?=$valcons["id"]?>"><?=$valcons["construction_type"]?></option>\
									<?php 
								}
								?>
							</select>\
						</div>\
					</div>\
					<div class="row">\
						<div class="col-sm-12">\
							Built Up Area  (in Sq. Ft)\
						</div>\
						<div class="col-sm-12">\
							<input type="tel" name="builtup_area[]" value="" id="builtup_area'+occ+'" class="form-control">\
						</div>\
					</div>\
					<div class="row">\
						<div class="col-sm-12">\
							Date From\
						</div>\
						<div class="col-sm-12">\
							<input type="month" name="occ_mm[]" id="occ_mm'+occ+'" placeholder="Month" value="" onchange="checkdate(\'M\',1)" class="form-control">\
							<input type="text" name="occ_yyyy[]" id="occ_yyyy'+occ+'" maxlength="4" minlength="4" placeholder="Year" value="2021" onchange="checkdate(\'Y\',1)" class="form-control">\
						</div>\
					</div>\
					<div class="row">\
						<div class="col-sm-12">\
							<span class="btn btn-info"><a href="javascript:AddOccupancy()" style="text-decoration:none; color:#FFFFFF">Add</a></span>\
							<span class="btn btn-danger"><a href="javascript:RemoveOccupancy('+occ+')" style="text-decoration:none; color:#FFFFFF">Remove</a></span>\
						</div>\
					</div>\
				</div>\
				</div>';
			console.log($str);
            var value=0;
            ++occ;
            var div = document.createElement("div");
            div.id = "occ_tr_" + occ;
            div.className = "panel";
            var clr = document.createElement("div");
            clr.className = "clr";
            var ct=document.getElementById("countarea").value=occ ;
            /******************Creating One Row Start*********************/
            var td1 = document.createElement("div");
            td1.className = "span12";
            div.appendChild(td1);

            var td1_1 = document.createElement("div");
            td1_1.className = "span2";
            td1_1.innerHTML="Floor No.";
            td1.appendChild(td1_1);

            var td1_2 = document.createElement("div");
            td1_2.className = "span4";
            var floor_id = document.createElement("select");
            floor_id.style.marginLeft = "0px";

            floor_id.id = "floor_id" + (occ);
            floor_id.name = "floor_id[]";
            floor_id.required = "required";
            var sel = document.createElement("option");
            sel.value = "";
            sel.text = "Select";
            floor_id.appendChild(sel);

            <?php
            foreach ($floor_list as  $valfloor) {
            ?>
            var floor1 = document.createElement("option");
            floor1.value = "<?=$valfloor["id"]?>";
            floor1.text = "<?=$valfloor["floor_name"]?>";
            floor_id.appendChild(floor1);
            <?PHP } ?>

            td1_2.appendChild(floor_id);
            td1.appendChild(td1_2);

            var td1_ut = document.createElement("div");
            td1_ut.className = "span2";
            td1_ut.innerHTML="Use Type";
            td1.appendChild(td1_ut);

            var td1_u = document.createElement("div");
            td1_u.className = "span4";
            var use_type_id = document.createElement("select");
            //use_type_id.style.width = "90%"
            use_type_id.id = "use_type_id" + (occ);
            use_type_id.name = "use_type_id[]";
            use_type_id.required = "required";
            use_type_id.className = "use_type";

            var select2 = document.createElement("option");
            select2.value = "";
            select2.text = "Select";

            use_type_id.appendChild(select2);

            <?php
            foreach ($usage_list as  $valuse) {
            ?>
            var use_typ1 = document.createElement("option");
            use_typ1.value = "<?=$valuse["id"]?>";
            use_typ1.text = "<?=$valuse["usage_type"]?>";
            use_type_id.appendChild(use_typ1);
            <?PHP } ?>
            td1_u.appendChild(use_type_id);
            td1.appendChild(td1_u);

            /******************Creation of Row End*********************/
            /******************Creation of another Row Start*********************/
            var tdn = document.createElement("div");
            tdn.className = "span12";
            tdn.style.marginLeft="0px";
            div.appendChild(tdn);

            var tdo_s = document.createElement("div");
            tdo_s.className = "span2";
            tdo_s.style.marginLeft="0px";
            tdo_s.innerHTML="Occupancy Type";
            tdn.appendChild(tdo_s);

            var td1_ss = document.createElement("div");
            td1_ss.className = "span4";
            var occupancy_type = document.createElement("select");
            occupancy_type.id = "occupancy_type_id" + (occ);
            occupancy_type.name = "occupancy_type_id[]";
            occupancy_type.required = "required";

            var select4 = document.createElement("option");
            select4.value = "";
            select4.text = "Select";

            occupancy_type.appendChild(select4);
            <?php
            foreach ($occupancy_list as  $valoccu) {
            ?>
            var occ_type1 = document.createElement("option");
            occ_type1.value = "<?=$valoccu["id"]?>";
            occ_type1.text = "<?=$valoccu["occupancy_name"]?>";
            occupancy_type.appendChild(occ_type1);
            <?php } ?>
            td1_ss.appendChild(occupancy_type);
            tdn.appendChild(td1_ss);

            var tdo_c = document.createElement("div");
            tdo_c.className = "span2";
            //tdo_c.style.marginLeft="0px";
            tdo_c.innerHTML="Construction Type";
            tdn.appendChild(tdo_c);

            var td1_sc = document.createElement("div");
            td1_sc.className = "span4";
            var const_type = document.createElement("select");
            //const_type.style.width = "90%"
            const_type.id = "construction_type_id" + (occ);
            const_type.name = "construction_type_id[]";
            const_type.required = "required";

            var select3 = document.createElement("option");
            select3.value = "";
            select3.text = "Select";
            const_type.appendChild(select3);

            <?php
            foreach ($const_type_list as  $valcons) {
            ?>
            var const_typ1 = document.createElement("option");
            const_typ1.value = "<?=$valcons["id"]?>";
            const_typ1.text = "<?=$valcons["construction_type"]?>";
            const_type.appendChild(const_typ1);
            <?php } ?>
            td1_sc.appendChild(const_type);
            tdn.appendChild(td1_sc);

            /******************Creation of Row End*********************/
            /******************Creation of another Row Start*********************/
            var tdnn = document.createElement("div");
            tdnn.className = "span12";
            tdnn.style.marginLeft="0px";
            div.appendChild(tdnn);

            var tdo_sn = document.createElement("div");
            tdo_sn.className = "span2";
            tdo_sn.style.marginLeft="0px";
            tdo_sn.innerHTML="Built Up Area (in Sq. Ft)";
            tdnn.appendChild(tdo_sn);

            var td1_ssn = document.createElement("div");
            td1_ssn.className = "span4";
            var builtup_area = document.createElement("input");
            builtup_area.type = "text"
            builtup_area.id = "builtup_area" + (occ);
            builtup_area.name = "builtup_area[]";
            //builtup_area.style.width = "80%";
            builtup_area.required = "required digits";
            td1_ssn.appendChild(builtup_area);
            tdnn.appendChild(td1_ssn);

            var tdo_cn = document.createElement("div");
            tdo_cn.className = "span2";

            tdnn.appendChild(tdo_cn);


            /******************Creation of Row End*********************/
            /******************Creation of Last Row Start*********************/
            var tdnnl = document.createElement("div");
            tdnnl.className = "span12";
            tdnnl.style.marginLeft="0px";
            div.appendChild(tdnnl);

            var tdo_snl = document.createElement("div");
            tdo_snl.className = "span2";
            tdo_snl.style.marginLeft="0px";
            tdo_snl.innerHTML="From Date";
            tdnnl.appendChild(tdo_snl);

            var td1_ssnl = document.createElement("div");
            td1_ssnl.className = "span4";

            var sapn5 = document.createElement("div");
            sapn5.className = "span5";
            var occ_mm=document.createElement("input");
            occ_mm.type="text";
            occ_mm.name="occ_mm[]";
            occ_mm.id="occ_mm"+(occ);
            occ_mm.maxLength=2;
            occ_mm.required="required";
            occ_mm.placeholder="Month";
            occ_mm.setAttribute("onchange", "checkdate('M',"+occ+")");
            sapn5.appendChild(occ_mm);
            td1_ssnl.appendChild(sapn5);
            tdnnl.appendChild(td1_ssnl);

            var sapn6 = document.createElement("div");
            sapn6.className = "span6";
            var occ_yyyy=document.createElement("input");
            occ_yyyy.type="text";
            occ_yyyy.name="occ_yyyy[]";
            occ_yyyy.id="occ_yyyy"+(occ);
            occ_yyyy.maxLength=4;
            occ_yyyy.minLength=4;
            occ_yyyy.required="required";
            occ_yyyy.placeholder="Year";
            occ_yyyy.setAttribute("onchange", "checkdate('Y',"+occ+")");
            sapn6.appendChild(occ_yyyy);
            td1_ssnl.appendChild(sapn6);
            tdnnl.appendChild(td1_ssnl);


            //td1_ssn2.appendChild(sapn6);
            //tdnnl.appendChild(td1_ssn2);

			debugger;

            var sapn6n = document.createElement("div");
            sapn6n.className = "span6";

            sapn6n.innerHTML = "<a href='javascript:AddOccupancy()'><span class='btn btn-info'>ADD</span></a>&nbsp;&nbsp;&nbsp;<a href=\"javascript:RemoveOccupancy('occ_tr_" + occ + "')\" ><span class='btn btn-danger'>DELETE</span></a>"

            td1_ssnl.appendChild(sapn6n);
            tdnnl.appendChild(sapn6n);
            /******************Creation of Row End*********************/


            div.appendChild(clr);
            //if(occ==1){}
			console.log(div);
            document.getElementById("tr_floor_dtl_head").append(div);


        
    }

    //$.validator.messages.required = '';

    function RemoveOccupancy(elemid) {
        try {
            var count = document.getElementsByClassName("occu_details").length;
            var child = document.getElementById(elemid);
            if (count == 1){
                alert("At least one occupancy detail is required");}
            else
            { child.parentNode.removeChild(child);
                var cntarea = document.getElementById("countarea").value;
                document.getElementById("countarea").value=cntarea-1;}

        }
        catch (err) {
            alert(err.message);
        }
    }

    function addfloor(correctval){
        if(correctval==1){
            document.getElementById("chkfloor").disabled = true;
        }else{
            document.getElementById("chkfloor").disabled = false;
        }
    }

</script>
<!--CONTENT CONTAINER-->
	<div id="content-container">
		<!--Page content-->
		<div id="page-content">
			<form action="<?=base_url('SafVerification/field_verification/'.$id.'/'.$levelid);?>" id="form_tc_verification" name="FORMNAME1" method="post" onSubmit="return checkselected();">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"><b>Self Assessment - Field Survey</b></h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12" style="text-align: center;">
								<div class="col-md-4">
									<span style="font-weight: bold; color: #bb4b0a;"> Your Application No. is :</span> &nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=$saf_no?></span>
								</div>
								<div class="col-md-4">
									<span style="font-weight: bold; color: #bb4b0a;">Application Type:   </span>&nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=$Saf_detail["has_previous_holding_no"]=='f'? 'New Assessment':'Reassessment'?></span>
								</div>
								<div class="col-md-4">
									<span style="font-weight: bold; color: #bb4b0a;">Applied Date :   </span>&nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=$apply_date?></span>
								</div>
							</div>
						</div>
						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Ward No</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?php echo $ward_no;?>
										<input type="hidden" name="hid_ward_id" id="hid_ward_id" value="<?=$ward_mstr_id?>" />
									</div>
									<?php if($user_type_mstr_id==7){?>
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?php echo $vward_no; ?>
											<input type="hidden" name="vhid_ward_id" id="vhid_ward_id" value="<?=$vward_no?>" />
										</div>
									<?php }?>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<b>Check </b>
									</div>
									<div class="col-sm-6">
										<?php $chk="";$chk2="";$diswrd="";
										if(isset($_POST["rdo_ward_no"])){
											if(@$_POST["rdo_ward_no"]==1){ $chk='checked="checked"';  $diswrd='disabled="disabled"';}if(@$_POST["rdo_ward_no"]==0){ $chk2='checked="checked"';
											}
										}?>
										<input type="radio" name="rdo_ward_no" id="rdo_ward_no1" value="1" onClick="OperateDropDown('rdo_ward_no1', 'ward_id', 'hid_ward_id')" <?=$chk?> />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_ward_no" id="rdo_ward_no2"  value="0" onClick="OperateDropDown('rdo_ward_no2', 'ward_id', 'hid_ward_id')" <?=$chk2?> />&nbsp;&nbsp;Incorrect
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<b>Verification </b>
										<select name="ward_id" id="ward_id"  <?=$diswrd?> required class="form-control">
											<option value="">Select</option>
											<?php
											foreach ($ward_list as  $ward)
											{
												?>
												<option value="<?=$ward["id"]?>" <?php if(isset($_POST["ward_id"]) && $ward["id"]==$_POST["ward_id"]){?> selected="selected"<?php }?>><?=$ward["ward_no"]?></option>
												<?php 
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Zone</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?php echo $zone_id;?>
										<input type="hidden" name="hid_zone" id="hid_zone" value="<?=$zone_id?>" />
									</div>
									<?php if($user_type_mstr_id==7){?>
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?php echo $vzone_id; ?>
											<input type="hidden" name="vhid_zone" id="vhid_zone" value="<?=$vzone_id?>" />
										</div>
									<?php }?>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<b>Check </b>
									</div>
									<div class="col-sm-6">
										<?php $chk="";$chk2="";$diswrd="";
										if(isset($_POST["rdo_zone"])){
											if(@$_POST["rdo_zone"]==1){ $chk='checked="checked"';  $diswrd='disabled="disabled"';}if(@$_POST["rdo_zone"]==0){ $chk2='checked="checked"';
											}
										}?>
										<input type="radio" name="rdo_zone" id="rdo_zone1" value="1" onClick="OperateDropDown('rdo_zone1', 'zone', 'hid_zone')" <?=$chk?> />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_zone" id="rdo_zone2"  value="0" onClick="OperateDropDown('rdo_zone2', 'zone', 'hid_zone')" <?=$chk2?> />&nbsp;&nbsp;Incorrect
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<b>Verification </b>
										<select name="zone" id="zone"  <?=$diswrd?> required class="form-control">
											<option value="">Select</option>
											<option value="1" <?php if(isset($_POST["zone"]) && $_POST["zone"]==1){?> selected="selected"<?php }?>>1</option>
											<option value="2" <?php if(isset($_POST["zone"]) && $_POST["zone"]==2){?> selected="selected"<?php }?>>2</option>
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Property Type</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?php echo $property_type;?>
										<input type="hidden" name="hid_property_type" id="hid_property_type" value="<?=$prop_type_mstr_id?>" />
									</div>
									<?php if($user_type_mstr_id==7){?>
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?php echo $vproperty_type;?>
											<input type="hidden" name="hid_property_typev" id="hid_property_typev" value="<?=$vproperty_type?>" />
										</div>
									<?php }?>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<b>Check </b>
									</div>
									<div class="col-sm-6">
										<?php
										$chkprop=NULL;
										$chkprop2=NULL;
										$disprop=NULL;
										if(isset($_POST["rdo_property_type"]))
										{
											if($_POST["rdo_property_type"]==1)
											{
												$chkprop='checked="checked"';
												$disprop='disabled="disabled"';
											}
											if($_POST["rdo_property_type"]==0)
											{
												
												$chkprop2='checked="checked"';
											}
										}
										?>
										<input type="radio" name="rdo_property_type" id="rdo_property_type1" <?=$chkprop?>  value="1" onClick="OperateDropDown('rdo_property_type1', 'property_type_id', 'hid_property_type')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_property_type" id="rdo_property_type2"  value="0" <?=$chkprop2?> onClick="OperateDropDown('rdo_property_type2', 'property_type_id', 'hid_property_type')" />&nbsp;&nbsp;Incorrect
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<b>Verification </b>
										<select name="property_type_id" id="property_type_id"   <?=$disprop?> class="form-control">
											<option value="">Select</option>
											<?php
											foreach ($prop_type_list as  $proptype)
											{
												?>
												<option value="<?=$proptype["id"]?>"  <?php if(isset($_POST["property_type_id"]) && $_POST["property_type_id"]==$proptype["id"]){?> selected="selected"<?php }?>><?=$proptype["property_type"]?></option>
												<?php 
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<?php if(isset($_GET["tp"]) && $_GET["tp"]=='m'){?>

							<div class="panel panel-bordered">
								<div class="panel-heading" style="background:#1b8388f7;">
									<h3 class="panel-title"><b>Transfer Mode</b></h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6">
											<b>Self-Assessed </b>
										</div>
										<div class="col-sm-6">
											<?php if($transfar_mode_id>0){$query_trans="select * from prop_transfer_mode_master 	where id='".$transfar_mode_id."'";
													$exe_trans=pg_query($_SESSION["db_property"],$query_trans)or die("Invalid Table");
													$val_trans=pg_fetch_array($exe_trans);}
												?>
												<?php if($transfar_mode_id>0){echo $val_trans["transfer_mode"];}else{echo "No Declaration";}?>
											<input type="hidden" name="hid_transfer_mode" id="hid_transfer_mode" value="<?=$transfar_mode_id?>" />
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Check </b>
										</div>
										<div class="col-sm-6">
											<?php
											if(isset($_POST["rdo_transfer_mode"])){
												if(@$_POST["rdo_transfer_mode"]==1){ $chktrans='checked="checked"';  $distrans='disabled="disabled"';}if(@$_POST["rdo_transfer_mode"]==0){ $chktrans2='checked="checked"';
												}
											}?>
											<input type="radio" name="rdo_transfer_mode" id="rdo_transfer_mode1" <?=$chktrans?>  value="1" onClick="OperateDropDown('rdo_transfer_mode1', 'transfer_mode_id', 'hid_transfer_mode')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="rdo_transfer_mode" id="rdo_transfer_mode2"  value="0" <?=$chktrans2?> onClick="OperateDropDown('rdo_transfer_mode2', 'transfer_mode_id', 'hid_transfer_mode')" />&nbsp;&nbsp;Incorrect
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<b>Verification </b>
											<select name="transfer_mode_id" id="transfer_mode_id" <?=$distrans?> class="form-control">
												<option value="">Select</option>
												<?php
												$msql_trans="select * from prop_transfer_mode_master order by transfer_mode";
												$mrs_trans=pg_query($_SESSION["db_property"],$msql_trans)or die("Invalid Table");
												while($mrowtrans=pg_fetch_array($mrs_trans)){
													?>
													<option value="<?php echo $mrowtrans["id"];?>" <?php if($_POST["transfer_mode_id"]==$mrowtrans["id"]){?> selected="selected"<?php }?>><?php echo $mrowtrans["transfer_mode"];?></option>
												<?php }?>
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="panel panel-bordered">
								<div class="panel-heading" style="background:#1b8388f7;">
									<h3 class="panel-title"><b>Percentage of Property Transfered</b></h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-12">
											<input type="text" name="percentage_of_property" id="percentage_of_property" value="<?=$_POST["percentage_of_property"]?>" class="form-control" />
										</div>
									</div>
								</div>
							</div>
						<?php }?>

						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Area of Plot (in decimal)</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?=$area_of_plot?>
										<input type="hidden" name="hid_area_of_plot" id="hid_area_of_plot" value="<?=$area_of_plot?>" />
									</div>
									<?php if($user_type_mstr_id==7){?>
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?=$varea_of_plot?>
											<input type="hidden" name="hid_area_of_plotv" id="hid_area_of_plotv" value="<?=$varea_of_plot?>" />
										</div>
									<?php }?>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<b>Check </b>
									</div>
									<div class="col-sm-6">
										<?php
										$chkparea=NULL;
										$disparea=NULL;
										$chkparea2=NULL;
										if(isset($_POST["rdo_area_of_plot"]))
										{
											if($_POST["rdo_area_of_plot"]==1)
											{
												$chkparea='checked="checked"'; 
												$disparea='disabled="disabled"';
											}
											if($_POST["rdo_area_of_plot"]==0)
											{
												$chkparea2='checked="checked"';
											}
										}
										?>
										<input type="radio" name="rdo_area_of_plot" id="rdo_area_of_plot1" <?=$chkparea?> value="1" onClick="OperateTexBox('rdo_area_of_plot1', 'area_of_plot', 'hid_area_of_plot')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_area_of_plot" id="rdo_area_of_plot2"  <?=$chkparea2?> value="0" onClick="OperateTexBox('rdo_area_of_plot2', 'area_of_plot', 'hid_area_of_plot')" />&nbsp;&nbsp;Incorrect
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<b>Verification </b> <input type="text" name="area_of_plot" id="area_of_plot"  value="<?=$_POST["area_of_plot"] ?? NULL;?>"  <?=$disparea?> class="form-control">

									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Street Type</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?php echo $road_type; ?>
										<input type="hidden" name="hid_street_type" id="hid_street_type" value="<?=$road_type_mstr_id?>" />
									</div>
									<?php if($user_type_mstr_id==7){?>
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?php echo $vroad_type; ?>
											<input type="hidden" name="hid_street_typev" id="hid_street_typev" value="<?=$vroad_type?>" />
										</div>
									<?php }?>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<b>Check </b>
									</div>
									<div class="col-sm-6">
										<?php
										
										$chkstreet=NULL;
										$disstret=NULL;
										$chkstreet2=NULL;
										if(isset($_POST["rdo_street_type"]))
										{
											if($_POST["rdo_street_type"]==1)
											{
												$chkstreet='checked="checked"';
												$disstret='disabled="disabled"';
											}
											if($_POST["rdo_street_type"]==0)
											{
												$chkstreet2='checked="checked"';
											}
										}
										?>
										<input type="radio" name="rdo_street_type" id="rdo_street_type1"  value="1" <?=$chkstreet?> onClick="OperateDropDown('rdo_street_type1', 'street_type_id', 'hid_street_type')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_street_type" id="rdo_street_type2"  value="0" <?=$chkstreet2?>onClick="OperateDropDown('rdo_street_type2', 'street_type_id', 'hid_street_type')" />&nbsp;&nbsp;Incorrect
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<b>Verification </b>
										<select name="street_type_id" id="street_type_id"  <?=$disstret?> class="form-control">
											<option value="">Select</option>
											<?php
											foreach ($road_type_list as $valroad)
											{
												?>
												<option value="<?=$valroad["id"]?>" <?php if(isset($_POST["street_type_id"]) && $_POST["street_type_id"]==$valroad["id"]){?> selected="selected"<?php }?>><?=$valroad["road_type"]?></option>
												<?php 
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>

						<?php
						if($property_type<>'VACANT LAND'){
						?>

						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>No. of floors</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?php echo $no_of_floor;?>
										<input type="hidden" name="hid_total_floors" id="hid_total_floors" value="<?=$no_of_floor?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<b>Check </b>
									</div>
									<div class="col-sm-6">
										<?php
										$chkflr=NULL;
										$disflr=NULL;
										$chkflr2=NULL;
										if(isset($_POST["rdo_total_floors"]))
										{
											if($_POST["rdo_total_floors"]==1)
											{
												$chkflr='checked="checked"';
												$disflr='disabled="disabled"';
											}
											if($_POST["rdo_total_floors"]==0)
											{
												$chkflr2='checked="checked"';
											}
										}
										?>
										<input type="radio" name="rdo_total_floors" id="rdo_total_floors1"  value="1" <?=$chkflr?> onClick="OperateTexBox('rdo_total_floors1', 'total_floors', 'hid_total_floors'),addfloor(this.value)" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_total_floors" id="rdo_total_floors2"  value="0" <?=$chkflr2?> onClick="OperateTexBox('rdo_total_floors2', 'total_floors', 'hid_total_floors'),addfloor(this.value)" />&nbsp;&nbsp;Incorrect
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<b>Verification </b> <input type="text" name="total_floors" id="total_floors"  value="<?=$_POST["total_floors"] ?? NULL;?>"  <?=$disflr?> class="form-control">
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>

				<?php
				$i=1;
				//print_var($floor_details);exit;
				foreach ($floor_details as $value)
				{
					if(isset($value["vfloor_details"], $value["vfloor_details"]))
					foreach ($value["vfloor_details"] as  $valuefloor)
					{
						$vusage_type=$valuefloor["usage_type"];
						$voccupancy_name=$valuefloor["occupancy_name"];
						$vconstruction_type=$valuefloor["construction_type"];
						$vbuiltup_area=$valuefloor["builtup_area"];
					}
					?>

					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title"><b><?=$value["floor_name"]?></b></h3>
						</div>
						<div class="panel-body">
							<div class="panel panel-bordered">
								<div class="panel-heading" style="background:#1b8388f7;">
									<h3 class="panel-title"><b>Usage Type - <?=$value["floor_name"]?></b></h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6">
											<b>Self-Assessed </b>
										</div>
										<div class="col-sm-6">
											<?=$value['usage_type']?>
											<input type="hidden" id="hid_use_type_id_<?=$i?>" value="<?=$value['usage_type_mstr_id']?>" name="hid_use_type_id_<?=$i?>" />
										</div>
										<?php if($user_type_mstr_id==7){?>
											<div class="col-sm-6">
												<b>Assessed By Agency TC  </b>
											</div>
											<div class="col-sm-6">
												<?=$vusage_type?>
												<input type="hidden" id="hid_use_type_idv_<?=$i?>" value="<?=$vusage_type?>" name="hid_use_type_idv_<?=$i?>" />
											</div>
										<?php }?>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Check </b>
										</div>
										<div class="col-sm-6">
											<?php  $chkuse=""; $chkuse2=""; $disuse="";
											if(isset($_POST["rdo_usage_type$i"])){
												if(@$_POST["rdo_usage_type$i"]==1){ $chkuse='checked="checked"';  $disuse='disabled="disabled"';}
												else if(@$_POST["rdo_usage_type$i"]==0){ $chkuse2='checked="checked"'; $disuse="";
												}
											}?>
											<input type="radio" name="rdo_usage_type<?=$i?>" id="rdo_usage_type1"  value="1" <?=$chkuse?> onClick="OperateDropDown('rdo_usage_type1', 'usagetypeid_<?=$i?>', 'hid_use_type_id_<?=$i?>')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="rdo_usage_type<?=$i?>" id="rdo_usage_type2"  value="0"<?=$chkuse2?>  onClick="OperateDropDown('rdo_usage_type2', 'usagetypeid_<?=$i?>', 'hid_use_type_id_<?=$i?>')" />&nbsp;&nbsp;Incorrect
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<b>Verification </b>
											<select name="usagetypeid_<?=$i?>" id="usagetypeid_<?=$i?>" <?=$disuse?> required class="form-control">
												<option value="">Select</option>
												<?php
												foreach ($usage_list as  $valuse)
												{
													?>
													<option value="<?php echo $valuse["id"];?>" <?php if(isset($_POST["usagetypeid_$i"]) && $valuse["id"]==$_POST["usagetypeid_$i"]){?> selected="selected"<?php }?>><?php echo $valuse["usage_type"];?></option>
													<?php 
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="panel panel-bordered">
								<div class="panel-heading" style="background:#1b8388f7;">
									<h3 class="panel-title"><b>Occupancy Type - <?=$value["floor_name"]?></b></h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6">
											<b>Self-Assessed </b>
										</div>
										<div class="col-sm-6">
											<?=$value['occupancy_name']?>
											<input type="hidden" name="hid_occupancy_type_id_<?=$i?>" id="hid_occupancy_type_id_<?=$i?>" value="<?=$value["occupancy_type_mstr_id"]?>" />
										</div>
										<?php if($user_type_mstr_id==7){?>
											<div class="col-sm-6">
												<b>Assessed By Agency TC  </b>
											</div>
											<div class="col-sm-6">
												<?=$voccupancy_name?>
												<input type="hidden" name="hid_occupancy_type_idv_<?=$i?>" id="hid_occupancy_type_idv_<?=$i?>" value="<?=$voccupancy_name?>" />
											</div>
										<?php }?>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Check </b>
										</div>
										<div class="col-sm-6">
											<?php  $chkocc=""; $chkocc2="";  $disocc="";
											if(isset($_POST["rdo_occupancy_type$i"])){
												if(@$_POST["rdo_occupancy_type$i"]==1){ $chkocc='checked="checked"';  $disocc='disabled="disabled"';}if(@$_POST["rdo_occupancy_type$i"]==0){ $chkocc2='checked="checked"';$disocc="";
												}
											}?>
											<input type="radio" name="rdo_occupancy_type<?=$i?>" id="rdo_occupancy_type1"  value="1" <?=$chkocc?> onClick="OperateDropDown('rdo_occupancy_type1', 'occupancytypeid_<?=$i?>', 'hid_occupancy_type_id_<?=$i?>')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="rdo_occupancy_type<?=$i?>" id="rdo_occupancy_type2"  value="0"<?=$chkocc2?>  onClick="OperateDropDown('rdo_occupancy_type2', 'occupancytypeid_<?=$i?>', 'hid_occupancy_type_id_<?=$i?> ')" />&nbsp;&nbsp;Incorrect
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<b>Verification </b>
											<select name="occupancytypeid_<?=$i?>" id="occupancytypeid_<?=$i?>" <?=$disocc?> class="form-control" required >
												<option value="">Select</option>
												<?php
												foreach ($occupancy_list as $valoccu)
												{
													?>
													<option value="<?php echo $valoccu["id"];?>"<?php if(isset($_POST["occupancytypeid_".$i]) && $valoccu["id"]==$_POST["occupancytypeid_".$i]){?> selected="selected"<?php }?> ><?php echo $valoccu["occupancy_name"];?></option>
													<?php 
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-bordered">
								<div class="panel-heading" style="background:#1b8388f7;">
									<h3 class="panel-title"><b>Construction Type - <?=$value["floor_name"]?></b></h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6">
											<b>Self-Assessed </b>
										</div>
										<div class="col-sm-6">
											<?=$value['construction_type']?>
											<input type="hidden" id="hid_construction_type_id_<?=$i?>" value="<?=$value['const_type_mstr_id']?>" name="hid_construction_type_id_<?=$i?>" />
										</div>
										<?php if($user_type_mstr_id==7){?>
											<div class="col-sm-6">
												<b>Assessed By Agency TC  </b>
											</div>
											<div class="col-sm-6">
												<?=$vconstruction_type?>
												<input type="hidden" name="hid_construction_type_idv_<?=$i?>" id="hid_construction_type_idv_<?=$i?>" value=" <?=$vconstruction_type?>" />
											</div>
										<?php }?>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Check </b>
										</div>
										<div class="col-sm-6">
											<?php  $chkcons=""; $chkcons2=""; $discons="";
											if(isset($_POST["rdo_construction_type$i"])){
												if(@$_POST["rdo_construction_type$i"]==1){ $chkcons='checked="checked"';  $discons='disabled="disabled"';}if(@$_POST["rdo_construction_type$i"]==0){ $chkcons2='checked="checked"';$discons="";
												}
											}?>
											<input type="radio" name="rdo_construction_type<?=$i?>" id="rdo_construction_type1"  value="1" <?=$chkcons?> onClick="OperateDropDown('rdo_construction_type1', 'consttypeid<?=$i?>', 'hid_construction_type_id_<?=$i?>')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="rdo_construction_type<?=$i?>" id="rdo_construction_type2"  value="0" <?=$chkcons2?> onClick="OperateDropDown('rdo_construction_type2', 'consttypeid<?=$i?>', 'hid_construction_type_id_<?=$i?>')" />&nbsp;&nbsp;Incorrect
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<b>Verification </b>
											<select name="consttypeid<?=$i?>" id="consttypeid<?=$i?>" <?=$discons?> required class="form-control">
												<option value="">Select</option>
												<?php
												foreach ($const_type_list as  $valcons)
												{
													?>
													<option value="<?php echo $valcons["id"];?>" <?php if(isset($_POST["consttypeid$i"]) && $valcons["id"]==$_POST["consttypeid$i"]){?> selected="selected"<?php }?>><?php echo $valcons["construction_type"];?></option>
													<?php 
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-bordered">
								<div class="panel-heading" style="background:#1b8388f7;">
									<h3 class="panel-title"><b>Builtup Area - <?=$value["floor_name"]?></b></h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6">
											<b>Self-Assessed </b>
										</div>
										<div class="col-sm-6">
											<?=$value["builtup_area"]?>
											<input type="hidden" id="hid_builtup_area_<?=$i?>" value="<?=$value["builtup_area"]?>" name="hid_builtup_area_<?=$i?>" />
										</div>
										<?php if($user_type_mstr_id==7){?>
											<div class="col-sm-6">
												<b>Assessed By Agency TC  </b>
											</div>
											<div class="col-sm-6">
												<?=$vbuiltup_area?>
												<input type="hidden" name="hid_builtup_areav_<?=$i?>" id="hid_builtup_areav_<?=$i?>" value="<?=$vbuiltup_area?>" />
											</div>
										<?php }?>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Check </b>
										</div>
										<div class="col-sm-6">
											<?php   $disbarea=""; $chkbarea2=""; $chkbarea="";
											if(isset($_POST["rdo_builtup_area$i"])){
												if(@$_POST["rdo_builtup_area$i"]==1){ $chkbarea='checked="checked"';  $disbarea="readonly";}if(@$_POST["rdo_builtup_area$i"]==0){ $chkbarea2='checked="checked"'; $disbarea="";
												}
											}?>
											<input type="radio" name="rdo_builtup_area<?=$i?>" id="rdo_builtup_area1"  value="1" <?=$chkbarea?> onClick="OperateTexBox('rdo_builtup_area1', 'builtuparea_<?=$i?>', 'hid_builtup_area_<?=$i?>')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="rdo_builtup_area<?=$i?>" id="rdo_builtup_area2"  value="0" <?=$chkbarea2?> onClick="OperateTexBox('rdo_builtup_area2', 'builtuparea_<?=$i?>', 'hid_builtup_area_<?=$i?>')" />&nbsp;&nbsp;Incorrect
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<b>Verification </b> <input type="text" id="builtuparea_<?=$i?>" name="builtuparea_<?=$i?>"    value="<?=$_POST["builtuparea_$i"] ?? 0;?>" required class="form-control"/>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				
					<?php 
					$i++; 
				}
				?>

				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"><b>Extra Details</b></h3>
					</div>
					<div class="panel-body">
						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Hoarding Board(s)</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Does Property Have Hoarding Board </b>
									</div>
									<div class="col-sm-6">
										<?=$is_hoarding_board=='t'?'Yes':'No';?>
										<input type="hidden" id="hid_builtup_area_<?=$i?>" value="<?=$value["builtup_area"]?>" name="hid_builtup_area_<?=$i?>" class="form-control"/>
									</div>
									<?php if($user_type_mstr_id==7){?>
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?=$vis_hoarding_board=='t'?'Yes':'No';?>
											<input type="hidden" name="hordv" id="hordv" value="<?=$vis_hoarding_board?>" class="form-control"/>
										</div>
									<?php }?>
								</div>
								<?php if($is_hoarding_board=='t'){?>
									<div class="row">
										<div class="col-sm-6">
											<b>Installation Date of Hoarding Board(s) </b>
										</div>
										<div class="col-sm-6">
											<input type="hidden" id="assess_hording_installation_date" value="<?=$hoarding_installation_date?>" class="form-control"> <?=$hoarding_installation_date?>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Total  Area  (in Sq. Ft.) </b>
										</div>
										<div class="col-sm-12">
											<input type="hidden" id="assess_total_hording_area" value="<?=$hoarding_area?>" class="form-control"> <?=$hoarding_area?>
										</div>
									</div>
								<?php }else{?>
									<input type="hidden" id="assess_hording_installation_date" />
									<input type="hidden" id="assess_total_hording_area"  />
								<?php }?>
								<div class="row">
									<div class="col-sm-6 pad-btm">
										<b>Does Property Have Hoarding Board(s)  </b>
									</div>
									<div class="col-sm-6 pad-btm">
										<?php 
										$hordval1=NULL;
										if(isset($_POST["has_hording"]))
										{
											$hordval1=$_POST["has_hording"];
										}
										else
										{
											//$hordval1=$hrd; // var does not exist
										}
										?>
										<select name="has_hording" id="has_hording" onChange="HideUnhide('has_hording','hrd_details','hording_installation_date','total_hording_area')" class="form-control">
											<option value="">Select</option>
											<option value="1"<?php if($hordval1==1){?> selected="selected"<?php }?> >Yes</option>
											<option value="0" <?php if($hordval1==0){?> selected="selected"<?php }?>>No</option>
										</select>
									</div>
									<div class="col-sm-12 pad-btm">
										<div class="hrd_details" style="display:none">
											Installation Date of Hoarding Board(s) :
											<input type="date" name="hording_installation_date" id="hording_installation_date" class="form-control" value="<?php if(isset($_POST["hording_installation_date"])){echo $_POST["hording_installation_date"];}else{ echo $hoarding_installation_date;}?>" style="width:140px;height:30px;" />
											<input type="hidden" id="assess_hording_installation_date" value="<?=$hoarding_installation_date?>" class="form-control"> <?=$hoarding_installation_date?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 pad-btm">
										<div class="hrd_details" style="display:none">
											Total Floor Area of Roof / Land (in Sq. Ft.)  :
											<input type="text" name="total_hording_area" id="total_hording_area"  value="<?php if(isset($_POST["total_hording_area"])){echo $_POST["total_hording_area"];}else{ echo $hoarding_area;}?>" class="form-control"/>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Mobile Tower</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Does Property Have Mobile Tower </b>
									</div>
									<div class="col-sm-6">
										<?=$is_mobile_tower=='t'?'Yes':'No';?>
									</div>
								</div>
								<?php if($is_mobile_tower=='t'){?>
									<div class="row">
										<div class="col-sm-6">
											<b>Installation Date of Mobile Tower </b>
										</div>
										<div class="col-sm-6">
											<input type="hidden" id="assess_tower_installation_date" value="<?=$tower_installation_date?>" class="form-control"/><?=$tower_installation_date?>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Total  Area  (in Sq. Ft.) </b>
										</div>
										<div class="col-sm-6">
											<input type="hidden" id="assess_total_tower_area" value="<?=$tower_area?>" class="form-control"/> <?=$tower_area?>
										</div>
									</div>
								<?php }else{?>
									<input type="hidden" id="assess_tower_installation_date" />
									<input type="hidden" id="assess_total_tower_area"  />
								<?php }?>
								<?php if($user_type_mstr_id==7){?>
									<div class="row">
										<div class="col-sm-6 pad-btm">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6 pad-btm">
											<?=$vis_mobile_tower=='t'?'Yes':'No';?>
											<input type="hidden" name="towerv" id="towerv" class="form-control" value="<?=$vis_mobile_tower?>" />
										</div>
									</div>
								<?php }?>
								<div class="row">
									<div class="col-sm-12 pad-btm">
										<b>Does Property Have Mobile Tower(s)  </b>
										<?php if(isset($_POST["has_mobile_tower"])){$towlval=$_POST["has_mobile_tower"];}else{$towlval=NULL;}?>
										<select name="has_mobile_tower" id="has_mobile_tower" onChange="HideUnhide('has_mobile_tower','tw_details','tower_installation_date','total_tower_area')" class="form-control">
											<option value="">Select</option>
											<option value="1"<?php if($towlval==1){?> selected="selected"<?php }?> >Yes</option>
											<option value="0" <?php if($towlval==0){?> selected="selected"<?php }?>>No</option>
										</select>
									</div>
									<div class="col-sm-12 pad-btm">
										<div class="tw_details" style="display:none"><b>Installation Date of Mobile Tower(s) </b>
											<input type="date" name="tower_installation_date" id="tower_installation_date" value="<?php if(isset($_POST["tower_installation_date"])){echo $_POST["tower_installation_date"];}else{ echo $tower_installation_date;}?>" class="form-control"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 pad-btm">
										<div class="tw_details" style="display:none">
											<b>Total Floor Area of Roof / Land (in Sq. Ft.)  </b>
											<input type="text" name="total_tower_area" id="total_tower_area"  value="<?php if(isset($_POST["total_tower_area"])){echo $_POST["total_tower_area"];}else{ echo $tower_area;}?>" class="form-control"/>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Petrol Pump</b></h3>
							</div>
							<?php if($property_type=='INDEPENDENT BUILDING'){?>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Is property a Petrol Pump ? </b>
									</div>
									<div class="col-sm-6">
										<?=$is_petrol_pump=='t'?'Yes':'No';?>
									</div>
								</div>
								<?php if($is_petrol_pump=='t'){?>
									<div class="row">
										<div class="col-sm-6">
											<b>Completion Date of Petrol Pump </b>
										</div>
										<div class="col-sm-6">
											<input type="hidden" id="assess_petrol_pump_completion_date" value="<?=$petrol_pump_completion_date?>" class="form-control"/><?=$petrol_pump_completion_date?>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Underground Storage Area (in Sq. Ft.) </b>
										</div>
										<div class="col-sm-6">
											<input type="hidden" id="assess_under_ground_area" value="<?=$under_ground_area?>" class="form-control"/> <?=$under_ground_area?>
										</div>
									</div>
								<?php }else{?>
									<input type="hidden" id="assess_petrol_pump_completion_date" />
									<input type="hidden" id="assess_under_ground_area"  />
								<?php }?>
								<?php }?>
								<?php if($user_type_mstr_id==7){?>
									<div class="row">
										<div class="col-sm-6 pad-btm">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6 pad-btm">
											<?=$vis_petrol_pump=='t'?'Yes':'No';?>
											<input type="hidden" name="petrolrv" id="petrolrv" value="<?=$vis_petrol_pump?>" class="form-control"/>
										</div>
									</div>
								<?php }?>
								<div class="row">
									<div class="col-sm-6 pad-btm">
										<b>Is property a Petrol Pump ?  </b>
									</div>
									<div class="col-sm-6 pad-btm">
										<?php if(isset($_POST["is_petrol_pump"])){$petrolval=$_POST["is_petrol_pump"];}else{$petrolval=$is_petrol_pump;}?>
										<select name="is_petrol_pump" id="is_petrol_pump" onChange="HideUnhide('is_petrol_pump','pt_details','petrol_pump_completion_date','under_ground_area')" class="form-control">
											<option value="">Select</option>
											<option value="1" <?php if($petrolval==1){?> selected="selected"<?php }?> >Yes</option>
											<option value="0" <?php if($petrolval==0){?> selected="selected"<?php }?>>No</option>
										</select>
									</div>
									<div class="col-sm-12 pad-btm">
										<div class="pt_details" style="display:none"><b>Completion Date of Petrol Pump </b>
											<input type="date" name="petrol_pump_completion_date" id="petrol_pump_completion_date" value="<?php if(isset($_POST["petrol_pump_completion_date"])){echo $_POST["petrol_pump_completion_date"];}else{ echo $petrol_pump_completion_date;}?>"  class="form-control"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 pad-btm">
										<div class="pt_details" style="display:none">
											<b>Underground Storage Area (in Sq. Ft.)  </b>
											<input type="text" name="under_ground_area" id="under_ground_area"  value="<?php if(isset($_POST["under_ground_area"])){echo $_POST["under_ground_area"];}else{ echo $under_ground_area;}?>" class="form-control"/>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Rainwater Harvesting Provision</b></h3>
							</div>

							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?=$is_water_harvesting=='t'?'Yes':'No';?>
									</div>
									<input type="hidden" name="hid_water_harvesting" id="hid_water_harvesting" value="<?php if($is_water_harvesting=='t'){echo '1';}else{echo '0';}?>" class="form-control"/>
								</div>

								<?php if($user_type_mstr_id==7){?>
									<div class="row">
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?=$vis_water_harvesting=='t'?'Yes':'No';?>
											<input type="hidden" name="rainwater_harvestv" id="rainwater_harvestv" value="<?=$vis_water_harvesting?>" class="form-control"/>
										</div>
									</div>
								<?php }?>
								<div class="row">
									<div class="col-sm-6">
										<b>Check  </b>
									</div>
									<div class="col-sm-6">
										<?php
										$chkharbst=NULL;
										$dishrbst=NULL;
										$chkharbst2=NULL;
										if(isset($_POST["rdo_water_harvesting"]))
										{
											if($_POST["rdo_water_harvesting"]==1)
											{
												$chkharbst='checked="checked"';
												$dishrbst='disabled="disabled"';
											}
											if($_POST["rdo_water_harvesting"]==0)
											{
												$chkharbst2='checked="checked"';
											}
										}
										?>
										<input type="radio" name="rdo_water_harvesting" id="rdo_water_harvesting1"  value="1" <?=$chkharbst?> onClick="OperateDropDown('rdo_water_harvesting1', 'water_harvesting', 'hid_water_harvesting')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_water_harvesting" id="rdo_water_harvesting2"  value="0" <?=$chkharbst2?> onClick="OperateDropDown('rdo_water_harvesting2', 'water_harvesting', 'hid_water_harvesting')" />&nbsp;&nbsp;Incorrect
									</div>
									<div class="col-sm-12">
										<div class="pt_details" style="display:none"><b>Verification </b>
											<select name="water_harvesting" id="water_harvesting"   <?=$dishrbst?> class="form-control">
												<option value="">Select</option>
												<option value="1" <?php if(isset($_POST["water_harvesting"]) && $_POST["water_harvesting"]==1){?> selected="selected"<?php }?> >Yes</option>
												<option value="0" <?php if(isset($_POST["water_harvesting"]) && $_POST["water_harvesting"]==0){?> selected="selected"<?php }?>>No</option>
											</select>
										</div>
									</div>
								</div>

							</div>
						</div>

					</div>
				</div>


				<!--Floor Added by Agency Tc-->
				<?php 
				if(isset($no_of_addedfloor) && $no_of_addedfloor>0)
				{
					?>
					<div class="panel panel-bordered">
						<div class="panel-heading" style="background:#1b8388f7;">
							<h3 class="panel-title" style="background:#FF0000; color:#fff;"><b>Floor Added By Agency TC</b></h3>
						</div>
						<?php
						foreach ($vfloor_details_added as $rowusesver)
						{
							?>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Floor No </b>
									</div>
									<div class="col-sm-6">
										<?=$rowusesver['floor_name']?>
									</div>
									<div class="col-sm-6">
										<b>Use Type </b>
									</div>
									<div class="col-sm-6">
										<?=$rowusesver['usage_type']?>
									</div>
									<div class="col-sm-6">
										<b>Occupancy Type </b>
									</div>
									<div class="col-sm-6">
										<?php echo $rowusesver['occupancy_name'];?>
									</div>
									<div class="col-sm-6">
										<b>Construction Type </b>
									</div>
									<div class="col-sm-6">
										<?=$rowusesver['construction_type']?>
									</div>
									<div class="col-sm-6">
										<b>Carpet Area </b>
									</div>
									<div class="col-sm-6">
										<?=$rowusesver['carpet_area']?>
									</div>
									<div class="col-sm-6">
										<b>Build Up Area </b>
									</div>
									<div class="col-sm-6">
										<?=$rowusesver['builtup_area']?>
									</div>
									<div class="col-sm-6">
										<b>Date From </b>
									</div>
									<div class="col-sm-6">
										<?=date('m-Y',strtotime($rowusesver['date_from']))?>
									</div>
								</div>
							</div>
							<?php 
						}
						?>
					</div>
					<?php 
				}
				?>



				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"><b>Do You Want To Add Floor</b> &nbsp;&nbsp;
							<input type="checkbox"  name="chkfloor" id="chkfloor" <?php if(isset($_POST["chkfloor"]) && $_POST["chkfloor"]=='on'){echo 'checked="checked"';}?> onClick="checkboxchk()">
						</h3>
					</div>
					<div id="showflrr" style="display:none;">
						<div class="panel-body">
							<div class="panel panel-bordered">
								<div class="panel-heading" style="background:#1b8388f7; margin-bottom: 10px;">
									<h3 class="panel-title"><b>Floor  Details</b></h3>
								</div>
								<?php
								if(isset($_POST["use_type_id"]))
								{
									for($m=0;$m<sizeof($_POST["use_type_id"]);$m++)
									{
										?>
										<div class="panel-body" id="occ_tr_<?=$m?>">
											<div class="row" id="tr_floor_dtl">
												<div class="col-sm-12">
													Floor No
												</div>
												<div class="col-sm-12">
													<?php echo $_POST["floor_id$m"];?>
													<select name="floor_id[]" id="floor_id<?=$m?>" class="form-control">
														<option value="">Select</option>
														<?php
														foreach ($floor_list as  $valfloor)
														{
															?>
															<option value="<?php echo $valfloor["id"];?>" <?php if($_POST["floor_id"][$m]==$valfloor["id"]){?> selected="selected" <?php } ?> ><?php echo $valfloor["floor_name"];?></option>
														<?php }?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													Use Type
												</div>
												<div class="col-sm-12">
													<select  name="use_type_id[]" id="use_type_id<?=$m?>" class="form-control">
														<option value="">Select</option>
														<?php
														foreach ($usage_list as $valuse) {
															?>
															<option value="<?php echo $valuse["id"];?>" <?php if($_POST["use_type_id"][$m]==$valuse["id"]){echo 'selected="selected"'; } ?> ><?php echo $valuse["usage_type"];?></option>    <?php }?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													Occupancy Type
												</div>
												<div class="col-sm-12">
													<select name="occupancy_type_id[]"  id="occupancy_type_id<?=$m?>" class="form-control">
														<option value="">Select</option>
														<?php
														foreach ($occupancy_list as $valoccu) {
															?>
															<option value="<?php echo $valoccu["id"];?>" <?php if($_POST["occupancy_type_id"][$m]==$valoccu["id"]){?> selected="selected" <?php } ?>><?php echo $valoccu["occupancy_name"];?></option>
														<?php }?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													Construction Type
												</div>
												<div class="col-sm-12">
													<select name="construction_type_id[]" id="construction_type_id<?=$m?>" class="form-control">
														<option value="">Select</option>
														<?php
														foreach ($const_type_list as $valcons) {
															?>
															<option value="<?php echo $valcons["id"];?>" <?php if($_POST["construction_type_id"][$m]==$valcons["id"]){?> selected="selected" <?php } ?>><?php echo $valcons["construction_type"];?></option>
														<?php }?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													Built Up Area  (in Sq. Ft)
												</div>
												<div class="col-sm-12">
													<input type="tel" name="builtup_area[]"  value="<?=$_POST["builtup_area"][$m]?>" id="builtup_area<?=$m?>"  class="form-control"/>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													Date From
												</div>
												<div class="col-sm-12">
													<input type="month" name="occ_mm[]" id="occ_mm<?=$m?>" value="<?=$_POST["occ_mm"][$m]?>" maxlength="2" onChange="checkdate('M',<?=$m?>)" class="form-control">
													<input type="text" name="occ_yyyy[]" id="occ_yyyy<?=$m?>"  value="<?=$_POST["occ_yyyy"][$m]?>"  maxlength="4" minlength="4" onChange="checkdate('Y',<?=$m?>)" class="form-control"/>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<span class="btn btn-info"><a href="javascript:AddOccupancy()" style="text-decoration:none; color:#FFFFFF">ADD</a></span>
													<span class="btn btn-danger"><a href="javascript:RemoveOccupancy('occ_tr_<?=$m?>')" style="text-decoration:none; color:#FFFFFF">DELETE</a></span>
												</div>
											</div>
										</div>

										<?php 
									}
								}
								else
								{
									?>
									<div class="panel panel-bordered panel-dark container-fluid">
									<div class="panel-body" id="tbl_occupancy">
									<div class="row" id="tr_floor_dtl">
										<div class="col-sm-12">
											Floor No 
										</div>
										<div class="col-sm-12">
											<select name="floor_id[]" id="floor_id1" class="form-control">
												<option value="">Select</option>
												<?php
												foreach ($floor_list as  $valfloor) {
													?>
													<option value="<?php echo $valfloor["id"];?>" ><?php echo $valfloor["floor_name"];?></option>
												<?php }?>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											Use Type
										</div>
										<div class="col-sm-12">
											<select  name="use_type_id[]" id="use_type_id1" class="form-control">
												<option value="">Select</option>
												<?php
												foreach ($usage_list as $valuse)
												{
													?>
													<option value="<?php echo $valuse["id"];?>" <?php if(1==$valuse["id"]){?> selected="selected"<?php }?>><?php echo $valuse["usage_type"];?></option>
													<?php 
												}
												?>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											Occupancy Type
										</div>
										<div class="col-sm-12">
											<select name="occupancy_type_id[]"  id="occupancy_type_id1" class="form-control">
												<option value="">Select</option>
												<?php
												foreach ($occupancy_list as $valoccu)
												{
													?>
													<option value="<?php echo $valoccu["id"];?>" <?php if(1==$valoccu["occupancy_name"]){?> selected="selected"<?php }?>><?php echo $valoccu["occupancy_name"];?></option>
													<?php 
												}
												?>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											Construction Type
										</div>
										<div class="col-sm-12">
											<select name="construction_type_id[]" id="construction_type_id1" class="form-control">
												<option value="">Select</option>
												<?php
												foreach ($const_type_list as  $valcons)
												{
													?>
													<option value="<?php echo $valcons["id"];?>" ><?php echo $valcons["construction_type"];?></option>
													<?php 
												}
												?>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											Built Up Area  (in Sq. Ft)
										</div>
										<div class="col-sm-12">
											<input type="tel" name="builtup_area[]"  value="" id="builtup_area1" class="form-control">
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											Date From
										</div>
										<div class="col-sm-12">
											<input type="month" name="occ_mm[]" id="occ_mm1" placeholder="Month" value="" onChange="checkdate('M',1)" class="form-control">

											<input type="text" name="occ_yyyy[]" id="occ_yyyy1"  maxlength="4" minlength="4" placeholder="Year" value="<?php echo date('Y'); ?>" onChange="checkdate('Y',1)" class="form-control"/>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<span class="btn btn-info"><a href="javascript:AddOccupancy()" style="text-decoration:none; color:#FFFFFF">ADD</a></span>
											<span class="btn btn-danger"><a href="javascript:RemoveOccupancy('occ_tr_[]')" style="text-decoration:none; color:#FFFFFF">DELETE</a></span>
										</div>
									</div>

									</div>
									</div>
									<?php 
								}
								?>
								<div id="tr_floor_dtl_head"></div>
							</div>
						</div>
					</div>
				</div>
				<!--   Add New Floor -->
			
				<input type="hidden"  name="countarea" id="countarea" value="<?php if(isset($_POST["countarea"])){echo $_POST["countarea"];}else{echo "1";}?>">

				<div class="panel">
					<div class="panel-body text-center">
						<input type="submit" name="btn_submit" value="Proceed to survey" style="width: 140px" onClick="return ValidateRadio()" class="btn btn-success" />
					</div>
				</div>
			</form>
		</div>
	</div>


<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script>
    function checkboxchk()
    {
        var check=document.getElementById("chkfloor");
        if(check.checked==true)
        {
            document.getElementById("showflrr").style.display="block";
        }
        else
        {
            document.getElementById("showflrr").style.display="none";
        }
    }
</script>
<script >
    function checkdate(d,cnt)
    {
        try{
            var msg="";
            var curryr=<?=date('Y')?>;
            var currmn=<?=date('m')?>;
            var mmyy=<?=date('Ym')?>;
            var mm=document.getElementById("occ_mm"+cnt).value;
            var yy=document.getElementById("occ_yyyy"+cnt).value;
            //var scmmdd="";
            var scmmdd = yy + "" + mm;


            if(d=='M')
            {
                //alert(isNaN(mm));
                if(isNaN(mm) == false){alert("Please Enter only Digit"); document.getElementById("occ_mm"+cnt).value="";   document.getElementById("occ_mm"+cnt).focus();}
                else if(mm<1 || mm>12){alert("Please Enter valid month"); document.getElementById("occ_mm"+cnt).value=""; document.getElementById("occ_mm"+cnt).focus();}
                else if(yy!="")
                {
                    if(yy>curryr){alert("Complition Year Must Be less or equal to current Year"); document.getElementById("occ_yyyy"+cnt).value="";}
                    else if(scmmdd>mmyy){alert("Date of Completion Must Be less or equal to current date"); document.getElementById("occ_yyyy"+cnt).value="";document.getElementById("occ_mm"+cnt).value="";}
                }
            }

            if(d=='Y')
            {
                if(isNaN(yy)){alert("Please Enter Valid Year"); document.getElementById("occ_yyyy"+cnt).value="";  document.getElementById("occ_yyyy"+cnt).focus();}
                else if(yy<1960){alert("Year Must be grater than 1960"); document.getElementById("occ_yyyy"+cnt).value=""; document.getElementById("occ_yyyy"+cnt).focus();}
                else if(yy>curryr){alert("Year Must be less or equal to current Year"); document.getElementById("occ_yyyy"+cnt).value="";  document.getElementById("occ_yyyy"+cnt).focus();}
                else if(mm!="")
                {
                    if(scmmdd>mmyy){alert("Date of Completion Must Be less or equal to current date"); document.getElementById("occ_yyyy"+cnt).value="";document.getElementById("occ_yyyy"+cnt).value="";}
                }

            }



        }    catch (err) { alert(err.message);}


    }


</script>

<script>
    HideUnhide('has_hording','hrd_details','hording_installation_date','total_hording_area')
    HideUnhide('has_mobile_tower','tw_details','tower_installation_date','total_tower_area')
    HideUnhide('is_petrol_pump','pt_details','petrol_pump_completion_date','under_ground_area')
    checkboxchk();

    $(document).ready(function () {
        //addValidator();
        //if(ValidateRadio()){
        //$.validator.messages.required = "";
        $("#FORMNAME1").validate({
            rules: {
                ward_id: {   required:true, }, property_type_id: {   required:true, }, percentage_of_property: {   required:true,   number:true, }, area_of_plot: {   required:true,   number:true, }, street_type_id: {   required:true, }, total_floors: {   required:true,   digits:true, }, usetype_10302: {   required:true, }, occu_10302: {   required:true, }, cons_10302: {   required:true, }, txt_builtarea_10302: {   required:true,   number:true, },has_mobile_tower: {   required:true, },tower_installation_date: {   required:true,   date_hyphen:true, },total_tower_area: {   required:true,   number:true, },is_petrol_pump: {   required:true, },petrol_pump_completion_date: {   required:true,   date_hyphen:true, },under_ground_area: {   required:true,   number:true, },water_harvesting: {   required:true, },floor_id1: {   required:true, },use_type_id1: {   required:true, },occupancy_type_id1: {   required:true, },construction_type_id1: {   required:true, },builtup_area1: {   required:true, },occ_mm1: {   required:true, },occ_yyyy1: {   required:true, },
            },
            messages: {
            },
        });
        //}
    });

    function ValidateRadio()
    {

        var rdo_ward_no=document.getElementsByName('rdo_ward_no');
        if(rdo_ward_no[0].checked==false && rdo_ward_no[1].checked==false)
        {
            alert("Please answer all the questions");
            return false;
        }

        var rdo_property_type=document.getElementsByName('rdo_property_type');
        if(rdo_property_type[0].checked==false && rdo_property_type[1].checked==false)
        {
            alert("Please answer all the questions");
            return false;
        }

        var rdo_area_of_plot=document.getElementsByName('rdo_area_of_plot');
        if(rdo_area_of_plot[0].checked==false && rdo_area_of_plot[1].checked==false)
        {
            alert("Please answer all the questions");
            return false;
        }

        var rdo_street_type=document.getElementsByName('rdo_street_type');
        if(rdo_street_type[0].checked==false && rdo_street_type[1].checked==false)
        {
            alert("Please answer all the questions");
            return false;
        }

        var rdo_total_floors=document.getElementsByName('rdo_total_floors');
        if(rdo_total_floors[0].checked==false && rdo_total_floors[1].checked==false)
        {
            alert("Please answer all the questions");
            return false;
        }


        var rdo_water_harvesting=document.getElementsByName('rdo_water_harvesting');
        if(rdo_water_harvesting[0].checked==false && rdo_water_harvesting[1].checked==false)
        {
            alert("Please answer all the questions");
            return false;
        }

        var per=document.getElementById("percentage_of_property");
        if(per.value.length!=0)
        {
            if(!isNaN(per.value))
            {
                var val=parseFloat(per.value);
                if(val<0 || val>100)
                {
                    alert("Invalid percentage of property transfered");
                    return false;
                }
            }
        }


    }


</script>
<script type="text/javascript">
    function checkselected()
    {
        var dddd=0;
        var totalChercks=0;
        $('input:radio').each(function() {
            if($(this).is(':checked')) { totalChercks+=1; } else {totalChercks=totalChercks; }  dddd+=1;});
        var check=dddd/2;
        if(check!=totalChercks){  alert("Please answer all the questions");  return false;}

    }
</script>


<script type="text/javascript">
    /*$('#rdo_total_floors1').onClick({

    });*/
    /*$('#tower_installation_date').datepicker({
           format: "yyyy-mm-dd",
           weekStart: 0,
           autoclose:true,
           todayHighlight:true,
           todayBtn: "linked",
           clearBtn:true,
           daysOfWeekHighlighted:[0]
       });
     $('#hording_installation_date').datepicker({
           format: "yyyy-mm-dd",
           weekStart: 0,
           autoclose:true,
           todayHighlight:true,
           todayBtn: "linked",
           clearBtn:true,
           daysOfWeekHighlighted:[0]
       });
      $('#petrol_pump_completion_date').datepicker({
           format: "yyyy-mm-dd",
           weekStart: 0,
           autoclose:true,
           todayHighlight:true,
           todayBtn: "linked",
           clearBtn:true,
           daysOfWeekHighlighted:[0]
       });*/


</script>