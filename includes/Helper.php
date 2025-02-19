<?php

namespace Fau\DegreeProgram\Shares;

defined('ABSPATH') || exit;

class Helper {

    /*
     * Source: "Drawing Arcs and Pie Slices with SVG" by Christopher Webb
     * https://www.codedrome.com/drawing-arcs-pie-slices-with-svg/ (as of 2025-02-12)
     * Converted to PHP and adapted for our needs :-)
     */
    public static function drawPieChart($svgID, $data) {
        $svg = '<svg height="300" width="300" viewBox="0 0 512 512" class="chart" id="' . $svgID . '" aria-hidden="true">';
        $total = array_sum(array_column($data, 'percent'));
        $radiansPerUnit = (2 * pi()) / $total;
        $startAngleRadians = 0 - pi() / 2;

        foreach ($data as $item) {
            $sweepAngleRadians = $item[ 'percent' ] * $radiansPerUnit;
            $sliceData = [
                'id' => $svgID,
                'centreX' => 256,
                'centreY' => 256,
                'startAngleRadians' => $startAngleRadians,
                'sweepAngleRadians' => $sweepAngleRadians,
                'radius' => 250,
                'fillColour' => $item[ 'color' ],
                'strokeColour' => '#ffffff',
                'label' => $item[ 'share' ],
                'percent' => $item[ 'percent' ]
            ];
            $svg .= self::drawPieSlice($sliceData);
            $startAngleRadians += $sweepAngleRadians;
        }

        $svg .= '<circle r="100" cx="256" cy="256" fill="#FFFFFF" />';
        $svg .= '</svg>';

        return $svg;
    }

    private static function drawPieSlice($settings) {
        $d = "";

        $firstCircumferenceX = $settings['centreX'] + $settings['radius'] * cos($settings['startAngleRadians']);
        $firstCircumferenceY = $settings['centreY'] + $settings['radius'] * sin($settings['startAngleRadians']);
        $secondCircumferenceX = $settings['centreX'] + $settings['radius'] * cos($settings['startAngleRadians'] + $settings['sweepAngleRadians']);
        $secondCircumferenceY = $settings['centreY'] + $settings['radius'] * sin($settings['startAngleRadians'] + $settings['sweepAngleRadians']);

        // move to centre
        $d .= "M" . $settings['centreX'] . "," . $settings['centreY'] . " ";
        // line to first edge
        $d .= "L" . $firstCircumferenceX . "," . $firstCircumferenceY . " ";
        // arc
        // Radius X, Radius Y, X Axis Rotation, Large Arc Flag, Sweep Flag, End X, End Y
        $d .= "A" . $settings['radius'] . "," . $settings['radius'] . " 0 0,1 " . $secondCircumferenceX . "," . $secondCircumferenceY . " ";
        // close path
        $d .= "Z";

        $arc = "<path"
            . ' d="' . $d . '"'
            . ' fill="' . $settings['fillColour'].'"'
            . ' style="stroke:' . $settings['strokeColour'] . ';"'
            . ' class="chart-share"'
            . ' data-label="' . $settings['label'] . '"'
            . ' data-percent="' . round($settings['percent'] * 100) . '%"'
            . ' title="' . $settings['label'] . ": " . $settings['percent'] . '%"'
            . '></path>';

        return $arc;
    }

}
