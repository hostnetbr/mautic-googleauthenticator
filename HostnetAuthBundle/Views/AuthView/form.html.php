<?php
// load base template
$view->extend('MauticUserBundle:Security:base.html.php');
$view['slots']->set(
    'header',
    $view['translator']->trans('mautic.user.auth.header')
);
?>

<form class="form-group login-form" name="gauth" id="gauth" role="form" action="" method="post">
    <div class="input-group mb-md">
        <span class="input-group-addon">
            <i class="fa fa-key"></i>
        </span>
        <label for="password" class="sr-only"><?=$view['translator']->trans('mautic.plugin.auth.code');?></label>
        <input type="text" id="password" name="_code" class="form-control input-lg" pattern="[0-9]{6}" required="" placeholder="<?=$view['translator']->trans('mautic.plugin.auth.placeholder');?>">
    </div>
    <div class="input-group mb-md">
        <div class="checkbox-inline custom-primary">
            <label class="mb-0">
                <input type="checkbox" name="trust_browser" id="browser" value="1" />
                <span class="mr-0"></span>
                <?=$view['translator']->trans('mautic.plugin.auth.remember_me');?>
            </label>
        </div>
    </div>
    <input type="hidden" name="_csrf_token" value="<?php echo $view['form']->csrfToken('gauth') ?>" />
    <input type="hidden" name="hash" id="hash" />
    <button class="btn btn-lg btn-primary btn-block" type="submit"><?=$view['translator']->trans('mautic.plugin.auth.submit');?></button>
</form>

<script src="<?= $view['assets']->getUrl('plugins/HostnetAuthBundle/Assets/js/afingerprint2.min.js'); ?>"></script>
<script src="<?= $view['assets']->getUrl('plugins/HostnetAuthBundle/Assets/js/gauth.js'); ?>?t=<?=time()?>"></script>
