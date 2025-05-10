const startDate = document.getElementById('startdate');
const endDate = document.getElementById('enddate');

// Set first date's min to 7 days from today
const today = new Date();
const minFirst = new Date(today.setDate(today.getDate() + 7));
startDate.min = minFirst.toISOString().split('T')[0];

// When first date is selected
startDate.addEventListener('change', function () {
    const selectedDate = new Date(this.value);

    if (!isNaN(selectedDate)) {
        const minSecond = new Date(selectedDate);
        const maxSecond = new Date(selectedDate);
        maxSecond.setDate(maxSecond.getDate() + 4);

        endDate.min = minSecond.toISOString().split('T')[0];
        endDate.max = maxSecond.toISOString().split('T')[0];
        endDate.disabled = false;
        endDate.value = ''; // Clear previous value
    } else {
        endDate.disabled = true;
    }
});