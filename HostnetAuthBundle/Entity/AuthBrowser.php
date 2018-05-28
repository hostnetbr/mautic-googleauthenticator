<?php

namespace MauticPlugin\HostnetAuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CategoryBundle\Entity\Category;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\CommonEntity;

/**
 * Class World
 */
class AuthBrowser extends CommonEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $user_id;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var DateTime
     */
    private $date_added;

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('plugin_auth_browsers')
            ->setCustomRepositoryClass('MauticPlugin\HostnetAuthBundle\Entity\AuthBrowserRepository');

        $builder->addIdColumns('user_id', 'hash');
        $builder->addField('date_added', 'datetime');
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return AuthBrowser
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return AuthBrowser
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     *
     * @return AuthBrowser
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int
     *
     * @return AuthBrowser
     */
    public function setUserId(int $user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string
     *
     * @return AuthBrowser
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return datetime
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * @param DateTime
     *
     * @return AuthBrowser
     */
    public function setDateAdded($date_added)
    {
        $this->date_added = $date_added;
        return $this;
    }
}
