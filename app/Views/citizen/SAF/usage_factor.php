<!DOCTYPE html>
<html lang="en">
<head>
<title>Usage Factors</title>
<link href="<?=base_url();?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/css/nifty.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <div class="boxed mar-top">
        <div class="container">
            <label class="text-2x text-dark">Usage Factors</label>
            <hr />
            <div id="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-dark">
                        <tr>
                            <th>#</th>
                            <th>Usage Type</th>
                            <th>Multiplying Factor</th>
                        </tr>
                    </thead>
					<?php
						if( isset($usageTypeFactorList) ) {
					?>
						<tbody>
							<?php
							$i = 0;
							foreach ($usageTypeFactorList as $usageTypeFactor) {
							?>
								<tr>
									<td class="text-normal"><?=++$i;?></td>
									<td class="text-normal"><?=$usageTypeFactor['usage_type'];?></td>
									<td class="text-bold"><?=$usageTypeFactor['mult_factor'];?></td>
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
