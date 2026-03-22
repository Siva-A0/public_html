<?php
require_once(__DIR__ . '/../../../config.php');

include_once(INCLUDES_PATH . '/header.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbComtCtg = TB_COMT_CATEG;
$tbComt = TB_COMMITTEE;

$ComtCateg = $fcObj->getComiteCatg($tbComtCtg);
$categoryCnt = sizeof($ComtCateg);

$CmtMemDet = array();

for ($i = 0; $i < $categoryCnt; $i++) {
    $categoryId = $ComtCateg[$i]['id'];
    $CmtMemDet[$i] = $fcObj->getCmtMembers($tbComt, $categoryId);
}
?>

<style>
    .assoc-shell .assoc-hero,
    .assoc-shell .assoc-card {
        border: 1px solid #d7e4f0;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 12px 28px rgba(15, 30, 52, 0.08);
    }

    .assoc-shell .assoc-hero {
        padding: 22px 22px 18px;
        margin-bottom: 18px;
        background: linear-gradient(140deg, #f8fbff, #eef5fd);
    }

    .assoc-shell .assoc-kicker {
        display: inline-block;
        margin-bottom: 10px;
        font-size: 12px;
        font-weight: 700;
        color: #164a88;
        letter-spacing: 1.1px;
        text-transform: uppercase;
    }

    .assoc-shell .assoc-title {
        margin: 0;
        font-size: 32px;
        font-weight: 800;
        color: #132a45;
    }

    .assoc-shell .assoc-desc {
        margin: 10px 0 0;
        font-size: 15px;
        line-height: 1.8;
        color: #4f657f;
    }

    .assoc-shell .assoc-card {
        padding: 18px;
    }

    .assoc-shell .assoc-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .assoc-shell .assoc-member {
        display: flex;
        gap: 12px;
        align-items: center;
        border: 1px solid #e0e9f3;
        border-radius: 12px;
        padding: 12px;
        background: #f8fbff;
    }

    .assoc-shell .assoc-member-img {
        width: 74px;
        height: 74px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #d7e6f6;
        flex-shrink: 0;
    }

    .assoc-shell .assoc-member-name {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #16365c;
        line-height: 1.3;
    }

    .assoc-shell .assoc-member-role {
        margin: 2px 0 0;
        font-size: 13px;
        font-weight: 600;
        color: #0b63ce;
    }

    .assoc-shell .assoc-member-meta {
        margin: 4px 0 0;
        font-size: 13px;
        color: #57718f;
        line-height: 1.4;
        word-break: break-word;
    }

    .assoc-shell .assoc-empty {
        margin: 0;
        padding: 14px;
        border: 1px dashed #c9d8ea;
        border-radius: 10px;
        color: #4e6683;
        background: #f6faff;
    }

    @media (max-width: 991px) {
        .assoc-shell .assoc-title {
            font-size: 28px;
        }

        .assoc-shell .assoc-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container my-4 assoc-shell">
    <div class="row g-4">
        <div class="col-xl-9">
            <div class="assoc-hero">
                <span class="assoc-kicker">AIML Community</span>
                <h1 class="assoc-title">AIML Association</h1>
                <p class="assoc-desc">
                    The association organizes technical events, peer-learning sessions, and collaborative activities that build leadership and practical AI/ML skills across all student groups.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="assoc-card h-100">
                        <?php include_once('leftnav.php'); ?>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="assoc-card">
                        <div class="assoc-grid">
                            <?php
                            $membersCnt = count($CmtMemDet);
                            $rendered = 0;

                            for ($i = 0; $i < $membersCnt; $i++) {
                                if (!empty($CmtMemDet[$i])) {
                                    $rendered++;
                                    $member = $CmtMemDet[$i][0];
                                    $fullName = trim($member['firstname'] . ' ' . $member['lastname']);
                                    $memberImage = rawurlencode((string) $member['image']);
                            ?>
                                <div class="assoc-member">
                                    <img
                                        class="assoc-member-img"
                                        src="<?php echo BASE_URL; ?>/public/assets/images/users/<?php echo $memberImage; ?>"
                                        alt="<?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>"
                                        title="<?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>"
                                    />
                                    <div>
                                        <p class="assoc-member-name"><?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p class="assoc-member-role"><?php echo htmlspecialchars($ComtCateg[$i]['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p class="assoc-member-meta"><?php echo htmlspecialchars((string) $member['address'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    </div>
                                </div>
                            <?php
                                }
                            }

                            if ($rendered === 0) {
                                echo '<p class="assoc-empty">No association members available right now.</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <?php include_once('sidebar.php'); ?>
        </div>
    </div>
</div>

<?php include_once(INCLUDES_PATH . '/footer.php'); ?>

