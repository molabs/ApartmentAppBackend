<?php

namespace Moci\ApartemensBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Moci\ApartemensBundle\Entity\Apartment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $apartments = $this->getDoctrine()
            ->getRepository('MociApartemensBundle:Apartment')
            ->findAll();

        return $this->render('MociApartemensBundle:Default:index.html.twig', array('apartments'=>$apartments));
    }
}
