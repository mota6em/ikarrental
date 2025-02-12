const bxMoon = document.querySelector("#bx-moon");
const bxSun = document.querySelector("#bx-sun");

const savedMode = localStorage.getItem("theme");
if (savedMode) {
  if (savedMode === "dark-mode") {
    applyDarkMode();
  }
}

bxMoon.addEventListener("click", () => {
  localStorage.setItem("theme", "dark-mode");
  applyDarkMode();
  console.log(localStorage.getItem("theme"));
});

bxSun.addEventListener("click", () => {
  localStorage.setItem("theme", "light-mode");
  applyLightMode();
});

function applyLightMode() {
  bxMoon.style.display = "flex";
  bxSun.style.display = "none";
  document.querySelectorAll(".bg-d").forEach((e) => {
    e.classList.remove("bg-d");
    e.classList.add("bg-w");
  });
  document.querySelectorAll(".bg-b").forEach((e) => {
    e.classList.remove("bg-b");
    e.classList.add("bg-d");
  });
  document.querySelectorAll(".card-black").forEach((e) => {
    e.classList.remove("card-black");
    e.classList.add("card-light");
  });
  document.querySelectorAll(".txt-w").forEach((e) => {
    e.classList.remove("txt-w");
    e.classList.add("txt-b");
  });
  document.querySelectorAll(".txt-gd").forEach((e) => {
    e.classList.remove("txt-gd");
    e.classList.add("txt-g");
  });
  console.log("LightModeApplied");
}

function applyDarkMode() {
  bxSun.style.display = "flex";
  bxMoon.style.display = "none";
  document.querySelectorAll(".bg-d").forEach((e) => {
    e.classList.remove("bg-d");
    e.classList.add("bg-b");
  });
  document.querySelectorAll(".bg-w").forEach((e) => {
    e.classList.remove("bg-w");
    e.classList.add("bg-d");
  });

  document.querySelectorAll(".card-light").forEach((e) => {
    e.classList.remove("card-light");
    e.classList.add("card-black");
  });

  document.querySelectorAll(".txt-b").forEach((e) => {
    e.classList.remove("txt-b");
    e.classList.add("txt-w");
  });
  document.querySelectorAll(".txt-g").forEach((e) => {
    e.classList.remove("txt-g");
    e.classList.add("txt-gd");
  });
  console.log("darkmodapplied");
}

let backToTopButton = document.querySelector("#backToTop");

window.onscroll = function () {
  if (
    document.body.scrollTop > 100 ||
    document.documentElement.scrollTop > 100
  ) {
    backToTopButton.style.display = "block";
  } else {
    backToTopButton.style.display = "none";
  }
};

backToTopButton.addEventListener("click", () => {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
});
