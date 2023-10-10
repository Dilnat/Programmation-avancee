<?php

namespace App\Controller;

use App\Form\PublicationType;
use App\Repository\PublicationRepository;
use App\Service\FlashMessageHelper;
use App\Service\FlashMessageHelperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// A importer au début da la classe
use App\Entity\Publication;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class PublicationController extends AbstractController
{
    #[Route('/', name: 'feed', methods:["GET", "POST"])]
    public function feed(Request $request,
                         PublicationRepository $publicationRepository,
                         EntityManagerInterface $entityManager,
                         FlashMessageHelperInterface $flashMessageHelper): Response
    {
        $publication = new Publication();
        $utilisateur = $this->getUser();
        $publication->setAuteur($utilisateur);

        $form = $this->createForm(PublicationType::class,
            $publication,[
            'method' => 'POST',
            'action' => $this->generateUrl('feed')
            ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->denyAccessUnlessGranted('ROLE_USER');
            $this->addFlash("success", "Vous message a bien été publié");
            $entityManager->persist($publication);
            $entityManager->flush();
            return $this->redirectToRoute('feed');
        }
        else{
            $flashMessageHelper->addFormErrorsAsFlash($form);
        }

        return $this->render("/publication/feed.html.twig", [
            "publications" => $publicationRepository->findAllOrderedByDate(),
            "formulaire_publication" => $form
        ]);
    }
}
