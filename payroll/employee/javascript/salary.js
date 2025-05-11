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
