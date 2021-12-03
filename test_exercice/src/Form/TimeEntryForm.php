<?php

namespace Drupal\test_exercice\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the timeEntry entity edit forms.
 */
class TimeEntryForm extends ContentEntityForm {

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
            $this->messenger()->addStatus($this->t('New timeEntry %label has been created.', $message_arguments));
            $this->logger('test_exercice')->notice('Created new timeEntry %label', $logger_arguments);
        }
        else {
            $this->messenger()->addStatus($this->t('The timeEntry %label has been updated.', $message_arguments));
            $this->logger('test_exercice')->notice('Updated new timeEntry %label.', $logger_arguments);
        }

        $form_state->setRedirect('entity.time_entry.canonical', ['time_entry' => $entity->id()]);
    }

}
