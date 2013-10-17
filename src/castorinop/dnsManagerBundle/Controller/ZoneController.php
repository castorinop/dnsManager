<?php

namespace castorinop\dnsManagerBundle\Controller;

use castorinop\dnsManagerBundle\Form\RecordViewType;

use castorinop\dnsManagerBundle\Form\RecordType;

use castorinop\dnsManagerBundle\Entity\Record;

use Symfony\Component\HttpFoundation\Response;

use castorinop\dnsManagerBundle\Entity\Zone;

use castorinop\dnsManagerBundle\Form\ZoneType;

use castorinop\dnsManagerBundle\Form\ZoneImportType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Net_DNS2\Net\DNS2\Resolver; 
use castorinop\dnsManagerBundle\Entity\RecordView;
use Symfony\Component\Validator\Constraints\Null;
use castorinop\dnsManagerBundle\Entity\View;

class ZoneController extends Controller
{

	private function stripDomain($domain, $string) {
		$str =  preg_replace("~\.?".$domain."\.?~i", Null, $string);
		return ($str ? $str : "@");
	}
	
	private function export($zone, $alias = NULL) {
		
		$msgs = array();
		
		if (!$alias)
			$alias = $zone;
		
		$em = $this->getDoctrine()->getManager();
		$rq = $em->createQuery(
				"SELECT v,rv,r FROM dnsManagerBundle:View v
    						JOIN v.records rv
    						JOIN rv.records r
    						JOIN r.zone z
	    					WHERE z.domain = :zone
	    				");
		$rq->setParameter('zone', $zone);
		$views = $rq->execute();
		
		if (!$views)
			throw $this->createNotFoundException('cant get views for '.$zone);
		
		$z = $em->getRepository('dnsManagerBundle:Zone')->findOneByDomain($zone);
		$l = $this->get('logger');
		
// 		echo "<br>$zone";
		foreach ($views as $v) {
// 			echo "<br>$v";
			
			foreach ($v->getServers() as $s) {
				$u = new \Net_DNS2_Updater($alias);
				$u->setServers(array($s->getIP()));

				$l->info('update '.$v." ".$alias);
				if ($s->getTsig()) {
					$keyname = "key-$v";
					$u->signTSIG($keyname, $s->getTsig());
					$l->info("use key ".$keyname);
				}

				$u->deleteAll($alias);
				$u->deleteAny($alias, "NS");
				

				try {
					#FIXME: add SOA;
					$soa = $z->SOA();
					if ($alias)
					$soa = str_replace($zone, $alias, $soa);
					$rr = \Net_DNS2_RR::fromString($soa);
					//echo "<br>rr $rr";
					
					$l->info("post $rr");
  					$u->add($rr);
  					
					foreach($v->getRecords() as $r) {
						$l->info("pre  $r");
						$r = str_replace($zone, $alias, $r);
						$l->info("pre $r");
						$rr = \Net_DNS2_RR::fromString($r);
						$l->info("post $rr");
	 					$u->add($rr);
	 					
					}
				
					$u->update();
					$msgs[$v->getName()][$s->getIP()] = "Ok!";
				} catch (\Exception $e) {
					$msgs[$v->getName()][$s->getIP()] =  $e->getMessage();
				}
			 
			}
		}
		return $msgs;
		
	}
		
    public function indexAction()
    {
    		$zone = new Zone();
    		$form = $this->createForm(new ZoneType(), $zone);
    		$import = $this->createForm(new ZoneImportType());
    		
    		$doms = $this->getDoctrine()
    			->getManager()
    			->getRepository('dnsManagerBundle:Zone')->findBy(
					array('alias' => NULL) 	
    		);
    		
        return $this->render('dnsManagerBundle:Zone:index.html.twig', 
        		array(
        				'doms' => $doms,
        				'form' => $form->createView(),
        				'import' => $import->createView()
        				));
    }
    
    public function showAction($domain, $view = NULL)
    {
    	$em = $this->getDoctrine()
    	->getManager();
    	
    	$rq = $em->createQueryBuilder()
    		->select('z,r,rv')
    		->from('dnsManagerBundle:Zone', 'z')
    		->leftJoin('z.records', 'r')
    		->leftJoin('r.views', 'rv')
    		->leftJoin('rv.view', 'v')
    		->where('z.domain = :domain')
    		->orderBy('r.hostname,rv.recordtype,rv.destination,v.name');
    	
    	if ($view)
    		$rq->andWhere('v.name = :view');
    	
//     	$rq = $em->createQuery(
//     			"SELECT z,r,rv FROM dnsManagerBundle:Zone z
//     						JOIN z.records r
//     						JOIN r.views rv
//     						JOIN rv.view v
// 	    					WHERE z.domain = :domain
//     						ORDER BY r.hostname,rv.recordtype,rv.destination
// 	    				");

		$rq->setParameter('domain', $domain);
		if ($view)
			$rq->setParameter('view', $view);
		
		$zone = $rq->getQuery()->getOneOrNullResult();
    	//$zone = $rq->getOneOrNullResult();
    	
//     	$zone = $em	->getRepository('dnsManagerBundle:Zone')
//     	->findOneByDomain($domain);
    
    	
    	$host = new Record();
    	$host->setZone($zone);
    	
    	$form = $this->createForm(new RecordType(), $host);

//     	$v = $em->getRepository('dnsManagerBundle:View')->findAll();
    	$rq = $em->createQuery(
    			"SELECT v FROM dnsManagerBundle:View v
    						JOIN v.records rv
    						JOIN rv.records r
    						JOIN r.zone z
	    					WHERE z.domain = :domain
    						GROUP By v.name
	    				");
    	 
    	$rq->setParameter('domain', $domain);
    	$v = $rq->execute();
    	
    	return $this->render('dnsManagerBundle:Zone:show.html.twig', 
    				array(
    						'dom' => $zone,
    						'domain' => $domain,
    						'views' => $v,
    						'view' => $view,
    						'form' => $form->createView()
    				)
    			);
    }
    
    public function exportAction($zone)
    {
    	$msgs = array();
    	
    	$em = $this->get('doctrine')->getManager();
    	
    	$z = $em->getRepository('dnsManagerBundle:Zone')->findOneByDomain($zone);
    	
    	if (!$z)
    		throw $this->createNotFoundException('cant get zone '.$zone);
    	
    	$msgs[$zone] = $this->export($zone);
    	
    	
    	foreach ($z->getAliases() as $a)
    		$msgs[$a->getDomain()] = $this->export($zone, $a->getDomain());
    	
    	
    	return $this->render('dnsManagerBundle:Zone:export.html.twig',
    			array('msgs' => $msgs,
    			'domain' => $zone)
    	);
    	
    }
    
    public function importAction()
    {
    	$result = null;
    	$e = null;
    	$f = null;
    	$z = null;
    	$ff = array();
    	$arv = array();
    	
    	$params = $this->getRequest()->get('castorinop_dnsmanagerbundle_zoneimporttype');
    	$domain = $params['domain'];
    	$r = new \Net_DNS2_Resolver(array('nameservers' => array('172.100.15.92')));
    	
    	if ($params['server']) {
    		$r->setServers(array($params['server']));
    	}
    	//
    	// add a TSIG to authenticate the request
    	//
    	if ($params['keyname'] && $params['key'])
    		$r->signTSIG($params['key'],$params['keyname']);
    	#$r->signTSIG('mykey', 'UzfN2Xya5WJCDSfQDKest60Db1zNgELiwzErRqvC27zA4SoXvyiKCGcpeMbb');
    	
    	//
    	// execute the query request for the google.com MX servers
    	//
    	try {
    		$result = $r->query($domain, 'AXFR');
    	
    	} catch(\Net_DNS2_Exception $e) {
    		
    		
    		//echo "::query() failed: ", $e->getMessage(), "\n";
    	}
    	
    	//
    	// loop through the answer, printing out each resource record.
    	//
    	$em = $this->getDoctrine()->getManager();
    	
    	
    	$v = $em->getRepository('dnsManagerBundle:View')->find($params['view']);

    	if ($result) {
    		$rq = $em->createQuery(
    				"SELECT z,r,rv FROM dnsManagerBundle:Zone z
    						LEFT JOIN z.records r
    						LEFT JOIN r.views rv
    						LEFT JOIN rv.view v
	    					WHERE z.domain = :domain
    						ORDER BY r.hostname,rv.recordtype,rv.destination
	    				");
    		 
    		$rq->setParameter('domain', $domain);
//     		$rq->setParameter('view', $v);
    		$z = $rq->getOneOrNullResult();

//     		$z = $em->getRepository('dnsManagerBundle:Zone')
//     			->findOneByDomain($domain);
    		
    		if (!$z) {
    			$this->get('logger')->info("create new zone!");
    			$z = new Zone();
    			$em->persist($z);
    		}
    		
	     	foreach($result->answer as $rr) {
				$this->get('logger')->info('parse '.$rr->name);
	     		$f = null; $q = null; $rv = null;
	     		if ($rr->type == 'SOA') {
	     			
	     			$z->setDomain($rr->name);
	     			$z->setDefttl($rr->minimum);
	     			$z->setExpire($rr->expire);
	     			$z->setMail($rr->rname);
	     			$z->setRefresh($rr->refresh);
	     			$z->setRetry($rr->retry);
	     			$z->setSerial($rr->serial);		
	     			$z->setEnable(true);																																																																																																																																																																																																																																																																																																																																																																																																																	
	     			$z->setSoa($rr->mname);
	     			$z->setTtl($rr->ttl);
	     			
	     		} else {
	     			
	     			foreach ($z->getRecords() as $k => $r ){
	     				$this->get('logger')->info(
	     						sprintf('hostname %s == %s', $r->getHostname(), $rr->name)
    					);
	     				if ($r->getHostname() == $rr->name) {
	     					$f = $r;
	     					$this->get('logger')->info('finded '.$r);
	     					break;
	     				}
	     			}
	     			
	     			if (!$f) {
     					$this->get('logger')->info("create new record!");
     					$f = new Record();
						$f->setEnable(true);
     					$f->setHostname($rr->name);
     					$f->setZone($z);
     					$z->addRecord($f);
      					$em->persist($f);
	     			}
	     			
	     			# Record View
	     			$values = preg_split('/[\s]+/', $rr, 5);
	     			if ($rr->type == 'MX'){
	     				$pref = $rr->preference;
	     				$values[4] = $rr->exchange;
	     			}
	     			
     				foreach ($f->getViews() as $k => $val) {
     					if ($val->getRecordtype() == $rr->type &&
     						$val->getDestination() == $values[4] &&
     						$val->getView() == $v &&
     						$val->getRecords() == $rr->name) {
     						$rv = $val;
     						break;
     					}
     				}
     				
     				if (!$rv) {
     					$this->get('logger')->info("create new view-record!");
     					$rv = new RecordView();
     					$rv->setRecordtype($rr->type);
     					$rv->setDestination($values[4]);
     					$rv->setView($v);
     					#FIXME: esto esta bien?????
     					$rv->setRecords($f);
     					$f->addView($rv);
     				}

	     			if ($rr->type == 'MX'){
	     				$rv->setMx($pref);
	     			}
	     			
	     			$rv->setTtl($rr->ttl);
	     			#FIXME: 5???

	     			
	     			$em->persist($rv);
	     			
	     		}
			}
    	}
    	
    	if ($this->getRequest()->get('confirm')) {
    		$em->flush();
    		$this->get('session')->getFlashBag()->add(
    				'notice',
    				$domain. ' ha sido importado!'
    		);
    		
//     		return $this->redirect(
//     					$this->generateUrl('dns_manager_zone', array('domain' => $domain))
//     		);
    	}
    	$import = $this->getRequest();
    	
    	$import = $this->createForm(new ZoneImportType());
    	$import->bind($this->getRequest());
    	
    	
    	return $this->render('dnsManagerBundle:Zone:import.html.twig',
    			array(
    					'error' => $e,
    					'result' => $result,
    					'domain' => $params['domain'],
    					'forms' => $ff,
    					'zone' => $z,
    					'view' => $v,
    					'import' => $import->createView()
    			)
    	);

    }
    
    public function updateAction($domain = NULL) 
    {
    	if ($domain) {
    		$zone = $this->getDoctrine()
    		->getManager()
    		->getRepository('dnsManagerBundle:Zone')
    		->findOneByDomain($domain);
      } else 
    		$zone = new Zone();
    	
    	$form = $this->createForm(new ZoneType(), $zone);
    	
    	$form->bind($this->getRequest());
    	
    	if ($form->isValid()) {
    		$em = $this->getDoctrine()
    			->getManager();
    		
    		#FIXME: Force update serial really ?
    		$zone->setSerial();
    		
    		$em->persist($zone);
    		$em->flush();
    		
    		return $this->redirect(
	     				$this->generateUrl('dns_manager_zone', array('domain' => $zone->getDomain()))
    				);

    	}
    	
    	return $this->redirect(
    			$this->generateUrl('dns_manager_homepage')
    			);    	
    	
    }
    
    public function editAction($domain) 
    {
    	
    	$zone  = $this->getDoctrine()
    		->getManager()
    		->getRepository('dnsManagerBundle:Zone')
    		->findOneByDomain($domain);
    	
    	$form = $this->createForm(new ZoneType(), $zone);
    	
    	return $this->render('dnsManagerBundle:Zone:edit.html.twig', 
    				array('form' => $form->createView(), 'domain' => $domain)
    			);
    }
    
    public function deleteAction($id) {
    	$obj = $this->getDoctrine()
    	->getManager()
    	->getRepository('dnsManagerBundle:Zone')
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
    			SELECT z FROM dnsManagerBundle:Zone z
    				WHERE z.domain LIKE :domain
    			')
    	->setParameter(":domain", "%$text%")
    	->getResult();
        	 
    		
    		 
    		#return $this->container->get('markdown.parser')->transformMarkdown($text);
    		return $this->render('dnsManagerBundle:Zone:list.html.twig', array('doms' => $zones));
    }
    
}

