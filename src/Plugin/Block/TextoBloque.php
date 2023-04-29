<?php

namespace Drupal\landing_aio\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Texto Bloque.
 *
 * @Block(
 *   id = "texto_bloque",
 *   admin_label = @Translation("Texto Bloque"),
 *   category = @Translation("Landing AIO"),
 * )
 */
class TextoBloque extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'texto' => '',
      'destacado' => FALSE,
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['texto'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Texto'),
      '#description' => $this->t('Escriba el contenido del bloque de texto.'),
      '#default_value' => $this->configuration['texto'],
    ];

    $form['destacado'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Destacado'),
      '#description' => $this->t('Marque esta casilla si desea que el texto sea destacado.'),
      '#default_value' => $this->configuration['destacado'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['texto'] = $form_state->getValue('texto');
    $this->configuration['destacado'] = $form_state->getValue('destacado');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $texto = $this->configuration['texto'];
    $destacado = $this->configuration['destacado'];

    return [
      '#theme' => 'landing_aio_texto_bloque',
      '#texto' => $texto,
      '#destacado' => $destacado,
    ];
  }

}
