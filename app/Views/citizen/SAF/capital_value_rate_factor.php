<!DOCTYPE html>
<html lang="en">

<head>
    <title> Capital Value Rate</title>
    <link href="<?= base_url(); ?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/public/assets/css/nifty.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>


    <div class="boxed mar-top">
        <div class="row">
            <div class="col-md-12 text-center">
                <label class="text-x2 text-dark">Capital Value Rate</label>
            </div>
        </div>
        
        <div id="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-dark">
                    <tr>
                        <td></td>
                       
                       
                        <th colspan="4" class="text-center">DLX Apartment (Square Feet )</th>
                        <th colspan="4" class="text-center">Building Pakka(Square Feet )</th>
                        <th colspan="4" class="text-center">Building Kaccha(Square Feet )</th>
                    </tr>
                    <tr>
                        <th class="text-center">WARD NO</th>
                        <th class="text-center">Urban Residential Main Road</th>
                        <th class="text-center">Urban Commercial Main Road</th>
                        <th class="text-center">Urban Residential</th>
                        <th class="text-center">Urban Commercial</th>
                        <th class="text-center">Urban Residential Main Road</th>
                        <th class="text-center">Urban Commercial Main Road</th>
                        <th class="text-center">Urban Residential</th>
                        <th class="text-center">Urban Commercial</th>
                        <th class="text-center">Urban Residential Main Road</th>
                        <th class="text-center">Urban Commercial Main Road</th>
                        <th class="text-center">Urban Residential</th>
                        <th class="text-center">Urban Commercial</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($capital_rate)) {
                        foreach ($capital_rate as $item) { ?>
                            <tr>
                                <td><?= $item[0]['ward_no'] ?></td>
                                <?php foreach ($item as $key=>$data) { 
                                   
                                        if($key<=3){
                                            continue;
                                        }
                                       ?>
                                    <td><?= $data['rate'] ?></td>
                                <?php } ?>
                            </tr>
                    <?php }
                    }    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>