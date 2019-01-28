<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Roles Controller
 *
 * @property \App\Model\Table\RolesTable $Roles
 * @property \Alaxos\Controller\Component\FilterComponent $Filter
 */
class RolesController extends AppController
{

    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = ['Alaxos.AlaxosHtml', 'Alaxos.AlaxosForm', 'Alaxos.Navbars'];

    /**
     * Components
     *
     * @var array
     */
    public $components = ['Alaxos.Filter'];

    /**
    * Index method
    *
    * @return void
    */
    public function index()
    {
        $this->set('roles', $this->paginate($this->Filter->getFilterQuery()));
        $this->set('_serialize', ['roles']);
    }

    /**
     * View method
     *
     * @param string|null $id Role id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $role = $this->Roles->get($id, [
            'contain' => ['Users']
        ]);
        $this->set('role', $role);
        $this->set('_serialize', ['role']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $role = $this->Roles->newEntity();
        if ($this->getRequest()->is('post')) {
            $role = $this->Roles->patchEntity($role, $this->getRequest()->getData());
            if ($this->Roles->save($role)) {
                $this->Flash->success(___('the role has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the role could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $this->set(compact('role'));
        $this->set('_serialize', ['role']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Role id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $role = $this->Roles->get($id, [
            'contain' => []
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $role = $this->Roles->patchEntity($role, $this->getRequest()->getData());
            if ($this->Roles->save($role)) {
                $this->Flash->success(___('the role has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'view', $id]);
            } else {
                $this->Flash->error(___('the role could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $this->set(compact('role'));
        $this->set('_serialize', ['role']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Role id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $role = $this->Roles->get($id);

        try
        {
            if ($this->Roles->delete($role)) {
                $this->Flash->success(___('the role has been deleted'), ['plugin' => 'Alaxos']);
            } else {
                $this->Flash->error(___('the role could not be deleted. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        catch(\Exception $ex)
        {
            if($ex->getCode() == 23000)
            {
                $this->Flash->error(___('the role could not be deleted as it is still used in the database'), ['plugin' => 'Alaxos']);
            }
            else
            {
                $this->Flash->error(sprintf(__('The role could not be deleted: %s', $ex->getMessage())), ['plugin' => 'Alaxos']);
            }
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete all method
     */
    public function deleteAll() {

        $this->getRequest()->allowMethod('post', 'delete');

        $checkedIds = $this->getRequest()->getData('checked_ids');
        if(!empty($checkedIds)) {

            $roles = $this->Roles->find()->where(['id IN' => $checkedIds]);

            $total         = $roles->count();
            $total_deleted = 0;

            foreach($roles as $role) {

                try {

                    if ($this->Roles->delete($role)) {
                        $total_deleted++;
                    }

                } catch(\Exception $ex) {
                    $this->log($ex);
                }

            }

            if ($total_deleted == $total) {

                if ($total_deleted == 1) {
                    $this->Flash->success(___('the selected role has been deleted.'), ['plugin' => 'Alaxos']);
                } elseif ($total_deleted > 1) {
                    $this->Flash->success(sprintf(__('The %s selected roles have been deleted.'), $total_deleted), ['plugin' => 'Alaxos']);
                }

            } else {

                if ($total_deleted == 0) {
                    $this->Flash->error(___('the selected roles could not be deleted. Please, try again.'), ['plugin' => 'Alaxos']);
                } else {
                    $this->Flash->error(sprintf(___('only %s selected roles on %s could be deleted'), $total_deleted, $total), ['element' => 'Alaxos']);
                }

            }

        } else {
            $this->Flash->error(___('there was no role to delete'), ['plugin' => 'Alaxos']);
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Copy method
     *
     * @param string|null $id Role id.
     * @return void Redirects on successful copy, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function copy($id = null)
    {
        $role = $this->Roles->get($id, [
            'contain' => []
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $role = $this->Roles->newEntity();
            $role = $this->Roles->patchEntity($role, $this->getRequest()->getData());
            if ($this->Roles->save($role)) {
                $this->Flash->success(___('the role has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the role could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }

        $role->id = $id;
        $this->set(compact('role'));
        $this->set('_serialize', ['role']);
    }
}
