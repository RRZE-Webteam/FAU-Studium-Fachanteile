<?php

// Compatibility with Shortcode
$attributes['subject'] = $attributes['selectedSubject'] ?? '';
$attributes['degree'] = $attributes['selectedDegree'] ?? '';
$attributes['format'] = $attributes['format'] ?? 'chart';
$attributes['percent'] = $attributes['showPercent'] ?? '0';
$attributes['title'] = $attributes['showTitle'] ?? '0';

echo (new Fau\DegreeProgram\Shares\Shortcode)->shortcodeOutput($attributes);

