<thead>
<tr>
    <th>Type</th>
    <th>Property Details</th>
    <th>ULB Verification</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php
        if($property['is_water_harvesting']!=$ulbverification['is_water_harvesting']){?>
            <tr>
                <th>Water Harvesting</th>
                <td><?= ($property['is_water_harvesting'] == "t") ? "Yes" : "No"; ?></td>
                <td><?= ($ulbverification['is_water_harvesting'] == "t") ? "Yes" : "No"; ?></td>
                <td><button class="ladda-button btn-glitch btn-sm" data-color="mint" data-style="expand-right" data-size="xs"
                            onclick="correction($(this),'WH');" data-propid="<?=$prop_dtl_id;?>">Update</button></td>
            </tr>
<?php        }?>
<?php
        if($property['prop_type_mstr_id']!=$ulbverification['prop_type_mstr_id']){?>
            <tr>
                <th>Property Type</th>
                <td><?= ($property['property_type']) ?></td>
                <td><?= ($ulbverification['property_type']) ?></td>
                <td><button  >Update</button></td>
            </tr>
<?php        }?>
<?php
        if($property['road_type_mstr_id']!=$ulbverification['road_type_mstr_id']){?>
            <tr>
                <th>Street Type</th>
                <td><?= ($property['road_type']) ?></td>
                <td><?= ($ulbverification['road_type']) ?></td>
                <td><button class="ladda-button btn-glitch btn-sm" data-color="mint" data-style="expand-right" data-size="xs"
                            onclick="correction($(this),'ST');" data-propid="<?=$prop_dtl_id;?>">Update</button></td>
            </tr>
<?php        }?>
<?php
        if($property['is_petrol_pump']!=$ulbverification['is_petrol_pump']){?>
            <tr>
                <th>Is Petrol Pump</th>
                <td><?= ($property['is_petrol_pump'] == "t") ? "Yes" : "No"; ?></td>
                <td><?= ($ulbverification['is_petrol_pump'] == "t") ? "Yes" : "No"; ?></td>
                <td><button>Update</button></td>
            </tr>
<?php        }?>

<!--                                                        <tfoot>-->
<!--                                                            <tr>-->
<!--                                                                <td colspan="8">-->
<!--                                                                    <input type="submit" name="upto_update" id="upto_update" value="Update" class="btn btn-primary" />-->
<!--                                                                    <input type="hidden" id="holding_no" name="holding_no" value="--><?php //echo isset($holding_no)?$holding_no:'NA';?><!-- " class="form-control" >-->
<!--                                                                </td>-->
<!--                                                            </tr>-->
<!--                                                        </tfoot>-->
</tbody>