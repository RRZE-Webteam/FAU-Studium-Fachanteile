jQuery(document).ready(function($) {

    let degreeSelect = $('select#fau-degree-programm-shares-degree-select');
    let subjectSelect = $('select#fau-degree-programm-shares-subject-select');
    let shortcodeTemplate = $('code.fau-degree-programm-shares-shortcode-template');

    degreeSelect.change(function() {
        setShortcodeTemplate();
    });

    subjectSelect.change(function() {
        setShortcodeTemplate();
    });

    function setShortcodeTemplate() {
        let degreeID = degreeSelect.val() ;
        if (degreeID === '0') degreeID = '';
        let subjectID = subjectSelect.val();
        if (subjectID === '0') subjectID = '';

        $('#fau-degree-program-shares span.dashicons.dashicons-yes').remove();
        shortcodeTemplate.html('[fachanteile abschluss="' + degreeID + '" fach="' + subjectID + '"]');
    }

    $('button.fau-degree-programm-shares-copy-shortcode').click(function() {
        let copyText = shortcodeTemplate.html();
        navigator.clipboard.writeText(copyText);
        $(this).parent().find('span.dashicons.dashicons-yes').remove();
        $(this).after('<span class="dashicons dashicons-yes" style="font-size: 2em; color: #2271b1"></span>');
    });
});