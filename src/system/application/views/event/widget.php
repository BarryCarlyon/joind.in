<?php

$autofire = false;
if ($user_id) {
//    $autofire = true;
}

//<h3><?php echo $user_id; ? ></h3>
?>

<a href="" id="woo">eeeeee</a>
<pre id="pre"></pre>

<script type="text/javascript">
var autofire = false;
jQuery(document).ready(function() {
    jQuery('#woo').click(function(e) {
        e.preventDefault();
//        markAttending(this, 1, false);
        markAttendingB(this, <?php echo $event_id; ?>);
    });
    <?php
        if ($autofire) {
            ?>
autofire = true;
jQuery('#woo').trigger('click');
            <?php
        }
    ?>
});

function markAttendingB(el, eid) {
//    console.log('b ' + eid);
//    window.open('/event/widget/pending/', 'joind_api_login', 'height=200,width=400');//,location=1');
    if (!autofire) {
//        window.open('/event/widget/pending/', 'joind_api_login', 'height=200,width=400,location=1');
    }
    jQuery.ajax({
        type: 'POST',
        url: 'http://api.joind.local/v2.1/events/' + eid + '/attending/',
//        contentType: 'application/json',

        dataType: 'jsonp',
        crossDomain: true,

//        headers: {
//            "Authorization":"OAuth <?php echo $oauth; ?>",
//            "Content-Type":"application/json"
//        },

        beforeSend: function(xhrObj){
            xhrObj.setRequestHeader("Content-Type","application/json");
            xhrObj.setRequestHeader("Accept","application/json");
            xhrObj.setRequestHeader("Authorization","OAuth <?php echo $oauth; ?>");
//            xhrObj.setRequestHeader("Authorization","OAuth <?php echo $oauth; ?>");
        },

        success: function(rdata) {
            alert('yay');
//            alert('fox');
//            console.log('woo');
//            window.close('joind_api_login');
        }, error: function (xhr) {
            alert('fail');
//            window.close('joind_api_login');
            console.log(xhr);
            jQuery('#pre').html(xhr.responseText);
//            console.log(xhr.responseText);
            // pop a dialog to login
            var packet = {
                "eid": eid,
                "task": "attending"
            };
            packet = JSON.stringify(packet);

//            window.open('/event/widget/login/', 'joind_api_login', 'height=200,width=400');
//            window.open('/user/widget_login/', 'joind_api_login', 'height=200,width=400');
return;
            if (!autofire) {
                window.open('/user/oauth_widget_allow'
                    + '?api_key=37fd13205d81994000c603308201d1'
                    + '&callback=/event/widget/return/'
                    + '&state=' + packet,
                    'joind_api_login', 'height=200,width=400');
            }
        }, complete: function(xhr) {
            //http://stackoverflow.com/questions/2233553/data-inserted-successful-but-jquery-still-returning-error
             if (xhr.readyState == 4) {
                if (xhr.status == 201) {
                    alert('201');
                } else {
                    alert(xhr.status);
                }
             }
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
