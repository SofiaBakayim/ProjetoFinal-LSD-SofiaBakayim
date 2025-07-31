// Funções essenciais para o sidebar e navegação Chronos. NÃO REMOVER!
function openSidebar() {
  document.getElementById('sidebar').classList.add('open');
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
}
(function() {
  function scrollToUploadAndCloseSidebar() {
    var uploadSection = document.getElementById('upload-section');
    if (uploadSection) {
      uploadSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
      window.location.href = 'home.html#upload-section';
    }
    closeSidebar();
  }
  document.addEventListener('DOMContentLoaded', function() {
    var idBtn = document.getElementById('idBtn');
    var sidebarIdBtn = document.getElementById('sidebarIdBtn');
    if (idBtn) idBtn.onclick = scrollToUploadAndCloseSidebar;
    if (sidebarIdBtn) sidebarIdBtn.onclick = scrollToUploadAndCloseSidebar;

    // --- Upload de fotografia e câmara ---
    var fileInput = document.getElementById('fileInput');
    var preview = document.getElementById('preview');
    var camera = document.getElementById('camera');
    var captureBtn = document.getElementById('captureBtn');
    var openPhotoBtn = document.getElementById('openPhotoBtn');
    var cancelPhotoBtn = document.getElementById('cancelPhotoBtn');
    var stream = null;

    // Upload de fotografia
    if (fileInput) {
      fileInput.addEventListener('change', function(event) {
        var file = event.target.files[0];
        if (file) {
          var reader = new FileReader();
          reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            setTimeout(function() {
              window.location.href = 'objeto.html';
            }, 1200);
          };
          reader.readAsDataURL(file);
        }
      });
    }

    // Abrir câmara
    if (openPhotoBtn) {
      openPhotoBtn.addEventListener('click', function() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
          navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(s) {
              stream = s;
              camera.srcObject = stream;
              camera.style.display = 'block';
              captureBtn.style.display = 'inline-block';
              cancelPhotoBtn.style.display = 'inline-block';
              preview.style.display = 'none';
              openPhotoBtn.style.display = 'none';
            })
            .catch(function(err) {
              alert('Não foi possível aceder à câmara: ' + err);
            });
        }
      });
    }

    // Capturar foto
    if (captureBtn) {
      captureBtn.addEventListener('click', function() {
        var canvas = document.createElement('canvas');
        canvas.width = camera.videoWidth;
        canvas.height = camera.videoHeight;
        var ctx = canvas.getContext('2d');
        ctx.drawImage(camera, 0, 0, canvas.width, canvas.height);
        var dataUrl = canvas.toDataURL('image/png');
        preview.src = dataUrl;
        preview.style.display = 'block';
        camera.style.display = 'none';
        captureBtn.style.display = 'none';
        cancelPhotoBtn.style.display = 'none';
        openPhotoBtn.style.display = 'inline-block';
        if (stream) {
          stream.getTracks().forEach(function(track) { track.stop(); });
        }
        setTimeout(function() {
          window.location.href = 'objeto.html';
        }, 1200);
      });
    }

    // Cancelar foto
    if (cancelPhotoBtn) {
      cancelPhotoBtn.addEventListener('click', function() {
        camera.style.display = 'none';
        captureBtn.style.display = 'none';
        cancelPhotoBtn.style.display = 'none';
        openPhotoBtn.style.display = 'inline-block';
        if (stream) {
          stream.getTracks().forEach(function(track) { track.stop(); });
        }
      });
    }
  });
})();
function goTo(page) {
    console.log('Botão clicado:', page);
    if (page === 'register') {
        window.location.href = 'registar.html';
    } else if (page === 'login') {
        window.location.href = 'login.html';
    }
} 