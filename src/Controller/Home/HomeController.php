<?php

namespace App\Controller\Home;

use App\Service\BlogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home_index")
     */
    public function index(BlogService $blogService): Response
    {
        $blogs = $blogService->getActiveBlogs();

        return $this->render('home/home/index.html.twig', [
            'blogs' => $blogs,
        ]);
    }
}
