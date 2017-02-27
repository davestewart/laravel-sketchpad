<template>

	<div id="setup">

		<header id="header">
			<h1>Setup</h1>
		</header>

		<section id="steps">

			<config
                id="config"
				v-ref:config
                :settings="settings">
			</config>

			<article id="summary">

                <h2 class="text-info">Summary</h2>

                <p>Sketchpad will run in the following route:</p>
<pre>
{{ options.route }}
</pre>

                <p>Sketchpad will install to these folders under <code>{{ settings.basename }}</code>:</p>

<pre>
controllers  : {{ options.controllers }}
views        : {{ options.views }}
</pre>

                <template v-if="options.autoloader">
                    <p>Your site's Composer PSR-4 autoloader will be updated with the following entry:</p>
<pre>
"{{ options.namespace}}" : "{{ options.basedir}}"
</pre>

                </template>

                <p>Click <strong>Next</strong> to start the install.</p>
			</article>

			<article id="install">

                <h2 class="text-info">Installing</h2>
                <p>One moment please...</p>
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
                        <tr v-for="log in results.data" :class="['log', log.state ? 'pass' : 'fail']">
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

                <p>Actions:</p>
                <ul>
                    <li>Click <strong>Back</strong> to edit your settings and try again</li>
                    <li>Click <strong>Next</strong> if you think this is an external issue (perhaps permissions) and you are ready to try installation again</li>
                </ul>

                <p>To run the installation manually, run the following code in the terminal:</p>
                <pre>cd "{{ settings.basepath }}"
php artisan sketchpad:install
composer dump-autoload
</pre>

			</article>

			<article id="complete">
				<h2 class="text-success">Installation complete</h2>
				<p>The settings file is located at:</p>
                <pre><code>{{ settings.storagepath }}</code></pre>
				<p>Click <strong>Next</strong> to start using Sketchpad!</p>
			</article>

		</section>

		<hr>

		<div id="controls" class="form-group">
            <button name="back"  class="btnPrev btn">Back</button>
            <button name="next"  class="btnNext btn btn-primary">Next</button>
		</div>

	</div>

</template>

<script>

import StateMachine from 'state-machine/lib/StateMachine';
import StateHelper from 'state-machine/lib/StateHelper';

import Config from './Config.vue';

function scrollTop(callback)
{
    callback = callback || function() { }
    const top  = $(window).scrollTop();
    if(top > 0)
    {
        $('body').animate({scrollTop:0}, function(){
            setTimeout(function(){
                callback();
            }, 250);
        });
    }
    else
    {
        callback();
    }
}

var data =
{
    // install settings
    settings:{

    },

    // form data
    config:{

    },

    // installation results
    results: {

    }
};

export default
{
	components:
	{
		Config
	},

	data ()
	{
	    data.settings = JSON.parse($('#settings').text() || '{}');
		return data;
	},

	computed:
	{
	    options()
	    {
	        return this.$refs.config
	        	? this.$refs.config.cleanOptions
	        	: {};
	    },

	    route()
	    {
	        return '/' + this.$refs.config.cleanOptions.route;
	    }
	},

	created ()
	{
		window.app = this;
	},

	ready ()
	{
		fsm = new StateMachine({

		    scope:this,

		    transitions:
		    [
		        // user
		        'next     : config > summary > install                   < error',
		        'next     :                              complete > exit',
		        'back     : config < summary                             < error',

		        // internal
		        'complete :                    install > complete',
		        'error    :                    install >                   error'
		    ],

		    handlers:
		    {
		        ':leave'(event, fsm)
		        {
		            scrollTop();
		        },

                install(event, fsm)
                {
                    console.log(this.options, this.route);
                    jQuery
                        .post(this.route + 'setup/install', this.options)
                        .then(data => {
                            this.results = data;
                            if(data.success)
                            {
                                fsm.do('complete');
                            }
                            else
                            {
                                fsm.go('error');
                            }
                        });
                },

                exit(event, fsm)
                {
                    fsm.cancel(); // don't show final state screen
                    location.href = '/' + this.$refs.config.cleanOptions.route;
                }
            }
		});

		StateHelper.jQuery(fsm, '#steps', '#controls', '> *');
	}

}

</script>

<style>

</style>