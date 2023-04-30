<?php

namespace Drupal\landing_aio\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'FullWidthVideoBlock' block.
 *
 * @Block(
 *   id = "landing_aio_full_width_video",
 *   admin_label = @Translation("Full Width Video"),
 *   category = @Translation("Landing AIO")
 * )
 */
class FullWidthVideoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'video_title' => '',
      'video_file' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['video_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Video title'),
      '#default_value' => $this->configuration['video_title'],
    ];

    $form['video_file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Video file'),
      '#upload_validators' => [
        'file_validate_extensions' => ['mp4 ogv webm'],
      ],
      '#upload_location' => 'public://videos/',
      '#default_value' => $this->configuration['video_file'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['video_title'] = $form_state->getValue('video_title');

    // Obtén el valor del archivo de vídeo subido y guarda su referencia en la configuración del bloque
    $video_file = $form_state->getValue('video_file');
    if (!empty($video_file)) {
      $file = \Drupal\file\Entity\File::load(reset($video_file));
      $file->setPermanent();
      $file->save();
      $this->configuration['video_file'] = $file->id();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $video_url = NULL;
    if ($this->configuration['video_file']) {
      $file = \Drupal\file\Entity\File::load($this->configuration['video_file']);
      $video_url = $file->createFileUrl();
    }


    return [
      '#theme' => 'landing_aio_full_width_video',
      '#video_title' => $this->configuration['video_title'],
      '#video_url' => $video_url,
      '#attached' => [
        'library' => [
          'landing_aio/full_width_video',
        ],
      ],
    ];
  }

}
