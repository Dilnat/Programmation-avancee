<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Service\FlashMessageHelperInterface;
use App\Service\UtilisateurManager;
use App\Service\UtilisateurManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
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
        //deja connecté
        if ($this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('feed');
        }

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
        //deja connecté
        if ($this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('feed');
        }

        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('utilisateur/connexion.html.twig', [
            "lastusername" => $lastUsername
        ]);
    }

    #[Route('/deconnexion', name: 'deconnexion', methods: ['POST'])]
    public function deconnexion(): never
    {
        //Ne sera jamais appelée
        throw new \Exception("Cette route n'est pas censée être appelée. Vérifiez security.yaml");
    }

    #[Route('/utilisateurs/{login}/feed', name:'pagePerso', methods:['GET'])]
    public function pagePerso(#[MapEntity] ?Utilisateur $utilisateur,
                            FlashMessageHelperInterface $flashMessageHelper) : Response {

        if ($utilisateur == null){
            $flashMessageHelper->addErrorsAsFlash('Utilisateur inexistant');
            $this->redirectToRoute('feed');
        }

        return $this->render('utilisateur/page_perso.html.twig', [
            'utilisateur' => $utilisateur
        ]);
    }
}
