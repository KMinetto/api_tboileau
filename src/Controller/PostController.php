<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/posts")
 */
class PostController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private UrlGeneratorInterface $generator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, UrlGeneratorInterface $generator)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->generator = $generator;
    }


    /**
     * @Route(name="api_posts_collection_get", methods={"GET"})
     * @param PostRepository $postRepository
     * @return JsonResponse
     */
    public function collection(PostRepository $postRepository): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($postRepository->findAll(), "json", ["groups" => "get"]),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}" ,name="api_posts_item_get", methods={"GET"})
     * @param Post $post
     * @return JsonResponse
     */
    public function item(Post $post): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($post, "json", ["groups" => "get"]),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route(name="api_posts_collection_post", methods={"POST"})
     * @param Post $post
     * @return JsonResponse
     */
    public function createPost(Post $post): JsonResponse
    {
        /** @var Post $post */
        $post->setAuthor($this->entityManager->getRepository(User::class)->findOneBy([]));

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return new JsonResponse(
            $this->serializer->serialize($post, "json", ["groups" => "get"]),
            Response::HTTP_CREATED,
            ["Location" => $this->generator->generate("api_posts_collection_post", ["id" => $post->getId()])],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_posts_item_put", methods={"PUT"})
     * @param Post $post
     * @return JsonResponse
     */
    public function modifyPost(Post $post): JsonResponse
    {
        $this->entityManager->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{id}", name="api_posts_item_delete", methods={"DELETE"})
     * @param Post $post
     * @return JsonResponse
     */
    public function removePost(Post $post): JsonResponse
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}