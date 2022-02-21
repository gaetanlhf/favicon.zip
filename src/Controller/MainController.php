<?php

namespace App\Controller;

use App\Form\FaviconFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $faviconForm = $this->createForm(FaviconFormType::class);
        return $this->render('index.html.twig', [
            'faviconForm' => $faviconForm->createView()
        ]);
    }
}
