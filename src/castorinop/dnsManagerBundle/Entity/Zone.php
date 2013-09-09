<?php

namespace castorinop\dnsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zone
 */
class Zone
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $ttl = 86400;

    /**
     * @var string
     */
    private $soa;

    /**
     * @var string
     */
    private $mail;

    /**
     * @var string
     */
    private $serial;

    /**
     * @var integer
     */
    private $refresh = 172800;

    /**
     * @var integer
     */
    private $retry = 900;

    /**
     * @var integer
     */
    private $expire =	1209600;

    /**
     * @var integer
     */
    private $defttl = 86400;

    /**
     * @var boolean
     */
    private $enable = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $records;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $aliases;

    /**
     * @var \castorinop\dnsManagerBundle\Entity\Zone
     */
    private $alias;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->records = new \Doctrine\Common\Collections\ArrayCollection();
        $this->aliases = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set domain
     *
     * @param string $domain
     * @return Zone
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    
        return $this;
    }

    /**
     * Get domain
     *
     * @return string 
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set ttl
     *
     * @param string $ttl
     * @return Zone
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    
        return $this;
    }

    /**
     * Get ttl
     *
     * @return string 
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * Set soa
     *
     * @param string $soa
     * @return Zone
     */
    public function setSoa($soa)
    {
        $this->soa = $soa;
    
        return $this;
    }

    /**
     * Get soa
     *
     * @return string 
     */
    public function getSoa()
    {
        return $this->soa;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Zone
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set serial
     *
     * @param string $serial
     * @return Zone
     */
    public function setSerial($serial = NULL)
    {
    	
    	if (is_null($serial)) {
    		$s = $this->getSerial() ? $this->getSerial() : date('Ymd01');
    	} else {
    		$s = $serial;
    	}
    	
    	if (!preg_match('~(?P<year>\d{4})(?P<month>\d{2})(?P<day>\d{2})(?P<instance>\d{2})~', $s, $args))
    		throw new \Exception('bad sintax. use '.date('Ymd01'));
    	
    	$dt = new \DateTime(sprintf('%s-%s-%s 00:00:00', $args['year'], $args['month'],$args['day']));    	
    	
    	# update serial
    	if (is_null($serial)) {
    		$dtr = new \DateTime();
    		$dtr->setTime(0,0,0);
    		
    		if ($dt == $dtr)
    			$args['instance']++;
    		else {
    			$args['instance'] = 1;
    			$dt = $dtr;
    		}
    		$s = sprintf('%s%02s', $dt->format('Ymd'), $args['instance']);
    	}
    	
        $this->serial = $s;
    
        return $this;
    }

    /**
     * Get serial
     *
     * @return string 
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Set refresh
     *
     * @param integer $refresh
     * @return Zone
     */
    public function setRefresh($refresh)
    {
        $this->refresh = $refresh;
    
        return $this;
    }

    /**
     * Get refresh
     *
     * @return integer 
     */
    public function getRefresh()
    {
        return $this->refresh;
    }

    /**
     * Set retry
     *
     * @param integer $retry
     * @return Zone
     */
    public function setRetry($retry)
    {
        $this->retry = $retry;
    
        return $this;
    }

    /**
     * Get retry
     *
     * @return integer 
     */
    public function getRetry()
    {
        return $this->retry;
    }

    /**
     * Set expire
     *
     * @param integer $expire
     * @return Zone
     */
    public function setExpire($expire)
    {
        $this->expire = $expire;
    
        return $this;
    }

    /**
     * Get expire
     *
     * @return integer 
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * Set defttl
     *
     * @param integer $defttl
     * @return Zone
     */
    public function setDefttl($defttl)
    {
        $this->defttl = $defttl;
    
        return $this;
    }

    /**
     * Get defttl
     *
     * @return integer 
     */
    public function getDefttl()
    {
        return $this->defttl;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     * @return Zone
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

    /**
     * Add records
     *
     * @param \castorinop\dnsManagerBundle\Entity\Record $records
     * @return Zone
     */
    public function addRecord(\castorinop\dnsManagerBundle\Entity\Record $records)
    {
        $this->records[] = $records;
    
        return $this;
    }

    /**
     * Remove records
     *
     * @param \castorinop\dnsManagerBundle\Entity\Record $records
     */
    public function removeRecord(\castorinop\dnsManagerBundle\Entity\Record $records)
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
     * Add aliasess
     *
     * @param \castorinop\dnsManagerBundle\Entity\Zone $aliasess
     * @return Zone
     */
    public function addAliases(\castorinop\dnsManagerBundle\Entity\Zone $aliasess)
    {
        $this->aliasess[] = $aliasess;
    
        return $this;
    }

    /**
     * Remove aliasess
     *
     * @param \castorinop\dnsManagerBundle\Entity\Zone $aliasess
     */
    public function removeAliases(\castorinop\dnsManagerBundle\Entity\Zone $aliasess)
    {
        $this->aliasess->removeElement($aliasess);
    }

    /**
     * Get aliasess
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Set alias
     *
     * @param \castorinop\dnsManagerBundle\Entity\Zone $alias
     * @return Zone
     */
    public function setAlias(\castorinop\dnsManagerBundle\Entity\Zone $alias = null)
    {
        $this->alias = $alias;
    
        return $this;
    }

    /**
     * Get alias
     *
     * @return \castorinop\dnsManagerBundle\Entity\Zone 
     */
    public function getAlias()
    {
        return $this->alias;
    }
    
    public function __toString() {
    	return $this->getDomain();
    }

    /**
     * Add aliases
     *
     * @param \castorinop\dnsManagerBundle\Entity\Zone $aliases
     * @return Zone
     */
    public function addAliase(\castorinop\dnsManagerBundle\Entity\Zone $aliases)
    {
        $this->aliases[] = $aliases;
    
        return $this;
    }

    /**
     * Remove aliases
     *
     * @param \castorinop\dnsManagerBundle\Entity\Zone $aliases
     */
    public function removeAliase(\castorinop\dnsManagerBundle\Entity\Zone $aliases)
    {
        $this->aliases->removeElement($aliases);
    }
    
    public function SOA() {
    	
    	return sprintf('%s IN SOA %s %s %s %s %s %s %s',
    			$this->getDomain(),
    			$this->getSoa(),
    			$this->getMail(),
    			$this->getSerial(),
    			$this->getRefresh(),
    			$this->getRetry(),
    			$this->getExpire(),
    			$this->getDefttl()
		);
//     	{{zone.domain }}. IN SOA {{ zone.soa }}.{{ zone.domain }}. {{ zone.mail }}. (
//     			<ul>
//     			<li>{{ zone.serial }} ; serial </li>
//     			<li>{{ zone.refresh }} ; refresh </li>
//     			<li>{{ zone.retry }} ; retry </li>
//     			<li>{{ zone.expire }} ; expire </li>
//     			<li>{{ zone.defttl}} ; minimum </li>
//     			</ul>
    }
}