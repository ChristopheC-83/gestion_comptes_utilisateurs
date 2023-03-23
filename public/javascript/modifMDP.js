const btnModifMDP = document.getElementById("btnModifMDP");
const new_password = document.getElementById("new_password");
const confirm_new_password = document.getElementById("confirm_new_password");
const error = document.getElementById("error");

new_password.addEventListener("keyup", () => {
  verificationPassword();
});
confirm_new_password.addEventListener("keyup", () => {
  verificationPassword();
});

function verificationPassword() {
  if (new_password.value === confirm_new_password.value) {
    btnModifMDP.classList.remove("desactive");
    error.classList.add("dnone")
  }else{
    btnModifMDP.classList.add("desactive");
    error.classList.remove("dnone")
  }
}
