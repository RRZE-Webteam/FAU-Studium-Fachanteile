/*
 * Source: "Drawing Arcs and Pie Slices with SVG" by Christopher Webb
 * https://www.codedrome.com/drawing-arcs-pie-slices-with-svg/ (2025-02)
 * Adapted for our needs :-)
 */

"use strict"


function drawArc(settings)
{
    let d = "";

    const firstCircumferenceX = settings.centreX + settings.radius * Math.cos(settings.startAngleRadians);
    const firstCircumferenceY = settings.centreY + settings.radius * Math.sin(settings.startAngleRadians);
    const secondCircumferenceX = settings.centreX + settings.radius * Math.cos(settings.startAngleRadians + settings.sweepAngleRadians);
    const secondCircumferenceY = settings.centreY + settings.radius * Math.sin(settings.startAngleRadians + settings.sweepAngleRadians);

    // move to first point
    d += "M" + firstCircumferenceX + "," + firstCircumferenceY + " ";

    // arc
    // Radius X, Radius Y, X Axis Rotation, Large Arc Flag, Sweep Flag, End X, End Y
    d += "A" + settings.radius + "," + settings.radius + " 0 0,1 " + secondCircumferenceX + "," + secondCircumferenceY;

    const arc = document.createElementNS("http://www.w3.org/2000/svg", "path");

    arc.setAttributeNS(null, "d", d);
    arc.setAttributeNS(null, "fill", settings.fillColor);
    arc.setAttributeNS(null, "fill-opacity", settings.fillOpacity);
    arc.setAttributeNS(null, "style", "stroke:" + settings.strokeColour + ";");

    document.getElementById(settings.id).appendChild(arc);
}


function drawPieSlice(settings)
{
    let d = "";

    const firstCircumferenceX = settings.centreX + settings.radius * Math.cos(settings.startAngleRadians);
    const firstCircumferenceY = settings.centreY + settings.radius * Math.sin(settings.startAngleRadians);
    const secondCircumferenceX = settings.centreX + settings.radius * Math.cos(settings.startAngleRadians + settings.sweepAngleRadians);
    const secondCircumferenceY = settings.centreY + settings.radius * Math.sin(settings.startAngleRadians + settings.sweepAngleRadians);

    // move to centre
    d += "M" + settings.centreX + "," + settings.centreY + " ";
    // line to first edge
    d += "L" + firstCircumferenceX + "," + firstCircumferenceY + " ";
    // arc
    // Radius X, Radius Y, X Axis Rotation, Large Arc Flag, Sweep Flag, End X, End Y
    d += "A" + settings.radius + "," + settings.radius + " 0 0,1 " + secondCircumferenceX + "," + secondCircumferenceY + " ";
    // close path
    d += "Z";

    const arc = document.createElementNS("http://www.w3.org/2000/svg", "path");

    arc.setAttributeNS(null, "d", d);
    arc.setAttributeNS(null, "fill", settings.fillColour);
    arc.setAttributeNS(null, "style", "stroke:" + settings.strokeColour + ";");
    arc.setAttributeNS(null, "class", "chart-share");
    arc.setAttributeNS(null, "data-label", settings.label);
    arc.setAttributeNS(null, "data-percent", settings.percent + "%");
    arc.setAttributeNS(null, "title", settings.label + ": " + settings.percent + "%");

    document.getElementById(settings.id).appendChild(arc);

}

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