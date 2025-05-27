<!DOCTYPE html>
    <html lang="es">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Simulador de Parking</title>
      <style>
        body {
          font-family: Arial, sans-serif;
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
          background-color: #f0f2f5;
          margin: 0;
        }
        .parking-container {
          background: white;
          padding: 40px;
          border-radius: 10px;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .parking-lot {
          display: grid;
          grid-template-columns: repeat(6, 80px);
          grid-gap: 20px 40px;
          row-gap: 80px;
        }
        .slot {
          width: 80px;
          height: 120px;
          background-color: #4CAF50; /* libre */
          display: flex;
          justify-content: center;
          align-items: center;
          border: 2px solid #222;
          color: white;
          font-weight: bold;
          border-radius: 8px;
          transition: background-color 0.3s;
        }
          
        .occupied {
          background-color: #D32F2F; /* ocupado */
        }
      </style>
    </head>
    <body>
      <div class="parking-container">
        <div class="parking-lot" id="parkingLot">
          <div class="slot" id="slot1">1</div>
          <div class="slot" id="slot2">2</div>
          <div class="slot" id="slot3">3</div>
          <div class="slot" id="slot4">4</div>
          <div class="slot" id="slot5">5</div>
          <div class="slot" id="slot6">6</div>
          <div class="slot" id="slot7">7</div>
          <div class="slot" id="slot8">8</div>
          <div class="slot" id="slot9">9</div>
          <div class="slot" id="slot10">10</div>
          <div class="slot" id="slot11">11</div>
          <div class="slot" id="slot12">12</div>
        </div>
      </div>
        <script>
          async function cargarEstados() {
            try {
              const response = await fetch('http://192.168.200.50:5000/api/estado_parking');
              const datos = await response.json();
              
              for (let i = 1; i <= 12; i++) { // Reinicia todas las plazas a "libre"
                const slot = document.getElementById("slot" + i);
                slot.classList.remove("occupied");
              }
                
              datos.forEach(plaza => { // Marca las ocupadas
                const numero = parseInt(plaza.nombre.replace("plaza", ""));
                const ocupado = plaza.estado === 1;
                actualizarPlaza(numero, ocupado);
              });
            } catch (error) {
              console.error("Error al cargar estados:", error);
            }
          }
        
          function actualizarPlaza(numero, ocupado) {
            const slot = document.getElementById("slot" + numero);
            if (slot) {
              if (ocupado) {
                slot.classList.add("occupied");
              } else {
                slot.classList.remove("occupied");
              }
            }
          }  
          setInterval(cargarEstados, 3000); // Refrescar cada 3 segundos
          window.onload = cargarEstados;
        </script>
    </body>
</html>
