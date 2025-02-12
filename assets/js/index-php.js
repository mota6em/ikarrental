const addOneBtn = document.querySelector("#addOne");
const subtractOneBtn = document.querySelector("#subtractOne");
const seatsNum = document.querySelector("#seatsNum");
const resetFilterBtn = document.querySelector("#reset-filter-btn");
const applyFilter = document.querySelector("#apply");
addOneBtn.addEventListener("click", () => {
  let currentValue = Number(seatsNum.value);
  if (currentValue < Number(seatsNum.max)) {
    seatsNum.value = currentValue + 1;
  }
});

subtractOneBtn.addEventListener("click", () => {
  let currentValue = Number(seatsNum.value);
  if (currentValue > Number(seatsNum.min)) {
    seatsNum.value = currentValue - 1;
  }
});

resetFilterBtn.addEventListener("click", () => {
  document.querySelector("#fuelType").value = "all";
  document.querySelector("#transmission").value = "all";
  document.querySelector("#seatsNum").value = 5;
  document.querySelector("#dateFrom").value = "";
  document.querySelector("#dateTo").value = "";
  document.querySelector("#priceFrom").value = 0;
  document.querySelector("#priceTo").value = 150000;
});

// ToDo leter. (Optional => using AJAX while filtering )

// const allSeats = document.querySelector("#allSeats").checked;
// const allDates = document.querySelector("#allDates").checked;
// const dateFrom = document.querySelector("#dateFrom").value;
// const dateTo = document.querySelector("#dateTo").value;
// const transmission = document.querySelector("#transmission").value;
// const fuelType = document.querySelector("#fuelType").value;
// const priceFrom = document.querySelector("#priceFrom").value;
// const priceTo = document.querySelector("#priceTo").value;

// applyFilter.addEventListener("click", () => {
//   fetch('/backend/filter-cars.php', {
//     method : 'POST',
//     headers: {
//       'Content-Type': 'application/json'
//     },
//     body: JSON.stringify([   ])

//   })
// });
