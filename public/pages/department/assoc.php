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
$totalMembers = 0;

for ($i = 0; $i < $categoryCnt; $i++) {
    $categoryId = $ComtCateg[$i]['id'];
    $CmtMemDet[$i] = $fcObj->getCmtMembers($tbComt, $categoryId);
    $totalMembers += !empty($CmtMemDet[$i]) ? count($CmtMemDet[$i]) : 0;
}
?>

<style>
    .assoc-page {
        --assoc-primary: #173d69;
        --assoc-primary-deep: #13345a;
        --assoc-accent: #f0b323;
        --assoc-accent-soft: #f8ecd0;
        --assoc-surface: #f4f7fb;
        --assoc-card: #ffffff;
        --assoc-border: #d9e3ef;
        --assoc-text: #23415f;
        --assoc-muted: #6b819c;
        padding-top: 26px;
        padding-bottom: 52px;
    }

    .assoc-hero {
        position: relative;
        overflow: hidden;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 28px;
        align-items: end;
        padding: 26px 30px;
        border: 1px solid var(--assoc-border);
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, var(--assoc-surface) 100%);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.04);
    }

    .assoc-hero::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 4px;
        background: linear-gradient(90deg, var(--assoc-accent), #d79a12);
    }

    .assoc-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        background: #eef3f8;
        color: var(--assoc-primary);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .assoc-kicker::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--assoc-accent), #d79a12);
    }

    .assoc-title {
        margin: 14px 0 10px;
        color: var(--assoc-primary-deep);
        font-size: clamp(30px, 3.2vw, 44px);
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1.08;
    }

    .assoc-desc {
        margin: 0;
        max-width: 720px;
        color: var(--assoc-muted);
        font-size: 15px;
        line-height: 1.7;
    }

    .assoc-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(120px, 1fr));
        gap: 12px;
        min-width: 250px;
    }

    .assoc-meta-pill {
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding: 14px 16px;
        border-radius: 14px;
        border: 1px solid #d7e0ea;
        background: rgba(255, 255, 255, 0.95);
        color: var(--assoc-text);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .assoc-meta-pill strong {
        color: var(--assoc-primary);
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1;
        text-transform: none;
    }

    .assoc-sections {
        display: grid;
        gap: 22px;
        margin-top: 26px;
    }

    .assoc-section {
        border: 1px solid var(--assoc-border);
        border-radius: 22px;
        background: var(--assoc-card);
        box-shadow: 0 12px 26px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .assoc-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 22px;
        border-bottom: 1px solid #e5edf5;
        background: linear-gradient(180deg, #fbfdff 0%, #f5f9fd 100%);
    }

    .assoc-section-title {
        margin: 0;
        color: var(--assoc-primary-deep);
        font-size: 19px;
        font-weight: 800;
    }

    .assoc-section-count {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 999px;
        background: var(--assoc-accent-soft);
        color: #8e6508;
        font-size: 12px;
        font-weight: 800;
    }

    .assoc-members-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        padding: 20px 22px 22px;
    }

    .assoc-member {
        position: relative;
        display: grid;
        grid-template-columns: 84px minmax(0, 1fr);
        gap: 16px;
        align-items: center;
        padding: 16px;
        border: 1px solid #dfe8f1;
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #f7fafe 100%);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.85);
    }

    .assoc-member::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 4px;
        border-radius: 18px 0 0 18px;
        background: linear-gradient(180deg, #d7e3ef 0%, #b9cade 100%);
    }

    .assoc-member-img,
    .assoc-member-avatar {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        border: 3px solid #edf3f9;
    }

    .assoc-member-img {
        object-fit: cover;
        background: #d9e5f0;
    }

    .assoc-member-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #2a5688, var(--assoc-primary-deep));
        color: #ffffff;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .assoc-member-content {
        min-width: 0;
    }

    .assoc-member-name {
        margin: 0;
        color: var(--assoc-primary-deep);
        font-size: 17px;
        font-weight: 800;
        line-height: 1.35;
    }

    .assoc-member-role {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin: 10px 0 0;
        padding: 6px 10px;
        border: 1px solid #d8e3ee;
        border-radius: 999px;
        background: #f3f7fb;
        color: var(--assoc-primary);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .assoc-member-role::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--assoc-accent);
    }

    .assoc-member-meta {
        margin: 12px 0 0;
        padding-top: 12px;
        border-top: 1px solid #e7eef5;
        color: var(--assoc-muted);
        font-size: 13px;
        line-height: 1.5;
        word-break: break-word;
    }

    .assoc-empty {
        margin: 0;
        padding: 22px;
        color: var(--assoc-muted);
        font-size: 14px;
        background: linear-gradient(180deg, #fbfdff 0%, #f6f9fc 100%);
    }

    @media (max-width: 991px) {
        .assoc-members-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .assoc-page {
            padding-top: 18px;
            padding-bottom: 40px;
        }

        .assoc-hero {
            grid-template-columns: 1fr;
            gap: 18px;
            padding: 22px 18px 20px;
            border-radius: 16px;
        }

        .assoc-meta {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            min-width: 0;
        }

        .assoc-section-head,
        .assoc-member {
            grid-template-columns: 1fr;
        }

        .assoc-member {
            text-align: center;
        }

        .assoc-member-img,
        .assoc-member-avatar {
            margin: 0 auto;
        }
    }
</style>

<div class="container assoc-page">
    <section class="assoc-hero">
        <div>
            <span class="assoc-kicker">Student Bodies</span>
            <h1 class="assoc-title">Associations</h1>
            <p class="assoc-desc">
                Explore the student professional bodies and association groups that support technical growth, peer learning, collaboration, and leadership within the AIML department.
            </p>
        </div>
        <div class="assoc-meta" aria-label="Association summary">
            <span class="assoc-meta-pill"><strong><?php echo (int)$categoryCnt; ?></strong>Categories</span>
            <span class="assoc-meta-pill"><strong><?php echo (int)$totalMembers; ?></strong>Members</span>
        </div>
    </section>

    <div class="assoc-sections">
        <?php for ($i = 0; $i < $categoryCnt; $i++) { ?>
            <?php $members = $CmtMemDet[$i]; ?>
            <section class="assoc-section">
                <div class="assoc-section-head">
                    <h2 class="assoc-section-title"><?php echo htmlspecialchars((string)$ComtCateg[$i]['category_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <span class="assoc-section-count"><?php echo (int)count($members); ?> Members</span>
                </div>

                <?php if (empty($members)) { ?>
                    <p class="assoc-empty">No members are available in this category right now.</p>
                <?php } else { ?>
                    <div class="assoc-members-grid">
                        <?php foreach ($members as $member) { ?>
                            <?php
                            $memberName = trim((string)($member['member_name'] ?? ''));
                            $memberAbout = trim((string)($member['member_about'] ?? ''));
                            $memberImage = trim((string)($member['member_image'] ?? ''));
                            $initial = strtoupper(substr($memberName !== '' ? $memberName : 'M', 0, 1));
                            $imagePath = ROOT_PATH . '/public/assets/images/students/' . $memberImage;
                            $hasImage = $memberImage !== '' && is_file($imagePath);
                            $imageUrl = BASE_URL . '/public/assets/images/students/' . rawurlencode($memberImage);
                            ?>
                            <div class="assoc-member">
                                <?php if ($hasImage) { ?>
                                    <img
                                        class="assoc-member-img"
                                        src="<?php echo htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="<?php echo htmlspecialchars($memberName !== '' ? $memberName : 'Member', ENT_QUOTES, 'UTF-8'); ?>"
                                    />
                                <?php } else { ?>
                                    <span class="assoc-member-avatar"><?php echo htmlspecialchars($initial, ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php } ?>

                                <div class="assoc-member-content">
                                    <p class="assoc-member-name"><?php echo htmlspecialchars($memberName !== '' ? $memberName : 'Member', ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p class="assoc-member-role"><?php echo htmlspecialchars((string)$ComtCateg[$i]['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p class="assoc-member-meta"><?php echo htmlspecialchars($memberAbout !== '' ? $memberAbout : 'AIML Association Member', ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </section>
        <?php } ?>
    </div>
</div>

<?php include_once(INCLUDES_PATH . '/footer.php'); ?>
