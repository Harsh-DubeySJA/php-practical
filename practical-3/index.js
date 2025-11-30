function setupFormHandler(formId) {
    const form = document.getElementById(formId);
    
    const containerId = `${formId}ResultContainer`;
    const resultId = `${formId}Result`;
    const resultContainer = document.getElementById(containerId);
    const resultDisplay = document.getElementById(resultId);

    if (!form) {
        console.warn(`Skipping handler setup for missing form ID: ${formId}`);
        return;
    }
    
    if (!resultContainer || !resultDisplay) {
         console.warn(`Missing result container/display elements for form ID: ${formId}. Please add <p id="${containerId}">...</p> and <span id="${resultId}"></span> to HTML.`);
    }

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        if (resultDisplay && resultContainer) {
            resultDisplay.textContent = 'Processing...';
            resultContainer.style.display = 'block'; 
        }

        const formData = new FormData(form);
        
        fetch(form.action, {
            method: form.method,
            body: formData
        }).then(response => {
            if (response.status !== 200) {
                return response.text().then(errorText => {
                    throw new Error(errorText);
                });
            }
            return response.text();
        }).then(data => {
            if (resultDisplay) {
                resultDisplay.textContent = data;
            }
        }).catch(error => {
            if (resultDisplay) {
                resultDisplay.textContent = `Error: ${error.message}`;
            }
            console.error(`Fetch Error for ${formId}:`, error);
        });
    });
}

setupFormHandler('length');
setupFormHandler('substringInput');
setupFormHandler('replaceString');
setupFormHandler('space');
setupFormHandler('validString');
setupFormHandler('title');
