<?php

namespace Drupal\philasales\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the division entity edit forms.
 */
class DivisionForm extends ContentEntityForm {

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
      drupal_set_message($this->t('New division %label has been created.', $message_arguments));
      $this->logger('philasales')->notice('Created new division %label', $logger_arguments);
    }
    else {
      drupal_set_message($this->t('The division %label has been updated.', $message_arguments));
      $this->logger('philasales')->notice('Created new division %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.division.canonical', ['division' => $entity->id()]);
  }

}
