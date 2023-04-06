<?php

namespace App\Service;

use App\Entity\AgeBetween;
use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\Status;
use App\Repository\AgeBetweenRepository;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class BlogService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var BlogRepository
     */
    private $blogRepository;
    /**
     * @var StatusRepository
     */
    private $statusRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var AgeBetweenRepository
     */
    private $ageBetweenRepository;

    public function __construct(
        EntityManagerInterface $em,
        BlogRepository $blogRepository,
        StatusRepository $statusRepository,
        CategoryRepository $categoryRepository,
        AgeBetweenRepository $ageBetweenRepository
    )
    {
        $this->em = $em;
        $this->blogRepository = $blogRepository;
        $this->statusRepository = $statusRepository;
        $this->categoryRepository = $categoryRepository;
        $this->ageBetweenRepository = $ageBetweenRepository;
    }

    public function getAllBlogs(Request $request)
    {
        return $this->blogRepository->search($request);
    }

    public function getAllStatus(): array
    {
        return $this->statusRepository->findAll();
    }

    public function getActiveBlogs()
    {
        return $this->em->getRepository(Blog::class)->findBy(['deletedAt' => null]);
    }


    public function getAllAgeBetween(): array
    {
        return $this->ageBetweenRepository->findAll();
    }

    public function getAllCategory(): array
    {
        return $this->categoryRepository->findAll();
    }

    public function create(Request $request): Blog
    {
        $blog = new Blog();
        $blog->setTitle($request->request->get('title'));
        $blog->setContent($request->request->get('content'));
        $blog->setCategory($this->em->getRepository(Category::class)->find($request->request->get('category')));
        $blog->setStatus($this->em->getRepository(Status::class)->find($request->request->get('status')));
        $blog->setAgeBetween($this->em->getRepository(AgeBetween::class)->find($request->request->get('ageBetween')));
        $blog->setCreatedAt(new \DateTime());


        $this->em->persist($blog);
        $this->em->flush();

        return $blog;
    }

    public function update($id, Request $request)
    {
        /** @var Blog $blog */
        $blog = $this->em->getRepository(Blog::class)->findOneBy(['id' => $id]);

        $blog->setTitle($request->request->get('title'));
        $blog->setContent($request->request->get('content'));
        $blog->setCategory($this->em->getRepository(Category::class)->find($request->request->get('category')));
        $blog->setStatus($this->em->getRepository(Status::class)->find($request->request->get('status')));
        $blog->setAgeBetween($this->em->getRepository(AgeBetween::class)->find($request->request->get('ageBetween')));

        $this->em->persist($blog);
        $this->em->flush();

        return $blog;
    }

    public function delete($id)
    {
        $blog = $this->em->getRepository(Blog::class)->find($id);
        $blog->setDeletedAt(new \DateTime());

        $this->em->persist($blog);
        $this->em->flush();
    }
}
