jQuery(document).ready(function($) {

    let degreeSelect = $('select#fau-degree-programm-shares-degree-select');
    let subjectSelect = $('select#fau-degree-programm-shares-subject-select');
    let percentCheck = $('input#fau-degree-programm-shares-percent-check');
    let titleCheck = $('input#fau-degree-programm-shares-title-check');
    let shortcodeTemplate = $('code.fau-degree-programm-shares-shortcode-template');

    degreeSelect.change(function() {
        setShortcodeTemplate();
    });

    subjectSelect.change(function() {
        setShortcodeTemplate();
    });

    percentCheck.change(function () {
        setShortcodeTemplate();
    })

    titleCheck.change(function () {
        setShortcodeTemplate();
    })

    function setShortcodeTemplate() {
        let degreeID = degreeSelect.val() ;
        if (degreeID === '0') degreeID = '';
        let subjectID = subjectSelect.val();
        if (subjectID === '0') subjectID = '';
        let showPercent = percentCheck.is(':checked');
        let textPercent = '';
        if (showPercent === true) {
            textPercent = ' percent="1"'
        }
        let showTitle = titleCheck.is(':checked');
        let textTitle = '';
        if (showTitle === true) {
            textTitle = ' title="1"'
        }
//console.log(showPercent);
        $('#fau-degree-program-shares span.dashicons.dashicons-yes').remove();
        shortcodeTemplate.html('[fachanteile abschluss="' + degreeID + '" fach="' + subjectID + '"' + textTitle + textPercent + ']');
    }

    $('button.fau-degree-programm-shares-copy-shortcode').click(function() {
        let copyText = shortcodeTemplate.html();
        navigator.clipboard.writeText(copyText);
        $(this).parent().find('span.dashicons.dashicons-yes').remove();
        $(this).after('<span class="dashicons dashicons-yes" style="font-size: 2em; color: #2271b1"></span>');
    });
});