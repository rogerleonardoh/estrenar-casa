<?php

namespace Drupal\example_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class DataController extends ControllerBase {
  public function data() {
    $connection = \Drupal::database();

    $query = $connection->select('example_users');
    $query->fields('example_users');

    $sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'ASC';
    $field = (isset($_GET['order'])) ? $_GET['order'] : 'id';
    $query->orderBy($field, $sort);
    $results = $query->execute();

    $rows = [];
    foreach ($results as $resultado) {
      $rows[] = [
        'id' => $resultado->id,
        'identificacion' => $resultado->identificacion,
        'nombre' => $resultado->nombre,
        'fecha' => date('d/m/Y', $resultado->fecha),
        'cargo' => $resultado->cargo,
        'estado' => $resultado->estado,
      ];
    }

    $header = [
      [
        'data' => 'ID',
        'field' => 'id',
      ],
      [
        'data' => 'Identificacion',
        'field' => 'identificacion',
      ],
      [
        'data' => 'Nombre',
        'field' => 'nombre',
      ],
      [
        'data' => 'Fecha',
        'field' => 'fecha',
      ],
      [
        'data' => 'Cargo',
        'field' => 'cargo',
      ],
      [
        'data' => 'Estado',
        'field' => 'estado',
      ],
    ];

    $build['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#sticky' => TRUE,
      '#empty' => 'No log messages available.',
    ];
    return $build;;
  }
}
