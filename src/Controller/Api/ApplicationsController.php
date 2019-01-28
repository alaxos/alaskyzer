<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Alaxos\Lib\StringTool;

/**
 * Applications Controller
 *
 * @property \App\Model\Table\ApplicationsTable $Applications
 * @property \Alaxos\Controller\Component\FilterComponent $Filter
 */
class ApplicationsController extends AppController
{

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
            'sortWhitelist'=>['name', 'Frameworks.name', 'FrameworkVersions.name', 'created', 'modified'],
            'order' => ['name' => 'asc', 'Frameworks.name' => 'asc', 'FrameworkVersions.name' => 'asc']
        ];

        $this->Filter->config('aliases', [
            'Users.fullname' => [
                'expression' => $this->Applications->find()->func()->concat(['Users.firstname' => 'literal', ' ', 'Users.lastname' => 'literal']),
                'columnType' => 'string'
            ]
        ]);

        $query = $this->Filter->getFilterQuery(['auto_wildcard_string' => false]);
        $query->contain(['Users' => ['Roles'], 'ApplicationsFrameworks' => ['Frameworks', 'FrameworkVersions'], 'Technologies', 'Tasks']);

        /*
         * Trick to allow sorting on linked models
         *
         * (Cake 3.0.x does not allow to sort on linked models that are not belongsTo ?)
         */
        $association = $this->Applications->association('ApplicationsFrameworks');
        $this->Filter->addJoin($query, $association, 'LEFT');
        $association = $this->Applications->ApplicationsFrameworks->association('FrameworkVersions');
        $this->Filter->addJoin($query, $association, 'LEFT');
        $association = $this->Applications->ApplicationsFrameworks->association('Frameworks');
        $this->Filter->addJoin($query, $association, 'LEFT');

//         echo $query->__debugInfo()['sql'];

        $applications = $this->paginate($query);

        $applications_data = [];

        foreach($applications as $application)
        {
            $app = [
                'id'   => $application->id,
                'name' => $application->name,
                'total_open_tasks' => count($application->tasks)
            ];

            $applications_data[] = $app;
        }

        $this->autoRender = false;
        $this->setResponse($this->getResponse()->withType('json')->withStringBody(json_encode($applications_data)));
    }

    /**
     * View method
     *
     * @param string|null $id Application id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $application = $this->Applications->get($id, [
            'contain' => ['ApplicationsFrameworks' => ['Frameworks', 'FrameworkVersions'], 'Technologies', 'Instances', 'Tasks']
        ]);
        $this->set('application', $application);
        $this->set('_serialize', ['application']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $application = $this->Applications->newEntity();
        if ($this->getRequest()->is('post')) {

            $data = $this->getRequest()->getData();

            foreach($data['applications_frameworks'] as $index => $application_framework){

                /*
                 * Save framework
                 */
                $framework_id = $application_framework['framework_id'];
                if(StringTool::start_with($framework_id, '[new]')){
                    $framework = $this->Applications->ApplicationsFrameworks->Frameworks->ensureEntityExists($framework_id);

                    $data['applications_frameworks'][$index]['framework_id'] = $framework->id;
                    $this->setRequest($this->getRequest()->withParsedBody($data));
                    $framework_id = $framework->id;
                }

                /*
                 * Save linked new framework version
                 */
                $framework_version_id = $application_framework['framework_version_id'];
                if(StringTool::start_with($framework_version_id, '[new]')){
                    $framework_version = $this->Applications->ApplicationsFrameworks->FrameworkVersions->ensureEntityExists($framework_id, $framework_version_id);

                    $data['applications_frameworks'][$index]['framework_version_id'] = $framework_version->id;
                    $this->setRequest($this->getRequest()->withParsedBody($data));
                    $framework_version_id = $framework_version->id;

                    $this->Applications->ApplicationsFrameworks->FrameworkVersions->updateNaturalSortValues($framework_id);
                }
            }

            if(is_array($data['technologies']['_ids']))
            {
                foreach($data['technologies']['_ids'] as $index => $technology_id){
                    if(StringTool::start_with($technology_id, '[new]')){
                        $technology = $this->Applications->Technologies->ensureEntityExists($technology_id);

                        $data['technologies']['_ids'][$index] = $technology->id;
                        $this->setRequest($this->getRequest()->withParsedBody($data));
                    }
                }
            }

            $application = $this->Applications->patchEntity($application, $this->getRequest()->getData());
            if ($this->Applications->save($application)) {
                $this->Flash->success(___('the application has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'view', $application->id]);
            } else {
                $this->Flash->error(___('the application could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $frameworks = $this->Applications->ApplicationsFrameworks->Frameworks->find('list', ['limit' => 200])->order(['name' => 'asc']);
        $technologies = $this->Applications->Technologies->find('list', ['limit' => 200]);
        $this->set(compact('application', 'frameworks', 'frameworkVersions', 'technologies'));
        $this->set('_serialize', ['application']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Application id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
//         debug($this->getRequest()->getData());

        $application = $this->Applications->get($id, [
            'contain' => ['ApplicationsFrameworks' => ['Frameworks', 'FrameworkVersions'], 'Technologies']
        ]);

        if ($this->getRequest()->is(['patch', 'post', 'put'])) {

            $data = $this->getRequest()->getData();

            foreach($data['applications_frameworks'] as $index => $application_framework){

                /*
                 * Save framework
                 */
                $framework_id = $application_framework['framework_id'];
                if(StringTool::start_with($framework_id, '[new]')){
                    $framework = $this->Applications->ApplicationsFrameworks->Frameworks->ensureEntityExists($framework_id);

                    $data['applications_frameworks'][$index]['framework_id'] = $framework->id;
                    $this->setRequest($this->getRequest()->withParsedBody($data));
                    $framework_id = $framework->id;
                }

                /*
                 * Save linked new framework version
                 */
                $framework_version_id = $application_framework['framework_version_id'];

                if(StringTool::start_with($framework_version_id, '[new]')){
                    $framework_version = $this->Applications->ApplicationsFrameworks->FrameworkVersions->ensureEntityExists($framework_id, $framework_version_id);

                    $data['applications_frameworks'][$index]['framework_version_id'] = $framework_version->id;
                    $this->setRequest($this->getRequest()->withParsedBody($data));
                    $framework_version_id = $framework_version->id;

                    $this->Applications->ApplicationsFrameworks->FrameworkVersions->updateNaturalSortValues($framework_id);
                }
            }

            if(is_array($data['technologies']['_ids']))
            {
                foreach($data['technologies']['_ids'] as $index => $technology_id){
                    if(StringTool::start_with($technology_id, '[new]')){
                        $technology = $this->Applications->Technologies->ensureEntityExists($technology_id);

                        $data['technologies']['_ids'][$index] = $technology->id;
                        $this->setRequest($this->getRequest()->withParsedBody($data));
                    }
                }
            }

            $application = $this->Applications->patchEntity($application, $this->getRequest()->getData());
            if ($this->Applications->save($application)) {
                $this->Flash->success(___('the application has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the application could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }

        $frameworks = $this->Applications->ApplicationsFrameworks->Frameworks->find('list', ['limit' => 200])->order(['name' => 'asc']);
        $frameworkVersions = $this->Applications->ApplicationsFrameworks->FrameworkVersions->find('list')->where(['framework_id' => $application->applications_frameworks[0]->framework->id])->order(['sort' => 'asc']);
        $technologies = $this->Applications->Technologies->find('list', ['limit' => 200]);
        $this->set(compact('application', 'frameworks', 'frameworkVersions', 'technologies'));
        $this->set('_serialize', ['application']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Application id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $application = $this->Applications->get($id);

        try
        {
            if ($this->Applications->delete($application)) {
                $this->Flash->success(___('the application has been deleted'), ['plugin' => 'Alaxos']);
            } else {
                $this->Flash->error(___('the application could not be deleted. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        catch(\Exception $ex)
        {
            if($ex->getCode() == 23000)
            {
                $this->Flash->error(___('the application could not be deleted as it is still used in the database'), ['plugin' => 'Alaxos']);
            }
            else
            {
                $this->Flash->error(sprintf(__('The application could not be deleted: %s', $ex->getMessage())), ['plugin' => 'Alaxos']);
            }
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete all method
     */
    public function delete_all() {
        $this->getRequest()->allowMethod('post', 'delete');

        $checkedIds = $this->getRequest()->getData('checked_ids');
        if(!empty($checkedIds)) {

            $checkedIds = $this->getRequest()->getData('checked_ids');
            $entities = $this->Applications->find()->where(['id IN' => $checkedIds]);

            $deleted_total     = 0;
            $not_deleted_total = 0;
            foreach($entities as $entity)
            {
                if($this->Applications->delete($entity)){
                    $deleted_total++;
                }
                else{
                    $not_deleted_total++;
                }
            }

            if($not_deleted_total === 0)
            {
                if($deleted_total == 1){
                    $this->Flash->set(___('the selected application has been deleted.'), ['element' => 'Alaxos.success']);
                }
                elseif($deleted_total > 1){
                    $this->Flash->set(sprintf(__('The %s selected applications have been deleted.'), $deleted_total), ['element' => 'Alaxos.success']);
                }
            }
            else
            {
                $this->Flash->set(sprintf(___('%s selected applications could not be deleted. Please, try again.'), $not_deleted_total), ['element' => 'Alaxos.error']);
            }

        } else {
            $this->Flash->set(___('there was no application to delete'), ['element' => 'Alaxos.error']);
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Copy method
     *
     * @param string|null $id Application id.
     * @return void Redirects on successful copy, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function copy($id = null)
    {
        $application = $this->Applications->get($id, [
            'contain' => ['ApplicationsFrameworks' => ['Frameworks', 'FrameworkVersions'], 'Technologies']
        ]);

        if ($this->getRequest()->is(['patch', 'post', 'put'])) {

            $data = $this->getRequest()->getData();

            $application_frameworks = $this->getRequest()->getData('applications_frameworks');
            foreach($application_frameworks as $index => $application_framework){

                /*
                 * Save framework
                 */
                $framework_id = $application_framework['framework_id'];
                if(StringTool::start_with($framework_id, '[new]')){
                    $framework = $this->Applications->ApplicationsFrameworks->Frameworks->ensureEntityExists($framework_id);

                    $data['applications_frameworks'][$index]['framework_id'] = $framework->id;
                    $this->setRequest($this->getRequest()->withParsedBody($data));
                    $framework_id = $framework->id;
                }

                /*
                 * Save linked new framework version
                 */
                $framework_version_id = $application_framework['framework_version_id'];

                if(StringTool::start_with($framework_version_id, '[new]')){
                    $framework_version = $this->Applications->ApplicationsFrameworks->FrameworkVersions->ensureEntityExists($framework_id, $framework_version_id);

                    $data['applications_frameworks'][$index]['framework_version_id'] = $framework_version->id;
                    $this->setRequest($this->getRequest()->withParsedBody($data));
                    $framework_version_id = $framework_version->id;

                    $this->Applications->ApplicationsFrameworks->FrameworkVersions->updateNaturalSortValues($framework_id);
                }
            }

            if(is_array($data['technologies']['_ids']))
            {
                foreach($data['technologies']['_ids'] as $index => $technology_id){
                    if(StringTool::start_with($technology_id, '[new]')){
                        $technology = $this->Applications->Technologies->ensureEntityExists($technology_id);

                        $data['technologies']['_ids'][$index] = $technology->id;
                        $this->setRequest($this->getRequest()->withParsedBody($data));
                    }
                }
            }
            $application = $this->Applications->newEntity();
            $application = $this->Applications->patchEntity($application, $this->getRequest()->getData());
            if ($this->Applications->save($application)) {
                $this->Flash->success(___('the application has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the application could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }

        $frameworks = $this->Applications->ApplicationsFrameworks->Frameworks->find('list', ['limit' => 200])->order(['name' => 'asc']);
        $frameworkVersions = $this->Applications->ApplicationsFrameworks->FrameworkVersions->find('list')->where(['framework_id' => $application->applications_frameworks[0]->framework->id])->order(['sort' => 'asc']);
        $technologies = $this->Applications->Technologies->find('list', ['limit' => 200]);
        $this->set(compact('application', 'frameworks', 'frameworkVersions', 'technologies'));
        $this->set('_serialize', ['application']);
    }

    public function getList()
    {
        $this->setResponse($this->getResponse()->withType('json'));

        $applications = $this->Applications->find()->contain(['Tasks'])->order(['name']);

        $this->set(compact('applications'));
    }
}
