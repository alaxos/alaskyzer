
<div class="row" id="flashSuccess" style="cursor:pointer;">
    <div class="col-md-6 col-md-offset-3">
        <p class="flash-message panel panel-default bg-success">
        <?php 
        echo $this->Html->image('close.png', ['style' => 'float:right;margin-top:-12px;margin-right:-12px;']);
        ?>
        <?= h($message) ?>
        </p>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){

// 	setTimeout(function(){
// 		$("#flashSuccess").hide(400);
// 	}, 4000);

	$("#flashSuccess").click(function(e){
		e.preventDefault();
		$(this).hide(200);
	});
});
</script>
