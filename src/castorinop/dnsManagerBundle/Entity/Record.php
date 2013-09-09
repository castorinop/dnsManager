<?php

namespace castorinop\dnsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Record
 */
class Record
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $hostname;

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
        $this->domains = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set hostname
     *
     * @param string $hostname
     * @return Record
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    
        return $this;
    }

    /**
     * Get hostname
     *
     * @return string 
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Add views
     *
     * @param \castorinop\dnsManagerBundle\Entity\RecordView $views
     * @return Record
     */
    public function addView(\castorinop\dnsManagerBundle\Entity\RecordView $views)
    {
    	$views->setRecords($this);
        $this->views->add($views);
    
        return $this;
    }

    /**
     * Remove views
     *
     * @param \castorinop\dnsManagerBundle\Entity\RecordView $views
     */
    public function removeView(\castorinop\dnsManagerBundle\Entity\RecordView $views)
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
    /**
     * @var \castorinop\dnsManagerBundle\Entity\Zone
     */
    private $zone;


    /**
     * Set zone
     *
     * @param \castorinop\dnsManagerBundle\Entity\Zone $zone
     * @return Record
     */
    public function setZone(\castorinop\dnsManagerBundle\Entity\Zone $zone = null)
    {
        $this->zone = $zone;
    
        return $this;
    }

    /**
     * Get zone
     *
     * @return \castorinop\dnsManagerBundle\Entity\Zone 
     */
    public function getZone()
    {
        return $this->zone;
    }
    /**
     * @var boolean
     */
    private $enable = true;


    /**
     * Set enable
     *
     * @param boolean $enable
     * @return Record
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
    
        return $this;
    }

    /**
     * Get enable
     *
     * @return boolean 
     */
    public function getEnable()
    {
        return $this->enable;
    }
    
    public function __toString()
    {
    	return $this->getHostname();
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $subdomains;

    /**
     * @var \castorinop\dnsManagerBundle\Entity\Record
     */
    private $domain;


    /**
     * Add subdomains
     *
     * @param \castorinop\dnsManagerBundle\Entity\Record $subdomains
     * @return Record
     */
    public function addSubdomain(\castorinop\dnsManagerBundle\Entity\Record $subdomains)
    {
        $this->subdomains[] = $subdomains;
    
        return $this;
    }

    /**
     * Remove subdomains
     *
     * @param \castorinop\dnsManagerBundle\Entity\Record $subdomains
     */
    public function removeSubdomain(\castorinop\dnsManagerBundle\Entity\Record $subdomains)
    {
        $this->subdomains->removeElement($subdomains);
    }

    /**
     * Get subdomains
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubdomains()
    {
        return $this->subdomains;
    }

    /**
     * Set domain
     *
     * @param \castorinop\dnsManagerBundle\Entity\Record $domain
     * @return Record
     */
    public function setDomain(\castorinop\dnsManagerBundle\Entity\Record $domain = null)
    {
        $this->domain = $domain;
    
        return $this;
    }

    /**
     * Get domain
     *
     * @return \castorinop\dnsManagerBundle\Entity\Record 
     */
    public function getDomain()
    {
        return $this->domain;
    }
    
    public function getUrl($base = true) {
    	$host[] = $this->getHostname();
    	$dom = $this->getDomain();
    	#print_r('<br/>'.$this->getHostname());
    	if (!$dom) {
    		if ($base)
    			$host[] = $this->getZone()->getDomain();
    		return $host; 
    	} 
    	
			$flag = true;
    	while ($flag) {
    		
	    	if ($dom) {
	    		#print_r('<br/>'.$dom->getHostname());
	    		$host[] = $dom->getHostname();
	    		if ($dom->getZone()) {
	    			if ($base)
	    				$host[] = $dom->getZone()->getDomain();
	    			$flag = false;
	    		} 
	    		
	    		$dom = $dom->getDomain();
	    	}
    		
    	}
//     	print_r($host);
    	
    	return $host;
    }
}