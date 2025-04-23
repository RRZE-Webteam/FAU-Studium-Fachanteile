<?php

namespace Fau\DegreeProgram\Shares;

class Shortcode
{
    private $options;

    public function __construct()
    {
        add_shortcode('fachanteile', [$this, 'shortcodeOutput']);
        add_shortcode('subject-shares', [$this, 'shortcodeOutput']);
    }

    public function shortcodeOutput($atts)
    {
        wp_enqueue_style('fau-degree-program-shares');

        $this->options = get_option('fau-degree-program-shares');
        $args = shortcode_atts(
            ['subject' => '',
             'degree' => '',
             'fach' => '',
             'abschluss' => '',
             'format' => 'chart',
             'percent' => '',
             'title' => '',
             'errors' => ''],
            $atts);
        $subject = $args['subject'] != '' ? (int)$args['subject'] : (int)$args['fach'];
        $degree = $args['degree'] != '' ? (int)$args['degree'] : (int)$args['abschluss'];
        $format = in_array($args['format'], array('chart', 'table')) ? $args['format'] : 'chart';
        $showPercent = $args['percent'] == '1';
        $showTitle = $args['title'] == '1';
        $errorOptions = $this->options[ 'show-errors' ] ?? '';
        if ($errorOptions == 'on') {
            $showErrors = true;
        } else {
            $showErrors = $args['errors'] == '1';
        }

        if ($subject == 0) {
            return $showErrors ? sprintf(__('%sError%s: Please specify a subject.%s', 'fau-degree-program-shares'), '<p class="fau-subject-shares-error"><b>', '</b>', '</p>') : '';
        }
        if ($degree == 0) {
            return $showErrors ? sprintf(__('%sError%s: Please specify a degree.%s', 'fau-degree-program-shares'), '<p class="fau-subject-shares-error"><b>', '</b>', '</p>') : '';
        }

        $api = new API();
        $data = $api->getShares($subject, $degree);

        $title = '';
        $subjectName = $api->getSubjects($subject);
        $degreeName = $api->getDegrees($degree);
        if ($showTitle) {
            $title = '<h3 class="chart-title">' . $degreeName[0]['name'] . ' ' . $subjectName[0]['name'] . '</h3>';
        }

        if (empty($data)) {
            return $showErrors ? sprintf(__('%sError%s: No data for degree %s (%s) and subject %s (%s)%s', 'fau-degree-program-shares'), '<p class="fau-subject-shares-error"><b>', '</b>', $subject, $subjectName[0]['name'], $degree, $degreeName[0]['name'], '</p>' ) : '';
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