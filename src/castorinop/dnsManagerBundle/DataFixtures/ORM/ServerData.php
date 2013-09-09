<?php

namespace castorinop\dnsManagerBundle\DataFixtures\ORM;

use castorinop\dnsManagerBundle\Entity\Server;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ServerData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		
		$s1 = new Server();
		$s1->setName('w4');
		
		$this->addReference('server-1',$s1);
		$manager->persist($s1);
		
		$s2 = new Server();
		$s2->setName('w3');
		
		$this->addReference('server-2',$s2);
		$manager->persist($s2);
		
		$s3 = new Server();
		$s3->setName('monitor');
		
		$this->addReference('server-3',$s3);
		$manager->persist($s3);
		
		$manager->flush();

	}

	public function getOrder()
	{
		return 10; // the order in which fixtures will be loaded
	}
}