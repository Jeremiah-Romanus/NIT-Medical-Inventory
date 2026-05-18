<script>
    document.addEventListener('DOMContentLoaded', function () {
        const header = document.querySelector('.shared-site-header');
        const toggle = document.querySelector('.mobile-nav-toggle');
        const sidebar = document.querySelector('.sidebar');
        const backdrop = document.querySelector('.sidebar-backdrop');

        if (toggle && header) {
            toggle.addEventListener('click', function () {
                const navOpen = header.classList.toggle('nav-open');

                if (sidebar && window.innerWidth <= 991.98) {
                    document.body.classList.toggle('sidebar-open');
                }

                toggle.setAttribute('aria-expanded', navOpen || document.body.classList.contains('sidebar-open') ? 'true' : 'false');
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', function () {
                document.body.classList.remove('sidebar-open');
                if (header) {
                    header.classList.remove('nav-open');
                }
                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        }

        document.addEventListener('click', function (event) {
            if (!header || !toggle) {
                return;
            }

            const clickedInsideHeader = header.contains(event.target);
            const clickedSidebar = sidebar && sidebar.contains(event.target);

            if (!clickedInsideHeader && !clickedSidebar) {
                header.classList.remove('nav-open');
                document.body.classList.remove('sidebar-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 991.98) {
                document.body.classList.remove('sidebar-open');
                if (header) {
                    header.classList.remove('nav-open');
                }
                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
    });
</script>
