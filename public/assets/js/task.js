var isMenuOpen = false;
var lastOpenedMenuId = "";
var currentMenuId = "";
let barrier = document.getElementsByClassName("hidden-barrier")[0];
barrier.addEventListener("click", hideBarrier);

function showTaskMenu(taskID) {
  currentMenuId = "task-menu-list-" + taskID;
  if (!isMenuOpen) {
    document.getElementById(currentMenuId).style.cssText = "display: block;";
    lastOpenedMenuId = currentMenuId;
    isMenuOpen = true;
    showBarrier();
  } else {
    if (lastOpenedMenuId != currentMenuId) {
      document.getElementById(lastOpenedMenuId).style.cssText =
        "display: none;";
      document.getElementById(currentMenuId).style.cssText = "display: block;";
      lastOpenedMenuId = currentMenuId;
      showBarrier();
    } else {
      document.getElementById(lastOpenedMenuId).style.cssText =
        "display: none;";
      lastOpenedMenuId = "";
      isMenuOpen = false;
    }
  }
}

function showBarrier() {
  barrier.style.cssText = "display: block;";
}

function hideBarrier() {
  barrier.style.cssText = "display: none;";
  showTaskMenu(currentMenuId.slice(15));
}

function deleteTask(task_name, task_id) {
  hideBarrier();

  document.getElementById("confirm-action-text").innerHTML =
    'Czy chcesz usunąć wpis: "' +
    task_name +
    '" Ta operacja jest nieodwracalna.';
  document
    .getElementById("action-confirm")
    .setAttribute(
      "onclick",
      "window.location.href='/delete-task/" + task_id + "'"
    );

  document.getElementById("confirm-action-box").style.cssText =
    "display: block;";
}
