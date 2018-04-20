<?php

/*
 * @author      Henrique Rodrigues <henrique@hostnet.com.br>
 *
 * @link        https://www.hostnet.com.br
 *
 */

namespace MauticPlugin\HostnetAuthBundle\Controller;

use MauticPlugin\HostnetAuthBundle\Helper\AuthenticatorHelper;
use MauticPlugin\HostnetAuthBundle\Entity\AuthBrowser;

use Mautic\CoreBundle\Controller\CommonController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;


use Mautic\PluginBundle\Helper\IntegrationHelper;

class AuthController extends CommonController
{
    public function authAction(Request $request)
    {
        if ($this->isCsrfTokenValid('gauth', $request->request->get('_csrf_token'))) {
            $integrationHelper = $this->get('mautic.helper.integration');
            $myIntegration = $integrationHelper->getIntegrationObject('HostnetAuth');

            $secret = $myIntegration->getGauthSecret();

            $code = $request->request->get('_code');

            $ga = new AuthenticatorHelper();

            if ($ga->checkCode($secret, $code)) {
                $trustBrowser = !!$request->request->get('trust_browser');

                if ($trustBrowser) {
                    $entityManager = $this->getDoctrine()->getManager();

                    $browser = new AuthBrowser();
                    $browser->setUserId($this->get('mautic.helper.user')->getUser()->getId());
                    $browser->setHash($request->request->get('hash'));
                    $browser->setDateAdded(date('Y-m-d H:i:s'));

                    $entityManager->persist($browser);

                    $entityManager->flush();
                }

                $this->get('session')->set('gauth_granted', true);

                $response =  new RedirectResponse('dashboard');
                $response->headers->setCookie(
                    new Cookie(
                        'plugin_browser_hash',
                        $request->request->get('hash'),
                        (new \DateTime())->add(new \DateInterval("P{$myIntegration->getCookieDuration()}D"))
                    )
                );

                return $response;
            } else {
                $this->addFlash($this->translator->trans('mautic.plugin.auth.invalid'), [], 'error', null, false);
            }
        }

        return $this->delegateView([
            'contentTemplate' => 'HostnetAuthBundle:AuthView:form.html.php',
            'viewParameters' => [

            ]
        ]);
    }
}
