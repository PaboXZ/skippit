function closeDialogBox(boxId) {
  document.getElementById(boxId).style.cssText = "display: none;";
}
function showDialogBox(boxId) {
  document.getElementById(boxId).style.cssText = "display: block";
}

function clearConfirmActionBox() {
  document.getElementById("confirm-action-text").innerHTML = "";
  const oldElement = document.getElementById("action-confirm");

  const replacement = oldElement.cloneNode(true);
  oldElement.parentNode.replaceChild(replacement, oldElement);
}

function confirmActionDisplay(message, action) {
  document.getElementById("confirm-action-text").innerHTML = message;
  document.getElementById("action-confirm").addEventListener("click", action);
  document.getElementById("confirm-action-box").style.cssText =
    "display: block;";
}
