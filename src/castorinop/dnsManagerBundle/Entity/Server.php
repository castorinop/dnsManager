<?php

namespace castorinop\dnsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Server
 */
class Server
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $views;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->views = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Server
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add views
     *
     * @param \castorinop\dnsManagerBundle\Entity\ServerView $views
     * @return Server
     */
    public function addView(\castorinop\dnsManagerBundle\Entity\ServerView $views)
    {
        $this->views[] = $views;
    
        return $this;
    }

    /**
     * Remove views
     *
     * @param \castorinop\dnsManagerBundle\Entity\ServerView $views
     */
    public function removeView(\castorinop\dnsManagerBundle\Entity\ServerView $views)
    {
        $this->views->removeElement($views);
    }

    /**
     * Get views
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getViews()
    {
        return $this->views;
    }
    
    public function __toString() {
    		return $this->getName();
    }
    
}