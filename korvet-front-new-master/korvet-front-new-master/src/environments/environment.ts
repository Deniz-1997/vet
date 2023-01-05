// This file can be replaced during build by using the `fileReplacements` array.
// `ng build ---prod` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

import { EnvironmentInterface } from '../app/interfaces/environment.interface';

export const environment: EnvironmentInterface = {
  production: false,
  credentials: {
    client_id: '1_3u3bpqxw736s4kgo0gsco4kw48gos800gscg4s4w8w80oogc8c',
    client_secret: '6cja0geitwsok4gckw0cc0c04sc0sgwgo8kggcoc08wocsw8wg',
  },
  support: {
    tell: '88005552404',
    tellText: '8 800 555 24 04',
    email: 'help@corvet.ru'
  },
};

/*
 * In development mode, to ignore zone related error stack frames such as
 * `zone.run`, `zoneDelegate.invokeTask` for easier debugging, you can
 * import the following file, but please comment it out in production mode
 * because it will have performance impact when throw error
 */
// import 'zone.js/dist/zone-error';  // Included with Angular CLI.
