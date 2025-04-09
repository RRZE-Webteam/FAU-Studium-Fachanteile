jQuery(document).ready(function($) {
    $( function() {
        // tooltip
        function onHoverToggleTooltip( e ) {
            var 	$this	= $( this ),
                //title	= $this.attr( 'title' ),
                title = '<span class="tooltip-label">' + $this.data('label') + '</span> <span class="tooltip-percent">(' + $this.data('percent') + ')</span>',
                type	= e.type,
                offset 	= $this.offset(),
                xOffset = e.pageX - offset.left + 10,
                yOffset = e.pageY - offset.top + 30,
                tooltip = $(this).parents('div.fau-subject-shares').find('div.tooltip');
            if( type == 'mouseenter' ) {
                tooltip.append(title)
                    .hide().fadeIn(250)
                    .css( 'top', ( yOffset ) + 'px' )
                    .css( 'left', ( xOffset ) + 'px' );
            } else if ( type == 'mouseleave' ) {
                tooltip.fadeOut().empty();
            } else if ( type == 'mousemove' ) {
                tooltip
                    .css( 'top', ( yOffset ) + 'px' )
                    .css( 'left', ( xOffset ) + 'px' );
            }
        }

        $( document.querySelectorAll( '.chart-share' ) ).on({
            mouseenter: onHoverToggleTooltip,
            mouseleave: onHoverToggleTooltip,
            mousemove: onHoverToggleTooltip
        });

    });
});