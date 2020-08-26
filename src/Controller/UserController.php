<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\User;
use App\Form\UserType;

/**
 * User controller.
 * @Route("/api", name="api_")
 */
class UserController extends AbstractFOSRestController
{
  /**
   * Lists all Users.
   * @Rest\Get("/users")
   *
   * @return Response
   */
  public function getAllAction()
  {
    $repository = $this->getDoctrine()->getRepository(User::class);
    $users = $repository->findall();
    return $this->handleView($this->view($users));
  }

  /**
   * @Rest\Get("/user/{id}")
   *
   * @return Response
   */
  public function getOneAction($id)
  {
    $repository = $this->getDoctrine()->getRepository(User::class);
    $user = $repository->find($id);
    if(empty($user)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    return $this->handleView($this->view($user));
  }

  /**
   * @Rest\Delete("/user/{id}")
   *
   * @return Response
   */
  public function deleteAction($id)
  {
    $repository = $this->getDoctrine()->getRepository(User::class);
    $user = $repository->find($id);
    if(empty($user)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    $manager = $this->getDoctrine()->getManagerForClass(User::class);
    $manager->remove($user);
    $manager->flush();

    return $this->handleView($this->view(['Success' => 'true'], Response::HTTP_OK));
  }

  /**
   * @Rest\Put("/user/{id}")
   *
   * @return Response
   */
  public function putAction($id, Request $request)
  {
    $entityManager = $this->getDoctrine()->getManager();
    $user = $entityManager->find(User::class, $id);
    if(empty($user)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    $data = json_decode($request->getContent(), true);

    $userEntity = $this->getUserById($data['user']);
    if (empty($userEntity)) {
      return $this->handleView($this->view(['error' => 'user entity not found'], Response::HTTP_NOT_FOUND));
    }
    $basket->setUser($userEntity)
      ->setModified(new \DateTime());

    $entityManager->persist($basket);
    $entityManager->flush();

    return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_OK));
  }

  /**
   * Create User.
   * @Rest\Post("/user")
   *
   * @return Response
   */
  public function postUserAction(Request $request)
  {
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();
      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }
    return $this->handleView($this->view($form->getErrors()));
  }

  /**
   * Sucht eine User Entität und gibt diese oder null zurück
   *
   * @param int $userId
   * @return Entity\User|null
   */
  private function getBasketById(int $userId): ?Entity\User
  {
    $entityManager = $this->getDoctrine()->getManager();

    return $entityManager->find(Entity\User::class, $id);
  }
}