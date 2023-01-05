import readline from 'readline';
import { headers } from './consts';

export const getAuthorities = (authorities: Partial<{ [key: string]: string }>) =>
  Object.keys(authorities).reduce<{ [key: string]: boolean }>(
    (result, authority) => ({
      ...result,
      [authority]: true,
    }),
    {}
  );

export async function getToken() {
  const rl = readline.createInterface({
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

export async function getHeaders() {
  return {
    ...headers,
    Authorization: `Basic ${await getToken()}`,
  };
}
