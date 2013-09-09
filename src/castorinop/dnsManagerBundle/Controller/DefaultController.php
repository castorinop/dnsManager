<?php

namespace castorinop\dnsManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('dnsManagerBundle:Default:index.html.twig', array('name' => $name));
    }
}
