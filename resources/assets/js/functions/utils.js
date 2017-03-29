import _ from 'underscore';

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

/**
 * Computes the difference between two arrays, with an optional single element to calculate any changes to
 *
 * Used only in State::setController() to determine if the current method has changed when the current controller is
 * reloaded
 *
 * @param   {Array}     newValues   An array of values to compare to
 * @param   {Array}     oldValues   An array of values to compare from
 * @param   {*}         oldValue    An array in the old array of values to calculate changes (move, change, none) to
 * @returns {Object}                An object with "type" and misc attributes for changed values
 *
 *  - type: none, index, value
 *  - type: changed, index, oldValue, newValue
 *  - type: moved, oldIndex, newIndex, added, removed
 *  - type: modified, oldIndex, removed, added
 */
export function getArrayChange (newValues, oldValues, oldValue)
{
	const oldIndex = oldValues.indexOf(oldValue);
	const newIndex = newValues.indexOf(oldValue);

	const added = _.difference(newValues, oldValues);
	const removed = _.difference(oldValues, newValues);

	// no change
	if (newIndex === oldIndex)
	{
		return {type: 'none', value: oldValue, index: oldIndex}
	}
	// there was a single change to the target element only
	else if (added.length == 1 && removed.length === 1 && newValues.indexOf(added[0]) === oldIndex)
	{
		return {type: 'changed', index: oldIndex, oldValue, newValue: added[0]}
	}
	// the target element moved positions in the array (other elements may or may not have been added)
	else if (newIndex > -1 && newIndex !== oldIndex)
	{
		return {type: 'moved', oldIndex, newIndex, added, removed}
	}
	// there were additions and removals from the array
	else
	{
		return {
			type: 'modified',
			oldIndex,
			removed: removed,
			added: added
		}
	}
}

