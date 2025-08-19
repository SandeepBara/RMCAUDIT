<?= $this->include('layout_vertical/header'); ?>
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
							<div class="row">
								<!-- Property DCB Current Finacial Year -->
								<div class="col-md-4 shadow-lg">
									<div class="panel panel-info  media middle pad-all p-3" style="background-color: #03A9F4;box-shadow:5px 5px 5px gray !important;color:white">
										<div class="media-left">
											<div class="pad-hor">
												<i class="fa fa-inr" style="font-size: 35px;"></i>
											</div>
										</div>
										<div class="media-body">
											<p class="text-2x mar-no text-semibold"><?= in_num_format(round($property_dcb["total_demand"], 2)); ?></p>
											<p class="mar-no">Total Demand</p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="panel panel-mint media middle pad-all"  style="background-color: #26A69A;box-shadow:5px 5px 5px gray !important;color:white">
										<div class="media-left">
											<div class="pad-hor">
												<i class="fa fa-inr" style="font-size: 35px;"></i>
											</div>
										</div>
										<div class="media-body">
											<p class="text-2x mar-no text-semibold"><?= in_num_format(round($property_dcb["total_collection"], 2)); ?></p>
											<p class="mar-no">Total Collection</p>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="panel panel-warning media middle pad-all"  style="background-color: #FFB300;box-shadow:5px 5px 5px gray !important;color:white">
										<div class="media-left">
											<div class="pad-hor">
												<i class="fa fa-inr" style="font-size: 35px;"></i>
											</div>
										</div>
										<div class="media-body">
											<p class="text-2x mar-no text-semibold"><?= in_num_format(round($property_dcb["total_due"], 2)); ?></p>
											<p class="mar-no">Total Due Demand</p>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12" style="padding: 10px;">
									<div class="panel panel-bordered-dark bg-white" style="padding: 10px;">
										<!-- Panel Property 2022 -->
										<div class="panel-heading" style="background-color: #25476A;margin-bottom:10px">
											<h3 class="panel-title" style="color:white">Current Financial Year <?=getFY();?></h3>
										</div>
										<div class="panel-body pad-no">
											<div class="row">
												<!-- Property Total SAF 2022 -->
												<div class="col-sm-2 col-md-2">
													<div class="panel pos-rel" style="background-color: #f7f7f7;box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px">
														<div class="media" style="padding: 30px 0px 30px 0px;">
															<div class="media-body text-center">
																<a href="<?php echo base_url('MiniDashboard/wardWiseSaf/'.getFY().'/All');?>" class="box-inline">
																	<!-- <button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= array_sum(array_column($property_pending_at_level_2022["current_2022_total_saf"], 'total_count')); ?></button> -->

																	<span class="text-2x text-semibold text-main"><?= array_sum(array_column($property_pending_at_level_2022["current_2022_total_saf"], 'total_count')); ?></span>
																	<br />
																	<span class="text-lg text-semibold">Total SAF</span>

																</a>
															</div>
														</div>
													</div>
												</div>
												<?php foreach ($property_pending_at_level_2022["current_2022_total_saf"] as $current_2022_total_saf) { ?>

													<?php if ($current_2022_total_saf["assessment_type"] != 'Mutation with Reassessment') { ?>
														<div class="col-sm-2 col-md-2">
															<div class="panel pos-rel" style="background-color: #f7f7f7;box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px;">
																<div class="media" style="padding: 30px 0px 30px 0px;">
																	<div class="media-body text-center">
																		<a href="<?php echo base_url('MiniDashboard/wardWiseSaf/'.getFY().'/'. $current_2022_total_saf["assessment_type"]);?>" class="box-inline">


																			<!-- <button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $current_2022_total_saf["total_count"]; ?></button> -->
																			<span class="text-2x text-semibold text-main"> <?= $current_2022_total_saf["total_count"]; ?></span>
																			<br />
																			<span class="text-lg text-semibold"><?= $current_2022_total_saf["assessment_type"]; ?></span>
																		</a>
																	</div>
																</div>
															</div>
														</div>
													<?php } ?>
													<?php if ($current_2022_total_saf["assessment_type"] == 'Mutation with Reassessment') { ?>
														<div class="col-sm-4 col-md-4">
															<div class="panel pos-rel" style="background-color: #f7f7f7;box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px">
																<div class="media" style="padding: 30px 0px 30px 0px;">
																	<div class="media-body text-center">
																		<a href="<?php echo base_url('MiniDashboard/wardWiseSaf/'.getFY().'/'. $current_2022_total_saf["assessment_type"]);?>" class="box-inline">


																			<!-- <button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $current_2022_total_saf["total_count"]; ?></button> -->
																			<span class="text-2x text-semibold text-main"> <?= $current_2022_total_saf["total_count"]; ?></span>
																			<br />
																			<span class="text-lg text-semibold"><?= $current_2022_total_saf["assessment_type"]; ?></span>
																		</a>
																	</div>
																</div>
															</div>
														</div>
													<?php } ?>
												<?php } ?>
											</div>
											<div class="panel-heading" style="background-color: #25476A;margin-bottom:10px;margin-top:20px">
												<h3 class="panel-title" style="color:white">PROPERTY PENDING REPORT</h3>
											</div>
											<div class="row">
												<!-- Property Pending Report 2022 -->
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="" style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/'.getFY().'/'. md5(6));?>" class="box-inline"><img style="border:2px solid white;padding:5px" alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/Dealing%20Assistant.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/'.getFY().'/'. md5(6));?>" class="box-inline">
																		<span class="text-lg text-semibold text-main">Dealing Assistant</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $property_pending_at_level_2022["no_of_pending_by_dealing_assistant"]; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/'.getFY().'/'. md5(7));?>" class="box-inline"><img style="border:2px solid white;padding:5px" alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/ULB Tax Collector.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/'.getFY().'/'. md5(7));?>" class="box-inline">
																		<span class="text-lg text-semibold text-main">Tax Collector</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $property_pending_at_level_2022["no_of_pending_by_ulb_tc"]; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/'.getFY().'/'. md5(9));?>" class="box-inline"><img style="border:2px solid white;padding:5px" alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/Property Section Incharge.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/'.getFY().'/'. md5(9));?>" class="box-inline">
																		<span class="text-lg text-semibold text-main">Section Head</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $property_pending_at_level_2022["no_of_pending_by_section_incharge"]; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/'.getFY().'/'. md5(10));?>" class="box-inline"><img style="border:2px solid white;padding:5px" alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/Executive Officer.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/'.getFY().'/'. md5(10));?>" class="box-inline">
																		<span class="text-lg text-semibold text-main">Executive Officer</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $property_pending_at_level_2022["no_of_pending_by_executive_officer"]; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-bordered-dark" style="padding: 10px;background-color:#f7f7f7;margin-top:40px">
										<!-- Panel Property Before 2022 -->
										
										<div class="panel-heading" style="background-color: #25476A;margin-bottom:10px;margin-top:20px">
												<h3 class="panel-title" style="color:white">Upto Last Financial Year 2021-2022</h3>
											</div>
										<div class="panel-body pad-no">
											<div class="row">
												<!-- Property Total SAF 2022 -->
												<div class="col-sm-3 col-md-3">
													<div class="panel pos-rel" style="background-color: f7f7f7;">
														<div class="media"  style="background-color: #f7f7f7;box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px" >
															<div class="media-body text-center" style="padding: 30px 0px 30px 0px;">
																<a href="<?php echo base_url('MiniDashboard/wardWiseSaf/2021-2022/All');?>" class="box-inline">
																	<!-- <span class="text-2x text-semibold text-main">Total SAF</span>
																	<br />
																	<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= array_sum(array_column($property_pending_at_level_before_2022["before2022_total_saf"], 'total_count')); ?></button> -->

																	<span class="text-2x text-semibold text-main"><?= array_sum(array_column($property_pending_at_level_before_2022["before2022_total_saf"], 'total_count')); ?></span>
																	<br />
																	<span class="text-lg text-semibold">Total SAF</span>
																</a>
															</div>
														</div>
													</div>
												</div>
												<?php foreach ($property_pending_at_level_before_2022["before2022_total_saf"] as $before2022_total_saf) { ?>
													<div class="col-sm-3 col-md-3">
														<div class="panel pos-rel bg-white">
															<div class="media pad-ver"  style="background-color: #f7f7f7;box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px;padding: 30px 0px 30px 0px;" >
																<div class="media-body text-center" >
																	<a href="<?php echo base_url('MiniDashboard/wardWiseSaf/2021-2022/'. $before2022_total_saf["assessment_type"]);?>" class="box-inline">
																		<!-- <span class="text-2x text-semibold text-main"><?= $before2022_total_saf["assessment_type"]; ?></span>
																		<br />
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $before2022_total_saf["total_count"]; ?></button> -->

																		<span class="text-2x text-semibold text-main"><?= $before2022_total_saf["total_count"]; ?></span>
																	<br />
																	<span class="text-lg text-semibold"><?= $before2022_total_saf["assessment_type"]; ?></span>
																	</a>
																</div>
															</div>
														</div>
													</div>
												<?php } ?>
											</div>
											<div class="panel-heading" style="background-color: #25476A;margin-bottom:10px;margin-top:20px">
												<h3 class="panel-title" style="color:white">PROPERTY PENDING REPORT</h3>
											</div>
											
											<div class="row">
												<!-- Property Pending Report 2022 -->
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all" style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/2021-2022/'. md5(6));?>" class="box-inline"><img style="border:2px solid white;padding:5px" alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/Dealing%20Assistant.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/2021-2022/'. md5(6));?>" class="box-inline">
																		<span class="text-lg text-semibold text-main">Dealing Assistant</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $property_pending_at_level_before_2022["no_of_pending_by_dealing_assistant"]; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all" style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/2021-2022/'. md5(7));?>" class="box-inline"><img style="border:2px solid white;padding:5px" alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/ULB Tax Collector.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/2021-2022/'. md5(7));?>" class="box-inline">
																		<span class="text-lg text-semibold text-main">Tax Collector</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $property_pending_at_level_before_2022["no_of_pending_by_ulb_tc"]; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all" style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/2021-2022/'. md5(9));?>" class="box-inline"><img style="border:2px solid white;padding:5px" alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/Property Section Incharge.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/2021-2022/'. md5(9));?>" class="box-inline">
																		<span class="text-lg text-semibold text-main">Section Head</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $property_pending_at_level_before_2022["no_of_pending_by_section_incharge"]; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all" style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/2021-2022/'. md5(10));?>" class="box-inline"><img style="border:2px solid white;padding:5px" alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/Executive Officer.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="<?php echo base_url('MiniDashboard/level_wardwise/2021-2022/'. md5(10));?>" class="box-inline">
																		<span class="text-lg text-semibold text-main">Executive Officer</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $property_pending_at_level_before_2022["no_of_pending_by_executive_officer"]; ?></button>
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
									<div class="panel panel-bordered-dark bg-light" style="box-shadow: 10px 10px 10px gray !important;padding:10px">
										<!-- Panel Property 2022 -->
									
										<div class="panel-heading" style="background-color: #25476A;margin-bottom:10px;margin-top:20px">
												<h3 class="panel-title" style="color:white">Current Financial Year <?=getFY();?></h3>
											</div>
										<div class="panel-body pad-no">
											<div class="row pad-top">
												<div class="col-sm-12 col-md-12">
													<div class="panel pos-rel">
														<div class="media pad-ver">
															<div class="media-body text-left">
																<a href="#" class="box-inline">
																	<span class="text-2x text-semibold text-main" style="margin-left: 10px;">Total Trade Applications (<?=getFY();?>)</span>
																	<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $total_application_22_23['count']; ?></button>
																</a>
															</div>
														</div>
													</div>
												</div>
											</div>
											<h5> TRADE PENDING REPORT </h5>
											<div class="row">
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all" style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/Dealing%20Assistant.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Dealing Assistant</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $trade_pending_at_level_22_23["da_total_pending"]; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all" style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/ULB Tax Collector.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Tax Daroga</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $trade_pending_at_level_22_23["td_total_pending"]; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all" style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/Property Section Incharge.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Section Head</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $trade_pending_at_level_22_23["sh_total_pending"]; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel" >
														<div class="pad-all" style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/Executive Officer.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Executive Officer</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $trade_pending_at_level_22_23["eo_total_pending"]; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-bordered-dark bg-light" style="box-shadow: 10px 10px 10px gray !important;padding:10px">
										<!-- Panel Property Before 2022 -->
										<div class="panel-heading" style="background-color: #25476A;margin-bottom:10px;margin-top:20px">
												<h3 class="panel-title" style="color:white">Previous Financial Year <?=$privFyear?></h3>
											</div>
										<div class="panel-body pad-no">
											<div class="row pad-top">
												<div class="col-sm-12 col-md-12">
													<div class="panel pos-rel">
														<div class="media pad-ver">
															<div class="media-body text-left">
																<a href="#" class="box-inline">
																	<span class="text-2x text-semibold text-main">Total Trade Applications (<?=$privFyear?>) </span>
																	<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $total_application_21_22['count']; ?></button>
																</a>
															</div>
														</div>
													</div>
												</div>
											</div>
											<h5> TRADE PENDING REPORT </h5>
											<div class="row">
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all" style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/Dealing%20Assistant.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Dealing Assistant</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $trade_pending_at_level_21_22['da_total_pending']; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all" style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/ULB Tax Collector.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Tax Daroga</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $trade_pending_at_level_21_22['td_total_pending']; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all" style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/Property Section Incharge.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Section Head</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $trade_pending_at_level_21_22['sh_total_pending']; ?></button>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-4 col-md-3">
													<div class="panel pos-rel">
														<div class="pad-all" style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
															<div class="media pad-ver">
																<div class="media-left">
																	<a href="#" class="box-inline"><img alt="Profile Picture" class="img-md img-circle" src="<?= base_url(); ?>/public/assets/img/Executive Officer.png"></a>
																</div>
																<div class="media-body pad-top">
																	<a href="#" class="box-inline">
																		<span class="text-lg text-semibold text-main">Executive Officer</span>
																		<button type="button" class="btn btn-lg btn-default"><i class="fa fa-bullhorn"></i> <?= $trade_pending_at_level_21_22['eo_total_pending']; ?></button>
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
<?= $this->include('layout_vertical/footer'); ?>