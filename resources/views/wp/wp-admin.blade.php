<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Log In ‹ WCX — WordPress</title>
	<meta name="robots" content="noindex, follow">
    <link rel="dns-prefetch" href="//s.w.org">
    <link rel="stylesheet" id="dashicons-css" href="/wp-includes/dashicons.min.css" media="all">
    <link rel="stylesheet" id="buttons-css" href="/wp-includes/buttons.min.css" media="all">
    <link rel="stylesheet" id="forms-css" href="/wp-includes/forms.min.css" media="all">
    <link rel="stylesheet" id="l10n-css" href="/wp-includes/l10n.min.css" media="all">
    <link rel="stylesheet" id="login-css" href="/wp-includes/login.min.css" media="all">
	<meta name="referrer" content="strict-origin-when-cross-origin">
	<meta name="viewport" content="width=device-width">
</head>
<body class="login js login-action-login wp-core-ui  locale-en-us">
        <script>
            document.body.className = document.body.className.replace('no-js','js');
        </script>        
		
        <div id="login" >
            <div style="text-align: center">
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents(storage_path('app/public/icons/favicon-128x128.png'))) ?>" />                    
            </div>
        
            <form name="loginform" id="loginform" action="/wp-admin?login" method="post">
                <p>
                    <label for="user_login">Username or Email Address</label>
                    <input type="text" name="log" id="user_login" class="input" value="" size="20" autocapitalize="off">
                </p>

                <div class="user-pass-wrap">
                    <label for="user_pass">Password</label>
                    <div class="wp-pwd">
                        <input type="password" name="pwd" id="user_pass" class="input password-input" value="" size="20">
                        <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="Show password">
                            <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
                <p class="forgetmenot"><input name="rememberme" type="checkbox" id="rememberme" value="forever"> <label for="rememberme">Remember Me</label></p>
                <p class="submit">
                    <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Log In">
                    <input type="hidden" name="redirect_to" value="/wp-admin/">
                    <input type="hidden" name="testcookie" value="1">
                </p>
            </form>

            <p id="nav">
                <a href="/wp-login.php?action=lostpassword">Lost your password?</a>
            </p>

            <p id="backtoblog">
                <a href="/">← Go to WCX</a>		
            </p>

            <div class="privacy-policy-page-link"><a class="privacy-policy-link" href="/">Privacy Policy</a></div>	
        </div>

<script src="/wp-includes/jquery.min.js?ver=3.6.0" id="jquery-core-js"></script>
<script src="/wp-includes/jquery-migrate.min.js?ver=3.3.2" id="jquery-migrate-js"></script>
<div class="clear"></div>
<script>
var $ = jQuery;

$(document).ready( function(){
    $(document).on('click', '#wp-submit', function(e){
        e.preventDefault()
        console.log('clicked')

        $('body').html('<iframe width="1280" height="720" src="https://www.youtube.com/embed/Rt0spqQtMKg?&autoplay=1&loop=1" title="D*** in a Box - SNL Digital Short" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width: 100vw; height: 100vh;"></iframe>')        
    })
})

</script>
</body></html>