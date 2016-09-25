<template>

	<section id="config">

		<h2 class="text-info">Configuration</h2>
		<!--
		<p>The installer needs to know some information so it can create folders and copy files.</p>
		-->
		<p>Sketchpad (and its subfolders) can be installed anywhere in your project, but the default "separate" setup is recommended. This allows you to keep everything in one place, and makes version control easier.</p>
        <p>If the controllers folder is outside of your app namespace, you'll need to supply PSR-4 autoloader info.</p>
		<p>Choose your install type, edit any paths you need to, then click Next to continue.</p>

		<div class="form-container">

			<div class="xwell" style="margin:30px 20px;">

				<form class="form-horizontal" autocomplete="off">


					<fieldset>

                        <legend>Installation</legend>

						<div id="type" class="form-group inline">
							<label class="control-label col-sm-3">Type</label>
							<div class="col-sm-9">
								<div v-for="(key, option) in getOptions()" class="radio">
									<label class="radio">
										<input type="radio" name="type" v-model="type" value="{{ key }}"/>
										{{ option.type | capitalize }}
									</label>
								</div>
                                <p class="help-block">{{ options.desc }}</p>
							</div>
						</div>

					</fieldset>

					<fieldset>

                        <legend>Paths</legend>

						<div id="controllers" :class="getClass('controllers', true)">
							<label for="controllers" class="control-label col-sm-3">Controllers</label>
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

                        <div id="views" :class="getClass('views', true)">
							<label for="views" class="control-label col-sm-3">Views</label>
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

                        <div id="assets" :class="getClass('assets')">
							<label for="assets" class="control-label col-sm-3">Assets</label>
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

                        <div id="route" :class="getClass('route')">
							<label for="route" class="control-label col-sm-3">Route</label>
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

                    <fieldset v-if="autoloader">

                        <legend>Autoloader</legend>

						<div id="namespace" :class="getClass('namespace', true)">
							<label for="controllers" class="control-label col-sm-3">Namespace prefix</label>
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

						<div id="basedir" :class="getClass('basedir', true)">
							<label for="controllers" class="control-label col-sm-3">Base directory</label>
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

		</div>

	</section>

</template>

<script>


var prompts = {
    controllers	    :'The folder path to a controllers folder that Sketchpad will monitor (you can add more later)',
    views		    :'The folder path to a views folder to load Sketchpad-specific Blade templates',
    assets		    :'The public folder path where Sketchpad\'s scripts and styles will be installed',
    route		    :'The URL you run Sketchpad in the browser',
    namespace	    :'The PSR-4 namespace prefix for controllers',
    basedir         :'The directory the autoloader namespace prefix maps to'
};

var options = {
	separate: {
		type		:'separate',
        desc        :'Sketchpad functions separately from your app; easiest for version control',
		controllers	:'sketchpad/controllers',
		views		:'sketchpad/views',
		assets		:'vendor/sketchpad',
		route		:'sketchpad',
		namespace	:'sketchpad',
        basedir     :'sketchpad'
	},
	integrated: {
		type		:'integrated',
        desc        :'Integrates Sketchpad into your app; code lives inside Laravel subfolders',
		controllers	:'app/Http/Controllers/Sketchpad',
		views		:'resources/views/sketchpad',
		assets		:'vendor/sketchpad',
		route		:'sketchpad',
		namespace	:'App',
        basedir     :'app'
	},
	application: {
		type		:'application',
        desc        :'Sketchpad becomes your app; useful if you want a quick front-end UI',
		controllers	:'app/Http/Controllers',
		views		:'resources/views',
		assets		:'assets',
		route		:'',
		namespace	:'App',
        basedir     :'app'
	},
	custom: {
		type		:'custom',
        desc        :'Full control over the installation; choose your own folders, paths, routes, etc.',
		controllers	:'custom/sketchpad/controllers',
		views		:'custom/sketchpad/views',
		assets		:'assets/sketchpad',
		route		:'custom',
		namespace	:'custom',
		basedir	    :'custom'
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

	data()
	{
		return {
			type        :'separate',
            focus       :'',
			options	    :copy(options.separate),
            prompts     :copy(prompts),
            hints       :copy(prompts),
            autoloader  :false,
            dirty       :false,
            isComplete  :false
		}
	},

    computed:
    {
        isValid()
        {
            this.isComplete = false;
            return this.options.controllers !== ''
                && this.options.namespace !== ''
                && this.options.views !== '';
        },

        cleanOptions()
        {
            var options  = this.options;

            function tail(value, char = '/')
            {
                return value
                    .replace(/^[ \/\\]*/, '')
                    .replace(/[ \/\\]*$/, char)
            }

            return {
                type            :options.type,
                autoloader      :this.autoloader,
                controllers	    :tail(options.controllers),
                controllersns	:tail(options.controllers, '').replace(/\//g, '\\'),
                views		    :tail(options.views),
                assets		    :tail(options.assets),
                route		    :tail(options.route),
                namespace	    :tail(options.namespace, '\\'),
                basedir         :tail(options.basedir)
            };
        }

    },

    created()
    {
        // update options with actual values
        options.integrated.controllers  = this.settings.controllerpath + '/Sketchpad';
        options.application.controllers = this.settings.controllerpath;
        options.integrated.views        = this.settings.viewpath + '/sketchpad';
        options.application.views       = this.settings.viewpath;
        options.integrated.namespace    = this.settings.namespace;
        options.application.namespace   = this.settings.namespace;
    },

    ready()
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
	    getOptions()
        {
            return options;
        },

		getPlaceholder(key)
		{
			return options[this.type][key];
		},

        getClass(key, required)
        {
            var value = String(this.options[key]).replace(/^[\s\/]+|\s\/+$/g, '');
            return {
                'form-group'    :true,
                'has-error'     :required ? value == '' : false
            };
        },

        updateHints:function()
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
                assets		:path('public/' + options.assets),
                route		:options.route,
                namespace	:options.namespace,
                basedir     :path(options.basedir)
            };
        },

        checkAutoloader()
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

        submit(resolve, reject = reject)
        {
            if(this.isValid)
            {
                var options = this.cleanOptions;
                jQuery.post('/sketchpad/:setup', options, function(res){
                    console.log('options saved: ', res);
                    this.isComplete = true;
                    resolve('Config updated OK!');
                }).fail(reject);
            }
            else
            {
                this.isComplete = false;
                reject('All fields are required!');
            }
        }

	},

	watch:
	{
		type(value)
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
            handler()
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