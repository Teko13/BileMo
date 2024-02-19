<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\GetProductsWithHateoas;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ProductController extends AbstractController
{
    public function __construct(private TagAwareCacheInterface $tagAwareCacheInterface, private GetProductsWithHateoas $getProductsWithHateoas, private ProductRepository $productRepository, private SerializerInterface $serializerInterface) {}
    #[Route('/api/products', name: 'get_products', methods: ["GET"])]
    public function getProducts(): JsonResponse
    {
        $cacheId = "productsId";
        $jsonProducts = $this->tagAwareCacheInterface->get($cacheId, function (ItemInterface $item) {
             echo 'test';
            $item->expiresAfter(3600*24); // cache item expire after 1 day
            $products = $this->getProductsWithHateoas->products($this->productRepository->findAll());
            return $this->serializerInterface->serialize($products, "json");
        });
        return new JsonResponse(
            $jsonProducts,
            JsonResponse::HTTP_OK,
            [],
            true,
        );
    }
    #[Route("/api/products/{id}", name: "get_product", methods: ["GET"])]
    public function getProduct(Product $product): JsonResponse
    {
        $productWhitHateos = $this->getProductsWithHateoas->product($product);
        $jsonProduct = $this->serializerInterface->serialize($productWhitHateos, "json");
        return new JsonResponse(
            $jsonProduct,
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
