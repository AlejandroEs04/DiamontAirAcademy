let scannerActive = true;
let currentScanToken = null;

async function handleQRScan(result) {
    if (!scannerActive) return;
    
    const qrData = parseQRData(result);
    if (!qrData) return;

    // Si es el mismo código QR, ignorar
    if (currentScanToken === qrData.token) return;
    
    currentScanToken = qrData.token;
    scannerActive = false; // Pausar el escáner
    
    try {
        const response = await registerAttendance(qrData);
        
        showSuccessMessage(response);
        setTimeout(resetScanner, 5000); // Reactivar después de 5 segundos
        
    } catch (error) {
        showErrorMessage(error);
        resetScanner(); // Reactivar inmediatamente en caso de error
    }
}

// Helper functions
function parseQRData(result) {
    try {
        return JSON.parse(result.text);
    } catch (e) {
        showErrorMessage("QR inválido");
        return null;
    }
}

async function registerAttendance(data) {
    const response = await fetch('/asistencia/registrar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    
    if (!response.ok) {
        const error = await response.json();
        throw new Error(error.message || "Error al registrar");
    }
    
    return response.json();
}

function resetScanner() {
    currentScanToken = null;
    scannerActive = true;
    document.getElementById('status-indicator').classList.remove('scan-success');
}

function showSuccessMessage(response) {
    const message = `Asistencia registrada: ${response.data.hora}`;
    document.getElementById('result-message').textContent = message;
    document.getElementById('status-indicator').classList.add('scan-success');
}

function showErrorMessage(error) {
    document.getElementById('result-message').textContent = error.message;
    document.getElementById('status-indicator').classList.add('scan-error');
    setTimeout(() => {
        document.getElementById('status-indicator').classList.remove('scan-error');
    }, 3000);
}

// Inicializar el escáner
function initScanner() {
    const codeReader = new ZXing.BrowserQRCodeReader();
    
    codeReader.decodeFromVideoDevice(undefined, 'qr-video', (result, err) => {
        if (result) handleQRScan(result);
        if (err) {
            console.error(err);
        }
    });
    
    return codeReader;
}

// Al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    const scanner = initScanner();
    
    // Controles manuales
    document.getElementById('rescan-btn').addEventListener('click', resetScanner);
});