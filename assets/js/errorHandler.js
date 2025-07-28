// assets/js/errorHandler.js
// Control global de errores JS para desarrollo

(function() {
  function showError(msg, url, lineNo, columnNo, error) {
    const errorMsg = [
      'Error: ' + msg,
      'Archivo: ' + url,
      'Línea: ' + lineNo + ', Columna: ' + columnNo,
      error ? ('Stack: ' + error.stack) : ''
    ].join('\n');
    if (window.console) {
      console.error(errorMsg);
    }
    // Mostrar en una tarjeta flotante centrada (tipo alerta Bootstrap)
    let alertBox = document.getElementById('globalErrorBox');
    if (!alertBox) {
      alertBox = document.createElement('div');
      alertBox.id = 'globalErrorBox';
      alertBox.style.position = 'fixed';
      alertBox.style.top = '30px';
      alertBox.style.left = '50%';
      alertBox.style.transform = 'translateX(-50%)';
      alertBox.style.minWidth = '320px';
      alertBox.style.maxWidth = '90vw';
      alertBox.style.background = '#fff';
      alertBox.style.color = '#dc3545';
      alertBox.style.border = '1.5px solid #dc3545';
      alertBox.style.boxShadow = '0 4px 16px rgba(0,0,0,0.12)';
      alertBox.style.borderRadius = '12px';
      alertBox.style.padding = '18px 28px 14px 28px';
      alertBox.style.fontSize = '1.1rem';
      alertBox.style.fontFamily = 'system-ui, sans-serif';
      alertBox.style.zIndex = '99999';
      alertBox.style.display = 'flex';
      alertBox.style.alignItems = 'center';
      alertBox.style.gap = '12px';
      alertBox.style.pointerEvents = 'auto';
      alertBox.innerHTML = '<span style="font-size:1.5em;">&#9888;&#65039;</span><span id="globalErrorMsg"></span>';
      document.body.appendChild(alertBox);
      // Cerrar al hacer click
      alertBox.addEventListener('click', function() {
        alertBox.style.display = 'none';
      });
    } else {
      alertBox.style.display = 'flex';
    }
    document.getElementById('globalErrorMsg').textContent = errorMsg;
    // Ocultar tras 8s
    clearTimeout(window._globalErrorTimeout);
    window._globalErrorTimeout = setTimeout(function() {
      alertBox.style.display = 'none';
    }, 8000);
  }

  window.onerror = showError;
  window.addEventListener('unhandledrejection', function(e) {
    showError('Unhandled promise rejection: ' + (e.reason && e.reason.message ? e.reason.message : e.reason), '', 0, 0, e.reason);
  });
})();
