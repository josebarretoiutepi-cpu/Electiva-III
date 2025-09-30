document.getElementById('registroForm').addEventListener('submit', function(event) {
  event.preventDefault();

  const nombre = document.getElementById('nombre').value.trim();
  const email = document.getElementById('email').value.trim();
  const contrasena = document.getElementById('contrasena').value;

  const mensajeDiv = document.getElementById('mensaje');

  // Para ejemplo simple vamos a simular una "base de datos" con localStorage
  let usuarios = JSON.parse(localStorage.getItem('usuarios')) || [];

  // Verificar si email ya registrado
  if (usuarios.find(u => u.email === email)) {
    mensajeDiv.textContent = 'Este email ya está registrado.';
    mensajeDiv.style.color = 'red';
    return;
  }

  // Si no registrado, guardamos datos
  usuarios.push({nombre, email, contrasena});
  localStorage.setItem('usuarios', JSON.stringify(usuarios));

  mensajeDiv.textContent = '¡Registro finalizado con éxito!';
  mensajeDiv.style.color = 'green';

  // Opcional: limpiar formulario
  this.reset();
});