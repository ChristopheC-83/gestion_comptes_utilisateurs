// Modif Mail

const btnModifMail = document.querySelector("#btnModifMail");
const btnModifMdp = document.querySelector("#btnModifMdp");
const btnValidationModifMail = document.querySelector(
  "#btnValidationModifMail"
);
const divMail = document.querySelector("#mail");
const divModifMail = document.querySelector("#modificationMail");

btnModifMail.addEventListener("click", () => {
  divMail.style.display = "none";
  btnModifMdp.style.display = "none";
  divModifMail.classList.remove("div_cachee");
  btnSuppCompte.classList.add("dnone");
});


// Suppression Compte

const btnSuppCompte = document.getElementById("btnSuppCompte");
const suppCompte = document.getElementById("suppCompte");

btnSuppCompte.addEventListener("click", ()=>{
  suppCompte.classList.remove("dnone");
})