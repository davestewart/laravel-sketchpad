<template>

	<article id="config">

		<h2 class="text-info">Configuration</h2>

		<p>Sketchpad (and its subfolders) can be installed anywhere in your project, but the default "separate" setup is recommended if you just want a bolt-on development environment. See the <a
				href="https://github.com/davestewart/laravel-sketchpad/wiki/Setup" target="_blank">wiki</a> for further info.</p>
        <p>If the controllers folder is outside of your app namespace, you'll need to supply PSR-4 autoloader info.</p>
		<p>Choose an installation preset, edit any paths you need to, then click Next to continue.</p>

		<div class="form-container" style="margin:30px 15px;">

			<form class="form-horizontal" autocomplete="off">

				<fieldset>

                    <legend>Config</legend>

					<div class="form-group inline">
						<label class="control-label col-sm-3">Installation</label>
						<div class="col-sm-9">
							<div v-for="(key, option) in getOptions()" class="radio">
								<label class="radio">
									<input type="radio" name="type" v-model="type" :value="key"/>
									<span>{{ option.type | capitalize }}</span>
								</label>
							</div>
                            <p class="help-block">{{ options.desc }}</p>
						</div>
					</div>

                    <div :class="getClass('route')">
						<label class="control-label col-sm-3">Route</label>
						<div class="col-sm-9">
							<input type="text"
								   v-model="options.route"
								   class="form-control"
								   name="route"
								   :placeholder="getPlaceholder('route')">
							<p class="help-block prompt">{{ prompts.route }}</p>
							<p class="help-block hint">{{ hints.route }}</p>
						</div>
					</div>

				</fieldset>

				<fieldset>

                    <legend>Paths</legend>

					<div :class="getClass('controllers', true)">
						<label class="control-label col-sm-3">Controllers</label>
						<div class="col-sm-9">
							<input type="text"
								   v-model="options.controllers"
                                   class="form-control"
								   name="controllers"
								   :placeholder="getPlaceholder('controllers')">
							<p class="help-block prompt">{{ prompts.controllers }}</p>
							<p class="help-block hint">{{ hints.controllers }}</p>
						</div>
					</div>

                    <div :class="getClass('views', true)">
						<label class="control-label col-sm-3">Views</label>
						<div class="col-sm-9">
							<input type="text"
								   v-model="options.views"
								   class="form-control"
								   name="views"
								   :placeholder="getPlaceholder('views')">
							<p class="help-block prompt">{{ prompts.views }}</p>
							<p class="help-block hint">{{ hints.views }}</p>
						</div>
					</div>

                    <div :class="getClass('assets')">
						<label class="control-label col-sm-3">Assets</label>
						<div class="col-sm-9">
							<input type="text"
								   v-model="options.assets"
								   class="form-control"
								   name="assets"
								   :placeholder="getPlaceholder('assets')">
							<p class="help-block prompt">{{ prompts.assets }}</p>
							<p class="help-block hint">{{ hints.assets }}</p>
						</div>
					</div>
					<!--
					-->
				</fieldset>

                <fieldset v-if="autoloader">

                    <legend>Autoloader</legend>

					<div :class="getClass('namespace', true)">
						<label class="control-label col-sm-3">Namespace prefix</label>
						<div class="col-sm-9">
							<input type="text"
								   v-model="options.namespace"
                                   class="form-control"
								   name="namespace"
								   :placeholder="getPlaceholder('namespace')">
							<p class="help-block prompt">{{ prompts.namespace }}</p>
							<p class="help-block hint">{{ hints.namespace }}</p>
						</div>
					</div>

					<div :class="getClass('basedir', true)">
						<label class="control-label col-sm-3">Base directory</label>
						<div class="col-sm-9">
							<input type="text"
								   v-model="options.basedir"
                                   class="form-control"
								   name="basedir"
								   :placeholder="getPlaceholder('basedir')">
							<p class="help-block prompt">{{ prompts.basedir }} </p>
							<p class="help-block hint">{{ hints.basedir }} </p>
						</div>
					</div>

                </fieldset>


			</form>

		</div>

	</article>

</template>

<script>


var prompts = {
    route		    :'The URL you run Sketchpad in the browser',
    controllers	    :'A root-relative path to a controllers folder that Sketchpad will monitor (you can add more later)',
    views		    :'A root-relative path to a views folder to load Sketchpad-specific Blade templates',
    assets		    :'A root-relative path to an assets folder to load user scripts and styles',
    namespace	    :'The PSR-4 namespace prefix for controllers',
    basedir         :'The directory the autoloader namespace prefix maps to'
};

var options = {
	separate: {
		type		:'separate',
        desc        :'Sketchpad functions separately from your app; great for development',
		controllers	:'sketchpad/controllers',
		views		:'sketchpad/views',
		assets		:'sketchpad/assets',
		route		:'sketchpad',
		namespace	:'sketchpad',
        basedir     :'sketchpad'
	},
	integrated: {
		type		:'integrated',
        desc        :'Integrates Sketchpad into your app; code lives inside Laravel subfolders',
		controllers	:'app/Http/Controllers/Sketchpad',
		views		:'resources/views/sketchpad',
		assets		:'public/vendor/sketchpad',
		route		:'sketchpad',
		namespace	:'App',
        basedir     :'app'
	},
	application: {
		type		:'application',
        desc        :'Sketchpad becomes your app; useful if you want a quick front-end UI',
		controllers	:'app/Http/Controllers',
		views		:'resources/views',
		assets		:'public/assets',
		route		:'',
		namespace	:'App',
        basedir     :'app'
	},
	custom: {
		type		:'custom',
        desc        :'Full control over the installation; choose your own folders, paths, routes, etc.',
		controllers	:'custom/src/Controllers',
		views		:'custom/resources/views',
		assets		:'custom/resources/assets',
		route		:'custom',
		namespace	:'Custom',
		basedir	    :'custom/src'
	}
};

function copy(obj)
{
	return JSON.parse(JSON.stringify(obj));
}

function reject(message)
{
    console.log('Promise failed: ', message);
}

export default
{
	props:['settings', 'validate'],

	data ()
	{
		return {
			type        :'separate',
            focus       :'',
			options	    :copy(options.separate),
            prompts     :copy(prompts),
            hints       :copy(prompts),
            autoloader  :false,
            dirty       :false
		}
	},

    computed:
    {
        isValid ()
        {
            this.isComplete = false;
            return this.options.controllers !== ''
                && this.options.namespace !== ''
                && this.options.views !== '';
        },

        cleanOptions ()
        {
            const options  = this.options;

            function tail(value, char = '/')
            {
                return value
                    .replace(/^[ \/\\]*/, '')
                    .replace(/[ \/\\]*$/, char)
            }

            function route(value)
            {
                const route = '/' + tail(value);
                return route === '//' ? '/' : route;
            }

            return {
                type            :options.type,
                autoloader      :this.autoloader,
                route		    :route(options.route),
                controllers	    :tail(options.controllers),
                views		    :tail(options.views),
                assets		    :tail(options.assets),
                namespace	    :tail(options.namespace, '\\'),
                basedir         :tail(options.basedir)
            };
        }

    },

    created ()
    {
        // update options with actual values
        options.integrated.controllers  = this.settings.controllerpath + '/Sketchpad';
        options.application.controllers = this.settings.controllerpath;
        options.integrated.views        = this.settings.viewpath + '/sketchpad';
        options.application.views       = this.settings.viewpath;
        options.integrated.namespace    = this.settings.namespace;
        options.application.namespace   = this.settings.namespace;
    },

    ready ()
    {
        // update help prompts on focus/blur
        $(this.$el)
            .on('focus blur', 'input', (event) => {
                $(event.target)
                    .closest('.form-group')
                    .toggleClass('active', event.type == 'focusin');
            });

        // update ui
        this.updateHints();
        this.checkAutoloader();
    },

	methods:
	{
	    getOptions ()
        {
            return options;
        },

		getPlaceholder (key)
		{
			return options[this.type][key];
		},

        getClass (key, required)
        {
            var value = String(this.options[key]).replace(/^[\s\/]+|\s\/+$/g, '');
            return {
                'form-group'    :true,
                'has-error'     :required ? value == '' : false
            };
        },

        updateHints ()
        {
            var options  = this.cleanOptions;
            var settings = this.settings;

            function path(value)
            {
                return settings.basename + value;
            }

            this.hints =
            {
                controllers	:path(options.controllers),
                views		:path(options.views),
                assets		:path(options.assets),
                route		:options.route,
                namespace	:options.namespace,
                basedir     :path(options.basedir)
            };
        },

        checkAutoloader ()
        {
            // variables
            var path        = this.options.controllers;
            var folder      = String(path.match(/^[^\/]+/)) || '';

            // determine whether to show autoloader
            var basedir     = this.settings.namespaces[this.settings.namespace + '\\'];
            this.autoloader = path.indexOf(basedir) !== 0;

            // update
            if(this.dirty)
            {
                this.options.namespace  = folder.replace(/\w/, function(match){ return match.toUpperCase(); });
                this.options.basedir    = folder;
            }
            this.dirty = true;
        },

        validate ()
        {
            if(this.isValid)
            {
                return true;
            }
            reject('All fields are required!');
            return false;
        }

	},

	watch:
	{
		type (value)
		{
            this.dirty      = false;
            this.options    = copy(options[value] || options.application);
        },

        'options.controllers'()
        {
            this.checkAutoloader();
        },

        options:
        {
            handler ()
            {
                this.updateHints()
            },
            deep :true
        }
	}
}

</script>

<style>

</style>