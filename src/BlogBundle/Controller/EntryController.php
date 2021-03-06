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

    public function indexAction($page){

        $em = $this->getDoctrine()->getEntityManager();
        $entry_repo = $em->getRepository("BlogBundle:Entry");
        $category_repo = $em->getRepository("BlogBundle:Category");
        $categories = $category_repo->findAll();
        $pageSize = 1;
        $entries = $entry_repo->getPaginateEntries(1,$page);
        $totalItems = count($entries);
        $pagesCount = ceil($totalItems/$pageSize);

        return $this->render("BlogBundle:Entry:index.html.twig", array(
            "entries" => $entries,
            "categories" => $categories,
            "totalItems" => $totalItems,
            "pagesCount" => $pagesCount,
            "page" => $page,
            "page_m" => $page,
        ));
    }

    public function addAction(Request $request){
        $entry = new Entry();
        $form = $this->createForm(EntryType::class, $entry);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $entry_repo = $em->getRepository("BlogBundle:Entry");
                $category_repo = $em->getRepository("BlogBundle:Category");

                $file = $form['image']->getData();
                if(!empty($file) && $file!=null) {
                    $ext = $file->guessExtension();
                    $file_name = time() . "." . $ext;
                    $file->move('uploads', $file_name);
                }else{
                    $file_name = null;
                }
                $category = $category_repo->find($form->get("category")->getData());
                $user = $this->getUser();

                $entry = new Entry();
                $entry->setTitle($form->get("title")->getData());
                $entry->setContent($form->get("content")->getData());
                $entry->setStatus($form->get("status")->getData());
                $entry->setImage($file_name);
                $entry->setCategory($category);
                $entry->setUser($user);

                $em->persist($entry);
                $flush = $em->flush();

                $entry_repo->saveEntryTags(
                    $form->get("tags")->getData(),
                    $form->get("title")->getData(),
                    $form->get("category")->getData(),
                    $user
                );

                if($flush == null){
                    $status = "La categoría se ha creado correctamente";
                    $messagebox = 'success';
                }else{
                    $status = "Error al añadir la nueva categoría";
                    $messagebox = 'danger';
                }
            }else{
                $status = "La categoría no se ha creado porque hay fallos de validación";
                $messagebox = 'danger';
            }

            $this->session->getFlashBag()->add("status", $status);
            $this->session->getFlashBag()->add("messagebox", $messagebox);

            return $this->redirectToRoute('blog_homepage');
        }

        return $this->render("BlogBundle:Entry:add.html.twig", array(
            "form" => $form->createView()
        ));
    }

    public function deleteAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $entry_repo = $em->getRepository("BlogBundle:Entry");
        $entry_tag_repo = $em->getRepository("BlogBundle:EntryTag");
        $entry = $entry_repo->find($id);
        $entry_tags = $entry_tag_repo->findBy(array("entry"=>$entry));
        foreach ($entry_tags as $entry_tag){
            if(is_object($entry_tag)) {
                $em->remove($entry_tag);
                $em->flush();
            }
        }
        $em->remove($entry);
        $em->flush();
        return $this->redirectToRoute('blog_homepage');
    }

    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getEntityManager();
        $entry_repo = $em->getRepository("BlogBundle:Entry");
        $category_repo = $em->getRepository("BlogBundle:Category");
        $entry = $entry_repo->find($id);
        $entry_image = $entry->getImage();
        $tags = '';
        foreach ($entry->getEntryTag() as $entry_tag){
            $tags .= $entry_tag->getTag()->getName(). ", ";
        }
        $tags = substr($tags,0,-2);

        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $file = $form['image']->getData();
                if(!empty($file) && $file!=null) {
                    $ext = $file->guessExtension();
                    $file_name = time() . "." . $ext;
                    $file->move('uploads', $file_name);
                }else{
                    $file_name = $entry_image;
                }
                $category = $category_repo->find($form->get("category")->getData());
                $user = $this->getUser();

                $entry->setTitle($form->get("title")->getData());
                $entry->setContent($form->get("content")->getData());
                $entry->setStatus($form->get("status")->getData());
                $entry->setImage($file_name);
                $entry->setCategory($category);
                $entry->setUser($user);

                $em->persist($entry);
                $flush = $em->flush();

                $entry_tag_repo = $em->getRepository("BlogBundle:EntryTag");
                $entry_tags = $entry_tag_repo->findBy(array("entry"=>$entry));
                foreach ($entry_tags as $entry_tag){
                    if(is_object($entry_tag)) {
                        $em->remove($entry_tag);
                        $em->flush();
                    }
                }

                $entry_repo->saveEntryTags(
                    $form->get("tags")->getData(),
                    $form->get("title")->getData(),
                    $form->get("category")->getData(),
                    $user
                );
                if($flush == null){
                    $status = "La entrada se ha editado correctamente";
                    $messagebox = 'success';
                }else{
                    $status = "Error al editar la entrada";
                    $messagebox = 'danger';
                }
            }else{
                $status = "La entrada no se ha editado porque hay fallos de validación";
                $messagebox = 'danger';
            }

            $this->session->getFlashBag()->add("status", $status);
            $this->session->getFlashBag()->add("messagebox", $messagebox);

            return $this->redirectToRoute('blog_homepage');
        }

        return $this->render("BlogBundle:Entry:edit.html.twig", array(
            "form" => $form->createView(),
            "entry" => $entry,
            "tags" => $tags
        ));
    }
}
