<!DOCTYPE html>
<html lang="en">
<head>
<title> Rental Rate</title>
<link href="<?=base_url();?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/css/nifty.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <div class="boxed mar-top">
        <div class="container">
            <label class="text-2x text-dark">Rental Rate</label>
            <hr />
            <div id="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-dark">
                        <tr>
                            <th>Construction Type</th>
                            <th>Principal Main Road</th>
                            <th>Main Road</th>
                            <th>Other</th>
                        </tr>
                    </thead>
					<?php
						if( isset($rentalRateFactorList) ) {
					?>
						<tbody>
							<?php
							$i = 0;
							foreach ($rentalRateFactorList as $rentalRateFactor) {
							?>
								<tr>
									<td class="text-normal"><?=$rentalRateFactor[0]['construction_type'];?></td>
									<td class="text-normal"><?=$rentalRateFactor[0]['cal_rate'];?></td>
									<td class="text-bold"><?=$rentalRateFactor[1]['cal_rate'];?></td>
									<td class="text-bold"><?=$rentalRateFactor[2]['cal_rate'];?></td>
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
