<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PruebasController extends Controller
{

    public function indexAction(Request $request, $name, $page)
    {

        //return $this->redirect($this->generateUrl("helloWorld"));
        //return $this->redirect($this->container->get("router")->getContext()->getBaseUrl()."/hello-world?hola=true");
        //return $this->redirect($request->getBaseUrl()."/hello-world?hola=true");

        /*var_dump($request->query->get('hola'));
        var_dump($request->get('hola-post'));
        die();*/

        $productos = array(
            array("producto"=>"consola 1","precio"=>2),
            array("producto"=>"consola 2","precio"=>2),
            array("producto"=>"consola 3","precio"=>2),
            array("producto"=>"consola 4","precio"=>2),
            array("producto"=>"consola 5","precio"=>2),
        );

        $fruta = array("manzana"=>"golden","pera"=>"rica");

        // replace this example code with whatever you need
        return $this->render('AppBundle:pruebas:index.html.twig', array(
            "texto" =>  $name." - ".$page,
            "productos" => $productos,
            "fruta" => $fruta
        ));
    }
}
