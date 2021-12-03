<?php

namespace Drupal\test_exercice;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the timeEntry entity type.
 */
class TimeEntryAccessControlHandler extends EntityAccessControlHandler {

    /**
     * {@inheritdoc}
     */
    protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

        switch ($operation) {
            case 'view':
                return AccessResult::allowedIfHasPermission($account, 'view timeEntry');

            case 'update':
                return AccessResult::allowedIfHasPermissions($account, ['edit timeEntry', 'administer timeEntry'], 'OR');

            case 'delete':
                return AccessResult::allowedIfHasPermissions($account, ['delete timeEntry', 'administer timeEntry'], 'OR');

            default:
                // No opinion.
                return AccessResult::neutral();
        }

    }

    /**
     * {@inheritdoc}
     */
    protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
        return AccessResult::allowedIfHasPermissions($account, ['create timeEntry', 'administer timeEntry'], 'OR');
    }

}
