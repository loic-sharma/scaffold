@if(count($<?php echo $plural; ?>) == 0)
	<p>No <?php echo str_replace('_', ' ', $plural); ?>.</p>
@else
	<table>
		<thead>
			<tr>
<?php foreach($fields as $field => $type): ?>
				<th><?php echo ucwords(str_replace('_', ' ', $field)); ?></th>
<?php endforeach; ?>
<?php foreach($plural_relationships as $type => $models): ?>
<?php foreach($models as $model): ?>
				<th><?php echo ucwords(str_replace('_', ' ', Str::plural($model))); ?></th>
<?php endforeach; ?>
<?php endforeach; ?>
				<th></th>
			</tr>
		</thead>

		<tbody>
			@foreach($<?php echo $plural; ?> as $<?php echo $singular; ?>)
				<tr>
<?php foreach($fields as $field => $type): ?>
<?php if($type != 'boolean'): ?>
<?php if(strpos($field, '_id') !== false && in_array($model = substr($field, 0, -3), $belongs_to)): ?>
					<td><a href="{{URL::to('<?php echo Str::plural($model); ?>/view/'.$<?php echo $singular; ?>->id)}}"><?php echo ucwords(str_replace('_', ' ', $model)); ?> #{{$<?php echo $singular; ?>-><?php echo $field; ?>}}</a></td>
<?php else: ?>
					<td>{{$<?php echo $singular; ?>-><?php echo $field; ?>}}</td>
<?php endif; ?>
<?php else: ?>
					<td>{{($<?php echo $singular; ?>-><?php echo $field; ?>) ? 'True' : 'False'}}</td>
<?php endif; ?>
<?php endforeach; ?>
<?php foreach($plural_relationships as $type => $models): ?>
<?php foreach($models as $model): ?>
					<td>{{count($<?php echo $singular; ?>-><?php echo Str::plural($model); ?>)}}</td>
<?php endforeach; ?>
<?php endforeach; ?>
					<td>
						<a href="{{URL::to('<?php echo $plural; ?>/view/'.$<?php echo $singular; ?>->id)}}">View</a>
						<a href="{{URL::to('<?php echo $plural; ?>/edit/'.$<?php echo $singular; ?>->id)}}">Edit</a>
						<a href="{{URL::to('<?php echo $plural; ?>/delete/'.$<?php echo $singular; ?>->id)}}" onclick="return confirm('Are you sure?')">Delete</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endif

<p><a class="btn success" href="{{URL::to('<?php echo $plural; ?>/create')}}">Create new <?php echo str_replace('_', ' ', $singular_class); ?></a></p>