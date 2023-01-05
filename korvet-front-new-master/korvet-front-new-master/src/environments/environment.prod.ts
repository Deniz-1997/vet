import { EnvironmentInterface } from '../app/interfaces/environment.interface';
import Config from './configuration/configuration.json';

export const environment: EnvironmentInterface = {
  production: true,
  credentials: {
    client_id: '1_3u3bpqxw736s4kgo0gsco4kw48gos800gscg4s4w8w80oogc8c',
    client_secret: '6cja0geitwsok4gckw0cc0c04sc0sgwgo8kggcoc08wocsw8wg',
  },
  support: {
    tell: '88005552404',
    tellText: '8 800 555 24 04',
    email: 'help@corvet.ru'
  },
  ...Config,
};
