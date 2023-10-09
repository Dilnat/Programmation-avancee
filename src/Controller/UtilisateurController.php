<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Service\FlashMessageHelperInterface;
use App\Service\UtilisateurManager;
use App\Service\UtilisateurManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UtilisateurController extends AbstractController
{
    #[Route('/inscription', name: 'inscription', methods:["GET", "POST"])]
    public function inscription(Request $request ,
                                UtilisateurRepository $utilisateurRepository,
                                EntityManagerInterface $entityManager,
                                FlashMessageHelperInterface $flashMessageHelper,
                                UtilisateurManagerInterface $utilisateurManagerInterface): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class,
            $utilisateur, [
            'method' => 'POST',
            'action' => $this->generateUrl('inscription')
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash("success", "Inscription réussie");
            $plainPassword = $form["plainPassword"]->getData();
            $fichierPhotoProfil = $form["fichierPhotoProfil"]->getData();
            $utilisateurManagerInterface->processNewUtilisateur($utilisateur, $plainPassword, $fichierPhotoProfil);
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            return $this->redirectToRoute('feed');
        }
        else{
            $flashMessageHelper->addFormErrorsAsFlash($form);
        }

        return $this->render("/utilisateur/inscription.html.twig", [
//            "utilisateurs" => $utilisateurRepository->findAllOrderedByDate(),
            "formulaire" => $form
        ]);
    }

    #[Route('/connexion', name: 'connexion', methods:["GET", "POST"])]
    public function connexion(AuthenticationUtils $authenticationUtils) : Response {
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('utilisateur/connexion.html.twig', [
            "lastusername" => $lastUsername
        ]);
    }
}
