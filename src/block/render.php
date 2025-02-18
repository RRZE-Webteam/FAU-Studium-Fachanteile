<?php

// Compatibility with Shortcode
$attributes['subject'] = $attributes['selectedSubject'] ?? '';
$attributes['degree'] = $attributes['selectedDegree'] ?? '';
$attributes['layout'] = $attributes['layout'] ?? 'chart';

echo wp_kses_post((new Fau\DegreeProgram\Shares\Shortcode)->shortcodeOutput($attributes));