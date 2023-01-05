'use strict';
Object.defineProperty(exports, '__esModule', { value: true });
exports.getHeaders = exports.getToken = exports.getAuthorities = void 0;
const tslib_1 = require('tslib');
const readline_1 = tslib_1.__importDefault(require('readline'));
const consts_1 = require('./consts');
const getAuthorities = (authorities) =>
  Object.keys(authorities).reduce(
    (result, authority) => ({
      ...result,
      [authority]: true,
    }),
    {}
  );
exports.getAuthorities = getAuthorities;
async function getToken() {
  const rl = readline_1.default.createInterface({
    input: process.stdin,
    output: process.stdout,
  });
  const username = await new Promise((resolve) => {
    rl.question('Login:', resolve);
  });
  const password = await new Promise((resolve) => {
    rl.question('Password:', resolve);
  });
  rl.close();
  return Buffer.from(`${username}:${password}`).toString('base64');
}
exports.getToken = getToken;
async function getHeaders() {
  return {
    ...consts_1.headers,
    Authorization: `Basic ${await getToken()}`,
  };
}
exports.getHeaders = getHeaders;
