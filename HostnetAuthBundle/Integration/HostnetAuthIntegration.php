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

    /**
     * Return's authentication method such as oauth2, oauth1a, key, etc.
     *
     * @return string
     */
    public function getAuthenticationType()
    {
        return 'none';
    }

    /**
     * Return array of key => label elements that will be converted to inputs to
     * obtain from the user.
     *
     * @return array
     */
    public function getRequiredKeyFields()
    {
        return [
        ];
    }

    /**
     * @param FormBuilder|Form $builder
     * @param array            $data
     * @param string           $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ($formArea === 'keys') {
            $builder
                ->add(
                    $this->status_field,
                    'yesno_button_group',
                    [
                        'label' => 'mautic.integration.auth.scanned',
                        'data'  => $this->isConfigured(),
                        'attr'  => [
                            'tooltip' => 'You must scan the code with your phone to use the plugin.',
                        ],
                    ]
                )
                ->add(
                    $this->cookie_field,
                    'text',
                    [
                        'label' => 'mautic.integration.auth.cookie_duration',
                        'data'  => $this->getCookieDuration(),
                        'attr'  => [
                            'tooltip' => 'You won\'t be prompted for codes in trusted browsers',
                            'class' => 'form-control'
                        ],
                    ]
                )
                ->add(
                    $this->secret_field,
                    'hidden',
                    [
                        'data'  => $this->getGauthSecret()
                    ]
                );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param $section
     *
     * @return string|array
     */
    public function getFormNotes($section)
    {
        if ('custom' === $section) {
            $url = $this->router->generate(
                'mautic_dashboard_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $url = preg_replace('/http[s]?:\/\/|\/s\/dashboard/i', '', $url);

            return [
                'template'   => 'HostnetAuthBundle:Integration:form.html.php',
                'parameters' => [
                    'secret' => $this->secret,
                    'qrUrl' => $this->gauth->getURL(
                        $this->user->getUsername(),
                        $url,
                        $this->secret
                    )
                ],
            ];
        }

        return parent::getFormNotes($section);
    }

    public function getGauthSecret()
    {
        $featureSettings = $this->getKeys();

        $this->secret = isset($featureSettings[$this->secret_field])
            ? $featureSettings[$this->secret_field]
            : $this->gauth->generateSecret();

        return $this->secret;
    }

    public function isConfigured()
    {
        $featureSettings = $this->getKeys();

        return isset($featureSettings[$this->status_field])
            ? (bool) $featureSettings[$this->status_field]
            : false;
    }

    public function getCookieDuration()
    {
        $featureSettings = $this->getKeys();

        return isset($featureSettings[$this->cookie_field])
            ? $featureSettings[$this->cookie_field]
            : 30;
    }
}
