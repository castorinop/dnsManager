<?php

namespace castorinop\dnsManagerBundle\Controller;

use castorinop\dnsManagerBundle\Form\ServerViewType;

use castorinop\dnsManagerBundle\Entity\ServerView;

use castorinop\dnsManagerBundle\Entity\Record;

use castorinop\dnsManagerBundle\Form\RecordType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ServerViewController extends Controller
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
    		
    		$v = new ServerView();
    		$v->setRecords($hosts);
    		$form_view = $this->createForm(new ServerViewType(), $v);
    		
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
    
    public function showAction($server)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$s = $em->getRepository('dnsManagerBundle:Server')->find($server);
    	
    	$sv = new ServerView();
    	$sv->setServer($s);
    	
    	$form = $this->createForm(new ServerViewType(), $sv);
    	
    	if (!$s)
    		throw $this->createNotFoundException('Server Not Found');
    
    	return $this->render('dnsManagerBundle:ServerView:show.html.twig', 
    			array(
    					'server' => $s,
    					'form' => $form->createView(),
    					)
    			);
    }
    
    public function createAction() {
    	$sv = new ServerView();
    	$form = $this->createForm(new ServerViewType(), $sv);
    	return $this->render('dnsManagerBundle:ServerView:create.html.twig',
    		array('form' => $form->createView()));
    }
    
    public function updateAction($id = NULL)
    {
    	
    	if (!$id) 
    		$record = new ServerView();
    	else
    		$record = $this->getDoctrine()
	    		->getManager()
	    		->getRepository('dnsManagerBundle:ServerView')
	    		->findOneById($id);
    	 
    	$form = $this->createForm(new ServerViewType(), $record);
    	 
    	$form->bind($this->getRequest());
    	 
    	if ($form->isValid()) {
    		
    		$em = $this->getDoctrine()
    			->getManager();
    
    		$em->persist($record);
    		$em->flush();
    
    		$this->get('session')->getFlashBag()->add('notice', 'server view updated '.$record->getId());
//     		return $this->redirect(
//     				$this->getRequest()->headers->get('referer')
//     				$this->generateUrl('dns_manager_host', 
//     						array(
//     								'domain' => $domain, 
//     								'host' => $record->getHostname()
//     								)
//     					)
//     		);
    		//     		return $this->render('dnsManagerBundle:Zone:show.html.twig', array('dom' => $zone));
    	}
    	
    	$form = $this->createForm(new ServerViewType(), $record);
    	
    	return $this->render('dnsManagerBundle:ServerView:edit.html.twig',
    			array(
    					'form' => $form->createView(),
    					'serverview' => $record,
    			)
    	);
    	
    	return $this->redirect(
    			$this->generateUrl('dns_manager_homepage')
    	);
    	 
    }
    
    public function editAction($id = NULL)
    {
    	 
    	if (!$id) 
    		$record = new ServerView();
    	else
	    	$record = $this->getDoctrine()
	    		->getManager()
	    		->getRepository('dnsManagerBundle:ServerView')
	    		->findOneById($id);	

    	if (!$record)
    		throw $this->createNotFoundException('Server View not found');
    	
    	$form = $this->createForm(new ServerViewType(), $record);
    	 
    	return $this->render('dnsManagerBundle:ServerView:edit.html.twig',
    			array(
    					'form' => $form->createView(),
    					'serverview' => $record,
    				)
    		);
    }
    
    public function deleteAction($id)
    {
    
    	$em = $this->getDoctrine()
    	->getManager();

    	$record = 
    		$em->getRepository('dnsManagerBundle:ServerView')
    		->find($id);
    
    	if (!$record)
    		throw $this->createNotFoundException('ServerView View not found');

    	
    	$em->remove($record);
    	$em->flush();
    	
    	$this->get('session')->getFlashbag()->add('notice','server view removed');
    	
    	$form = $this->createForm(new ServerViewType(), $record);
    
    	
    	return $this->redirect(
    			$this->getRequest()->headers->get('referer')
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

