var isMenuOpen = false;
var lastOpenedMenuId = "";
var currentMenuId = "";

let barrier = document.getElementsByClassName("hidden-barrier")[0];
barrier.addEventListener("click", hideBarrier);

// Tasks menus event listeners
const menuTiles = document.getElementsByClassName("task-title-menu");
for (let tile of menuTiles) {
  let id = tile.id.slice(16);
  tile.addEventListener("click", () => {
    showTaskMenu(id);
  });
}

const deleteTiles = document.getElementsByClassName("delete-task");
for (let tile of deleteTiles) {
  let id = tile.id.slice(12);
  let title = document.getElementById(`task-title-${id}`).innerText;
  tile.addEventListener("click", () => {
    deleteTask(title, id);
  });
}

function showTaskMenu(taskID) {
  currentMenuId = "task-menu-list-" + taskID;
  if (!isMenuOpen) {
    showDialogBox(currentMenuId);
    lastOpenedMenuId = currentMenuId;
    isMenuOpen = true;
    showBarrier();
  } else {
    if (lastOpenedMenuId != currentMenuId) {
      closeDialogBox(lastOpenedMenuId);
      showDialogBox(currentMenuId);
      lastOpenedMenuId = currentMenuId;
      showBarrier();
    } else {
      closeDialogBox(lastOpenedMenuId);
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

  confirmActionDisplay(
    'Delete task  "' + task_name + '"?   This operation is irreversible.',
    () => {
      window.location.href = "/delete-task/" + task_id + "'";
    }
  );
}
