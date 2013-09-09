<?php

namespace castorinop\dnsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecordView
 */
class RecordView
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $destination;

    /**
     * @var string
     */
    private $recordtype;

    /**
     * @var integer
     */
    private $mx;

    /**
     * @var integer
     */
    private $ttl;

    /**
     * @var \castorinop\dnsManagerBundle\Entity\Record
     */
    private $records;

    /**
     * @var \castorinop\dnsManagerBundle\Entity\View
     */
    private $view;


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
     * Set destination
     *
     * @param string $destination
     * @return RecordView
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    
        return $this;
    }

    /**
     * Get destination
     *
     * @return string 
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set recordtype
     *
     * @param string $recordtype
     * @return RecordView
     */
    public function setRecordtype($recordtype)
    {
        $this->recordtype = $recordtype;
    
        return $this;
    }

    /**
     * Get recordtype
     *
     * @return string 
     */
    public function getRecordtype()
    {
        return $this->recordtype;
    }

    /**
     * Set mx
     *
     * @param integer $mx
     * @return RecordView
     */
    public function setMx($mx)
    {
        $this->mx = $mx;
    
        return $this;
    }

    /**
     * Get mx
     *
     * @return integer 
     */
    public function getMx()
    {
        return $this->mx;
    }

    /**
     * Set ttl
     *
     * @param integer $ttl
     * @return RecordView
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    
        return $this;
    }

    /**
     * Get ttl
     *
     * @return integer 
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * Set records
     *
     * @param \castorinop\dnsManagerBundle\Entity\Record $records
     * @return RecordView
     */
    public function setRecords(\castorinop\dnsManagerBundle\Entity\Record $records = null)
    {
        $this->records = $records;
    
        return $this;
    }

    /**
     * Get records
     *
     * @return \castorinop\dnsManagerBundle\Entity\Record 
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * Set view
     *
     * @param \castorinop\dnsManagerBundle\Entity\View $view
     * @return RecordView
     */
    public function setView(\castorinop\dnsManagerBundle\Entity\View $view = null)
    {
        $this->view = $view;
    
        return $this;
    }

    /**
     * Get view
     *
     * @return \castorinop\dnsManagerBundle\Entity\View 
     */
    public function getView()
    {
        return $this->view;
    }
    /**
     * @var boolean
     */
    private $enable = true;


    /**
     * Set enable
     *
     * @param boolean $enable
     * @return RecordView
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
    
    public function __toString() {
     	$host = $this->getRecords()->getHostname();

     	$type = $this->getRecordtype();
    	if (strtolower($type) == "mx") {
    		$type .= " ".$this->getMx();
    	}
    	
    	return sprintf("%s %s IN %s %s", $host, $this->getTtl(), $type, $this->getDestination());
    }
}