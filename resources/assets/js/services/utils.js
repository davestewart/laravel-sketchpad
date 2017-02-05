export function scrollTop(callback)
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
