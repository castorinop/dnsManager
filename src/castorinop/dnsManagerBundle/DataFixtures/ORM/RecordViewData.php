<?php

namespace castorinop\dnsManagerBundle\DataFixtures\ORM;

use castorinop\dnsManagerBundle\Entity\RecordView;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class RecordViewData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		
		$rv1 = new RecordView();
		$rv1->setDestination('1.0.0.1');
		$rv1->setRecordtype('A');
		$rv1->setView($manager->merge($this->getReference("view-1")));
		$rv1->setRecords($manager->merge($this->getReference("record-1")));
		$this->addReference('recordview-1',$rv1);
		$manager->persist($rv1);

		$rv2 = new RecordView();
		$rv2->setDestination('172.16.0.1');
		$rv2->setRecordtype('A');
		$rv2->setView($manager->merge($this->getReference("view-2")));
		$rv2->setRecords($manager->merge($this->getReference("record-1")));
		$this->addReference('recordview-2',$rv2);
		$manager->persist($rv2);
		
		$rv3 = new RecordView();
		$rv3->setDestination('10.200.0.1');
		$rv3->setRecordtype('A');
		$rv3->setView($manager->merge($this->getReference("view-3")));
		$rv3->setRecords($manager->merge($this->getReference("record-1")));
		$this->addReference('recordview-3',$rv3);
		$manager->persist($rv3);
		
		$rv4 = new RecordView();
		$rv4->setDestination('1.0.0.1');
		$rv4->setRecordtype('A');
		$rv4->setView($manager->merge($this->getReference("view-1")));
		$rv4->setRecords($manager->merge($this->getReference("record-7")));
		$this->addReference('recordview-4',$rv4);
		$manager->persist($rv4);
		
		$rv5 = new RecordView();
		$rv5->setDestination('1.0.0.1');
		$rv5->setRecordtype('A');
		$rv5->setView($manager->merge($this->getReference("view-1")));
		$rv5->setRecords($manager->merge($this->getReference("record-6")));
		$this->addReference('recordview-5',$rv5);
		$manager->persist($rv5);
		
		$rv6 = new RecordView();
		$rv6->setDestination('1.0.0.1');
		$rv6->setRecordtype('A');
		$rv6->setView($manager->merge($this->getReference("view-1")));
		$rv6->setRecords($manager->merge($this->getReference("record-5")));
		$this->addReference('recordview-6',$rv6);
		$manager->persist($rv6);
		
		$manager->flush();

	}

	public function getOrder()
	{
		return 20; // the order in which fixtures will be loaded
	}
}