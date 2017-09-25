<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PruebasController extends Controller
{

    /**
     * @Route("/pruebas/index", name="pruebasIndex")
     */
    public function indexAction(Request $request)
    {

        // replace this example code with whatever you need
        return $this->render('AppBundle:pruebas:index.html.twig', array(
            "texto" =>  "Te lo envio desde la acción del controlador"
        ));
    }
}
