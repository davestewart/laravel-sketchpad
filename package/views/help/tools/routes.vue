<div id="routes">
	<p>
		<label>Routes: <input type="text" v-model="filter"></label>
	</p>
	<div>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<td colspan="6">Routes: {{ getCount(filter) }}</td>
				</tr>
				<tr>
					<td>#</td>
					<td v-for="(key, value) in routes[0]">{{ key }}</td>
				</tr>
			</thead>
			<tbody>
			<template v-for="route in routes">
				<tr v-if="filter == '' || (filter && inRow(route))">
					<td>{{ $index + 1}}</td>
					<td v-for="value in route" :class="{found:filter && value && inCell(value)}">{{ value }}</td>
				</tr>
			</template>
			</tbody>
		</table>
	</div>
</div>

<script>
	var routes = new Vue({
		el: '#routes',
		data: {
			filter: '',
			routes: $data
		},
		methods: {
			getCount: function() {
				return $('#routes').find('tbody tr').length;
			},
			getText: function(route) {
				return Object.keys(route).reduce(function(p,c,i,a){ return p + ' ' + (route[c] || ''); }, '');
			},
			inCell: function(value) {
				return value.toLowerCase().indexOf(this.filter.toLowerCase()) > -1;
			},
			inRow: function(route) {
				return this.getText(route).toLowerCase().indexOf(this.filter.toLowerCase()) > -1
			}
		}
	});
</script>

<style>
	.found {
		color:red;
	}
</style>