<?php

namespace Drupal\landing_aio\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'HighlightedTextBlock' block.
 *
 * @Block(
 *  id = "highlighted_text_block",
 *  admin_label = @Translation("Highlighted Text Block"),
 * )
 */
class HighlightedTextBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'title_highlighted' => '',
      'text' => '',
      'show_more' => FALSE,
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    $form['title_highlighted'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Highlighted Title'),
      '#description' => $this->t('Enter the highlighted title.'),
      '#default_value' => $config['title_highlighted'] ?? '',
    ];

    $form['text'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Text'),
      '#description' => $this->t('Enter the text with formatting options.'),
      '#default_value' => $config['text'] ?? '',
      '#format' => 'full_html',
    ];

    $form['show_more'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show more option'),
      '#description' => $this->t('Check this box to show a "..." (more) option at the end of the text.'),
      '#default_value' => $config['show_more'] ?? FALSE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('title_highlighted', $form_state->getValue('title_highlighted'));
    $this->setConfigurationValue('text', $form_state->getValue('text')['value']);
    $this->setConfigurationValue('show_more', $form_state->getValue('show_more'));
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    $title_highlighted = $config['title_highlighted'] ?? '';
    $text = $config['text'] ?? '';
    $show_more = $config['show_more'] ?? FALSE;

    return [
      '#theme' => 'landing_aio_highlighted_text',
      '#title_highlighted' => $title_highlighted,
      '#text' => $text,
      '#show_more' => $show_more,
      '#attached' => [
        'library' => [
          'landing_aio/highlighted_text',
        ],
      ],
    ];
  }

}
