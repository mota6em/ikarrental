const carBackBtn = document.querySelector("#car-back-btn");

carBackBtn.addEventListener("click", () => {
  history.back();
});
