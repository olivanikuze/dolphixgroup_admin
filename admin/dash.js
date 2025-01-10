// dashboard.js
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard components
    initializeDashboard();
    initializeChat();
    initializeProductManagement();
});

// Dashboard initialization
function initializeDashboard() {
    // Update dashboard metrics
    fetchDashboardMetrics();
    // Set up sidebar navigation
    setupNavigation();
}

// Dashboard metrics
function fetchDashboardMetrics() {
    fetch('api/dashboard-metrics.php')
        .then(response => response.json())
        .then(data => {
            updateMetricsDisplay(data);
        })
        .catch(error => console.error('Error fetching metrics:', error));
}

function updateMetricsDisplay(data) {
    document.querySelector('.total-products').textContent = data.totalProducts;
    document.querySelector('.active-chats').textContent = data.activeChats;
    document.querySelector('.total-orders').textContent = data.totalOrders;
    document.querySelector('.total-revenue').textContent = `$${data.revenue}`;
}

// Chat functionality
function initializeChat() {
    const chatInput = document.querySelector('.chat-input input');
    const sendButton = document.querySelector('.send-btn');
    const chatMessages = document.querySelector('.chat-messages');

    // Load existing messages
    loadChatMessages();

    // Set up WebSocket connection for real-time chat
    const ws = new WebSocket('ws://your-websocket-server');
    
    ws.onmessage = function(event) {
        const message = JSON.parse(event.data);
        appendMessage(message);
    };

    sendButton.addEventListener('click', () => {
        sendChatMessage(chatInput.value);
        chatInput.value = '';
    });
}

function loadChatMessages() {
    fetch('api/chat-messages.php')
        .then(response => response.json())
        .then(messages => {
            messages.forEach(message => appendMessage(message));
        })
        .catch(error => console.error('Error loading messages:', error));
}

function appendMessage(message) {
    const messageElement = document.createElement('div');
    messageElement.classList.add('message', message.is_admin ? 'admin' : 'user');
    messageElement.textContent = message.message;
    document.querySelector('.chat-messages').appendChild(messageElement);
}

// Product management
function initializeProductManagement() {
    const productTable = document.querySelector('.content-table tbody');
    const addProductButton = document.querySelector('.add-product-btn');

    // Load products
    loadProducts();

    // Add product event listener
    addProductButton.addEventListener('click', () => {
        showProductModal();
    });
}

function loadProducts() {
    fetch('api/products.php')
        .then(response => response.json())
        .then(products => {
            displayProducts(products);
        })
        .catch(error => console.error('Error loading products:', error));
}

function displayProducts(products) {
    const productTable = document.querySelector('.content-table tbody');
    productTable.innerHTML = '';

    products.forEach(product => {
        const row = createProductRow(product);
        productTable.appendChild(row);
    });
}

function createProductRow(product) {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${product.name}</td>
        <td>${product.category}</td>
        <td>$${product.price}</td>
        <td>${product.stock}</td>
        <td>
            <button class="action-btn edit-btn" onclick="editProduct(${product.id})">Edit</button>
            <button class="action-btn delete-btn" onclick="deleteProduct(${product.id})">Delete</button>
        </td>
    `;
    return row;
}

// Navigation
function setupNavigation() {
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            const section = item.textContent.toLowerCase();
            showSection(section);
        });
    });
}

function showSection(section) {
    // Hide all sections
    document.querySelectorAll('.section').forEach(s => s.style.display = 'none');
    // Show selected section
    document.querySelector(`.${section}-section`).style.display = 'block';
}