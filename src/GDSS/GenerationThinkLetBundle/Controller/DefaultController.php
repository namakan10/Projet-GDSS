<?php

namespace GDSS\GenerationThinkLetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GDSSGenerationThinkLetBundle:Default:index.html.twig');
    }
}
