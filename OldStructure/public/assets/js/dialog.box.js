function closeDialogBox(boxId)
{
	document.getElementById(boxId).style.cssText='display: none;';
}
function showDialogBox(boxId)
{
	document.getElementById(boxId).style.cssText='display: block';
}

function clearConfirmActionBox()
{
	document.getElementById('confirm-action-text').innerHTML='';
	document.getElementById('action-confirm').setAttribute('onclick', '');
}

function corfirmActionDisplay(message, target, data)
{
	document.getElementById('confirm-action-text').innerHTML = message;
	document.getElementById('action-confirm').setAttribute('onclick', 'window.location.assign(\'' + target + data + '\')');
	
	document.getElementById('confirm-action-box').style.cssText = 'display: block;';
}