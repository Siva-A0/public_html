<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();

$tbBatch  = TB_BATCH;
$tbClass  = TB_CLASS;
$tbSection = TB_SECTION;

$batches = $fcObj->getBatches($tbBatch);
$classes = $fcObj->getClasses($tbClass);
$currentYear = (int)date('Y');

/* --------- REDIRECT IF LOGGED IN --------- */
if (isset($_SESSION['userName'])) {
    header('Location: ' . BASE_URL . '/');
    exit;
}

/* --------- FORM SUBMIT --------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['err_msg'] = 'Your session expired. Please try again.';
        header('Location: ' . BASE_URL . '/public/pages/Authentication/register.php');
        exit;
    }

    $uName       = trim((string)$_POST['uname']);
    $pass        = (string)$_POST['pword'];
    $cPass       = (string)$_POST['confirmpassword'];
    $fName       = trim((string)$_POST['firstname']);
    $lName       = trim((string)$_POST['lastname']);
    $gender      = trim((string)$_POST['gender']);
    $email       = trim((string)$_POST['email']);
    $address     = trim((string)$_POST['address']);
    $phone       = trim((string)$_POST['phone']);
    $batchId     = (int)($_POST['batchId'] ?? 0);
    $classId     = (int)($_POST['classId'] ?? 0);
    $userTypeInput = strtolower(trim((string)($_POST['user_type'] ?? 'student')));
    $userType = $userTypeInput === 'alumni' ? 'alumni' : 'student';
    $sectionInput = trim((string)($_POST['sectionId'] ?? ''));
    $sectionId   = $sectionInput !== '' && ctype_digit($sectionInput) ? (int)$sectionInput : 0;
    $passoutYearInput = trim((string)($_POST['passout_year'] ?? ''));
    $passoutYear = null;
    $admissionId = trim((string)$_POST['admissionId']);
    $class = $classId;
    $isAlumni = ($userType === 'alumni') ? 1 : 0;

    if ($passoutYearInput !== '') {
        if (preg_match('/^\d{4}$/', $passoutYearInput)) {
            $passoutYear = (int)$passoutYearInput;
        } else {
            $passoutYear = -1;
        }
    }

    if ($userType === 'alumni' || strtoupper($sectionInput) === 'PASSED_OUT') {
        $isAlumni = 1;
    }

    if ($uName === '' || $pass === '' || $fName === '' || $lName === '' || $gender === '' || $email === '' || $address === '' || $phone === '' || $admissionId === '') {
        $_SESSION['err_msg'] = 'Please fill all required fields.';
    } elseif ($pass !== $cPass) {
        $_SESSION['err_msg'] = 'Passwords do not match';
    } elseif (strlen($pass) < 8) {
        $_SESSION['err_msg'] = 'Password must be at least 8 characters long.';
    } elseif ($batchId <= 0) {
        $_SESSION['err_msg'] = 'Please select a valid Batch.';
    } elseif ($isAlumni === 1 && ($passoutYear === -1 || $passoutYear === null || $passoutYear < 1900 || $passoutYear > ($currentYear + 1))) {
        $_SESSION['err_msg'] = 'Please enter a valid passout year.';
    } elseif ($isAlumni !== 1 && ($class <= 0 || $sectionId <= 0)) {
        $_SESSION['err_msg'] = 'Please select a valid Class and Section.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['err_msg'] = 'Please enter a valid email address.';
    } else {

        $fileName = '';
        $uploadName = isset($_FILES['usrImage']['name']) ? $_FILES['usrImage']['name'] : '';
        $uploadTmp = isset($_FILES['usrImage']['tmp_name']) ? $_FILES['usrImage']['tmp_name'] : null;
        if (!empty($uploadName) && $uploadTmp !== null) {
            $safeAdmissionId = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$admissionId);
            $uploadDir = ROOT_PATH . '/public/assets/images/users/';
            $uploadError = '';
            $fileName = app_store_uploaded_image($_FILES['usrImage'], $uploadDir, 'user_' . $safeAdmissionId, $uploadError, 2 * 1024 * 1024);
            if ($fileName === '') {
                $_SESSION['err_msg'] = $uploadError;
            }
        }

        if (!isset($_SESSION['err_msg'])) {
            $sectionToStore = $isAlumni === 1 ? null : (string)(int)$sectionId;
            $varArray = [
                'username'      => $uName,
                'password'      => $fcObj->hashPassword($pass),
                'mail_id'       => $email,
                'firstname'     => $fName,
                'lastname'      => $lName,
                'gender'        => $gender,
                'address'       => $address,
                'mobile_no'     => $phone,
                'batch_id'      => $batchId,
                'section'       => $sectionToStore,
                'admission_id'  => $admissionId,
                'image'         => $fileName,
                'status'        => 0,
                'user_type'     => $isAlumni === 1 ? 'alumni' : 'student',
                'passout_year'  => $isAlumni === 1 ? (string)$passoutYear : null
            ];

            $tbUser = TB_USERS;
            $register = $fcObj->regUser($tbUser, $varArray);

            if ($register == 1) {
                $_SESSION['success_msg'] = 'Registration successful. Admin approval required. Please login after approval.';
                app_rotate_csrf_token();
                header('Location: ' . BASE_URL . '/public/pages/Authentication/login.php');
                exit;
            } else {
                if ($fileName !== '') {
                    $uploadedFilePath = ROOT_PATH . '/public/assets/images/users/' . $fileName;
                    if (is_file($uploadedFilePath)) {
                        @unlink($uploadedFilePath);
                    }
                }
                $_SESSION['err_msg'] = is_string($register) ? $register : 'Registration failed. Please verify your details and try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | AIML Department</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/register.css">

    
</head>

<body>

<div class="auth-shell">
    <div class="auth-frame auth-frame-wide">
        <header class="auth-header">
            <div class="brand-mark">AIML Department</div>
            <div class="auth-switch" role="tablist" aria-label="Authentication pages">
                <a href="<?php echo BASE_URL; ?>/public/pages/Authentication/register.php" class="switch-link active" aria-current="page">Sign up</a>
                <a href="<?php echo BASE_URL; ?>/public/pages/Authentication/login.php" class="switch-link">Login</a>
            </div>
        </header>

        <main class="auth-panel">
            <div class="auth-back-row">
                <a class="btn btn-sm btn-outline-secondary auth-back-btn" href="<?php echo BASE_URL; ?>/">
                    &larr; Back
                </a>
            </div>
            <div class="auth-copy">
                <h2 class="auth-title">Create your department account</h2>
                <p class="auth-subtitle">Fill in your details to complete registration (admin approval required).</p>
            </div>

            <?php if (isset($_SESSION['err_msg'])) { ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['err_msg']; unset($_SESSION['err_msg']); ?>
                </div>
            <?php } ?>

            <form method="POST" enctype="multipart/form-data" class="register-form">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-section">
                    <h6>Account</h6>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <input type="text" name="uname" class="form-control modern-input" placeholder="Username" required>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="admissionId" class="form-control modern-input" placeholder="Roll No" required>
                        </div>
                        <div class="col-sm-6">
                            <input type="password" name="pword" class="form-control modern-input" placeholder="Password" required>
                        </div>
                        <div class="col-sm-6">
                            <input type="password" name="confirmpassword" class="form-control modern-input" placeholder="Confirm Password" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h6>Personal</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="firstname" class="form-control modern-input" placeholder="First Name" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="lastname" class="form-control modern-input" placeholder="Last Name" required>
                        </div>
                        <div class="col-md-4">
                            <select name="gender" class="form-select modern-input" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input type="email" name="email" class="form-control modern-input" placeholder="Email Address" required>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="phone" class="form-control modern-input" placeholder="Phone Number" required>
                        </div>
                        <div class="col-12">
                            <input type="text" name="address" class="form-control modern-input" placeholder="Home Address" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h6>Academic</h6>
                    <div class="row g-3">
                        <div class="col-sm-6 col-lg-4">
                            <select name="batchId" class="form-select modern-input" required>
                                <option value="">Academic Batch</option>
                                <?php foreach ($batches as $b) { ?>
                                    <option value="<?= (int)$b['id']; ?>"><?= htmlspecialchars((string)$b['batch'], ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <select name="user_type" id="userType" class="form-select modern-input">
                                <option value="student">Student</option>
                                <option value="alumni">Alumni</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-lg-4" id="classWrap">
                            <select name="classId" id="classId" class="form-select modern-input">
                                <option value="">Year/Semester</option>
                                <?php foreach ($classes as $c) { ?>
                                    <option value="<?= (int)$c['id']; ?>">
                                        <?= htmlspecialchars((string)$c['class_name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-6 col-lg-4" id="sectionWrap">
                            <select name="sectionId" id="sectionId" class="form-select modern-input">
                                <option value="">Section</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-lg-4 d-none" id="passoutYearWrap">
                            <input type="number" min="1900" max="<?php echo $currentYear + 1; ?>" name="passout_year" id="passoutYear" class="form-control modern-input" placeholder="Passout Year">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h6>Profile Photo</h6>
                    <input type="file" name="usrImage" class="form-control modern-input file-input">
                </div>

                <button type="submit" name="submit" class="btn create-btn w-100">
                    Create My Account
                </button>
            </form>
        </main>
    </div>
</div>

<script>
function syncRegistrationMode() {
    var userTypeEl = document.getElementById('userType');
    var classEl = document.getElementById('classId');
    var classWrap = document.getElementById('classWrap');
    var sectionEl = document.getElementById('sectionId');
    var sectionWrap = document.getElementById('sectionWrap');
    var passoutYearWrap = document.getElementById('passoutYearWrap');
    var passoutYearEl = document.getElementById('passoutYear');
    var isAlumni = userTypeEl && userTypeEl.value === 'alumni';

    if (classEl) {
        classEl.required = !isAlumni;
        classEl.disabled = isAlumni;
        if (isAlumni) {
            classEl.value = '';
        }
    }
    if (classWrap) {
        classWrap.classList.toggle('opacity-50', isAlumni);
    }
    if (sectionEl) {
        sectionEl.required = !isAlumni;
        sectionEl.disabled = isAlumni;
        if (isAlumni) {
            sectionEl.value = '';
            sectionEl.innerHTML = '<option value="">Section</option>';
        }
    }
    if (sectionWrap) {
        sectionWrap.classList.toggle('opacity-50', isAlumni);
    }
    if (passoutYearWrap) {
        passoutYearWrap.classList.toggle('d-none', !isAlumni);
    }
    if (passoutYearEl) {
        passoutYearEl.required = isAlumni;
        if (!isAlumni) {
            passoutYearEl.value = '';
        }
    }
}

function loadSections() {
    var batchEl = document.querySelector('select[name="batchId"]');
    var classEl = document.querySelector('select[name="classId"]');
    var sectionEl = document.getElementById('sectionId');
    var userTypeEl = document.getElementById('userType');
    if (!batchEl || !classEl || !sectionEl) {
        return;
    }

    var batchId = parseInt(batchEl.value || '0', 10);
    var classId = parseInt(classEl.value || '0', 10);

    sectionEl.innerHTML = '<option value="">Section</option>';
    sectionEl.disabled = true;

    if (userTypeEl && userTypeEl.value === 'alumni') {
        return;
    }

    if (!batchId || !classId) {
        return;
    }

    fetch('<?php echo BASE_URL; ?>/public/pages/Authentication/sections.php?batchId=' + encodeURIComponent(batchId) + '&classId=' + encodeURIComponent(classId), {
        credentials: 'same-origin'
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data || !data.ok || !Array.isArray(data.sections)) {
                return;
            }
            data.sections.forEach(function (sec) {
                var opt = document.createElement('option');
                opt.value = String(sec.id);
                opt.textContent = (sec.name || sec.code || ('Section ' + sec.id));
                sectionEl.appendChild(opt);
            });
        })
        .catch(function () {})
        .finally(function () {
            sectionEl.disabled = false;
        });
}

document.addEventListener('change', function (e) {
    if (!e || !e.target) return;
    if (e.target.name === 'user_type') {
        syncRegistrationMode();
        loadSections();
    }
    if (e.target.name === 'batchId' || e.target.name === 'classId') {
        loadSections();
    }
});

document.addEventListener('DOMContentLoaded', function () {
    syncRegistrationMode();
    loadSections();
});

document.querySelectorAll(".switch-link").forEach((link) => {
    link.addEventListener("click", (event) => {
        const target = link.getAttribute("href");
        if (!target || target === window.location.href) {
            return;
        }

        event.preventDefault();

        const navigate = () => {
            window.location.href = target;
        };

        if (document.startViewTransition) {
            document.startViewTransition(() => {
                navigate();
            });
            return;
        }

        document.body.classList.add("is-switching");
        window.setTimeout(navigate, 220);
    });
});
</script>

</body>




</html>
