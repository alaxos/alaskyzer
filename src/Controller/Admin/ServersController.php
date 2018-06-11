<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Servers Controller
 *
 * @property \App\Model\Table\ServersTable $Servers
 * @property \Alaxos\Controller\Component\FilterComponent $Filter
 */
class ServersController extends AppController
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
        $this->set('servers', $this->paginate($this->Filter->getFilterQuery()));
        $this->set('_serialize', ['servers']);
    }

    /**
     * View method
     *
     * @param string|null $id Server id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $server = $this->Servers->get($id, [
            'contain' => ['Instances', 'Tasks']
        ]);
        $this->set('server', $server);
        $this->set('_serialize', ['server']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $server = $this->Servers->newEntity();
        if ($this->request->is('post')) {
            $server = $this->Servers->patchEntity($server, $this->request->data);
            if ($this->Servers->save($server)) {
                $this->Flash->success(___('the server has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the server could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $this->set(compact('server'));
        $this->set('_serialize', ['server']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Server id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $server = $this->Servers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $server = $this->Servers->patchEntity($server, $this->request->data);
            if ($this->Servers->save($server)) {
                $this->Flash->success(___('the server has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the server could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $this->set(compact('server'));
        $this->set('_serialize', ['server']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Server id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $server = $this->Servers->get($id);

        try
        {
            if ($this->Servers->delete($server)) {
                $this->Flash->success(___('the server has been deleted'), ['plugin' => 'Alaxos']);
            } else {
                $this->Flash->error(___('the server could not be deleted. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        catch(\Exception $ex)
        {
            if($ex->getCode() == 23000)
            {
                $this->Flash->error(___('the server could not be deleted as it is still used in the database'), ['plugin' => 'Alaxos']);
            }
            else
            {
                $this->Flash->error(sprintf(__('The server could not be deleted: %s', $ex->getMessage())), ['plugin' => 'Alaxos']);
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

            $query = $this->Servers->query();
            $query->delete()->where(['id IN' => $this->request->data['checked_ids']]);

            try{
                if ($statement = $query->execute()) {
                    $deleted_total = $statement->rowCount();
                    if($deleted_total == 1){
                        $this->Flash->set(___('the selected server has been deleted.'), ['element' => 'Alaxos.success']);
                    }
                    elseif($deleted_total > 1){
                        $this->Flash->set(sprintf(__('The %s selected servers have been deleted.'), $deleted_total), ['element' => 'Alaxos.success']);
                    }
                } else {
                    $this->Flash->set(___('the selected servers could not be deleted. Please, try again.'), ['element' => 'Alaxos.error']);
                }
            }
            catch(\Exception $ex){
                $this->Flash->set(___('the selected servers could not be deleted. Please, try again.'), ['element' => 'Alaxos.error', 'params' => ['exception_message' => $ex->getMessage()]]);
            }
        } else {
            $this->Flash->set(___('there was no server to delete'), ['element' => 'Alaxos.error']);
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Copy method
     *
     * @param string|null $id Server id.
     * @return void Redirects on successful copy, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function copy($id = null)
    {
        $server = $this->Servers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $server = $this->Servers->newEntity();
            $server = $this->Servers->patchEntity($server, $this->request->data);
            if ($this->Servers->save($server)) {
                $this->Flash->success(___('the server has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the server could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }

        $server->id = $id;
        $this->set(compact('server'));
        $this->set('_serialize', ['server']);
    }
}
