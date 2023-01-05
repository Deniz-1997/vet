import {Injectable} from '@angular/core';

declare var $: any;

@Injectable({
  providedIn: 'root'
})
export class YmapsGeoproviderService {

  constructor() {
  }

  suggest(request, options?) {
    return new Promise(((resolve, reject) => {
      const url = 'https://suggest-maps.yandex.ru/suggest-geo?';
      const params = new URLSearchParams([
        ['v', 5],
        ['search_type', 'tp'],
        ['n', 5],
        ['part', request],
        ['rspn', 1],
        ['local_only', options.strictBounds ? 1 : 0],
        ['lang', 'ru_RU'],
        /*['origin', 'jsapi2Geocoder'],
        ['bbox', '35.24409148794242,54.460350331811824,40.81415984731741,56.63954014863938'], // moscow by default*/
      ]);
      if (options.boundedBy) {
        params.set(
          'bbox',
          options.boundedBy[0][0].toString() +
          ',' + options.boundedBy[0][1].toString() + '~'
          + options.boundedBy[1][0].toString() + ','
          + options.boundedBy[1][1].toString()
        );
      }
      const suggest = window['suggest'];
      window['suggest'] = {
        apply: function ([part, geo]) {
          resolve(geo.map(data => ({
            type: data[0],
            displayName: data[1],
            value: data[2],
            hl: data[3],
          })));
        },
      };
      $.getScript(url + params.toString(), (src: string, status: string, xhr) => {
        window['suggest'] = suggest;
      }).fail(() => reject([]));
    }));
  }
}
