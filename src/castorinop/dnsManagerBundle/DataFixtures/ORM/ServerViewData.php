<?php

namespace castorinop\dnsManagerBundle\DataFixtures\ORM;

use castorinop\dnsManagerBundle\Entity\ServerView;

use castorinop\dnsManagerBundle\Entity\RecordView;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ServerViewData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		
		$sv1 = new ServerView();
		$sv1->setType('master');
		$sv1->setView($manager->merge($this->getReference("view-1")));
		$sv1->setServer($manager->merge($this->getReference("server-1")));
		$this->addReference('serverview-1',$sv1);
		$manager->persist($sv1);
		
		$sv2 = new ServerView();
		$sv2->setType('slave');
		$sv2->setView($manager->merge($this->getReference("view-1")));
		$sv2->setServer($manager->merge($this->getReference("server-2")));
		$this->addReference('serverview-2',$sv2);
		$manager->persist($sv2);
		
		$sv3 = new ServerView();
		$sv3->setType('master');
		$sv3->setView($manager->merge($this->getReference("view-3")));
		$sv3->setServer($manager->merge($this->getReference("server-3")));
		$th3->addReference('serverview-3',$sv3);
		$manager->persist($sv1);
		
		$manager->flush();

	}

	public function getOrder()
	{
		return 20; // the order in which fixtures will be loaded
	}
}