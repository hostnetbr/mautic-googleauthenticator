<?php

namespace MauticPlugin\MauticAuthBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Mautic\CoreBundle\Security\Permissions\CorePermissions;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticAuthbundle\Entity\AuthBrowserRepository;
use Mautic\CoreBundle\Helper\UserHelper;

/**
 * Class UserSubscriber
 *
 * @author Henrique Rodrigues <henrique@hostnet.com.br>
 *
 * @link https://www.hostnet.com.br
 *
 */
class UserSubscriber extends CommonSubscriber
{
    protected $router;
    protected $security;
    protected $session;
    protected $integration;

    /**
     * UserSubscriber constructor.
     *
     * @param RouterInterface  $router
     */
    public function __construct(
        RouterInterface $router,
        CorePermissions $security,
        IntegrationHelper $integration,
        UserHelper $user
    ) {
        $this->router = $router;
        $this->security = $security;
        $this->integration = $integration;
        $this->user = $user;
    }

    /**orm
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 0],
        ];
    }

    /**
     * verifies if the user is authenticated and gives the right response
     *
     * @param GetResponseEvent $event
     * @return void
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->isMasterRequest()) {
            $myIntegration = $this->integration->getIntegrationObject('Auth');

            if ($myIntegration) {
                $published = $myIntegration->getIntegrationSettings()->getIsPublished();

                if ($published && $myIntegration->isConfigured()) {
                    $request    = $event->getRequest();
                    $requestUri = $request->getRequestUri();
                    $userId = $this->user->getUser()->getId();

                    $gauthGranted = $this->isSafeBrowser($request->cookies, $userId)
                        ? true
                        : $request->getSession()->get('gauth_granted');

                    $needVerification = (!$this->security->isAnonymous()) // User logged in
                        && !preg_match('/gauth|login/i', $requestUri) // it's not an authentication url
                        && !$gauthGranted // user not authenticated
                    ;

                    if ($needVerification) {
                        $request->getSession()->set('gauth_granted', false);
                        $generateUrl = $this->router->generate('mautic_gauth_test');
                        $event->setResponse(new RedirectResponse($generateUrl));
                    }
                }
            }
        }
    }

    public function isSafeBrowser($cookies, $userId)
    {
        if (!$cookies->has('plugin_browser_hash')) {
            return false;
        }

        $hash = $cookies->get('plugin_browser_hash');

        $browsers = $this->em->getRepository('MauticAuthBundle:AuthBrowser')->findBy([
            'user_id' => $userId,
            'hash' => $hash
        ]);

        return !empty($browsers);
    }

    public function kill()
    {
        echo "<pre>";
        print_r(func_get_args());
        exit;
    }
}
