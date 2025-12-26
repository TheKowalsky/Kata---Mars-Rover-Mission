
<!--Component utilitzat per crear tant el mapa de 200 x 200 com el rover i els seus obstacles pertinents-->
<template>
  <div
    :style="{
      width: width + 'px',
      height: height + 'px',
      border: '1px solid #333',
      borderRadius: '8px',
      overflow: 'hidden',
    }"
  >
    <canvas
      ref="canvasRef"
      :width="width"
      :height="height"
      style="display:block"
    ></canvas>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";

const props = defineProps({
  worldSize: { type: Number, default: 200 },
  width: { type: Number, default: 600 },
  height: { type: Number, default: 600 },
  cellSize: { type: Number, default: 3 },
  paddingPx: { type: Number, default: 10 },

  rover: {
    type: Object,
    default: () => ({ x: 0, y: 0, direction: "N" }),
  },

  obstacles: {
    type: Array,
    default: () => [],
  },
});

const canvasRef = ref(null);


function drawMarsGround(ctx) {
  ctx.clearRect(0, 0, props.width, props.height);                     // Avans de qualsevol cosa es neteja el CANVA sancer

  const innerW = props.width - props.paddingPx * 2;
  const innerH = props.height - props.paddingPx * 2;

  ctx.fillStyle = "#8b4a2b";                                          // Color utilitazat per generar el mapa, l'objteciu es que aparenti el terra de mart
  ctx.fillRect(props.paddingPx, props.paddingPx, innerW, innerH);     // Generem els marges

  for (let i = 0; i < 12000; i++) {                                   // Es un bucle que ens serveis per generar de forma random punts dins del mapa per a que sembli mes natural
    const x = props.paddingPx + Math.floor(Math.random() * innerW);
    const y = props.paddingPx + Math.floor(Math.random() * innerH);
    const a = Math.random() * 0.18;
    ctx.fillStyle = `rgba(40,20,10,${a})`;
    ctx.fillRect(x, y, 1, 1);
  }
}

/**
 * Funcio per calcular les coordenades reals sobre les coordenades del mapa
 * Es utilitzat tant per generar el rover i mourel com per generar tots els obtacles dins del mapa
 * @param x Posicio X del mapa
 * @param y Posicio Y del mapa
 */
function worldToCanvas(x, y) {                                       
  const px = props.paddingPx + x * props.cellSize;
  const py = props.paddingPx + (props.worldSize - 1 - y) * props.cellSize;
  return { px, py };
}

/**
 * Funció que genera els objstacles dins del mapa
 * @param ctx context utilitzat per dir que es en 2D
 */
function drawObstacles(ctx) {
  if (!props.obstacles?.length) return;           // En primer lloc hem de veure si s'han de geneara algun obstacle

  for (const o of props.obstacles) {              // En cas afirmatiu, es genera un bule per cada obstacle
    const ox = Number(o.x);                       // Posicio real X del obstacle
    const oy = Number(o.y);                       // Posicio real y del obstacle
    const { px, py } = worldToCanvas(ox, oy);     // Ho tranformem a posicons sobre el mapa

    const size = props.cellSize * 1.6;            // Mida del obstacle

    const cx = px + props.cellSize / 2;           // Calculem el centre de la cel·la, aixo es fa per generar el obstacle al centre i no de forma desplaçada
    const cy = py + props.cellSize / 2;
    const x0 = cx - size / 2;
    const y0 = cy - size / 2;

    ctx.save();                                   // Guardem el context
    ctx.shadowColor = "rgba(0,0,0,0.6)";          // Creem profunditat
    ctx.shadowBlur = 8;
    // Cos del obstacle
    ctx.fillStyle = "#102a43";
    ctx.fillRect(x0, y0, size, size);

    ctx.restore();                                // Restaurem el context

    ctx.strokeStyle = "#ffffff";                  // Generem un contorn blanc
    ctx.lineWidth = 1;
    ctx.strokeRect(x0, y0, size, size);
  }
}

/**
 * Creació del rover
 * @param ctx 
 */
function drawRover(ctx) {
  const { x, y, direction } = props.rover;        // Obtenim la posicio real del rover
  const { px, py } = worldToCanvas(x, y);         // Enviam aquesta posicio real a la funció worldToCanva per tranformar-ho en posicions sobre el mapa

  // Calculem el centre de la cela per al rover
  const cx = px + props.cellSize / 2;
  const cy = py + props.cellSize / 2;
  const s = props.cellSize * 1.2;                 // Mida visual del rover

  ctx.fillStyle = "#ffffff";                      // Generem el color blanc per al cos del rover
  ctx.beginPath();                                // Començem la traçada del triangle

  if (direction === "N") {                        // Com tenim 4 direccions possibles el nostre objectiu es veure depenent de la direcció
    ctx.moveTo(cx, cy - s);                       // aquest triangle ha de mirar cap a una direcció diferent
    ctx.lineTo(cx - s, cy + s);
    ctx.lineTo(cx + s, cy + s);
  } else if (direction === "S") {
    ctx.moveTo(cx, cy + s);
    ctx.lineTo(cx - s, cy - s);
    ctx.lineTo(cx + s, cy - s);
  } else if (direction === "E") {
    ctx.moveTo(cx + s, cy);
    ctx.lineTo(cx - s, cy - s);
    ctx.lineTo(cx - s, cy + s);
  } else {
    ctx.moveTo(cx - s, cy);
    ctx.lineTo(cx + s, cy - s);
    ctx.lineTo(cx + s, cy + s);
  }

  ctx.closePath();                      // Tanquem el triangle i omplim el interior del color ja definit anteriorment
  ctx.fill();

  ctx.strokeStyle = "rgba(0,0,0,0.7)";
  ctx.stroke();
}

/**
 * És la funcio requewrida per muntar totes les funcions anterions
 * en aquesta es configura tot, tant el mapa 2d com es crida a les funcions anteriorment comentades
 */
function render() {
  const canvas = canvasRef.value;
  if (!canvas) return;
  const ctx = canvas.getContext("2d");

  drawMarsGround(ctx);
  drawObstacles(ctx);
  drawRover(ctx);
}

onMounted(render);

watch(() => props.rover, render, { deep: true });
watch(() => props.obstacles, render, { deep: true });
</script>
