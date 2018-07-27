window.fbAsyncInit = function() {
	FB.init({
		appId      : okay.facebook_id,
		cookie     : true,
		xfbml      : true,
		version    : 'v3.0'
	});
	FB.AppEvents.logPageView();
};
(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "https://connect.facebook.net/ru_RU/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
var googleUser = {};
function googleAuth() {
    gapi.load('auth2', function(){
        auth2 = gapi.auth2.init({
            client_id: $('meta[name=google-signin-client_id]').attr('content'),
            cookiepolicy: '/',
            scope: 'profile'
        });
        attachSignin(document.getElementById('g-signin'));
    });
};
$(function() {
	$_oauth_request = '',
	$_fb_id = '';
	$_fb_access_token = '';
	$_g_access_token = '';
	$('body').on('click', '#facebook', function(){
		checkUserFb();
		return false;
	}).on('submit', 'form[name=oauth]', function(){
		var $_form = $(this).serializeArray(),
			$_data = [];
		$.each($_form, function(i, v) {
			$_data[v.name] = v.value;
		});
		if ($_data['phone'].length) {
			switch ($_oauth_request) {
				case 'facebook':
					checkUserFb($_fb_id, $_fb_access_token, $_data['phone']);
					break;
				case 'google':
					checkUserGoogle($_g_access_token, $_data['phone']);
					break;
			}
		}
		return false;
	});

    googleAuth();
	checkUserFb();
	checkUserGoogle();
});
function checkUserFb(id, access_token, phone) {
	if (id && access_token) {
	    $.ajax({
			type: "POST",
			url: "../ajax/auth.php",
			data: {
		    	type: "facebook",
			    phone: phone,
			    id: id,
				access_token: access_token
			},
			dataType: 'json',
			success: function (r) {
				console.log(r);
		    	if (r.status === 200) {
		        	location.href = 'user';
				} else if (r.status !== 400) {
                    $.fancybox({
                        content: r.error
                    });
				} else {
			    	$_oauth_request = 'facebook';
					$.fancybox({
			        	content: $("#request_oauth").html()
					});
				}
			},
			error: function (r) {
                console.log(r);
			}
		});
	}
	return false;
}
function checkUserGoogle(access_token, phone) {
	if (access_token) {
		$.ajax({
			type: "POST",
			url: "../ajax/auth.php",
			data: {
				type: "google",
				phone: phone,
				access_token: access_token
			},
			dataType: 'json',
			success: function (r) {
				console.log(r);
	            if (r.status === 200) {
					location.href = 'user';
				} else if (r.status !== 400) {
                    $.fancybox({
                        content: r.error
                    });
                } else {
					$_oauth_request = 'google';
					$.fancybox({
						content: $("#request_oauth").html()
					});
				}
			},
			error: function (r) {
                console.log(r);
			}
		});
	}
	return false;
}
function checkLoginState() {
	FB.login(function(r) {
		console.log(r);
		if (r.status === 'connected') {
            $_fb_id = r.authResponse.userID;
            $_fb_access_token = r.authResponse.accessToken;
			checkUserFb($_fb_id, $_fb_access_token);
		}
	}, {scope: 'public_profile,email'});
	FB.logout(function () {});
}
function onSignIn(googleUser) {
	var r = googleUser.getAuthResponse();
	console.log(r);
	if (r.id_token) {
		$_g_access_token = r.id_token;
		checkUserGoogle($_g_access_token);
	}
	signOut();
}
function signOut() {
	var auth2 = gapi.auth2.getAuthInstance();
	auth2.signOut().then(function () {});
}
function attachSignin(element) {
    auth2.attachClickHandler(
    	element, {},
        function(googleUser) {
    		console.log(googleUser);
            onSignIn(googleUser);
        }, function(error) {
            console.log(JSON.stringify(error, undefined, 2));
        }
	);
}