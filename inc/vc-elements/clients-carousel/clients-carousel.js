(function () {
    'use strict';

    function initCarousel(root) {
        var viewport = root.querySelector('.fr-clients__viewport');
        var track    = root.querySelector('.fr-clients__track');
        var prevBtn  = root.querySelector('.fr-clients__nav--prev');
        var nextBtn  = root.querySelector('.fr-clients__nav--next');
        if (!viewport || !track || !track.children.length) return;

        var index = 0;

        function visibleCount() {
            var first = track.children[0];
            if (!first) return 1;
            var itemWidth = first.getBoundingClientRect().width;
            if (!itemWidth) return 1;
            return Math.max(1, Math.round(viewport.getBoundingClientRect().width / itemWidth));
        }

        function maxIndex() {
            return Math.max(0, track.children.length - visibleCount());
        }

        function update() {
            var first = track.children[0];
            if (!first) return;
            var w = first.getBoundingClientRect().width;
            index = Math.min(index, maxIndex());
            track.style.transform = 'translateX(' + (-index * w) + 'px)';
            if (prevBtn) prevBtn.disabled = index <= 0;
            if (nextBtn) nextBtn.disabled = index >= maxIndex();
        }

        if (prevBtn) prevBtn.addEventListener('click', function () { index = Math.max(0, index - 1); update(); });
        if (nextBtn) nextBtn.addEventListener('click', function () { index = Math.min(maxIndex(), index + 1); update(); });

        var resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(update, 120);
        });

        update();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.fr-clients__carousel').forEach(initCarousel);
    });
})();
