<?php require_once(__DIR__ . '/../../config.php');?>
<?php 
include_once('../layout/main_header.php');

// require_once("libraries/functions.class.php");
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbComtCtg = TB_COMT_CATEG;
$tbComt    = TB_COMMITTEE;

$ComtCateg = $fcObj->getComiteCatg($tbComtCtg);
$categoryCnt = sizeof($ComtCateg);

$CmtMemDet = array();

for ($i = 0; $i < $categoryCnt; $i++) {
    $categoryId = $ComtCateg[$i]['id'];
    $CmtMemDet[$i] = $fcObj->getCmtMembers($tbComt, $categoryId);
}
?>

<style type="text/css">
    :root {
        --cm-text: #163a61;
        --cm-subtext: #6b819c;
        --cm-accent: #f0b323;
        --cm-accent-deep: #d79a12;
        --cm-accent-soft: #fff5da;
        --cm-bg: #eef4fa;
        --cm-card: #ffffff;
        --cm-border: #d9e3ef;
        --cm-primary: #173d69;
        --cm-primary-deep: #13345a;
    }

    .committee-header {
        position: relative;
        overflow: hidden;
        padding: 24px 26px;
        border: 1px solid var(--cm-border);
        border-radius: 22px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--cm-bg) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 18px;
    }

    .committee-header::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--cm-accent), var(--cm-accent-deep));
    }

    .committee-page-title {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.6px;
        color: var(--cm-primary-deep);
        margin: 0 0 8px;
    }

    body {
        background: linear-gradient(135deg, #edf3f9 0%, #e5edf6 100%) !important;
    }

    .content-area {
        background:
            radial-gradient(circle at 12% 10%, rgba(240, 179, 35, 0.1), transparent 34%),
            radial-gradient(circle at 92% 18%, rgba(23, 61, 105, 0.08), transparent 32%),
            linear-gradient(180deg, #eef4fa 0%, #e6edf6 100%) !important;
        min-height: calc(100vh - 80px);
        border-radius: 16px 0 0 0;
    }

    .committee-subtitle {
        color: var(--cm-subtext);
        font-size: 16px;
        margin: 0;
    }

    .committee-stats {
        margin-top: 14px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .committee-stat-pill {
        border: 1px solid #ead290;
        border-radius: 999px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 700;
        color: #8b6510;
        background: var(--cm-accent-soft);
    }

    .committee-shell {
        border-radius: 20px;
        border: 1px solid var(--cm-border);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(2px);
        overflow: hidden;
    }

    .committee-body {
        padding: 24px;
    }

    .committee-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(280px, 1fr));
        gap: 16px;
    }

    .committee-category {
        border: 1px solid var(--cm-border);
        background: linear-gradient(180deg, #f9fbfe 0%, #f1f5fa 100%);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 0;
        display: flex;
        flex-direction: column;
    }

    .committee-category-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 12px;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 10px;
    }

    .committee-category-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: var(--cm-primary-deep);
        line-height: 1.2;
    }

    .committee-member-count {
        font-size: 12px;
        font-weight: 700;
        color: #8b6510;
        background: var(--cm-accent-soft);
        padding: 4px 10px;
        border-radius: 999px;
        white-space: nowrap;
    }

    .committee-member-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
        transition: transform .2s ease, box-shadow .2s ease;
        background: var(--cm-card);
        position: relative;
        overflow: hidden;
    }

    .committee-member-card::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--cm-primary), var(--cm-accent));
        opacity: 0.9;
    }

    .committee-member-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 28px rgba(15, 23, 42, 0.12);
    }

    .committee-members-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 14px;
    }

    .committee-member-card .card-body {
        display: flex;
        align-items: center;
        gap: 14px;
        text-align: left;
        min-height: 140px;
    }

    .committee-member-media {
        flex-shrink: 0;
    }

    .committee-member-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid var(--cm-accent-soft);
    }

    .committee-member-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid var(--cm-accent-soft);
        background: linear-gradient(135deg, var(--cm-primary), var(--cm-primary-deep));
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        font-weight: 800;
    }

    .committee-member-meta {
        font-size: 13px;
        color: #5b6574;
        line-height: 1.45;
        min-height: 38px;
        margin-bottom: 0;
        overflow-wrap: anywhere;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .committee-empty {
        color: var(--cm-subtext);
        font-size: 14px;
        font-weight: 600;
        padding: 14px 12px;
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
        background: #f9fafb;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .committee-empty::before {
        content: "\f52a";
        font-family: "bootstrap-icons";
        font-size: 16px;
        color: #6c7f95;
    }

    .committee-add-btn {
        padding: 13px 22px;
        border: 0;
        border-radius: 13px;
        background: linear-gradient(135deg, var(--cm-primary-deep), var(--cm-primary));
        color: #ffffff;
        font-weight: 700;
        box-shadow: 0 8px 16px rgba(19, 52, 90, 0.28);
    }

    .committee-add-btn:hover {
        filter: brightness(1.06);
        color: #ffffff;
    }

    @media (max-width: 992px) {
        .committee-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .committee-page-title {
            font-size: 26px;
        }

        .committee-body {
            padding: 16px;
        }

        .committee-category-title {
            font-size: 17px;
        }

        .committee-member-card .card-body {
            flex-direction: column;
            text-align: center;
            min-height: 0;
        }
    }

    html[data-theme="dark"] body {
        background: linear-gradient(135deg, #0d1726 0%, #0a1321 100%) !important;
    }

    html[data-theme="dark"] .content-area {
        background:
            radial-gradient(circle at 12% 10%, rgba(45, 212, 191, 0.13), transparent 36%),
            radial-gradient(circle at 92% 18%, rgba(59, 130, 246, 0.14), transparent 32%),
            linear-gradient(180deg, #0f1b2c 0%, #0b1523 100%) !important;
    }

    html[data-theme="dark"] .committee-header {
        border-color: #2a3f5d;
        background:
            linear-gradient(135deg, rgba(45, 212, 191, 0.12), rgba(59, 130, 246, 0.08)),
            #101f32;
        box-shadow: 0 14px 30px rgba(2, 8, 20, 0.42);
    }

    html[data-theme="dark"] .committee-page-title {
        color: #e6f0ff;
    }

    html[data-theme="dark"] .committee-subtitle {
        color: #a8bfdc;
    }

    html[data-theme="dark"] .committee-stat-pill {
        border-color: #33557e;
        background: #18304f;
        color: #cbe2ff;
    }

    html[data-theme="dark"] .committee-shell {
        border-color: #2a3f5d;
        background: rgba(16, 29, 47, 0.9);
        box-shadow: 0 16px 30px rgba(2, 8, 20, 0.45);
    }

    html[data-theme="dark"] .committee-category {
        border-color: #2a3f5d;
        background: linear-gradient(180deg, #13233a 0%, #101f34 100%);
    }

    html[data-theme="dark"] .committee-category-head {
        border-bottom-color: #2a3f5d;
    }

    html[data-theme="dark"] .committee-category-title {
        color: #8de4db;
    }

    html[data-theme="dark"] .committee-member-count {
        background: #1a3455;
        color: #9af0e5;
    }

    html[data-theme="dark"] .committee-member-card {
        border-color: #2a3f5d;
        box-shadow: 0 10px 24px rgba(2, 8, 20, 0.4);
        background: #111f34;
    }

    html[data-theme="dark"] .committee-member-meta,
    html[data-theme="dark"] .committee-member-content h6 {
        color: #d9e8fb !important;
    }

    html[data-theme="dark"] .committee-empty {
        color: #c4d7f0;
        border-color: #365473;
        background: #14283f;
    }

    html[data-theme="dark"] .committee-empty::before {
        color: #9eb9d9;
    }
</style>

<?php
$totalMembers = 0;
for ($i = 0; $i < $categoryCnt; $i++) {
    $totalMembers += !empty($CmtMemDet[$i]) ? count($CmtMemDet[$i]) : 0;
}
?>

<div class="committee-header">
    <h3 class="committee-page-title">AIML Association Committee</h3>
    <p class="committee-subtitle">Manage office bearers and members category-wise.</p>
    <div class="committee-stats">
        <span class="committee-stat-pill">
            <i class="bi bi-diagram-3 me-1"></i>
            <?php echo (int)$categoryCnt; ?> Categories
        </span>
        <span class="committee-stat-pill">
            <i class="bi bi-people me-1"></i>
            <?php echo (int)$totalMembers; ?> Total Members
        </span>
    </div>
</div>

<div class="card committee-shell border-0">
    <div class="committee-body">

        <?php if ($categoryCnt > 0) { ?>
            <div class="committee-grid">

            <?php for ($j = 0; $j < $categoryCnt; $j++) { ?>
                <?php $memberCount = !empty($CmtMemDet[$j]) ? sizeof($CmtMemDet[$j]) : 0; ?>

                <div class="committee-category">
                    <div class="committee-category-head">
                        <h5 class="committee-category-title">
                            <?php echo $ComtCateg[$j]['category_name']; ?>
                        </h5>
                        <span class="committee-member-count"><?php echo $memberCount; ?> Members</span>
                    </div>

                    <div class="committee-members-grid">

                        <?php if (!empty($CmtMemDet[$j])) { ?>

                            <?php foreach ($CmtMemDet[$j] as $member) { ?>
                                <?php
                                    $memberName = trim((string)($member['member_name'] ?? ''));
                                    $memberAbout = (string)($member['member_about'] ?? '');
                                    $memberImage = trim((string)($member['member_image'] ?? ''));
                                    $imagePath = BASE_URL.'/public/assets/images/users/'.rawurlencode($memberImage !== '' ? $memberImage : 'default.png');
                                    $initial = strtoupper(substr($memberName !== '' ? $memberName : 'M', 0, 1));
                                ?>

                                <div>
                                    <div class="card committee-member-card border-0 h-100">

                                        <div class="card-body">
                                            <div class="committee-member-media">
                                                <img 
                                                    src="<?php echo $imagePath; ?>"
                                                    class="committee-member-img"
                                                    alt="<?php echo htmlspecialchars($memberName); ?>"
                                                    onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex';"
                                                >
                                                <span class="committee-member-avatar" style="display:none;"><?php echo htmlspecialchars($initial, ENT_QUOTES, 'UTF-8'); ?></span>
                                            </div>

                                            <div class="committee-member-content">
                                                <h6 class="fw-semibold mb-1">
                                                    <?php echo htmlspecialchars($memberName !== '' ? $memberName : 'Member'); ?>
                                                </h6>

                                                <p class="committee-member-meta">
                                                    <?php echo htmlspecialchars($memberAbout !== '' ? $memberAbout : 'No profile details available.'); ?>
                                                </p>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            <?php } ?>

                        <?php } else { ?>

                            <div class="committee-empty">
                                No members assigned for this category.
                            </div>

                        <?php } ?>

                    </div>
                </div>

            <?php } ?>
            </div>

        <?php } else { ?>

            <p class="text-muted">No committee categories found.</p>

        <?php } ?>

        <div class="mt-5">
            <a href="addmem.php" class="btn committee-add-btn">
                <i class="bi bi-plus-circle me-1"></i>
                Add Committee Member
            </a>
        </div>

    </div>
</div>

<?php include_once('../layout/footer.php'); ?>
