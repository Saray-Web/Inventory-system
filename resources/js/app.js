import './bootstrap';

// The lucide CDN build takes `root` (an element or document), not `nodes`.
const renderIcons = (root) => {
    if (!window.lucide || !root) return;
    window.lucide.createIcons({ root });
};

// Lucide replaces each <i data-lucide="..."> placeholder with an <svg>. Livewire's
// morph sees the <svg> vs the incoming <i> as a different element name and swaps
// the icon back out to a bare <i>, which renders as nothing. `morph.updated` is
// useless here: Alpine's morph calls updated(el) *before* patchChildren(el), so the
// placeholders do not exist yet. The `morphed` hook fires once the DOM is final.
const registerLivewireIconHook = () => {
    if (typeof Livewire === 'undefined' || Livewire.__iconsHooked) return;
    Livewire.__iconsHooked = true;

    Livewire.hook('morphed', ({ el, component }) => {
        renderIcons(el ?? component?.el);
    });
};

document.addEventListener('livewire:initialized', registerLivewireIconHook);
registerLivewireIconHook();

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const closeMenu = () => {
        sidebar?.classList.add('-translate-x-full');
        overlay?.classList.add('hidden');
    };

    document.getElementById('menuBtn')?.addEventListener('click', () => {
        sidebar?.classList.toggle('-translate-x-full');
        overlay?.classList.toggle('hidden');
    });
    overlay?.addEventListener('click', closeMenu);

    const notifPanel = document.getElementById('notifPanel');
    document.getElementById('notifBtn')?.addEventListener('click', (event) => {
        event.stopPropagation();
        notifPanel?.classList.toggle('hidden');
    });
    document.addEventListener('click', (event) => {
        if (!notifPanel?.contains(event.target) && !document.getElementById('notifBtn')?.contains(event.target)) {
            notifPanel?.classList.add('hidden');
        }
    });

    const profileButton = document.getElementById('profileMenuBtn');
    const profileMenu = document.getElementById('profileMenu');
    profileButton?.addEventListener('click', (event) => {
        event.stopPropagation();
        const willOpen = profileMenu?.classList.contains('hidden');
        profileMenu?.classList.toggle('hidden');
        profileButton.setAttribute('aria-expanded', String(Boolean(willOpen)));
    });
    document.addEventListener('click', (event) => {
        if (!profileButton?.contains(event.target) && !profileMenu?.contains(event.target)) {
            profileMenu?.classList.add('hidden');
            profileButton?.setAttribute('aria-expanded', 'false');
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            profileMenu?.classList.add('hidden');
            profileButton?.setAttribute('aria-expanded', 'false');
        }
    });

    const root = document.documentElement;
    const setTheme = (dark) => {
        root.classList.toggle('dark', dark);
        document.body.classList.toggle('dark', dark);
        localStorage.setItem('stockflow-theme', dark ? 'dark' : 'light');
        const themeButton = document.getElementById('themeBtn');
        if (themeButton) {
            themeButton.innerHTML = `<i data-lucide="${dark ? 'sun' : 'moon'}" class="h-4 w-4"></i>`;
            renderIcons(themeButton);
        }
    };
    setTheme(localStorage.getItem('stockflow-theme') === 'dark');
    document.getElementById('themeBtn')?.addEventListener('click', () => setTheme(!root.classList.contains('dark')));
});
