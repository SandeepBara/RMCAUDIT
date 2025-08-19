<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">Details for Property Tax and User Charges</h5>
            </div>
        </div>      
        <div class="panel panel-dark">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="dataTableID" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th width="7%">SL No.</th>
                                        <th width="43%">Indicators</th>
                                        <th width="10%">2018-2019</th>
                                        <th width="10%">2019-2020</th>
                                        <th width="10%">2020-2021</th>
                                        <th width="10%">2021-2022</th>
                                        <th width="10%">2022-2023</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b>I</b></td>
                                        <td><b>Property Tax Demand Details</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Are user charges collected with property tax? (Yes/No)</td>
                                        <td>No</td>
                                        <td>No</td>
                                        <td>Yes</td>
                                        <td>Yes</td>
                                        <td>Yes</td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Total property tax demand (including cess, other tax, AND excluding user charges if user charges are collected with property tax) ( INR Lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['total_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['total_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['total_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['total_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['total_demand']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Current property tax demand (including cess, other taxes, AND excluding user charges if user charges are collected with property tax) (INR Lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['current_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['current_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['current_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['current_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['current_demand']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Arrear property tax demand (including cess, other taxes, AND excluding user charges if user charges are collected with property tax) (INR Lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['arrear_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['arrear_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['arrear_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['arrear_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['arrear_demand']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>E</td>
                                        <td>Total property tax demand (excluding cess, other taxes, user charges if any) (INR Lakh)</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <td>F</td>
                                        <td>Demand figure for each type of cess collected (INR Lakh)</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <td>G</td>
                                        <td>Demand figure for each type of cess other than property tax collected (INR Lakh)</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <td>H</td>
                                        <td>Demand figure for each type of user charge collected along with property tax (INR Lakh)</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>


                                    <tr>
                                        <td>II</td>
                                        <td><b>Property tax collection Details</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Total collections (including cess, other taxes, AND excluding user charges if user charges are collected with property tax) (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['total_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['total_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['total_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['total_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['total_collection_amount']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Current collections (including cess, other taxes, AND excluding user charges if user charges are collected with property tax) (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['current_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['current_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['current_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['current_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['current_collection_amount']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Arrear collections (including cess, other taxes. AND excluding user charges if user charges are collected with property tax) (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['arrear_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['arrear_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['arrear_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['arrear_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['arrear_collection_amount']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Total collections (excluding cess, other taxes, user charges if any) (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['total_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['total_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['total_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['total_collection_amount']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['total_collection_amount']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>E</td>
                                        <td>Collection figure for each type of cess collected (INR lakh)</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <td>F</td>
                                        <td>Collection figure for each type of tax other than property tax collected (INR lakh)</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <td>G</td>
                                        <td>Collection figure for each type of user charge collected along with property tax (INR lakh)</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>


                                    <tr>
                                        <td><b>III</b></td>
                                        <td><b>Property Register Details</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Total number of properties mapped in the ULB (including properties exempted from paying property tax)*</td>
                                        <td><?php echo $report_list['2018-2019']['prop_mapped']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['prop_mapped']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['prop_mapped']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['prop_mapped']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['prop_mapped']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Total number of properties exempted from paying property tax*</td>
                                        <td><?php echo $report_list['2018-2019']['exampted_prop']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['exampted_prop']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['exampted_prop']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['exampted_prop']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['exampted_prop']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Total number of properties from which property tax was demanded</td>
                                        <td><?php echo $report_list['2018-2019']['prop_tax_demanded']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['prop_tax_demanded']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['prop_tax_demanded']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['prop_tax_demanded']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['prop_tax_demanded']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Total number of properties from which property tax was collected</td>
                                        <td><?php echo $report_list['2018-2019']['prop_tax_colleted']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['prop_tax_colleted']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['prop_tax_colleted']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['prop_tax_colleted']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['prop_tax_colleted']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>IV</b></td>
                                        <td><b>Property Tax Demand and Collections Details by Property Type (value of demand and collections to include cess and other taxes AND exclude user charges if any</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>IV.1</b></td>
                                        <td><b>Residential properties</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Value of property tax demanded (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['residential_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['residential_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['residential_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['residential_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['residential_demand']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Number of properties from which property tax was demanded</td>
                                        <td><?php echo $report_list['2018-2019']['residential_property_demand']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['residential_property_demand']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['residential_property_demand']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['residential_property_demand']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['residential_property_demand']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Value of property tax collected from properties (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['residential_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['residential_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['residential_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['residential_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['residential_collection']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Number of properties from which property tax was collected</td>
                                        <td><?php echo $report_list['2018-2019']['residential_property_collection']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['residential_property_collection']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['residential_property_collection']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['residential_property_collection']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['residential_property_collection']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>IV.2</b></td>
                                        <td><b>Commercial Properties</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Value of property tax demanded (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['commercial_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['commercial_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['commercial_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['commercial_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['commercial_demand']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Number of properties from which property tax was demanded</td>
                                        <td><?php echo $report_list['2018-2019']['commercial_property_demand']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['commercial_property_demand']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['commercial_property_demand']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['commercial_property_demand']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['commercial_property_demand']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Value of property tax collected from properties (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['commercial_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['commercial_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['commercial_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['commercial_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['commercial_collection']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Number of properties from which property tax was collected</td>
                                        <td><?php echo $report_list['2018-2019']['commercial_property_collection']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['commercial_property_collection']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['commercial_property_collection']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['commercial_property_collection']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['commercial_property_collection']; ?></td>
                                    </tr>

                                    <tr>
                                        <td><b>IV.3</b></td>
                                        <td><b>Industrial Properties</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Value of property tax demanded (INR lakh)</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Number of properties from which property tax was demanded</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Value of property tax collected from properties (INR lakh)</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Number of properties from which property tax was collected</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>

                                    <tr>
                                        <td><b>IV.4</b></td>
                                        <td><b>Government Properties</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Value of property tax demanded (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['gov_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['gov_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['gov_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['gov_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['gov_demand']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Number of properties from which property tax was demanded</td>
                                        <td><?php echo $report_list['2018-2019']['gov_property_demand']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['gov_property_demand']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['gov_property_demand']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['gov_property_demand']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['gov_property_demand']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Value of property tax collected from properties (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['gov_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['gov_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['gov_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['gov_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['gov_collection']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Number of properties from which property tax was collected</td>
                                        <td><?php echo $report_list['2018-2019']['gov_property_collection']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['gov_property_collection']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['gov_property_collection']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['gov_property_collection']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['gov_property_collection']; ?></td>
                                    </tr>

                                    <tr>
                                        <td><b>IV.5</b></td>
                                        <td><b>Institutional Properties</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Value of property tax demanded (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['institutional_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['institutional_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['institutional_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['institutional_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['institutional_demand']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Number of properties from which property tax was demanded</td>
                                        <td><?php echo $report_list['2018-2019']['institutional_property_demand']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['institutional_property_demand']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['institutional_property_demand']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['institutional_property_demand']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['institutional_property_demand']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Value of property tax collected from properties (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['institutional_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['institutional_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['institutional_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['institutional_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['institutional_collection']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Number of properties from which property tax was collected</td>
                                        <td><?php echo $report_list['2018-2019']['institutional_property_collection']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['institutional_property_collection']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['institutional_property_collection']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['institutional_property_collection']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['institutional_property_collection']; ?></td>
                                    </tr>

                                    <tr>
                                        <td><b>IV.6</b></td>
                                        <td><b>Other Properties (TRUST)</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Value of property tax demanded (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['trust_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['trust_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['trust_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['trust_demand']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['trust_demand']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Number of properties from which property tax was demanded</td>
                                        <td><?php echo $report_list['2018-2019']['trust_property_demand']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['trust_property_demand']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['trust_property_demand']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['trust_property_demand']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['trust_property_demand']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Value of property tax collected from properties (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['trust_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['trust_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['trust_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['trust_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['trust_collection']/100000); ?></td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Number of properties from which property tax was collected</td>
                                        <td><?php echo $report_list['2018-2019']['trust_property_collection']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['trust_property_collection']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['trust_property_collection']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['trust_property_collection']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['trust_property_collection']; ?></td>
                                    </tr>

                                    <tr>
                                        <td><b>V</b></td>
                                        <td><b>Property Tax Collection Details by Mode of  Payment (Value of collections include cess and other taxes AND exclude user charges if any)</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Number of properties that paid online (through website or mobile application)</td>
                                        <td><?php echo $report_list['2018-2019']['online_prop_count']; ?></td>
                                        <td><?php echo $report_list['2019-2020']['online_prop_count']; ?></td>
                                        <td><?php echo $report_list['2020-2021']['online_prop_count']; ?></td>
                                        <td><?php echo $report_list['2021-2022']['online_prop_count']; ?></td>
                                        <td><?php echo $report_list['2022-2023']['online_prop_count']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Value of property tax collected from properties (INR lakh)</td>
                                        <td><?php echo round($report_list['2018-2019']['online_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2019-2020']['online_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2020-2021']['online_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2021-2022']['online_collection']/100000); ?></td>
                                        <td><?php echo round($report_list['2022-2023']['online_collection']/100000); ?></td>
                                    </tr>
                               
								</tbody>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

