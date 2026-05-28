(function () {
    'use strict';

    var GAP_PX     = 10;
    var BG_OPEN    = '#e21f26';
    var BG_CLOSED  = '#f3f3f3';
    var COL_OPEN   = '#ffffff';
    var COL_CLOSED = '#1a1a1a';

    function setPanel(item, open) {
        var panel = item.querySelector('.fr-values__panel');
        var head  = item.querySelector('.fr-values__head');
        var icon  = item.querySelector('.fr-values__icon');
        if (!panel || !head) return;

        if (open) {
            item.classList.add('is-open');
            head.setAttribute('aria-expanded', 'true');
            head.style.background = BG_OPEN;
            head.style.color      = COL_OPEN;
            if (icon) icon.style.transform = 'rotate(-180deg)';
            panel.style.marginTop = GAP_PX + 'px';
            panel.style.maxHeight = panel.scrollHeight + 'px';
        } else {
            item.classList.remove('is-open');
            head.setAttribute('aria-expanded', 'false');
            head.style.background = BG_CLOSED;
            head.style.color      = COL_CLOSED;
            if (icon) icon.style.transform = 'rotate(0deg)';
            panel.style.marginTop = '0px';
            panel.style.maxHeight = '0px';
        }
    }

    function initAccordion(root) {
        var items = root.querySelectorAll('.fr-values__item');
        items.forEach(function (item) {
            if (item.classList.contains('is-open')) {
                requestAnimationFrame(function () { setPanel(item, true); });
            }
            var head = item.querySelector('.fr-values__head');
            if (!head) return;
            head.addEventListener('click', function () {
                var willOpen = !item.classList.contains('is-open');
                items.forEach(function (other) {
                    if (other !== item) setPanel(other, false);
                });
                setPanel(item, willOpen);
            });
        });

        window.addEventListener('resize', function () {
            items.forEach(function (item) {
                if (item.classList.contains('is-open')) setPanel(item, true);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.fr-values__list').forEach(initAccordion);
    });
})();
