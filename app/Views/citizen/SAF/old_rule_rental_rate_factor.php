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
                        <?php if(isset($_SESSION['ulb_dtl']['ulb_mstr_id'])){
                                if($_SESSION['ulb_dtl']['ulb_mstr_id']==1){ ?>
                            <th></th>
                            <th class="text-center" colspan="3">ZONE 1</th>
                            
                            <th class="text-center" colspan="3">ZONE 2</th>
                           <?php } } ?>
                        </tr>
                        <tr>
                            <th>Construction Type <br /> ------------ <br /> USE OF BUILDING </th>
                            <th>Pucca with RCC Roof (RCC)</th>
                            <th>Pucca with Asbestos/Corrugated Sheet (ACC)</th>
                            <th>Kuttcha with Clay Roof (Other)</th>

                            <?php if(isset($_SESSION['ulb_dtl']['ulb_mstr_id'])){
                                if($_SESSION['ulb_dtl']['ulb_mstr_id']==1){ ?>
                            <th>Pucca with RCC Roof (RCC)</th>
                            <th>Pucca with Asbestos/Corrugated Sheet (ACC)</th>
                            <th>Kuttcha with Clay Roof (Other)</th>
                           <?php } } ?>
                           
                        </tr>
                    </thead>
                <?php
                    if( isset($rentalOldRuleRateFactorList) ) {
                ?>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($rentalOldRuleRateFactorList as $key=>$rentalOldRuleRateFactor) {
                        ?>
                            <tr>
                            <?php
                            if ( $key==0 ) {
                            ?>
                                <td class="text-normal">RESIDENTIAL</td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[0]['rate'];?></td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[1]['rate'];?></td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[2]['rate'];?></td>

                                <?php if(isset($_SESSION['ulb_dtl']['ulb_mstr_id'])){
                                if($_SESSION['ulb_dtl']['ulb_mstr_id']==1){ ?>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[3]['rate'];?></td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[4]['rate'];?></td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[5]['rate'];?></td>
                           <?php } } ?>
                                
                            <?php
                            } else if ( $key==1 ) {
                            ?>
                                <td class="text-normal">COMMERCIAL</td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[0]['rate'];?></td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[1]['rate'];?></td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[2]['rate'];?></td>
                                <?php if(isset($_SESSION['ulb_dtl']['ulb_mstr_id'])){
                                if($_SESSION['ulb_dtl']['ulb_mstr_id']==1){ ?>
                               <td class="text-bold"><?=$rentalOldRuleRateFactor[3]['rate'];?></td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[4]['rate'];?></td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[5]['rate'];?></td>
                           <?php } } ?>
                                
                            <?php
                            }
                            ?>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                
                </table>
            <?php
            } else {
            ?>
                <table class="table table-bordered">
                    <thead class="bg-dark">
                        <tr>
                            <th>Construction Type <br /> ------------ <br /> USE OF BUILDING </th>
                            <th>Pucca with RCC Roof (RCC)</th>
                            <th>Pucca with Asbestos/Corrugated Sheet (ACC)</th>
                            <th>Kuttcha with Clay Roof (Other)</th>
                        </tr>
                    </thead>
                <?php
                    if( isset($rentalOldRuleRateFactorList) ) {
                ?>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($rentalOldRuleRateFactorList as $key=>$rentalOldRuleRateFactor) {
                        ?>
                            <tr>
                            <?php
                            if ( $key==0 ) {
                            ?>
                                <td class="text-normal">RESIDENTIAL</td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[0]['rate'];?></td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[1]['rate'];?></td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[2]['rate'];?></td>
                            <?php
                            } else if ( $key==1 ) {
                            ?>
                                <td class="text-normal">COMMERCIAL</td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[0]['rate'];?></td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[1]['rate'];?></td>
                                <td class="text-bold"><?=$rentalOldRuleRateFactor[2]['rate'];?></td>
                            <?php
                            }
                            ?>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                <?php
                    }
                ?>
                </table>
            <?php
            }
            ?>
            </div>
        </div>
    </div>
</body>
</html>
