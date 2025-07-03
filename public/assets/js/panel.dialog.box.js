// Thread dialog menu open/close

const addThreadButton = document.getElementById("add-thread-button");
const closeThreadButton = document.getElementById("close-add-thread");

addThreadButton.addEventListener("click", () => {
  showDialogBox("add-thread");
});

closeThreadButton.addEventListener("click", () => {
  closeDialogBox("add-thread");
});

// Task dialog menu open/close

const addTaskButton = document.getElementById("add-task-button");
const closeTaskButton = document.getElementById("close-add-task");

addTaskButton.addEventListener("click", () => {
  showDialogBox("add-task");
});

closeTaskButton.addEventListener("click", () => {
  closeDialogBox("add-task");
});
// Confirm action dialog box close

const declineConfirmActionButton = document.getElementById("action-decline");
const closeConfirmActionButton = document.getElementById(
  "close-confirm-action-button"
);

closeConfirmActionButton.addEventListener("click", () => {
  closeDialogBox("confirm-action-box");
  clearConfirmActionBox();
});
declineConfirmActionButton.addEventListener("click", () => {
  closeDialogBox("confirm-action-box");
  clearConfirmActionBox();
});
