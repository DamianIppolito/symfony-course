<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Curso;
use AppBundle\Form\CursoType;
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

        /*$curso_ochenta = $cursos_repo->findBy(array("precio"=>100));
        echo $curso_ochenta[0]->getTitulo();*/

        $curso_ochenta = $cursos_repo->findOneByPrecio(100);
        echo $curso_ochenta->getTitulo();

//        foreach ($cursos as $curso){
//            echo $curso->getTitulo()."<br/>";
//            echo $curso->getDescripcion()."<br/>";
//            echo $curso->getPrecio()."<br/><hr/>";
//        }

        die();
    }

    public function updateAction($id, $titulo, $descripcion, $precio){
        $em = $this->getDoctrine()->getEntityManager();
        $cursos_repo = $em->getRepository("AppBundle:Curso");
        $curso = $cursos_repo->find($id);
        $curso->setTitulo($titulo);
        $curso->setDescripcion($descripcion);
        $curso->setPrecio($precio);

        $em->persist($curso);
        $flush = $em->flush();
        if($flush != null){
            echo "El curso ha fallado al actualizarse";
        }else{
            echo "El curso se ha actualizado correctamente";
        }

        die();
    }

    public function deleteAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $cursos_repo = $em->getRepository("AppBundle:Curso");
        $curso = $cursos_repo->find($id);
        $em->remove($curso);
        $flush = $em->flush();
        if($flush != null){
            echo "El curso ha fallado al ser eliminado";
        }else{
            echo "El curso se ha eliminado correctamente";
        }

        die();
    }

    public function nativeSqlAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();
        $query = "SELECT * FROM cursos";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);

        $cursos = $stmt->fetchAll();
        foreach ($cursos as $curso){
            echo $curso["titulo"]."</br>";
        }

        die();
    }

    public function dqlAction(){
        $em = $this->getDoctrine()->getEntityManager();

        $query = $em->createQuery("
            SELECT c FROM AppBundle:Curso c
            WHERE c.precio > :precio
        ")->setParameter("precio","80");
        $cursos = $query->getResult();
        foreach ($cursos as $curso){
            echo $curso->getTitulo()."</br>";
        }

        die();
    }

    public function queryBuilderAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $cursos_repo = $em->getRepository("AppBundle:Curso");

//        $query = $cursos_repo->createQueryBuilder("c")
//            ->where("c.precio > :precio")
//            ->setParameter("precio","79")
//            ->getQuery();
//        $cursos = $query->getResult();

        $cursos = $cursos_repo->getCursos();
        foreach ($cursos as $curso){
            echo $curso->getTitulo()."</br>";
        }

        die();
    }

    public function formAction(Request $request){

        $curso = new Curso();
        $form = $this->createForm(CursoType::class, $curso);

        $form->handleRequest($request);
        if($form->isValid()){
           $status = "Formulario Valido";
           $data = array(
               "titulo" => $form->get("titulo")->getData(),
               "descripcion" => $form->get("descripcion")->getData(),
               "precio" => $form->get("precio")->getData(),
           );
        }else{
            $status = null;
            $data = null;
        }

        return $this->render('AppBundle:pruebas:form.html.twig', array(
            "form" =>  $form->createView(),
            "status" => $status,
            "data" => $data
        ));
    }
}
