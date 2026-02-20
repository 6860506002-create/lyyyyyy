<?php
$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? '';
$dbname = $_ENV['DB_NAME'] ?? 'dokploy';

$conn = @new mysqli($host, $user, $pass, $dbname);

if (!$conn->connect_error) {
    $sql = "CREATE TABLE IF NOT EXISTS bst_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        action VARCHAR(50),
        value INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🌳 ระบบเรียนรู้ Binary Search Tree (BST)</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #0f172a, #020617);
    color: white;
    margin: 0;
    text-align: center;
}

/* เมนู */
nav {
    background: black;
    padding: 10px;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 0 15px #00f7ff;
}
nav a {
    color: #00f7ff;
    margin: 0 15px;
    text-decoration: none;
    font-weight: bold;
}
nav a:hover {
    color: #ff00ea;
}

header {
    padding: 20px;
    font-size: 32px;
    font-weight: bold;
    background: black;
    color: #00f7ff;
}

section {
    margin: 20px;
    padding: 20px;
    border-radius: 20px;
    background: rgba(255,255,255,0.05);
    box-shadow: 0 0 15px rgba(0,255,255,0.2);
}

input, button {
    padding: 10px;
    margin: 5px;
    border-radius: 10px;
    border: none;
}

button {
    background: #00f7ff;
    color: black;
    font-weight: bold;
    cursor: pointer;
}
button:hover {
    background: #ff00ea;
    color: white;
    transform: scale(1.05);
}

ul { list-style: none; padding: 0; }

.collection-item {
    background: rgba(0,255,255,0.2);
    margin: 5px;
    padding: 10px;
    border-radius: 10px;
}

/* แผนผังต้นไม้ */
.tree-container {
    overflow-x: auto;
    padding: 20px;
}
.tree ul {
    padding-top: 20px;
    position: relative;
}
.tree li {
    display: inline-block;
    text-align: center;
    list-style-type: none;
    position: relative;
    padding: 20px 5px 0 5px;
}
.tree li::before, .tree li::after {
    content: '';
    position: absolute;
    top: 0;
    right: 50%;
    border-top: 2px solid #00f7ff;
    width: 50%;
    height: 20px;
}
.tree li::after {
    right: auto;
    left: 50%;
    border-left: 2px solid #00f7ff;
}
.tree li:only-child::after, 
.tree li:only-child::before {
    display: none;
}
.tree li div {
    border: 2px solid #00f7ff;
    padding: 10px 15px;
    border-radius: 50%;
    display: inline-block;
    background: #00f7ff;
    color: black;
    font-weight: bold;
    box-shadow: 0 0 10px #00f7ff;
}
</style>
</head>

<body>

<header>
🌳 ระบบการเรียนรู้ Binary Search Tree (BST) แบบอินเทอร์แอคทีฟ
</header>

<!-- เมนู -->
<nav>
<a href="#about">📚 ความรู้ BST</a>
<a href="#crud">🌳 จัดการ BST</a>
<a href="#diagram">📊 แผนผังต้นไม้</a>
<a href="#game">🎮 เกม</a>
<a href="#puzzle">🧩 พัซเซิล</a>
<a href="#collection">🗂️ คลังสะสม</a>
</nav>

<section id="about">
<h2>📚 Binary Search Tree (BST) คืออะไร</h2>
<p>
BST คือโครงสร้างข้อมูลแบบต้นไม้  
โดยโหนดด้านซ้ายจะมีค่าน้อยกว่าโหนดแม่  
และโหนดด้านขวาจะมีค่ามากกว่าโหนดแม่  
ทำให้การค้นหา (Search), เพิ่ม (Insert), และลบ (Delete) ทำได้รวดเร็ว
</p>
</section>

<section id="crud">
<h2>🌳 ระบบจัดการข้อมูล BST (CRUD)</h2>
<input type="number" id="value" placeholder="กรอกตัวเลข">
<br>
<button onclick="insertValue()">➕ เพิ่มข้อมูล (Insert)</button>
<button onclick="searchValue()">🔍 ค้นหาข้อมูล (Search)</button>
<button onclick="deleteValue()">❌ ลบข้อมูล (Delete)</button>
<button onclick="showTree()">📋 แสดงข้อมูลเรียงลำดับ (Inorder)</button>

<h3>ข้อมูลใน BST (เรียงจากน้อยไปมาก)</h3>
<ul id="output"></ul>
</section>

<section id="diagram">
<h2>📊 แผนผังโครงสร้างต้นไม้ BST (ดูภาพเข้าใจง่าย)</h2>
<div class="tree-container">
<div class="tree" id="treeDiagram"></div>
</div>
</section>

<section id="game">
<h2>🎮 เกมทายตัวเลขแบบ BST</h2>
<p>ทายเลขลับ (1-100)</p>
<input type="number" id="guessInput">
<button onclick="guessGame()">ทายตัวเลข</button>
<div id="gameResult"></div>
</section>

<section id="puzzle">
<h2>🧩 พัซเซิล: การเรียงลำดับแบบ BST</h2>
<p>สุ่มตัวเลขแล้วแสดงผลการเรียงแบบ Inorder</p>
<button onclick="generatePuzzle()">สุ่มตัวเลขพัซเซิล</button>
<p id="puzzleResult"></p>
</section>

<section id="collection">
<h2>🗂️ คลังสะสมโหนดพิเศษ</h2>
<button onclick="addCollection()">สะสมโหนดพิเศษ</button>
<div id="collectionBox"></div>
</section>

<script>
class Node {
    constructor(value) {
        this.value = value;
        this.left = null;
        this.right = null;
    }
}

class BST {
    constructor() {
        this.root = null;
    }

    insert(value) {
        this.root = this.insertNode(this.root, value);
        renderTree();
    }

    insertNode(root, value) {
        if (root === null) return new Node(value);
        if (value < root.value)
            root.left = this.insertNode(root.left, value);
        else if (value > root.value)
            root.right = this.insertNode(root.right, value);
        return root;
    }

    search(value, root = this.root) {
        if (!root) return false;
        if (value === root.value) return true;
        if (value < root.value) return this.search(value, root.left);
        return this.search(value, root.right);
    }

    delete(value) {
        this.root = this.deleteNode(this.root, value);
        renderTree();
    }

    deleteNode(root, value) {
        if (!root) return null;

        if (value < root.value) {
            root.left = this.deleteNode(root.left, value);
        } else if (value > root.value) {
            root.right = this.deleteNode(root.right, value);
        } else {
            if (!root.left) return root.right;
            if (!root.right) return root.left;

            let min = this.findMin(root.right);
            root.value = min.value;
            root.right = this.deleteNode(root.right, min.value);
        }
        return root;
    }

    findMin(root) {
        while (root.left) root = root.left;
        return root;
    }

    inorder(root = this.root, result = []) {
        if (root) {
            this.inorder(root.left, result);
            result.push(root.value);
            this.inorder(root.right, result);
        }
        return result;
    }
}

const tree = new BST();

// CRUD
function insertValue() {
    const value = parseInt(document.getElementById("value").value);
    if (!isNaN(value)) {
        tree.insert(value);
        alert("เพิ่มข้อมูลลงใน BST สำเร็จ");
    }
}

function searchValue() {
    const value = parseInt(document.getElementById("value").value);
    const found = tree.search(value);
    alert(found ? "พบข้อมูลในต้นไม้ BST" : "ไม่พบข้อมูลใน BST");
}

function deleteValue() {
    const value = parseInt(document.getElementById("value").value);
    tree.delete(value);
    alert("ลบข้อมูลเรียบร้อยแล้ว");
}

function showTree() {
    const list = tree.inorder();
    const output = document.getElementById("output");
    output.innerHTML = "";
    list.forEach(v => {
        const li = document.createElement("li");
        li.textContent = "โหนด: " + v;
        output.appendChild(li);
    });
}

// วาดแผนผังต้นไม้
function buildTreeHTML(node) {
    if (!node) return "";
    let html = "<li><div>" + node.value + "</div>";
    if (node.left || node.right) {
        html += "<ul>";
        html += node.left ? buildTreeHTML(node.left) : "<li></li>";
        html += node.right ? buildTreeHTML(node.right) : "<li></li>";
        html += "</ul>";
    }
    html += "</li>";
    return html;
}

function renderTree() {
    const container = document.getElementById("treeDiagram");
    if (!tree.root) {
        container.innerHTML = "<p>ยังไม่มีข้อมูลในต้นไม้ BST</p>";
        return;
    }
    container.innerHTML = "<ul>" + buildTreeHTML(tree.root) + "</ul>";
}

// เกม
let secret = Math.floor(Math.random() * 100) + 1;
function guessGame() {
    const guess = parseInt(document.getElementById("guessInput").value);
    let msg = "";
    if (guess === secret) msg = "🎉 ถูกต้อง! คุณเข้าใจแนวคิด BST แล้ว!";
    else if (guess > secret) msg = "ค่ามากเกินไป → เปรียบเหมือนไปทางขวาของต้นไม้";
    else msg = "ค่าน้อยเกินไป → เปรียบเหมือนไปทางซ้ายของต้นไม้";
    document.getElementById("gameResult").textContent = msg;
}

// Puzzle
function generatePuzzle() {
    let arr = [];
    for (let i = 0; i < 5; i++) {
        let num = Math.floor(Math.random() * 50);
        arr.push(num);
        tree.insert(num);
    }
    document.getElementById("puzzleResult").textContent =
        "ตัวเลขสุ่ม: " + arr.join(", ") +
        " | เรียงแบบ Inorder: " + tree.inorder().join(", ");
}

// Collection
let count = 1;
function addCollection() {
    const div = document.createElement("div");
    div.className = "collection-item";
    div.textContent = "โหนดพิเศษลำดับที่ #" + count++;
    document.getElementById("collectionBox").appendChild(div);
}
</script>

</body>

</html>
