<?php

namespace castorinop\dnsManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServerView
 */
class ServerView
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \castorinop\dnsManagerBundle\Entity\Server
     */
    private $server;

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
     * Set type
     *
     * @param string $type
     * @return ServerView
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set server
     *
     * @param \castorinop\dnsManagerBundle\Entity\Server $server
     * @return ServerView
     */
    public function setServer(\castorinop\dnsManagerBundle\Entity\Server $server = null)
    {
        $this->server = $server;
    
        return $this;
    }

    /**
     * Get server
     *
     * @return \castorinop\dnsManagerBundle\Entity\Server 
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set view
     *
     * @param \castorinop\dnsManagerBundle\Entity\View $view
     * @return ServerView
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
     * @var string
     */
    private $key;


    /**
     * Set key
     *
     * @param string $key
     * @return ServerView
     */
    public function setKey($key)
    {
        $this->key = $key;
    
        return $this;
    }

    /**
     * Get key
     *
     * @return string 
     */
    public function getKey()
    {
        return $this->key;
    }
    /**
     * @var string
     */
    private $key_name;

    /**
     * @var string
     */
    private $key_value;

    /**
     * @var long
     */
    private $ip;


    /**
     * Set key_name
     *
     * @param string $keyName
     * @return ServerView
     */
    public function setKeyName($keyName)
    {
        $this->key_name = $keyName;
    
        return $this;
    }

    /**
     * Get key_name
     *
     * @return string 
     */
    public function getKeyName()
    {
        return $this->key_name;
    }

    /**
     * Set key_value
     *
     * @param string $keyValue
     * @return ServerView
     */
    public function setKeyValue($keyValue)
    {
        $this->key_value = $keyValue;
    
        return $this;
    }

    /**
     * Get key_value
     *
     * @return string 
     */
    public function getKeyValue()
    {
        return $this->key_value;
    }

    /**
     * Set ip
     *
     * @param $ip
     * @return ServerView
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return \long 
     */
    public function getIp()
    {
        return $this->ip;
    }
    /**
     * @var string
     */
    private $tsig;


    /**
     * Set tsig
     *
     * @param string $tsig
     * @return ServerView
     */
    public function setTsig($tsig)
    {
        $this->tsig = $tsig;
    
        return $this;
    }

    /**
     * Get tsig
     *
     * @return string 
     */
    public function getTsig()
    {
        return $this->tsig;
    }
}