const express = require('express');
const { createProxyMiddleware } = require('http-proxy-middleware');
const app = express();

app.use(
  '/',
  createProxyMiddleware({
    target: 'http://127.0.0.1:8000', // Laravel server
    changeOrigin: true,
    onProxyReq: (proxyReq, req, res) => {
      proxyReq.setHeader('Host', '127.0.0.1:8000');
    },
    onError: (err, req, res) => {
      res.status(500).send('Proxy error: ' + err.message);
    }
  })
);

app.listen(3000, () => {
  console.log('Proxy server running on port 3000');
});

module.exports = app;
