<template>

	<div id="setup">

		<h1>Setup</h1>

		<div class="wz-steps">

			<config
				v-ref:config
                :settings="settings">
			</config>

			<wizard-step v-ref:script :next="checkInstall" :enter="install">

                <h2 class="text-info">Installation</h2>
                <p>Copy the script below and run it in the terminal to complete the installation:</p>

<pre>cd "{{ settings.basepath }}"
php artisan sketchpad:install
composer dump-autoload
</pre>
                <p>Once complete, click Next to test everything worked...</p>

			</wizard-step>

			<wizard-step v-ref:error :next="checkInstall" :back="restart">
				<h2 class="text-danger">Installation failed</h2>
				<p>Review the errors and take action:</p>

                <table class="table table-striped table-bordered logs">
                    <thead>
                        <th>State</th>
                        <th>Operation</th>
                    </thead>
                    <tbody>
                        <tr v-for="log in logs" :class="['log', log.state ? 'pass' : 'fail']">
                            <td class="state">
                                <i v-if="log.state" class="fa fa-check"></i>
                                <i v-else class="fa fa-times"></i>
                            </td>
                            <td class="operation">
                                <p>{{ log.title }}</p>
                                <p v-if="log.text" class="help-block">{{{ log.text }}}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p>If you think you made a mistake with the paths, restart, edit the details, and repeat the installation process.</p>
				<p>If you think you can repair the errors yourself, do that, then click Next to check the installation again.</p>
			</wizard-step>

			<wizard-step v-ref:complete>
				<h2 class="text-success">Installation complete</h2>
				<p>The config file is located at:</p>
                <pre><code>{{ settings.configpath }}</code></pre>
				<p>You can edit it to add additional controllers folders.</p>
				<p>Click Finish to start using Sketchpad!</p>
			</wizard-step>

		</div>

		<hr>

		<div class="wz-controls form-group">
			<template v-if="! isLast">
				<button @click="back" v-if="!isFirst" class="btnPrev btn btnPrevious">{{ index == 2 ? 'Restart' : 'Back' }}</button>
				<button @click="next" :disabled="!isValid" class="btnNext btn btn-primary">Next</button>
			</template>
			<button v-else @click="finish" class="btn btn-primary btnNext">Finish</button>
		</div>

	</div>

</template>

<script>

import Config from './Config.vue';
import { WizardStep } from '../../js/classes/Wizard';


export default
{
	components:{
		Config,
		WizardStep
	},

	data:() =>
	{
		var settings = JSON.parse($('#settings').text() || '{}');

		return {
			index	:0,
			steps	:[
				'config',
				'script',
                'error',
                'complete'
			],
            logs    :[],
			settings:settings
		}
	},

	ready()
	{
		console.log(this.$refs);
		this.show(0);
	},

	computed:
	{
		step()
		{
			let id = this.steps[this.index];
			return this.$refs[id];
		},

		isFirst()
		{
			return this.index == 0;
		},

		isLast()
		{
			return this.index == this.steps.length - 1;
		},

        isValid()
        {
            if(this.step)
            {
                return this.step.hasOwnProperty('isValid')
                    ? this.step.isValid
                    : true;
            }
            return true;
        }
    },

	methods:
	{

	    // ------------------------------------------------------------------------------------------------
	    // navigation

            back()
            {
                var ref = this.getRef();
                var fn  = ref.back;
                if(fn)
                {
                    fn(this.onBackComplete, this.onNavigateFail)
                }
                else
                {
                    this.onBackComplete();
                }
            },

            next()
            {
                var ref = this.getRef();
                var fn  = ref.submit || ref.next;
                if(fn)
                {
                    // TODO consider chained promises to do submit and next
                    fn(this.onNextComplete, this.onNavigateFail)
                }
                else
                {
                    this.onNextComplete();
                }
            },

            onBackComplete()
            {
                this.index--;
            },

            onNextComplete()
            {
                var top  = $(window).scrollTop();
                var next = () => { this.index++ };
                if(top > 0)
                {
                    $('body').animate({scrollTop:0}, function(){
                        setTimeout(function(){
                            next();
                        }, 250);
                    });
                }
                else
                {
                    next();
                }
            },

            onNavigateFail(message)
            {
                console.log('FAIL: ', message);
            },


        // ------------------------------------------------------------------------------------------------
        // actions

            checkInstall(onSuccess)
            {
                var route = this.getRef('config').cleanOptions.route;

                console.log('checking install', this);

                jQuery.get('/' + route + ':setup/test', () => {
                    this.index  = 3;
                }).fail( (response) => {
                    this.logs   = response.responseJSON.data;
                    this.index  = 2;
                });
            },

            restart()
            {
                this.index = 0;
            },

            finish()
            {
                location.href = '/' + this.getRef('config').cleanOptions.route;
            },


        // ------------------------------------------------------------------------------------------------
        // utilities

            is(id)
            {
                return this.steps.indexOf(id) == this.index;
            },

            show:function(index)
            {
                this.steps.forEach( (id, i) =>
                {
                    let ref = this.$refs[id];
                    if(ref)
                    {
                        ref.$el.style.display = index == i ? 'block' : 'none';
                    }
                });
            },

            getRef(id = null)
            {
                // name; return immediately
                if(typeof id == 'string')
                {
                    return this.$refs[id];
                }

                // number; find by index
                let index = typeof id == 'number'
                    ? id
                    : this.index;

                // return
                return this.$refs[this.steps[index]];
            }

	},

	watch:
	{
		index(value)
		{
			this.show(value);
		}
	}
}

</script>

<style>

</style>