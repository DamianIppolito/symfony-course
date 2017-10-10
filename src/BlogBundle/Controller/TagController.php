<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use BlogBundle\Entity\Tag;
use BlogBundle\Form\TagType;

class TagController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function addAction(Request $request){
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $status = "la etiqueta se ha creado correctamente";
                $messagebox = 'success';
            }else{
                $status = "la etiqueta no se ha creado porque hay fallos de validación";
                $messagebox = 'danger';
            }

            $this->session->getFlashBag()->add("status", $status);
            $this->session->getFlashBag()->add("messagebox", $messagebox);
        }

        return $this->render("BlogBundle:Tag:add.html.twig", array(
            "form" => $form->createView()
        ));
    }
}
