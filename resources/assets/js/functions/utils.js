export function clone(obj)
{
	return JSON.parse(JSON.stringify(obj));
}

export function dump()
{
	Array.prototype.slice.call(arguments).map(obj => console.log(clone(obj)))
}

export function trim (string)
{
	return String(string || '').replace(/^\s+|\s+$/g, '')
}

let context;

export function getTextWidth(text, font)
{
	// re-use canvas object for better performance
	if (!context) 
	{
		const canvas = document.createElement('canvas');
		context = canvas.getContext('2d');
	}
	context.font = font;
	const metrics = context.measureText(text);
	return metrics.width;
}
