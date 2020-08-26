<?php
namespace App\Controller;

use App\Entity;
use App\Form\BasketType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Warenkorb Controller
 *
 * @Route("/api", name="api_")
 */
class BasketController extends AbstractFOSRestController
{
  /**
   * Gibt eine Liste aller Warenkörbe zurück
   *
   * @Rest\Get("/baskets")
   *
   * @return Response
   */
  public function getAllAction(): Response
  {
    $repository = $this->getDoctrine()->getRepository(Entity\Basket::class);
    $baskets = $repository->findAll();

    return $this->handleView($this->view($baskets));
  }

  /**
   * Gibt einen Warenkorb anhand einer Id zurück
   *
   * @Rest\Get("/basket/{id}")
   *
   * @return Response
   */
  public function getOneAction($id): Response
  {
    $entityManager = $this->getDoctrine()->getManager();
    $basket = $entityManager->find(Entity\Basket::class, $id);
    if (empty($basket)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    return $this->handleView($this->view($basket));
  }

  /**
   * Löscht einen Warenkorb anhand einer Id
   *
   * @Rest\Delete("/basket/{id}")
   *
   * @return Response
   */
  public function deleteAction($id): Response
  {
    $entityManager = $this->getDoctrine()->getManager();
    $basket = $entityManager->find(Entity\Basket::class, $id);
    if (empty($basket)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    $entityManager->remove($basket);
    $entityManager->flush();

    return $this->handleView($this->view(['Success' => 'true'], Response::HTTP_OK));
  }

  /**
   * Überschreibt einen Warenkorb mit neuen Daten
   *
   * @Rest\Put("/basket/{id}")
   *
   * @return Response
   */
  public function putAction($id, Request $request): Response
  {
    $entityManager = $this->getDoctrine()->getManager();
    $basket = $entityManager->find(Entity\Basket::class, $id);
    if (empty($basket)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    $data = json_decode($request->getContent(), true);
    $basket->setModified(new \DateTime());

    $entityManager->persist($basket);
    $entityManager->flush();

    return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_OK));
  }

  /**
   * Erstellt einen neuen Warenkorb
   *
   * @Rest\Post("/basket")
   *
   * @return Response
   */
  public function postBasketAction(Request $request): Response
  {
    $data = json_decode($request->getContent(), true);

    $basket = new Entity\Basket();
    $basket->setCreated(new \DateTime());

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($basket);
    $entityManager->flush();

    return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
  }
}