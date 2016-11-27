/**
 * Basic request class to collate and pass around request values
 *
 * @param	{string}	route		The route to call
 * @param	{object}	data		Any data to pass with the call
 * @param	{Function}	done		A callback to fire when the call completes
 * @param	{Function}	fail		A callback to fire when the call fails
 * @param	{Function}	always		A callback to fire when the call is finished
 * @constructor
 */
export default function Request (route, data, done = null, fail = null, always = null)
{
	this.url        = route.replace(/\/+$/, '') + '?_call=1';
	this.data       = data;
	this.done  		= done;
	this.fail  		= fail;
	this.always  	= always;
}

Request.prototype =
{
	url		    :'',
	data		:null,
	done		:null,
	fail		:null,
	always		:null
};
