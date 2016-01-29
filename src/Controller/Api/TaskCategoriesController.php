<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * TaskCategories Controller
 *
 * @property \App\Model\Table\TaskCategoriesTable $TaskCategories
 * @property \Alaxos\Controller\Component\FilterComponent $Filter
 */
class TaskCategoriesController extends AppController
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
        $query = $this->Filter->getFilterQuery();
        
        $task_categories = [];
        
        foreach($query as $task_category)
        {
            $tc = [
                'id'   => $task_category->id,
                'name' => $task_category->name,
            ];
            
            $task_categories[] = $tc;
        }
        
        $this->autoRender = false;
        $this->response->type('json');
        $this->response->body(json_encode($task_categories));
    }

    /**
     * View method
     *
     * @param string|null $id Task Category id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $taskCategory = $this->TaskCategories->get($id, [
            'contain' => ['Tasks']
        ]);
        $this->set('taskCategory', $taskCategory);
        $this->set('_serialize', ['taskCategory']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $taskCategory = $this->TaskCategories->newEntity();
        if ($this->request->is('post')) {
            $taskCategory = $this->TaskCategories->patchEntity($taskCategory, $this->request->data);
            if ($this->TaskCategories->save($taskCategory)) {
                $this->Flash->success(___('the task category has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the task category could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $this->set(compact('taskCategory'));
        $this->set('_serialize', ['taskCategory']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Task Category id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $taskCategory = $this->TaskCategories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $taskCategory = $this->TaskCategories->patchEntity($taskCategory, $this->request->data);
            if ($this->TaskCategories->save($taskCategory)) {
                $this->Flash->success(___('the task category has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the task category could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $this->set(compact('taskCategory'));
        $this->set('_serialize', ['taskCategory']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Task Category id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $taskCategory = $this->TaskCategories->get($id);
        
        try
        {
            if ($this->TaskCategories->delete($taskCategory)) {
                $this->Flash->success(___('the task category has been deleted'), ['plugin' => 'Alaxos']);
            } else {
                $this->Flash->error(___('the task category could not be deleted. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        catch(\Exception $ex)
        {
            if($ex->getCode() == 23000)
            {
                $this->Flash->error(___('the task category could not be deleted as it is still used in the database'), ['plugin' => 'Alaxos']);
            }
            else
            {
                $this->Flash->error(sprintf(__('The task category could not be deleted: %s', $ex->getMessage())), ['plugin' => 'Alaxos']);
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
            
            $query = $this->TaskCategories->query();
            $query->delete()->where(['id IN' => $this->request->data['checked_ids']]);
            
            try{
                if ($statement = $query->execute()) {
                    $deleted_total = $statement->rowCount();
                    if($deleted_total == 1){
                        $this->Flash->set(___('the selected task category has been deleted.'), ['element' => 'Alaxos.success']);
                    }
                    elseif($deleted_total > 1){
                        $this->Flash->set(sprintf(__('The %s selected taskcategories have been deleted.'), $deleted_total), ['element' => 'Alaxos.success']);
                    }
                } else {
                    $this->Flash->set(___('the selected taskcategories could not be deleted. Please, try again.'), ['element' => 'Alaxos.error']);
                }
            }
            catch(\Exception $ex){
                $this->Flash->set(___('the selected taskcategories could not be deleted. Please, try again.'), ['element' => 'Alaxos.error', 'params' => ['exception_message' => $ex->getMessage()]]);
            }
        } else {
            $this->Flash->set(___('there was no task category to delete'), ['element' => 'Alaxos.error']);
        }
        
        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Copy method
     *
     * @param string|null $id Task Category id.
     * @return void Redirects on successful copy, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function copy($id = null)
    {
        $taskCategory = $this->TaskCategories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $taskCategory = $this->TaskCategories->newEntity();
            $taskCategory = $this->TaskCategories->patchEntity($taskCategory, $this->request->data);
            if ($this->TaskCategories->save($taskCategory)) {
                $this->Flash->success(___('the task category has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the task category could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        
        $taskCategory->id = $id;
        $this->set(compact('taskCategory'));
        $this->set('_serialize', ['taskCategory']);
    }
}
