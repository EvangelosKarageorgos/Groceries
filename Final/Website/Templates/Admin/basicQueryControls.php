<form id="query-params-form" method="GET">
	<span class='label'>From:</span>
	<input type="text" class="date-picker" name="date-from" value="<?= $model['dateFrom'] ?>"/>
	<span class='label'>To:</span>
	<input type="text" class="date-picker" name="date-to" value="<?= $model['dateTo'] ?>"/>
	<span class='label'>Max Results: </span>
	<input type="number" class="resultNumberField" name="results-count" min="1" value="<?= $model['resultsCount'] ?>"/>
	<input type="submit" class="groceriesBtn" value="Go" />
</form>
