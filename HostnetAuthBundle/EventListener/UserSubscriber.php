<?php

namespace MauticPlugin\HostnetAuthBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Mautic\CoreBundle\Security\Permissions\CorePermissions;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\HostnetAuthBundle\Entity\AuthBrowserRepository;
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
        if (!$event->isMasterRequest()) {
            return false;
        }

        $myIntegration = $this->integration->getIntegrationObject('HostnetAuth');

        if (!$myIntegration) {
            return false;
        }


        $published = $myIntegration->getIntegrationSettings()->getIsPublished();

        if (!$published || !$myIntegration->isConfigured()) {
            return false;
        }

        $request    = $event->getRequest();
        $requestUri = $request->getRequestUri();
        $userId = $this->user->getUser()->getId();

        $gauthGranted = $this->isSafeBrowser(
            $request->cookies,
            $userId,
            $myIntegration->getCookieDuration()
        )
            ? true
            : $request->getSession()->get('gauth_granted');

        $needVerification = (!$this->security->isAnonymous()) // User logged in
            && !preg_match('/gauth|login|HostnetAuth|api/i', $requestUri) // it's not an authentication url
            && !$gauthGranted // user not authenticated
        ;

        if ($needVerification) {
            $request->getSession()->set('gauth_granted', false);
            $generateUrl = $this->router->generate('hostnet_google_authenticator');
            $event->setResponse(new RedirectResponse($generateUrl));
        }
    }

    public function isSafeBrowser($cookies, $userId, $cookieDuration)
    {
        if (!$cookies->has('plugin_browser_hash')) {
            return false;
        }

        $hash = $cookies->get('plugin_browser_hash');

        $browsers = $this->em->getRepository('HostnetAuthBundle:AuthBrowser')->findBy([
            'user_id' => $userId,
            'hash' => $hash
        ]);

        if (empty($browsers)) {
            return false;
        }

        $currentBrowser = current($browsers);

        $currentDate = new \DateTime();
        $currentDate->setTimezone(new \DateTimeZone('UTC'));

        $cookieAge = $currentDate->diff($currentBrowser->getDateAdded())->format('%d');

        if ($cookieAge > $cookieDuration) {
            return false;
        }

        return true;
    }

    public function kill()
    {
        echo "<pre>";
        print_r(func_get_args());
        exit;
    }
}
