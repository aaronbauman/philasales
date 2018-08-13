<?php

namespace Drupal\philasales\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the ward entity edit forms.
 */
class WardForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      drupal_set_message($this->t('New ward %label has been created.', $message_arguments));
      $this->logger('philasales')->notice('Created new ward %label', $logger_arguments);
    }
    else {
      drupal_set_message($this->t('The ward %label has been updated.', $message_arguments));
      $this->logger('philasales')->notice('Created new ward %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.ward.canonical', ['ward' => $entity->id()]);
  }

}
