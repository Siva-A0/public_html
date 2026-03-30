<?php require_once(__DIR__ . '/../../config.php');

   require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();

   $tbStream	= TB_STREAM;

   $branches	= $fcObj->getStreams( $tbStream );
 
   $branchesCnt	= sizeof($branches);
   
	if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_academic_pages.css';

include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');
?>

<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter"></span>
						<p>
							
						</p>
					</div>

					<div id='content_left' class='content_left'></div>
                    
					<div id='content_right' class='content_right'>
						<div class="branch-page">
						<div class="branch-list-hero">
                            <h3 class="branch-list-title">Manage Branches</h3>
                            <p class="branch-list-subtitle">Keep branch/specialization records aligned and easy to manage.</p>
                        </div>

                        <div class="branch-list-card">
                            <div class="branch-list-head">
                                <div>Branch Name</div>
                                <div style="text-align:right;">Actions</div>
                            </div>

                            <?php if ($branchesCnt > 0) { ?>
                                <?php for($j=0; $j< $branchesCnt; $j++){ ?>
                                    <div class="branch-list-row">
                                        <div class="branch-name">
                                            <?php echo htmlspecialchars((string)$branches[$j]['stream_code'], ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                        <div class="branch-actions">
                                            <a class="branch-btn branch-btn-edit" href="edit_branch.php?branch=<?php echo (int)$branches[$j]['id'];?>">
                                                Edit
                                            </a>
                                            <a class="branch-btn branch-btn-delete" href="delete_branch.php?branch=<?php echo (int)$branches[$j]['id'];?>" onclick="return confirm('Do You Want To Continue To Delete');">
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="branch-empty">No branches found.</div>
                            <?php } ?>

                        </div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=streams" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>

<script type="text/javascript"></script>

