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
</script>
<script src='https://www.google.com/recaptcha/api.js'></script>
JSELEMENT;

$html = <<<HTML
    {$jsElement}
	<div $containerAttr>
        {$label}
	    <div class="g-recaptcha" data-sitekey="{$field['customParameters']['site_key']}" data-callback="verifyCallback_{$hashedFormName}"></div>
        <input $inputAttr type="hidden">
        <span class="mauticform-errormsg" style="display: none;"></span>
    </div>
HTML;
?>



<?php
echo $html;
?>

