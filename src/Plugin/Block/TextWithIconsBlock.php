<?php

namespace Drupal\landing_aio\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\File\FileSystemInterface;

/**
 * Provides a 'TextWithIcons' Block.
 *
 * @Block(
 *   id = "landing_aio_text_with_icons",
 *   admin_label = @Translation("Texto con iconos"),
 *   category = @Translation("Landing AIO"),
 * )
 */
class TextWithIconsBlock extends BlockBase {

  public function defaultConfiguration() {
    return [
      'title' => '',
      'description' => '',
      'icon' => [],
      'icon_text' => '',
      'icon_link' => '',
      'icon2' => [],
      'icon2_text' => '',
      'icon2_link' => '',
      'icon3' => [],
      'icon3_text' => '',
      'icon3_link' => '',
      'icon4' => [],
      'icon4_text' => '',
      'icon4_link' => '',
    ];
  }

  public function blockForm($form, FormStateInterface $form_state) {
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Titulo destacado'),
      '#default_value' => $this->configuration['title'],
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Texto'),
      '#default_value' => $this->configuration['description'],
    ];

    $form['icon'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Icono'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg svg gif'],
      ],
      '#upload_location' => 'public://icons/',
      '#default_value' => $this->configuration['icon'],
    ];
    $form['icon_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Texto del icono'),
      '#default_value' => $this->configuration['icon_text'],
    ];
    $form['icon_link'] = [
      '#type' => 'url',
      '#title' => $this->t('Enlace del icono'),
      '#default_value' => $this->configuration['icon_link'],
    ];
    $form['icon2'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Icono'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg svg gif'],
      ],
      '#upload_location' => 'public://icons/',
      '#default_value' => $this->configuration['icon2'],
    ];
    $form['icon2_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Texto del icono'),
      '#default_value' => $this->configuration['icon2_text'],
    ];
    $form['icon2_link'] = [
      '#type' => 'url',
      '#title' => $this->t('Enlace del icono'),
      '#default_value' => $this->configuration['icon2_link'],
    ];
    $form['icon3'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Icono'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg svg gif'],
      ],
      '#upload_location' => 'public://icons/',
      '#default_value' => $this->configuration['icon3'],
    ];
    $form['icon3_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Texto del icono'),
      '#default_value' => $this->configuration['icon3_text'],
    ];
    $form['icon3_link'] = [
      '#type' => 'url',
      '#title' => $this->t('Enlace del icono'),
      '#default_value' => $this->configuration['icon3_link'],
    ];
    $form['icon4'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Icono'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg svg gif'],
      ],
      '#upload_location' => 'public://icons/',
      '#default_value' => $this->configuration['icon4'],
    ];
    $form['icon4_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Texto del icono'),
      '#default_value' => $this->configuration['icon4_text'],
    ];
    $form['icon4_link'] = [
      '#type' => 'url',
      '#title' => $this->t('Enlace del icono'),
      '#default_value' => $this->configuration['icon4_link'],
    ];
    return $form;
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['title'] = $form_state->getValue('title');
    $this->configuration['description'] = $form_state->getValue('description');

    $icon_file = $form_state->getValue('icon');
    if (!empty($icon_file)) {
      $file = \Drupal\file\Entity\File::load(reset($icon_file));
      $file->setPermanent();
      $file->save();
      $this->configuration['icon'] = [$file->id()];
    } else {
      $this->configuration['icon'] = [];
    }

    $this->configuration['icon_text'] = $form_state->getValue('icon_text');
    $this->configuration['icon_link'] = $form_state->getValue('icon_link');

    $icon2_file = $form_state->getValue('icon2');
    if (!empty($icon2_file)) {
      $file = \Drupal\file\Entity\File::load(reset($icon2_file));
      $file->setPermanent();
      $file->save();
      $this->configuration['icon2'] = [$file->id()];
    } else {
      $this->configuration['icon2'] = [];
    }

    $this->configuration['icon2_text'] = $form_state->getValue('icon2_text');
    $this->configuration['icon2_link'] = $form_state->getValue('icon2_link');
  

    $icon3_file = $form_state->getValue('icon3');
    if (!empty($icon3_file)) {
      $file = \Drupal\file\Entity\File::load(reset($icon3_file));
      $file->setPermanent();
      $file->save();
      $this->configuration['icon3'] = [$file->id()];
    } else {
      $this->configuration['icon3'] = [];
    }

    $this->configuration['icon3_text'] = $form_state->getValue('icon3_text');
    $this->configuration['icon3_link'] = $form_state->getValue('icon3_link');

    $icon4_file = $form_state->getValue('icon4');
    if (!empty($icon4_file)) {
      $file = \Drupal\file\Entity\File::load(reset($icon4_file));
      $file->setPermanent();
      $file->save();
      $this->configuration['icon4'] = [$file->id()];
    } else {
      $this->configuration['icon4'] = [];
    }

    $this->configuration['icon4_text'] = $form_state->getValue('icon4_text');
    $this->configuration['icon4_link'] = $form_state->getValue('icon4_link');
  }

  public function build() {
    $icon_url = '';
    if (!empty($this->configuration['icon'])) {
      $file = \Drupal\file\Entity\File::load(reset($this->configuration['icon']));
      $icon_url = $file->createFileUrl();
    }
    $icon2_url = '';
    if (!empty($this->configuration['icon2'])) {
      $file = \Drupal\file\Entity\File::load(reset($this->configuration['icon2']));
      $icon2_url = $file->createFileUrl();
    }    $icon3_url = '';
    if (!empty($this->configuration['icon3'])) {
      $file = \Drupal\file\Entity\File::load(reset($this->configuration['icon3']));
      $icon3_url = $file->createFileUrl();
    }    $icon4_url = '';
    if (!empty($this->configuration['icon4'])) {
      $file = \Drupal\file\Entity\File::load(reset($this->configuration['icon4']));
      $icon4_url = $file->createFileUrl();
    }
    return [
      '#theme' => 'landing_aio_text_with_icons',
      '#title' => $this->configuration['title'],
      '#description' => $this->configuration['description'],
      '#icon_url' => $icon_url,
      '#icon_text' => $this->configuration['icon_text'],
      '#icon_link' => $this->configuration['icon_link'],
      '#icon2_url' => $icon2_url,
      '#icon2_text' => $this->configuration['icon2_text'],
      '#icon2_link' => $this->configuration['icon2_link'],
      '#icon3_url' => $icon3_url,
      '#icon3_text' => $this->configuration['icon3_text'],
      '#icon3_link' => $this->configuration['icon3_link'],
      '#icon4_url' => $icon4_url,
      '#icon4_text' => $this->configuration['icon4_text'],
      '#icon4_link' => $this->configuration['icon4_link'],
    ];
  }
}

