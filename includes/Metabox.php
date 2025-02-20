<?php

namespace Fau\DegreeProgram\Shares;

class Metabox
{
    public function __construct() {
        add_action( 'add_meta_boxes', [$this, 'cmb_add_meta_box'] );
    }
    public function cmb_add_meta_box() {
        add_meta_box(
            'fau-degree-program-shares',
            __('Study Shares Wizard', 'fau-degree-program-shares'),
            [$this, 'cmb_display_meta_box'],
            ['post', 'page'],
            'side',
            'default',
            array(
                '__back_compat_meta_box' => true,
            )

        );

    }

    public function cmb_display_meta_box( $post ) {

        $api = new API();
        $degreesRaw = $api->getDegrees();
        $subjectsRaw = $api->getSubjects();

        $html = '<p><label class="block-label" for="fau-degree-programm-shares-degree-select">' . __('Degree', 'fau-degree-program-shares') . '</label>';
        $html .= '<select id="fau-degree-programm-shares-degree-select">'
                 . '<option value="0">' . __('-- Select --', 'fau-degree-program-shares') . '</option>';
        foreach ( $degreesRaw as $degree ) {
            $html .= '<option value="' . $degree['campo_key'] . '">' . $degree['name'] . '</option>';
        }
        $html .= '</select></p>';

        $html .= '<p><label class="block-label" for="fau-degree-programm-shares-subject-select">' . __('Subject', 'fau-degree-program-shares') . '</label>';
        $html .= '<select id="fau-degree-programm-shares-subject-select">'
                 . '<option value="0">' . __('-- Select --', 'fau-degree-program-shares') . '</option>';
        foreach ( $subjectsRaw as $subject ) {
            $html .= '<option value="' . $subject['campo_key'] . '">' . $subject['name'] . '</option>';
        }
        $html .= '</select></p>';

        $html .= '<p><input type="checkbox" id="fau-degree-programm-shares-percent-check" name="percent" value="1" checked="checked" />'
                 . '<label for="fau-degree-programm-shares-percent-check">' . __('Show Percent Values', 'fau-degree-program-shares') . '</label></p>';

        $html .= '<p>'
                 . '<code class="fau-degree-programm-shares-shortcode-template">[fachanteile abschluss="" fach=""]</code>'
                 . '</p>';

        $html .= '<button class="fau-degree-programm-shares-copy-shortcode button button-secondary">' . __('Copy', 'fau-degree-program-shares') . '</button>';

        echo $html;

    }

}