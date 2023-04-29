<?php

namespace Drupal\landing_aio\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a 'HeroVideo' block.
 *
 * @Block(
 *   id = "hero_video",
 *   admin_label = @Translation("Hero Video"),
 *   category = @Translation("Landing AIO")
 * )
 */
class HeroVideo extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'titulo' => '',
      'posicion_titulo' => 'arriba',
      'tipo_contenido' => 'imagen',
      'archivo_contenido' => NULL,
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['titulo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Título'),
      '#description' => $this->t('Ingrese el título del bloque.'),
      '#default_value' => $this->configuration['titulo'],
    ];

    $form['posicion_titulo'] = [
      '#type' => 'select',
      '#title' => $this->t('Posición del título'),
      '#description' => $this->t('Seleccione la posición del título en relación al contenido.'),
      '#options' => [
        'arriba' => $this->t('Arriba'),
        'abajo' => $this->t('Abajo'),
      ],
      '#default_value' => $this->configuration['posicion_titulo'],
    ];

    $form['tipo_contenido'] = [
      '#type' => 'select',
      '#title' => $this->t('Tipo de contenido'),
      '#description' => $this->t('Seleccione el tipo de contenido que desea mostrar.'),
      '#options' => [
        'imagen' => $this->t('Imagen'),
        'video' => $this->t('Video'),
      ],
      '#default_value' => $this->configuration['tipo_contenido'],
    ];

    $form['archivo_contenido'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Archivo de contenido'),
      '#description' => $this->t('Suba la imagen o el video que desea mostrar.'),
      '#upload_location' => 'public://landing_aio_hero_video/',
      '#upload_validators' => [
        'file_validate_extensions' => ['jpeg jpg png gif mp4 webm'],
      ],
      '#default_value' => $this->configuration['archivo_contenido'] ? [$this->configuration['archivo_contenido']] : NULL,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['titulo'] = $form_state->getValue('titulo');
    $this->configuration['posicion_titulo'] = $form_state->getValue('posicion_titulo');
    $this->configuration['tipo_contenido'] = $form_state->getValue('tipo_contenido');

    $archivo = $form_state->getValue('archivo_contenido');
    if (!empty($archivo)) {
      $file = \Drupal\file\Entity\File::load(reset($archivo));
      $file->setPermanent();
      $file->save();
      $this->configuration['archivo_contenido'] = $file->id();
    }
    else {
      $this->configuration['archivo_contenido'] = NULL;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $titulo = $this->configuration['titulo'];
    $posicion_titulo = $this->configuration['posicion_titulo'];
    $tipo_contenido = $this->configuration['tipo_contenido'];

    $archivo_contenido = NULL;
    if ($this->configuration['archivo_contenido']) {
      $file = \Drupal\file\Entity\File::load($this->configuration['archivo_contenido']);
      if ($file) {
        $archivo_contenido = $file->createFileUrl();
      }
    }

    return [
      '#theme' => 'landing_aio_hero_video',
      '#titulo' => $titulo,
      '#posicion_titulo' => $posicion_titulo,
      '#tipo_contenido' => $tipo_contenido,
      '#archivo_contenido' => $archivo_contenido,
    ];
  }

}
