<?php
use Cake\Routing\Router;
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon', 'img/alaskyzer.png') ?>

    <?php
	echo $this->AlaxosHtml->includeBootstrapCSS(['block' => false]);
	echo $this->AlaxosHtml->includeBootstrapThemeCSS(['block' => false]);
	echo $this->AlaxosHtml->includeAlaxosCSS(['block' => false]);
	echo $this->AlaxosHtml->css('alaskyzer');
	
	echo $this->AlaxosHtml->includeAlaxosJQuery(['block' => false]);
	echo $this->AlaxosHtml->includeAlaxosBootstrapJS(['block' => false]);
	echo $this->AlaxosHtml->script('jquery.hotkeys');
	?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <div id="container" class="container-fluid">
        
        <div class="row">
            <div class="col-md-12">
                <header>
                    <div class="row">
                        <div class="col-md-1 col-sm-2 col-xs-3">
                            <?php 
                            echo '<div id="top_unige_logo">';
                            echo $this->Html->image('alaskyzer.png', ['style' => 'height:75px;vertical-align:middle;']);
                            echo '</div>';
                            ?>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-5">
                            <?php
                            echo '<h1>'; 
                            echo $this->Html->link('Alaskyzer', '/');
                            echo '</h1>';
                            ?>
                        </div>
                        <div class="col-md-5 col-sm-4 col-xs-4 text-right">
                            <?php 
                            echo $this->Html->link(__('<u>t</u>asks list'), ['prefix' => 'admin', 'controller' => 'Tasks', 'action' => 'index'], ['escape' => false]);
                            echo ' | ';
                            echo $this->Html->link(__('<u>n</u>ew task'), ['prefix' => 'admin', 'controller' => 'Tasks', 'action' => 'add'], ['escape' => false]);
                            echo ' | ';
                            echo $this->Html->link(__('<u>a</u>pplications list'), ['prefix' => 'admin', 'controller' => 'Applications', 'action' => 'index'], ['escape' => false]);
                            ?>
                        </div>
                    </div>
                </header>
            </div>
        </div>
        
        <?php 
        echo $this->element('menus/top');
        ?>
        
        <div id="content" class="row">
            <div class="col-md-12">
            
                <?= $this->Flash->render() ?>
                <?= $this->Flash->render('auth') ?>
                
                <?= $this->fetch('content') ?>
                
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <footer id="footer">
                    
                    <div class="row">
                        <div class="col-md-9">
                        <?php
//                         echo $this->element('horizontal_external_links'); 
                        ?>
                        </div>
                        
                        <div class="col-md-3 text-right">
                        <?php
                        echo '<div style="padding-top:10px">';
                        echo __('University of Geneva');
                        echo '</div>';
                        ?>
                        </div>
                    </div>
                    
                </footer>
            </div>
        </div>
        
    </div>
</body>
</html>

<script type="text/javascript">
$(document).ready(function(){

	var tasksList = function(){
        window.location = "<?php echo Router::url(['prefix' => 'admin', 'controller' => 'Tasks', 'action' => 'index']); ?>";
        return false;
    }
    
    var newTask = function(){
        window.location = "<?php echo Router::url(['prefix' => 'admin', 'controller' => 'Tasks', 'action' => 'add']); ?>";
        return false;
    }

    var applicationsList = function(){
        window.location = "<?php echo Router::url(['prefix' => 'admin', 'controller' => 'Applications', 'action' => 'index']); ?>";
        return false;
    }
    
    $(document).on('keydown', null, 'ctrl+t', tasksList);
    $(document).on('keydown', null, 'ctrl+n', newTask);
    $(document).on('keydown', null, 'ctrl+a', applicationsList);
    
});
</script>