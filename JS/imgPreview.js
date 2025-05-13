document.addEventListener('DOMContentLoaded', () => {
  const input   = document.getElementById('imgInput');
  const preview = document.querySelector('.imgPreview');
  const error   = document.querySelector('.imgError');
  const MAX_MB  = 2 * 1024 * 1024; // 2 MiB
  
  if (!input || !preview || !error) return;

  input.addEventListener('change', () => {
  const file = input.files[0];

  // 1) Reiniciar estado
  preview.style.display = 'none';
  preview.src           = '';
  error.style.display   = 'none';
  error.textContent     = '';

  if (!file) return;

  // 2) Validar tipo
  if (!file.type.startsWith('image/')) {
    error.textContent   = 'Por favor selecciona una imagen.';
    error.style.display = 'block';
    return;
  }

  // 3) Validar tamaño
  if (file.size > MAX_MB) {
    error.textContent   = 'El archivo supera los 2 MB.';
    error.style.display = 'block';
    return;
  }

  // 4) Leer y mostrar preview
  const reader = new FileReader();
  reader.onload = e => {
    preview.src           = e.target.result;
    preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
  });
});
  