<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Form\ImportFormType;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use League\Csv\Reader;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        $evenements = $evenementRepository->findBy([], ['beginAt' => 'DESC']);

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements
        ]);
    }

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EvenementRepository $evenementRepository): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenement->setUser($this->getUser());
            $evenement->setBeginAt($evenement->getBeginAt()->setTimezone(new \DateTimeZone('Europe/Paris')));
            $evenement->setUpdatedAt($evenement->getBeginAt()->setTimezone(new \DateTimeZone('Europe/Paris')));
            $evenement->setEndAt($evenement->getEndAt()->setTimezone(new \DateTimeZone('Europe/Paris')));
            $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/import', name: 'app_evenement_import', methods: ['GET', 'POST'])]
    public function import(Request $request, EvenementRepository $evenementRepository): Response
    {
        $form = $this->createForm(ImportFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $csvFile = $form->get('csvFile')->getData();

            if ($csvFile->getMimeType() == 'text/csv' || $csvFile->getMimeType() == 'application/vnd.ms-excel' || $csvFile->getMimeType() ==  'text/plain' || $csvFile->getMimeType() == 'text/tsv') {
                $csv = Reader::createFromPath($csvFile->getRealPath());
                $csv->setHeaderOffset(0);
                $records = $csv->getRecords();
                foreach ($csv as $record) {
                    $evenement = new Evenement();
                    $evenement->setTitle($record['title']);
                    $evenement->setDescription($record['description']);
                    $evenement->setBeginAt(new \DateTime($record['begin_at']));
                    $evenement->setEndAt(new \DateTime($record['end_at']));
                    $evenement->setUpdatedAt(new \DateTime('now'));
                    $evenement->setPublic(1);
                    $evenement->setUser(null);
                    $evenement->setBackgroundcolor($record['backgroundcolor']);
                    $evenement->setBordercolor($record['bordercolor']);
                    $evenement->setTextcolor($record['textcolor']);

                    $evenementRepository->save($evenement, true);

                    return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
                }
            } else {
                $this->addFlash('danger', 'Le fichier n\'est pas au format CSV');
            }
        }

        return $this->renderForm('evenement/import.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($evenement->getUser() == $this->getUser()) {
                $evenement->setUpdatedAt(new \DateTime('now'));
                $evenementRepository->save($evenement, true);

                return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('danger', 'Vous n\'êtes pas le propriétaire de cet événement');
            }
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement, true);
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
