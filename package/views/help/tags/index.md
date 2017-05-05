### Overview

Sketchpad uses tags to express additional functionality or formatting in the front end.

Just add them to your methods' doc comments, and Sketchpad automatically implements that functionality:

	/**
	 * I do something amazing
	 *
	 * @group       Awesome methods
	 * @icon        address-book
	 * @iframe
	 */
	public function someMethod ()
	{
		// I'll load in an iframe
	}

### Source

You can view the source for this section at:

	vendor/davestewart/sketchpad/package/views/help/tags
	vendor/davestewart/sketchpad/src/help/docs/TagsController.php
