<?php

namespace App\Controller;

use App\Message\RecordHitMessage;
use App\Repository\ShortUrlRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/{urlId}', name: 'redirection', requirements: ['urlId' => '^(?!api).*$'])]
    public function redirectToLongUrl(
        string $urlId,
        ShortUrlRepository $repository,
        MessageBusInterface $messageBus
    ): RedirectResponse
    {
        $shortUrl = $repository->findOneBy(['shortUrl' => $urlId]);

        $messageBus->dispatch(new RecordHitMessage($shortUrl->getId()));

        return $this->redirect($shortUrl->getLongUrl());
    }
}
