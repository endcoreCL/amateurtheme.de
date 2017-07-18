jQuery(document).ready(function(){		
	jQuery('iframe[src*="youtube"], iframe[src*="vimeo.com"], iframe[src*="google.com/maps"]').wrap('<div class="embed-responsive embed-responsive-16by9" />');
	jQuery('#comments_reply .form-submit input[type="submit"]').addClass('btn btn-dt pull-right').after('<div class="clearfix"></div>');
	
	jQuery('#content p').each(function() {
		var $this = jQuery(this);	
		if($this.html().replace(/\s| /g, '').length == 0) $this.addClass('empty');
	});
});

/**
 * jQuery load
 */
jQuery(window).on('load', function() {
    var isMobile = window.matchMedia("only screen and (max-width: 760px)");
    if(isMobile.matches === false) {
        jQuery('.navbar-5-2-5 .navbar-brand').css('margin-left', (jQuery('.navbar-5-2-5 .navbar-brand').outerWidth() / 2) * -1);
    }
});

/*
 * Social JS
 */
function socialp(elem, m) {
	if (m == 'twitter') {
		var desc = '';
		var el, els = document.getElementsByTagName("meta");
		var i = els.length;
		while (i--) {
			el = els[i];
			if (el.getAttribute("property") == "og:title") {
				desc = el.content;
				break;
			}
		}
		var creator = "";
		if (document.getElementsByName("twitter:creator").length) {
			creator = document.getElementsByName("twitter:creator")[0].content;
		}
		creator = creator.replace('@', '');
		elem.href += "&text=" + desc + "&via=" + creator + "&related=" + creator;
	}

	elem = window.open(elem.href, "Teile diese Seite", "width=600,height=500,resizable=yes");
	elem.moveTo(screen.width / 2 - 300, screen.height / 2 - 450);
	elem.focus()
}

/*
 * Smooth Scroll
 */
jQuery(function() {
	jQuery('a[href*="#"]:not([href="#"], [href*="tab"], [href*="collapse"], [href*="carousel"])').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = jQuery(this.hash);
			target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
			
			if (target.length) {
				jQuery('html,body').animate({
					scrollTop: target.offset().top
				}, 1000);
				return false;
			}
		}
	});
	
	// ToTop Smooth Scroll
	jQuery('a.totop').click(function() {
		jQuery('html,body').animate({
			scrollTop: 0
		}, 1000);
		return false;
	});
});

/*
 * Navigation Dropdown Script
 */
(function($){
	$(document).ready(function(){
		$('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
			event.preventDefault(); 
			event.stopPropagation(); 
			$(this).parent().siblings().removeClass('open');
			$(this).parent().toggleClass('open');
		});
	});
})(jQuery);

/*
 * Carousel Swipe
 */
(function(a){if(typeof define==="function"&&define.amd&&define.amd.jQuery){define(["jquery"],a)}else{a(jQuery)}}(function(f){var p="left",o="right",e="up",x="down",c="in",z="out",m="none",s="auto",l="swipe",t="pinch",A="tap",j="doubletap",b="longtap",y="hold",D="horizontal",u="vertical",i="all",r=10,g="start",k="move",h="end",q="cancel",a="ontouchstart" in window,v=window.navigator.msPointerEnabled&&!window.navigator.pointerEnabled,d=window.navigator.pointerEnabled||window.navigator.msPointerEnabled,B="TouchSwipe";var n={fingers:1,threshold:75,cancelThreshold:null,pinchThreshold:20,maxTimeThreshold:null,fingerReleaseThreshold:250,longTapThreshold:500,doubleTapThreshold:200,swipe:null,swipeLeft:null,swipeRight:null,swipeUp:null,swipeDown:null,swipeStatus:null,pinchIn:null,pinchOut:null,pinchStatus:null,click:null,tap:null,doubleTap:null,longTap:null,hold:null,triggerOnTouchEnd:true,triggerOnTouchLeave:false,allowPageScroll:"auto",fallbackToMouseEvents:true,excludedElements:"label, button, input, select, textarea, a, .noSwipe",preventDefaultEvents:true};f.fn.swipe=function(G){var F=f(this),E=F.data(B);if(E&&typeof G==="string"){if(E[G]){return E[G].apply(this,Array.prototype.slice.call(arguments,1))}else{f.error("Method "+G+" does not exist on jQuery.swipe")}}else{if(!E&&(typeof G==="object"||!G)){return w.apply(this,arguments)}}return F};f.fn.swipe.defaults=n;f.fn.swipe.phases={PHASE_START:g,PHASE_MOVE:k,PHASE_END:h,PHASE_CANCEL:q};f.fn.swipe.directions={LEFT:p,RIGHT:o,UP:e,DOWN:x,IN:c,OUT:z};f.fn.swipe.pageScroll={NONE:m,HORIZONTAL:D,VERTICAL:u,AUTO:s};f.fn.swipe.fingers={ONE:1,TWO:2,THREE:3,ALL:i};function w(E){if(E&&(E.allowPageScroll===undefined&&(E.swipe!==undefined||E.swipeStatus!==undefined))){E.allowPageScroll=m}if(E.click!==undefined&&E.tap===undefined){E.tap=E.click}if(!E){E={}}E=f.extend({},f.fn.swipe.defaults,E);return this.each(function(){var G=f(this);var F=G.data(B);if(!F){F=new C(this,E);G.data(B,F)}})}function C(a4,av){var az=(a||d||!av.fallbackToMouseEvents),J=az?(d?(v?"MSPointerDown":"pointerdown"):"touchstart"):"mousedown",ay=az?(d?(v?"MSPointerMove":"pointermove"):"touchmove"):"mousemove",U=az?(d?(v?"MSPointerUp":"pointerup"):"touchend"):"mouseup",S=az?null:"mouseleave",aD=(d?(v?"MSPointerCancel":"pointercancel"):"touchcancel");var ag=0,aP=null,ab=0,a1=0,aZ=0,G=1,aq=0,aJ=0,M=null;var aR=f(a4);var Z="start";var W=0;var aQ=null;var T=0,a2=0,a5=0,ad=0,N=0;var aW=null,af=null;try{aR.bind(J,aN);aR.bind(aD,a9)}catch(ak){f.error("events not supported "+J+","+aD+" on jQuery.swipe")}this.enable=function(){aR.bind(J,aN);aR.bind(aD,a9);return aR};this.disable=function(){aK();return aR};this.destroy=function(){aK();aR.data(B,null);aR=null};this.option=function(bc,bb){if(av[bc]!==undefined){if(bb===undefined){return av[bc]}else{av[bc]=bb}}else{f.error("Option "+bc+" does not exist on jQuery.swipe.options")}return null};function aN(bd){if(aB()){return}if(f(bd.target).closest(av.excludedElements,aR).length>0){return}var be=bd.originalEvent?bd.originalEvent:bd;var bc,bb=a?be.touches[0]:be;Z=g;if(a){W=be.touches.length}else{bd.preventDefault()}ag=0;aP=null;aJ=null;ab=0;a1=0;aZ=0;G=1;aq=0;aQ=aj();M=aa();R();if(!a||(W===av.fingers||av.fingers===i)||aX()){ai(0,bb);T=at();if(W==2){ai(1,be.touches[1]);a1=aZ=au(aQ[0].start,aQ[1].start)}if(av.swipeStatus||av.pinchStatus){bc=O(be,Z)}}else{bc=false}if(bc===false){Z=q;O(be,Z);return bc}else{if(av.hold){af=setTimeout(f.proxy(function(){aR.trigger("hold",[be.target]);if(av.hold){bc=av.hold.call(aR,be,be.target)}},this),av.longTapThreshold)}ao(true)}return null}function a3(be){var bh=be.originalEvent?be.originalEvent:be;if(Z===h||Z===q||am()){return}var bd,bc=a?bh.touches[0]:bh;var bf=aH(bc);a2=at();if(a){W=bh.touches.length}if(av.hold){clearTimeout(af)}Z=k;if(W==2){if(a1==0){ai(1,bh.touches[1]);a1=aZ=au(aQ[0].start,aQ[1].start)}else{aH(bh.touches[1]);aZ=au(aQ[0].end,aQ[1].end);aJ=ar(aQ[0].end,aQ[1].end)}G=a7(a1,aZ);aq=Math.abs(a1-aZ)}if((W===av.fingers||av.fingers===i)||!a||aX()){aP=aL(bf.start,bf.end);al(be,aP);ag=aS(bf.start,bf.end);ab=aM();aI(aP,ag);if(av.swipeStatus||av.pinchStatus){bd=O(bh,Z)}if(!av.triggerOnTouchEnd||av.triggerOnTouchLeave){var bb=true;if(av.triggerOnTouchLeave){var bg=aY(this);bb=E(bf.end,bg)}if(!av.triggerOnTouchEnd&&bb){Z=aC(k)}else{if(av.triggerOnTouchLeave&&!bb){Z=aC(h)}}if(Z==q||Z==h){O(bh,Z)}}}else{Z=q;O(bh,Z)}if(bd===false){Z=q;O(bh,Z)}}function L(bb){var bc=bb.originalEvent;if(a){if(bc.touches.length>0){F();return true}}if(am()){W=ad}a2=at();ab=aM();if(ba()||!an()){Z=q;O(bc,Z)}else{if(av.triggerOnTouchEnd||(av.triggerOnTouchEnd==false&&Z===k)){bb.preventDefault();Z=h;O(bc,Z)}else{if(!av.triggerOnTouchEnd&&a6()){Z=h;aF(bc,Z,A)}else{if(Z===k){Z=q;O(bc,Z)}}}}ao(false);return null}function a9(){W=0;a2=0;T=0;a1=0;aZ=0;G=1;R();ao(false)}function K(bb){var bc=bb.originalEvent;if(av.triggerOnTouchLeave){Z=aC(h);O(bc,Z)}}function aK(){aR.unbind(J,aN);aR.unbind(aD,a9);aR.unbind(ay,a3);aR.unbind(U,L);if(S){aR.unbind(S,K)}ao(false)}function aC(bf){var be=bf;var bd=aA();var bc=an();var bb=ba();if(!bd||bb){be=q}else{if(bc&&bf==k&&(!av.triggerOnTouchEnd||av.triggerOnTouchLeave)){be=h}else{if(!bc&&bf==h&&av.triggerOnTouchLeave){be=q}}}return be}function O(bd,bb){var bc=undefined;if((I()||V())||(P()||aX())){if(I()||V()){bc=aF(bd,bb,l)}if((P()||aX())&&bc!==false){bc=aF(bd,bb,t)}}else{if(aG()&&bc!==false){bc=aF(bd,bb,j)}else{if(ap()&&bc!==false){bc=aF(bd,bb,b)}else{if(ah()&&bc!==false){bc=aF(bd,bb,A)}}}}if(bb===q){a9(bd)}if(bb===h){if(a){if(bd.touches.length==0){a9(bd)}}else{a9(bd)}}return bc}function aF(be,bb,bd){var bc=undefined;if(bd==l){aR.trigger("swipeStatus",[bb,aP||null,ag||0,ab||0,W,aQ]);if(av.swipeStatus){bc=av.swipeStatus.call(aR,be,bb,aP||null,ag||0,ab||0,W,aQ);if(bc===false){return false}}if(bb==h&&aV()){aR.trigger("swipe",[aP,ag,ab,W,aQ]);if(av.swipe){bc=av.swipe.call(aR,be,aP,ag,ab,W,aQ);if(bc===false){return false}}switch(aP){case p:aR.trigger("swipeLeft",[aP,ag,ab,W,aQ]);if(av.swipeLeft){bc=av.swipeLeft.call(aR,be,aP,ag,ab,W,aQ)}break;case o:aR.trigger("swipeRight",[aP,ag,ab,W,aQ]);if(av.swipeRight){bc=av.swipeRight.call(aR,be,aP,ag,ab,W,aQ)}break;case e:aR.trigger("swipeUp",[aP,ag,ab,W,aQ]);if(av.swipeUp){bc=av.swipeUp.call(aR,be,aP,ag,ab,W,aQ)}break;case x:aR.trigger("swipeDown",[aP,ag,ab,W,aQ]);if(av.swipeDown){bc=av.swipeDown.call(aR,be,aP,ag,ab,W,aQ)}break}}}if(bd==t){aR.trigger("pinchStatus",[bb,aJ||null,aq||0,ab||0,W,G,aQ]);if(av.pinchStatus){bc=av.pinchStatus.call(aR,be,bb,aJ||null,aq||0,ab||0,W,G,aQ);if(bc===false){return false}}if(bb==h&&a8()){switch(aJ){case c:aR.trigger("pinchIn",[aJ||null,aq||0,ab||0,W,G,aQ]);if(av.pinchIn){bc=av.pinchIn.call(aR,be,aJ||null,aq||0,ab||0,W,G,aQ)}break;case z:aR.trigger("pinchOut",[aJ||null,aq||0,ab||0,W,G,aQ]);if(av.pinchOut){bc=av.pinchOut.call(aR,be,aJ||null,aq||0,ab||0,W,G,aQ)}break}}}if(bd==A){if(bb===q||bb===h){clearTimeout(aW);clearTimeout(af);if(Y()&&!H()){N=at();aW=setTimeout(f.proxy(function(){N=null;aR.trigger("tap",[be.target]);if(av.tap){bc=av.tap.call(aR,be,be.target)}},this),av.doubleTapThreshold)}else{N=null;aR.trigger("tap",[be.target]);if(av.tap){bc=av.tap.call(aR,be,be.target)}}}}else{if(bd==j){if(bb===q||bb===h){clearTimeout(aW);N=null;aR.trigger("doubletap",[be.target]);if(av.doubleTap){bc=av.doubleTap.call(aR,be,be.target)}}}else{if(bd==b){if(bb===q||bb===h){clearTimeout(aW);N=null;aR.trigger("longtap",[be.target]);if(av.longTap){bc=av.longTap.call(aR,be,be.target)}}}}}return bc}function an(){var bb=true;if(av.threshold!==null){bb=ag>=av.threshold}return bb}function ba(){var bb=false;if(av.cancelThreshold!==null&&aP!==null){bb=(aT(aP)-ag)>=av.cancelThreshold}return bb}function ae(){if(av.pinchThreshold!==null){return aq>=av.pinchThreshold}return true}function aA(){var bb;if(av.maxTimeThreshold){if(ab>=av.maxTimeThreshold){bb=false}else{bb=true}}else{bb=true}return bb}function al(bb,bc){if(av.preventDefaultEvents===false){return}if(av.allowPageScroll===m){bb.preventDefault()}else{var bd=av.allowPageScroll===s;switch(bc){case p:if((av.swipeLeft&&bd)||(!bd&&av.allowPageScroll!=D)){bb.preventDefault()}break;case o:if((av.swipeRight&&bd)||(!bd&&av.allowPageScroll!=D)){bb.preventDefault()}break;case e:if((av.swipeUp&&bd)||(!bd&&av.allowPageScroll!=u)){bb.preventDefault()}break;case x:if((av.swipeDown&&bd)||(!bd&&av.allowPageScroll!=u)){bb.preventDefault()}break}}}function a8(){var bc=aO();var bb=X();var bd=ae();return bc&&bb&&bd}function aX(){return !!(av.pinchStatus||av.pinchIn||av.pinchOut)}function P(){return !!(a8()&&aX())}function aV(){var be=aA();var bg=an();var bd=aO();var bb=X();var bc=ba();var bf=!bc&&bb&&bd&&bg&&be;return bf}function V(){return !!(av.swipe||av.swipeStatus||av.swipeLeft||av.swipeRight||av.swipeUp||av.swipeDown)}function I(){return !!(aV()&&V())}function aO(){return((W===av.fingers||av.fingers===i)||!a)}function X(){return aQ[0].end.x!==0}function a6(){return !!(av.tap)}function Y(){return !!(av.doubleTap)}function aU(){return !!(av.longTap)}function Q(){if(N==null){return false}var bb=at();return(Y()&&((bb-N)<=av.doubleTapThreshold))}function H(){return Q()}function ax(){return((W===1||!a)&&(isNaN(ag)||ag<av.threshold))}function a0(){return((ab>av.longTapThreshold)&&(ag<r))}function ah(){return !!(ax()&&a6())}function aG(){return !!(Q()&&Y())}function ap(){return !!(a0()&&aU())}function F(){a5=at();ad=event.touches.length+1}function R(){a5=0;ad=0}function am(){var bb=false;if(a5){var bc=at()-a5;if(bc<=av.fingerReleaseThreshold){bb=true}}return bb}function aB(){return !!(aR.data(B+"_intouch")===true)}function ao(bb){if(bb===true){aR.bind(ay,a3);aR.bind(U,L);if(S){aR.bind(S,K)}}else{aR.unbind(ay,a3,false);aR.unbind(U,L,false);if(S){aR.unbind(S,K,false)}}aR.data(B+"_intouch",bb===true)}function ai(bc,bb){var bd=bb.identifier!==undefined?bb.identifier:0;aQ[bc].identifier=bd;aQ[bc].start.x=aQ[bc].end.x=bb.pageX||bb.clientX;aQ[bc].start.y=aQ[bc].end.y=bb.pageY||bb.clientY;return aQ[bc]}function aH(bb){var bd=bb.identifier!==undefined?bb.identifier:0;var bc=ac(bd);bc.end.x=bb.pageX||bb.clientX;bc.end.y=bb.pageY||bb.clientY;return bc}function ac(bc){for(var bb=0;bb<aQ.length;bb++){if(aQ[bb].identifier==bc){return aQ[bb]}}}function aj(){var bb=[];for(var bc=0;bc<=5;bc++){bb.push({start:{x:0,y:0},end:{x:0,y:0},identifier:0})}return bb}function aI(bb,bc){bc=Math.max(bc,aT(bb));M[bb].distance=bc}function aT(bb){if(M[bb]){return M[bb].distance}return undefined}function aa(){var bb={};bb[p]=aw(p);bb[o]=aw(o);bb[e]=aw(e);bb[x]=aw(x);return bb}function aw(bb){return{direction:bb,distance:0}}function aM(){return a2-T}function au(be,bd){var bc=Math.abs(be.x-bd.x);var bb=Math.abs(be.y-bd.y);return Math.round(Math.sqrt(bc*bc+bb*bb))}function a7(bb,bc){var bd=(bc/bb)*1;return bd.toFixed(2)}function ar(){if(G<1){return z}else{return c}}function aS(bc,bb){return Math.round(Math.sqrt(Math.pow(bb.x-bc.x,2)+Math.pow(bb.y-bc.y,2)))}function aE(be,bc){var bb=be.x-bc.x;var bg=bc.y-be.y;var bd=Math.atan2(bg,bb);var bf=Math.round(bd*180/Math.PI);if(bf<0){bf=360-Math.abs(bf)}return bf}function aL(bc,bb){var bd=aE(bc,bb);if((bd<=45)&&(bd>=0)){return p}else{if((bd<=360)&&(bd>=315)){return p}else{if((bd>=135)&&(bd<=225)){return o}else{if((bd>45)&&(bd<135)){return x}else{return e}}}}}function at(){var bb=new Date();return bb.getTime()}function aY(bb){bb=f(bb);var bd=bb.offset();var bc={left:bd.left,right:bd.left+bb.outerWidth(),top:bd.top,bottom:bd.top+bb.outerHeight()};return bc}function E(bb,bc){return(bb.x>bc.left&&bb.x<bc.right&&bb.y>bc.top&&bb.y<bc.bottom)}}}));
jQuery('.carousel').swipe({
	swipeLeft:function(event, direction, distance, duration, fingerCount) {
		jQuery(this).carousel('next');    
	},
	swipeRight:function(event, direction, distance, duration, fingerCount) {
		jQuery(this).carousel('prev');  
	},
	threshold:0
});

/*
 * Lustagenten SIGNUP FORM
 */
function signUpResult(result) {
    switch (result) {
        case 0:
            if (typeof(disableUnloadMessage) == 'function') {
                disableUnloadMessage();
            }
            var current_form = jQuery('form.lustagenten.current');
            var username = jQuery(current_form).find('#username').val();
            var email = jQuery(current_form).find('#email').val();
            var redirect_after = jQuery(current_form).find('input[name="redirect-after"]').val();
            
            jQuery.ajax(
                {
                    url: ajaxurl, type: "GET",
                    data: {username : username, email : email, action : 'reg_report', 'portal' : 'lustagenten'}
                }
            ).done(function( data ) {
                jQuery(".form-msg").html("<span class=\"form-ok\"><i class=\"fa fa-check\"></i> Dein Account wurde erstellt.</span>");
                if(redirect_after.length > 0) {
                    document.location.href = redirect_after;
                    return false;
                } else {
                    jQuery(current_form).submit();
                }
            });
            break;
        case 1:
            jQuery(".form-msg").html("<span class=\"form-error\"><i class=\"fa fa-times\"></i> Username ist bereits vergeben</span>");
            jQuery(form).removClass('current');
            break;
        case 2:
            jQuery(".form-msg").html("<span class=\"form-error\"><i class=\"fa fa-times\"></i> Email ist bereits vergeben</span>");
            jQuery(form).removClass('current');
            break;
        case 3:
        default:
            jQuery(".form-msg").html("<span class=\"form-error\"><i class=\"fa fa-times\"></i> Weder diese Email noch der Username sind verfügbar</span>");
            jQuery(form).removClass('current');
            break;
    }
}

jQuery(".lustagenten #form-submit").click(function(e) {
    var form = jQuery(this).closest('form');
    var postData = jQuery(form).serializeArray();
    var url = jQuery(form).attr("data-validate");
    var nameVal = jQuery(form).find('#username').val();
    var emailVal = jQuery(form).find('#email').val();

    if (nameVal === null || nameVal === "" || nameVal.length < 6) {
        jQuery(".form-msg").html("<span class=\"form-error\"><i class=\"fa fa-times\"></i> Bitte gib deinen Namen an. (mind. 6 Zeichen, keine Sonderzeichen)</span>");
        return false;
    }

    //regEX match
    if (/[^a-zA-Z0-9]/.test(nameVal)) {
        jQuery(".form-msg").html("<span class=\"form-error\"><i class=\"fa fa-times\"></i> Es sind keine Sonderzeichen im Username erlaubt!</span>");
        return false;
    }

    var atPos = emailVal.indexOf("@");
    var dotPos = emailVal.lastIndexOf(".");
    if (atPos < 1 || dotPos < atPos + 2 || dotPos + 2 >= emailVal.length) {
        jQuery(".form-msg").html("<span class=\"form-error\"><i class=\"fa fa-times\"></i> Bitte gib deine Email an.</span>");
        return false;
    }

    //regEX match
    var reg = emailVal.match(/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|rocks|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i);
    if (!reg) {
        jQuery(".form-msg").html("<span class=\"form-error\"><i class=\"fa fa-times\"></i> Ungültige Email!</span>");
        return false;
    }

    jQuery(form).addClass('current');

    var su = document.createElement('script');
    su.type = 'text/javascript';
    su.async = false;
    su.src = url + '?user_name=' + nameVal + '&user_email=' + emailVal;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(su, s);

    e.preventDefault();
});

/*
 * AUTOCOMPLETE
 */
jQuery(document).ready(function(){
    jQuery('form#quicksearch input#city').typeahead({
        source : function(query, process) {
            jQuery.ajax({
                url : ajaxurl,
                type : 'GET',
                data : {
                    "q" : query,
                    "action" : "get_city_autocomplete"
                },
                dataType : 'json',
                success : function(json) {
                    //console.log(json);
                    process(json);
                }
            });
        }
    });
});

jQuery('#ContactModal').on('show.bs.modal', function (event) {
    var button = jQuery(event.relatedTarget);
    var parent = jQuery(button).closest('.contact');

    var contact_image = jQuery(parent).find('.contact-image').attr('src');
    var contact_name = jQuery(parent).find('.contact-name').html();
    var contact_city = jQuery(parent).find('.contact-city').html();

    jQuery('#ContactModal .media .modal-image').attr('src', contact_image);
    jQuery('#ContactModal .media .modal-subtitle .contact-name').html(contact_name);

    if(contact_city) {
        jQuery('#ContactModal .media .modal-subtitle .contact-city').html(contact_city);
    } else {
        jQuery('#ContactModal .media .modal-subtitle .contact-city-wrapper').hide();
    }

    jQuery('.form-msg').html('');
});

/**
 * Floating Bar
 */
function at_pfb_trigger_scroll() {
    var wintop = jQuery(window).scrollTop(), docheight = jQuery(document).height(), winheight = jQuery(window).height();
    var percScrolled = (wintop/(docheight-winheight))*100;
    var pfbHeight = jQuery(".profile-floating-bar").outerHeight() + 10;
    var scroll_at = jQuery(".profile-floating-bar").data('show-at');

    if(percScrolled >= scroll_at) {
        jQuery(".profile-floating-bar").fadeIn();
        jQuery("body").addClass("pfb-visible");
        jQuery("#footer-bottom").css("margin-bottom", pfbHeight);
    } else {
        jQuery(".profile-floating-bar").fadeOut();
        jQuery("body").removeClass("pfb-visible");
        jQuery("#footer-bottom").css("margin-bottom", "0");
    }
}

var canRun;
jQuery(window).scroll( function(){
    if (canRun) {
        window.clearTimeout(canRun);
    }
    canRun = window.setTimeout(function() {
        at_pfb_trigger_scroll();
    }, 100);
});

jQuery(document).ready(function() {
    if (canRun) {
        window.clearTimeout(canRun);
    }
    canRun = window.setTimeout(function() {
        at_pfb_trigger_scroll();
    }, 100);
});