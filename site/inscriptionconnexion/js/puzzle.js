var rows = 3;
var columns = 3;

var currTile;
var otherTile; //blank tile

var imgOrderWaited = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
var imgOrder = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
var currentOrder = [];

function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

window.onload = function() {
    shuffleArray(imgOrder);
    while (imgOrder.toString() === imgOrderWaited.toString()) {
        shuffleArray(imgOrder);
    }

    let j = 1;
    let tile_folder_name = 'capcha1';

    while (true) {
        const xhr = new XMLHttpRequest(); 
        xhr.open('HEAD', `image/${tile_folder_name}`, false);
        xhr.send();
        
        if (xhr.status === 404) {
            break;
        }
        
        j++;
        tile_folder_name = `capcha${j}`;
    }

    j--;

    const folderNumber = Math.floor(Math.random() * j) + 1;
    const randomCapcha = `capcha${folderNumber}`;

    for (let r=0; r < rows; r++) {
        for (let c=0; c < columns; c++) {
            let tile = document.createElement("img");
            let imageName = imgOrder.shift();
            tile.id = "tile-" + imageName;
            tile.src = "image/" + randomCapcha + "/" + imageName + ".jpg";
            tile.style.position = "absolute";
            tile.style.top = r * 100 + "px";
            tile.style.left = c * 100 + "px";

            //DRAG FUNCTIONALITY
            tile.addEventListener("dragstart", dragStart);
            tile.addEventListener("dragover", dragOver);
            tile.addEventListener("dragenter", dragEnter);
            tile.addEventListener("drop", dragDrop);
            tile.addEventListener("dragend", dragEnd);

            document.getElementById("board").appendChild(tile);
        }
    }
}

function dragStart(e) {
    currTile = this;
    e.dataTransfer.setData("text", ""); //needed for Firefox to allow dragging
}

function dragOver(e) {
    e.preventDefault();
}

function dragEnter(e) {
    e.preventDefault();
}

function dragDrop(e) {
    otherTile = this;

    let currX = parseInt(currTile.style.left);
    let currY = parseInt(currTile.style.top);

    let otherX = parseInt(otherTile.style.left);
    let otherY = parseInt(otherTile.style.top);

    currTile.style.left = otherX + "px";
    currTile.style.top = otherY + "px";

    otherTile.style.left = currX + "px";
    otherTile.style.top = currY + "px";

    checkSolution();
}

function checkSolution() {
    let tiles = document.querySelectorAll("#board img");
    currentOrder = [];
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < columns; c++) {
            for (let i = 0; i < tiles.length; i++) {
                let tile = tiles[i];
                let tileTop = parseInt(tile.style.top);
                let tileLeft = parseInt(tile.style.left);

                if (tileTop === r * 100 && tileLeft === c * 100) {
                    let tileSrc = tile.getAttribute("id");
                    let tileNumber = tileSrc.split("-").pop();
                    currentOrder.push(tileNumber);
                    break;
                }
            }
        }
    }

    if (currentOrder.join() === imgOrderWaited.join()) {
        setTimeout(function () {
            window.location.href = "win.php";
        }, 1000);
        return true;
    } else {
        console.log(currentOrder);
        return false;
    }
    
}

function dragEnd() {
    otherTile = null;
}