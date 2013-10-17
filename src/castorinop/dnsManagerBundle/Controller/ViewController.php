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
    			->getManager()
    			->getRepository('dnsManagerBundle:View')->findAll();
    		
        return $this->render('dnsManagerBundle:View:index.html.twig', 
        		array(
        				'servers' => $servers,
        				'form' => $form->createView(),
        				));
    }
    
    public function showAction($id)
    {
    	
    	$zone = $this->getDoctrine()
    	->getManager()
    	->getRepository('dnsManagerBundle:View')->find($id);
    	#->findOneByDomain($domain);
    
//     	if (count($domain))
    	
    	#$host = new View();
    	#$host->setView($zone);
    	
    	#$form = $this->createForm(new ViewType(), $host);
    		 
    		
    	return $this->render('dnsManagerBundle:View:show.html.twig', 
    				array(
    						'view' => $zone,
    						#'domain' => $zone->getDomain(),
    						#'form' => $form->createView()
    				)
    			);
    }
    
    public function updateAction($domain = NULL) 
    {
    	if ($domain) {
    		$zone = $this->getDoctrine()
    		->getManager()
    		->getRepository('dnsManagerBundle:View')
    		->findOneByDomain($domain);
      } else 
    		$zone = new View();
    	
    	$form = $this->createForm(new ViewType(), $zone);
    	
    	$form->bind($this->getRequest());
    	
    	if ($form->isValid()) {
    		$em = $this->getDoctrine()
    			->getManager();
    		
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
    	->getManager()
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
    		->getManager()
    		->getRepository('dnsManagerBundle:View')
    		->findOneById($id);
    	
    	$em = $this->getDoctrine()
    		->getManager();
    	
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
    	->getManager()
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

