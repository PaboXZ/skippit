var isMenuOpen = false;
var lastOpenedMenuId = '';
var currentMenuId = '';

function showTaskMenu(task_title)
{
	currentMenuId = 'task-menu-list-' + task_title;
	if(!isMenuOpen)
	{
		document.getElementById(currentMenuId).style.cssText = 'display: block;';
		lastOpenedMenuId = currentMenuId;
		isMenuOpen = true;
	}
	else
	{
		if(lastOpenedMenuId != currentMenuId)
		{
			document.getElementById(lastOpenedMenuId).style.cssText = 'display: none;';
			document.getElementById(currentMenuId).style.cssText = 'display: block;';
			lastOpenedMenuId = currentMenuId;	
		}
		else
		{
			document.getElementById(lastOpenedMenuId).style.cssText = 'display: none;';
			lastOpenedMenuId = '';
			isMenuOpen = false;
		}
	}
}



function deleteTask(task_name, task_id)
{
	showTaskMenu(task_name);
	
	document.getElementById('confirm-action-text').innerHTML = 'Czy chcesz usunąć wpis: "' + task_name + '" Ta operacja jest nieodwracalna.';
	document.getElementById('action-confirm').setAttribute('onclick', 'window.location.href=\'task_delete.php?task_id=' + task_id + '\'');
	
	document.getElementById('confirm-action-box').style.cssText='display: block;';
}