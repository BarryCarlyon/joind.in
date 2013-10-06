<?php
    header("Content-type: text/javascript; charset=utf8");
    header("Cache-control: public, max-age=10000");
    header("Expires: " . date(DATE_RFC822,strtotime(" 2 day")));
    define('BASEPATH', 'something');
    $config_filename = dirname(__FILE__).'/../system/application/config/config.php';
    if (is_readable($config_filename)) {
        require($config_filename);
        $siteBase = $config['base_url'];
        $apiBase  = $config['api_base_url'];
    } else {
        $siteBase = '//joind.in';
        $apiBase  = '//api.joind.in';
    }
?>
var joindin_attending = function(){};

joindin_attending.urlBase_api     = "<?php echo $apiBase; ?>";

joindin_attending.embedStyle      = true;

// data collectors
joindin_attending.eventId     = null;
joindin_attending.attending   = false;

joindin_attending.draw = function(eventId, node) {
    if (!node) {
        var rndm = parseInt(Math.random() * 9999999);
        document.write('<div id="joindin-content-placeholder-' + rndm + '"></div>');
        node = document.getElementById("joindin-content-placeholder-" + rndm);
    } else if (typeof node == "string") {
        node = document.getElementById(node);
    }
    if (typeof jQuery == 'undefined') {
        // TODO: Attempt to auto-load jQuery, then relaunch the widget when jQuery is available
        if (typeof console.log != "undefined") {
            console.log("No jQuery available - not proceeding with joind.in widget");
            return;
        }
    }
    joindin_attending.eventId = eventId;

    // finished init

    joindin_attending.renderWidget(node);

    return;
    // grab some data
    jQuery.ajax({
        url: joindin_speaker.urlBase_api + 'v2.1/users/' + userId + '/talks?format=json',
        dataType: 'jsonp',
        success: function(json){
            joindin_attending.gotData(json, node, eventId);
        }
    });
}

joindin_attending.gotData = function(json, node, $eventId) {

}
joindin_attending.renderWidget = function(node) {
    var content = "";

    content += "<div class='joindin-content-insert'>";

    content += '<div class="joindin-content-insert-past">';
    content += 'woo';
    content += '</div>';

    jQuery(node).append(content);
}
<?php
exit;

//.
?>
function apiRequest(rtype,raction,data,callback){
    var xml_str='';
    $.each(data,function(k,v){
        xml_str+='<'+k+'>'+v+'</'+k+'>';
    });
    xml_str='<request><action type="'+raction+'" output="json">'+xml_str+'</action></request>';
    var gt_url="/api/"+rtype+'?reqk='+reqk+'&seck='+seck;

    $.ajax({
        type: "POST",
        url : gt_url,
        data: xml_str,
        contentType: "text/xml",
        processData: false,
        dataType: 'json',
        success: function(rdata){
            //notifications.alert(rdata);
            //obj=eval('('+rdata+')'); //notifications.alert(obj.msg);
            /* rdata should be json now ... parsed properly by the browser */
            var obj = rdata;

            //check for the redirect
            if(obj.msg && obj.msg.match('redirect:')){
                var targetLocation=obj.msg.replace(/redirect:/,'');
                document.location.href=targetLocation;
            }else{
                //maybe add some callback method here
                //notifications.alert('normal');
                if ($.isFunction(callback))
                    callback(obj);
            }
        }

    });
}
function markAttending(el,eid,isPast){
    var $loading;
    var $el = $(el);
    if (!$el.next().is('.loading')) {
        $loading = $('<span class="loading">Loading...</span>');
        var pos = $el.position();
        $loading.css({left: pos.left + 15, top: pos.top - 30}).hide();
        $el.after($loading);
        $loading.fadeIn('fast');
    }

    var obj=new Object();
    obj.eid=eid;

    apiRequest('event','attend',obj, function(obj) {
        if ($el.is('.btn-success')) {
            $el.removeClass('btn-success');
            link_txt=isPast ? 'I attended' : 'I\'m attending';
            adjustAttendCount(eid, -1);
        } else {
            $el.addClass('btn-success');
            link_txt=isPast ? 'I attended' : 'I\'m attending';
            adjustAttendCount(eid, 1);
        }

        $el.html(link_txt);

        function hideLoading()
        {
            if ($loading)
                $loading.addClass('loading-complete').html('Thanks for letting us know!').pause(1500).fadeOut(function() { $(this).remove() });
        }

        if ($('#attendees').length == 0 || $('#attendees').is(':hidden')) {
            $('#attendees').data('loaded', false);
            hideLoading();
        } else {
            $('#attendees').load('/event/attendees/' + eid, function() {
                hideLoading()
            });
        }
    });

    return false;
}
