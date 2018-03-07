<?php

/*
 * @author      Henrique Rodrigues <henrique@hostnet.com.br>
 *
 * @link        https://www.hostnet.com.br
 *
 */

namespace MauticPlugin\MauticAuthBundle\Controller;

use MauticPlugin\MauticAuthBundle\Helper\AuthenticatorHelper;

use Mautic\CoreBundle\Controller\CommonController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Mautic\PluginBundle\Helper\IntegrationHelper;

class AuthController extends CommonController
{
    public function authAction(Request $request)
    {
        if ($this->isCsrfTokenValid('gauth', $request->request->get('_csrf_token'))) {
            $integrationHelper = $this->get('mautic.helper.integration');
            $myIntegration = $integrationHelper->getIntegrationObject('Auth');

            $secret = $myIntegration->getGauthSecret();

            $code = $request->request->get('_code');

            $ga = new AuthenticatorHelper();

            if ($ga->checkCode($secret, $code)) {
                $this->get('session')->set('gauth_granted', true);
                return new RedirectResponse('dashboard');
            } else {
                $this->addFlash('Invalid code. Please try again.', [], 'error', null, false);
            }
        }

        return $this->delegateView([
            'contentTemplate' => 'MauticAuthBundle:AuthView:form.html.php',
            'viewParameters' => [

            ]
        ]);
    }
}
