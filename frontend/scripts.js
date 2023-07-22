let errorShown = false;
document.getElementById('login-form').addEventListener('submit', function(e) {
    e.preventDefault();

    // Obtener los valores de usuario y contraseña
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Enviar una solicitud al backend utilizando Fetch API
    fetch('http://localhost/backend/src/controllers/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ username: username, password: password })
    })
    .then(response => response.json())
    .then(data => {
        // Manejar la respuesta del backend
        if (data.success) {    
            if (data.loggedIn) {
                // Redirigir a la página de bienvenida
                window.location.href = "ruta_de_bienvenida.html";
            } else {
                // Mostrar mensaje de error si este no se muestra ya
                if (!errorShown) {
                    const errorMessage = document.createElement('div');
                    errorMessage.textContent = 'Usuario o contraseña incorrectos';
                    errorMessage.classList.add('error-message');
                    document.getElementById('mensaje').appendChild(errorMessage);
                    // Actualizar la variable para indicar que ya se mostró un mensaje de error
                    errorShown = true;
                }
            }
    
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        // Mostrar mensaje de error si este no se muestra ya
        console.log(error);
        if (!errorShown) {
            const errorMessage = document.createElement('div');
            errorMessage.textContent = 'Ha ocurrido un error con respecto a la base de datos';
            errorMessage.classList.add('error-message');
            document.getElementById('mensaje').appendChild(errorMessage);
            // Actualizar la variable para indicar que ya se mostró un mensaje de error
            errorShown = true;
        }
    });
});