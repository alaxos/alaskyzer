<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;

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

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Security->setConfig('unlockedActions', ['close', 'open', 'delete']);
    }

    /**
    * Index method
    *
    * @return void
    */
    public function index()
    {
        $this->paginate = [
            'contain' => ['TaskCategories', 'Applications', 'Servers']
        ];
        $this->set('tasks', $this->paginate($this->Filter->getFilterQuery()));
        $this->set('_serialize', ['tasks']);

        $taskCategories = $this->Tasks->TaskCategories->find('list', ['limit' => 200]);
        $applications = $this->Tasks->Applications->find('list', ['limit' => 200]);
        $servers = $this->Tasks->Servers->find('list', ['limit' => 200]);
        $this->set(compact('taskCategories', 'applications', 'servers'));
    }

    /**
     * View method
     *
     * @param string|null $id Task id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $task = $this->Tasks->get($id, [
            'contain' => ['TaskCategories', 'Applications', 'Servers']
        ]);
        $this->set('task', $task);
        $this->set('_serialize', ['task']);
    }

    public function details($id = null)
    {
        $task = $this->Tasks->get($id, [
            'contain' => ['TaskCategories', 'Applications', 'Servers']
        ]);
        $this->set('task', $task);
    }

    public function find()
    {
        $conditions = [];

        if($application_id = $this->getRequest()->getQuery('application_id'))
        {
            $conditions['application_id'] = $application_id;
        }

        $tasks = $this->Tasks->find('all')->contain(['TaskCategories', 'Applications', 'Servers'])->where($conditions);
        $this->set('tasks', $tasks);
    }


    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $task = $this->Tasks->newEntity();
        if ($this->getRequest()->is('post')) {
            $task = $this->Tasks->patchEntity($task, $this->getRequest()->getData());
            if ($this->Tasks->save($task)) {
                $this->Flash->success(___('the task has been saved'));

                return $this->redirect(['controller' => 'Dashboard', 'action' => 'index', '#' => $task->application_id . '_' . $task->id]);

            } else {
                $this->Flash->error(___('the task could not be saved. Please, try again.'));
            }
        }
        else
        {
            $this->setRequest($this->getRequest()->withData('application_id', $this->getRequest()->getQuery('application_id')));
        }

        $taskCategories = $this->Tasks->TaskCategories->find('list', ['limit' => 200]);
        $applications   = $this->Tasks->Applications->find('list', ['limit' => 200])->order(['name']);
        $servers        = $this->Tasks->Servers->find('list', ['limit' => 200]);

        $this->set(compact('task', 'taskCategories', 'applications', 'servers'));
        $this->set('_serialize', ['task']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Task id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $task = $this->Tasks->get($id, [
            'contain' => []
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
//             debug($this->getRequest()->getData());
//             die();
            $task = $this->Tasks->patchEntity($task, $this->getRequest()->getData());
            if ($this->Tasks->save($task)) {
                $this->Flash->success(___('the task has been saved'));
//                 return $this->redirect(['action' => 'index']);
                return $this->redirect(['controller' => 'Dashboard', 'action' => 'index', '#' => $task->application_id . '_' . $task->id]);
            } else {
                $this->Flash->error(___('the task could not be saved. Please, try again.'));
            }
        }
        $taskCategories = $this->Tasks->TaskCategories->find('list', ['limit' => 200]);
        $applications = $this->Tasks->Applications->find('list', ['limit' => 200]);
        $servers = $this->Tasks->Servers->find('list', ['limit' => 200]);
        $this->set(compact('task', 'taskCategories', 'applications', 'servers'));
        $this->set('_serialize', ['task']);
    }

    public function close($id = null)
    {
        if($this->Tasks->close($id))
        {
//             $this->Flash->success(___('the task has been closed'), ['plugin' => 'Alaxos']);
            $this->Flash->success(___('the task has been closed'));
        }
        else
        {
            $this->Flash->error(___('the task could not be closed'));
        }

        $task = $this->Tasks->get($id, [
            'contain' => []
        ]);

        return $this->redirect(['controller' => 'Dashboard', 'action' => 'index', '#' => $task->application_id . '_' . $task->id]);

//         return $this->redirect($this->referer(['action' => 'view', $id]));
    }

    public function open($id = null)
    {
        if($this->Tasks->open($id))
        {
            $this->Flash->success(___('the task has been opened'));
        }
        else
        {
            $this->Flash->error(___('the task could not be opened'));
        }

        $task = $this->Tasks->get($id, [
            'contain' => []
        ]);

        return $this->redirect(['controller' => 'Dashboard', 'action' => 'index', '#' => $task->application_id . '_' . $task->id]);

//         return $this->redirect($this->referer(['action' => 'view', $id]));
    }

    /**
     * Delete method
     *
     * @param string|null $id Task id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $task = $this->Tasks->get($id);

        try
        {
            $application_id = $task->application_id;

            if ($this->Tasks->delete($task)) {
                $this->Flash->success(___('the task has been deleted'));
            } else {
                $this->Flash->error(___('the task could not be deleted. Please, try again.'));
            }
        }
        catch(\Exception $ex)
        {
            if($ex->getCode() == 23000)
            {
                $this->Flash->error(___('the task could not be deleted as it is still used in the database'));
            }
            else
            {
                $this->Flash->error(sprintf(__('The task could not be deleted: %s', $ex->getMessage())));
            }
        }

        return $this->redirect(['controller' => 'Dashboard', 'action' => 'index', '#' => $application_id]);
    }

    /**
     * Delete all method
     */
    public function delete_all() {
        $this->getRequest()->allowMethod('post', 'delete');

        $checkedIds = $this->getRequest()->getData('checked_ids');
        if(!empty($checkedIds)) {

            $query = $this->Tasks->query();
            $query->delete()->where(['id IN' => $checkedIds]);

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
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function copy($id = null)
    {
        $task = $this->Tasks->get($id, [
            'contain' => []
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $task = $this->Tasks->newEntity();
            $task = $this->Tasks->patchEntity($task, $this->getRequest()->getData());
            if ($this->Tasks->save($task)) {
                $this->Flash->success(___('the task has been saved'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the task could not be saved. Please, try again.'));
            }
        }
        $taskCategories = $this->Tasks->TaskCategories->find('list', ['limit' => 200]);
        $applications = $this->Tasks->Applications->find('list', ['limit' => 200]);
        $servers = $this->Tasks->Servers->find('list', ['limit' => 200]);

        $task->id = $id;
        $this->set(compact('task', 'taskCategories', 'applications', 'servers'));
        $this->set('_serialize', ['task']);
    }

    public function open_tasks()
    {
        $this->autoRender = false;

        $applications = $this->Tasks->Applications->find()->contain(['Tasks' => function($q){
                return $q->where(['Tasks.closed IS NULL', 'Tasks.abandoned IS NULL']);
            }])->order(['name']);

        $this->setResponse($this->getResponse()->withType('json')->withStringBody(json_encode($applications->toArray())));
    }
}
