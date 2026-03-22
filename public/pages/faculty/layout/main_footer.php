        </div>
    </div>
</div>

<script>
(function () {
    var toggleButton = document.getElementById('facultySidebarToggle');
    if (!toggleButton) {
        return;
    }

    function isMobile() {
        return window.innerWidth < 992;
    }

    function syncAria() {
        var expanded = isMobile()
            ? document.body.classList.contains('faculty-mobile-sidebar-open')
            : !document.body.classList.contains('faculty-sidebar-collapsed');
        toggleButton.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    }

    toggleButton.addEventListener('click', function () {
        if (isMobile()) {
            document.body.classList.toggle('faculty-mobile-sidebar-open');
        } else {
            document.body.classList.toggle('faculty-sidebar-collapsed');
        }
        syncAria();
    });

    document.addEventListener('click', function (event) {
        if (!isMobile()) {
            return;
        }

        var insideSidebar = !!event.target.closest('.faculty-side-panel');
        var clickedToggle = event.target.closest('#facultySidebarToggle');
        if (!insideSidebar && !clickedToggle) {
            document.body.classList.remove('faculty-mobile-sidebar-open');
            syncAria();
        }
    });

    document.addEventListener('click', function (event) {
        var link = event.target.closest('a[href*="/public/pages/Authentication/logout.php"]');
        var preloader = document.getElementById('faculty-site-preloader');

        if (!link || !preloader) {
            return;
        }

        var href = link.getAttribute('href');
        if (!href) {
            return;
        }

        event.preventDefault();
        preloader.classList.remove('is-hidden');
        document.body.classList.add('faculty-preloading');

        window.setTimeout(function () {
            window.location.href = href;
        }, 850);
    });

    window.addEventListener('resize', function () {
        if (!isMobile()) {
            document.body.classList.remove('faculty-mobile-sidebar-open');
        }
        syncAria();
    });

    syncAria();
})();
</script>

<script>
(function () {
    var root = document.documentElement;
    var themeBtn = document.getElementById('facultyThemeToggle');
    var themeIcon = document.getElementById('facultyThemeToggleIcon');
    var themeText = document.getElementById('facultyThemeToggleText');

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
                localStorage.setItem('faculty-theme', next);
            } catch (e) {}
        });
    }
})();
</script>
