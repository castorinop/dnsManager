<?php

namespace castorinop\dnsManagerBundle\DataFixtures\ORM;

use castorinop\dnsManagerBundle\Entity\Record;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class RecordData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		
		$r1 = new Record();
		$r1->setHostname('correo');
		$r1->setZone($manager->merge($this->getReference("zone-1")));
		
		$this->addReference('record-1',$r1);
		$manager->persist($r1);
		
		$r2 = new Record();
		$r2->setHostname('www');
		$r2->setZone($manager->merge($this->getReference("zone-1")));
		
		$this->addReference('record-2',$r2);
		$manager->persist($r2);
		
		$r3 = new Record();
		$r3->setHostname('ftp');
		$r3->setZone($manager->merge($this->getReference("zone-1")));
		
		$this->addReference('record-3',$r3);
		$manager->persist($r3);
		
		$r4 = new Record();
		$r4->setHostname('nube');
		$r4->setZone($manager->merge($this->getReference("zone-1")));
		
		$this->addReference('record-4',$r4);
		$manager->persist($r4);
		
		$r5 = new Record();
		$r5->setHostname('sndb');
		$r5->setZone($manager->merge($this->getReference("zone-1")));
		
		$this->addReference('record-5',$r5);
		$manager->persist($r5);
		
		$r6 = new Record();
		$r6->setHostname('www');
		$r6->setDomain($r5);
// 		$r6->setZone($manager->merge($this->getReference("zone-1")));
		$this->addReference('record-6',$r6);
		$manager->persist($r6);
		
		$r7 = new Record();
		$r7->setHostname('datos');
		$r7->setDomain($r5);
// 		$r7->setZone($manager->merge($this->getReference("zone-1")));
		
		$this->addReference('record-7',$r7);
		$manager->persist($r7);
		
		$manager->flush();

	}

	public function getOrder()
	{
		return 15; // the order in which fixtures will be loaded
	}
}