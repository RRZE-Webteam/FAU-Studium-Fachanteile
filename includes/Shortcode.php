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
        $tableClass = 'fau-subject-shares';
        if ($format == 'chart') {
            $tableClass .= ' sr-only';
        }

        if ($subject == 0 || $degree == 0) {
            return '';
        }

        $api = new API();
        $data = $api->getShares($subject, $degree);

        if (empty($data)) return '';

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

        if ($format == 'chart') {
            $aSeries = [];
            $aColors = [];
            $aLabels = [];
            foreach ($data as $item) {
                $aSeries[] = number_format($item[ 'percent' ] * 100, 2);
                $aColors[] = $item[ 'color' ];
                $aLabels[] = $item[ 'share' ];
            }
            $sSeries = '[' . implode(',', $aSeries) . ']';
            $sColors = "['" . implode("','", $aColors) . "']";
            $sLabels = "['" . implode("','", $aLabels) . "']";

            $chartID = 'chart_' . $subject . '-' . $degree . '_' . rand(0, 9999);
            $output  .= '<div id="' . $chartID . '" aria-hidden="true"></div>';
            $script  = "
            <script>             
                var options = {
                    series: $sSeries,
                    labels: $sLabels,
                    legend: {
                        show: true,
                        fontSize: '15px',
                        width: 300,
                        height: undefined,
                    },
                    tooltip: {
                        fillSeriesColor: false,
                        theme: 'dark',
                    },
                    chart: {
                      type: 'donut',
                    },
                    colors:$sColors,
                    plotOptions: {
                      pie: {
                        size: 100,
                        donut: {
                            size: '50%',
                        }
                      }
                    },
                    /*responsive: [{
                      breakpoint: 480,
                      options: {
                        chart: {
                          width: 200
                        },
                        legend: {
                          position: 'bottom'
                        }
                      }
                    }]*/
                };
                var chart = new ApexCharts(document.querySelector(\"#$chartID\"), options);
                chart.render();
            </script>";

            wp_enqueue_script('fau-degree-program-shares-apexcharts');
            wp_enqueue_style('fau-degree-program-shares-apexcharts');
            add_action('wp_footer', function () use ($script) { echo $script; }, 99 );
        }

        wp_enqueue_style('fau-degree-program-shares');

        return $output;
    }

}