<?php

$defaultInputClass = (isset($inputClass)) ? $inputClass : 'input';
$containerType     = 'div-wrapper';

include __DIR__.'/../../../../app/bundles/FormBundle/Views/Field/field_helper.php';

$action   = $app->getRequest()->get('objectAction');
$settings = $field['properties'];

$formName    = str_replace('_', '', $formName);
$hashedFormName = md5($formName);
$formButtons = (!empty($inForm)) ? $view->render(
    'MauticFormBundle:Builder:actions.html.php',
    [
        'deleted'        => false,
        'id'             => $id,
        'formId'         => $formId,
        'formName'       => $formName,
        'disallowDelete' => false,
    ]
) : '';

$label = (!$field['showLabel'])
    ? ''
    : <<<HTML
<label $labelAttr>{$view->escape($field['label'])}</label>
HTML;

$jsElement = <<<JSELEMENT
	<script type="text/javascript">
    function verifyCallback_{$hashedFormName}( response ) {
        document.getElementById("mauticform_input_{$formName}_{$field['alias']}").value = response;
    }
    function onLoad{$hashedFormName}() { 
        grecaptcha.execute('{$field['customParameters']['site_key']}', {action: 'form'}).then(function(token) {
            verifyCallback_{$hashedFormName}(token);
         }); 
    }
</script>
JSELEMENT;

if($field['customParameters']['version'] == 'v2') {
$jsElement .= <<<JSELEMENT
<script src='https://www.google.com/recaptcha/api.js'></script>
JSELEMENT;
} else {
$jsElement .= <<<JSELEMENT
<script src='https://www.google.com/recaptcha/api.js?onload=onLoad{$hashedFormName}&render={$field['customParameters']['site_key']}'></script>
JSELEMENT;
}

$html = <<<HTML
    {$jsElement}
	<div $containerAttr>
        {$label}
HTML;

if($field['customParameters']['version'] == 'v2') {
$html .= <<<HTML
<div class="g-recaptcha" data-sitekey="{$field['customParameters']['site_key']}" data-callback="verifyCallback_{$hashedFormName}"></div>
HTML;
}

$html .= <<<HTML
        <input $inputAttr type="hidden">
        <span class="mauticform-errormsg" style="display: none;"></span>
    </div>
HTML;
?>



<?php
echo $html;
?>

