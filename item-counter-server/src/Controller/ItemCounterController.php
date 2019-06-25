<?php
namespace App\Controller;

use App\Entity\ItemCounter;
use App\Repository\ItemCounterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ItemCounterController extends ApiController
{
    /**
    * @Route("/items", methods="GET")
    */
    public function index(ItemCounterRepository $itemCounterRepository)
    {
        if (! $this->isAuthorized()) {
            return $this->respondUnauthorized();
        }

        $items = $itemCounterRepository->transformAll();

        return $this->respond($items);
    }

    /**
    * @Route("/items", methods="POST")
    */
    public function create(Request $request, ItemCounterRepository $itemCounterRepository, EntityManagerInterface $em)
    {
        if (! $this->isAuthorized()) {
            return $this->respondUnauthorized();
        }

        $request = $this->transformJsonBody($request);

        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        // validate the title
        if (! $request->get('title')) {
            return $this->respondValidationError('Please provide a title!');
        }

        // persist the new item counter
        $itemCounter = new ItemCounter;
        $itemCounter->setTitle($request->get('title'));
        $itemCounter->setCount(0);
        $em->persist($itemCounter);
        $em->flush();

        return $this->respondCreated($itemCounterRepository->transform($itemCounter));
    }

    /**
    * @Route("/items/{id}/count", methods="POST")
    */
    public function increaseCount($id, EntityManagerInterface $em, ItemCounterRepository $itemCounterRepository)
    {
        if (! $this->isAuthorized()) {
            return $this->respondUnauthorized();
        }

        $itemCounter = $itemCounterRepository->find($id);

        if (! $itemCounter) {
            return $this->respondNotFound();
        }

        $itemCounter->setCount($itemCounter->getCount() + 1);
        $em->persist($itemCounter);
        $em->flush();

        return $this->respond(
            $itemCounterRepository->transform($itemCounter)
        );
    }

    /**
    * @Route("/items/{id}", methods="DELETE")
    */
    public function delete($id, EntityManagerInterface $em, ItemCounterRepository $itemCounterRepository)
    {
        if (! $this->isAuthorized()) {
            return $this->respondUnauthorized();
        }

        $itemCounter = $itemCounterRepository->find($id);

        if (! $itemCounter) {
            return $this->respondNotFound();
        }

        $em->remove($itemCounter);
        $em->flush();

        return $this->respond([]);
    }
}
