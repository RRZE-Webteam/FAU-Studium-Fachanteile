jQuery(document).ready(function($) {
    $( function() {
        // tooltip
        function showTooltip(evt) {
            let tooltip = $(this).parents('div.fau-subject-shares').find('div.tooltip');
            tooltip.append('<span class="tooltip-label">' + $(this).data('label') + '</span> <span class="tooltip-percent">(' + $(this).data('percent') + ')</span>')
                .css('display', 'block')
                .css('left', evt.offsetX + 'px')
                .css('top', evt.offsetY + 'px');
        }

        function moveTooltip(evt) {
            let tooltip = $(this).parents('div.fau-subject-shares').find('div.tooltip');
            tooltip.css('left', evt.offsetX + 'px')
                .css('top', evt.offsetY + 'px');
        }

        function hideTooltip() {
            let tooltip = $(this).parents('div.fau-subject-shares').find('div.tooltip');
            tooltip.css('display', 'none')
            tooltip.empty()
        }

        $( document.querySelectorAll( '.chart-share' ) ).on({
            mouseenter: showTooltip,
            mouseleave: hideTooltip,
            mousemove: moveTooltip
        });

    });
});