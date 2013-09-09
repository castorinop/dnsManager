<?php

namespace castorinop\dnsManagerBundle\DataFixtures\ORM;

use castorinop\dnsManagerBundle\Entity\View;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ViewData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		
		$v1 = new View();
		$v1->setName('external');
		$this->addReference('view-1',$v1);
		$manager->persist($v1);
		
		$v2 = new View();
		$v2->setName('internal');
		$this->addReference('view-2',$v2);
		$manager->persist($v2);
		
		$v3 = new View();
		$v3->setName('admin');
		$this->addReference('view-3',$v3);
		$manager->persist($v3);
		
		$manager->flush();

	}

	public function getOrder()
	{
		return 10; // the order in which fixtures will be loaded
	}
}