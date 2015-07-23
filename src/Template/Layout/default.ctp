<?php
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
    <?= $this->Html->meta('icon') ?>

    <?php
	echo $this->AlaxosHtml->includeBootstrapCSS(['block' => false]);
	echo $this->AlaxosHtml->includeBootstrapThemeCSS(['block' => false]);
	echo $this->AlaxosHtml->includeAlaxosCSS(['block' => false]);
	echo $this->AlaxosHtml->css('alaskyzer');
	
	echo $this->AlaxosHtml->includeAlaxosJQuery(['block' => false]);
	echo $this->AlaxosHtml->includeAlaxosBootstrapJS(['block' => false]);
	?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <div id="container" class="container">
        
        <div class="row">
            <div class="col-md-12">
                <header>
                    <div class="row">
                        <div class="col-md-3 col-sm-4">
                            <?php 
                            echo '<div id="top_unige_logo">';
                            //echo $this->Html->image('unige.png');
                            echo 'Alaskyzer';
                            echo '</div>';
                            ?>
                        </div>
                        <div class="col-md-6 col-sm-4">
                            <?php
                            echo '<h1>'; 
                            echo $this->Html->link('Alaskyzer', '/');
                            echo '</h1>';
                            ?>
                        </div>
                        <div class="col-md-3 col-sm-4 text-right">
                            <?php 
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
