<!DOCTYPE html>
<html lang="en">
<head>
<title>Occupancy Factors</title>
<link href="<?=base_url();?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/css/nifty.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <div class="boxed mar-top">
        <div class="container">
            <label class="text-2x text-dark">Occupancy Factors</label>
            <hr />
            <div id="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-dark">
                        <tr>
                            <th>#</th>
                            <th>Occupancy Type</th>
                            <th>Multiplying Factor</th>
                        </tr>
                    </thead>
                <?php
                    if( isset($occupancyTypeFactorList) ) {
                ?>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($occupancyTypeFactorList as $occupancyTypeFactor) {
                        ?>
                            <tr>
                                <td class="text-normal"><?=++$i;?></td>
                                <td class="text-normal"><?=$occupancyTypeFactor['occupancy_name'];?></td>
                                <td class="text-bold"><?=$occupancyTypeFactor['mult_factor'];?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                <?php
                    }
                ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
