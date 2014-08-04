<?php

namespace castorinop\dnsManagerBundle\Controller;

use castorinop\dnsManagerBundle\Form\RecordViewType;

use castorinop\dnsManagerBundle\Entity\RecordView;

use castorinop\dnsManagerBundle\Entity\Record;

use castorinop\dnsManagerBundle\Form\RecordType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

class HostController extends Controller
{

		private function getHostname($host) {
			
			$hosts = explode('/', $host);
		
			$hosts = array_reverse($hosts);
			array_shift($hosts);
			return implode('.', $hosts); 
		}
	
		private function getHost($domain, $host) {
			
			if (preg_match('~/~', $host))
				$hosts = explode('/', $host);
			else
				$hosts[] = $host;
			
			$rhosts = array_reverse($hosts);
			$deep = count($rhosts);
			
			$em = $this->getDoctrine()
			->getManager();
			
			$rq = $em->createQueryBuilder()
				->select('r1')
				->from('dnsManagerBundle:Record', 'r1')
				->Where('r1.hostname = :host')
				->andWhere('z.domain = :domain')
				->setParameter('host', $rhosts[0])
				->setParameter('domain', $domain);
				
				for ($i = 1; $i < count($hosts); $i++) {
					$k = $i + 1;
					$rq->join("r$i.domain", "r$k")
						->andWhere("r$k.hostname = :host$k")
						->setParameter("host$k", $rhosts[$i]);
				}
				
			$rq->join("r{$deep}.zone", 'z');
				
			$q = $rq->getQuery();
			return $q->getOneOrNullResult(); 
			
		}
		
    public function indexAction($domain, $host)
    {
    	$hosts = $this->getHost($domain, $host);
    		
    		$v = new RecordView();
    		$v->setRecords($hosts);
    		$form_view = $this->createForm(new RecordViewType(), $v);
    		
    		$h = new Record();
    		$h->setDomain($hosts);
    		$form_host = $this->createForm(new RecordType(), $h);
    		
        return $this->render('dnsManagerBundle:Host:index.html.twig', 
        		array(
        				'host' => $hosts,
        				'domain' => $domain,
        				'hostname' => $this->getHostname($host),
        				'form_view' => $form_view->createView(),
        				'form_host' => $form_host->createView(),
        				));
    }
    
    public function showAction($domain, $host)
    {
    	$record = $this->getHost($domain, $host);
//     	$rq = $em->createQuery(
//     			'SELECT r FROM dnsManagerBundle:Record r
//     				JOIN r.zone z
//     				WHERE z.domain = :domain
//     					AND r.hostname = :host');
    	
//     	$rq->setParameter('domain', $domain);
//     	$rq->setParameter('host', $host);
//     	$record = $rq->getOneOrNullResult();
    
    	return $this->render('dnsManagerBundle:Zone:show.html.twig', 
    			array(
    					'host' => $record,
    					'hostname' => $this->getHostname($host),
    					)
    			);
    }
    
    public function updateAction($id = NULL)
    {
    	$record = false;
    	
    	if ($id)
    	$record = $this->getDoctrine()->getManager()
    		->getRepository('dnsManagerBundle:Record')->find($id);
//     	if ($host) {
//     		$record = $this->getDoctrine()
// 	    	->getManager()
//   	  	->createQuery('
//     			SELECT h FROM dnsManagerBundle:Record h
//   	  			JOIN h.zone z
//     				WHERE h.hostname = :text 
//   	  				AND z.domain = :domain
//     			')
//  				->setParameter(":text", $host)
//  				->setParameter(":domain", $domain)
//     		->getResult();
//     	} else {
//     		$record = new Record();
//     		$zone = $this->getDoctrine()
//     		->getManager()
//     		->getRepository('dnsManagerBundle:Zone')
//     		->findOneByDomain($domain);
//     		$record->setZone($zone);
//     	}
		if (!$record)
    		$record = new Record();

		$oRV = array();
		
		// Create an array of the current Tag objects in the database
		foreach ($record->getViews() as $rv) {
			$oRV[] = $rv;
		}
		
    	$form = $this->createForm(new RecordType(), $record);
    	 
    	$form->bind($this->getRequest());
    	//$form->handleRequest($this->getRequest());
    	 
    	if ($form->isValid()) {
    		$em = $this->getDoctrine()
    			->getManager();

    		// filter $oRV to contain tags no longer present
    		foreach ($record->getViews() as $rv) {
    			foreach ($oRV as $key => $toDel) {
    				if ($toDel->getId() === $rv->getId()) {
    					unset($oRV[$key]);
    				}
    			}
    		}
    		
    		// remove the relationship between the tag and the Task
    		foreach ($oRV as $rv) {
    			// remove the Task from the Tag
    			//$rv->getRecords()->removeElement($record);
    			
    		
    			// if it were a ManyToOne relationship, remove the relationship like this
    			$rv->setRecords(null);
    			$rv->setView(null);
    			
    			$em->persist($rv);
    		
    			// if you wanted to delete the Tag entirely, you can also do that
    			$em->remove($rv);
    		}
    		
    		$em->persist($record);
    		
    		$zone = $record->getZone();
    		$zone->setSerial();
    		$em->persist($zone);
    		
    		$em->flush();
    		
    		$this->get('session')->getFlashBag()->add(
    				'notice', 'updated');
    		 
    		$ret = ($id
    			? $this->generateUrl('dns_manager_zone', 
    				array('domain' => $record->getZone()))
    			: $this->generateUrl('dns_manager_host_edit', 
    				array('id' => $record->getId()))
    			);
     		//return  $this->redirect( $ret);
    	} else {
    	 
    		$this->get('session')->getFlashBag()->add(
    			'warning', 'failed!');
    	} 
    	$domain = $record->getZone();
    	$host = $record->getHostname();
    	
    	return $this->render('dnsManagerBundle:Host:edit.html.twig',
    			array(
    					'form' => $form->createView(),
    					'domain' => $domain,
    					'record' => $record,
    					'host' => $host
    			)
    	);
    	return $this->redirect(
    			$this->generateUrl('dns_manager_homepage')
    	);
    	 
    }
    
    public function editAction($id)
    {

    	$em = $this->getDoctrine()->getManager();
    	
    	$record = $em->getRepository('dnsManagerBundle:Record')->find($id);
    	
//     	->getManager()
//     	->createQuery('
//     			SELECT h FROM dnsManagerBundle:Record h
//   	  			JOIN h.zone z
//     				WHERE h.hostname = :text
//   	  				AND z.domain = :domain
//     			')
//     	    			->setParameter(":text", $host)
//     	    			->setParameter(":domain", $domain)
//     	    			->getOneOrNullResult();
    	 
    	$domain = $record->getZone();
    	$host = $record->getHostname();
    	$form = $this->createForm(new RecordType(), $record);
    	 
    	return $this->render('dnsManagerBundle:Host:edit.html.twig',
    			array(
    					'form' => $form->createView(),
    					'domain' => $domain,
    					'record' => $record,
    					'host' => $host
    				)
    		);
    }
    
    public function searchAction($domain) {
    
    	$r = $this->getRequest();
    	
    	if (!$r->isXmlHttpRequest())
    		throw $this->createNotFoundException('no way');
    
    	$text = $r->get('text', NULL);
    
    	if (is_null($text)) {
    		return new Response('sin resultados');
    	}
    	 
    	$hosts  = $this->getDoctrine()
	    	->getManager()
  	  	->createQuery('
    			SELECT h FROM dnsManagerBundle:Record h
  	  			JOIN h.zone z
    				WHERE h.hostname LIKE :text 
  	  				AND z.domain = :domain
    			')
 				->setParameter(":text", "%$text%")
 				->setParameter(":domain", $domain)
    		->getResult();
    
    	#return $this->container->get('markdown.parser')->transformMarkdown($text);
    	return $this->render('dnsManagerBundle:Host:list.html.twig', 
    		array('records' => $hosts));
    }
    
    public function deleteAction(Record $host) {
    	
    	$ret = $host->getZone()->getDomain();
    	$em = $this->getDoctrine()->getManager();
    	
    	$this->get('session')->getFlashBag()->add(
    			'notice', sprintf('removed %s.%s', $host->getHostname(),$ret));
    	$em->remove($host);
    	$em->flush();
    	
    	return $this->redirect($this->generateUrl('dns_manager_zone', array('domain' => $ret) ));
    	
    }
}

