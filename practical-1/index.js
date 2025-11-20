const form = document.getElementById('numberForm');
const resultDisplay = document.getElementById('result');
const resultContainer = document.getElementById('reversed'); 

form.addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(form);
    fetch(form.action, {
        method: form.method, 
        body: formData 
    }).then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text(); 
    }).then(data => {
        resultDisplay.textContent = data;
        resultContainer.style.display = 'block'; 
    }).catch(error => {
        resultDisplay.textContent = `Error: ${error.message}`;
        resultContainer.style.display = 'block'; 
        console.error('Fetch error:', error);
    });
});
