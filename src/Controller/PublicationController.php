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
        $formulaire = new Publication();
        $form = $this->createForm(PublicationType::class,
        $formulaire,[
            'method' => 'POST',
            'action' => $this->generateUrl('feed')
            ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->denyAccessUnlessGranted('ROLE_USER');
            $this->addFlash("success", "Vous message a bien été publié");
            $entityManager->persist($formulaire);
            $entityManager->flush();
            return $this->redirectToRoute('feed');
        }
        else{
            $flashMessageHelper->addFormErrorsAsFlash($form);
        }



        return $this->render("/publication/feed.html.twig", [
            "publications" => $publicationRepository->findAllOrderedByDate(),
            "formulaire" => $form
        ]);
    }
}
