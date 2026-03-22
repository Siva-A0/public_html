<?php 
require_once(__DIR__ . '/config.php');

include_once(INCLUDES_PATH . '/header.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbComments = TB_COMMENTS;

$chirmanComment = $fcObj->getComment($tbComments, CHAIRMAN);
$HodComment     = $fcObj->getComment($tbComments, HOD);
$princComment   = $fcObj->getComment($tbComments, PRINCIPAL);
$directorComment = $fcObj->getComment($tbComments, DIRECTOR);

$leadershipComments = array(
    array('title' => 'Chairman Message', 'data' => $chirmanComment, 'alt' => 'Chairman'),
    array('title' => 'Principal Message', 'data' => $princComment, 'alt' => 'Principal'),
    array('title' => 'Director Message', 'data' => $directorComment, 'alt' => 'Director'),
    array('title' => 'HOD Message', 'data' => $HodComment, 'alt' => 'HOD')
);
?>

<div class="container my-5 home-shell">
    <section class="home-overview mb-5">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-8">
                <div class="home-intro-card h-100">
                    <span class="home-kicker">Logo-Inspired Homepage Direction</span>
                    <h2 class="fw-bold mb-3 home-section-title">Learn with intelligence. Build with purpose.</h2>
                    <p class="home-intro-text mb-0">
                        Our AIML department combines strong academic foundations, applied lab work, and industry-facing learning experiences so students can build intelligent systems with clarity, responsibility, and real-world impact.
                    </p>
                    <div class="home-intro-points">
                        <span>Academic depth</span>
                        <span>Applied AI projects</span>
                        <span>Industry-ready learning</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="home-aside-card h-100">
                    <span class="home-kicker">At A Glance</span>
                    <div class="home-aside-stat">
                        <strong>1200+</strong>
                        <span>students building their AI foundation</span>
                    </div>
                    <div class="home-aside-stat">
                        <strong>25+</strong>
                        <span>research and innovation spaces</span>
                    </div>
                    <div class="home-aside-stat">
                        <strong>40+</strong>
                        <span>recent placement opportunities</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-feature-grid mb-5">
        <article class="home-feature-card">
            <span class="home-feature-index">01</span>
            <h3>About the Department</h3>
            <p>The AIML department focuses on building intelligent systems that can learn, analyze, and make decisions. It provides strong foundations in data science, machine learning, deep learning, and real-world AI applications to prepare students for future technologies.</p>
        </article>
        <article class="home-feature-card">
            <span class="home-feature-index">02</span>
            <h3>Core Technologies &amp; Skills</h3>
            <p>Students gain hands-on experience in Python, Machine Learning, Deep Learning, NLP, Computer Vision, and Data Analytics. The curriculum is designed to align with industry demands, ensuring practical knowledge through projects and labs.</p>
        </article>
        <article class="home-feature-card">
            <span class="home-feature-index">03</span>
            <h3>Career &amp; Opportunities</h3>
            <p>The department prepares students for roles like AI Engineer, Data Scientist, ML Engineer, and Research Analyst. With strong placement support, internships, and research opportunities, students are equipped for both industry and higher studies.</p>
        </article>
    </section>

    <section class="home-highlight-band mb-5">
        <div>
            <span class="home-kicker">Department Focus</span>
            <h3 class="home-section-title mb-2">Building intelligent systems with academic rigor and practical relevance.</h3>
            <p class="home-intro-text mb-0">
                From core machine learning concepts to applied development, the department encourages students to move from understanding to implementation through guided projects, research culture, and collaborative learning.
            </p>
        </div>
        <div class="home-pill-row">
            <span class="home-pill">Indigo base</span>
            <span class="home-pill">Muted gold accents</span>
            <span class="home-pill">Silver-blue surfaces</span>
            <span class="home-pill">Research culture</span>
            <span class="home-pill">Student outcomes</span>
            <span class="home-pill">Practical AI learning</span>
        </div>
    </section>

    <section class="home-leadership-section">
        <div class="home-section-heading">
            <span class="home-kicker">Leadership Messages</span>
            <h3 class="home-section-title mb-2">Voices shaping the department vision.</h3>
            <p class="home-intro-text mb-0">
                Guidance from the department leadership reflects the academic direction, institutional values, and future-facing goals of AIML.
            </p>
        </div>

        <div class="home-leadership-grid">
            <?php foreach ($leadershipComments as $leader) { ?>
                <?php if (!empty($leader['data'])) { ?>
                    <article class="card profile-quote-card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="profile-quote-label"><?php echo $leader['title']; ?></div>
                            <div class="d-flex align-items-center gap-3">
                                <img
                                    src="<?php echo BASE_URL; ?>/public/assets/images/<?php echo $leader['data'][0]['image']; ?>"
                                    class="rounded-circle profile-quote-photo"
                                    width="80"
                                    height="80"
                                    alt="<?php echo $leader['alt']; ?>"
                                >
                                <div>
                                    <div class="fw-semibold profile-quote-name">
                                        <?php echo $leader['data'][0]['name']; ?>
                                    </div>
                                    <div class="text-muted small profile-quote-role">
                                        <?php echo strtoupper(str_replace('\,', ',', $leader['data'][0]['designation'])); ?>
                                    </div>
                                </div>
                            </div>

                            <p class="fst-italic fs-6 profile-quote-text mt-3 mb-0">
                                <?php echo $leader['data'][0]['comment']; ?>
                            </p>
                        </div>
                    </article>
                <?php } ?>
            <?php } ?>
        </div>
    </section>
</div>

<?php include_once(INCLUDES_PATH . '/footer.php'); ?>
