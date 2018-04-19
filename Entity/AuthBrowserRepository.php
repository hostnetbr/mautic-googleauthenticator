<?php

namespace MauticPlugin\HostnetAuthBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * AuthBrowserRepository
 */
class AuthBrowserRepository extends CommonRepository
{
    public function getEntities(array $args = [])
    {
        $q = $this
            ->createQueryBuilder('b')
            ->leftJoin('a.category', 'c');

        $args['qb'] = $q;

        return parent::getEntities($args);
    }
}
