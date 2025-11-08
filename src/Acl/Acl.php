<?php

/*
 * @package     Modern Joomla Entity
 *
 * @author      Anibal Sanchez <team@extly.com>
 * @copyright   Copyright (c)2025 Anibal Sanchez. All rights reserved.
 *              Based on phproberto/joomla-entity by Roberto Segura López
 *
 * @license     LGPL-2.1+
 *
 * @see         https://www.extly.com
 */

namespace Extly\Joomla\Entity\Acl;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Acl\Contracts\Aclable;
use Extly\Joomla\Entity\Core\Column;
use Extly\Joomla\Entity\Core\Contracts\Publishable;
use Extly\Joomla\Entity\Decorator;
use Extly\Joomla\Entity\Users\Contracts\Ownerable;
use Extly\Joomla\Entity\Users\User;

/**
 * Entity ACL.
 *
 * @since   1.0.0
 */
class Acl extends Decorator
{
    /**
     * Associated asset name.
     *
     * @var  string
     */
    protected $assetName;

    /**
     * Can user administrate entity?
     *
     * @var  bool
     */
    protected $canAdmin;

    /**
     * User to check against.
     *
     * @var  User
     */
    protected $user;

    /**
     * Constructor.
     *
     * @param Aclable $aclable Entity to decorate.
     * @param   User     $user    User to check permissions.
     */
    public function __construct(Aclable $aclable, ?User $user = null)
    {
        $this->entity = $aclable;
        $this->user = $user ?: User::active();
    }

    /**
     * Check permission.
     *
     * @param   string  $action  Action to check. Example: core.create
     *
     * @return  bool
     */
    public function can($action)
    {
        if ($this->user->isRoot() || $this->canAdmin()) {
            return true;
        }

        $action = $this->entity->aclPrefix().'.'.$action;

        return $this->user->authorise($action, $this->entity->aclAssetName());
    }

    /**
     * Check can administrate entity.
     *
     * @return  bool
     */
    public function canAdmin()
    {
        if (null === $this->canAdmin) {
            $this->canAdmin = $this->user->authorise('core.admin', $this->entity->aclAssetName());
        }

        return $this->canAdmin;
    }

    /**
     * Check if user can create an entity.
     *
     * @return  bool
     */
    public function canCreate()
    {
        if ($this->can('create')) {
            return true;
        }

        return $this->isOwner() && $this->can('create.own');
    }

    /**
     * Check if user can delete associated entity.
     *
     * @return  bool
     */
    public function canDelete()
    {
        if (!$this->entity->hasId()) {
            return false;
        }

        if ($this->can('delete')) {
            return true;
        }

        return $this->isOwner() && $this->can('delete.own');
    }

    /**
     * Check if current user can edit this entity.
     *
     * @return  bool
     */
    public function canEdit()
    {
        if (!$this->entity->hasId()) {
            return false;
        }

        if ($this->can('edit')) {
            return true;
        }

        return $this->isOwner() && $this->can('edit.own');
    }

    /**
     * Check if user can edit this entity state.
     *
     * @return  bool
     */
    public function canEditState()
    {
        if (!$this->entity->hasId()) {
            return false;
        }

        if ($this->can('edit.state')) {
            return true;
        }

        return $this->isOwner() && $this->can('edit.state.own');
    }

    /**
     * Check if user can view this entity.
     *
     * @return  bool
     */
    public function canView()
    {
        if (!$this->entity->hasId() || !$this->isPublishedEntity()) {
            return false;
        }

        if ($this->canEdit() || $this->canEditState()) {
            return true;
        }

        if (!$this->entity->has(Column::ACCESS)) {
            return true;
        }

        return in_array($this->entity->get(Column::ACCESS), $this->user->getAuthorisedViewLevels());
    }

    /**
     * Check if user is the owner of the entity.
     *
     * @return  bool
     */
    protected function isOwner()
    {
        if (!$this->entity instanceof Ownerable) {
            return false;
        }

        return $this->entity->isOwner();
    }

    /**
     * Check if the entity is published.
     *
     * @return  bool
     */
    protected function isPublishedEntity()
    {
        if (!$this->entity instanceof Publishable) {
            return true;
        }

        return $this->entity->isPublished();
    }
}
