function myFunction(event) {
  let inputField = document.querySelector(".input");

  if (/[0-9+\-*/%.]/.test(event.key)) {
    inputField.value += event.key;
  } else if (event.key === "Backspace" || event.key === "Delete") {
    back();
  } else if (event.key === "Enter") {
    solve();
  } else if (event.key === "c") {
    clr();
  }
}

function solve() {
  let inputField = document.querySelector(".input");
  let expression = inputField.value;

  try {
    let result = new Function('return ' + expression)();
    inputField.value = result;
  } catch (error) {
    inputField.value = "Error";
  }
}

function clr() {
  document.querySelector(".input").value = "";
}

function back() {
  let inputField = document.querySelector(".input");
  inputField.value = inputField.value.slice(0, -1);
}

function dis(val) {
  document.querySelector(".input").value += val;
}