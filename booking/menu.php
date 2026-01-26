<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>Menu - Yanji Restaurant</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: Roboto, sans-serif;
      background: #fff;
      color: #333;
      min-height: 100vh;
      font-weight: 100;
    }
    .container {
      max-width: 900px;
      margin: 0 auto;
      padding: 0;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 20px;
      background: #fff;
      border-bottom: 1px solid #ddd;
      position: sticky;
      top: 0;
      z-index: 100;
      flex-wrap: wrap;
    }
    header h1 { 
      font-size: 24px; 
      color: #333;
      flex: 1;
      font-weight: 100;
    }
    .header-controls {
      display: flex;
      gap: 20px;
      align-items: center;
    }
    .admin-controls {
      display: none;
      gap: 10px;
    }
    .admin-controls.visible {
      display: flex;
    }
    .lang-switch {
      display: flex;
      gap: 8px;
    }
    .lang-btn {
      padding: 8px 14px;
      background: #f5f5f5;
      border: 1px solid #ddd;
      color: #999;
      cursor: pointer;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 400;
      transition: all 0.3s;
      font-family: Roboto, sans-serif;
    }
    .lang-btn.active {
      background: #333;
      color: #fff;
      border-color: #333;
    }
    .lang-btn:hover {
      background: #eee;
      border-color: #999;
    }
    .admin-btn {
      padding: 8px 14px;
      background: #333;
      border: 1px solid #333;
      color: #fff;
      cursor: pointer;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 400;
      transition: all 0.3s;
      font-family: Roboto, sans-serif;
    }
    .admin-btn:hover {
      background: #555;
      border-color: #555;
    }
    .logout-btn {
      background: #f5f5f5;
      border: 1px solid #ddd;
      color: #333;
    }
    .logout-btn:hover {
      background: #eee;
    }
    
    /* Menu Content */
    .menu-content {
      padding: 40px 20px;
      max-height: calc(100vh - 100px);
      overflow-y: auto;
    }
    
    .menu-content::-webkit-scrollbar {
      width: 8px;
    }
    .menu-content::-webkit-scrollbar-track {
      background: #fff;
    }
    .menu-content::-webkit-scrollbar-thumb {
      background: #ddd;
      border-radius: 4px;
    }
    
    .menu-section {
      margin-bottom: 50px;
    }
    .menu-section h2 {
      font-size: 28px;
      color: #333;
      margin-bottom: 30px;
      padding-bottom: 15px;
      border-bottom: 2px solid #ddd;
      font-weight: 300;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .menu-item {
      margin-bottom: 30px;
      padding: 15px;
      background: #fafafa;
      border-radius: 2px;
      transition: all 0.3s;
      border-left: 3px solid transparent;
    }
    .menu-item:hover {
      background: #f5f5f5;
      border-left-color: #333;
    }
    
    .menu-item-image-container {
      margin-bottom: 15px;
    }
    .menu-item-image {
      width: 100%;
      height: 200px;
      background: #f0f0f0;
      border-radius: 2px;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      position: relative;
      cursor: pointer;
      transition: all 0.3s;
    }
    .menu-item-image:hover {
      background: #e8e8e8;
    }
    .menu-item-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .menu-item-image.empty {
      color: #999;
      font-size: 48px;
    }
    .menu-item-image-placeholder {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      color: #999;
      text-align: center;
    }
    .menu-item-image-placeholder-icon {
      font-size: 48px;
    }
    .menu-item-image-placeholder-text {
      font-size: 12px;
    }
    .upload-btn {
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      background: rgba(51, 51, 51, 0.8);
      display: none;
      align-items: center;
      justify-content: center;
      transition: opacity 0.3s;
      cursor: pointer;
      color: #fff;
      font-weight: 400;
      font-size: 14px;
    }
    .menu-item-image:hover .upload-btn {
      display: flex;
    }
    .menu-item-image input[type="file"] {
      display: none;
    }
    
    .menu-item-name {
      font-size: 16px;
      font-weight: 300;
      color: #333;
      margin-bottom: 8px;
    }
    .menu-item-price {
      font-size: 18px;
      font-weight: 400;
      color: #333;
      margin-bottom: 12px;
    }
    .menu-item-buttons {
      display: flex;
      gap: 10px;
      margin-top: 12px;
    }
    .order-btn {
      background: #333;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 2px;
      cursor: pointer;
      font-size: 12px;
      font-weight: 400;
      transition: all 0.3s;
      font-family: Roboto, sans-serif;
    }
    .order-btn:hover {
      background: #555;
    }
    
    .note {
      text-align: center;
      padding: 40px 20px;
      color: #999;
      font-size: 13px;
      margin-top: 20px;
      border-top: 1px solid #ddd;
      font-weight: 300;
    }
    
    /* Toast */
    .toast {
      position: fixed;
      bottom: 20px;
      left: 20px;
      background: #333;
      color: #fff;
      padding: 15px 20px;
      border-radius: 2px;
      display: none;
      z-index: 1000;
      font-size: 13px;
      animation: slideIn 0.3s ease-out;
      max-width: 300px;
    }
    .toast.active {
      display: block;
    }
    .toast.error {
      background: #d32f2f;
    }
    @keyframes slideIn {
      from {
        transform: translateX(-100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    /* Admin & Cart Styles */
    .admin-controls {
      display: none;
      gap: 10px;
    }
    .admin-controls.visible {
      display: flex;
    }
    .admin-btn {
      padding: 8px 14px;
      background: #333;
      color: #fff;
      border: 1px solid #333;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
      font-weight: 400;
      font-family: Roboto, sans-serif;
    }
    .admin-btn:hover {
      background: #555;
    }
    .logout-btn {
      background: #f5f5f5;
      color: #333;
      border-color: #ddd;
    }
    .logout-btn:hover {
      background: #eee;
    }
    .cart-btn {
      background: #4ecdc4;
      color: #fff;
      border-color: #4ecdc4;
    }
    .cart-btn:hover {
      background: #36a39e;
    }
    .cart-btn.hidden {
      display: none;
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }
    .modal.active {
      display: flex;
    }
    .modal-content {
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      width: 90%;
      max-width: 500px;
      max-height: 80vh;
      overflow-y: auto;
    }
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #ddd;
    }
    .modal-header h2 {
      font-size: 20px;
      font-weight: 400;
    }
    .close-btn {
      background: none;
      border: none;
      font-size: 28px;
      cursor: pointer;
      color: #999;
    }
    .close-btn:hover {
      color: #333;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
      color: #333;
    }
    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-family: Roboto, sans-serif;
      font-size: 14px;
    }
    .form-group textarea {
      resize: vertical;
      min-height: 60px;
    }
    .lang-inputs {
      border: 1px solid #ddd;
      padding: 10px;
      border-radius: 4px;
      background: #f9f9f9;
    }
    .lang-input {
      margin-bottom: 10px;
    }
    .lang-input:last-child {
      margin-bottom: 0;
    }
    .lang-label {
      display: block;
      font-weight: 400;
      font-size: 12px;
      color: #666;
      margin-bottom: 3px;
    }
    .btn-group {
      display: flex;
      gap: 10px;
      margin-top: 20px;
    }
    .btn {
      flex: 1;
      padding: 10px;
      background: #333;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-family: Roboto, sans-serif;
    }
    .btn:hover {
      background: #555;
    }
    .btn-danger {
      background: #ff4444;
    }
    .btn-danger:hover {
      background: #cc0000;
    }

    /* Cart Styles */
    .cart-items-list {
      list-style: none;
      margin: 20px 0;
    }
    .cart-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px;
      border-bottom: 1px solid #ddd;
      font-size: 14px;
    }
    .cart-item-name {
      flex: 1;
    }
    .cart-item-qty {
      margin: 0 10px;
      min-width: 60px;
      display: flex;
      gap: 5px;
      align-items: center;
    }
    .qty-btn {
      background: #f0f0f0;
      border: 1px solid #ddd;
      padding: 4px 8px;
      cursor: pointer;
      border-radius: 2px;
      font-size: 12px;
    }
    .qty-btn:hover {
      background: #e0e0e0;
    }
    .cart-item-price {
      min-width: 80px;
      text-align: right;
      font-weight: 500;
    }
    .cart-summary {
      margin-top: 20px;
      padding-top: 20px;
      border-top: 2px solid #ddd;
    }
    .summary-row {
      display: flex;
      justify-content: space-between;
      padding: 8px 0;
      font-size: 14px;
    }
    .summary-row.total {
      font-size: 18px;
      font-weight: 600;
      color: #333;
    }
    .summary-row.tax {
      color: #666;
      font-size: 12px;
    }
    .empty-cart {
      text-align: center;
      padding: 40px 20px;
      color: #999;
    }
    .cart-comments {
      margin-top: 20px;
      padding: 15px;
      background: #f9f9f9;
      border-radius: 4px;
      border: 1px solid #eee;
    }
    .cart-comments label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      font-size: 14px;
      color: #333;
    }
    .cart-comments textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-family: Roboto, sans-serif;
      font-size: 14px;
      resize: vertical;
      min-height: 80px;
      max-height: 150px;
      box-sizing: border-box;
    }
    .cart-comments textarea:focus {
      outline: none;
      border-color: #333;
      box-shadow: 0 0 0 2px rgba(0,0,0,0.05);
    }
    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .section-header h2 {
      margin: 0;
      flex: 1;
    }
    .section-admin-btns {
      display: flex;
      gap: 8px;
    }
    .btn-edit, .btn-delete, .btn-add-item {
      padding: 6px 10px;
      font-size: 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: 500;
    }
    .btn-edit {
      background: #4ecdc4;
      color: #1a1a2e;
    }
    .btn-edit:hover {
      background: #3db5b1;
    }
    .btn-delete {
      background: #ff6b6b;
      color: white;
    }
    .btn-delete:hover {
      background: #ee5a52;
    }
    .btn-add-item {
      background: #95e1d3;
      color: #1a1a2e;
    }
    .btn-add-item:hover {
      background: #7dd4c2;
    }
    .menu-item-desc {
      font-size: 13px;
      color: #666;
      margin: 5px 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>🥢 Yanji Restaurant Menu</h1>
      <div class="header-controls">
        <div class="admin-controls" id="admin-controls">
          <button class="admin-btn" onclick="openAddSectionModal()">+ Section</button>
          <button class="admin-btn" onclick="openAddItemModal()">+ Item</button>
          <button class="admin-btn logout-btn" onclick="logout()">Logout</button>
        </div>
        <button class="admin-btn cart-btn" id="cart-btn" onclick="openCartModal()">🛒 Cart (<span id="cart-count">0</span>)</button>
        <div class="lang-switch">
          <button class="lang-btn active" data-lang="en">EN</button>
          <button class="lang-btn" data-lang="zh">中文</button>
          <button class="lang-btn" data-lang="ko">한글</button>
        </div>
      </div>
    </header>

    <div class="menu-content" id="menu-content">
      <!-- Dynamically loaded from JSON -->
    </div>

    <div class="note" id="service-note">
      12.5% service charge will be added
    </div>
  </div>

  <!-- Toast Notification -->
  <div class="toast" id="toast"></div>

  <script>
    console.log('Menu page loaded - no auth.js dependency');
    const API_BASE = window.YANJI_CONFIG?.API_BASE || 'https://yanji.tunesbasis.com';
    let menuData = null;
    let isAdmin = false;
    let currentLang = 'en';
    let tableNumber = null;
    let hasTableParam = false;

    // Get table number from URL query parameter
    function getTableNumber() {
      const params = new URLSearchParams(window.location.search);
      return params.get('table');
    }

    // Check if table query parameter exists
    function checkTableParam() {
      hasTableParam = !!getTableNumber();
      // Hide/show cart button based on table parameter
      const cartBtn = document.getElementById('cart-btn');
      if (cartBtn) {
        if (hasTableParam) {
          cartBtn.classList.remove('hidden');
        } else {
          cartBtn.classList.add('hidden');
        }
      }
    }

    const serviceNotes = {
      en: '12.5% service charge will be added',
      zh: '12.5% 服务费将被添加',
      ko: '12.5% 서비스 차지가 추가됩니다'
    };

    // ==================== Authentication ====================
    function checkAuthentication() {
      // Check if user has a valid token in localStorage (set by auth.js on other pages)
      const token = localStorage.getItem('yanji-access-token');
      isAdmin = !!token && token.length > 0;
      
      if (isAdmin) {
        document.getElementById('admin-controls').classList.add('visible');
      }
    }

    function logout() {
      // Clear tokens
      localStorage.removeItem('yanji-access-token');
      localStorage.removeItem('yanji-id-token');
      localStorage.removeItem('yanji-refresh-token');
      localStorage.removeItem('yanji-token-expiry');
      
      isAdmin = false;
      document.getElementById('admin-controls').classList.remove('visible');
      location.reload();
    }

    // ==================== Menu Loading & Rendering ====================
    async function loadMenuData() {
      try {
        // Try to load from API first
        const response = await fetch(`${API_BASE}/menu`, {
          headers: {
            'Authorization': localStorage.getItem('yanji-access-token') ? `Bearer ${localStorage.getItem('yanji-access-token')}` : ''
          }
        });
        
        if (response.ok) {
          menuData = await response.json();
          console.log('Menu loaded from API');
        } else {
          throw new Error(`API returned ${response.status}`);
        }
        renderMenu();
      } catch (error) {
        console.error('Failed to load menu from API:', error);
        // Fallback to JSON file
        try {
          console.log('Falling back to JSON file');
          const fallbackResponse = await fetch('/booking/menu-data.json');
          if (!fallbackResponse.ok) throw new Error('JSON file not found');
          menuData = await fallbackResponse.json();
          renderMenu();
        } catch (fallbackError) {
          console.error('Fallback also failed:', fallbackError);
          document.getElementById('menu-content').innerHTML = '<div style="text-align: center; padding: 40px; color: #999;">Failed to load menu. Please try again later.</div>';
          showToast('Failed to load menu', true);
        }
      }
    }

    function renderMenu() {
      const container = document.getElementById('menu-content');
      container.innerHTML = '';

      menuData.sections.forEach(section => {
        const sectionDiv = document.createElement('div');
        sectionDiv.className = 'menu-section';

        // Section title with admin controls
        const sectionTitleDiv = document.createElement('div');
        sectionTitleDiv.className = 'section-header';
        const sectionTitle = document.createElement('h2');
        const sectionName = section.names[currentLang] || section.names.en;
        sectionTitle.textContent = `${section.icon} ${sectionName}`;
        sectionTitleDiv.appendChild(sectionTitle);

        // Admin controls for section
        if (isAdmin) {
          const adminBtns = document.createElement('div');
          adminBtns.className = 'section-admin-btns';
          adminBtns.innerHTML = `
            <button class="btn-edit" onclick="openEditSectionModal('${section.sectionId}')">Edit</button>
            <button class="btn-delete" onclick="deleteSection('${section.sectionId}')">Delete</button>
            <button class="btn-add-item" onclick="openAddItemModal('${section.sectionId}')">+ Item</button>
          `;
          sectionTitleDiv.appendChild(adminBtns);
        }
        sectionDiv.appendChild(sectionTitleDiv);

        // Menu items
        section.items.forEach(item => {
          const itemDiv = document.createElement('div');
          itemDiv.className = 'menu-item';
          itemDiv.setAttribute('data-item-id', item.itemId);

          const itemName = item.names[currentLang] || item.names.en;
          const itemDesc = item.description?.[currentLang] || item.description?.en || '';

          let html = ``;

          // Image with selection interface for admin only
          if (isAdmin) {
            html += `
              <div class="menu-item-image-container">
                <div class="menu-item-image"><img src="${item.imageUrl || '/booking/menu-images/menu_not_updated.png'}" alt="${itemName}"></div>
                <div class="image-select-section">
                  <select id="image-select-${item.itemId}" class="image-select" onchange="selectImage('${item.itemId}', this.value)" style="width: 100%; padding: 8px; margin-bottom: 8px; background: #252542; color: #fff; border: 1px solid #444; border-radius: 4px;">
                    <option value="">-- Select Image --</option>
                  </select>
                  <button onclick="loadAvailableImages('${item.itemId}')" style="width: 100%; padding: 6px; background: #4ecdc4; color: #1a1a2e; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold;">Refresh Images</button>
                </div>
              </div>
            `;
          } else if (item.imageUrl) {
            // Show image for non-authenticated users if it exists
            html += `
              <div class="menu-item-image">
                <img src="${item.imageUrl}" alt="${itemName}">
              </div>
            `;
          } else {
            // Show default placeholder for non-authenticated users without image
            html += `
              <div class="menu-item-image">
                <img src="/booking/menu-images/menu_not_updated.png" alt="${itemName}">
              </div>
            `;
          }

          html += `
            <div class="menu-item-name">${itemName}</div>
            ${itemDesc ? `<div class="menu-item-desc">${itemDesc}</div>` : ''}
            <div class="menu-item-price">£${item.price.toFixed(2)}</div>
            <div class="menu-item-buttons">
              ${hasTableParam ? `<button class="order-btn" onclick="addToCart('${item.itemId}', '${itemName}', ${item.price})">Add to Cart</button>` : ''}
              ${isAdmin ? `<button class="btn-edit" style="font-size: 12px; flex: 0.5;" onclick="openEditItemModal('${section.sectionId}', '${item.itemId}')">Edit</button>
                          <button class="btn-delete" style="font-size: 12px; flex: 0.5;" onclick="deleteItem('${item.itemId}')">Delete</button>` : ''}
            </div>
          `;

          itemDiv.innerHTML = html;
          sectionDiv.appendChild(itemDiv);
        });

        container.appendChild(sectionDiv);
      });

      // Update service note
      document.getElementById('service-note').textContent = serviceNotes[currentLang] || serviceNotes.en;
      
      // Load images for all items if admin
      if (isAdmin) {
        menuData.sections.forEach(section => {
          section.items.forEach(item => {
            loadAvailableImages(item.itemId);
          });
        });
      }

      // Update item section selector in modal
      const itemSectionSelect = document.getElementById('itemSection');
      if (itemSectionSelect) {
        itemSectionSelect.innerHTML = '<option value="">-- Select Section --</option>';
        menuData.sections.forEach(s => {
          const optionName = s.names[currentLang] || s.names.en;
          itemSectionSelect.innerHTML += `<option value="${s.sectionId}">${s.icon} ${optionName}</option>`;
        });
      }
    }
    // ==================== Auto-refresh images every 5 seconds ====================
    setInterval(() => {
      if (isAdmin && menuData) {
        menuData.sections.forEach(section => {
          section.items.forEach(item => {
            loadAvailableImages(item.itemId);
          });
        });
      }
    }, 5000);
    // ==================== Image Handling ====================
    function findItemById(itemId) {
      for (let section of menuData.sections) {
        const item = section.items.find(i => i.itemId === itemId);
        if (item) return item;
      }
      return null;
    }

    // ==================== Image Selection ====================
    function loadAvailableImages(itemId) {
      fetch('/booking/list-images.php')
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const select = document.getElementById(`image-select-${itemId}`);
            if (select) {
              // Keep the default option
              const defaultOption = select.options[0];
              select.innerHTML = '';
              select.appendChild(defaultOption);
              
              // Add images
              data.images.forEach(img => {
                const option = document.createElement('option');
                option.value = img.url;
                option.textContent = img.name;
                select.appendChild(option);
              });
              
              // Pre-select current image if exists
              const currentItem = findItemById(itemId);
              if (currentItem && currentItem.imageUrl) {
                select.value = currentItem.imageUrl;
              }
            }
          }
        })
        .catch(err => console.error('Error loading images:', err));
    }

    function selectImage(itemId, imageUrl) {
      const currentItem = findItemById(itemId);
      if (!currentItem) return;

      currentItem.imageUrl = imageUrl || null;

      // Call backend API directly
      const token = localStorage.getItem('yanji-access-token');
      const apiUrl = 'https://yanji.tunesbasis.com/menu/items/' + encodeURIComponent(itemId);
      
      fetch(apiUrl, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({
          imageUrl: imageUrl || null
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.itemId) {
          // Success - API returns the updated item
          showToast('Image updated!');
          renderMenu();
        } else if (data.message) {
          showToast('Error updating image: ' + data.message, true);
        } else {
          showToast('Error updating image', true);
        }
      })
      .catch(err => {
        console.error('Error updating image:', err);
        showToast('Error updating image: ' + err.message, true);
      });
    }

    // ==================== Language Switching ====================
    document.querySelectorAll('.lang-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        currentLang = btn.dataset.lang;
        
        document.querySelectorAll('.lang-btn').forEach(b => {
          b.classList.remove('active');
        });
        btn.classList.add('active');
        
        renderMenu();
      });
    });

    // ==================== Toast Notification ====================
    function showToast(message, isError = false) {
      const toast = document.getElementById('toast');
      toast.textContent = message;
      toast.className = 'toast active' + (isError ? ' error' : '');
      setTimeout(() => toast.classList.remove('active'), 3000);
    }

    // ==================== Shopping Cart ====================
    let cart = JSON.parse(localStorage.getItem('yanji-cart')) || [];
    loadOrderComments();

    function addToCart(itemId, itemName, price) {
      const existingItem = cart.find(item => item.itemId === itemId);
      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        cart.push({ itemId, name: itemName, price, quantity: 1 });
      }
      localStorage.setItem('yanji-cart', JSON.stringify(cart));
      updateCartCount();
      showToast(`${itemName} added to cart!`);
    }

    function updateCartCount() {
      const count = cart.reduce((sum, item) => sum + item.quantity, 0);
      document.getElementById('cart-count').textContent = count;
    }

    function updateItemQuantity(itemId, quantity) {
      const item = cart.find(i => i.itemId === itemId);
      if (item) {
        if (quantity <= 0) {
          cart = cart.filter(i => i.itemId !== itemId);
        } else {
          item.quantity = quantity;
        }
        localStorage.setItem('yanji-cart', JSON.stringify(cart));
        updateCartCount();
        renderCart();
      }
    }

    function openCartModal() {
      document.getElementById('cartModal').classList.add('active');
      renderCart();
    }

    function closeCartModal() {
      document.getElementById('cartModal').classList.remove('active');
    }

    function renderCart() {
      const cartItemsDiv = document.getElementById('cart-items');
      const cartSummaryDiv = document.getElementById('cart-summary');

      if (cart.length === 0) {
        cartItemsDiv.innerHTML = '<div class="empty-cart">Your cart is empty</div>';
        cartSummaryDiv.innerHTML = '';
        return;
      }

      let itemsHtml = '<ul class="cart-items-list">';
      let subtotal = 0;

      cart.forEach(item => {
        const total = item.price * item.quantity;
        subtotal += total;
        itemsHtml += `
          <li class="cart-item">
            <div class="cart-item-name">${item.name}</div>
            <div class="cart-item-qty">
              <button class="qty-btn" onclick="updateItemQuantity('${item.itemId}', ${item.quantity - 1})">-</button>
              <span>${item.quantity}</span>
              <button class="qty-btn" onclick="updateItemQuantity('${item.itemId}', ${item.quantity + 1})">+</button>
            </div>
            <div class="cart-item-price">£${total.toFixed(2)}</div>
          </li>
        `;
      });
      itemsHtml += '</ul>';

      const VAT_RATE = 0.20; // 20% VAT in London
      const vat = subtotal * VAT_RATE;
      const total = subtotal + vat;

      const summaryHtml = `
        <div class="cart-summary">
          <div class="summary-row">
            <span>Subtotal:</span>
            <span>£${subtotal.toFixed(2)}</span>
          </div>
          <div class="summary-row tax">
            <span>VAT (20% - London):</span>
            <span>£${vat.toFixed(2)}</span>
          </div>
          <div class="summary-row total">
            <span>Total:</span>
            <span>£${total.toFixed(2)}</span>
          </div>
        </div>
        <div class="cart-comments">
          <label for="order-comments">Special Requests / Comments:</label>
          <textarea id="order-comments" placeholder="Add any special requests or dietary requirements..." onchange="saveOrderComments()">${window.orderComments || ''}</textarea>
        </div>
      `;

      cartItemsDiv.innerHTML = itemsHtml;
      cartSummaryDiv.innerHTML = summaryHtml;
    }

    function saveOrderComments() {
      window.orderComments = document.getElementById('order-comments').value;
      localStorage.setItem('yanji-order-comments', window.orderComments);
    }

    function loadOrderComments() {
      window.orderComments = localStorage.getItem('yanji-order-comments') || '';
    }

    async function placeOrder() {
      // Get table number from URL
      const urlParams = new URLSearchParams(window.location.search);
      const tableNumber = parseInt(urlParams.get('table'), 10);

      if (!tableNumber || isNaN(tableNumber)) {
        showToast('Please specify table number in URL (?table=N)', true);
        return;
      }

      if (cart.length === 0) {
        showToast('Cart is empty', true);
        return;
      }

      const placeOrderBtn = document.getElementById('place-order-btn');
      const msgDiv = document.getElementById('order-message');
      
      // Disable button during submission
      placeOrderBtn.disabled = true;
      placeOrderBtn.textContent = 'Placing Order...';
      msgDiv.innerHTML = '';

      try {
        const orderData = {
          tableNumber: tableNumber,
          items: cart,
          comments: window.orderComments || '',
        };

        const response = await fetch('https://yanji.tunesbasis.com/orders', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(orderData),
        });

        const data = await response.json();

        if (!response.ok) {
          throw new Error(data.message || 'Failed to place order');
        }

        // Success
        msgDiv.innerHTML = `<div style="color: #4CAF50; padding: 10px; background: #e8f5e9; border-radius: 4px; margin-bottom: 10px;">✓ ${data.message}<br>Order ID: ${data.orderId.slice(0, 8)}</div>`;
        
        // Clear cart
        cart = [];
        localStorage.removeItem('yanji-cart');
        localStorage.removeItem('yanji-order-comments');
        window.orderComments = '';
        updateCartCount();
        renderCart();

        // Close modal after 2 seconds
        setTimeout(() => {
          closeCartModal();
        }, 2000);

      } catch (error) {
        msgDiv.innerHTML = `<div style="color: #f44336; padding: 10px; background: #ffebee; border-radius: 4px; margin-bottom: 10px;">✗ Error: ${error.message}</div>`;
        showToast('Order failed: ' + error.message, true);
      } finally {
        placeOrderBtn.disabled = false;
        placeOrderBtn.textContent = 'Place Order';
      }
    }

    // ==================== Admin CRUD ====================
    let editingSectionId = null;
    let editingItemId = null;
    let editingSectionIdForItem = null;

    function openAddSectionModal() {
      editingSectionId = null;
      document.getElementById('sectionModalTitle').textContent = 'Add Section';
      document.getElementById('sectionIcon').value = '';
      document.getElementById('sectionNameEn').value = '';
      document.getElementById('sectionNameKo').value = '';
      document.getElementById('sectionNameZh').value = '';
      document.getElementById('sectionModal').classList.add('active');
    }

    function openEditSectionModal(sectionId) {
      const section = menuData.sections.find(s => s.sectionId === sectionId);
      if (!section) return;

      editingSectionId = sectionId;
      document.getElementById('sectionModalTitle').textContent = 'Edit Section';
      document.getElementById('sectionIcon').value = section.icon;
      document.getElementById('sectionNameEn').value = section.names.en || '';
      document.getElementById('sectionNameKo').value = section.names.ko || '';
      document.getElementById('sectionNameZh').value = section.names.zh || '';
      document.getElementById('sectionModal').classList.add('active');
    }

    function closeSectionModal() {
      document.getElementById('sectionModal').classList.remove('active');
      editingSectionId = null;
    }

    async function saveSectionModal(event) {
      event.preventDefault();
      const data = {
        icon: document.getElementById('sectionIcon').value,
        names: {
          en: document.getElementById('sectionNameEn').value,
          ko: document.getElementById('sectionNameKo').value,
          zh: document.getElementById('sectionNameZh').value,
        }
      };

      try {
        const url = editingSectionId 
          ? `${API_BASE}/menu/sections/${editingSectionId}`
          : `${API_BASE}/menu/sections`;
        const method = editingSectionId ? 'PUT' : 'POST';
        const token = localStorage.getItem('yanji-access-token');

        const response = await fetch(url, {
          method,
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        });

        if (!response.ok) throw new Error('Failed to save section');
        showToast(editingSectionId ? 'Section updated!' : 'Section created!');
        closeSectionModal();
        loadMenuData();
      } catch (error) {
        showToast('Error: ' + error.message, true);
      }
    }

    async function deleteSection(sectionId) {
      if (!confirm('Delete this section and all items?')) return;

      try {
        const token = localStorage.getItem('yanji-access-token');
        const response = await fetch(`${API_BASE}/menu/sections/${sectionId}`, {
          method: 'DELETE',
          headers: { 'Authorization': `Bearer ${token}` }
        });

        if (!response.ok) throw new Error('Failed to delete');
        showToast('Section deleted!');
        loadMenuData();
      } catch (error) {
        showToast('Error: ' + error.message, true);
      }
    }

    function openAddItemModal(sectionId) {
      if (!sectionId && menuData.sections.length === 0) {
        showToast('Create a section first', true);
        return;
      }

      editingItemId = null;
      editingSectionIdForItem = sectionId || (menuData.sections[0]?.sectionId || '');
      document.getElementById('itemModalTitle').textContent = 'Add Item';
      document.getElementById('itemSection').value = editingSectionIdForItem;
      document.getElementById('itemNameEn').value = '';
      document.getElementById('itemNameKo').value = '';
      document.getElementById('itemNameZh').value = '';
      document.getElementById('itemDescEn').value = '';
      document.getElementById('itemDescKo').value = '';
      document.getElementById('itemDescZh').value = '';
      document.getElementById('itemPrice').value = '';
      document.getElementById('itemModal').classList.add('active');
    }

    function openEditItemModal(sectionId, itemId) {
      const section = menuData.sections.find(s => s.sectionId === sectionId);
      const item = section?.items.find(i => i.itemId === itemId);
      if (!item) return;

      editingItemId = itemId;
      editingSectionIdForItem = sectionId;
      document.getElementById('itemModalTitle').textContent = 'Edit Item';
      document.getElementById('itemSection').value = sectionId;
      document.getElementById('itemNameEn').value = item.names.en || '';
      document.getElementById('itemNameKo').value = item.names.ko || '';
      document.getElementById('itemNameZh').value = item.names.zh || '';
      document.getElementById('itemDescEn').value = item.description?.en || '';
      document.getElementById('itemDescKo').value = item.description?.ko || '';
      document.getElementById('itemDescZh').value = item.description?.zh || '';
      document.getElementById('itemPrice').value = item.price;
      document.getElementById('itemModal').classList.add('active');
    }

    function closeItemModal() {
      document.getElementById('itemModal').classList.remove('active');
      editingItemId = null;
    }

    async function saveItemModal(event) {
      event.preventDefault();
      const data = {
        sectionId: document.getElementById('itemSection').value,
        names: {
          en: document.getElementById('itemNameEn').value,
          ko: document.getElementById('itemNameKo').value,
          zh: document.getElementById('itemNameZh').value,
        },
        description: {
          en: document.getElementById('itemDescEn').value,
          ko: document.getElementById('itemDescKo').value,
          zh: document.getElementById('itemDescZh').value,
        },
        price: parseFloat(document.getElementById('itemPrice').value)
      };

      try {
        const url = editingItemId 
          ? `${API_BASE}/menu/items/${editingItemId}`
          : `${API_BASE}/menu/items`;
        const method = editingItemId ? 'PUT' : 'POST';
        const token = localStorage.getItem('yanji-access-token');

        const response = await fetch(url, {
          method,
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        });

        if (!response.ok) throw new Error('Failed to save item');
        showToast(editingItemId ? 'Item updated!' : 'Item created!');
        closeItemModal();
        loadMenuData();
      } catch (error) {
        showToast('Error: ' + error.message, true);
      }
    }

    async function deleteItem(itemId) {
      if (!confirm('Delete this item?')) return;

      try {
        const token = localStorage.getItem('yanji-access-token');
        const response = await fetch(`${API_BASE}/menu/items/${itemId}`, {
          method: 'DELETE',
          headers: { 'Authorization': `Bearer ${token}` }
        });

        if (!response.ok) throw new Error('Failed to delete');
        showToast('Item deleted!');
        loadMenuData();
      } catch (error) {
        showToast('Error: ' + error.message, true);
      }
    }

    // ==================== Initialize ====================
    tableNumber = getTableNumber();
    checkTableParam();
    checkAuthentication();
    loadMenuData();
    updateCartCount();
  </script>

  <!-- Section Modal -->
  <div id="sectionModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="sectionModalTitle">Add Section</h2>
        <button class="close-btn" onclick="closeSectionModal()">&times;</button>
      </div>
      <form onsubmit="saveSectionModal(event)">
        <div class="form-group">
          <label>Icon (emoji)</label>
          <input type="text" id="sectionIcon" maxlength="2" placeholder="🥢" required>
        </div>
        <div class="form-group">
          <label>Section Names</label>
          <div class="lang-inputs">
            <div class="lang-input">
              <span class="lang-label">English</span>
              <input type="text" id="sectionNameEn" required>
            </div>
            <div class="lang-input">
              <span class="lang-label">Korean</span>
              <input type="text" id="sectionNameKo">
            </div>
            <div class="lang-input">
              <span class="lang-label">Chinese</span>
              <input type="text" id="sectionNameZh">
            </div>
          </div>
        </div>
        <div class="btn-group">
          <button type="submit" class="btn">Save Section</button>
          <button type="button" class="btn" onclick="closeSectionModal()">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Item Modal -->
  <div id="itemModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="itemModalTitle">Add Item</h2>
        <button class="close-btn" onclick="closeItemModal()">&times;</button>
      </div>
      <form onsubmit="saveItemModal(event)">
        <div class="form-group">
          <label>Section</label>
          <select id="itemSection" required>
            <option value="">-- Select Section --</option>
          </select>
        </div>
        <div class="form-group">
          <label>Item Names</label>
          <div class="lang-inputs">
            <div class="lang-input">
              <span class="lang-label">English</span>
              <input type="text" id="itemNameEn" required>
            </div>
            <div class="lang-input">
              <span class="lang-label">Korean</span>
              <input type="text" id="itemNameKo">
            </div>
            <div class="lang-input">
              <span class="lang-label">Chinese</span>
              <input type="text" id="itemNameZh">
            </div>
          </div>
        </div>
        <div class="form-group">
          <label>Description</label>
          <div class="lang-inputs">
            <div class="lang-input">
              <span class="lang-label">English</span>
              <textarea id="itemDescEn"></textarea>
            </div>
            <div class="lang-input">
              <span class="lang-label">Korean</span>
              <textarea id="itemDescKo"></textarea>
            </div>
            <div class="lang-input">
              <span class="lang-label">Chinese</span>
              <textarea id="itemDescZh"></textarea>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label>Price (£)</label>
          <input type="number" id="itemPrice" min="0" step="0.01" required>
        </div>
        <div class="btn-group">
          <button type="submit" class="btn">Save Item</button>
          <button type="button" class="btn" onclick="closeItemModal()">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Cart Modal -->
  <div id="cartModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>🛒 Shopping Cart</h2>
        <button class="close-btn" onclick="closeCartModal()">&times;</button>
      </div>
      <div id="cart-items"></div>
      <div id="cart-summary"></div>
      <div id="order-message" style="margin-top: 15px;"></div>
      <div class="btn-group" style="margin-top: 20px;">
        <button class="btn" id="place-order-btn" onclick="placeOrder()" style="background: #ff6b35; flex: 1;">Place Order</button>
        <button class="btn" onclick="closeCartModal()" style="flex: 1;">Continue Shopping</button>
      </div>
    </div>
  </div>
</body>
</html>

