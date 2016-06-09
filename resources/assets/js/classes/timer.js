function Timer()
{
	
}

Timer.prototype =
{
	active:false,
	timeStart:0,
	time:0,

	start:function()
	{
		this.reset();
		this.active 	= true;
		this.timeStart 	= this.getTime();
		return this;
	},

	stop:function()
	{
		this.time 		= this.getTime();
		this.active 	= false;
		return this;
	},

	getTime:function()
	{
		return this.active
			? Date.now() - this.timeStart
			: this.time;
	},

	reset:function()
	{
		this.active 	= false;
		this.timeStart 	= this.time = 0;
		return this;
	}
};