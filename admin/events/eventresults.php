<?php require_once(__DIR__ . '/../../config.php');
	include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');
	include_once('../layout/events_list_style.php');
  
   require_once(LIB_PATH . '/functions.class.php');

   $fcObj			= new DataFunctions();
	
   $tbEvents		= TB_EVENTS;
   $tbEventReg		= TB_EVENT_REG;
	
   $curEvents		= $fcObj->getResultedEvents( $tbEvents, anu );
   
   $noOfCEvents		= sizeof( $curEvents );
?>
<style type="text/css">
	.legacy-admin-page {
		--la-primary: #173d69;
		--la-primary-deep: #13345a;
		--la-accent: #f0b323;
		--la-accent-deep: #d79a12;
		--la-surface: #eef4fa;
		--la-border: #d9e3ef;
		--la-muted: #6b819c;
		background: linear-gradient(180deg, #f3f7fb 0%, var(--la-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}
	.legacy-admin-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--la-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--la-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}
	.legacy-admin-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--la-accent), var(--la-accent-deep));
	}
	.legacy-admin-title {
		margin: 0;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--la-primary-deep);
	}
	.legacy-admin-subtitle {
		margin: 8px 0 0;
		font-size: 15px;
		color: var(--la-muted);
	}
	.legacy-admin-page .currentevents,
	.legacy-admin-page .comteeMem,
	.legacy-admin-page #eventDetails {
		border: 1px solid var(--la-border);
		border-radius: 18px;
		background: #ffffff;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 18px;
	}
	.legacy-admin-page .eventDetHeader,
	.legacy-admin-page .eventDet,
	.legacy-admin-page .usersDetHeader,
	.legacy-admin-page .comteeMemRow {
		border: 1px solid #dbe6f3;
		border-radius: 14px;
		background: #fbfdff;
		padding: 12px 14px;
		margin-bottom: 10px;
	}
	.legacy-admin-page .eventDetHeader {
		background: #f7faff;
		color: var(--la-primary);
		font-weight: 800;
	}
	.legacy-admin-page .button {
		border: 0;
		border-radius: 12px;
		padding: 11px 20px;
		background: linear-gradient(135deg, var(--la-primary-deep), var(--la-primary));
		color: #ffffff;
		font-weight: 700;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
	}
	.legacy-admin-page a { color: var(--la-primary); font-weight: 700; }
	.legacy-admin-page a:hover { color: var(--la-primary-deep); }
</style>

<style type="text/css">
	.legacy-admin-page {
		--la-primary: #173d69;
		--la-primary-deep: #13345a;
		--la-accent: #f0b323;
		--la-accent-deep: #d79a12;
		--la-surface: #eef4fa;
		--la-border: #d9e3ef;
		--la-muted: #6b819c;
		background: linear-gradient(180deg, #f3f7fb 0%, var(--la-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}
	.legacy-admin-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--la-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--la-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}
	.legacy-admin-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--la-accent), var(--la-accent-deep));
	}
	.legacy-admin-title {
		margin: 0;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--la-primary-deep);
	}
	.legacy-admin-subtitle {
		margin: 8px 0 0;
		font-size: 15px;
		color: var(--la-muted);
	}
	.legacy-admin-page .currentevents,
	.legacy-admin-page .comteeMem,
	.legacy-admin-page #eventDetails {
		border: 1px solid var(--la-border);
		border-radius: 18px;
		background: #ffffff;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 18px;
	}
	.legacy-admin-page .eventDetHeader,
	.legacy-admin-page .eventDet,
	.legacy-admin-page .usersDetHeader,
	.legacy-admin-page .comteeMemRow {
		border: 1px solid #dbe6f3;
		border-radius: 14px;
		background: #fbfdff;
		padding: 12px 14px;
		margin-bottom: 10px;
	}
	.legacy-admin-page .eventDetHeader {
		background: #f7faff;
		color: var(--la-primary);
		font-weight: 800;
	}
	.legacy-admin-page .button {
		border: 0;
		border-radius: 12px;
		padding: 11px 20px;
		background: linear-gradient(135deg, var(--la-primary-deep), var(--la-primary));
		color: #ffffff;
		font-weight: 700;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
	}
	.legacy-admin-page a { color: var(--la-primary); font-weight: 700; }
	.legacy-admin-page a:hover { color: var(--la-primary-deep); }
</style>
			<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter"></span>
						<p></p>
					</div>
					<div id='content_left' class='content_left'></div>
					<div id='content_right' class='content_right'>
						<div class="legacy-admin-page">
							<div class="legacy-admin-hero">
								<h3 class="legacy-admin-title">Event Results</h3>
								<p class="legacy-admin-subtitle">Choose resulted events and continue into the branded result-announcement flow.</p>
							</div>
						<div id="currentevents" class="currentevents">
							<div id="eventDetails">
								<div class="eventDetHeader">
									<div class="sno">
										S NO
									</div>
									<div class="eventName">
										Event Name
									</div>
									<div class="eventDate">
										Event Date
									</div>
									<div class="eventRegisDates">
										Registration Dates
									</div>		
								</div>
								
								<?php
									for( $i = 0; $i < $noOfCEvents; $i++){
										
									?>
										<div class="eventDet">
											<div class="sno">
												<?php echo $i+1; ?>
											</div>
											<div class="eventName">
												<a href="eventresult.php?event=<?php echo $curEvents[$i]['id'];?>"><?php echo $curEvents[$i]['event_name']; ?></a>
											</div>
											<div class="eventDate">
												<?php echo date("d-m-Y", strtotime($curEvents[$i]['event_date'])); ?>
											</div>
											<div class="eventRegisDates">
												<?php echo date("d-m-Y", strtotime($curEvents[$i]['reg_frm_date'])).' to '.date("d-m-Y", strtotime($curEvents[$i]['reg_to_date'])); ?>
											</div>
										</div>
									<?php
									}
								?>
							</div>
						</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=event_results" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>
