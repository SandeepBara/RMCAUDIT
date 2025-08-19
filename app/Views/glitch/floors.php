<thead>
<tr>
    <th colspan="7">Extra Floor</th>
</tr>
</thead>

<thead class="bg-trans-dark text-dark">
<tr>
    <th>Floor No. </th>
    <th>Usage Type</th>
    <th>Occupancy Type</th>
    <th>Construction Type</th>
    <th>Built Up Area (in Sq. Ft.)</th>
    <th>Carpet Area (in Sq. Ft.)</th>
    <th>Date of Completion</th>
    <th></th>
</tr>
</thead>
<tbody>
<?php
        if($extrafloorDetails){?>
            <?php
            foreach($extrafloorDetails as $new_floor)
            {
                ?>
                <tr>
                    <td><?=$new_floor['floor_name'];?></td>
                    <td><?=$new_floor['usage_type'];?></td>
                    <td><?=$new_floor['occupancy_name'];?></td>
                    <td><?=$new_floor['construction_type'];?></td>
                    <td><?=$new_floor['builtup_area'];?></td>
                    <td><?=$new_floor['carpet_area'];?></td>
                    <td><?=$new_floor['date_from'];?></td>
                    <td>
                        <button class="ladda-button wh btn-glitch" data-color="mint" data-style="expand-right" data-size="xs"
                                onclick="correction($(this),'FA');" data-vid="<?=$new_floor['field_verification_dtl_id'];?>"
                                data-floorID="<?=$new_floor['id'];?>" data-propID="<?=$prop_dtl_id;?>">
                            <span class="ladda-label" >Add Floor</span>
                        </button>
                </tr>
                <?php
            }
            ?>
<?php } ?>
</tbody>