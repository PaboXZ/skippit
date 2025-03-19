function showSideMenu(){
	document.getElementById('mobile-thread-menu-open').classList.add('d-none');
	document.getElementById('mobile-thread-menu-close').classList.remove('d-none');
	
	document.getElementById('sidemenu').classList.remove('d-none');
	
}

function hideSideMenu(){
	document.getElementById('mobile-thread-menu-open').classList.remove('d-none');
	document.getElementById('mobile-thread-menu-close').classList.add('d-none');
	
	document.getElementById('sidemenu').classList.add('d-none');
	
}