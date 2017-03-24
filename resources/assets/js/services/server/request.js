/**
 * Basic request class to collate and pass around request values
 *
 * @param	{string}	url		The url to call
 * @param	{object}	data		Any data to pass with the call
 * @param	{Function}	done		A callback to fire when the call completes
 * @param	{Function}	fail		A callback to fire when the call fails
 * @param	{Function}	always		A callback to fire when the call is finished
 * @constructor
 */
export default function Request (url, data, done = null, fail = null, always = null)
{
	this.url        = url;
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
