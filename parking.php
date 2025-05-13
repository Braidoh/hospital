<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Simulador de Parking</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
  <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
</head>
<body>
  <div class="parking-container">
    <div class="parking-lot" id="parkingLot">
      <!-- Genera 12 plazas dinámicamente -->
      <script>
        for (let i = 1; i <= 12; i++) {
          document.write(`<div class="slot" id="slot${i}">${i}</div>`);
        }
      </script>
    </div>
  </div>

  <script>
    // Conecta al broker MQTT local
    const client = mqtt.connect('ws://192.168.200.50:9001');

    client.on('connect', function () {
      console.log('Conectado al broker MQTT');
      for (let i = 1; i <= 12; i++) {
        client.subscribe(`parking/plaza${i}`, function (err) {
          if (!err) {
            console.log(`Suscrito a parking/plaza${i}`);
          }
        });
      }
    });

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

    // Función para generar una matrícula aleatoria
    function generarMatricula() {
      const letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      const numeros = "0123456789";
      let matricula = "";

      // Generar la parte de letras
      for (let i = 0; i < 4; i++) {
        matricula += letras.charAt(Math.floor(Math.random() * letras.length));
      }

      // Generar la parte de números
      for (let i = 0; i < 4; i++) {
        matricula += numeros.charAt(Math.floor(Math.random() * numeros.length));
      }

      return matricula;
    }

    client.on('message', function (topic, message) {
      const estado = message.toString(); // "1" = ocupado, "0" = libre
      const match = topic.match(/plaza(\d+)/);
      if (match) {
        const numero = parseInt(match[1]);
        const ocupado = estado === "1";
        actualizarPlaza(numero, ocupado);

        // Generar matrícula automáticamente si se ocupa la plaza
        let matricula = "N/A";
        if (ocupado) {
          matricula = generarMatricula(); // Matrícula aleatoria generada
          console.log(`La matrícula de la plaza ${numero} es: ${matricula}`);
        }

        // Enviar al backend PHP
        fetch("registrar.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: `plaza=${numero}&estado=${estado}&matricula=${encodeURIComponent(matricula)}`
        })
        .then(response => response.text())
        .then(data => console.log("Respuesta servidor:", data));
      }
    });
  </script>
</body>
</html>
