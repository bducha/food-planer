<?php

namespace App\Controller\Api;

use App\Entity\Ingredient;
use App\Entity\Meal;
use App\Entity\Product;
use App\Http\ApiResponse;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: "/api/meal", name: "api_meal_")]
class MealController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route(
        '',
        name: 'add',
        requirements: ["_format" => "json"],
        methods: ["POST"]
    )]
    public function add(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $data = json_decode($request->getContent(), true);
        $meal = (new Meal())
            ->setName($data["name"])
            ->setQuantity($data["quantity"]);
        foreach ($data['ingredients'] as $ing) {
            $meal->addIngredient(
                (new Ingredient())
                    ->setQuantity($ing['quantity'])
                    ->setProduct($em->getPartialReference(Product::class, $ing['productId']))
            );
        }
        $em->persist($meal);
        $em->flush();
        return $this->json(new ApiResponse("Meal created"), Response::HTTP_CREATED);
    }

    /**
     * @param MealRepository $repository
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @return JsonResponse
     */
    #[Route("", name: "all", methods: ["GET"])]
    public function findAll(
        MealRepository $repository,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ): Response {
        try {
            $data = $serializer->normalize($repository->findAll(), "json", ["groups" => "meal"]);
        } catch (ExceptionInterface $e) {
            $logger->error(
                "Error while trying to normalize meal list",
                ["message" => $e->getMessage(), "trace" => $e->getTrace()]
            );
            return $this->json(new ApiResponse("Unkown error"), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->json(new ApiResponse("", $data));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param MealRepository $repository
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route(
        path: "/{id}",
        name: "update",
        requirements: ["_format" => "json"],
        methods: ["PATCH"],
    )]
    public function update(
        Request $request,
        int $id,
        MealRepository $repository,
        EntityManagerInterface $em
    ): Response {
        $data = json_decode($request->getContent(), true);
        $meal = $repository->find($id);
        if (!$meal) {
            return $this->json(new ApiResponse("Meal not found"), Response::HTTP_NOT_FOUND);
        }
        $meal
            ->setName($data['name'] ?? $meal->getName())
            ->setQuantity($data['quantity'] ?? $meal->getQuantity());
        if (is_array($data['addedIngredients'] ?? null)) {
            foreach ($data['addedIngredients'] as $ing) {
                $meal->addIngredient(
                    (new Ingredient())
                        ->setQuantity($ing['quantity'])
                        ->setProduct($em->getPartialReference(Product::class, $ing['productId']))
                );
            }
        }
        if (is_array($data['removedIngredients'] ?? null)) {
            foreach ($data['removedIngredients'] as $ing) {
                foreach ($meal->getIngredients() as $ingredient) {
                    if ($ingredient->getId() === $ing['id']) {
                        $meal->removeIngredient($ingredient);
                        break;
                    }
                }
            }
        }
        if (is_array($data['updatedIngredients'] ?? null)) {
            foreach ($data['updatedIngredients'] as $ing) {
                foreach ($meal->getIngredients() as $key => $ingredient) {
                    if ($ingredient->getId() === $ing['id']) {
                        $meal->getIngredients()[$key]->setQuantity(
                            $ing['quantity'] ?? $ingredient->getQuantity()
                        );
                        break;
                    }
                }
            }
        }
        $em->persist($meal);
        $em->flush();
        return $this->json(new ApiResponse("Meal updated"));
    }

    /**
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @param MealRepository $repository
     * @return Response
     */
    #[Route(
        path: "/{id}",
        name: "delete",
        methods: ["DELETE"],
    )]
    public function delete(
        int $id,
        EntityManagerInterface $entityManager,
        MealRepository $repository
    ): Response {
        $meal = $repository->find($id);
        if (!$meal) {
            return $this->json(["message" => "Meal not found"], Response::HTTP_NOT_FOUND);
        }
        try {
            $entityManager->remove($meal);
            $entityManager->flush();
        } catch (ORMException $e) {
            return $this->json(["message" => "Unknown error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->json(new ApiResponse("Meal deleted"), Response::HTTP_OK);
    }
}
