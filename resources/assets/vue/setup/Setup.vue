<template>

	<div id="setup">

		<h1>Setup</h1>

		<section id="steps">

			<config
                id="config"
				v-ref:config
                :settings="settings">
			</config>

			<article id="summary">

                <h2 class="text-info">Installation</h2>
                <p>Copy the script below and run it in the terminal to complete the installation:</p>
<pre>cd "{{ settings.basepath }}"
php artisan sketchpad:install
composer dump-autoload
</pre>
                <p>Once complete, click Next to test everything worked...</p>

			</article>

			<article id="error">
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
			</article>

			<article id="complete">
				<h2 class="text-success">Installation complete</h2>
				<p>The config file is located at:</p>
                <pre><code>{{ settings.configpath }}</code></pre>
				<p>Click Finish to start using Sketchpad!</p>
			</article>

		</section>

		<hr>

		<div id="controls" class="form-group">
			<template v-if="! isLast">
				<button name="back" class="btnPrev btn">Back</button>
				<button name="next" class="btnNext btn btn-primary">Next</button>
			</template>
			<button v-else class="btn btn-primary btnNext">Finish</button>
		</div>

	</div>

</template>

<script>

import {setup, scrollTop} from './scripts'
import Config from './Config.vue';
import StateMachine from '../../js/lib/state-machine';

var state =
{
    settings:{

    },

    config:{

    },

    logs:[

    ]
};

export default
{
	components:{
		Config
	},

	data:() =>
	{
	    state.settings = JSON.parse($('#settings').text() || '{}');
		return state;
	},

	computed:
	{
	    options()
	    {
	        return this.$refs.config.cleanOptions;
	    },

	    route()
	    {
	        return '/' + this.$refs.config.cleanOptions.route;
	    }
	},

	ready()
	{
		fsm = new StateMachine({

		    scope:this,

		    transitions:
		    [
		        'next    : config > summary > install > complete',
		        'back    : config < summary',
		        'error   :                    install >            error',
		        'next    :                    install            < error',
		        'back    : config                                < error',
		        'next    :                              complete >         exit'
		    ],

		    handlers:
		    {
		        ':leave':function(event, fsm)
		        {
		            fsm.pause();
		            scrollTop(fsm.resume.bind(fsm));
		        },

                summary(event, fsm)
                {
                    console.log(this.options);
                    jQuery
                        .post(this.route + ':setup', this.options, data => console.log('Options saved: ', data) )
                        .fail( data => console.error(data || 'Options could not be saved') );
                },

		        install(event, fsm)
                {
                    fsm.pause();
                    jQuery.get(this.route + ':setup/test', (data) =>
                    {
                        console.log('Installation succeeded!', data);
                        fsm.go('complete', true);
                    })
                    .fail(response =>
                    {
                        this.logs   = response.responseJSON.data;
                        fsm.go('error', true);
                    });
                },

                complete(event, fsm)
                {
                    $('button[name=next]').text('Finish');
                },

                exit(event, fsm)
                {
                    fsm.cancel();
                    location.href = '/' + this.$refs.config.cleanOptions.route;
                }
            }
		});

		setup(fsm, '#steps', '#controls', '> *');
	}

}

</script>

<style>

</style>