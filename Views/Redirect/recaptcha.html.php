<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $site_key; ?>"></script>
<script>
    grecaptcha.ready(function () {
        setTimeout(function () {
            grecaptcha.execute('<?php echo $site_key; ?>', {action: 'redirect'}).then(function (token) {
                // compatible with IE7+, Firefox, Chrome, Opera, Safari
                let url = '<?php echo $view['router']->url('mautic_recaptcha_url_validate', ['token' => 'xxxxx', 'ct' => $clickThrough, 'redirectId' => $redirectId]); ?>';
                let xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function(){
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
                        let data = JSON.parse(xmlhttp.response);
                        if (data.success === true) {
                            location.href = '<?php echo $redirectUrl; ?>';
                        }
                    }
                }
                xmlhttp.open("GET", url.replace('xxxxx', token) , true);
                xmlhttp.send();
            });
        }, 300);
    });
</script>