<?php
namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Artikel Controller
 *
 * @Route("/api", name="api_")
 */
class ItemController extends AbstractFOSRestController
{
  /**
   * Gibt alle Artikel zurück
   *
   * @Rest\Get("/items")
   *
   * @return Response
   */
  public function getAllAction(): Response
  {
    $repository = $this->getDoctrine()->getRepository(Item::class);
    $items = $repository->findAll();

    return $this->handleView($this->view($items));
  }

  /**
   * Gibt einen Artikel zurück
   *
   * @Rest\Get("/item/{id}")
   *
   * @return Response
   */
  public function getOneAction($id): Response
  {
    $entityManager = $this->getDoctrine()->getManager();
    $item = $entityManager->find(Item::class, $id);
    if (empty($item)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    return $this->handleView($this->view($item));
  }

  /**
   * Löscht einen Artikel anhand einer Id
   *
   * @Rest\Delete("/item/{id}")
   *
   * @return Response
   */
  public function deleteAction($id): Response
  {
    $entityManager = $this->getDoctrine()->getManager();
    $item = $entityManager->find(Item::class, $id);
    if (empty($item)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    $entityManager->remove($item);
    $entityManager->flush();

    return $this->handleView($this->view(['Success' => 'true'], Response::HTTP_OK));
  }

  /**
   * Überschreibt einen Artikel mit neuen Daten
   *
   * @Rest\Put("/item/{id}")
   *
   * @return Response
   */
  public function putAction($id, Request $request): Response
  {
    $entityManager = $this->getDoctrine()->getManager();
    $item = $entityManager->find(Item::class, $id);
    if (empty($item)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    $data = json_decode($request->getContent(), true);
    $item->setName($data['name'])
      ->setDescription($data['description'])
      ->setModified(new \DateTime());

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($item);
    $entityManager->flush();

    return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_OK));
  }

  /**
   * Erstellt einen Artikel
   *
   * @Rest\Post("/item")
   *
   * @return Response
   */
  public function postItemAction(Request $request): Response
  {
    $item = new Item();
    $data = json_decode($request->getContent(), true);

    $item->setName($data['name'])
      ->setDescription($data['description'])
      ->setCreated(new \DateTime());

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($item);
    $entityManager->flush();

    return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
  }
}