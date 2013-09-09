<?php

namespace castorinop\dnsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * View
 */
class View
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
    private $records;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $servers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->records = new \Doctrine\Common\Collections\ArrayCollection();
        $this->servers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return View
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
     * Add records
     *
     * @param \castorinop\dnsManagerBundle\Entity\RecordView $records
     * @return View
     */
    public function addRecord(\castorinop\dnsManagerBundle\Entity\RecordView $records)
    {
        $this->records[] = $records;
    
        return $this;
    }

    /**
     * Remove records
     *
     * @param \castorinop\dnsManagerBundle\Entity\RecordView $records
     */
    public function removeRecord(\castorinop\dnsManagerBundle\Entity\RecordView $records)
    {
        $this->records->removeElement($records);
    }

    /**
     * Get records
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * Add servers
     *
     * @param \castorinop\dnsManagerBundle\Entity\ServerView $servers
     * @return View
     */
    public function addServer(\castorinop\dnsManagerBundle\Entity\ServerView $servers)
    {
        $this->servers[] = $servers;
    
        return $this;
    }

    /**
     * Remove servers
     *
     * @param \castorinop\dnsManagerBundle\Entity\ServerView $servers
     */
    public function removeServer(\castorinop\dnsManagerBundle\Entity\ServerView $servers)
    {
        $this->servers->removeElement($servers);
    }

    /**
     * Get servers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServers()
    {
        return $this->servers;
    }
    
    public function __toString()
    {
    	return $this->getName();
    }
}