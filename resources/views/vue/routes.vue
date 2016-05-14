

<div id="routes">
	<p>
		<label>Filter: <input type="text" v-model="filter"></label>
		<label>Results: {{ getCount(filter) }}</label>
	</p>
	<div>
		<table class="table table-bordered table-striped">
			<thead>
			<tr>
				<td v-for="(key, value) in routes[0]">{{ key }}</td>
			</tr>
			</thead>
			<tbody>
			<template v-for="route in routes">
				<tr v-if="filter == '' || inRow(route)">
					<td v-for="value in route" :class="{found:value && filter !== '' && inRow(value)}">{{ value }}</td>
				</tr>
			</template>
			</tbody>
		</table>
	</div>
</div>

<script>
	var routes = new Vue({
		el:'#routes',
		data:{
			filter:'',
			routes:%data%
		},
		computed:{

		},
		methods:{
			getCount:function(){
				return $('#routes').find('tbody tr').length;
			},
			inCell:function(value){
				return value.toLowerCase().indexOf(filter.toLowerCase()) > -1;
			},
			inRow:function(route){
				var text = Object.keys(route).reduce(function(p,c,i,a){ return p + route[c]; }, '');
				return text.toLowerCase().indexOf(this.filter.toLowerCase()) > -1
			}
		}
	});
</script>

<style>.found{ color:red; }</style>