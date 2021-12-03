<?php

namespace Drupal\test_exercice\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\test_exercice\TimeEntryInterface;
use Drupal\user\UserInterface;

/**
 * Defines the timeEntry entity class.
 *
 * @ContentEntityType(
 *   id = "time_entry",
 *   label = @Translation("TimeEntry"),
 *   label_collection = @Translation("TimeEntries"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\test_exercice\TimeEntryListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\test_exercice\TimeEntryAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\test_exercice\Form\TimeEntryForm",
 *       "edit" = "Drupal\test_exercice\Form\TimeEntryForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "time_entry",
 *   data_table = "time_entry_field_data",
 *   revision_table = "time_entry_revision",
 *   revision_data_table = "time_entry_field_revision",
 *   show_revision_ui = TRUE,
 *   translatable = TRUE,
 *   admin_permission = "administer timeEntry",
 *   entity_keys = {
 *     "id" = "teid",
 *     "revision" = "revision_id",
 *     "langcode" = "langcode",
 *     "label" = "title",
 *     "uuid" = "uuid"
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/time-entry/add",
 *     "canonical" = "/time_entry/{time_entry}",
 *     "edit-form" = "/admin/content/time-entry/{time_entry}/edit",
 *     "delete-form" = "/admin/content/time-entry/{time_entry}/delete",
 *     "collection" = "/admin/content/time-entry"
 *   },
 *   field_ui_base_route = "entity.time_entry.settings"
 * )
 */
class TimeEntry extends RevisionableContentEntityBase implements TimeEntryInterface {

    use EntityChangedTrait;

    /**
     * {@inheritdoc}
     *
     * When a new timeEntry entity is created, set the uid entity reference to
     * the current user as the creator of the entity.
     */
    public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
        parent::preCreate($storage_controller, $values);
        $values += ['uid' => \Drupal::currentUser()->id()];
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle() {
        return $this->get('title')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title) {
        $this->set('title', $title);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedTime() {
        return $this->get('created')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedTime($timestamp) {
        $this->set('created', $timestamp);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner() {
        return $this->get('uid')->entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwnerId() {
        return $this->get('uid')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwnerId($uid) {
        $this->set('uid', $uid);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(UserInterface $account) {
        $this->set('uid', $account->id());
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

        $fields = parent::baseFieldDefinitions($entity_type);

        $fields['title'] = BaseFieldDefinition::create('string')
            ->setRevisionable(TRUE)
            ->setTranslatable(TRUE)
            ->setLabel(t('Title'))
            ->setDescription(t('The title of the timeEntry entity.'))
            ->setRequired(TRUE)
            ->setSetting('max_length', 255)
            ->setDisplayOptions('form', [
                'type' => 'string_textfield',
                'weight' => -5,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayOptions('view', [
                'label' => 'hidden',
                'type' => 'string',
                'weight' => -5,
            ])
            ->setDisplayConfigurable('view', TRUE);

        $fields['time'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('Time'))
            ->setDescription(t('Time field (integer)'))
            ->setTranslatable(false)
            ->setRequired(true)
            ->setDisplayOptions('view', array(
                'label' => 'above',
                'weight' => 4,
            ))
            ->setDisplayOptions('form', array(
                'weight' => 4,
            ))
            ->setDisplayConfigurable('form', true)
            ->setDisplayConfigurable('view', true);


        $fields['uid'] = BaseFieldDefinition::create('entity_reference')
            ->setRevisionable(TRUE)
            ->setTranslatable(TRUE)
            ->setLabel(t('Author'))
            ->setDescription(t('The user ID of the timeEntry author.'))
            ->setSetting('target_type', 'user')
            ->setDisplayOptions('form', [
                'type' => 'entity_reference_autocomplete',
                'settings' => [
                    'match_operator' => 'CONTAINS',
                    'size' => 60,
                    'placeholder' => '',
                ],
                'weight' => 15,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayOptions('view', [
                'label' => 'above',
                'type' => 'author',
                'weight' => 15,
            ])
            ->setDisplayConfigurable('view', TRUE);

        $fields['created'] = BaseFieldDefinition::create('created')
            ->setLabel(t('Authored on'))
            ->setTranslatable(TRUE)
            ->setDescription(t('The time that the timeEntry was created.'))
            ->setDisplayOptions('view', [
                'label' => 'above',
                'type' => 'timestamp',
                'weight' => 20,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayOptions('form', [
                'type' => 'datetime_timestamp',
                'weight' => 20,
            ])
            ->setDisplayConfigurable('view', TRUE);

        $fields['changed'] = BaseFieldDefinition::create('changed')
            ->setLabel(t('Changed'))
            ->setTranslatable(TRUE)
            ->setDescription(t('The time that the timeEntry was last edited.'));

        return $fields;
    }

}
