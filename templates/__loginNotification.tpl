{if $__wcf->user->loginNotification}
	be.bastelstu.wcf.push.onMessage('com.mrk.wcf.loginNotification', function(payload) {
		if (payload.username) {
			new WCF.System.Notification('{lang}wcf.user.option.loginNotification.js{/lang}'.replace('%username%', payload.username), 'info').show();
		}
	});
{/if}
