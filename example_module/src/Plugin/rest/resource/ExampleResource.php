<?php

namespace Drupal\example_module\Plugin\rest\resource;

use Drupal\rest\ResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a Demo Resource
 *
 * @RestResource(
 *   id = "example_resource",
 *   label = @Translation("Example Resource"),
 *   uri_paths = {
 *      "canonical" = "/example-module/user/{id}",
 *      "https://www.drupal.org/link-relations/create" = "/example-module/user/{id}"
 *   }
 * )
 */
class ExampleResource extends ResourceBase {
  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get($id) {
    $connection = \Drupal::database();
    $response = new ResourceResponse();

    try {
      $query = $connection->select('example_users');
      $query->fields('example_users');
      $query->condition('id', $id);
      $results = $query->execute();
      $results = $results->fetchAll();
      $results = json_encode($results);
      $response->setContent($results);
    }
    catch (\Exception $e) {
      $response->setStatusCode(204);
    }

    return $response;
  }

  /**
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response objects
   */
  public function post(Request $request) {
    $connection = \Drupal::database();
    $response = new ResourceResponse();
    $data = json_decode($request->getContent(), TRUE);

    try {
      $query = $connection->select('example_users');
      $query->fields('example_users');
      $query->condition('identificacion',$data['nombre']);
      $resultados = $query->execute();

      if (count($resultados->fetchAll()) > 0) {
        $response->setStatusCode(409);
      }
      else {
        $connection->insert('example_users')
          ->fields([
            'nombre' => $data['nombre'],
            'identificacion' => $data['nombre'],
            'fecha' => $data['nombre'],
            'cargo' => $data['nombre'],
            'estado' => $data['nombre'],
          ])
          ->execute();
        $response->setStatusCode(201);
      }
    }
    catch (\Exception $e) {
      $response->setStatusCode(400);
    }

    return $response;
  }

  /**
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response objects
   */
  public function put($id, Request $request) {
    $connection = \Drupal::database();
    $response = new ResourceResponse();
    $data = json_decode($request->getContent(), TRUE);

    try {
      $query = $connection->update('example_users');
      $query->fields([
        'nombre' => $data['nombre'],
        'identificacion' => $data['identificacion'],
        'fecha' => $data['fecha'],
        'cargo' => $data['cargo'],
        'estado' => $data['estado'],
      ]);
      $query->condition('id', $id);
      $query->execute();

      $response->setStatusCode(200);
    }
    catch (\Exception $e) {
      $response->setStatusCode(400);
    }

    return $response;
  }

  /**
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response objects
   */
  public function delete($id) {
    $connection = \Drupal::database();
    $response = new ResourceResponse();

    try {
      $query = $connection->delete('example_users');
      $query->condition('id', $id);
      $query->execute();

      $response->setStatusCode(200);
    }
    catch (\Exception $e) {
      $response->setStatusCode(400);
    }

    return $response;
  }
}
