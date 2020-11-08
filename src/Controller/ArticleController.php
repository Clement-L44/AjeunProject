<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\ArticleRepository;


class ArticleController extends AbstractController
{
    //Sans api-plateform
//    /**
//     * @Route("/accueil", name="GetArticlesForCategories", methods= {"GET"})
//     * @param SerializerInterface $serializer
//     * @param ArticleRepository $repository
//     * @return void
//     */
//    public function GetArticlesForCategories ( SerializerInterface $serializer, ArticleRepository $repository)
//    {
//
//
//        //Récupérer les 5 catégories ayant le plus de j'aime
//
//
//
////        $articles = $repository->findAll();
////        $resultat = $serializer -> serialize(
////            $articles,
////            'json',
////            [
////                'groups' => ['articleRead']
////            ]
////        );
////        return new JsonResponse ($resultat, 200, [], true);
//    }
}
