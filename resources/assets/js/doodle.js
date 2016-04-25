Doodle =
{
	onFileChanged: function (file) 
	{
		$('#test').append('<div>' + file.path + '</div>');
	}
};
