<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Token
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TokenRepository")
 */
class Token
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var guid
     *
     * @ORM\Column(name="authkey", type="string", length=20, unique=true)
     */
    private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string", length=20)
     */
    private $secret;

    /**
     * @ORM\ManyToOne(targetEntity="App", inversedBy="tokens")
     */
    private $app;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set key
     *
     * @param key $key
     *
     * @return Token
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set secret
     *
     * @param string $secret
     *
     * @return Token
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get secret
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set app
     *
     * @param \AppBundle\Entity\App $app
     *
     * @return Token
     */
    public function setApp(\AppBundle\Entity\App $app = null)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * Get app
     *
     * @return \AppBundle\Entity\App
     */
    public function getApp()
    {
        return $this->app;
    }

    public function getCreated()
    {
        return $this->created;
    }
}
