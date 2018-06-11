<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Frameworks Controller
 *
 * @property \App\Model\Table\FrameworksTable $Frameworks
 * @property \Alaxos\Controller\Component\FilterComponent $Filter
 */
class FrameworksController extends AppController
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
        $this->set('frameworks', $this->paginate($this->Filter->getFilterQuery()));
        $this->set('_serialize', ['frameworks']);
    }

    /**
     * View method
     *
     * @param string|null $id Framework id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $framework = $this->Frameworks->get($id, [
            'contain' => ['Applications']
        ]);
        $this->set('framework', $framework);
        $this->set('_serialize', ['framework']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $framework = $this->Frameworks->newEntity();
        if ($this->request->is('post')) {
            $framework = $this->Frameworks->patchEntity($framework, $this->request->data);
            if ($this->Frameworks->save($framework)) {
                $this->Flash->success(___('the framework has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the framework could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $applications = $this->Frameworks->Applications->find('list', ['limit' => 200]);
        $this->set(compact('framework', 'applications'));
        $this->set('_serialize', ['framework']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Framework id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $framework = $this->Frameworks->get($id, [
            'contain' => ['Applications']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $framework = $this->Frameworks->patchEntity($framework, $this->request->data);
            if ($this->Frameworks->save($framework)) {
                $this->Flash->success(___('the framework has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the framework could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $applications = $this->Frameworks->Applications->find('list', ['limit' => 200]);
        $this->set(compact('framework', 'applications'));
        $this->set('_serialize', ['framework']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Framework id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $framework = $this->Frameworks->get($id);

        try
        {
            if ($this->Frameworks->delete($framework)) {
                $this->Flash->success(___('the framework has been deleted'), ['plugin' => 'Alaxos']);
            } else {
                $this->Flash->error(___('the framework could not be deleted. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        catch(\Exception $ex)
        {
            if($ex->getCode() == 23000)
            {
                $this->Flash->error(___('the framework could not be deleted as it is still used in the database'), ['plugin' => 'Alaxos']);
            }
            else
            {
                $this->Flash->error(sprintf(__('The framework could not be deleted: %s', $ex->getMessage())), ['plugin' => 'Alaxos']);
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

            $query = $this->Frameworks->query();
            $query->delete()->where(['id IN' => $this->request->data['checked_ids']]);

            try{
                if ($statement = $query->execute()) {
                    $deleted_total = $statement->rowCount();
                    if($deleted_total == 1){
                        $this->Flash->set(___('the selected framework has been deleted.'), ['element' => 'Alaxos.success']);
                    }
                    elseif($deleted_total > 1){
                        $this->Flash->set(sprintf(__('The %s selected frameworks have been deleted.'), $deleted_total), ['element' => 'Alaxos.success']);
                    }
                } else {
                    $this->Flash->set(___('the selected frameworks could not be deleted. Please, try again.'), ['element' => 'Alaxos.error']);
                }
            }
            catch(\Exception $ex){
                $this->Flash->set(___('the selected frameworks could not be deleted. Please, try again.'), ['element' => 'Alaxos.error', 'params' => ['exception_message' => $ex->getMessage()]]);
            }
        } else {
            $this->Flash->set(___('there was no framework to delete'), ['element' => 'Alaxos.error']);
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Copy method
     *
     * @param string|null $id Framework id.
     * @return void Redirects on successful copy, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function copy($id = null)
    {
        $framework = $this->Frameworks->get($id, [
            'contain' => ['Applications']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $framework = $this->Frameworks->newEntity();
            $framework = $this->Frameworks->patchEntity($framework, $this->request->data);
            if ($this->Frameworks->save($framework)) {
                $this->Flash->success(___('the framework has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the framework could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $applications = $this->Frameworks->Applications->find('list', ['limit' => 200]);

        $framework->id = $id;
        $this->set(compact('framework', 'applications'));
        $this->set('_serialize', ['framework']);
    }
}
