<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Curso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PruebasController extends Controller
{

    public function indexAction(Request $request, $name, $page){

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

    public function createAction(){
        $curso = new Curso();
        $curso->setTitulo("Curso de Symfony3 de Victor Robles");
        $curso->setDescripcion("Curso completo de symfony3");
        $curso->setPrecio(80);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($curso);
        $flush = $em->flush();
        if($flush != null){
            echo "El curso ha fallado al crease";
        }else{
            echo "El curso se ha creado correctamente";
        }

        die();
    }

    public function readAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $cursos_repo = $em->getRepository("AppBundle:Curso");
        $cursos = $cursos_repo->findAll();
        foreach ($cursos as $curso){
            echo $curso->getTitulo()."<br/>";
            echo $curso->getDescripcion()."<br/>";
            echo $curso->getPrecio()."<br/><hr/>";
        }

        die();
    }
}
