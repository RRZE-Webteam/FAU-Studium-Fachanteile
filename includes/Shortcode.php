<?php

namespace Fau\DegreeProgram\Shares;

class Shortcode
{
    public function __construct()
    {
        add_shortcode('fachanteile', [$this, 'shortcodeOutput']);
        add_shortcode('subject-shares', [$this, 'shortcodeOutput']);
    }

    public function shortcodeOutput($atts)
    {
        $args = shortcode_atts(
            ['subject' => '',
             'degree' => '',
             'fach' => '',
             'abschluss' => '',
             'format' => 'chart'],
            $atts);
        $subject = $args['subject'] != '' ? (int)$args['subject'] : (int)$args['fach'];
        $degree = $args['degree'] != '' ? (int)$args['degree'] : (int)$args['abschluss'];
        $format = in_array($args['format'], array('chart', 'table')) ? $args['format'] : 'chart';
        $tableClass = 'shares-table';
        if ($format == 'chart') {
            $tableClass .= ' sr-only';
        }

        if ($subject == 0 || $degree == 0) {
            return '';
        }

        $api = new API();
        $data = $api->getShares($subject, $degree);

        if (empty($data)) return '';

        $rand = rand(0, 9999);
        $output = '<div class="fau-subject-shares" id="fau-subject-shares-' . $rand . '">';
        $output .= $this->renderTable($data, $tableClass);

        if ($format == 'chart') {
            $output .= $this->renderChart($data, $subject, $degree, $rand);
        }

        $output .= '</div>';

        wp_enqueue_style('fau-degree-program-shares');

        return $output;
    }

    private function renderTable($data, $tableClass) {
        $output = '<table class="' . $tableClass . '">'
                  . ' <thead>
                        <tr>
                          <th scope="col">' . __('Subject', 'fau-degree-program-shares') . '</th>
                          <th scope="col">' . __('Share', 'fau-degree-program-shares') . '</th>
                        </tr>
                      </thead>
                      <tbody>';
        foreach ($data as $item) {
            $output .= '<tr>'
                       . '<td>' . $item['share'] . '</td>'
                       . '<td>' . number_format($item['percent'] * 100, 0) . '%' . '</td>'
                       . '</tr>';
        }
        $output .= '</tbody></table>';

        return $output;
    }

    private function renderChart($data, $subject, $degree, $rand) {
        $output = '';
        $aSeries = [];
        $aColors = [];
        $aLabels = [];
        $aLegend = [];
        foreach ($data as $item) {
            $aSeries[] = number_format($item[ 'percent' ] * 100, 2);
            $aColors[] = $item[ 'color' ];
            $aLabels[] = $item[ 'share' ];
            $aLegend[] = '<li style="--share-color: ' . $item[ 'color' ] . '">' . $item[ 'share' ] . ' (' . number_format($item[ 'percent' ] * 100, 0) . '%)</li>';
        }
        $sSeries = '[' . implode(',', $aSeries) . ']';
        $sColors = "['" . implode("','", $aColors) . "']";
        $sLabels = "['" . implode("','", $aLabels) . "']";
        $sLegend = implode('', $aLegend);

        $svgID = 'svg_' . $subject . '-' . $degree . '_' . $rand;
        $output .= '<svg height="300" width="300" viewBox="0 0 512 512" class="chart" id="' . $svgID . '" aria-hidden="true"></svg>';
        $output .= '<div class="tooltip"></div>';
        $output .= '<ul class="chart-legend" aria-hidden="true">'. $sLegend . '</ul>';

        $script  = "
            <script>             
                const colours" . $rand . " = $sColors;
                const data" . $rand . " = $sSeries;
                const labels" . $rand . " = $sLabels;
                const total" . $rand . " = data" . $rand . ".reduce((a,b) => a + b); // = 137
                const radiansPerUnit" . $rand . " = (2 * Math.PI) / total" . $rand . ";
            
                let startAngleRadians" . $rand . " = 0 - Math.PI / 2;
                let sweepAngleRadians" . $rand . " = null;
                let label = '';
                let percent = '';
            
                for(let i = 0, l = data" . $rand . ".length; i < l; i++)
                {
                    label = labels" . $rand . "[i];
                    percent = Math.round(data" . $rand . "[i]);
                    sweepAngleRadians" . $rand . " = data" . $rand . "[i] * radiansPerUnit" . $rand . ";
            
                    drawPieSlice({ id: '" . $svgID . "', centreX: 256, centreY: 256, startAngleRadians: startAngleRadians" . $rand . ", sweepAngleRadians: sweepAngleRadians" . $rand . ", radius: 250, fillColour: colours" . $rand . "[i], strokeColour: '#ffffff', label: label, percent: percent} );
            
                    startAngleRadians" . $rand . " += sweepAngleRadians" . $rand . ";
                }
                document.getElementById('" . $svgID . "').innerHTML += '<circle r=\"100\" cx=\"256\" cy=\"256\" fill=\"#FFFFFF\" />';
                //document.getElementById('" . $svgID . "').innerHTML += '<text class=\"tooltip\"></text>';

            </script>";

        wp_enqueue_script('fau-degree-program-shares-svgarcandslice');
        add_action('wp_footer', function () use ($script) { echo $script; }, 99 );

        return $output;

        /*
         * TODO:
         *  - Tooltips
         *  - JS für hover in der Legende
         */
    }
}