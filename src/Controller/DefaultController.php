<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Form\CandidatFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="show_home", methods={"GET"})
     */
    public function home(EntityManagerInterface $entityManager): Response
    {

        $candidats = $entityManager->getRepository(Candidat::class)->findAll();

        return $this->render('default/home.html.twig', [
            'candidats' => $candidats
        ]);
    } // END home()

    /**
     * @Route("/creer-candidat", name="create_candidat", methods={"GET|POST"})
     */
    public function createCandidat(Request $request, EntityManagerInterface $entityManager): Response
    {

//{# ==========__// ↓↓↓↓↓↓↓↓↓ \\__========== !!! ↓ CODE A EFFACER AVANT DE COMMENCER ↓ !!! =========__// ↓↓↓↓↓↓↓↓↓ \\__========= #}//
//        if( file_exists($this->getParameter('form_dir') . '/CandidatFormType.php') === false) {
//            $this->addFlash('warning', 'Il vous faut faire le formulaire <strong>CandidatFormType</strong> en ligne de commande, <strong>puis</strong> faire son traitement dans le <strong>DefaultController</strong>. À vos claviers ! Bon courage');
//            return $this->redirectToRoute('show_home');
//        }
//        return $this->redirectToRoute('show_home');
//{# ==========__// ↑↑↑↑↑↑↑↑↑ \\__========== !!! ↑ CODE A EFFACER AVANT DE COMMENCER ↑ !!! =========__// ↑↑↑↑↑↑↑↑↑ \\__========= #}//

        $candidat = new Candidat();

        $form = $this->createForm(CandidatFormType::class, $candidat)
            ->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($candidat);
            $entityManager->flush();

            return $this->redirectToRoute('show_home');

        }

        return $this->render('form/create_employe.html.twig', [
            'form' => $form->createView()
        ]);
    } # END createCandidat()

    /**
     * @Route("/archiver-un-candidat/{id}", name="soft_delete_candidat", methods={"GET"})
     */
    public function softDeleteCandidat(Candidat $candidat, EntityManagerInterface $entityManager): Response
    {
        $candidat->setArchivedAt(new \DateTime());

        $entityManager->persist($candidat);
        $entityManager->flush();

        $this->addFlash('success', 'Archivé');
        return $this->redirectToRoute('show_home');
    }

} # END DefaultController