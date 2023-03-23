const titre = document.querySelector(".page_title h1")
const paragraphe = document.querySelector(".page_title p")
const alerte = document.querySelector(".alert")

gsap.from(titre, {x:-80, duration :1.25, opacity:0})
gsap.from(paragraphe, {y:10,duration :0.5, opacity:0, delay:0.25})
gsap.to(alerte, {y:10,duration :0.5, opacity:0, delay:3.25, height:0})

