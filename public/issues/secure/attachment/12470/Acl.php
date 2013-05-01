<?php
/**
 * Resource for initializing ACL
 */
class My_Application_Resource_Acl
    extends Zend_Application_Resource_ResourceAbstract
{
    const DEFAULT_REGISTRY_KEY = 'Zend_Acl';

    private $_missingPropertyMessage = 'Missing property %s for %s in %s.';

    /**
     * @var Zend_Acl
     */
    protected $_acl;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Zend_Acl
     */
    public function init()
    {
        $this->getAcl();
        $this->store();
        return $this->_acl;
    }


    /**
     * Retrieve ACL object
     *
     * @return Zend_Acl
     */
    public function getAcl()
    {
        if ($this->_acl === null) {
            $this->_acl = new Zend_Acl();

            $options = $this->getOptions();

            $roles = array();
            $resources = array();

            if (isset($options['roles'])) {
                $roles = $options['roles'];
            }

            if (isset($options['resources'])) {
                $resources = $options['resources'];
            }

            $this->_addRoles($roles);
            $this->_addResources($resources);
        }

        return $this->_acl;
    }

    /**
     * Stores ACL in registry
     *
     * @return void
     */
    public function store()
    {
        $options = $this->getOptions();

        $key = self::DEFAULT_REGISTRY_KEY;

        if (isset($options['storage']['registry']['key']) &&
            !empty($options['storage']['registry']['key'])) {
            $key = $options['storage']['registry']['key'];
        }

        Zend_Registry::set($key, $this->_acl);
    }

    /**
     * Method used to add our specified roles to our ACL instance.
     */
    protected function _addRoles(array $roles)
    {
        foreach ($roles as $roleName => $properties) {
            // If the properties aren't set as an array, then we will consider
            // the value as the role ID.
            if (!is_array($properties)) {
                $properties = array('id' => $properties);
            }

            $id = $properties['id'];

            if (is_null($id) || empty($id)) {
                throw new Zend_Application_Resource_Exception(sprintf(
                  $this->_missingPropertyMessage, 'ID', 'role', $roleName
                ));
            }

            $this->_addRoleById($roles, $id);
        }
    }

    private function _addRoleById(array $roles, $roleId) {
        foreach ($roles as $roleName => $properties) {
            // If the properties aren't set as an array, then we will consider
            // the value as the role ID.
            if (!is_array($properties)) {
                $properties = array('id' => $properties);
            }

            $id = $properties['id'];
            $parents = array();

            if (is_null($id) || empty($id)) {
                throw new Zend_Application_Resource_Exception(sprintf(
                  $this->_missingPropertyMessage, 'ID', 'role', $roleName
                ));
            }

            if (isset($properties['parents']) && !empty($properties['parents'])) {
                $parents = explode(',', $properties['parents']);
            }

            if ($id == $roleId) {
                if ($this->_acl->hasRole($roleId)) return;

                foreach ($parents as $parent) {
                    if (!$this->_acl->hasRole($parent)) {
                        $this->_addRoleById($roles, $parent);
                    }
                }

                $this->_acl->addRole(new Zend_Acl_Role($roleId), $parents);

                // Since we've finished adding the specified role, let's break
                // from our loop.
                break;
            }
        }
    }

    /**
     * Method used to add our specified resources to our ACL instance and create
     * any rules if specified.
     */
    protected function _addResources(array $resources)
    {
        foreach ($resources as $resourceName => $properties) {
            // If the properties aren't set as an array, then we will consider
            // the value as the resource ID.
            if (!is_array($properties)) {
                $properties = array('id' => $properties);
            }

            $resourceName = strtolower($resourceName);
            $id = $properties['id'];

            if ($resourceName === 'all') {
                $id = 'all';
            }

            if (is_null($id) || empty($id)) {
                throw new Zend_Application_Resource_Exception(sprintf(
                  $this->_missingPropertyMessage, 'ID', 'resource', $resourceName
                ));
            }

            $this->_addResourceById($resources, $id);
        }
    }

    private function _addResourceById(array $resources, $resourceId) {
        foreach ($resources as $resourceName => $properties) {
            $resourceName = strtolower($resourceName);

            // If the properties aren't set as an array, then we will consider
            // the value as the resource ID.
            if (!is_array($properties)) {
                $properties = array('id' => $properties);
            }

            $id = $properties['id'];
            $parent = null;
            $resource = null;
            $allowRules = array();
            $denyRules = array();

            if ($resourceName === 'all') {
                $id = 'all';
            }

            if (is_null($id) || empty($id)) {
                throw new Zend_Application_Resource_Exception(sprintf(
                  $this->_missingPropertyMessage, 'ID', 'resource', $resourceName
                ));
            }

            if (isset($properties['parent']) && !empty($properties['parent'])) {
                $parent = $properties['parent'];
            }

            if (isset($properties['allow']) && !empty($properties['allow'])) {
                $allowRules = $properties['allow'];
            }

            if (isset($properties['deny']) && !empty($properties['deny'])) {
                $denyRules = $properties['deny'];
            }

            if ($id == $resourceId) {
                if ($this->_acl->has($resourceId)) return;

                if (!is_null($parent)) {
                    if (!$this->_acl->has($parent)) {
                        $this->_addResourceById($resources, $parent);
                    }
                }

                if ($resourceId !== 'all') {
                    $resource = new Zend_Acl_Resource($resourceId);
                    $this->_acl->addResource($resource, $parent);
                }

                $this->_addRules(Zend_Acl::TYPE_ALLOW, $allowRules, $resource, $resourceName);
                $this->_addRules(Zend_Acl::TYPE_DENY, $denyRules, $resource, $resourceName);

                // Since we've finished adding the specified resource, let's break
                // from our loop.
                break;
            }
        }
    }

    /**
     * Method used to add rules to the specified resource.
     */
    protected function _addRules($type = Zend_Acl::TYPE_ALLOW, array $rules,
        $resource, $resourceName)
    {
        foreach ($rules as $privilege => $ruleProperties) {
            // If the user sets the privilege value to a string, we will consider
            // this as the list of roles.
            if (!is_array($ruleProperties)) {
                $ruleProperties = array('roles' => $ruleProperties);
            }

            if ($privilege === 'all') {
                $privilege = null;
            }

            $roles = $ruleProperties['roles'];

            if (is_null($roles) || empty($roles)) {
                $section = 'rules';
                if ($type === Zend_Acl::TYPE_ALLOW) {
                    $section = 'allow ' . $section;
                } else {
                    $section = 'deny ' . $section;
                }

                throw new Zend_Application_Resource_Exception(sprintf(
                  $this->_missingPropertyMessage, 'roles', $section, 'resource ' . $resourceName
                ));
            }

            $roles = explode(',', $roles);

            if ($roles[0] === 'all') {
                $roles = null;
            }

            $assert = null;

            if (isset($ruleProperties['assert']) && !empty($ruleProperties['assert'])) {
                $assert = $ruleProperties['assert'];
                $assert = new $assert();
            }

            $this->_acl->setRule(
                Zend_Acl::OP_ADD,
                $type,
                $roles,
                $resource,
                $privilege,
                $asset
            );
        }
    }
}