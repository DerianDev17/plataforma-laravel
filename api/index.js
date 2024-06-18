const express = require('express');
const { createProxyMiddleware } = require('http-proxy-middleware');
const app = express();

app.use(
  '/',
  createProxyMiddleware({
    target: 'http://127.0.0.1:3306', // Laravel server
    changeOrigin: true,
  })
);

app.listen(3000, () => {
  console.log('Proxy server running on port 3000');
});

module.exports = app;
