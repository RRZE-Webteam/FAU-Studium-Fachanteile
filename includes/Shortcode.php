<?php

namespace Fau\DegreeProgram\Shares;

class Shortcode
{
    public function __construct()
    {
        add_shortcode('fachanteile', [$this, 'shortcodeOutput']);
        add_shortcode('subject-share', [$this, 'shortcodeOutput']);
    }

    public function shortcodeOutput($atts)
    {
        $args = shortcode_atts(
            ['subject' => '',
             'degree' => '',],
            $atts);
        $subject = (int)$args['subject'];
        $degree = (int)$args['degree'];

        if ($subject == 0 || $degree == 0) {
            return '';
        }

        $api = new API();
        $data = $api->getData($subject, $degree);
        /*print "<pre>";
        var_dump($data);
        //echo array_sum(array_column($data, 'percent'));
        print "</pre>";*/

        if (empty($data)) return '';

        $legend = '';

        /*$output = '<table class="fau-subject-shares">';
        foreach ($data as $item) {
            $output .= '<tr>'
                . '<td>' . $item['share'] . '</td>'
                . '<td>' . number_format($item['percent'] * 100, 2) . '%' . '</td>'
                . '</tr>';
        }
        $output .= '</table>';*/

        $count = 0;
        $output = '<div id="my-chart" style="width: 300px; float: left; margin-right: 30px;">'
        . '<table class="fau-subject-shares charts-css pie hide-data Xshow-labels">'
        . ' <thead>
            <tr>
              <th scope="col">' . __('Subject', 'fau-degree-program-shares') . '</th>
              <th scope="col">' . __('Share', 'fau-degree-program-shares') . '</th>
            </tr>
          </thead>
          <tbody>';
        foreach ($data as $item) {
            $start = $count;
            $end = $count + $item['percent'];
            $percent = number_format($item['percent'] * 100, 2, _x('.', 'Decimals separator', 'fau-degree-program-shares')) . '%';
            $output .= '<tr>'
                       . '<th scope="row">' . $item['share'] . '</td>'
                       . '<td style="--start: '. $start . '; --end: '. $end . '; --color: ' . $item['color']  . ';">'
                            . '<span class="data">' . $percent . '</span>'
                       . '</td>'
                       . '</tr>';
            $count = $end;
            $legend .= '<li style="--color: ' . $item['color']  . ';">' . $item['share'] . ' (' . $percent . ')' . '</li>';
        }
        $output .= '</tbody></table>';

        $output .='</div>';

        $output .= '<ul class="legend" aria-hidden="true">' . $legend . '</ul>';

        wp_enqueue_style('fau-degree-program-shares');
        wp_enqueue_style('fau-degree-program-shares-charts');

        return $output;
    }

}