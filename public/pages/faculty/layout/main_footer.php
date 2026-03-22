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
