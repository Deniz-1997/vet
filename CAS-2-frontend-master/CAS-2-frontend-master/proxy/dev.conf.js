const PROXY_CONFIG = {
  "/api": {
    "target": "http://173.55.1.3/",
    "secure": false,
    "changeOrigin": true,
  },
  "/docs": {
    "target": "http://173.55.1.3/",
    "secure": false,
    "changeOrigin": true,
  },
  "/uploaded": {
    "target": "http://173.55.1.3/",
    "secure": false,
    "changeOrigin": true,
  },
};

module.exports = PROXY_CONFIG;
