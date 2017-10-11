<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Entry;
use BlogBundle\Form\EntryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class EntryController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexAction(){

        $em = $this->getDoctrine()->getEntityManager();
        $category_repo = $em->getRepository("BlogBundle:Category");
        $categories = $category_repo->findAll();

        return $this->render("BlogBundle:Category:index.html.twig", array(
            "categories" => $categories
        ));
    }

    public function addAction(Request $request){
        $entry = new Entry();
        $form = $this->createForm(EntryType::class, $entry);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if ($form->isValid()) {

//                $em = $this->getDoctrine()->getEntityManager();
//
//                $entry = new Entry();
//                $entry->setTitle($form->get("title")->getData());
//                $entry->setContent($form->get("content")->getData());
//
//                $em->persist($entry);
//                $flush = $em->flush();
//                if($flush == null){
//                    $status = "La categoría se ha creado correctamente";
//                    $messagebox = 'success';
//                }else{
//                    $status = "Error al añadir la nueva categoría";
//                    $messagebox = 'danger';
//                }
            }else{
                $status = "La categoría no se ha creado porque hay fallos de validación";
                $messagebox = 'danger';
            }

//            $this->session->getFlashBag()->add("status", $status);
//            $this->session->getFlashBag()->add("messagebox", $messagebox);
//
//            return $this->redirectToRoute('blog_index_category');
        }

        return $this->render("BlogBundle:Entry:add.html.twig", array(
            "form" => $form->createView()
        ));
    }

    public function deleteAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $category_repo = $em->getRepository("BlogBundle:Category");
        $category = $category_repo->find($id);
        if(count($category->getEntry()) == 0){
            $em->remove($category);
            $em->flush();
        }
        return $this->redirectToRoute('blog_index_category');
    }

    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getEntityManager();
        $category_repo = $em->getRepository("BlogBundle:Category");
        $category = $category_repo->find($id);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $category->setName($form->get("name")->getData());
                $category->setDescription($form->get("description")->getData());

                $em->persist($category);
                $flush = $em->flush();
                if($flush == null){
                    $status = "La categoría se ha editado correctamente";
                    $messagebox = 'success';
                }else{
                    $status = "Error al editar la categoría";
                    $messagebox = 'danger';
                }
            }else{
                $status = "La categoría no se ha editado porque hay fallos de validación";
                $messagebox = 'danger';
            }

            $this->session->getFlashBag()->add("status", $status);
            $this->session->getFlashBag()->add("messagebox", $messagebox);

            return $this->redirectToRoute('blog_index_category');
        }

        return $this->render("BlogBundle:Category:edit.html.twig", array(
            "form" => $form->createView()
        ));
    }
}
