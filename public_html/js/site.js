(function(){

    app = new App();  
    app.loadVueMessage();
    app.loadVueForms();
    app.loadVueTables();

    $('#side-menu').metisMenu();
    $(".multiselect").multiselect();
    $(".js-input-number").inputNumber();

    //Loads the correct sidebar on window load,
    //collapses the sidebar on window resize.
    // Sets the min-height of #page-wrapper to window size
    $(window).bind("load resize", function() {
        var topOffset = 50;
        var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    // var element = $('ul.nav a').filter(function() {
    //     return this.href == url;
    // }).addClass('active').parent().parent().addClass('in').parent();
    var element = $('ul.nav a').filter(function() {
        return this.href == url;
    }).addClass('active').parent();

    while (true) {
        if (element.is('li')) {
            element = element.parent().addClass('in').parent();
        } else {
            break;
        }
    }
    if($(".js-select2").length>0){
        $(".js-select2").select2();
    }
})();

function getQueryParams(qs) {
    
    qs = qs.split('+').join(' ');
    if(qs.indexOf('?')>=0){
        qs = qs.split('?')[1];
    }
    var params = {},
        tokens,
        re = /[?&]?([^=]+)=([^&]*)/g;

    while (tokens = re.exec(qs)) {
        params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
    }

    return params;
}
function buildQueryParams(params){
    var url = [];
    for(key in params){
        if(params.key!=''){
            url.push(key+'='+params.key);
        }
    }
    return url.join("&");
}


