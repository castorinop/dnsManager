<?php

namespace castorinop\dnsManagerBundle\Controller;

use castorinop\dnsManagerBundle\Form\RecordType;

use castorinop\dnsManagerBundle\Entity\Record;

use Symfony\Component\HttpFoundation\Response;

use castorinop\dnsManagerBundle\Entity\Server;

use castorinop\dnsManagerBundle\Form\ServerType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Tests\A;

class ServerController extends Controller
{
    public function indexAction()
    {
    		$zone = new Server();
    		$form = $this->createForm(new ServerType(), $zone);
    		
    		$servers = $this->getDoctrine()
    			->getEntityManager()
    			->getRepository('dnsManagerBundle:Server')->findAll();
    		
        return $this->render('dnsManagerBundle:Server:index.html.twig', 
        		array(
        				'servers' => $servers,
        				'form' => $form->createView(),
        				));
    }
    
    public function showAction($domain)
    {
    	
    	$zone = $this->getDoctrine()
    	->getEntityManager()
    	->getRepository('dnsManagerBundle:Server')
    	->findOneByDomain($domain);
    
//     	if (count($domain))
    	
    	$host = new Record();
    	$host->setServer($zone);
    	
    	$form = $this->createForm(new RecordType(), $host);
    		 
    		
    	return $this->render('dnsManagerBundle:Server:show.html.twig', 
    				array(
    						'dom' => $zone,
    						'domain' => $zone->getDomain(),
    						'form' => $form->createView()
    				)
    			);
    }
    
    public function updateAction($id = NULL) 
    {
    	if ($id) {
    		$zone = $this->getDoctrine()
    		->getEntityManager()
    		->getRepository('dnsManagerBundle:Server')
    		->findOneById($id);
      } else 
    		$zone = new Server();
    	
    	$form = $this->createForm(new ServerType(), $zone);
    	
    	$form->bind($this->getRequest());
    	
    	if ($form->isValid()) {
    		$em = $this->getDoctrine()
    			->getEntityManager();
    		
    		$em->persist($zone);
    		$em->flush();
    		
    		return $this->redirect(
    				$this->getRequest()->headers->get('referer')
//     				$this->generateUrl('dns_manager_zone', array('domain' => $zone->getDomain()))
    				);
//     		return $this->render('dnsManagerBundle:Server:show.html.twig', array('dom' => $zone));
    	}
    	
    	return $this->redirect(
    			$this->generateUrl('dns_manager_homepage')
    			);    	
    	
    }
    
    public function editAction($domain) 
    {
    	
    	$zone  = $this->getDoctrine()
    		->getEntityManager()
    		->getRepository('dnsManagerBundle:Server')
    		->findOneByDomain($domain);
    	
    	$form = $this->createForm(new ServerType(), $zone);
    	
    	return $this->render('dnsManagerBundle:Server:edit.html.twig', 
    				array('form' => $form->createView(), 'domain' => $domain)
    			);
    }
    
    public function deleteAction($id) {
    	$obj = $this->getDoctrine()
    	->getEntityManager()
    	->getRepository('dnsManagerBundle:Server')
    	->findOneById($id);
    
    	$em = $this->getDoctrine()
    	->getEntityManager();
    
    	$em->remove($obj);
    	$em->flush();
    
    	return $this->redirect(
    			$this->getRequest()->headers->get('referer')
    	);
    }
    
    
    public function searchAction() {

    	$r = $this->getRequest();
    	
    	if (!$r->isXmlHttpRequest())
    		throw $this->createNotFoundException('no way');
    	
    	$text = $r->get('text', NULL);
    	 
    	if (is_null($text)) {
    		return new Response('sin resultados');
    	}
    	
    	$zones  = $this->getDoctrine()
    	->getEntityManager()
    	->createQuery('
    			SELECT z FROM dnsManagerBundle:Server z
    				WHERE z.domain LIKE :domain
    			')
    	->setParameter(":domain", "%$text%")
    	->getResult();
        	 
    		
    		 
    		#return $this->container->get('markdown.parser')->transformMarkdown($text);
    		return $this->render('dnsManagerBundle:Server:list.html.twig', array('doms' => $zones));
    }
    
    public function configAction($server) {
    	$as = array();
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$srv = $em->getRepository('dnsManagerBundle:Server')->find($server);
    	#FIXME: wait doctrine 2.4 and use SELECT NEW http://docs.doctrine-project.org/en/latest/reference/dql-doctrine-query-language.html#new-operator-syntax
    	$rq = $em->createQuery(
    			"SELECT z,r,rv,v,sv,s  FROM dnsManagerBundle:Zone z
    						LEFT JOIN z.records r
    						LEFT JOIN r.views rv
    						LEFT JOIN rv.view v
    						JOIN v.servers sv
    						JOIN sv.server s
	    					WHERE s.id  = :server
    						GROUP BY v.id
	    				");
    	$rq->setParameter('server', $server);
    	$views = $rq->getResult();
    	
    	foreach ($views as $z) {
    		$r = $z->getRecords()->first();
    		$rv = $r->getViews()->first();
    		$v = $rv->getView();
    		
    		foreach ($v->getServers() as $sv) {
    			$as[] = clone $sv;
    			
    			$zones = array($z);
    			foreach ($z->getAliases() as $a)
	    			$zones[] = $a;
	    			
    			foreach ($zones as $zone) {
	    			$item = new \stdClass();
	    			$item->domain = $zone->getDomain();
	    			$item->tsig = $sv->getTsig();
	    			$item->type = $sv->getType();
	    			$item->view  = $v->getName();
	    			$item->ip = $sv->getIp();
	    			$item->name = $sv->getServer()->getName();
	    			$l[] = clone $item;
    			}
    		}
    	}
    	
    	return $this->render('dnsManagerBundle:Server:config.html.twig', 
    			array('rows' => $l,
    				'keys' => $as,
    				'server' => $srv));
    }
    
}

