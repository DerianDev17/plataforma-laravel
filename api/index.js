const express = require('express');
const { createProxyMiddleware } = require('http-proxy-middleware');
const app = express();

app.use(
  '/',
  createProxyMiddleware({
    target: 'http://127.0.0.1:8000', // Esto asume que Laravel está corriendo en este puerto
    changeOrigin: true,
  })
);

module.exports = app;
