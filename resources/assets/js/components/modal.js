Vue.component('modal', {

	template:'#modal-template',
	
	props:['title', 'body', 'save'],

	methods:
	{
		load:function(url)
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

		show:function()
		{
			console.log('show');
			$('#modal').modal('show');
		},

		hide:function()
		{
			$('#modal').modal('hide');
		}

	}
	
});