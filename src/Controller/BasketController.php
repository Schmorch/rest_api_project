<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Basket;
use App\Form\BasketType;
/**
 * Basket controller.
 * @Route("/api", name="api_")
 */
class BasketController extends AbstractFOSRestController
{
  /**
   * Lists all Baskets.
   * @Rest\Get("/baskets")
   *
   * @return Response
   */
  public function getAllAction()
  {
    $repository = $this->getDoctrine()->getRepository(Basket::class);
    $baskets = $repository->findall();

    return $this->handleView($this->view($baskets));
  }

  /**
   * @Rest\Get("/basket/{id}")
   *
   * @return Response
   */
  public function getOneAction($id)
  {
    $repository = $this->getDoctrine()->getRepository(Basket::class);
    $basket = $repository->find($id);
    if(empty($basket)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    return $this->handleView($this->view($basket));
  }

  /**
   * @Rest\Delete("/basket/{id}")
   *
   * @return Response
   */
  public function deleteAction($id)
  {
    $repository = $this->getDoctrine()->getRepository(Basket::class);
    $basket = $repository->find($id);
    if(empty($basket)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    $manager = $this->getDoctrine()->getManagerForClass(Basket::class);
    $manager->remove($basket);
    $manager->flush();

    return $this->handleView($this->view(['Success' => 'true'], Response::HTTP_OK));
  }

  /**
   * Create Basket.
   * @Rest\Post("/basket")
   *
   * @return Response
   */
  public function postBasketAction(Request $request)
  {
    $basket = new Basket();
    $form = $this->createForm(BasketType::class, $basket);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($basket);
      $em->flush();

      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }

    return $this->handleView($this->view($form->getErrors()));
  }
}