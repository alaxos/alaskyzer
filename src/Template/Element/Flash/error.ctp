<div class="row" id="flashError">
    <div class="col-md-6 col-md-offset-3">
        <?php 
        echo $this->Html->image('close.png', ['style' => 'float:right;margin-top:-12px;margin-right:-12px;']);
        ?>
        <p class="flash-message panel panel-default bg-danger">
        <?= h($message) ?>
        </p>
        <?php 
        if(isset($params['exception_message']))
        {
            echo '<p class="flash-message panel panel-default bg-danger"><?= h($message) ?>';
            echo h($params['exception_message']);
            echo '</p>';
        }
        ?>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){

// 	setTimeout(function(){
// 		$("#flashSuccess").hide(400);
// 	}, 4000);

	$("#flashError").click(function(e){
		e.preventDefault();
		$(this).hide(200);
	});
});
</script>