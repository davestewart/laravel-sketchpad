import Vue from 'vue';

function fnOK(resolve, reject)
{
	console.log('Default OK');
    resolve();
}

/**
 * Base logic for wizard step
 */
export let WizardStepMixin =
{
	props:{
		name:{
			type: String
		},
		validate:{
			type	: Function,
			default	: fnOK
		},
		next:{
			type	: Function,
			default	: fnOK
		},
		back:{
			type	: Function,
			default	: fnOK
		},
		enter:{
			type	: Function,
			default	: fnOK
		},
		leave:{
			type	: Function,
			default	: fnOK
		}
	},

	methods:
	{
		/*
		validate :fnOK,
		onEnter  :fnOK,
		onLeave  :fnOK,
		onBack   :fnOK
		*/
	}

};

export let WizardStep = Vue.component('wizard-step',
{
	template:'<section class="wz-step"><slot></slot></section>',
	mixins:[ WizardStepMixin ]
});


/**
 * Base logic for wizard component
 */
export let WizardMixin = {

	data:function()
	{
		return {
			steps	:[],
			step	:null
		}
	},

	computed:
	{
		stepIndex()
		{
			return this.steps.indexOf(this.step);
		},

		stepName()
		{
			return this.steps.name;
		},

		isFirst()
		{
			return this.stepIndex == 0;
		},

		isLast()
		{
			return this.stepIndex == this.steps.length - 1;
		}
	},

	created()
	{
		// get all steps
	},

	ready()
	{
		// find progress and add it
		// find steps and add them


	},

	methods:
	{
		validate(step)
		{
			return true;
		},

		tryNext()
		{
			var validate = this.step.validate || this.validate || fnOK;
			if(validate())
			{
				let index = this.steps.indexOf(this.step);
				if(index < this.steps.length - 2)
				{
					this.step = this.steps[index + 1];
					this.$emit('step.change', index + 1 , index);
				}
			}
		},

		tryPrev()
		{
			let index = this.steps.indexOf(this.step);
			if(index > 0)
			{
				this.step = this.steps[index - 1];
				this.$emit('step.change', index -1 , index);
			}
		},

		next (step, next)
		{

		},

		prev (step, prev)
		{

		}
	}

};

/**
 * Include this mixin to automatically update
 * @type {{}}
 */
export let WizardLogic = {

};

export let Wizard = Vue.extend({

	template:'<div class="wz-wizard"><slot name="progress"></slot></div>',

	mixins: [ WizardMixin ]
});
