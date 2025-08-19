<!DOCTYPE html>
<html lang="en">
<head>
<title> Rates Vacant Land</title>
<link href="<?=base_url();?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/css/nifty.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <div class="boxed mar-top">
        <div class="container">
            <label class="text-2x text-dark">Rates Vacant Land</label>
            <hr />
            <div id="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-dark">
                        <tr>
                            <th>Road Type</th>
                            <th>Rate</th>
                        </tr>
                    </thead>
                <?php
                    if( isset($rentaVacantLandRateFactorList) ) {
                ?>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($rentaVacantLandRateFactorList as $rentaVacantLandRateFactor) {
                        ?>
                            <tr>
                                <td class="text-normal"><?=$rentaVacantLandRateFactor['road_type'];?></td>
                                <td class="text-normal"><?=$rentaVacantLandRateFactor['rate'];?></td>
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
