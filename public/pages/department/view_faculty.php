<?php 
	require_once(__DIR__ . '/../../../config.php');

    include_once(INCLUDES_PATH . '/header.php');
    require_once(LIB_PATH . '/functions.class.php');

   $fcObj			= new DataFunctions();
	
	$staffId		= isset($_GET['faculty']) ? (int)$_GET['faculty'] : 0;
	
   $tbStaff		 	= TB_STAFF;
	
$staffDetails	= $fcObj->getStaffDetailsById( $tbStaff , $staffId );
$staff         = !empty($staffDetails) ? $staffDetails[0] : null;

$fullName = $staff ? trim(((string)$staff['first_name']) . ' ' . ((string)$staff['last_name'])) : '';
$qualification = $staff ? str_replace('\,', ',', (string)$staff['qualification']) : '';
$designation = $staff ? (string)$staff['designation'] : '';
$email = $staff ? (string)$staff['e_mail'] : '';
$industryExp = $staff ? (string)$staff['industry_exp'] : '';
$teachingExp = $staff ? (string)$staff['teach_exp'] : '';
$research = $staff ? (string)$staff['research'] : '';
$pubNational = $staff ? (string)$staff['publ_national'] : '';
$pubInternational = $staff ? (string)$staff['publ_international'] : '';
$confNational = $staff ? (string)$staff['conf_national'] : '';
$confInternational = $staff ? (string)$staff['conf_international'] : '';
$imageName = $staff ? trim((string)$staff['image']) : '';
$isTeaching = $staff && ((int)$staff['staff_categ_id'] === (int)TEACHING);
?>
<style type="text/css">
	.staff-profile-page {
		max-width: 1080px;
		margin: 34px auto;
		padding: 0 14px;
	}

	.staff-hero {
		border: 1px solid #cfdced;
		border-radius: 18px;
		padding: 20px 24px;
		background: linear-gradient(140deg, rgba(37, 99, 235, 0.06), rgba(15, 118, 110, 0.04)), #f8fbff;
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 18px;
	}

	.staff-hero h2 {
		margin: 0;
		font-size: 38px;
		line-height: 1.05;
		font-weight: 800;
		color: #0f172a;
		letter-spacing: -0.8px;
	}

	.staff-hero p {
		margin: 8px 0 0;
		font-size: 17px;
		color: #556a84;
	}

	.staff-card {
		border: 1px solid #d7dde6;
		border-radius: 18px;
		background: #ffffff;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 20px;
	}

	.staff-summary {
		display: grid;
		grid-template-columns: 130px minmax(0, 1fr);
		gap: 16px;
		align-items: center;
		padding-bottom: 16px;
		margin-bottom: 16px;
		border-bottom: 1px solid #e2e8f0;
	}

	.staff-photo {
		width: 120px;
		height: 120px;
		border-radius: 16px;
		object-fit: cover;
		border: 1px solid #d6e2f0;
		background: #f1f5f9;
	}

	.staff-name {
		margin: 0;
		font-size: 30px;
		line-height: 1.1;
		font-weight: 800;
		color: #0f172a;
	}

	.staff-role {
		margin: 6px 0 0;
		color: #436384;
		font-weight: 600;
		font-size: 16px;
	}

	.detail-group {
		margin-bottom: 14px;
	}

	.detail-group:last-child {
		margin-bottom: 0;
	}

	.group-title {
		margin: 0 0 10px;
		font-size: 17px;
		font-weight: 800;
		color: #17406d;
	}

	.detail-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(220px, 1fr));
		gap: 10px 14px;
	}

	.detail-item {
		border: 1px solid #dce7f3;
		border-radius: 12px;
		background: #f8fbff;
		padding: 10px 12px;
	}

	.detail-label {
		display: block;
		font-size: 12px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.4px;
		color: #5f7690;
		margin-bottom: 3px;
	}

	.detail-value {
		font-size: 18px;
		line-height: 1.35;
		font-weight: 600;
		color: #10263f;
		overflow-wrap: anywhere;
	}

	.empty-state {
		border: 1px dashed #cbd5e1;
		border-radius: 14px;
		background: #f8fafc;
		color: #475569;
		font-size: 16px;
		font-weight: 600;
		padding: 18px;
	}

	@media (max-width: 820px) {
		.staff-hero h2 {
			font-size: 30px;
		}

		.staff-summary {
			grid-template-columns: 1fr;
		}

		.detail-grid {
			grid-template-columns: 1fr;
		}
	}
</style>

<div class="staff-profile-page">
	<div class="staff-hero">
		<div class="d-flex justify-content-start mb-3">
			<a href="<?php echo BASE_URL; ?>/public/pages/department/department.php" class="btn btn-sm btn-outline-secondary">
				<i class="bi bi-arrow-left"></i> Back to Faculty
			</a>
		</div>
		<h2>IT Department</h2>
		<p>Faculty profile details</p>
	</div>

	<?php if ($staff === null) { ?>
		<div class="empty-state">Faculty details are not available for the selected profile.</div>
	<?php } else { ?>
		<div class="staff-card">
			<div class="staff-summary">
				<div>
					<img
						class="staff-photo"
						src="<?php echo BASE_URL; ?>/public/assets/images/staff/<?php echo rawurlencode($imageName); ?>"
						alt="<?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>"
						title="<?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>"
					>
				</div>
				<div>
					<h3 class="staff-name"><?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?></h3>
					<p class="staff-role"><?php echo htmlspecialchars($designation, ENT_QUOTES, 'UTF-8'); ?></p>
				</div>
			</div>

			<div class="detail-group">
				<h4 class="group-title">Basic Info</h4>
				<div class="detail-grid">
					<div class="detail-item">
						<span class="detail-label">Qualification</span>
						<div class="detail-value"><?php echo htmlspecialchars($qualification, ENT_QUOTES, 'UTF-8'); ?></div>
					</div>
					<div class="detail-item">
						<span class="detail-label">Email</span>
						<div class="detail-value"><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></div>
					</div>
				</div>
			</div>

			<?php if ($isTeaching) { ?>
				<div class="detail-group">
					<h4 class="group-title">Experience</h4>
					<div class="detail-grid">
						<div class="detail-item">
							<span class="detail-label">Industry Experience</span>
							<div class="detail-value"><?php echo htmlspecialchars($industryExp, ENT_QUOTES, 'UTF-8'); ?></div>
						</div>
						<div class="detail-item">
							<span class="detail-label">Teaching Experience</span>
							<div class="detail-value"><?php echo htmlspecialchars($teachingExp, ENT_QUOTES, 'UTF-8'); ?></div>
						</div>
						<div class="detail-item" style="grid-column: 1 / -1;">
							<span class="detail-label">Research</span>
							<div class="detail-value"><?php echo htmlspecialchars($research, ENT_QUOTES, 'UTF-8'); ?></div>
						</div>
					</div>
				</div>

				<div class="detail-group">
					<h4 class="group-title">Publications</h4>
					<div class="detail-grid">
						<div class="detail-item">
							<span class="detail-label">National</span>
							<div class="detail-value"><?php echo htmlspecialchars($pubNational, ENT_QUOTES, 'UTF-8'); ?></div>
						</div>
						<div class="detail-item">
							<span class="detail-label">International</span>
							<div class="detail-value"><?php echo htmlspecialchars($pubInternational, ENT_QUOTES, 'UTF-8'); ?></div>
						</div>
					</div>
				</div>

				<div class="detail-group">
					<h4 class="group-title">Conferences</h4>
					<div class="detail-grid">
						<div class="detail-item">
							<span class="detail-label">National</span>
							<div class="detail-value"><?php echo htmlspecialchars($confNational, ENT_QUOTES, 'UTF-8'); ?></div>
						</div>
						<div class="detail-item">
							<span class="detail-label">International</span>
							<div class="detail-value"><?php echo htmlspecialchars($confInternational, ENT_QUOTES, 'UTF-8'); ?></div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>

<?php include_once(INCLUDES_PATH . '/footer.php'); ?>

