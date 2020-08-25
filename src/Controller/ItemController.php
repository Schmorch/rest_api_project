<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Item;
use App\Form\ItemType;
/**
 * Item controller.
 * @Route("/api", name="api_")
 */
class ItemController extends AbstractFOSRestController
{
  /**
   * Lists all Items.
   * @Rest\Get("/items")
   *
   * @return Response
   */
  public function getAllAction()
  {
    $repository = $this->getDoctrine()->getRepository(Item::class);
    $items = $repository->findall();
    return $this->handleView($this->view($items));
  }

  /**
   * @Rest\Get("/item/{id}")
   *
   * @return Response
   */
  public function getOneAction($id)
  {
    $repository = $this->getDoctrine()->getRepository(Item::class);
    $item = $repository->find($id);
    if(empty($item)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    return $this->handleView($this->view($item));
  }

  /**
   * @Rest\Delete("/item/{id}")
   *
   * @return Response
   */
  public function deleteAction($id)
  {
    $repository = $this->getDoctrine()->getRepository(Item::class);
    $item = $repository->find($id);
    if(empty($item)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    $manager = $this->getDoctrine()->getManagerForClass(Item::class);
    $manager->remove($item);
    $manager->flush();

    return $this->handleView($this->view(['Success' => 'true'], Response::HTTP_OK));
  }

  /**
   * Create Item.
   * @Rest\Post("/item")
   *
   * @return Response
   */
  public function postItemAction(Request $request)
  {
    $item = new Item();
    $form = $this->createForm(ItemType::class, $item);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($item);
      $em->flush();
      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }
    return $this->handleView($this->view($form->getErrors()));
  }
}