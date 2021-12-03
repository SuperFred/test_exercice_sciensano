<?php

namespace Drupal\test_exercice;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a timeEntry entity type.
 */
interface TimeEntryInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

    /**
     * Gets the timeEntry title.
     *
     * @return string
     *   Title of the timeEntry.
     */
    public function getTitle();

    /**
     * Sets the timeEntry title.
     *
     * @param string $title
     *   The timeEntry title.
     *
     * @return \Drupal\test_exercice\TimeEntryInterface
     *   The called timeEntry entity.
     */
    public function setTitle($title);

    /**
     * Gets the timeEntry creation timestamp.
     *
     * @return int
     *   Creation timestamp of the timeEntry.
     */
    public function getCreatedTime();

    /**
     * Sets the timeEntry creation timestamp.
     *
     * @param int $timestamp
     *   The timeEntry creation timestamp.
     *
     * @return \Drupal\test_exercice\TimeEntryInterface
     *   The called timeEntry entity.
     */
    public function setCreatedTime($timestamp);

}
