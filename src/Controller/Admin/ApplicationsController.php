<?php
namespace App\Controller\Admin;

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
        $this->Filter->config('aliases', [
            'Users.fullname' => [
                'expression' => $this->Applications->find()->func()->concat(['Users.firstname' => 'literal', ' ', 'Users.lastname' => 'literal']),
                'columnType' => 'string'
            ]
        ]);
        
        $query = $this->Filter->getFilterQuery();
        $query->contain(['Users' => ['Roles'], 'ApplicationsFrameworks' => ['Frameworks', 'FrameworkVersions']]);
        
//         $query = $this->Applications->find();
//         $query->contain(['Users', 'Frameworks', 'FrameworkVersions']);
//         $query->where(function($exp, $query) {
//             $f =  $query->func()->concat(['Users.firstname' => 'literal', ' ', 'Users.lastname' => 'literal']);
//             return $exp->like($f, '%Alex%');
//         });
        
        $this->set('applications', $this->paginate($query));
        $this->set('_serialize', ['applications']);
    }

    /**
     * View method
     *
     * @param string|null $id Application id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $application = $this->Applications->get($id, [
            'contain' => ['ApplicationsFrameworks' => ['Frameworks', 'FrameworkVersions'], 'Technologies', 'Bugs', 'Instances', 'Tasks']
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
        if ($this->request->is('post')) {
            
//             debug($this->request->data);
//             die();
            
            foreach($this->request->data['frameworks'] as $index => $framework_data){
                
                /*
                 * Save framework
                 */
                $framework_id = $framework_data['id'];
                if(StringTool::start_with($framework_id, '[new]')){
                    $framework = $this->Applications->Frameworks->newEntity(['name' => StringTool::remove_leading($framework_id, '[new]')]);
                    if($this->Applications->Frameworks->save($framework)){
                        $this->request->data['frameworks'][$index]['id'] = $framework->id;
                        $framework_id = $framework->id;
                    }
                }
                
                /*
                 * Save linked new framework version
                 */
                if(isset($framework_data['_joinData']['framework_version_id'])){
                    $framework_version_id = $framework_data['_joinData']['framework_version_id'];
                    if(StringTool::start_with($framework_version_id, '[new]')){
                        $frameworkVersion = $this->Applications->Frameworks->FrameworkVersions->newEntity(['framework_id' => $framework_id, 'name' => StringTool::remove_leading($framework_version_id, '[new]')]);
                        if($this->Applications->Frameworks->FrameworkVersions->save($frameworkVersion)){
                            $this->request->data['frameworks'][$index]['_joinData']['framework_version_id'] = $frameworkVersion->id;
                            $framework_version_id = $frameworkVersion->id;
                        }
                    }
                }
            }
            
            if(is_array($this->request->data['technologies']['_ids']))
            {
                foreach($this->request->data['technologies']['_ids'] as $index => $technology_id){
                    if(StringTool::start_with($technology_id, '[new]')){
                        $technology = $this->Applications->Technologies->newEntity(['name' => StringTool::remove_leading($technology_id, '[new]')]);
                        if($this->Applications->Technologies->save($technology)){
                            $this->request->data['technologies']['_ids'][$index] = $technology->id;
                        }
                    }
                }
            }
            
//             debug($this->request->data);
//             die();
            
            $application = $this->Applications->patchEntity($application, $this->request->data);
            if ($this->Applications->save($application)) {
                $this->Flash->success(___('the application has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'view', $application->id]);
            } else {
                $this->Flash->error(___('the application could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $frameworks = $this->Applications->Frameworks->find('list', ['limit' => 200]);
//         $frameworkVersions = $this->Applications->Frameworks->FrameworkVersions->find('list', ['limit' => 200]);
        $technologies = $this->Applications->Technologies->find('list', ['limit' => 200]);
        $this->set(compact('application', 'frameworks', 'frameworkVersions', 'technologies'));
        $this->set('_serialize', ['application']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Application id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
//         debug($this->request->data);
        
        $application = $this->Applications->get($id, [
            'contain' => ['ApplicationsFrameworks' => ['Frameworks', 'FrameworkVersions'], 'Technologies']
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            
//             debug($this->request->data);
//             die();
            
            foreach($this->request->data['applications_frameworks'] as $index => $application_framework){
                
                /*
                 * Save framework
                 */
                $framework_id = $application_framework['framework_id'];
                if(StringTool::start_with($framework_id, '[new]')){
                    $framework = $this->Applications->ApplicationsFrameworks->Frameworks->newEntity(['name' => StringTool::remove_leading($framework_id, '[new]')]);
                    if($this->Applications->ApplicationsFrameworks->Frameworks->save($framework)){
                        $this->request->data['applications_frameworks'][$index]['framework_id'] = $framework->id;
                        $framework_id = $framework->id;
                    }
                }
                
                /*
                 * Save linked new framework version
                 */
                $framework_version_id = $application_framework['framework_version_id'];
                if(StringTool::start_with($framework_version_id, '[new]')){
                    $framework_version = $this->Applications->ApplicationsFrameworks->FrameworkVersions->newEntity(['name' => StringTool::remove_leading($framework_version_id, '[new]')]);
                    if($this->Applications->ApplicationsFrameworks->FrameworkVersions->save($framework_version)){
                        $this->request->data['applications_frameworks'][$index]['framework_version_id'] = $framework_version->id;
                        $framework_version_id = $framework_version->id;
                    }
                }
            }
            
//             foreach($this->request->data['frameworks'] as $index => $framework_data){
                
//                 /*
//                  * Save framework
//                  */
//                 $framework_id = $framework_data['id'];
//                 if(StringTool::start_with($framework_id, '[new]')){
//                     $framework = $this->Applications->Frameworks->newEntity(['name' => StringTool::remove_leading($framework_id, '[new]')]);
//                     if($this->Applications->Frameworks->save($framework)){
//                         $this->request->data['frameworks'][$index]['id'] = $framework->id;
//                         $framework_id = $framework->id;
//                     }
//                 }
                
//                 /*
//                  * Save linked new framework version
//                  */
//                 if(isset($framework_data['_joinData']['framework_version_id'])){
//                     $framework_version_id = $framework_data['_joinData']['framework_version_id'];
//                     if(StringTool::start_with($framework_version_id, '[new]')){
//                         $frameworkVersion = $this->Applications->Frameworks->FrameworkVersions->newEntity(['framework_id' => $framework_id, 'name' => StringTool::remove_leading($framework_version_id, '[new]')]);
//                         if($this->Applications->Frameworks->FrameworkVersions->save($frameworkVersion)){
//                             $this->request->data['frameworks'][$index]['_joinData']['framework_version_id'] = $frameworkVersion->id;
//                             $framework_version_id = $frameworkVersion->id;
//                         }
//                     }
//                 }
//             }
            
            if(is_array($this->request->data['technologies']['_ids']))
            {
                foreach($this->request->data['technologies']['_ids'] as $index => $technology_id){
                    if(StringTool::start_with($technology_id, '[new]')){
                        $technology = $this->Applications->Technologies->newEntity(['name' => StringTool::remove_leading($technology_id, '[new]')]);
                        if($this->Applications->Technologies->save($technology)){
                            $this->request->data['technologies']['_ids'][$index] = $technology->id;
                        }
                    }
                }
            }
            
            $application = $this->Applications->patchEntity($application, $this->request->data);
            if ($this->Applications->save($application)) {
                $this->Flash->success(___('the application has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the application could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        
        $frameworks = $this->Applications->ApplicationsFrameworks->Frameworks->find('list', ['limit' => 200]);
        $frameworkVersions = $this->Applications->ApplicationsFrameworks->FrameworkVersions->find('list')->where(['framework_id' => $application->applications_frameworks[0]->framework->id]);
        $technologies = $this->Applications->Technologies->find('list', ['limit' => 200]);
        $this->set(compact('application', 'frameworks', 'frameworkVersions', 'technologies'));
        $this->set('_serialize', ['application']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Application id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
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
        $this->request->allowMethod('post', 'delete');
        
        if(isset($this->request->data['checked_ids']) && !empty($this->request->data['checked_ids'])){
            
            $query = $this->Applications->query();
            $query->delete()->where(['id IN' => $this->request->data['checked_ids']]);
            
            try{
                if ($statement = $query->execute()) {
                    $deleted_total = $statement->rowCount();
                    if($deleted_total == 1){
                        $this->Flash->set(___('the selected application has been deleted.'), ['element' => 'Alaxos.success']);
                    }
                    elseif($deleted_total > 1){
                        $this->Flash->set(sprintf(__('The %s selected applications have been deleted.'), $deleted_total), ['element' => 'Alaxos.success']);
                    }
                } else {
                    $this->Flash->set(___('the selected applications could not be deleted. Please, try again.'), ['element' => 'Alaxos.error']);
                }
            }
            catch(\Exception $ex){
                $this->Flash->set(___('the selected applications could not be deleted. Please, try again.'), ['element' => 'Alaxos.error', 'params' => ['exception_message' => $ex->getMessage()]]);
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
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function copy($id = null)
    {
        
        $application = $this->Applications->get($id, [
            'contain' => ['Frameworks', 'Technologies']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            
            /*
             * Save eventual new technologies or frameworks
             */
            foreach($this->request->data['frameworks']['_ids'] as $index => $framework_id){
                if(StringTool::start_with($framework_id, '[new]')){
                    $framework = $this->Applications->Frameworks->newEntity(['name' => StringTool::remove_leading($framework_id, '[new]')]);
                    if($this->Applications->Frameworks->save($framework)){
                        $this->request->data['frameworks']['_ids'][$index] = $framework->id;
                    }
                }
            }
            
            foreach($this->request->data['technologies']['_ids'] as $index => $technology_id){
                if(StringTool::start_with($technology_id, '[new]')){
                    $technology = $this->Applications->Technologies->newEntity(['name' => StringTool::remove_leading($technology_id, '[new]')]);
                    if($this->Applications->Technologies->save($technology)){
                        $this->request->data['technologies']['_ids'][$index] = $technology->id;
                    }
                }
            }
            
            $application = $this->Applications->newEntity();
            $application = $this->Applications->patchEntity($application, $this->request->data);
            if ($this->Applications->save($application)) {
                $this->Flash->success(___('the application has been saved'), ['plugin' => 'Alaxos']);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(___('the application could not be saved. Please, try again.'), ['plugin' => 'Alaxos']);
            }
        }
        $frameworks = $this->Applications->Frameworks->find('list', ['limit' => 200]);
        $technologies = $this->Applications->Technologies->find('list', ['limit' => 200]);
        
        $application->id = $id;
        $this->set(compact('application', 'frameworks', 'technologies'));
        $this->set('_serialize', ['application']);
    }
}
