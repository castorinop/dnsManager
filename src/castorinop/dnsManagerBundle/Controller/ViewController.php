<?php

namespace castorinop\dnsManagerBundle\Controller;

use castorinop\dnsManagerBundle\Form\RecordType;

use castorinop\dnsManagerBundle\Entity\Record;

use Symfony\Component\HttpFoundation\Response;

use castorinop\dnsManagerBundle\Entity\View;

use castorinop\dnsManagerBundle\Form\ViewType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ViewController extends Controller
{
    public function indexAction()
    {
    		$zone = new View();
    		$form = $this->createForm(new ViewType(), $zone);
    		
    		$servers = $this->getDoctrine()
    			->getEntityManager()
    			->getRepository('dnsManagerBundle:View')->findAll();
    		
        return $this->render('dnsManagerBundle:View:index.html.twig', 
        		array(
        				'servers' => $servers,
        				'form' => $form->createView(),
        				));
    }
    
    public function showAction($domain)
    {
    	
    	$zone = $this->getDoctrine()
    	->getEntityManager()
    	->getRepository('dnsManagerBundle:View')
    	->findOneByDomain($domain);
    
//     	if (count($domain))
    	
    	$host = new Record();
    	$host->setView($zone);
    	
    	$form = $this->createForm(new RecordType(), $host);
    		 
    		
    	return $this->render('dnsManagerBundle:View:show.html.twig', 
    				array(
    						'dom' => $zone,
    						'domain' => $zone->getDomain(),
    						'form' => $form->createView()
    				)
    			);
    }
    
    public function updateAction($domain = NULL) 
    {
    	if ($domain) {
    		$zone = $this->getDoctrine()
    		->getEntityManager()
    		->getRepository('dnsManagerBundle:View')
    		->findOneByDomain($domain);
      } else 
    		$zone = new View();
    	
    	$form = $this->createForm(new ViewType(), $zone);
    	
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
//     		return $this->render('dnsManagerBundle:View:show.html.twig', array('dom' => $zone));
    	}
    	
    	return $this->redirect(
    			$this->generateUrl('dns_manager_homepage')
    			);    	
    	
    }
    
    public function editAction($id)
    {
    
    	$record = $this->getDoctrine()
    	->getEntityManager()
    	->getRepository('dnsManagerBundle:View')
    	->findOneById($id);
        			    
    	$form = $this->createForm(new RecordType(), $record);
    
    	return $this->render('dnsManagerBundle:View:edit.html.twig',
    			array(
    					'form' => $form->createView(),
    					'view' =>$view,
    			)
    	);
    }
    
    public function deleteAction($id) {
    	$obj = $this->getDoctrine()
    		->getEntityManager()
    		->getRepository('dnsManagerBundle:View')
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
    			SELECT z FROM dnsManagerBundle:View z
    				WHERE z.domain LIKE :domain
    			')
    	->setParameter(":domain", "%$text%")
    	->getResult();
        	 
    		
    		 
    		#return $this->container->get('markdown.parser')->transformMarkdown($text);
    		return $this->render('dnsManagerBundle:View:list.html.twig', array('doms' => $zones));
    }
    
}

