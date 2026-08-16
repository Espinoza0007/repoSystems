let newWorker;
function Notificacion_Version() {
  let snackbar = document.getElementById('snackbar');
  snackbar.className = 'show';
  $("#notificacion_ac").show();
}
document.getElementById('reload').addEventListener('click', function(){
  console.log('elimando base de datos y actualizando la cache');
  indexedDB.deleteDatabase("DBAppSDV");
  newWorker.postMessage({ action: 'skipWaiting' });
});
if ('serviceWorker' in navigator) {
  window.addEventListener('load', async () => {
    const reg = await navigator.serviceWorker.register('./../sw_sdv.js')
    if (reg.waiting) {
      newWorker = reg.waiting;
      // console.log('2° o mas actualizacion pendiente => '+reg.waiting);
      Notificacion_Version();
    }
    reg.addEventListener('updatefound', () => {
      newWorker = reg.installing;
      // console.log('1° actualizacion pendiente => '+newWorker);
      newWorker.addEventListener('statechange', () => {
        switch (newWorker.state) {
          case 'installed':
              if (navigator.serviceWorker.controller) {
                Notificacion_Version();
              }
          break;
        }
      });
    });
    let refreshing;
    navigator.serviceWorker.addEventListener('controllerchange', function () {
      if (refreshing) return;
      window.location.reload();
      refreshing = true;
    });
  })
}