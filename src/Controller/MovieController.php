<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Movie;
use App\Form\MovieType;
/**
 * Movie controller.
 * @Route("/api", name="api_")
 */
class MovieController extends AbstractFOSRestController
{
  /**
   * Lists all Movies.
   * @Rest\Get("/movies")
   *
   * @return Response
   */
  public function getMovieAction()
  {
    $repository = $this->getDoctrine()->getRepository(Movie::class);
    $movies = $repository->findall();

    return $this->handleView($this->view($movies));
  }

  /**
   * @Rest\Get("/movie/{id}")
   *
   * @return Response
   */
  public function getOneAction($id)
  {
    $repository = $this->getDoctrine()->getRepository(Movie::class);
    $movie = $repository->find($id);
    if(empty($movie)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    return $this->handleView($this->view($movie));
  }

  /**
   * @Rest\Delete("/movie/{id}")
   *
   * @return Response
   */
  public function deleteAction($id)
  {
    $repository = $this->getDoctrine()->getRepository(Movie::class);
    $movie = $repository->find($id);
    if(empty($movie)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    $manager = $this->getDoctrine()->getManagerForClass(Movie::class);
    $manager->remove($movie);
    $manager->flush();

    return $this->handleView($this->view(['Success' => 'true'], Response::HTTP_OK));
  }

  /**
   * @Rest\Put("/movie/{id}")
   *
   * @return Response
   */
  public function putAction($id, Request $request)
  {
    $repository = $this->getDoctrine()->getRepository(Movie::class);
    $movie = $repository->find($id);
    if(empty($movie)) {
      return $this->handleView($this->view(['error' => 'entity not found'], Response::HTTP_NOT_FOUND));
    }

    $form = $this->createForm(MovieType::class, $movie);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($movie);
      $em->flush();

      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_OK));
    }

    return $this->handleView($this->view($form->getErrors()));
  }

  /**
   * Create Movie.
   * @Rest\Post("/movie")
   *
   * @return Response
   */
  public function postMovieAction(Request $request)
  {
    $movie = new Movie();
    $form = $this->createForm(MovieType::class, $movie);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($movie);
      $em->flush();

      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }

    return $this->handleView($this->view($form->getErrors()));
  }
}