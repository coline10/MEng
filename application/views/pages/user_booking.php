<?php

$classname = array(
	'name'	=> 'classname',
	'id'	=> 'classname',
	'value' => 'classname',
	'maxlength'	=> 20,
	'size'	=> 20,
	'class' => 'form-control',
	'placeholder' => 'Class Name',

	);

$date = array(
	'name'	=> 'date',
	'id'	=> 'date',
	'value' => set_value('date'),
	'maxlength'	=> 20,
	'size'	=> 20,
	'type'  => 'text',
	'class' => 'form-control',
	'placeholder' => 'Date',
	
	);

$starttime = array(
	'name'	=> 'starttime',
	'id'	=> 'starttime',
	'value' => set_value('starttime'),
	'maxlength'	=> 20,
	'size'	=> 20,
	'class' => 'form-control',
	'placeholder' => 'Between - Start Time',
	);

$endtime = array(
	'name'	=> 'endtime',
	'id'	=> 'endtime',
	'value' => set_value('endtime'),
	'maxlength'	=> 20,
	'size'	=> 20,
	'class' => 'form-control',
	'placeholder' => 'And - End Time',
	);

$label = array(
	'class' => 'col-sm-2 control-label',
	);

$form = array(
	'class' => 'form-horizontal',
	'role' => 'form',
	);


$js = 'class="form-control"';

?>

<div class="col-sm-4">

	<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
		<li class="active"><a href="#classstuff" data-toggle="tab"><b>Class Search</b></a></li>
		<li><a href="#courtsstuff" data-toggle="tab"><b>Sport Search</b></a></li>
	</ul>	

	<div class="tab-content" id="my-tab-content">
		<div class="tab-pane fade in active" id="classstuff">
			<?php $hidden = array('sportorclass' => 'class');?> 
			<?php echo form_open("/searchclass/index", $form, $hidden); ?>

			<div class="form-group">
				<?php echo form_dropdown('class_type_id', $classTypes, '' , $js);	 ?>
			</div>

			<div class="form-group hidden">
				<?php echo form_dropdown('class_type_id', $sportClassTypes, '' , $js. "disabled=disabled");	 ?>
			</div>

			<div class="form-group">
				<?php echo form_input($date); ?>
			</div>

			<div class="form-group">
				<?php echo form_input($starttime); ?>
			</div>

			<div class="form-group">
				<?php echo form_input($endtime); ?>
			</div>

			<div class="form-group">
				<?php echo form_submit('search', 'Search', 'class="btn btn-primary pull-right"'); ?>
				<?php echo form_close(); ?>
			</div>

		</div>    

	</div>


</div>



