<?php
namespace App\Controller;

use App\Entity;
use App\Form\BasketToItemType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Warenkorb zu Artikel Controller
 *
 * @Route("/api", name="api_")
 */
class BasketToItemController extends AbstractFOSRestController
{
  /**
   * Löscht eine Warenkorb zu Artikel Entität und somit wird der Artikel quasi aus dem Warenkorb entfernt
   *
   * @Rest\Delete("/basket-to-item/basket/{basketId}/item/{itemId}")
   *
   * @return Response
   */
  public function deleteAction($basketId, $itemId): Response
  {
    $repository = $this->getDoctrine()->getRepository(Entity\BasketToItem::class);

    $basketToItem = $repository->findOneBy(["basket" => $basketId, "item" => $itemId]);
    if(empty($basketToItem)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($basketToItem);
    $entityManager->flush();

    return $this->handleView($this->view(['Success' => 'true'], Response::HTTP_OK));
  }

  /**
   * Erstellt eine Warenkorb zu Artikel Entität und  somit wird quasi ein Artikel dem Warenkorb hinzugefügt
   *
   * @Rest\Post("/basket-to-item")
   *
   * @return Response
   */
  public function postBasketToItemAction(Request $request): Response
  {
    $data = json_decode($request->getContent(), true);

    $basketEntity = $this->getBasketById($data['basket']);
    if (empty($basketEntity)) {
      return $this->handleView($this->view(['error' => 'basket entity not found'], Response::HTTP_NOT_FOUND));
    }

    $itemEntity = $this->getItemById($data['item']);
    if (empty($itemEntity)) {
      return $this->handleView($this->view(['error' => 'item entity not found'], Response::HTTP_NOT_FOUND));
    }

    $basketToItem = new Entity\BasketToItem();
    $basketToItem->setBasket($basketEntity)
      ->setItem($itemEntity)
      ->setCreated(new \DateTime());

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($basketToItem);
    $entityManager->flush();

    return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
  }

  /**
   * Sucht eine Warenkorb Entität und gibt diese oder null zurück
   *
   * @param int $basketId
   * @return Entity\Basket|null
   */
  private function getBasketById(int $basketId): ?Entity\Basket
  {
    $entityManager = $this->getDoctrine()->getManager();

    return $entityManager->find(Entity\Basket::class, $basketId);
  }

  /**
   * Sucht eine Artikel Entität und gibt diese oder null zurück
   *
   * @param int $itemId
   * @return Entity\Item|null
   */
  private function getItemById(int $itemId): ?Entity\Item
  {
    $entityManager = $this->getDoctrine()->getManager();

    return $entityManager->find(Entity\Item::class, $itemId);
  }
}