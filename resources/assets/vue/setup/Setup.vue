<template>

	<div id="setup">

		<header>
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
{{ displayRoute }}
</pre>
                <p>Sketchpad will install to these folders under <code>{{ settings.basename }}</code>:</p>

<pre>
controllers  : {{ options.controllers }}
views        : {{ options.views }}
assets       : {{ options.assets }}
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
                <p>Running tasks...</p>
			</article>

			<article id="test">
                <h2 class="text-info">Installing</h2>
                <p>Testing install...</p>
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
                                <svg v-if="log.state"
                                     width="16" height="16" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z"/></svg>
                                <svg v-else
                                     width="16" height="16" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1490 1322q0 40-28 68l-136 136q-28 28-68 28t-68-28l-294-294-294 294q-28 28-68 28t-68-28l-136-136q-28-28-28-68t28-68l294-294-294-294q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 294 294-294q28-28 68-28t68 28l136 136q28 28 28 68t-28 68l-294 294 294 294q28 28 28 68z"/></svg>
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
                    <li>Click <strong>Next</strong> when you have fixed any problems mentioned above, to try again</li>
                    <li>Click <strong>Back</strong> to edit your settings and try again</li>
                </ul>

                <p>To run the installation manually, run the following code in the terminal:</p>
                <pre>cd "{{ settings.basepath }}"
php artisan sketchpad:install
</pre>

			</article>

			<article id="complete">
				<h2 class="text-success">Installation complete</h2>

				<div class="panel panel-danger" v-if="options.route === '/'">
					<div class="panel-heading">
						<h3 class="panel-title">Warning: manual update required</h3>
					</div>
					<div class="panel-body">
						<p>Because you configured Sketchpad to run as your <strong>site root</strong>, you will need to <strong>remove the existing Laravel <code>/</code> route</strong> to view Sketchpad before continuing.</p>
						<p>If you don't, your <a href="/" target="_blank">existing homepage</a> will show, rather than the Sketchpad dashboard.</p>
					</div>
				</div>

				<p>To have the UI update as you save, add or delete files, install Sketchpad Reload:</p>
				<ul style="padding: 25px 50px">
					<li><a target="_blank" href="https://github.com/davestewart/laravel-sketchpad-reload">github.com/davestewart/laravel-sketchpad-reload</a></li>
				</ul>
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

		displayRoute ()
		{
			return this.options.route.replace(/^\/(\w)/, '$1')
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
		        'next     : config > summary > install > test                   < error',
		        'next     :                                     complete > exit',
		        'back     : config < summary                                    < error',

		        // internal
		        'complete :                              test > complete',
		        'error    :                              test >                   error'
		    ],

		    handlers:
		    {
		        ':leave'(event, fsm)
		        {
		            scrollTop();
		        },

                install(event, fsm)
                {
                	// debug
                    console.info(this.options);

                    // run installer
                    jQuery
                        .post(this.settings.route + 'setup/install', this.options)
                        .then(data => fsm.go('test'));
                },

			    test ()
			    {
				    jQuery
					    .get(this.settings.route + 'setup/test')
					    .then(data => {
						    this.results = data;
						    if(data.success)
						    {
	                            this.settings.route = this.options.route;
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
                    location.href = this.options.route;
                }
            }
		});

		StateHelper.jQuery(fsm, '#steps', '#controls', '> *');
	}

}

</script>

<style>

</style>