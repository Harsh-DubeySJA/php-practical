const form = document.getElementById('login');

form.addEventListener('submit', (event) => {
    event.preventDefault();
    const formData = new FormData(form);
    fetch(form.action, {
        method: form.method,
        body: formData
    }).then(response => {
        if (!response.ok)
            throw new Error();
        return response.text();
    }).then(_ => {
        window.location.href = "successful.html";
    }).catch(_ => {
        const resultContainer = document.getElementById('login-issue');
        resultContainer.style.display = 'block';
    })
});
