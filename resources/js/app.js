function syncAdminNav() {
    const el = document.getElementById('admin-mobile-nav');
    const root = document.documentElement;
    if (!el) {
        root.removeAttribute('data-admin-nav');
        return;
    }
    if (el.checked) {
        root.setAttribute('data-admin-nav', 'open');
    } else {
        root.removeAttribute('data-admin-nav');
    }
}

function isAdminMobileLayout() {
    return typeof window !== 'undefined' && window.matchMedia('(max-width: 1023px)').matches;
}

// إغلاق القائمة عند اختيار رابط (تنقّل أسهل على الموبايل)
document.getElementById('admin-nav-panel')?.querySelectorAll('a[href]').forEach((link) => {
    link.addEventListener('click', () => {
        const toggle = document.getElementById('admin-mobile-nav');
        if (toggle) {
            toggle.checked = false;
            syncAdminNav();
        }
    });
});

// مزامنة أيقونة القائمة + قفل التمرير خلف الدرج
const adminNavInput = document.getElementById('admin-mobile-nav');
if (adminNavInput) {
    adminNavInput.addEventListener('change', syncAdminNav);
    syncAdminNav();
}

// Escape: إغلاق القائمة
document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    const toggle = document.getElementById('admin-mobile-nav');
    if (!toggle?.checked) return;
    if (!isAdminMobileLayout()) return;
    toggle.checked = false;
    syncAdminNav();
});

// سحب لإغلاق الدرج (يمين): مناسب لـ RTL مع القائمة من جهة البداية
const adminPanel = document.getElementById('admin-nav-panel');
if (adminPanel) {
    let touchStartX = 0;
    let touchStartY = 0;
    let trackSwipe = false;

    adminPanel.addEventListener(
        'touchstart',
        (e) => {
            if (!isAdminMobileLayout()) return;
            const toggle = document.getElementById('admin-mobile-nav');
            if (!toggle?.checked) return;
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
            trackSwipe = true;
        },
        { passive: true }
    );

    adminPanel.addEventListener(
        'touchend',
        (e) => {
            if (!trackSwipe) return;
            trackSwipe = false;
            if (!isAdminMobileLayout()) return;
            const toggle = document.getElementById('admin-mobile-nav');
            if (!toggle?.checked) return;
            const t = e.changedTouches[0];
            const dx = t.clientX - touchStartX;
            const dy = t.clientY - touchStartY;
            if (Math.abs(dy) > 55) return;
            if (Math.abs(dy) > Math.abs(dx) * 0.85) return;
            if (dx > 64) {
                toggle.checked = false;
                syncAdminNav();
            }
        },
        { passive: true }
    );
}

// توسيع سطح المكتب بعد الموبايل: إعادة تعيين حالة القائمة
window.addEventListener('resize', () => {
    if (!isAdminMobileLayout()) {
        const toggle = document.getElementById('admin-mobile-nav');
        if (toggle?.checked) {
            toggle.checked = false;
            syncAdminNav();
        }
    }
});
