<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/inscription', name: 'inscription', methods:["GET", "POST"])]
    public function inscription(UtilisateurRepository $utilisateurRepository): Response
    {
        $formulaire = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class,
        $formulaire, [
            'method' => 'POST',
            'action' => $this->generateUrl('inscription')
        ]);

        return $this->render("/utilisateur/inscription.html.twig", [
//            "utilisateurs" => $utilisateurRepository->findAllOrderedByDate(),
            "formulaire" => $form
        ]);
    }
}
