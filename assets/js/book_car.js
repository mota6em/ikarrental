const confirmDates = document.querySelector("#confirm-dates");
const selectDateBxBtn = document.querySelector("#select-date-bx-btn");
const carId = confirmDates.getAttribute("data-car-id");
confirmDates.addEventListener("click", function () {
  const startDate = document.querySelector("#startDate").value;
  const endDate = document.querySelector("#endDate").value;
  if (!startDate && !endDate) {
    showAlert("Please select valid start and end dates!", "danger");
    return;
  } else if (!startDate) {
    showAlert("Please select valid START date!", "danger");
    return;
  } else if (!endDate) {
    showAlert("Please select valid END date!", "danger");
    return;
  } else if (!isPeriodAvailable(startDate, endDate, bookedRanges)) {
    showAlert("The selected period overlaps with booked dates!", "danger");
    return;
  }
  fetch("/views/user/book_car.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ startDate, endDate, carId }),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      if (data.success) {
        showAlert(data.message, "success");
      } else {
        showAlert(data.message, "danger");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showAlert("An error occurred!", "danger");
    });
});

function showAlert(message, type = "success") {
  const alertContainer = document.querySelector("#alert-container");

  const alert = document.createElement("div");
  alert.className = `alert alert-${type} alert-dismissible fade show`;
  alert.role = "alert";
  alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
  alertContainer.innerHTML = "";
  alertContainer.appendChild(alert);
  const timeOut = 15000;
  setTimeout(() => {
    alert.classList.remove("show");
    alert.classList.add("hide");
    setTimeout(() => alert.remove(), 300);
  }, timeOut);
}

const bookedRanges = [];
selectDateBxBtn.addEventListener("click", () => {
  fetch("/storage/JSONfiles/bookings.json")
    .then((respond) => {
      if (!respond.ok) {
        throw new Error("ERROR: " + respond.statusText);
      }
      return respond.json();
    })
    .then((data) => {
      const bookingsArray = Object.values(data);
      bookingsArray.forEach((bookedDate) => {
        console.log("Processing booking...");
        if (bookedDate.carId === carId) {
          bookedRanges.push({
            from: bookedDate["startDate"],
            to: bookedDate["endDate"],
          });
        }
      });
      const disabledDates = bookedRanges.map((range) => ({
        from: range.from,
        to: range.to,
      }));

      flatpickr("#startDate", {
        dateFormat: "Y-m-d",
        minDate: "today",
        disable: disabledDates,
        onChange: function (selectedDates, dateStr) {
          endDatePicker.set("minDate", dateStr);
        },
      });

      const endDatePicker = flatpickr("#endDate", {
        dateFormat: "Y-m-d",
        minDate: "today",
        disable: disabledDates,
      });
    })
    .catch((error) => {
      console.log("Error:", error);
    });
});

function isPeriodAvailable(startDate, endDate, bookedRanges) {
  const userStart = new Date(startDate).getTime();
  const userEnd = new Date(endDate).getTime();
  for (const range of bookedRanges) {
    const bookedStart = new Date(range.from).getTime();
    const bookedEnd = new Date(range.to).getTime();
    if (userStart <= bookedEnd && userEnd >= bookedStart) {
      return false;
    }
  }
  return true;
}
