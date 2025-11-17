<?php
// file: text_particles.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Text Particles</title>
<style>
  body {
    margin:0;
    background:#0b0f19;
    overflow:hidden;
  }
  canvas {
    display:block;
  }
</style>
</head>
<body>
<canvas id="canvas"></canvas>
<script>
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
let W = canvas.width = window.innerWidth;
let H = canvas.height = window.innerHeight;

// dibagi jadi 4 baris
const textLines = [
  "saya selalu berpikir",
  "bahwa rebahan itu buang-buang waktu.",
  "makanya saya berhenti berpikir",
  "dan lanjtukan rebahan"
];

let particles = [];
let mouse = {x:W/2,y:H/2};

function createParticles() {
  particles = [];
  const off = document.createElement('canvas');
  const offCtx = off.getContext('2d');
  off.width = W;
  off.height = H;
  offCtx.fillStyle = "#fff";
  offCtx.font = "bold 60px Arial";
  offCtx.textAlign = "center";
  offCtx.textBaseline = "middle";

  // render 4 baris teks
  textLines.forEach((line, i) => {
    offCtx.fillText(line, W/2, H/2 - 120 + i*70);
  });

  const imgData = offCtx.getImageData(0,0,W,H);
  for(let y=0;y<H;y+=4){
    for(let x=0;x<W;x+=4){
      const idx = (y*W + x)*4;
      if(imgData.data[idx+3] > 128){
        particles.push(new Particle(x,y));
      }
    }
  }
}

class Particle {
  constructor(x,y){
    this.baseX = x;
    this.baseY = y;
    this.x = x + (Math.random()*20-10);
    this.y = y + (Math.random()*20-10);
    this.vx = 0;
    this.vy = 0;
  }
  update(){
    const dx = this.x - mouse.x;
    const dy = this.y - mouse.y;
    const dist = Math.sqrt(dx*dx+dy*dy);
    const radius = 120;
    if(dist < radius){
      const force = (radius - dist)/radius;
      const angle = Math.atan2(dy,dx);
      this.vx += Math.cos(angle)*force*4;
      this.vy += Math.sin(angle)*force*4;
    }
    // balik ke posisi asal
    this.vx += (this.baseX - this.x)*0.05;
    this.vy += (this.baseY - this.y)*0.05;

    this.vx *= 0.9;
    this.vy *= 0.9;

    this.x += this.vx;
    this.y += this.vy;
  }
  draw(){
    ctx.fillStyle = "#ffffff";
    ctx.fillRect(this.x,this.y,3,3);
  }
}

function animate(){
  ctx.clearRect(0,0,W,H);
  particles.forEach(p=>{
    p.update();
    p.draw();
  });
  requestAnimationFrame(animate);
}

window.addEventListener('mousemove', e=>{
  mouse.x = e.clientX;
  mouse.y = e.clientY;
});

window.addEventListener('resize', ()=>{
  W = canvas.width = window.innerWidth;
  H = canvas.height = window.innerHeight;
  createParticles();
});

createParticles();
animate();
</script>
</body>
</html>
