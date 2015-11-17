<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * App
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AppRepository")
 */
class App
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
     * @ORM\Column(name="guid", type="guid", unique=true)
     */
    private $guid;

    /**
     * @var string
     *
     * @Gedmo\Slug()
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Token", mappedBy="app", cascade={"remove"})
     */
    private $tokens;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tokens = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set guid
     *
     * @param guid $guid
     *
     * @return App
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return guid
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return App
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add token
     *
     * @param \AppBundle\Entity\Token $token
     *
     * @return App
     */
    public function addToken(\AppBundle\Entity\Token $token)
    {
        $this->tokens[] = $token;

        return $this;
    }

    /**
     * Remove token
     *
     * @param \AppBundle\Entity\Token $token
     */
    public function removeToken(\AppBundle\Entity\Token $token)
    {
        $this->tokens->removeElement($token);
    }

    /**
     * Get tokens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTokens()
    {
        return $this->tokens;
    }
}
