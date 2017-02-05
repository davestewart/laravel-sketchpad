<template>

	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">{{{ title }}}</h3>

				</div>
				<div class="modal-body">{{{ body }}}</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button v-if="save" type="button" class="btn btn-primary">OK</button>
				</div>
			</div>
		</div>
	</div>

</template>

<script>
	
export default
{
	props:['title', 'body', 'save'],

	methods:
	{
		load (url)
		{

			$.get(url, function (html)
			{
				var $body 	= $('<div>').append(html);
				this.title 	= $body.find('h1').remove().text();
				this.save	= $body.find('form').length;
				this.body 	= $body.html();
				this.show();
			}.bind(this))
		},

		show ()
		{
			console.log('show');
			$('#modal').modal('show');
		},

		hide ()
		{
			$('#modal').modal('hide');
		}

	}

}

</script>

<style lang="scss">
	
</style>