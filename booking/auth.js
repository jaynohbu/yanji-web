// Yanji Admin Authentication Helper
// Include this script in admin pages to protect them
// IMPORTANT: Include config.js before this script

(function() {
  'use strict';

  // API Base URL - fallback to production if config.js not loaded
  const API_BASE = window.API_BASE || 'https://yanji.tunesbasis.com';

  const AUTH_KEYS = {
    accessToken: 'yanji-access-token',
    idToken: 'yanji-id-token',
    refreshToken: 'yanji-refresh-token',
    tokenExpiry: 'yanji-token-expiry'
  };

  // Login page in same directory
  const LOGIN_PAGE = 'login.html';

  // Get stored tokens
  function getTokens() {
    return {
      accessToken: localStorage.getItem(AUTH_KEYS.accessToken),
      idToken: localStorage.getItem(AUTH_KEYS.idToken),
      refreshToken: localStorage.getItem(AUTH_KEYS.refreshToken),
      expiry: parseInt(localStorage.getItem(AUTH_KEYS.tokenExpiry) || '0')
    };
  }

  // Store tokens
  function storeTokens(tokens) {
    localStorage.setItem(AUTH_KEYS.accessToken, tokens.accessToken);
    localStorage.setItem(AUTH_KEYS.idToken, tokens.idToken);
    if (tokens.refreshToken) {
      localStorage.setItem(AUTH_KEYS.refreshToken, tokens.refreshToken);
    }
    localStorage.setItem(AUTH_KEYS.tokenExpiry, Date.now() + (tokens.expiresIn * 1000));
  }

  // Clear tokens
  function clearTokens() {
    Object.values(AUTH_KEYS).forEach(key => localStorage.removeItem(key));
  }

  // Check if token is expired or about to expire (within 5 minutes)
  function isTokenExpired() {
    const tokens = getTokens();
    if (!tokens.expiry) return true;
    return Date.now() > (tokens.expiry - (5 * 60 * 1000));
  }

  // Refresh access token
  async function refreshAccessToken() {
    const tokens = getTokens();
    if (!tokens.refreshToken) {
      return false;
    }

    try {
      const response = await fetch(`${API_BASE}/auth/refresh`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ refreshToken: tokens.refreshToken })
      });

      if (!response.ok) {
        return false;
      }

      const data = await response.json();
      storeTokens(data);
      return true;
    } catch (error) {
      console.error('Token refresh failed:', error);
      return false;
    }
  }

  // Verify token with backend
  async function verifyToken() {
    const tokens = getTokens();
    if (!tokens.accessToken) {
      return false;
    }

    try {
      const response = await fetch(`${API_BASE}/auth/verify`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${tokens.accessToken}`
        }
      });

      return response.ok;
    } catch (error) {
      console.error('Token verification failed:', error);
      return false;
    }
  }

  // Redirect to login page
  function redirectToLogin() {
    clearTokens();
    window.location.href = LOGIN_PAGE;
  }

  // Main authentication check
  async function checkAuth() {
    const tokens = getTokens();

    // No tokens at all
    if (!tokens.accessToken) {
      redirectToLogin();
      return false;
    }

    // Token expired, try to refresh
    if (isTokenExpired()) {
      const refreshed = await refreshAccessToken();
      if (!refreshed) {
        redirectToLogin();
        return false;
      }
    }

    // Verify token is still valid
    const isValid = await verifyToken();
    if (!isValid) {
      const refreshed = await refreshAccessToken();
      if (!refreshed) {
        redirectToLogin();
        return false;
      }
    }

    return true;
  }

  // Get current access token for API calls
  function getAccessToken() {
    return localStorage.getItem(AUTH_KEYS.accessToken);
  }

  // Logout function
  async function logout() {
    const token = getAccessToken();

    try {
      if (token) {
        await fetch(`${API_BASE}/auth/logout`, {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
      }
    } catch (error) {
      console.error('Logout error:', error);
    }

    clearTokens();
    window.location.href = LOGIN_PAGE;
  }

  // Add authorization header to fetch requests
  function authFetch(url, options = {}) {
    const token = getAccessToken();
    const headers = options.headers || {};

    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    return fetch(url, { ...options, headers });
  }

  // Set up automatic token refresh
  function setupAutoRefresh() {
    setInterval(async () => {
      if (isTokenExpired()) {
        const refreshed = await refreshAccessToken();
        if (!refreshed) {
          redirectToLogin();
        }
      }
    }, 60000);
  }

  // Create logout button and add to page
  function addLogoutButton() {
    let container = document.getElementById('auth-logout-container');

    if (!container) {
      container = document.createElement('div');
      container.id = 'auth-logout-container';
      container.style.cssText = `
        position: fixed;
        top: 15px;
        right: 15px;
        z-index: 10000;
        display: flex;
        gap: 10px;
        align-items: center;
      `;
      document.body.appendChild(container);
    }

    const logoutBtn = document.createElement('button');
    logoutBtn.id = 'auth-logout-btn';
    logoutBtn.innerHTML = `
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
        <polyline points="16 17 21 12 16 7"></polyline>
        <line x1="21" y1="12" x2="9" y2="12"></line>
      </svg>
      <span>Logout</span>
    `;
    logoutBtn.style.cssText = `
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      background: #dc3545;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.2s;
    `;
    logoutBtn.onmouseover = () => logoutBtn.style.background = '#c82333';
    logoutBtn.onmouseout = () => logoutBtn.style.background = '#dc3545';
    logoutBtn.onclick = logout;

    container.appendChild(logoutBtn);
  }

  // Initialize authentication
  async function init() {
    const loadingOverlay = document.createElement('div');
    loadingOverlay.id = 'auth-loading';
    loadingOverlay.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255,255,255,0.95);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 99999;
    `;
    loadingOverlay.innerHTML = `
      <div style="text-align: center;">
        <div style="
          width: 40px;
          height: 40px;
          border: 3px solid #f0f0f0;
          border-top-color: #4ecdc4;
          border-radius: 50%;
          animation: spin 1s linear infinite;
          margin: 0 auto 15px;
        "></div>
        <div style="color: #666; font-size: 14px;">Verifying authentication...</div>
      </div>
      <style>
        @keyframes spin { to { transform: rotate(360deg); } }
      </style>
    `;
    document.body.appendChild(loadingOverlay);

    const isAuthenticated = await checkAuth();

    if (isAuthenticated) {
      loadingOverlay.remove();
      addLogoutButton();
      setupAutoRefresh();
      window.dispatchEvent(new Event('yanji-auth-ready'));
    }
  }

  // Export to window
  window.YanjiAuth = {
    checkAuth,
    logout,
    getAccessToken,
    authFetch,
    refreshAccessToken,
    isTokenExpired,
    clearTokens
  };

  // Auto-initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
