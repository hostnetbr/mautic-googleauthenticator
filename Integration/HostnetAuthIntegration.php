<?php

/*
 * @author      Henrique Rodrigues <henrique@hostnet.com.br>
 * @link        https://www.hostnet.com.br
 *
 */

namespace MauticPlugin\HostnetAuthBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Mautic\CoreBundle\Helper\UserHelper;

use MauticPlugin\HostnetAuthBundle\Helper\NotationHelper;
use MauticPlugin\HostnetAuthBundle\Helper\AuthenticatorHelper;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HostnetAuthIntegration extends AbstractIntegration
{

    protected $user;

    protected $status_field;
    protected $secret_field;

    protected $gauth;
    protected $secret;

    public function __construct(UserHelper $user)
    {

        $this->user = $user->getUser();

        $id = $this->user->getID();

        $this->status_field = "scanned_$id";
        $this->secret_field = "secret_$id";
        $this->cookie_field = "cookie_$id";
        $this->gauth = new AuthenticatorHelper();
    }

    public function getName()
    {
        return 'HostnetAuth';
    }

    public function getDisplayName()
    {
        return 'Google Authenticator';
    }

    public function getAuthenticationType()
    {
        return 'none';
    }
}
