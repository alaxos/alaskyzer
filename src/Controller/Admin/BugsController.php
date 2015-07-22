<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Bugs Controller
 *
 * @property \App\Model\Table\BugsTable $Bugs
 * @property \Alaxos\Controller\Component\FilterComponent $Filter
 */
class BugsController extends AppController
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
        $this->paginate = [
            'contain' => ['Status', 'Applications', 'Servers']
        ];
        $this->set('bugs', $this->paginate($this->Filter->getFilterQuery()));
        $this->set('_serialize', ['bugs']);
        
        $status = $this->Bugs->Status->find('list', ['limit' => 200]);
        $applications = $this->Bugs->Applications->find('list', ['limit' => 200]);
        $servers = $this->Bugs->Servers->find('list', ['limit' => 200]);
        $this->set(compact('status', 'applications', 'servers'));
    }

    /**
     * View method
     *
     * @param string|null $id Bug id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $bug = $this->Bugs->get($id, [
            'contain' => ['Status', 'Applications', 'Servers']
        ]);
        $this->set('bug', $bug);
        $this->set('_serialize', ['bug']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $bug = $this->Bugs->newEntity();
        if ($this->request->is('post')) {
            $bug = $this->Bugs->patchEntity($bug, $this->request->data);
            if ($this->Bugs->save($bug)) {
                $this->Flash->success(___('the bug has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the bug could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $status = $this->Bugs->Status->find('list', ['limit' => 200]);
        $applications = $this->Bugs->Applications->find('list', ['limit' => 200]);
        $servers = $this->Bugs->Servers->find('list', ['limit' => 200]);
        $this->set(compact('bug', 'status', 'applications', 'servers'));
        $this->set('_serialize', ['bug']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Bug id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bug = $this->Bugs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bug = $this->Bugs->patchEntity($bug, $this->request->data);
            if ($this->Bugs->save($bug)) {
                $this->Flash->success(___('the bug has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the bug could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $status = $this->Bugs->Status->find('list', ['limit' => 200]);
        $applications = $this->Bugs->Applications->find('list', ['limit' => 200]);
        $servers = $this->Bugs->Servers->find('list', ['limit' => 200]);
        $this->set(compact('bug', 'status', 'applications', 'servers'));
        $this->set('_serialize', ['bug']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Bug id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bug = $this->Bugs->get($id);
        
        try
        {
            if ($this->Bugs->delete($bug)) {
                $this->Flash->success(___('the bug has been deleted'), ['plugin' => 'Alaxos']);
            } else {
                $this->Flash->error(___('the bug could not be deleted. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        catch(\Exception $ex)
        {
            if($ex->getCode() == 23000)
            {
                $this->Flash->error(___('the bug could not be deleted as it is still used in the database'), ['plugin' => 'Alaxos']);
            }
            else
            {
                $this->Flash->error(sprintf(__('The bug could not be deleted: %s', $ex->getMessage())), ['plugin' => 'Alaxos']);
            }
        }
        
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Delete all method
     */
    public function delete_all() {
        $this->request->allowMethod('post', 'delete');
        
        if(isset($this->request->data['checked_ids']) && !empty($this->request->data['checked_ids'])){
            
            $query = $this->Bugs->query();
            $query->delete()->where(['id IN' => $this->request->data['checked_ids']]);
            
            try{
                if ($statement = $query->execute()) {
                    $deleted_total = $statement->rowCount();
                    if($deleted_total == 1){
                        $this->Flash->set(___('the selected bug has been deleted.'), ['element' => 'Alaxos.success']);
                    }
                    elseif($deleted_total > 1){
                        $this->Flash->set(sprintf(__('The %s selected bugs have been deleted.'), $deleted_total), ['element' => 'Alaxos.success']);
                    }
                } else {
                    $this->Flash->set(___('the selected bugs could not be deleted. Please, try again.'), ['element' => 'Alaxos.error']);
                }
            }
            catch(\Exception $ex){
                $this->Flash->set(___('the selected bugs could not be deleted. Please, try again.'), ['element' => 'Alaxos.error', 'params' => ['exception_message' => $ex->getMessage()]]);
            }
        } else {
            $this->Flash->set(___('there was no bug to delete'), ['element' => 'Alaxos.error']);
        }
        
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Copy method
     *
     * @param string|null $id Bug id.
     * @return void Redirects on successful copy, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function copy($id = null)
    {
        $bug = $this->Bugs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bug = $this->Bugs->newEntity();
            $bug = $this->Bugs->patchEntity($bug, $this->request->data);
            if ($this->Bugs->save($bug)) {
                $this->Flash->success(___('the bug has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the bug could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $status = $this->Bugs->Status->find('list', ['limit' => 200]);
        $applications = $this->Bugs->Applications->find('list', ['limit' => 200]);
        $servers = $this->Bugs->Servers->find('list', ['limit' => 200]);
        
        $bug->id = $id;
        $this->set(compact('bug', 'status', 'applications', 'servers'));
        $this->set('_serialize', ['bug']);
    }
}
