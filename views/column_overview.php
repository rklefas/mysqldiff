<!-- [extend src="screen"] -->



<table class="overviews">
	<tr>
		<th>
			Right Now
			<br />
			 <?php echo $this->get('PrefetchedDestinationTitle'); ?>
			<div style="font-style: italic; color: #888; ">Changes will be made to this database</div>
		</th>
		<th class="alternatecolor">Differences</th>
		<th>
			After
			<br />
			 <?php echo $this->get('PrefetchedSourceTitle'); ?>
		</th>
	</tr>


<?php if ($this->is('compiledColumns')): ?>

	<?php foreach ($this->get('compiledColumns') as $column => $instruction): ?>
		
		<tr>
			<td align="right">
			<?php if ($instruction != "add"): ?>
				<a href="index.php?task=difference_column.column_view&dbside=dest&table=<?php echo $this->get('table'); ?>&column=<?php echo $column; ?>" target="_blank"><?php echo $column; ?></a>
			<?php endif; ?>
			</td>

			
			<td class="alternatecolor">
				<?php if ($instruction): ?>
				<a href="index.php?task=difference_column.<?php echo $instruction; ?>&table=<?php echo $this->get('table'); ?>&column=<?php echo $column; ?>"><?php echo ucwords($instruction); ?></a>
				<?php endif; ?>
			</td>

			<td>
			<?php if ($instruction != "drop"): ?>
				<a href="index.php?task=difference_column.column_view&dbside=source&table=<?php echo $this->get('table'); ?>&column=<?php echo $column; ?>" target="_blank"><?php echo $column; ?></a>
			<?php endif; ?>
			</td>


		</tr>

	<?php endforeach; ?>
	
		<tr>
			<th></th>
			<td class="alternatecolor"><a href="index.php?task=difference_column.resolveall&table=<?php echo $this->get('table'); ?>">Resolve All</a></td>
			<th></th>
		</tr>

		<tr>
			<th></th>
			<td class="alternatecolor"><a href="index.php?task=difference_table.recreate&table=<?php echo $this->get('table'); ?>">Recreate Table</a></td>
			<th></th>
		</tr>

<?php endif; ?>
</table>


