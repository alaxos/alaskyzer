<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Tasks Controller
 *
 * @property \App\Model\Table\TasksTable $Tasks
 * @property \Alaxos\Controller\Component\FilterComponent $Filter
 */
class TasksController extends AppController
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
        $this->set('tasks', $this->paginate($this->Filter->getFilterQuery()));
        $this->set('_serialize', ['tasks']);
        
        $status = $this->Tasks->Status->find('list', ['limit' => 200]);
        $applications = $this->Tasks->Applications->find('list', ['limit' => 200]);
        $servers = $this->Tasks->Servers->find('list', ['limit' => 200]);
        $this->set(compact('status', 'applications', 'servers'));
    }

    /**
     * View method
     *
     * @param string|null $id Task id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $task = $this->Tasks->get($id, [
            'contain' => ['Status', 'Applications', 'Servers']
        ]);
        $this->set('task', $task);
        $this->set('_serialize', ['task']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $task = $this->Tasks->newEntity();
        if ($this->request->is('post')) {
            $task = $this->Tasks->patchEntity($task, $this->request->data);
            if ($this->Tasks->save($task)) {
                $this->Flash->success(___('the task has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the task could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $status = $this->Tasks->Status->find('list', ['limit' => 200]);
        $applications = $this->Tasks->Applications->find('list', ['limit' => 200]);
        $servers = $this->Tasks->Servers->find('list', ['limit' => 200]);
        $this->set(compact('task', 'status', 'applications', 'servers'));
        $this->set('_serialize', ['task']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Task id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $task = $this->Tasks->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $task = $this->Tasks->patchEntity($task, $this->request->data);
            if ($this->Tasks->save($task)) {
                $this->Flash->success(___('the task has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the task could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $status = $this->Tasks->Status->find('list', ['limit' => 200]);
        $applications = $this->Tasks->Applications->find('list', ['limit' => 200]);
        $servers = $this->Tasks->Servers->find('list', ['limit' => 200]);
        $this->set(compact('task', 'status', 'applications', 'servers'));
        $this->set('_serialize', ['task']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Task id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $task = $this->Tasks->get($id);
        
        try
        {
            if ($this->Tasks->delete($task)) {
                $this->Flash->success(___('the task has been deleted'), ['plugin' => 'Alaxos']);
            } else {
                $this->Flash->error(___('the task could not be deleted. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        catch(\Exception $ex)
        {
            if($ex->getCode() == 23000)
            {
                $this->Flash->error(___('the task could not be deleted as it is still used in the database'), ['plugin' => 'Alaxos']);
            }
            else
            {
                $this->Flash->error(sprintf(__('The task could not be deleted: %s', $ex->getMessage())), ['plugin' => 'Alaxos']);
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
            
            $query = $this->Tasks->query();
            $query->delete()->where(['id IN' => $this->request->data['checked_ids']]);
            
            try{
                if ($statement = $query->execute()) {
                    $deleted_total = $statement->rowCount();
                    if($deleted_total == 1){
                        $this->Flash->set(___('the selected task has been deleted.'), ['element' => 'Alaxos.success']);
                    }
                    elseif($deleted_total > 1){
                        $this->Flash->set(sprintf(__('The %s selected tasks have been deleted.'), $deleted_total), ['element' => 'Alaxos.success']);
                    }
                } else {
                    $this->Flash->set(___('the selected tasks could not be deleted. Please, try again.'), ['element' => 'Alaxos.error']);
                }
            }
            catch(\Exception $ex){
                $this->Flash->set(___('the selected tasks could not be deleted. Please, try again.'), ['element' => 'Alaxos.error', 'params' => ['exception_message' => $ex->getMessage()]]);
            }
        } else {
            $this->Flash->set(___('there was no task to delete'), ['element' => 'Alaxos.error']);
        }
        
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Copy method
     *
     * @param string|null $id Task id.
     * @return void Redirects on successful copy, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function copy($id = null)
    {
        $task = $this->Tasks->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $task = $this->Tasks->newEntity();
            $task = $this->Tasks->patchEntity($task, $this->request->data);
            if ($this->Tasks->save($task)) {
                $this->Flash->success(___('the task has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the task could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $status = $this->Tasks->Status->find('list', ['limit' => 200]);
        $applications = $this->Tasks->Applications->find('list', ['limit' => 200]);
        $servers = $this->Tasks->Servers->find('list', ['limit' => 200]);
        
        $task->id = $id;
        $this->set(compact('task', 'status', 'applications', 'servers'));
        $this->set('_serialize', ['task']);
    }
}
