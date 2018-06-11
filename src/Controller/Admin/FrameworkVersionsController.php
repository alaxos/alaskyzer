<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Http\Exception\NotFoundException;

/**
 * FrameworkVersions Controller
 *
 * @property \App\Model\Table\FrameworkVersionsTable $FrameworkVersions
 * @property \Alaxos\Controller\Component\FilterComponent $Filter
 */
class FrameworkVersionsController extends AppController
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
            'contain' => ['Frameworks']
        ];
        $this->set('frameworkVersions', $this->paginate($this->Filter->getFilterQuery()));
        $this->set('_serialize', ['frameworkVersions']);

        $frameworks = $this->FrameworkVersions->Frameworks->find('list', ['limit' => 200]);
        $this->set(compact('frameworks'));
    }

    /**
     * View method
     *
     * @param string|null $id Framework Version id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $frameworkVersion = $this->FrameworkVersions->get($id, [
            'contain' => ['Frameworks']
        ]);
        $this->set('frameworkVersion', $frameworkVersion);
        $this->set('_serialize', ['frameworkVersion']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $frameworkVersion = $this->FrameworkVersions->newEntity();
        if ($this->request->is('post')) {
            $frameworkVersion = $this->FrameworkVersions->patchEntity($frameworkVersion, $this->request->data);
            if ($this->FrameworkVersions->save($frameworkVersion)) {
                $this->Flash->success(___('the framework version has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the framework version could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $frameworks = $this->FrameworkVersions->Frameworks->find('list', ['limit' => 200]);
        $this->set(compact('frameworkVersion', 'frameworks'));
        $this->set('_serialize', ['frameworkVersion']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Framework Version id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $frameworkVersion = $this->FrameworkVersions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $frameworkVersion = $this->FrameworkVersions->patchEntity($frameworkVersion, $this->request->data);
            if ($this->FrameworkVersions->save($frameworkVersion)) {
                $this->Flash->success(___('the framework version has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the framework version could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $frameworks = $this->FrameworkVersions->Frameworks->find('list', ['limit' => 200]);
        $this->set(compact('frameworkVersion', 'frameworks'));
        $this->set('_serialize', ['frameworkVersion']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Framework Version id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $frameworkVersion = $this->FrameworkVersions->get($id);

        try
        {
            if ($this->FrameworkVersions->delete($frameworkVersion)) {
                $this->Flash->success(___('the framework version has been deleted'), ['plugin' => 'Alaxos']);
            } else {
                $this->Flash->error(___('the framework version could not be deleted. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        catch(\Exception $ex)
        {
            if($ex->getCode() == 23000)
            {
                $this->Flash->error(___('the framework version could not be deleted as it is still used in the database'), ['plugin' => 'Alaxos']);
            }
            else
            {
                $this->Flash->error(sprintf(__('The framework version could not be deleted: %s', $ex->getMessage())), ['plugin' => 'Alaxos']);
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

            $query = $this->FrameworkVersions->query();
            $query->delete()->where(['id IN' => $this->request->data['checked_ids']]);

            try{
                if ($statement = $query->execute()) {
                    $deleted_total = $statement->rowCount();
                    if($deleted_total == 1){
                        $this->Flash->set(___('the selected framework version has been deleted.'), ['element' => 'Alaxos.success']);
                    }
                    elseif($deleted_total > 1){
                        $this->Flash->set(sprintf(__('The %s selected frameworkversions have been deleted.'), $deleted_total), ['element' => 'Alaxos.success']);
                    }
                } else {
                    $this->Flash->set(___('the selected frameworkversions could not be deleted. Please, try again.'), ['element' => 'Alaxos.error']);
                }
            }
            catch(\Exception $ex){
                $this->Flash->set(___('the selected frameworkversions could not be deleted. Please, try again.'), ['element' => 'Alaxos.error', 'params' => ['exception_message' => $ex->getMessage()]]);
            }
        } else {
            $this->Flash->set(___('there was no framework version to delete'), ['element' => 'Alaxos.error']);
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Copy method
     *
     * @param string|null $id Framework Version id.
     * @return void Redirects on successful copy, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function copy($id = null)
    {
        $frameworkVersion = $this->FrameworkVersions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $frameworkVersion = $this->FrameworkVersions->newEntity();
            $frameworkVersion = $this->FrameworkVersions->patchEntity($frameworkVersion, $this->request->data);
            if ($this->FrameworkVersions->save($frameworkVersion)) {
                $this->Flash->success(___('the framework version has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the framework version could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $frameworks = $this->FrameworkVersions->Frameworks->find('list', ['limit' => 200]);

        $frameworkVersion->id = $id;
        $this->set(compact('frameworkVersion', 'frameworks'));
        $this->set('_serialize', ['frameworkVersion']);
    }

    public function get_framework_versions()
    {
        $this->autoRender = false;

        $framework_id = $this->request->query('framework_id');

        if(is_numeric($framework_id) && $this->FrameworkVersions->Frameworks->exists($framework_id))
        {
            $frameworkVersions = $this->FrameworkVersions->find()->where(['framework_id' => $framework_id])->order(['sort' => 'asc']);

            $this->response->type('json');
            $this->response->body(json_encode($frameworkVersions));
        }
        else
        {
            throw new NotFoundException();
        }
    }
}
