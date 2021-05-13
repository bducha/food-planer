<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Http\ApiResponse;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: "/api/product", name: "api_product_")]
class ProductController extends AbstractController
{
    #[Route(
        path: "",
        name: "add",
        requirements: ["_format" => "json"],
        methods: ["POST"],
    )]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ): Response {
        $product = $serializer->deserialize($request->getContent(), Product::class, "json");
        try {
            $entityManager->persist($product);
            $entityManager->flush();
        } catch (ORMException $e) {
            return $this->json(["message" => "Unknown error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->json(new ApiResponse("Product created"), Response::HTTP_CREATED);
    }

    #[Route(
        path: "",
        name: "all",
        methods: ["GET"],
    )]
    public function findAll(
        ProductRepository $productRepository
    ): Response {
        return $this->json(new ApiResponse(data: $productRepository->findAll()));
    }

    #[Route(
        path: "/{id}",
        name: "update",
        requirements: ["_format" => "json"],
        methods: ["PATCH"],
    )]
    public function update(
        Request $request,
        int $id,
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository
    ): Response {
        $data = json_decode($request->getContent(), true);
        $product = $productRepository->find($id);
        if (!$product) {
            return $this->json(["message" => "Product not found"], Response::HTTP_NOT_FOUND);
        }
        $product->setName($data['name'] ?? $product->getName());
        $product->setUnit($data['unit'] ?? $product->getUnit());
        try {
            $entityManager->persist($product);
            $entityManager->flush();
        } catch (ORMException $e) {
            return $this->json(["message" => "Unknown error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->json(new ApiResponse("Product updated"), Response::HTTP_OK);
    }

    #[Route(
        path: "/{id}",
        name: "delete",
        methods: ["DELETE"],
    )]
    public function delete(
        int $id,
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository
    ): Response {
        $product = $productRepository->find($id);
        if (!$product) {
            return $this->json(["message" => "Product not found"], Response::HTTP_NOT_FOUND);
        }
        try {
            $entityManager->remove($product);
            $entityManager->flush();
        } catch (ORMException $e) {
            return $this->json(["message" => "Unknown error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->json(new ApiResponse("Product deleted"), Response::HTTP_OK);
    }
}
