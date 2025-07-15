// Генерация лабиринта и отрисовка
let canvas = document.getElementById('mazeCanvas');
let ctx = canvas.getContext('2d');

const cellSize = 20;
let rows = 35;
let cols = 35;
const wall = 0;
const pass = 1;
let maze = [];

// Создание пустого лабиринта
function initializeMaze() {
    maze = Array.from({ length: rows }, () => Array(cols).fill(wall));
}

// Генерация лабиринта методом случайного блуждания
function generateMaze() {
    let x = 1, y = 1;
    maze[y][x] = pass;

    for (let steps = 0; steps < 10000; steps++) {
        const directions = [
            { dx: 0, dy: -2 }, // вверх
            { dx: 0, dy: 2 },  // вниз
            { dx: -2, dy: 0 }, // влево
            { dx: 2, dy: 0 }   // вправо
        ];

        const validDirections = directions.filter(dir => {
            const nx = x + dir.dx;
            const ny = y + dir.dy;
            return nx > 0 && ny > 0 && nx < cols - 1 && ny < rows - 1 && maze[ny][nx] === wall;
        });

        if (validDirections.length === 0) {
            do {
                x = 2 * Math.floor(Math.random() * ((cols - 1) / 2)) + 1;
                y = 2 * Math.floor(Math.random() * ((rows - 1) / 2)) + 1;
            } while (maze[y][x] !== pass);
            continue;
        }

        const direction = validDirections[Math.floor(Math.random() * validDirections.length)];
        const nx = x + direction.dx;
        const ny = y + direction.dy;

        maze[y + direction.dy / 2][x + direction.dx / 2] = pass;
        maze[ny][nx] = pass;

        x = nx;
        y = ny;
    }
}

const wallImage = new Image();
wallImage.src = 'pictures/wall.png'; 

const endImage = new Image();
endImage.src = 'pictures/soul.png'; 

// Загрузка изображения для игрока
const playerImage = new Image();
playerImage.src = 'pictures/hero.png'; 

function drawMaze() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for (let y = 0; y < rows; y++) {
        for (let x = 0; x < cols; x++) {
            if (y === rows - 2 && x === cols - 2) {
                // Конечная точка
                ctx.drawImage(endImage, x * cellSize, y * cellSize, cellSize, cellSize);
            } else if (maze[y][x] === wall) {
                ctx.drawImage(wallImage, x * cellSize, y * cellSize, cellSize, cellSize);
            } else {
                ctx.fillStyle = '#1C1C1C';
                ctx.fillRect(x * cellSize, y * cellSize, cellSize, cellSize);
            }
        }
    }
}

let imagesLoaded = 0;

function onImageLoad() {
    imagesLoaded++;
    if (imagesLoaded === 3) {
        // Все изображения загружены, отрисовываем лабиринт
        drawMaze();
        drawPlayer();
    }
}

wallImage.onload = onImageLoad;
endImage.onload = onImageLoad;
playerImage.onload = onImageLoad;

// Управление игроком
const player = { x: 1, y: 1 };

function drawPlayer() {
    ctx.drawImage(playerImage, player.x * cellSize, player.y * cellSize, cellSize, cellSize);
}

function movePlayer(dx, dy) {
    const nx = player.x + dx;
    const ny = player.y + dy;
    if (maze[ny][nx] === pass) {
        player.x = nx;
        player.y = ny;
        if (nx === cols - 2 && ny === rows - 2) {
            showMessage();
            restartMaze();
            return;
        }
    }
    drawMaze();
    drawPlayer();
}

// Управление клавишами
window.addEventListener('keydown', (e) => {
    if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
        e.preventDefault();
    }
    switch (e.key) {
        case 'ArrowUp': movePlayer(0, -1); break;
        case 'ArrowDown': movePlayer(0, 1); break;
        case 'ArrowLeft': movePlayer(-1, 0); break;
        case 'ArrowRight': movePlayer(1, 0); break;
    }
});


// Инициализация
canvas.width = cols * cellSize;
canvas.height = rows * cellSize;
initializeMaze();
generateMaze();
drawMaze();
drawPlayer();

function restartMaze() {
    const inputValue = document.getElementById('Input').value;
    rows = parseInt(inputValue);
    cols = parseInt(inputValue);
    canvas.width = cols * cellSize;
    canvas.height = rows * cellSize;
    player.x = 1;
    player.y = 1;
    initializeMaze();
    generateMaze();
    drawMaze();
    drawPlayer();
}

function showMessage() {
    document.querySelector('.message_modal').style.display = 'block';
  }
  
  function closeMessage() {
    document.getElementById('MessageModal').style.display = 'none';
  }