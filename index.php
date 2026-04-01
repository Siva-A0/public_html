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

$resolveLeadershipImage = static function ($imageName, $fallbackImage = '') {
    $imagesDir = ROOT_PATH . '/public/assets/images';
    $requestedImage = trim((string)$imageName);
    $fallbackImage = trim((string)$fallbackImage);
    $candidateNames = array_filter(array($requestedImage, $fallbackImage));
    $resolvedFile = '';

    foreach ($candidateNames as $candidateName) {
        $candidatePath = $imagesDir . '/' . $candidateName;
        if (is_file($candidatePath)) {
            $resolvedFile = $candidateName;
            break;
        }

        $matches = glob($imagesDir . '/' . pathinfo($candidateName, PATHINFO_FILENAME) . '.*');
        if (!empty($matches)) {
            $resolvedFile = basename($matches[0]);
            break;
        }
    }

    if ($resolvedFile === '') {
        return '';
    }

    $resolvedPath = $imagesDir . '/' . $resolvedFile;
    $version = is_file($resolvedPath) ? '?v=' . (string)filemtime($resolvedPath) : '';

    return BASE_URL . '/public/assets/images/' . rawurlencode($resolvedFile) . $version;
};
?>

<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <span class="hero-kicker">Department of AIML</span>
            <h1 class="hero-title">
                <span>Code.</span> <span>Learn.</span> <span class="typing hero-accent">Evolve.</span>
            </h1>
            <p class="hero-subtitle">
                Transforming ideas into AI-driven solutions through research, hands-on labs, and industry-ready learning paths.
            </p>

            <div class="hero-actions">
                <a href="<?php echo BASE_URL; ?>/public/pages/department/department.php" class="btn hero-btn hero-btn-primary">
                    Explore Department
                    <span aria-hidden="true">-></span>
                </a>
            </div>

            <div class="hero-stats" data-aos="fade-up" data-aos-delay="140">
                <div class="hero-stat">
                    <strong>700+</strong>
                    <span>Students</span>
                </div>
                <div class="hero-stat">
                    <strong>14+</strong>
                    <span>Placements</span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container home-shell">
    <section class="home-overview mb-5">
        <div class="row g-4 align-items-stretch">
            <div class="col-12">
                <div class="home-intro-card h-100">
                    <span class="home-kicker">Department Overview</span>
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
        </div>
    </section>

    <section class="home-feature-grid mb-5">
        <article class="home-feature-card">
            <span class="home-feature-index">01</span>
            <h3>About the Department</h3>
            <p>The AIML department builds intelligent systems that can learn, analyze, and support decisions. Students develop strong foundations in data science, machine learning, deep learning, and practical AI applications.</p>
        </article>
        <article class="home-feature-card">
            <span class="home-feature-index">02</span>
            <h3>Core Technologies &amp; Skills</h3>
            <p>Students gain hands-on experience in Python, machine learning, deep learning, NLP, computer vision, and data analytics. Projects and labs help turn core concepts into practical, industry-ready skills.</p>
        </article>
        <article class="home-feature-card">
            <span class="home-feature-index">03</span>
            <h3>Career &amp; Opportunities</h3>
            <p>The department prepares students for roles such as AI Engineer, Data Scientist, ML Engineer, and Research Analyst. Internships, placement support, and research exposure strengthen both career and higher-study pathways.</p>
        </article>
    </section>

    <section class="home-highlight-band mb-5">
        <div class="home-highlight-copy">
            <span class="home-kicker">Department Focus</span>
            <h3 class="home-section-title mb-2">Building intelligent systems with academic rigor and practical relevance.</h3>
            <p class="home-intro-text mb-0">
                From core machine learning concepts to applied development, the department encourages students to move from understanding to implementation through guided projects, research culture, and collaborative learning.
            </p>
        </div>
        <div class="home-focus-grid">
            <article class="home-focus-item">
                <div class="home-focus-icon">
                    <i class="bi bi-braces" aria-hidden="true"></i>
                </div>
                <div>
                    <h4 class="home-focus-title">Applied AI &amp; ML</h4>
                    <p class="home-focus-desc">Hands-on training in Python, TensorFlow, and PyTorch across structured lab sessions and capstone projects.</p>
                </div>
            </article>
            <article class="home-focus-item">
                <div class="home-focus-icon">
                    <i class="bi bi-diagram-3" aria-hidden="true"></i>
                </div>
                <div>
                    <h4 class="home-focus-title">Research &amp; Innovation</h4>
                    <p class="home-focus-desc">Students engage in faculty-led research on NLP, Computer Vision, and Generative AI with publication opportunities.</p>
                </div>
            </article>
            <article class="home-focus-item">
                <div class="home-focus-icon">
                    <i class="bi bi-buildings" aria-hidden="true"></i>
                </div>
                <div>
                    <h4 class="home-focus-title">Industry Connect</h4>
                    <p class="home-focus-desc">Active tie-ups with tech companies for internships, live projects, guest lectures, and placement drives.</p>
                </div>
            </article>
            <article class="home-focus-item">
                <div class="home-focus-icon">
                    <i class="bi bi-person-workspace" aria-hidden="true"></i>
                </div>
                <div>
                    <h4 class="home-focus-title">Student Development</h4>
                    <p class="home-focus-desc">Competitions, hackathons, workshops, and mentorship programs designed to sharpen problem-solving and communication skills.</p>
                </div>
            </article>
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
                                <?php
                                $leaderImageName = (string)($leader['data'][0]['image'] ?? '');
                                $leaderFallbackImage = $leader['alt'] === 'HOD' ? 'ITHOD.png' : '';
                                $leaderImageUrl = $resolveLeadershipImage($leaderImageName, $leaderFallbackImage);
                                ?>
                                <img
                                    src="<?php echo htmlspecialchars($leaderImageUrl !== '' ? $leaderImageUrl : (BASE_URL . '/public/assets/images/' . rawurlencode($leaderImageName)), ENT_QUOTES, 'UTF-8'); ?>"
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
