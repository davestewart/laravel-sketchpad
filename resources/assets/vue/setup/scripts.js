export function scrollTop(callback)
{
    callback = callback || function() { }
    var top  = $(window).scrollTop();
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

export function setup(fsm, system, buttons, step = 'article')
{
    function update()
    {
        // assign state and paused
        $system
            .attr('data-state', fsm.state)
            .toggleClass('paused', fsm.isPaused());

        // assign active class to the current state
        $states
            .removeClass('active')
            .filter('#'  + fsm.state)
            .addClass('active');

        // update buttons
        $buttons
            .each(function(i, e){
                e.disabled = ! fsm.can(e.name) || fsm.isPaused();
            });
    }

    // variables
	window.fsm		= fsm;
    var $system     = $(system),
        $states     = $system.find(step),
        $buttons    = $(buttons).find('button, a');

    // bind button clicks to fsm actions
    $buttons
        .on('click', function (event) {
            fsm.do(event.target.name);
        });

    // update UI when fsm state changes
    fsm.on('update', update);

    // update
    update();
}