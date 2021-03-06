<?php

namespace DALTCORE\Permissions\Traits;

use DALTCORE\Permissions\Facades\Permission;
use DALTCORE\Permissions\Models\Role;

trait Permissible
{
    /**
     * User roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    /**
     * Check if user has role
     *
     * @param string $name Role to check if exists
     *
     * @return boolean true if user has role, else false
     */
    public function hasRole($name = null)
    {
        if ($name !== null) {
            return $this->roles->contains('name', $name);
        }

        return false;
    }

    /**
     * Check if user has permission
     *
     * @param string $name Permission to check if exists
     *
     * @return boolean true if user has permission else false
     */
    public function hasPermission($name)
    {
        if ($name !== null) {
            foreach ($this->roles as $role) {
                if($role->permissions->contains('name', $name) === true)
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Assign role to user
     *
     * @param string $name Name of the role
     *
     * @return object $this instance of model
     */
    public function giveRole($name)
    {
        return Permission::linkUserToRole($this, $name);
    }

    /**
     * Drop role from user
     *
     * @param string $name Name of the role
     *
     * @return object $this instance of model
     */
    public function dropRole($name)
    {
        return Permission::takeRoleFromUser($this, $name);
    }


}