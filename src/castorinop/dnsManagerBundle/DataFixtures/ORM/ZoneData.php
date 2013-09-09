<?php

namespace castorinop\dnsManagerBundle\DataFixtures\ORM;

use castorinop\dnsManagerBundle\Entity\Zone;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ZoneData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		
		$z1 = new Zone();
		$z1->setDomain('example.org');
		$z1->setSoa('ns1');
		$z1->setMail('master@example.org');
		$z1->setSerial();

		$this->addReference('zone-1',$z1);
		$manager->persist($z1);
		$manager->flush();
		
		$z2 = new Zone();
		$z2->setDomain('example.net');
		$z2->setAlias($z1);
		$z2->setSoa('ns1');
		$z2->setMail('master@example.org');
		$z2->setSerial();
		
		$this->addReference('zone-2',$z2);
		$manager->persist($z2);
		
		$z3 = new Zone();
		$z3->setDomain('another.com');
		$z3->setSoa('ns1');
		$z3->setMail('master@example.org');
		$z3->setSerial();
		$z3->setEnable(false);
		
		$this->addReference('zone-3',$z3);
		$manager->persist($z3);
		
		$manager->flush();

	}

	public function getOrder()
	{
		return 10; // the order in which fixtures will be loaded
	}
}