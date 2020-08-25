<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\BasketToItem;
use App\Form\BasketToItemType;
/**
 * BasketToItem controller.
 * @Route("/api", name="api_")
 */
class BasketToItemController extends AbstractFOSRestController
{
  /**
   * Lists all BasketToItems.
   * @Rest\Get("/basketToItems")
   *
   * @return Response
   */
  public function getAllAction()
  {
    $repository = $this->getDoctrine()->getRepository(BasketToItem::class);
    $basketToItems = $repository->findall();
    return $this->handleView($this->view($basketToItems));
  }

  /**
   * @Rest\Get("/basketToItem/{id}")
   *
   * @return Response
   */
  public function getOneAction($id)
  {
    $repository = $this->getDoctrine()->getRepository(BasketToItem::class);
    $basketToItem = $repository->find($id);
    if(empty($basketToItem)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    return $this->handleView($this->view($basketToItem));
  }

  /**
   * @Rest\Delete("/basketToItem/{id}")
   *
   * @return Response
   */
  public function deleteAction($id)
  {
    $repository = $this->getDoctrine()->getRepository(BasketToItem::class);
    $basketToItem = $repository->find($id);
    if(empty($basketToItem)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    $manager = $this->getDoctrine()->getManagerForClass(BasketToItem::class);
    $manager->remove($basketToItem);
    $manager->flush();

    return $this->handleView($this->view(['Success' => 'true'], Response::HTTP_OK));
  }

  /**
   * Create BasketToItem.
   * @Rest\Post("/basketToItem")
   *
   * @return Response
   */
  public function postBasketToItemAction(Request $request)
  {
    $basketToItem = new BasketToItem();
    $form = $this->createForm(BasketToItemType::class, $basketToItem);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($basketToItem);
      $em->flush();
      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }
    return $this->handleView($this->view($form->getErrors()));
  }
}