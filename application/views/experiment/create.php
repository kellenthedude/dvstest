<h2>Create a new Experiment</h2>

<?php echo validation_errors(); ?>

<?php echo form_open('experiment/create') ?>

<div>
	<label for="title">Title</label>
	<input type="input" name="name" /><br />
</div>
<div>
	<label for="reactivity">Reactivity</label>
	<select name="reactivity" id="reactivity">
		<? foreach($reactivities as $react) : ?>
			<option value="<?=$react['react_id']?>"><?=$react['react_name']?></option>
		<? endforeach; ?>
	</select>
</div>
<div>
	<input type="submit" name="submit" value="Create Experiment" />
</div>
</form>