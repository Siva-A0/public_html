        </div>
    </div>
</div>

<script>
(function () {
    var toggleButton = document.getElementById('userSidebarToggle');
    var backButton = document.getElementById('userBackButton');
    if (!toggleButton) {
        return;
    }

    function isMobile() {
        return window.innerWidth < 992;
    }

    function syncAria() {
        var expanded = isMobile()
            ? document.body.classList.contains('user-mobile-sidebar-open')
            : !document.body.classList.contains('user-sidebar-collapsed');
        toggleButton.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    }

    function closeMobileOnNavigate(event) {
        if (!isMobile()) {
            return;
        }

        var link = event.target.closest('.user-side-link');
        if (!link) {
            return;
        }

        document.body.classList.remove('user-mobile-sidebar-open');
        syncAria();
    }

    toggleButton.addEventListener('click', function () {
        if (isMobile()) {
            document.body.classList.toggle('user-mobile-sidebar-open');
        } else {
            document.body.classList.toggle('user-sidebar-collapsed');
        }
        syncAria();
    });

    if (backButton) {
        backButton.addEventListener('click', function () {
            var fallbackUrl = backButton.getAttribute('data-fallback-url') || '/';
            if (window.history && window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = fallbackUrl;
            }
        });
    }

    document.addEventListener('click', function (event) {
        if (!isMobile()) {
            return;
        }

        var insideSidebar = !!event.target.closest('.user-side-panel');
        var clickedToggle = event.target.closest('#userSidebarToggle');
        if (!insideSidebar && !clickedToggle) {
            document.body.classList.remove('user-mobile-sidebar-open');
            syncAria();
        }
    });

    document.addEventListener('click', closeMobileOnNavigate);

    window.addEventListener('resize', function () {
        if (!isMobile()) {
            document.body.classList.remove('user-mobile-sidebar-open');
        }
        syncAria();
    });

    syncAria();
})();
</script>

<script>
(function () {
    var root = document.documentElement;
    var themeBtn = document.getElementById('userThemeToggle');
    var themeIcon = document.getElementById('userThemeToggleIcon');
    var themeText = document.getElementById('userThemeToggleText');

    function updateThemeUI(theme) {
        if (!themeIcon || !themeText) {
            return;
        }
        if (theme === 'dark') {
            themeIcon.className = 'bi bi-sun-fill';
            themeText.textContent = 'Light';
        } else {
            themeIcon.className = 'bi bi-moon-stars-fill';
            themeText.textContent = 'Dark';
        }
    }

    var currentTheme = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    updateThemeUI(currentTheme);

    if (themeBtn) {
        themeBtn.addEventListener('click', function () {
            var current = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            var next = current === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            updateThemeUI(next);
            try {
                localStorage.setItem('user-theme', next);
            } catch (e) {}
        });
    }
})();
</script>
