fetch("login.php?username").then(response => {
    if (!response.ok)
        throw new Error();
    return response.text()
}).then(username => {
    document.getElementById('welcome-message').textContent = `Welcome ${username}`;
});
