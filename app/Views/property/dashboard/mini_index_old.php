
<?= $this->include('layout_vertical/header');?>
<!--Page content-->
<div id="content-container">
	<div id="page-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="tab-base">
					<!--Nav Tabs-->
					<ul class="nav nav-tabs tabs-right">
						<li class="active">
							<a data-toggle="tab" href="#demo-lft-tab-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Property &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
						</li>
						<li>
							<a data-toggle="tab" href="#demo-lft-tab-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Trade &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
						</li>
					</ul>

					<!--Tabs Content-->
					<div class="tab-content">
						<div id="demo-lft-tab-1" class="tab-pane fade active in">
							<div class="row"> <!-- Property DCB Current Finacial Year -->
								<div class="col-md-4">
									<div class="panel panel-info panel-colorful media middle pad-all">
										<div class="media-left">
											<div class="pad-hor">
												<i class="fa fa-inr" style="font-size: 35px;"></i>
											</div>
										</div>
										<div class="media-body">
											<p class="text-2x mar-no text-semibold"><?=in_num_format($property_dcb["total_demand"]);?></p>
											<p class="mar-no">Total Demand</p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="panel panel-mint panel-colorful media middle pad-all">
										<div class="media-left">
											<div class="pad-hor">
											<i class="fa fa-inr" style="font-size: 35px;"></i>
											</div>
										</div>
										<div class="media-body">
											<p class="text-2x mar-no text-semibold"><?=in_num_format($property_dcb["total_collection"]);?></p>
											<p class="mar-no">Total Collection</p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="panel panel-warning panel-colorful media middle pad-all">
										<div class="media-left">
											<div class="pad-hor">
											<i class="fa fa-inr" style="font-size: 35px;"></i>
											</div>
										</div>
										<div class="media-body">
											<p class="text-2x mar-no text-semibold"><?=in_num_format($property_dcb["total_due"]);?></p>
											<p class="mar-no">Total Due Demand</p>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="panel panel-bordered-dark bg-gray-dark pad-no"> <!-- Panel Property 2022 -->
										<div class="panel-heading">
											<h3 class="panel-title">Current Financial Year 2022-2023</h3>
										</div>
										<div class="panel-body pad-no">
											<div class="row"> <!-- Property Total SAF 2022 -->
												<div class="col-sm-3 col-md-3">
													<div class="panel pos-rel">
														<div class="media pad-ver">
															<div class="media-body text-center">
																<a href="#" class="box-inline">
																	<span class="text-2x text-semibold text-main">Total SAF</span>
																	<br />
																	<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=array_sum(array_column($property_pending_at_level_2022["current_2022_total_saf"],'total_count'));?></button>
																</a>
															</div>
														</div>
													</div>
												</div>
												<?php foreach ($property_pending_at_level_2022["current_2022_total_saf"] AS $current_2022_total_saf) { ?>
												<div class="col-sm-3 col-md-3">
													<div class="panel pos-rel bg-gray">
														<div class="media pad-ver">
															<div class="media-body text-center">
																<a href="#" class="box-inline">
																	<span class="text-2x text-semibold text-main"><?=$current_2022_total_saf["assessment_type"];?></span>
																	<br />
																	<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$current_2022_total_saf["total_count"];?></button>
																</a>
															</div>
														</div>
													</div>
												</div>
												<?php } ?>
											</div>
											<h5 style="margin-bottom: 0px; margin-top: 0px;"> PROPERTY PENDING REPORT </h5> 
											<div class="row"> <!-- Property Pending Report 2022 -->
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/Dealing%20Assistant.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Dealing Assistant</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$property_pending_at_level_2022["no_of_pending_by_dealing_assistant"];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/ULB Tax Collector.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Tax Collector</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$property_pending_at_level_2022["no_of_pending_by_ulb_tc"];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/Property Section Incharge.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Section Head</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$property_pending_at_level_2022["no_of_pending_by_section_incharge"];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/Executive Officer.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Executive Officer</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$property_pending_at_level_2022["no_of_pending_by_executive_officer"];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-bordered-dark bg-gray-dark pad-no"> <!-- Panel Property Before 2022 -->
										<div class="panel-heading">
											<h3 class="panel-title">Upto Last Financial Year 2021-2022</h3>
										</div>
										<div class="panel-body pad-no">
											<div class="row"> <!-- Property Total SAF 2022 -->
											<div class="col-sm-3 col-md-3">
													<div class="panel pos-rel">
														<div class="media pad-ver">
															<div class="media-body text-center">
																<a href="#" class="box-inline">
																	<span class="text-2x text-semibold text-main">Total SAF</span>
																	<br />
																	<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=array_sum(array_column($property_pending_at_level_before_2022["before2022_total_saf"],'total_count'));?></button>
																</a>
															</div>
														</div>
													</div>
												</div>
												<?php foreach ($property_pending_at_level_before_2022["before2022_total_saf"] AS $before2022_total_saf) { ?>
												<div class="col-sm-3 col-md-3">
													<div class="panel pos-rel bg-gray">
														<div class="media pad-ver">
															<div class="media-body text-center">
																<a href="#" class="box-inline">
																	<span class="text-2x text-semibold text-main"><?=$before2022_total_saf["assessment_type"];?></span>
																	<br />
																	<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$before2022_total_saf["total_count"];?></button>
																</a>
															</div>
														</div>
													</div>
												</div>
												<?php } ?>
											</div>
											<h5 style="margin-bottom: 0px; margin-top: 0px;"> PROPERTY PENDING REPORT </h5> 
											<div class="row"> <!-- Property Pending Report 2022 -->
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/Dealing%20Assistant.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Dealing Assistant</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$property_pending_at_level_before_2022["no_of_pending_by_dealing_assistant"];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/ULB Tax Collector.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Tax Collector</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$property_pending_at_level_before_2022["no_of_pending_by_ulb_tc"];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/Property Section Incharge.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Section Head</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$property_pending_at_level_before_2022["no_of_pending_by_section_incharge"];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/Executive Officer.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Executive Officer</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$property_pending_at_level_before_2022["no_of_pending_by_executive_officer"];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<!-- End Property -->
						<!-- Start Trade  -->
						<div id="demo-lft-tab-2" class="tab-pane fade">
							<div class="row">
								<div class="col-md-12">
									<div class="panel panel-bordered-dark bg-gray-dark pad-no"> <!-- Panel Property 2022 -->
										<div class="panel-heading">
											<h3 class="panel-title">Current Financial Year 2022-2023</h3>
										</div>
										<div class="panel-body pad-no">
											<div class="row pad-top">
												<div class="col-sm-4 col-md-3"></div>
												<div class="col-sm-4 col-md-6">
													<div class="panel pos-rel">
														<div class="media pad-ver">
															<div class="media-body text-center">
																<a href="#" class="box-inline">
																	<span class="text-2x text-semibold text-main">Total Trade Applications (2022-23)</span>
																	<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$total_application_22_23['count'];?></button>
																</a>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3"></div>
											</div>
											<h5> TRADE PENDING REPORT </h5>
											<div class="row">
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/Dealing%20Assistant.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Dealing Assistant</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$trade_pending_at_level_22_23["da_total_pending"];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/ULB Tax Collector.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Tax Daroga</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$trade_pending_at_level_22_23["td_total_pending"];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/Property Section Incharge.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Section Head</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$trade_pending_at_level_22_23["sh_total_pending"];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/Executive Officer.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Executive Officer</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$trade_pending_at_level_22_23["eo_total_pending"];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-bordered-dark bg-gray-dark pad-no"> <!-- Panel Property Before 2022 -->
										<div class="panel-heading">
											<h3 class="panel-title">Previous Financial Year 2021-2022</h3>
										</div>
										<div class="panel-body pad-no">
											<div class="row pad-top">
												<div class="col-sm-4 col-md-3"></div>
												<div class="col-sm-4 col-md-6">
													<div class="panel pos-rel">
														<div class="media pad-ver">
															<div class="media-body text-center">
																<a href="#" class="box-inline">
																	<span class="text-2x text-semibold text-main">Total Trade Applications (2021-22) </span>
																	<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$total_application_21_22['count'];?></button>
																</a>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3"></div>
											</div>
											<h5> TRADE PENDING REPORT </h5>
											<div class="row">
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/Dealing%20Assistant.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Dealing Assistant</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$trade_pending_at_level_21_22['da_total_pending'];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/ULB Tax Collector.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Tax Daroga</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$trade_pending_at_level_21_22['td_total_pending'];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/Property Section Incharge.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Section Head</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$trade_pending_at_level_21_22['sh_total_pending'];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?=base_url();?>/public/assets/img/Executive Officer.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Executive Officer</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?=$trade_pending_at_level_21_22['eo_total_pending'];?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->include('layout_vertical/footer');?>