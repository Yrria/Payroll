function fetchSalaryData() {
    const year = document.getElementById('yearFilter').value;
    const month = document.getElementById('monthFilter').value;
    const cutoff = document.getElementById('cutoffFilter').value;
    const status = document.getElementById('statusFilter').value;

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `fetch_salary.php?year=${year}&month=${month}&cutoff=${cutoff}&status=${status}`, true);
    xhr.onload = function () {
        if (this.status === 200) {
            document.getElementById('salaryData').innerHTML = this.responseText;
        }
    };
    xhr.send();
}

// Attach change events
['yearFilter', 'monthFilter', 'cutoffFilter', 'statusFilter'].forEach(id => {
    document.getElementById(id).addEventListener('change', fetchSalaryData);
});

// Initial load
window.onload = fetchSalaryData;

// Details
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('#salaryData').addEventListener('click', async (e) => {
        const viewBtn = e.target.closest('button#viewBtn');
        if (viewBtn) {
            const row = viewBtn.closest('tr');
            const year = row.children[0].textContent;
            const month = row.children[1].textContent;
            const cutoff = row.children[2].textContent;

            const overlay = document.querySelector('#overlay');
            const infoContainer = document.querySelector('.info-container');

            const response = await fetch(`salary_details.php?year=${year}&month=${month}&cutoff=${cutoff}`);
            const html = await response.text();

            infoContainer.innerHTML = html;
            overlay.style.display = 'flex'; // Show overlay
        }
    });
});

