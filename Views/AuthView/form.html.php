<?php
//load base template
$view->extend('MauticUserBundle:Security:base.html.php');
$view['slots']->set(
    'header',
    $view['translator']->trans('mautic.user.auth.header')
);
?>

<form class="form-group login-form" name="login" role="form" action="" method="post">
    <div class="input-group mb-md">
        <span class="input-group-addon"><i class="fa fa-key"></i></span>
        <label for="password" class="sr-only">Código</label>
        <input type="text" id="password" name="_code" class="form-control input-lg" pattern="[0-9]{6}" required="" placeholder="Google Authenticator Token">
    </div>
    <input type="hidden" name="_csrf_token" value="<?php echo $view['form']->csrfToken('gauth') ?>" />
    <button class="btn btn-lg btn-primary btn-block" type="submit">Validate</button>
</form>