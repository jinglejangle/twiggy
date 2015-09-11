<?php

namespace shefphp\twiggyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('shefphptwiggyBundle:Default:index.html.twig', array('name' => $name));
    }
}
