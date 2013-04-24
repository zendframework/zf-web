<?php
/**
 * GiRaFfe
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @version $Id$
 */

/**
 * Grf controller class
 */
class GrfsController extends Zend_Controller_Action
{
    /**
     * Upload a new GRF
     */
    public function uploadAction()
    {
        $form = new App_Form(array(
            'action'         => $this->_helper->url->url(),
            'method'         => 'post',
            'enctype'        => 'multipart/form-data',
            'elements' => array(
                'grf_file' => array('file', array(
                    'label'       => 'GRF-File',
                    'required'    => true,
                    'destination' => UPLOADS_PATH,
                    'validators' => array(
                        array('count', false, array('1')),
                        array('extension', false, array('grf')),
                    )
                )),
                'readme_file' => array('file', array(
                    'label'       => 'Readme',
                    'required'    => true,
                    'destination' => UPLOADS_PATH,
                    'validators' => array(
                        array('count', false, array('1')),
                        array('extension', false, array('txt')),
                    )
                )),
                'terms_accepted' => array('checkbox', array(
                    'label'      => 'Herby i confirm that the requirements noted in the terms & conditions are met',
                    'required' => true
                )),
                'submit' => array('submit', array(
                    'label' => 'Upload GRF'
                ))
            )
        ));
        
        if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
            $data = $form->getValues();
            
            die(print_r($data));
        }

        $this->view->form  = $form;
    }
    
    /**
     * Edit a users
     */
    public function editAction()
    {
        $usersModel = new Users();
        $form       = $this->_getForm(false);
           
        $user = $usersModel->getUser($this->_getParam('userId'));
        $form->setDefaults($user);

        if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
            $data = $form->getValues();

            if ($usersModel->isUserNameInUse($data['user_name'], $user['user_id'])) {
                $form->getElement('user_name')->addValidator('customMessages', false, array('Username is already in use'));
            }
            
            if ($usersModel->isEmailInUse($data['user_email'], $user['user_id'])) {
                $form->getElement('user_email')->addValidator('customMessages', false, array('E-Mail is already in use'));
            }
            
            if ($form->isValid($_POST)) {
                $password = (!empty($data['user_password']) ? $data['user_password'] : null);
                
                $usersModel->updateUser($user['user_id'],
                                        $data['user_name'],
                                        $data['user_email'],
                                        $data['user_role'],
                                        $password);

                $this->_helper->getHelper('Redirector')->gotoRouteAndExit(array(), 'users-index');
            }
        }

        $this->view->form  = $form;
        $this->view->users = $usersModel->getUsers();
    }
    
    /**
     * Delete a user
     */
    public function deleteAction()
    {
        $usersModel = new Users();
        
        $usersModel->deleteUser($this->_getParam('userId'));
        
        $this->_helper->getHelper('Redirector')->gotoRouteAndExit(array(), 'users-index');
    }
    
    /**
     * Get the users form
     *
     * @param  boolean $passwordRequired
     * @return App_Form
     */
    protected function _getForm($passwordRequired)
    {
        
        if ($passwordRequired) {
            $form->getElement('user_password')->setRequired(true);
            $form->getElement('user_password_confirm')->setRequired(true);
        }
        
        return $form;
    }
}
