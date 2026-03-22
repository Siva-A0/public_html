<?php  
	 
	require_once(__DIR__ . '/../../../config.php');

    include_once(INCLUDES_PATH . '/header.php');
    require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();
   
   $tbStaffCateg = TB_STAFF_CATEGORY;
   $tbStaff		 = TB_STAFF;
   
   $staffCateg		= $fcObj->getStaffCategories($tbStaffCateg);
   $categoryCnt		= sizeof($staffCateg);
   
   for($i=0; $i<$categoryCnt;$i++){
  		
		$categoryId	= $staffCateg[$i]['id'];
		
		$staffDetails[$i]	= $fcObj->getStaffDetails($tbStaff,$categoryId);
	}
	
?>
<style>
    .department-staff {
        padding: 4px;
    }

    .department-staff .staff-category-block {
        margin-bottom: 24px;
    }

    .department-staff .staff-category-title {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
        padding: 10px 16px;
        border-left: 4px solid #f3b308;
        border-radius: 10px;
        background: linear-gradient(135deg, #f7fbff, #f2f8ff);
        font-size: 24px;
        font-weight: 700;
        color: #12355f;
    }

    .department-staff .staff-category-title::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #1d6ecc;
        box-shadow: 0 0 0 4px rgba(29, 110, 204, 0.14);
    }

    .department-staff .staff-row-list {
        display: grid;
        gap: 16px;
    }

    .department-staff .staff-line-card {
        position: relative;
        display: grid;
        grid-template-columns: 96px minmax(0, 1fr) auto;
        align-items: center;
        gap: 18px;
        padding: 18px 20px;
        border: 1px solid #d8e4f1;
        border-radius: 14px;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        box-shadow: 0 10px 24px rgba(15, 30, 52, 0.08);
        text-decoration: none;
        overflow: hidden;
        transition: transform 0.24s ease, box-shadow 0.24s ease, border-color 0.24s ease, background 0.24s ease;
    }

    .department-staff .staff-line-card::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #1e6dca, #4da3ff);
    }

    .department-staff .staff-line-card:hover {
        transform: translateY(-5px);
        border-color: #b8d2ef;
        background: linear-gradient(180deg, #ffffff, #f3f9ff);
        box-shadow: 0 18px 36px rgba(15, 30, 52, 0.16);
    }

    .department-staff .staff-avatar {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #d8e6f4;
        box-shadow: 0 8px 16px rgba(15, 30, 52, 0.12);
        flex-shrink: 0;
        transition: transform 0.22s ease;
    }

    .department-staff .staff-line-card:hover .staff-avatar {
        transform: scale(1.04);
    }

    .department-staff .staff-text {
        min-width: 0;
    }

    .department-staff .staff-quote {
        margin: 0 0 5px;
        font-size: 18px;
        font-style: italic;
        color: #617995;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .department-staff .staff-name {
        margin: 0 0 2px;
        font-size: 34px;
        font-weight: 700;
        line-height: 1.1;
        color: #132d4c;
        text-transform: capitalize;
    }

    .department-staff .staff-designation {
        margin: 0;
        font-size: 20px;
        color: #1d5ea8;
        font-weight: 600;
        text-transform: capitalize;
    }

    .department-staff .staff-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 1px solid #c8dbef;
        color: #1f69bf;
        font-size: 19px;
        background: #f4f9ff;
        transition: transform 0.22s ease, background-color 0.22s ease, border-color 0.22s ease;
    }

    .department-staff .staff-line-card:hover .staff-cta {
        transform: translateX(2px);
        background: #e9f4ff;
        border-color: #9ec4ea;
    }

    .department-staff .staff-empty {
        margin: 0;
        padding: 14px;
        border: 1px dashed #c4d8ed;
        border-radius: 12px;
        background: #f8fbff;
        color: #4d647f;
    }

    @media (max-width: 768px) {
        .department-staff .staff-line-card {
            grid-template-columns: 72px minmax(0, 1fr);
            gap: 12px;
            padding: 14px;
        }

        .department-staff .staff-avatar {
            width: 72px;
            height: 72px;
        }

        .department-staff .staff-quote {
            font-size: 15px;
        }

        .department-staff .staff-name {
            font-size: 24px;
        }

        .department-staff .staff-designation {
            font-size: 16px;
        }

        .department-staff .staff-cta {
            display: none;
        }
    }
</style>
<div class="box1">
        <div class="wrapper">
          <article class="col1">
				<div id="index_cont">
					<div class="post">
						<span class="alignCenter">
							<h4>IT Department </h4>
						</span>
						<p>
							
						</p>
					</div>
					<div id='content_left' class='content_left'>
						<?php 
							include_once('departleftnav.php');
						?>						
					</div>
					<div id='content_right' class='content_right'>
						<div class="comteeMem department-staff">
							<?php
								
								for($j=0; $j< $categoryCnt; $j++){
								
							?>
									<div class="staff-category-block">
										<div class="committeeTitle staff-category-title">
										<?php 
											echo htmlspecialchars($staffCateg[$j]['category_name'], ENT_QUOTES, 'UTF-8');
										?>
										</div>
									
									<div class="staff-row-list">
										<?php
											$catStafCnt	= sizeof($staffDetails[$j]);

                                            if ($catStafCnt === 0) {
                                                echo '<p class="staff-empty">No faculty members available in this category.</p>';
                                            }

											for($k=0; $k<$catStafCnt; $k++){
												
												$image	= (string)$staffDetails[$j][$k]['image'];
												$firstName = (string)$staffDetails[$j][$k]['first_name'];
                                                $lastName = isset($staffDetails[$j][$k]['last_name']) ? (string)$staffDetails[$j][$k]['last_name'] : '';
                                                $name = trim($firstName . ' ' . $lastName);
                                                if ($name === '') {
                                                    $name = 'Faculty Member';
                                                }
                                                $qualification = str_replace('\,', ',', (string)$staffDetails[$j][$k]['qualification']);
                                                if (trim($qualification) === '') {
                                                    $qualification = 'Department Faculty';
                                                }
                                                $designation = (string)$staffDetails[$j][$k]['designation'];
                                                if (trim($designation) === '') {
                                                    $designation = 'Faculty';
                                                }
                                                $staffId = (int)$staffDetails[$j][$k]['id'];
										?>
											<a class="comteeMemDetails staff-line-card" href="view_faculty.php?faculty=<?php echo $staffId; ?>">
												<img class="staff-avatar" src="<?php echo BASE_URL; ?>/public/assets/images/staff/<?php echo rawurlencode($image); ?>" alt="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" />
												<div class="staff-text">
                                                    <p class="staff-quote">&ldquo;<?php echo htmlspecialchars($qualification, ENT_QUOTES, 'UTF-8'); ?>&rdquo;</p>
													<h5 class="staff-name"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h5>
													<p class="staff-designation"><?php echo htmlspecialchars($designation, ENT_QUOTES, 'UTF-8'); ?></p>
												</div>
                                                <span class="staff-cta" aria-hidden="true">&rarr;</span>
											</a>
										<?php
											}
										?>
									</div>
                                    </div>
									<br class="clearfix" />
							<?php 
								} 
							?>
							
						</div>
					</div>
					<br class="clearfix" />
				</div>
					</article>
					<article class="col2 pad_left2">
					<?php 
						include_once('sidebar.php');
					?>
					</article>
</div>
</div>
<?php include_once(INCLUDES_PATH . '/footer.php'); ?>

