const apiBase = globalThis.SDV_API_URL ?? 'https://localhost:7080';
document.querySelector('#login-form').addEventListener('submit', async event => {
  event.preventDefault();
  const message = document.querySelector('#message');
  message.textContent = '';
  try {
    const response = await fetch(`${apiBase}/api/v1/auth/login`, { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ username:document.querySelector('#username').value, password:document.querySelector('#password').value }) });
    if (!response.ok) throw new Error('Usuario o contraseña incorrectos.');
    const session = await response.json();
    sessionStorage.setItem('sdv.accessToken', session.accessToken);
    message.style.color = '#83e6ad'; message.textContent = `Bienvenido, ${session.user.displayName}`;
  } catch (error) { message.style.color = '#ff9b9b'; message.textContent = error.message; }
});
