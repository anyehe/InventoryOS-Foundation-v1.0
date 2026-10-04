(() => {
    const body = document.body;
    const shell = document.getElementById('appShell');
    const sidebar = document.getElementById('sidebar');
    const desktopToggle = document.getElementById('sidebarToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const backdrop = document.getElementById('sidebarBackdrop');
    const navSearch = document.getElementById('navSearch');

    const setCollapsed = (collapsed) => {
        if (!shell) return;
        shell.classList.toggle('sidebar-collapsed', collapsed);
        localStorage.setItem('inventoryos-sidebar', collapsed ? 'collapsed' : 'expanded');
    };

    if (localStorage.getItem('inventoryos-sidebar') === 'collapsed') setCollapsed(true);
    desktopToggle?.addEventListener('click', () => setCollapsed(!shell.classList.contains('sidebar-collapsed')));
    mobileMenu?.addEventListener('click', () => sidebar?.classList.add('open'));
    backdrop?.addEventListener('click', () => sidebar?.classList.remove('open'));

    navSearch?.addEventListener('input', (event) => {
        const query = event.target.value.toLowerCase().trim();
        sidebar?.querySelectorAll('.nav-item').forEach((item) => {
            item.hidden = query !== '' && !(item.dataset.navLabel || '').includes(query);
        });
    });

    document.addEventListener('keydown', (event) => {
        if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
            event.preventDefault();
            document.querySelector('.global-search input')?.focus();
        }
        if (event.key === 'Escape') sidebar?.classList.remove('open');
    });
})();
