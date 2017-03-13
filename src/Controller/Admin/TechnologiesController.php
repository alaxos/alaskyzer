<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Technologies Controller
 *
 * @property \App\Model\Table\TechnologiesTable $Technologies
 * @property \Alaxos\Controller\Component\FilterComponent $Filter
 */
class TechnologiesController extends AppController
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
        $this->set('technologies', $this->paginate($this->Filter->getFilterQuery()));
        $this->set('_serialize', ['technologies']);
    }

    /**
     * View method
     *
     * @param string|null $id Technology id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $technology = $this->Technologies->get($id, [
            'contain' => ['Applications']
        ]);
        $this->set('technology', $technology);
        $this->set('_serialize', ['technology']);
    }

    /**
     * Add method
     *
     * @return void|\Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $technology = $this->Technologies->newEntity();
        if ($this->request->is('post')) {
            $technology = $this->Technologies->patchEntity($technology, $this->request->data);
            if ($this->Technologies->save($technology)) {
                $this->Flash->success(___('the technology has been saved'), ['plugin' => 'Alaxos']);

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the technology could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $applications = $this->Technologies->Applications->find('list', ['limit' => 200]);
        $this->set(compact('technology', 'applications'));
        $this->set('_serialize', ['technology']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Technology id.
     * @return void|\Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $technology = $this->Technologies->get($id, [
            'contain' => ['Applications']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $technology = $this->Technologies->patchEntity($technology, $this->request->data);
            if ($this->Technologies->save($technology)) {
                $this->Flash->success(___('the technology has been saved'), ['plugin' => 'Alaxos']);

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the technology could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $applications = $this->Technologies->Applications->find('list', ['limit' => 200]);
        $this->set(compact('technology', 'applications'));
        $this->set('_serialize', ['technology']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Technology id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $technology = $this->Technologies->get($id);

        try {
            if ($this->Technologies->delete($technology)) {
                $this->Flash->success(___('the technology has been deleted'), ['plugin' => 'Alaxos']);
            } else {
                $this->Flash->error(___('the technology could not be deleted. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        } catch (\Exception $ex) {
            if ($ex->getCode() == 23000) {
                $this->Flash->error(___('the technology could not be deleted as it is still used in the database'), ['plugin' => 'Alaxos']);
            } else {
                $this->Flash->error(sprintf(__('The technology could not be deleted: %s', $ex->getMessage())), ['plugin' => 'Alaxos']);
            }
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete all method
     */
    public function delete_all()
    {
        $this->request->allowMethod('post', 'delete');

        if (isset($this->request->data['checked_ids']) && !empty($this->request->data['checked_ids'])) {

            $query = $this->Technologies->query();
            $query->delete()->where(['id IN' => $this->request->data['checked_ids']]);

            try {
                if ($statement = $query->execute()) {
                    $deletedTotal = $statement->rowCount();
                    if ($deletedTotal == 1) {
                        $this->Flash->set(___('the selected technology has been deleted.'), ['element' => 'Alaxos.success']);
                    } elseif ($deletedTotal > 1) {
                        $this->Flash->set(sprintf(__('The %s selected technologies have been deleted.'), $deletedTotal), ['element' => 'Alaxos.success']);
                    }
                } else {
                    $this->Flash->set(___('the selected technologies could not be deleted. Please, try again.'), ['element' => 'Alaxos.error']);
                }
            } catch (\Exception $ex) {
                $this->Flash->set(___('the selected technologies could not be deleted. Please, try again.'), ['element' => 'Alaxos.error', 'params' => ['exception_message' => $ex->getMessage()]]);
            }
        } else {
            $this->Flash->set(___('there was no technology to delete'), ['element' => 'Alaxos.error']);
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Copy method
     *
     * @param string|null $id Technology id.
     * @return void|\Cake\Http\Response|null Redirects on successful copy, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function copy($id = null)
    {
        $technology = $this->Technologies->get($id, [
            'contain' => ['Applications']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $technology = $this->Technologies->newEntity();
            $technology = $this->Technologies->patchEntity($technology, $this->request->data);
            if ($this->Technologies->save($technology)) {
                $this->Flash->success(___('the technology has been saved'), ['plugin' => 'Alaxos']);

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the technology could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $applications = $this->Technologies->Applications->find('list', ['limit' => 200]);

        $technology->id = $id;
        $this->set(compact('technology', 'applications'));
        $this->set('_serialize', ['technology']);
    }
}
