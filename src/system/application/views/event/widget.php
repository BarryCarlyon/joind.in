<a href="" id="woo">eeeeee</a>

<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#woo').click(function(e) {
        e.preventDefault();
//        markAttending(this, 1, false);
        markAttendingB(this, 1);
    });
});

function markAttendingB(el, eid) {
    console.log('b ' + eid);
    window.open('/event/widget/pending/', 'joind_api_login', 'height=200,width=400,location=1');
    jQuery.ajax({
        type: 'POST',
        url: 'http://api.joind.local/v2.1/events/' + eid + '/attending/',
        contentType: 'application/json',

        dataType: 'jsonp',
        crossDomain: true,

        success: function(rdata) {
            console.log(rdata);
//            window.close('joind_api_login');
        }, error: function (xhr) {
//            window.close('joind_api_login');
//            console.log(xhr);
//            console.log(xhr.responseText);
            // pop a dialog to login
            window.open('/event/widget/login/', 'joind_api_login', 'height=200,width=400');
        }
    });
}

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
</script>
