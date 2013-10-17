<?php

namespace castorinop\dnsManagerBundle\Controller;

use castorinop\dnsManagerBundle\Form\RecordViewType;

use castorinop\dnsManagerBundle\Entity\RecordView;

use castorinop\dnsManagerBundle\Entity\Record;

use castorinop\dnsManagerBundle\Form\RecordType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RecordViewController extends Controller
{

		private function getHostname($host) {
			
			$hosts = explode('/', $host);#_welcome:
#    pattern:  /
#    defaults: { _controller: AcmeDemoBundle:Welcome:index }
		
			$hosts = array_reverse($hosts);
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
    
    public function updateAction($id)
    {
    	
    	if ($id) {
    		$record = $this->getDoctrine()
	    		->getManager()
	    		->getRepository('dnsManagerBundle:RecordView')
	    		->findOneById($id);
    	} else {
    		$record = new RecordView();
     		
    	}
    	 
    	$form = $this->createForm(new RecordViewType(), $record);
    	 
    	$form->bind($this->getRequest());
    	 
    	if ($form->isValid()) {
    		$em = $this->getDoctrine()
    			->getManager();
    
    		$em->persist($record);
    		
    		$zone = $record->getRecords()->getZone();
    		$zone->setSerial();
    		$em->persist($zone);
    		
    		$em->flush();
    
    		return $this->redirect(
    				$this->getRequest()->headers->get('referer')
//     				$this->generateUrl('dns_manager_host', 
//     						array(
//     								'domain' => $domain, 
//     								'host' => $record->getHostname()
//     								)
//     					)
    		);
    		//     		return $this->render('dnsManagerBundle:Zone:show.html.twig', array('dom' => $zone));
    	}
    	 
    	return $this->redirect(
    			$this->generateUrl('dns_manager_homepage')
    	);
    	 
    }
    
    public function editAction($id)
    {
    	 
    	$record = $this->getDoctrine()
    		->getManager()
    		->getRepository('dnsManagerBundle:RecordView')
    		->findOneById($id);	
    	 
    	$form = $this->createForm(new RecordViewType(), $record);
    	 
    	return $this->render('dnsManagerBundle:RecordView:edit.html.twig',
    			array(
    					'form' => $form->createView(),
    					'recordview' => $record,
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
    
    
}

