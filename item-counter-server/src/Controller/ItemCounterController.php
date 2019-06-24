<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ItemCounterController extends ApiController
{
    /**
    * @Route("/items")
    */
    public function itemsAction()
    {
        return $this->respond([       
                'title' => 'Winter is coming!',
                'count' => 0
        ]);
    }
}
