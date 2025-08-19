<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">       
        <div class="panel-heading btn-primary"><strong>Self Assessment - Field Survey</strong></div>

        <div class="row-fluid">
            
			<script>                    
            function chgAction(action_name)
            { 
            
            var ur=document.URL;
                if(action_name!="Save") 
                { 
                     document.FORMNAME1.action = 'view_verification.php<?=$url?>';
                  document.FORMNAME1.submit();
                }
                // else{
                //     $('save_agency_survey').html('saving....')
                // }
                
            }
			</script>
         
         
            <form action="" id="FORMNAME1" name="FORMNAME1" method="post" enctype="multipart/form-data">
            
               <input type="hidden" name="entry_date" id="entry_date" value="<?=$prop_val['apply_date']?>">
                <div class="panel-body">
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							<div class="col-md-6">
								<span style="font-weight: bold; color: #bb4b0a;"> Your Application No. is</span> &nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=$prop_val['application_no']?></span>
							</div>
							<div class="col-md-6">
								<span style="font-weight: bold; color: #bb4b0a;">Application Type : </span>&nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?php if($_GET["tp"]=='m'){echo "Mutation";}elseif($_GET["tp"]=='r'){echo "Re Assessment";}else{echo "New Assessment";}?></span>
							</div>
							<div class="col-md-6">
								<span style="font-weight: bold; color: #bb4b0a;">Applied Date : </span>&nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?php //$Coreproperty_webApp->IntToDate($prop_val['apply_date'])?></span>
							</div>
						</div>
					</div>
				</div>
               <!-- <div class="span12" style="margin-left: 0px;">
                     <div class="panel">
                    <div class="span2">
                        <b>Application No.:</b> <?=$prop_val['application_no']?>
                    </div>
                  
                    <div class="span2">
                        <b>Application Type: </b> <?php if($_GET["tp"]=='m'){echo "Mutation";}elseif($_GET["tp"]=='r'){echo "Re Assessment";}else{echo "New Assessment";}?>
                    </div>
                    <div class="span2" style="margin-left: 0px;">
                        <b>Applied Date :</b>  <?php //$Coreproperty_webApp->IntToDate($prop_val['apply_date'])?>
                    </div>
                  </div>
                </div>-->
                
                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Plot Details </b></div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2">
                                <b>Plot No.  : <?=$_POST["plot_no"]?>
                                <input type="hidden" name="plot_no" id="plot_no"  value="<?=$_POST["plot_no"]?>" style="width:120px;" readonly=""/>
                                </b>                            </div>
                      </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2" style="margin-left: 0px;">
                                <b>Khata No.  :</b><?=$_POST["khata_no"]?>
                                <input type="hidden" name="khata_no" id="khata_no"  value="<?=$_POST["khata_no"]?>" style="width:120px;" readonly=""/>
                            </div>
                           
                            <div class="span2">
                                <b>Mauja/Village :</b><?=$_POST["village"]?>
                                <input type="hidden" name="village" id="village"  value="<?=$_POST["village"]?>"  style="width:120px;" readonly=""/>
                            </div>
                           
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                
                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Ward No.</b></div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2">
                                <b>Self-Assessed :</b>  <?php
                             /* $msql="select * from ulb_ward_master where id='".$prop_val['ward_id']."' order by id";
                              $mrs=pg_query($_SESSION["db_system"],$msql)or die("Invalid Table");
                             $mrow=pg_fetch_array($mrs);
                            echo $mrow["ward"];*/
                            ?>
                            </div>
                           </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2" style="margin-left: 0px;">
                                <b>Check :</b><?php if($_POST["rdo_ward_no"]==1){echo "Correct";$ward=$prop_val['ward_id'];}else{echo "Incorrect";$ward=$_POST["ward_id"];}?>
                            <input type="hidden" name="rdo_ward_no"  value="<?=$_POST["rdo_ward_no"]?>">
                            </div>
                           
                            <div class="span2">
                                <b>Verification :</b><?php
                                   /* $msql1="select * from ulb_ward_master where id=$ward order  by id asc";
                              $mrs1=pg_query($_SESSION["db_system"],$msql1);
                              while($mrow1=pg_fetch_array($mrs1)){
                                echo $mrow1["ward"]; }*/ ?>

                                <input type="hidden" name="ward_id" id="ward_id" value="<?=$ward?>" />
                            </div>
                           
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Property Type</b></div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2">
                                <b>Self-Assessed :</b> <?php    /*$query_ptype="select * from prop_property_type_master where id='".$property_type_id."'";
                        $exe_ptype=pg_query($_SESSION["db_property"],$query_ptype);
                        $val_ptype=pg_fetch_array($exe_ptype);
                       echo $val_ptype["property_type"];*/?>
                             
                            </div>
                           </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2" style="margin-left: 0px;">
                                <b>Check :</b><?php /*if($_POST["rdo_property_type"]==1){echo "Correct";$proptype=$val_ptype["property_type"];}else{echo "Incorrect";$proptype=$_POST["property_type_id"];}*/
                                ?>
                            <input type="hidden" name="rdo_property_type"  value="<?=$_POST["rdo_property_type"]?>">  
                            
                             <input type="hidden" name="property_type_id" id="property_type_id" value="<?=$proptype?>" />
                            </div>
                          
                            <div class="span2">
                                <b>Verification :</b>   <?=$proptype?>
                            </div>
                          
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <?php if($_GET["tp"]=='m'){?>
                
                 <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Transfer Mode</b></div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2">
                                <b>Self-Assessed :</b> <?php    if($transfar_mode_id>0){$query_trans="select * from prop_transfer_mode_master where id='".$transfar_mode_id."'";
                        $exe_trans=pg_query($_SESSION["db_property"],$query_trans)or die("Invalid Table");
                        $val_trans=pg_fetch_array($exe_trans);}
                         ?>
                                  <?php if($transfar_mode_id>0){echo $val_trans["transfer_mode"];}else{echo "No Declaration";}?>
                            </div>
                            </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2" style="margin-left: 0px;">
                                <b>Check :</b><?php  
                    
                    if(@$_POST["rdo_transfer_mode"]==1){ $trmode=$transfar_mode_id; }else{$trmode=$_POST["transfer_mode_id"];}?>
                                <input type="hidden" name="rdo_transfer_mode" id="rdo_transfer_mode"   value="<?=$_POST["rdo_transfer_mode"]?>"  />
                                 
                            </div>
                            <div class="span2">
                                <b>Verification :</b> <?php if($trmode>0){$trval=$trmode;}else{$trval=0;}
                              $msql_trans="select * from prop_transfer_mode_master where id=$trval order by transfer_mode";
                              $mrs_trans=pg_query($_SESSION["db_property"],$msql_trans)or die("Invalid Table");
                              while($mrowtrans=pg_fetch_array($mrs_trans)){                         
                             echo $mrowtrans["transfer_mode"];?>
                              <?php }?>
                             <input type="hidden" name="transfer_mode_id" id="transfer_mode_id" value="<?=$trval?>" />
                            </div>
                          
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                
                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Percentage of Property Transfered</b></div>
                         <div class="span6" style="margin-left: 0px;"><?=$_POST["percentage_of_property"]?>
                             <input type="hidden" name="percentage_of_property" id="percentage_of_property" value="<?=$_POST["percentage_of_property"]?>"  />
                             
                      </div>
                        <div class="span6" style="margin-left: 0px;">
                      </div>
                        <div style="clear: both"></div>
                  </div>
              </div><?php }?>
                
                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Area of Plot (in decimal)</b></div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2">
                                <b>Self-Assessed :</b>  <?=$prop_val["plot_area"]?>
                            </div>
                            </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2" style="margin-left: 0px;">
                                <b>Check :</b><?php if($_POST["rdo_area_of_plot"]==1){echo "Correct";$parea=$prop_val["plot_area"];}else{echo "Incorrect";$parea=$_POST["area_of_plot"];}?>
                            <input type="hidden" name="rdo_area_of_plot"  value="<?=$_POST["rdo_area_of_plot"]?>"> 
                            </div>
                            
                            <div class="span2">
                                <b>Verification :</b>  <?=$parea?>
                                <input type="hidden" name="area_of_plot" id="area_of_plot" required value="<?=$parea?>"  />
                            </div>
                           
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>

                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Street Type</b></div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2">
                                <b>Self-Assessed :</b>  <?php
                                      /*$sqlroad="select * from prop_road_type where id='".$prop_val['road_type_id']."' order by id desc";
                                      $rsroad=pg_query($_SESSION["db_property"],$sqlroad)or die("Invalid Table");
                                     $valroad=pg_fetch_array($rsroad);
                                      echo $valroad["road_size"];*/
                                      ?>
                            </div>
                            </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2" style="margin-left: 0px;">
                                <b>Check :</b><?php /*if($_POST["rdo_street_type"]==1){echo "Correct";$rtype=$prop_val['road_type_id'];}else{echo "Incorrect";$rtype=$_POST["street_type_id"];}*/
                                ?>
                            <input type="hidden" name="rdo_street_type"  value="<?=$_POST["rdo_street_type"]?>"> 
                               <input type="hidden" name="street_type_id" id="street_type_id" value="<?=$rtype?>" />
                            </div>
                            
                            <div class="span2">
                                <b>Verification :</b> <?php
                                    /*$sqlroad1="select * from prop_road_type where id=$rtype order by id desc";
                               $rsroad1=pg_query($_SESSION["db_property"],$sqlroad1)or die("Invalid Table");
                               while($valroad1=pg_fetch_array($rsroad1)){echo $valroad1["road_size"]; }*/ ?>
                            </div>
                          
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <?php if($val_ptype["property_type"]<>'Vacant Land'){?>
                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>No. of floors</b></div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2">
                                <b>Self-Assessed :</b><?php 
                             /*$sqlfloor="select floor  from prop_arv_detail where assess_id='".$app_id."' and arv_type='B'  group by floor order by floor";
                            $rsfloor=pg_query($_SESSION["db_property"],$sqlfloor)or die("Invalid Table");
                             $nooffloor=pg_num_rows($rsfloor);echo $nooffloor;*/ ?>
                                <input type="hidden" name="hid_total_floors" id="hid_total_floors" value="<?=$nooffloor?>" />
                            </div>
                          
                      </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2" style="margin-left: 0px;">
                                <b>Check :</b><?php if($_POST["rdo_total_floors"]==1){echo "Correct";$nofloor=$nooffloor;}else{echo "Incorrect";$nofloor=$_POST["total_floors"];}?>
                            <input type="hidden" name="rdo_total_floors"  value="<?=$_POST["rdo_total_floors"]?>"> 
                            </div>
                           
                            <div class="span2">
                                <b>Verification :</b><?=$nofloor?>
                                <input type="hidden" name="total_floors" id="total_floors"  value="<?=$nofloor?>"  />
                            </div>
                          
                                                      
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <?php 
                       /*$sqlfloor1="select *  from prop_arv_detail where assess_id='".$app_id."' and arv_type='B'  order by id asc";
                        $rsfloor1=pg_query($_SESSION["db_property"],$sqlfloor1)or die("Invalid Table");
                        $i=1;
                        while($floorinfo=pg_fetch_array($rsfloor1)){
                       
            
            $occupancy_type_dtl_id=$floorinfo["occupancy_type_dtl_id"];
            $road_cons_rate_id=$floorinfo["road_cons_rate_id"];
            $usage_type_dtl_id=$floorinfo["usage_type_dtl_id"];
            
            $rowoccinfo=$Coreproperty_webApp->getRows($_SESSION["db_property"],"view_occupancy_type_dtl","id='".$occupancy_type_dtl_id."'");
            $rowusesinfo=$Coreproperty_webApp->getRows($_SESSION["db_property"],"view_usage_type_dtl","id='".$usage_type_dtl_id."'");
            $rowconstinfo=$Coreproperty_webApp->getRows($_SESSION["db_property"],"view_const_road_wise_rate","id='".$road_cons_rate_id."'   order by id desc");*/
            
            
            
            
                 ?>
                  <input type="hidden" name="floor<?=$i?>" value="<?=$floorinfo["floor"];?>">
                  <input type="hidden" name="carpet_area<?=$i?>" value="<?=$floorinfo["carpet_area"];?>">
                  <input type="hidden" name="arv_id<?=$i?>" value="<?=$floorinfo["id"];?>">                   
                  <input type="hidden" name="effect_quarter<?=$i?>" value="<?=$floorinfo["effect_quarter"];?>">
                  <input type="hidden" name="effect_year<?=$i?>" value="<?=$floorinfo["effect_year"];?>">
                  <input type="hidden" name="completion_date<?=$i?>" value="<?=$floorinfo["completion_date"];?>"> 
                  <input type="hidden" name="completiondate_upto<?=$i?>" value="<?=$floorinfo["comp_upto"];?>"> 
                   
                   
                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b><?=$floorinfo["floor"]?></b></div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="panel">
                                <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Usage Type - <?=$floorinfo["floor"]?></b></div>
                                <div class="span12" style="margin-left: 0px;">
                                    <div class="span2"><b>Self-Assessed :</b><?=$rowusesinfo['usage_type']?></div>
                                   
                              </div>
                                <div class="span12" style="margin-left: 0px;">
                                    <div class="span2" style="margin-left: 0px;">
                                        <b>Check :</b><?php if($_POST["rdo_usage_type$i"]==1){echo "Correct"; $usetp=$rowusesinfo['usage_type_id'];}else{echo "Incorrect";$usetp=$_POST["usetype_$i"];}?>
                            <input type="hidden" name="rdo_usage_type<?=$i?>"  value="<?=$_POST["rdo_usage_type$i"]?>"> 
                                    </div>
                                   
                                    <div class="span2">
                                        <b>Verification :</b><input type="hidden" name="usetype_<?=$i;?>"  id="usetype_<?php echo $i;?>"  value="<?php echo $usetp;?>"/> 
  
                                        <?php 
                                        
                                  /* $sqluse1="select * from prop_usage_type where id='$usetp'";
                                   $rsuse1=pg_query($_SESSION["db_property"],$sqluse1);
                                  $valuse1=pg_fetch_array($rsuse1);
                                     echo $valuse1["usage_type"]; */
                    ?>   
                                    </div>
                                   
                                    
                                
                                </div>
                                <div style="clear: both"></div>
                            </div>

                            <div class="panel">
                                <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Occupancy Type - <?=$floorinfo["floor"]?></b></div>
                                <div class="span12" style="margin-left: 0px;">
                                    <div class="span2">
                                        <b>Self-Assessed :</b> <?=$rowoccinfo['occupancy_type']?>
                                    </div>
                                  
                              </div>
                                <div class="span12" style="margin-left: 0px;">
                                    <div class="span2" style="margin-left: 0px;">
                                        <b>Check :</b><?php if($_POST["rdo_occupancy_type$i"]==1){echo "Correct"; $occup=$rowoccinfo['occupancy_master_id'];}else{echo "Incorrect";$occup=$_POST["occu_$i"];}?>
                            <input type="hidden" name="rdo_occupancy_type<?=$i?>"  value="<?=$_POST["rdo_usage_type$i"]?>">
                                    </div>
                                   
                                    <div class="span2">
                                        <b>Verification :</b><input type="hidden" name="occu_<?=$i?>"  id="occu_<?=$i;?>"  value="<?php echo $occup;?>"/> 
                                      
                                            
                                            <?php
                                                /*$sqloccu="select * from prop_occupancy_type_master where id='$occup'";
                                                $rsoccu=pg_query($_SESSION["db_property"],$sqloccu)or die("Invalid Table");
                                                while($valoccu=pg_fetch_array($rsoccu))
                                                {echo $valoccu["occupancy_type"];}*/
                                            ?>
                                    </div>
                                   
                                </div>
                                <div style="clear: both"></div>
                            </div>

                            <div class="panel">
                                <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Construction Type - <?=$floorinfo["floor"]?></b></div>
                                <div class="span12" style="margin-left: 0px;">
                                    <div class="span2">
                                        <b>Self-Assessed :</b> <?=$rowconstinfo['consruction_type']?> 
                  <input type="hidden" id="hid_construction_type_id_<?=$i?>" value="<?=$rowconstinfo['construction_type_id']?>" name="hid_construction_type_id_<?=$i?>" />
                                    </div>
                                   
                              </div>
                                <div class="span12" style="margin-left: 0px;">
                                    <div class="span2" style="margin-left: 0px;">
                                        <b>Check :</b><?php if($_POST["rdo_construction_type$i"]==1){echo "Correct";  $constr=$rowconstinfo['construction_type_id'];}else{echo "Incorrect";$constr=$_POST["cons_$i"];}?>
        <input type="hidden" name="rdo_construction_type<?=$i?>"  value="<?=$_POST["rdo_construction_type$i"]?>">
                              
                                    </div>
                                    <div class="span2"><b>Verification :</b>  <input type="hidden" id="cons_<?=$i?>" value="<?=$constr?>" name="cons_<?=$i?>" /> 
                      <?php
                      /*if($constr>0){$constr=$constr;}else{$constr=1;}
                         $sqlcons="select * from prop_construction_type_master where id=$constr";
                        $rscons=pg_query($_SESSION["db_property"],$sqlcons)or die("Invalid Table");
                        $valcons=pg_fetch_array($rscons);
                        echo $valcons["consruction_type"];*/
                    ?></div>
                                   
        
                                </div>
                                <div style="clear: both"></div>
                            </div>

                          <div class="panel">
                                <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Builtup Area - <?=$floorinfo["floor"]?></b></div>
                                <div class="span12" style="margin-left: 0px;">
                                    <div class="span2">
                                        <b>Self-Assessed :</b> <?=$floorinfo["built_up_area"]?>
                                    </div>
                                  
                            </div>
                                <div class="span12" style="margin-left: 0px;">
                                    <div class="span2" style="margin-left: 0px;">
                                        <b>Check :</b><?php if($_POST["rdo_builtup_area$i"]==1){echo "Correct"; $buildup=$floorinfo["built_up_area"];}else{echo "Incorrect";$buildup=$_POST["txt_builtarea_$i"];}?>
        <input type="hidden" name="rdo_builtup_area<?=$i?>"  value="<?=$_POST["rdo_builtup_area$i"]?>">
                                    </div>
                                  
                                    <div class="span2">
                                        <b>Verification :</b><?=$buildup?>
                                        <input type="hidden" id="txt_builtarea_<?=$i?>" name="txt_builtarea_<?=$i?>"  value="<?=$buildup?>" />
                                    </div>
                                  
                                </div>
                                <div style="clear: both"></div>
                          </div>
                           

                       
                        <div style="clear: both"></div> </div>
                    </div>
                <?php $i++;} ?>
                
                <!--   New Added Floor -->
                <input type="hidden" name="chkfloor" id="chkfloor" value="<?=$_POST["chkfloor"]?>">
                <?php if($_POST["chkfloor"]=="on"){?>
                 <div class="panel" style="border:#00FF00 solid 1px;" id="tr_floor_dtl_head">
<div class="panel-heading" style="background:#00FF00; color:#FFFFFF; font-weight:bold">New Added Floor  Details</div>
            
               <input type="hidden" value="<?=$_POST["countarea"]?>" name="countarea" id="countarea">
               <?php  
                      
                      $countarea=$_POST["countarea"];$cntt=1;$totalbuluduparea=0;
                       for($Arcnt=1;$Arcnt<=$countarea;$Arcnt++){
                       
                      if($Arcnt==$cntt){$cntt=$Arcnt;}else{$cntt=$cntt+1;}
                       if(strtoupper($_POST["construction_type_id$Arcnt"])=='RCC' or strtoupper($_POST["construction_type_id$Arcnt"]=='ACC')){ $slumareachk=0;}
                   if($_POST["use_type_id$Arcnt"]<>"" and $_POST["occupancy_type_id$Arcnt"]<>"" and $_POST["builtup_area$Arcnt"]<>""){                
                    $sqluse="select * from prop_usage_type where id='".$_POST["use_type_id$Arcnt"]."'";
                    $rsuse=pg_query($_SESSION["db_property"],$sqluse)or die("Invalid Table 1111");
                    $valuse=pg_fetch_array($rsuse);
                    $comdate1=strtotime(1 .'-'.$_POST["occ_mm$Arcnt"].'-'.$_POST["occ_yyyy$Arcnt"]);        
                    //$comdate=strtotime($cntt .'-'.$_POST["occ_mm$Arcnt"].'-'.$_POST["occ_yyyy$Arcnt"]);   
                    
                      if($oldlaw>=$comdate1)
                     {
                       $new_quarter=$rule_qtr;
                       $new_fy=$rule_fyyear;
                    
                     }
                    else{
                if($_POST["occ_mm$Arcnt"]=='4' || $_POST["occ_mm$Arcnt"]=='5' || $_POST["occ_mm$Arcnt"]=='6'){$new_quarter=1; $new_fy=$_POST["occ_yyyy$Arcnt"].'-'.($_POST["occ_yyyy$Arcnt"]+1);}
                elseif($_POST["occ_mm$Arcnt"]=='7' || $_POST["occ_mm$Arcnt"]=='8' || $_POST["occ_mm$Arcnt"]=='9'){$new_quarter=2;  $new_fy=$_POST["occ_yyyy$Arcnt"].'-'.($_POST["occ_yyyy$Arcnt"]+1);}
                elseif($_POST["occ_mm$Arcnt"]=='10' || $_POST["occ_mm$Arcnt"]=='11' || $_POST["occ_mm$Arcnt"]=='12'){$new_quarter=3;  $new_fy=$_POST["occ_yyyy$Arcnt"].'-'.($_POST["occ_yyyy$Arcnt"]+1);}
                else{$new_quarter=4;  $new_fy=($_POST["occ_yyyy$Arcnt"]-1).'-'.$_POST["occ_yyyy$Arcnt"];}
                      }
                        
                                        
                     $code="CODE".$Arcnt;
                    if($valuse["usage_code"]=='A'){$carpetarea=round($_POST["builtup_area$Arcnt"]*70/100);}else{$carpetarea=round($_POST["builtup_area$Arcnt"]*80/100);}
                    $sqlnew_r_rt1="select * from view_const_road_wise_rate where consruction_type='".$_POST["construction_type_id$Arcnt"]."' and road_type_id='".$rtype."' and ulb_master_id=$ulb_master_id  and suspended_status=1 order by effect_date desc";
                        $querynew_r_rt1=pg_query($_SESSION["db_property"],$sqlnew_r_rt1);
                         $cans_roadrate=pg_fetch_array($querynew_r_rt1);
                          $cons_mul_id=$cans_roadrate["id"];                        
                    
                          $sqlnew_uses1="select * from prop_usage_type_detail where usage_type_id='".$_POST["use_type_id$Arcnt"]."'  order by effect_date desc";
                          $querynew_uses=pg_query($_SESSION["db_property"],$sqlnew_uses1);
                          $uses_info=pg_fetch_array($querynew_uses);
                           $uses_mul_id=$uses_info["id"];
                        
                          $sqlnew_occu="select * from view_occupancy_type_dtl where occupancy_type='".$_POST["occupancy_type_id$Arcnt"]."' order by effect_date desc";
                          $querynew_occu=pg_query($_SESSION["db_property"],$sqlnew_occu);
                          $occu_info=pg_fetch_array($querynew_occu);
                          $occu_mul_id=$occu_info["id"];    
                                              
                          $totalarv=$carpetarea*$uses_info["mul_factor"]*$occu_info["mul_factor"]*$occu_info["mul_factor"];
                                        
     ?>
                 <input type="hidden" name="floor_id<?=$Arcnt?>" style="width: 80%" value="<?=$_POST["floor_id$Arcnt"]?>"/>
                 <input type="hidden" name="use_type_id<?=$Arcnt?>" value="<?=$_POST["use_type_id$Arcnt"]?>" />
                 <input type="hidden" name="occupancy_type_id<?=$Arcnt?>"  value="<?=$_POST["occupancy_type_id$Arcnt"]?>" /> 
                 <input type="hidden" name="construction_type_id<?=$Arcnt?>" value="<?=$_POST["construction_type_id$Arcnt"]?>"/>
                 <input type="hidden" name="builtup_area<?=$Arcnt?>"  value="<?=$_POST["builtup_area$Arcnt"]?>"  />              
                 <input type="hidden" name="occ_mm<?=$Arcnt?>" id="occ_mm<?=$Arcnt?>" value="<?=$_POST["occ_mm$Arcnt"]?>"  />
                 <input type="hidden" name="occ_yyyy<?=$Arcnt?>" id="occ_yyyy<?=$Arcnt?>"  value="<?=$_POST["occ_yyyy$Arcnt"]?>" />
                    
                 <input type="hidden" name="ncarpet_area<?=$Arcnt?>" id="ncarpet_area<?=$Arcnt?>" value="<?=$carpetarea?>"  />
                 <input type="hidden" name="ncons_mul_id<?=$Arcnt?>" id="ncons_mul_id<?=$Arcnt?>" value="<?=$cons_mul_id?>"  />
                 <input type="hidden" name="nuses_mul_id<?=$Arcnt?>" id="nuses_mul_id<?=$Arcnt?>"  value="<?=$uses_mul_id?>" />
                 <input type="hidden" name="noccu_mul_id<?=$Arcnt?>" id="noccu_mul_id<?=$Arcnt?>"  value="<?=$occu_mul_id?>" />
                 <input type="hidden" name="new_affect_qtr<?=$Arcnt?>" id="nnew_affect_qtr<?=$Arcnt?>"  value="<?=$new_quarter?>" />
                 <input type="hidden" name="new_affect_yr<?=$Arcnt?>" id="nsnew_affect_yr<?=$Arcnt?>"  value="<?=$new_fy?>" />
                 <input type="hidden" name="new_arv<?=$Arcnt?>" id="new_arv<?=$Arcnt?>"  value="<?=$totalarv?>" />
                 <input type="hidden" name="compdt<?=$Arcnt?>" id="compdt<?=$Arcnt?>"  value="<?=$comdate1?>" />
                
            
                 <?php  } ?>
                 
                
<div class="panel" id="tbl_occupancy">
<div class="span10" style="margin-left:0px;" id="tr_floor_dtl">
<div class="span2"  style="margin-left:0px;"><strong> Floor No : </strong><?=$_POST["floor_id$Arcnt"]?></div>
<div class="span2"><strong>Use Type : </strong><?=$valuse["usage_type"]?></div>
</div>

<div class="span12" style="margin-left:0px;">
<div class="span2"  style="margin-left:0px;"><strong> Occupancy Type : </strong><?=$_POST["occupancy_type_id$Arcnt"]?></div>
<div class="span2"><strong>Construction Type : </strong><?=$_POST["construction_type_id$Arcnt"]?></div>
</div>


<div class="span12" style="margin-left:0px;">
<div class="span2"  style="margin-left:0px;"><strong> Built Up Area  : </strong> <?=$_POST["builtup_area$Arcnt"]?></div>
</div>

<div class="span12" style="margin-left:0px;">
<div class="span2"  style="margin-left:0px;"><strong>  Date From : </strong> <?=$_POST["occ_mm$Arcnt"].'-'.$_POST["occ_yyyy$Arcnt"]?> </div>
</div>

<div class="clr"></div>
</div>
                 
                <?php }?>
                 </div>
                
            
                
                <?php }?>
            
                
                
                
                
                <input type="hidden" name="countdata" value="<?=$i?>">
                <?php 
                               /* $sqlhord="select * from prop_arv_detail where assess_id='".$app_id."' and arv_type='H'";
                                $rshord=pg_query($_SESSION["db_property"],$sqlhord)or die("Invalid Table");
                                if(pg_num_rows($rshord)>0){
                                $valhord=pg_fetch_array($rshord);
                                $holdcompdate=$Coreproperty_webApp->IntToDate($valhord["completion_date"]);
                                $built_up_areahord=$valhord["built_up_area"];
                                 $hord="Yes";}else{ $hord="No";}*/
                            ?>
              <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Hording Board(s)</b></div>
                        <div class="panel">
                          <div class="span12" style="margin-left: 0px;"><b>Self Assessed : Does Property Have Hoarding Board(s)? :</b><?=$hord?></div>
                                <?php if($hord=='Yes'){?><div class="span2"> <b>Installation Date of Hoarding Board(s) :</b> <?=$holdcompdate?>  </div>
                          </div>
                          
                            <div style="clear: both"></div>
                            <div class="span12" style="margin-left: 0px;">
                                <div class="span2" style="margin-left: 0px;"> <b>Total  Area  (in Sq. Ft.) :</b> </div>
                                <div class="span4" style="margin-left: 0px;"> <?=$built_up_areahord?></div>
                            </div>
                           
                      </div> <?php }?>
                        <div class="span12" style="margin-left: 0px;">
                         <div class="span2"><b>Does Property Have Hoarding Board(s) :</b> <?php if($_POST["has_hording"]==1){$hording='Yes';}else{$hording='No';} echo $hording;?>
                             <input type="hidden"  name="has_hording" id="has_hording" value="<?=$_POST["has_hording"]?>">
                         </div>
                           <?php if($hording=='Yes'){?>
                           
                           <div class="span2" style="margin-left: 0px;"><b>Installation Date of Hoarding Board(s) :</b> <?=$_POST["hording_installation_date"]?></div>
                          <input type="hidden" name="hording_installation_date" id="hording_installation_date" value="<?=$_POST["hording_installation_date"]?>"   /> 
                                            
                  <div class="span12" style="margin-left: 0px;">
                        <div class="span2"><b>Total Floor Area of Roof / Land (in Sq. Ft.) :</b> <?=$_POST["total_hording_area"]?></div>
                        <input type="hidden" name="total_hording_area" id="total_hording_area"  value="<?=$_POST["total_hording_area"]?>" />
                  </div>
                  <?php }?>
                        </div>
                        </div>
                        <div style="clear: both"></div>
                    </div>
              
              
              
               <?php 
                              /* $sqltower="select * from prop_arv_detail where assess_id='".$app_id."' and arv_type='T'";
                                $rstower=pg_query($_SESSION["db_property"],$sqltower)or die("Invalid Table");
                                if(pg_num_rows($rstower)>0){
                                $valtower=pg_fetch_array($rstower);
                                $towcompdate=$Coreproperty_webApp->IntToDate($valtower["completion_date"]);
                                $built_up_areatower=$valtower["built_up_area"];
                                 $tow="Yes";}else{ $tow="No";}*/
                            ?>
              <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Mobile Tower</b></div>
                        <div class="panel">
                          <div class="span12" style="margin-left: 0px;">
                                <div class="span2" style="margin-left: 0px;">
                                    <b>Does Property Have Mobile Tower? :</b>    <?=$tow?>                             </div>
                               
                                <?php if($tow=='Yes'){?>
                                <div class="span2"> <b>Installation Date of Mobile Tower :</b>  </div>
                                <div class="span4"> <?=$towcompdate?></div>
                          </div>
                            <div style="clear: both"></div>
                            <div class="span12" style="margin-left: 0px;">
                                <div class="span2" style="margin-left: 0px;"> <b>Total  Area  (in Sq. Ft.) :</b> </div>
                                <div class="span4" style="margin-left: 0px;"> <?=$built_up_areatower?></div>
                                <div class="span2" style="margin-left: 0px;"> </div>
                                <div class="span4" style="margin-left: 0px;"> </div>
                            </div>
                           
                      </div> <div style="clear: both"></div><?php }?>
                      
                        <div class="span12" style="margin-left: 0px;">
                         <div class="span2"><b>Does Property Have Mobile Tower :</b> <?php if($_POST["has_mobile_tower"]==1){$tower='Yes';}else{$tower='No';} echo $tower;?></div>
                                <input type="hidden"  name="has_mobile_tower" id="has_mobile_tower" value="<?=$_POST["has_mobile_tower"]?>">
                 <?php if($tower=='Yes'){?>
                     <div class="span2" style="margin-left: 0px;"> <div class="tw_details"><b>Installation Date of Mobile Tower :</b> <?=$_POST["tower_installation_date"]?></div>
                       <input type="hidden" name="tower_installation_date" id="tower_installation_date" value="<?=$_POST["tower_installation_date"]?>"/> 
                       </div> 
                        
                  <div class="span12" style="margin-left: 0px;">
                  <div class="span2"><div class="tw_details"><b>Total Floor Area of Roof / Land (in Sq. Ft.) :</b> <?=$_POST["total_tower_area"]?></div></div>
                 <input type="hidden" name="total_tower_area" id="total_tower_area"  value="<?=$_POST["total_tower_area"]?>" /></div>
                 <?php }?>
                     <div class="span4"></div>
                      </div>
                      <div style="clear: both"></div>
                     </div></div>
              </div>
              
              
                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Petrol Pump</b></div>
                        <?php if($val_ptype["property_type"]=='Independent Building'){
                               $sqlpetrol="select * from prop_arv_detail where assess_id='".$app_id."' and arv_type='P'";
                                $rspetrol=pg_query($_SESSION["db_property"],$sqlpetrol)or die("Invalid Table");
                                if(pg_num_rows($rspetrol)>0){
                                $val_petrol=pg_fetch_array($rspetrol);
                                $pinsdate=$Coreproperty_webApp->IntToDate($val_petrol["completion_date"]);
                                $built_up_area=$val_petrol["built_up_area"];
                                $pet="Yes";}else{$pet="No";}
                            ?>
                        <div class="panel">
                          <div class="span12" style="margin-left: 0px;">
                                <div class="span2" style="margin-left: 0px;"> <b>Is property a Petrol Pump ? :</b> <?=$pet?></div>
                               
                                <?php if($pet=='Yes'){?>
                                <div class="span2"><b>Completion Date of Petrol Pump :</b></div>
                                <div class="span4"><?=$pinsdate?></div>
                            </div>
                            <div style="clear: both"></div>
                            
                            
                            <div class="span12" style="margin-left: 0px;">
                                <div class="span2" style="margin-left: 0px;"> <b>Underground Storage Area (in Sq. Ft.) :</b></div>
                                <div class="span4" style="margin-left: 0px;"> <?=$built_up_area?></div>
                                <div class="span2" style="margin-left: 0px;"></div>
                                <div class="span4" style="margin-left: 0px;"> </div>
                            </div><?php }?>
                            <div style="clear: both"></div>
                            </div>
                            <?php }?>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2">
                                <b>Is property a Petrol Pump ? :</b><?php if($_POST["is_petrol_pump"]==1){$pet1='Yes';}else{$pet1='No';} echo $pet1;?>
                            
                                <input type="hidden" name="is_petrol_pump" id="is_petrol_pump" value="<?=$_POST["is_petrol_pump"]?>">
                            </div>
                           <?php if($pet1=='Yes'){?>
                            <div class="span2" style="margin-left: 0px;">
                                <div class="pt_details" style="display:none"
                                    >
                                    <b>Completion Date of Petrol Pump :</b>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="pt_details"><?=$_POST["petrol_pump_completion_date"]?>
                              <input type="hidden" name="petrol_pump_completion_date" id="petrol_pump_completion_date" value="<?=$_POST["petrol_pump_completion_date"]?>"  />
                                    
                                </div>
                            </div>

                        </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2">
                                <div class="pt_details">
                                    <b>Underground Storage Area (in Sq. Ft.) :</b>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="pt_details"><?=$_POST["under_ground_area"]?>
                                    <input type="hidden" name="under_ground_area" id="under_ground_area" value="<?=$_POST["under_ground_area"]?>" />
                                    
                                </div>
                            </div><?php }?>
                            <div class="span2" style="margin-left: 0px;">
                            </div>
                            <div class="span4">
                            </div>
                        </div>
                        <div style="clear: both"></div>

                    </div>

                </div>
                
                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Water Harvesting Provision</b></div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2">
                                <b>Self-Assessed :</b>
                            </div>
                            <div class="span4"><?php echo $prop_val['water_harvesting'];?>
                              <input type="hidden" name="hid_water_harvesting" id="hid_water_harvesting" value="<?php echo $prop_val['water_harvesting'];?>" />
                                
                            </div>
                        </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span2" style="margin-left: 0px;">
                                <b>Check :</b>
                            </div>
                            <div class="span4">
                            <?php
                            if($prop_val['water_harvesting']=='Yes'){$harvest=1;}else{$harvest=0;}
                             if($_POST["rdo_water_harvesting"]==1){echo "Correct"; $harvest=$harvest;}else{echo "Incorrect";$harvest=$_POST["water_harvesting"];} ?>
        <input type="hidden" name="rdo_water_harvesting"  value="<?=$_POST["rdo_water_harvesting"]?>">
                               
                                
                            </div>
                            <div class="span2"><b>Verification :</b></div>
                            <div class="span4">
                               <?php if($harvest==1){echo $harvestval='Yes';}else{echo $harvestval='No';}?>
                                <input type="hidden" name="water_harvesting" id="water_harvesting" value="<?=$harvest?>"  >
                            </div>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                
                <?php if($_SESSION["usertype"]>0){ ?>
                  <script>
    function checkboxchk()
   { 
   var check=document.getElementById("chkfimg");

    if(check.checked==true)
    { 
      document.getElementById("showflrr").style.display="block";
    }
    else
    {
     document.getElementById("showflrr").style.display="none";   
    }
} 

 $(document).ready(function () {
                        
                $("#FORMNAME1").validate({
                    rules: {
                             prop_img_front: {   required:true, },
                             prop_img_right: {   required:true, },
                             prop_img_left: {   required:true, },
                             prop_img_water_harvesting: {   required:true, },
                             prop_img_mobile_tower: {   required:true, },
                             prop_img_hoarding_board: {   required:true, },
                    },
                    messages: { 
                    },
                });
               
            });
            $.validator.messages.required = '';
            
</script>

                <!-- Image Upload -->
                <!-- Front Image Upload -->
                
                
    <!--    <div class="panel"><strong>Do You Want to upload Property image</strong> &nbsp;&nbsp;<input type="checkbox"  name="chkfimg" id="chkfimg"  onClick="checkboxchk()"></div>-->
        
        <div id="showflrr" style="display:none;">
                 <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Property Image - Front View</b></div>
                        <div class="span12" style="margin-left: 0px;">
                          <div class="span2" style="margin-left: 0px;">
                                <b>Upload Image :</b>
                            </div>
                            <div class="span4">
     <input id="prop_img_front" name="prop_img_front" onChange="readURL(this,'#img_img_front','address_front','latlong_front','FLongLat')" type="file" accept="image/*">
                              <input type="hidden" name="FLongLat" id="FLongLat" />    
                            </div>
                        </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span6">
                                <img src="../common/images/home_final.png"  id="img_img_front" style="height: 150px" />
                            </div>
                          
                            <div class="span3" style="font-weight: bold" id="address_front">
                            </div>
                              <div class="span3" id="latlong_front">
                            </div>

                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <!-- End Front Image Upload -->
                
                
                
                <!-- Start Right Image Upload -->
                
                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background: #0066FF; color: #fff"><b>Property Image - Right View</b></div>
                        <div class="span12" style="margin-left: 0px;">
                          <div class="span2" style="margin-left: 0px;">
                                <b>Upload Image :</b>
                            </div>
                            <div class="span4">
                                <input id="prop_img_right" name="prop_img_right" onChange="readURL(this,'#img_img_right','address_right','latlong_right','RLongLat')" type="file" accept="image/*">
                            <input type="hidden" name="RLongLat" id="RLongLat" /> 
                               
                            </div>
                        </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span6">
                                <img src="../common/images/home_final.png" id="img_img_right" style="height: 150px" />
                            </div>
                            <div class="span3" style="font-weight: bold" id="address_right">
                            </div>
                             <div class="span3" id="latlong_right">
                            </div>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <!-- End Right Image Upload -->
                
                <!-- Start Right Left Upload -->
                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Property Image - Left View</b></div>
                        <div class="span12" style="margin-left: 0px;">
                          <div class="span2" style="margin-left: 0px;">
                                <b>Upload Image :</b>
                            </div>
                            <div class="span4">
                                <input id="prop_img_left" name="prop_img_left"  onChange="readURL(this,'#img_img_left','address_left','latlong_left','LLongLat')" type="file" accept="image/*">
                             <input type="hidden" name="LLongLat" id="LLongLat" /> 
                             
                             
                            </div>
                        </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span6">
                                <img src="../common/images/home_final.png"  id="img_img_left" style="height: 150px" />
                            </div>
                            <div class="span3" style="font-weight: bold" id="address_left">
                            </div>
                            <div class="span3"  id="latlong_left">
                            </div>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <!-- End Left Upload -->
                
                
                <?php if($harvest==1){ ?>
                <!-- Start Water Harvesting Image Upload -->
                 <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background: #0066FF; color: #fff"><b>Water Harvesting Structure</b></div>
                        <div class="span12" style="margin-left: 0px;">
                          <div class="span2" style="margin-left: 0px;">
                                <b>Upload Image :</b>
                            </div>
                            <div class="span4">
                                <input id="prop_img_water_harvesting"  name="prop_img_water_harvesting" onChange="readURL(this,'#img_img_water_harvesting','address_water_harvesting','latlong_water_harvesting','WHLongLat')" type="file" accept="image/*">
                                <input type="hidden" name="WHLongLat" id="WHLongLat" />   
                            </div>
                        </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span6">
                                <img src="../common/images/home_final.png" id="img_img_water_harvesting" style="height: 150px" />
                            </div>
                            <div class="span3" style="font-weight: bold" id="address_water_harvesting">
                            </div>
                            <div class="span3"  id="latlong_water_harvesting">
                            </div>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                    <!-- End Water Harvesting Image Upload -->
                    <?php } if($tower=='Yes'){?>
                    
                    <!-- Start Mobile Tower Image Upload -->
                    <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background: #0066FF; color: #fff"><b>Mobile Tower</b></div>
                        <div class="span12" style="margin-left: 0px;">
                          <div class="span2" style="margin-left: 0px;">
                                <b>Upload Image :</b>
                            </div>
                            <div class="span4">
                                <input id="prop_img_mobile_tower" name="prop_img_mobile_tower" onChange="readURL(this,'#img_mobile_tower','address_mobile_tower','latlong_mobile_tower','MTLongLat')" type="file" accept="image/*">
                                 <input type="hidden" name="MTLongLat" id="MTLongLat" />   
                            </div>
                        </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span6">
                                <img src="../common/images/home_final.png" id="img_mobile_tower" style="height: 150px" />
                            </div>
                            <div class="span3" style="font-weight: bold" id="address_mobile_tower">
                            </div>
                            <div class="span3"  id="latlong_mobile_tower">
                            </div>
                        </div>
                        <div style="clear: both"></div>
                    </div> 
                </div>
                <!-- End Mobile Tower Image Upload -->
                <?php } if($hording=='Yes'){?>
                
                <!-- Start Hoarding Image Upload -->
                 <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background: #0066FF; color: #fff"><b>Hoarding Board</b></div>
                        <div class="span12" style="margin-left: 0px;">
                          <div class="span2" style="margin-left: 0px;">
                                <b>Upload Image :</b>
                            </div>
                            <div class="span4">
                                <input id="prop_img_hoarding_board" name="prop_img_hoarding_board" onChange="readURL(this,'#img_hoarding_board','address_hoarding_board','latlong_hoarding_board','HBLongLat')" type="file" accept="image/*">
                                 <input type="hidden" name="HBLongLat" id="HBLongLat" />   
                            </div>
                        </div>
                        <div class="span12" style="margin-left: 0px;">
                            <div class="span6">
                                <img src="../common/images/home_final.png"  id="img_hoarding_board" style="height: 150px" />
                            </div>
                            <div class="span3" style="font-weight: bold" id="address_hoarding_board">
                            </div>
                            <div class="span3"  id="latlong_hoarding_board">
                            </div>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <?php }}?>
                </div>
                    <!-- End Image Upload -->
                
                
                <div style="text-align: center">
                <input type="submit" name="back" value="Go Back" id="back" style="width: 120px"  class="btn btn-success cancel" onClick="chgAction('back')" />
               <input id="save_agency_survey" type="submit" name="Save" value="Save & Next" style="width: 100px" onClick="chgAction('Save')" class="btn btn-success" />
                </div>
  </form>
</div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>

<script type="text/javascript">
$(document).ready( function () {
    $("#btndesign").click(function() {
        var process = true;
        var saf_no = $("#saf_no").val();

        if (saf_no == '') {
            $("#saf_no").css({"border-color":"red"});
            $("#saf_no").focus();
            process = false;
          }
        return process;
    });
    $("#saf_no").keyup(function(){$(this).css('border-color','');});
});
</script>