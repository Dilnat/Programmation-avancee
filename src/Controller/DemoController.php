<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemoController extends AbstractController
{
    #[Route('/hello', name: 'hello_get', methods: ["GET"])]
    public function hello() : Response{
        return $this->render("/demo/demo1.html.twig");
    }

    #[Route('/hello/{nom}', name:'hello_get2', methods:["GET"])]
    public function hello2($nom) : Response {
        $this->addFlash("alert", "Vous avez dit hello à " . $nom);
        return $this->render("/demo/demo2.html.twig", [
            "nom" => $nom
        ]);
    }

    #[Route('/courses', name:'courses', methods:["GET"])]
    public function courses() : Response {
        $listeCourses = ["lait", "oignons", "gnocchi", "kiwi"];
        return $this->render("demo/demo3.html.twig", [
            "listeCourses" => $listeCourses
        ]);
    }

}
