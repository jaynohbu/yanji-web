// Yanji Restaurant - Frontend Configuration
// Change API_BASE to your production backend URL

window.YANJI_CONFIG = {
  // Development: 'http://localhost:3000'
  // Production: 'https://yanji.tunesbasis.com'
  API_BASE: 'https://yanji.tunesbasis.com',

  // Set to false to temporarily disable online reservations
  // This is a quick frontend toggle - backend also has its own flag
  RESERVATIONS_ENABLED: true
};

// Also set as global variable for backwards compatibility
window.API_BASE = window.YANJI_CONFIG.API_BASE;
