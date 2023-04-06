<?php

namespace App\Controller\Admin;

use App\Entity\AgeBetween;
use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\Status;
use App\Service\BlogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     */
    public function index(Request $request, BlogService $blogService): Response
    {
        $blogs = $blogService->getAllBlogs($request);
        //dd($blogs);
        return $this->render('admin/index/index.html.twig', [
            'blogs' => $blogs,
            'ageBetween' => $blogService->getAllAgeBetween(),
            'status' => $blogService->getAllStatus(),
            'category' => $blogService->getAllCategory()
        ]);
    }

    /**
     * @Route("/create-blog", name="create-blog")
     * @param Request $request
     * @param BlogService $blogService
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(
        Request $request,
        BlogService $blogService,
        EntityManagerInterface $em
    ): Response
    {
        $ageBetween = $em->getRepository(AgeBetween::class)->findAll();
        $category = $em->getRepository(Category::class)->findAll();
        $status = $em->getRepository(Status::class)->findAll();
        if ($request->isMethod('POST')) {
            $blogService->create($request);

            return $this->redirectToRoute('admin_index');
        }
        return $this->render('admin/index/create.html.twig', [
            'ageBetween' => $ageBetween,
            'category' => $category,
            'status' => $status
        ]);
    }

    /**
     * @Route("/update-blog/{id}", name="update-blog")
     */
    public function update(
        $id,
        Request $request,
        BlogService $blogService,
        EntityManagerInterface $em
    )
    {
        $ageBetween = $em->getRepository(AgeBetween::class)->findAll();
        $category = $em->getRepository(Category::class)->findAll();
        $status = $em->getRepository(Status::class)->findAll();
        $blog = $em->getRepository(Blog::class)->find($id);
        if ($request->isMethod('POST')) {
            $blogService->update($id, $request);

            return $this->redirectToRoute("admin_index");
        }

        return $this->render('admin/index/update.html.twig', [
            'blog' => $blog,
            'ageBetween' => $ageBetween,
            'category' => $category,
            'status' => $status
        ]);
    }

    /**
     * @Route("/delete-blog/{id}", name="delete-blog")
     */
    public function delete($id, BlogService $blogService)
    {
        $blogService->delete($id);
        return $this->redirectToRoute("admin_index");
    }
}
