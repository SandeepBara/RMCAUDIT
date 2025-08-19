<div class="panel-body">
                   
                    <div class="row">
                    <label class="col-md-3">Ward No. <b>:</b></label>
                        <div class="col-md-3 text-bold ">
                            <?= $basic_details_data['ward_no']; ?>
                        </div>

                        <label class="col-md-3">New Holding No. <b>:</b> </label>
                        <div class="col-md-3 text-bold ">
                            <?= $basic_details_data['new_holding_no']; ?>
                        </div>

                       
                    </div>

                    <div class="row">
                        <label class="col-md-3">New Ward No. <b>:</b></label>
                        <div class="col-md-3 text-bold ">
                       <?= $basic_details_data['new_ward_no'] ?>
                            </div>


                        <label class="col-md-3">Old Holding No. <b>:</b></label>
                        <div class="col-md-3 text-bold ">
                            <?= ($basic_details_data['holding_no']); ?>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-3">Assessment Type <b>:</b></label>
                        <div class="col-md-3 text-bold ">
                            <?= $basic_details_data['assessment_type']; ?>
                        </div>


                        <label class="col-md-3">Plot No. <b>:</b></label>
                        <div class="col-md-3 text-bold ">
                            <?= $basic_details_data['plot_no']; ?>
                        </div>
                    </div>


                    <div class="row">
                    <label class="col-md-3">Property Type <b>:</b></label>
                        <div class="col-md-3 text-bold ">
                            <?= $basic_details_data['property_type']; ?>
                        </div>
                       
                        <label class="col-md-3">Area of Plot <b>:</b></label>
                        <div class="col-md-3 text-bold ">
                        <?= $basic_details_data['area_of_plot']; ?> (decimal)
                        </div>
                    </div>
                    <div class="row">
                    <label class="col-md-3">Ownership Type <b>:</b></label>
                        <div class="col-md-3 text-bold ">
                            <?= $basic_details_data['ownership_type']; ?>
                        </div>
                       

                        <label class="col-md-3">Rain Water Harvesting <b>:</b></label>
                        <div class="col-md-3 text-bold">
                            <?= ($basic_details_data['is_water_harvesting'] == "t") ? "Yes" : "No"; ?>
                        </div>
                    </div>
                    <div class="row">
                    <label class="col-md-3">Holding Type <b>:</b></label>
                        <div class="col-md-3 text-bold ">
                            <?= str_replace('_',' ',$basic_details_data['holding_type']); ?>
                        </div>

                        <label class="col-md-3">Address <b>:</b></label>
                        <div class="col-md-3 text-bold ">
                            <?= $basic_details_data['prop_address']; ?>
                        </div>
                    </div>
                   

                    <div class="row">
                    <label class="col-md-3">Road Type <b>:</b></label>
                        <div class="col-md-3 text-bold ">
                            <?= $basic_details_data['road_type']; ?>
                        </div>
                        <label class="col-md-3">Zone <b>:</b></label>
                        <div class="col-md-3 text-bold">
                            <?= ($basic_details_data['zone_mstr_id'] == 1) ? "Zone 1" : "Zone 2"; ?>
                        </div>
                    </div>

                    <div class="row">
                    <label class="col-md-3">Entry Type <b>:</b></label>
                        <div class="col-md-3 text-bold ">
                            <?= ($basic_details_data['entry_type']); ?>
                        </div>
                       
                    </div>
                    <div class="row">
                        
                        <!-- apartment case -->
                        <?php if(isset($basic_details_data['appartment_name'])){ if($basic_details_data['appartment_name']!='N/A') {    ?>
                        <label class="col-md-3">Apartment <b>:</b></label>
                            <div class="col-md-3 text-bold ">
                                <?= $basic_details_data['appartment_name']; ?> (<?= isset($basic_details_data['apt_code']) ? $basic_details_data['apt_code'] : "N/A"; ?>)
                            </div>
                        <?php  } }  ?>
                        <?php if(isset($prop_type_mstr_id)){ if($prop_type_mstr_id==3) {    ?>
                        <label class="col-md-3">Registry Date <b>:</b></label>
                            <div class="col-md-3 text-bold ">
                                <?= $basic_details_data['flat_registry_date']; ?>
                            </div>
                            <?php  } }  ?>
                     
                    </div>

                   

                </div>