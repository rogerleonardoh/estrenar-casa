<?php

namespace Drupal\example_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Form\FormStateInterface;

class ExampleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'example_module_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="result_message"></div>'
    ];
    $form['identificacion'] = [
      '#type' => 'textfield',
      '#title' => 'Identificación',
      '#required' => TRUE,
    ];
    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => 'Nombre',
      '#required' => TRUE,
    ];
    $form['fecha'] = [
      '#type' => 'date',
      '#title' => 'Fecha',
      '#required' => TRUE,
    ];
    $form['cargo'] = [
      '#type' => 'select',
      '#title' => 'Cargo',
      '#options' => [
        'admin' => 'Administrador',
        'webmaster' => 'Webmaster',
        'dev' => 'Desarrollador',
      ],
      '#required' => TRUE,
    ];
    $form['actions'] = [
      '#type' => 'button',
      '#value' => 'Guardar',
      '#ajax' => [
        'callback' => '::submitFormAjax',
      ],
    ];

    $form['#attached']['library'][] = 'example_module/example_module';
    $form['#theme'] = 'example_module_form';

    return $form;
  }

  public function submitFormAjax(array $form, FormStateInterface $form_state) {
    $class = 'success';
    $message = 'Los datos se han guardado correctamente.';
    $connection = \Drupal::database();

    $name = $form_state->getValue('nombre');
    $id = $form_state->getValue('identificacion');
    $date = $form_state->getValue('fecha');
    $rol = $form_state->getValue('cargo');
    $date = strtotime($date);
    $state = ($rol == 'admin') ? 1 : 0;

    try {
      $connection = \Drupal::database();

      $query = $connection->select('example_users');
      $query->fields('example_users');
      $query->condition('identificacion', $id);
      $results = $query->execute();

      if (count($results->fetchAll()) > 0) {
        $class = 'danger';
        $message = 'Ya existe un usuario con esa identificacion';
      }
      else {
        $connection->insert('example_users')
          ->fields([
            'nombre' => $name,
            'identificacion' => $id,
            'fecha' => $date,
            'cargo' => $rol,
            'estado' => $state,
          ])
          ->execute();
      }
    }
    catch (\Exception $e) {
      $class = 'danger';
      $message = 'Los datos no se han guardado.';
    }

    $html = '
      <div aria-label="Mensaje de estado" class="alert alert-status alert-' . $class . ' alert-dismissible fade show" role="alert">
        ' . $message  . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';

    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('.result_message', $html));

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}
