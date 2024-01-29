<?php
namespace App;

use App\Entity\Product;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class GetProductsWithHateoas
{
    public function __construct(private RouterInterface $routerInterface) {}
    public function products(?array $products): array
    {
        $productListWithHateoasLinks = [];
        if($products)
        {
            foreach($products as $product)
        {
            $productListWithHateoasLinks[] = [
                "product" => $product,
                "_links" => [
                    "self" => $this->routerInterface->generate("get_product", ["id" => $product->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                ]
            ];
        }
        }
        return $productListWithHateoasLinks;
    }
    public function product(?Product $product): array
    {
        $productWithHateoasLinks = [];
        if($product)
        {
            $productWithHateoasLinks[] = [
                "product" => $product,
                "_links" => [
                    "self" => $this->routerInterface->generate("get_product", ["id" => $product->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                ]
            ];
        }
        return $productWithHateoasLinks;
    }
}