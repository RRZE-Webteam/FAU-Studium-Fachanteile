<?php

// Compatibility with Shortcode
$attributes['subject'] = $attributes['selectedSubject'] ?? '';
$attributes['degree'] = $attributes['selectedDegree'] ?? '';
$attributes['format'] = $attributes['layout'] ?? 'chart';

echo (new Fau\DegreeProgram\Shares\Shortcode)->shortcodeOutput($attributes);

