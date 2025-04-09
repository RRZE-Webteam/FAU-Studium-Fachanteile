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
             'format' => 'chart',
             'percent' => '',
             'title' => ''],
            $atts);
        $subject = $args['subject'] != '' ? (int)$args['subject'] : (int)$args['fach'];
        $degree = $args['degree'] != '' ? (int)$args['degree'] : (int)$args['abschluss'];
        $format = in_array($args['format'], array('chart', 'table')) ? $args['format'] : 'chart';
        $showPercent = $args['percent'] == '1';
        $showTitle = $args['title'] == '1';

        if ($subject == 0 || $degree == 0) {
            return '';
        }

        $api = new API();
        $data = $api->getShares($subject, $degree);

        $title = '';
        if ($showTitle) {
            $subjectName = $api->getSubjects($subject);
            $degreeName = $api->getDegrees($degree);
            $title = '<h3 class="chart-title">' . $degreeName[0]['name'] . ' ' . $subjectName[0]['name'] . '</h3>';
        }

        if (empty($data)) {
       //     error_log('No data for subject ' . $subject. ' '.$subjectName.' and degree '.$degree. ' '.$degreeName );
            return '';
        }
        $rand = rand(0, 9999);
        $output = '<div class="fau-subject-shares" id="fau-subject-shares-' . $rand . '">'
            . $title;

        if ($format == 'table') {
            $output .= $this->renderTable($data);
        } else {
            $output .= $this->renderChart($data, $subject, $degree, $rand, $showPercent);
        }

        $output .= '</div>';

        wp_enqueue_style('fau-degree-program-shares');
        wp_enqueue_script('fau-degree-program-shares');

        return $output;
    }

    private function renderTable($data) {
        $output = '<table class="shares-table">'
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

    private function renderChart($data, $subject, $degree, $rand, $showPercent) {

        $output = '';

        $legend = '<ul class="chart-legend">';
        foreach ($data as $item) {
            $legend .= '<li style="--share-color: ' . $item[ 'color' ] . '">' . $item[ 'share' ] . ($showPercent ? ' (' . number_format($item[ 'percent' ] * 100, 0) . '%)' : '') . '</li>';
        }
        $legend .= '</ul>';

        $svgID = 'svg_' . $subject . '-' . $degree . '_' . $rand;
        $output .= Helper::drawPieChart($svgID, $data);
        $output .= '<div class="tooltip"></div>';
        $output .= $legend;

        return $output;

        /*
         * TODO:
         *  - JS für hover in der Legende
         */
    }
}