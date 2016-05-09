
var vm 		= new Vue(vm);
var server	= new Server();
var hist	= new UserHistory(vm);

vm.$watch('method.params', function()
{
	//console.log(arguments);
	vm.$refs.result.updateMethod();

}, {deep:1});

