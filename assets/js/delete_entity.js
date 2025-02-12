
const deleteButtons = document.querySelectorAll('.del-btn');
deleteButtons.forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const type = this.getAttribute('data-type');
        fetch('/views/admin/delete_car.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id,type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && type!== 'del-bookings') {
                showAlert(data.message, 'success');
                this.closest('.card').remove(); 
            }else if (data.success && type === 'del-bookings') {
                showAlert(data.message, 'success');
            } 
            else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred!', 'danger');
        });
    });
});


function showAlert(message, type = 'success') {
    const alertContainer = document.querySelector('#alert-container');

    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.role = 'alert';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    alertContainer.appendChild(alert);

    setTimeout(() => {
        alert.classList.remove('show');
        alert.classList.add('hide');
        setTimeout(() => alert.remove(), 300);
    }, 3000);
}

