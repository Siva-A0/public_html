<?php 
	require_once(__DIR__ . '/../../config.php');

    include_once(INCLUDES_PATH . '/header.php');
    require_once(LIB_PATH . '/functions.class.php');

	$fcObj = new DataFunctions();
?>

<style>
    .about-aiml-page {
        flex: 1 1 100%;
        max-width: 100%;
    }

    .about-aiml-page #index_cont {
        max-width: 1240px;
        margin: 0 auto;
    }

    .about-aiml-page #content {
        border: 0;
        background: transparent;
        box-shadow: none;
        padding: 0;
    }

    .about-aiml-page .about-shell {
        background: #ffffff;
        border: 1px solid #d8e3ef;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 10px 24px rgba(15, 30, 52, 0.08);
    }

    .about-aiml-page .about-title {
        margin: 0;
        color: #12365f;
        font-size: 38px;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .about-aiml-page .about-subtitle {
        margin: 10px 0 0;
        color: #496686;
        font-size: 16px;
        line-height: 1.5;
    }

    .about-aiml-page .about-meta {
        margin-top: 14px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .about-aiml-page .about-meta span {
        padding: 6px 10px;
        border-radius: 999px;
        border: 1px solid #cbddf0;
        background: #f6fbff;
        color: #2f5479;
        font-size: 12px;
        font-weight: 600;
    }

    .about-aiml-page .about-grid {
        margin-top: 16px;
        display: grid;
        gap: 12px;
    }

    .about-aiml-page .about-block {
        border: 1px solid #dbe7f2;
        border-radius: 12px;
        padding: 14px;
        background: #fcfeff;
    }

    .about-aiml-page .about-block h5 {
        margin: 0 0 8px;
        color: #183b63;
        font-size: 20px;
        font-weight: 700;
    }

    .about-aiml-page .about-block p {
        margin: 0;
        color: #27496d;
        font-size: 15px;
        line-height: 1.65;
    }

    .about-aiml-page .about-block p + p {
        margin-top: 10px;
    }

    @media (max-width: 767px) {
        .about-aiml-page .about-shell {
            padding: 14px;
        }

        .about-aiml-page .about-title {
            font-size: 30px;
        }

        .about-aiml-page .about-subtitle {
            font-size: 14px;
        }

        .about-aiml-page .about-block h5 {
            font-size: 18px;
        }

        .about-aiml-page .about-block p {
            font-size: 14px;
        }
    }
</style>

<div class="box1">
    <div class="wrapper">

        <article class="col1 about-aiml-page">
            <div id="index_cont">
                <div id="content">
                    <div class="about-shell">
                        <h1 class="about-title">About AIML Department</h1>
                        <p class="about-subtitle">
                            The Department of Artificial Intelligence and Machine Learning at Narsimha Reddy Engineering College builds strong technical foundations, applied skills, and industry readiness.
                        </p>

                        <div class="about-meta">
                            <span>AI & ML</span>
                            <span>Research Culture</span>
                            <span>Industry Collaboration</span>
                            <span>Innovation Driven</span>
                        </div>

                        <div class="about-grid">
                            <section class="about-block">
                                <h5>AIML</h5>
                                <p>
                                    The Department of Artificial Intelligence and Machine Learning (AIML) at
                                    <strong>Narsimha Reddy Engineering College</strong> is committed to advancing intelligent technologies that shape the future of industries. The department focuses on strong foundations in mathematics, programming, data science, and algorithm design to help students build smart, data-driven systems.
                                </p>
                                <p>
                                    With the rapid evolution of AI across healthcare, finance, robotics, automation, cybersecurity, and smart infrastructure, the department prepares students to meet modern technological demands through practical exposure, internships, research initiatives, and real-world projects.
                                </p>
                            </section>

                            <section class="about-block">
                                <h5>Faculty</h5>
                                <p>
                                    The department is supported by experienced and research-oriented faculty specializing in Machine Learning, Deep Learning, Data Science, NLP, Computer Vision, Artificial Neural Networks, and Intelligent Systems. Faculty members actively mentor students in research activities, technical competitions, and innovation-driven projects.
                                </p>
                            </section>

                            <section class="about-block">
                                <h5>Undergraduate Program</h5>
                                <p>
                                    The undergraduate program in AIML is designed to equip students with essential technical expertise in programming, statistical modeling, AI algorithms, data analytics, and system design. Students gain hands-on experience through laboratories, coding challenges, hackathons, industrial visits, and collaborative projects.
                                </p>
                            </section>

                            <section class="about-block">
                                <h5>Department Vision</h5>
                                <p>
                                    The AIML Department emphasizes innovation, research culture, entrepreneurship, and ethical AI development. With modern infrastructure, advanced computing facilities, and strong placement support, the department aims to produce industry-ready engineers capable of driving intelligent digital transformation.
                                </p>
                            </section>
                        </div>
                    </div>

                    <br class="clearfix" />

                </div>
            </div>
        </article>

    </div>
</div>

<?php include_once(INCLUDES_PATH . '/footer.php'); ?>
