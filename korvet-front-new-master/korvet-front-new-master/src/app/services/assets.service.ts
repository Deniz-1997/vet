import {Injectable} from '@angular/core';
import {SettingsService} from './settings.service';
import {map, take} from 'rxjs/operators';

declare var $: any;

interface Script {
  name: string;
  src: string | Promise<string>;
  loaded?: boolean;
  promise?: Promise<Script>;
  status?: string;
}

@Injectable({
  providedIn: 'root'
})
export class AssetsService {

  readonly scripts: { [name: string]: Script } = {};

  constructor(private settings: SettingsService) {
    const scripts: Script[] = [
      {name: 'markup-main', src: './assets/js/main.min.js'},
      {
        name: 'yamaps-lib', src: settings.getSetting('map.apiKey').pipe(
          take(1),
          map(setting => {
            const params = new URLSearchParams();
            params.append('load', 'package.full');
            params.append('lang', 'ru_RU');
            if (setting) {
              params.append('apikey', setting.value);
            }
            return 'https://api-maps.yandex.ru/2.1/?' + params;
          }),
        ).toPromise(),
      },
      {name: 'yamaps', src: './other-assets/js/yamaps.js'}
    ];
    this.scripts = scripts.reduce((acc, script) => ({...acc, [script.name]: script}), {});
  }

  execScript(name: string): Promise<Script> {
    if (!this.scripts[name] || !this.scripts[name].promise) {
      this.scripts[name].promise = new Promise((resolve, reject) => {
        if (this.scripts[name]) {
          if (!this.scripts[name].loaded) {
            const srcPromise: Promise<string> = this.scripts[name].src instanceof Promise ?
              <Promise<string>>this.scripts[name].src : new Promise((r) => r(this.scripts[name].src));
            srcPromise.then(s => {
              $.getScript(s, (src: string, status: string, xhr) => {
                if (status) {
                  this.scripts[name].loaded = true;
                }
                resolve(this.scripts[name]);
              });
            });
          } else {
            resolve(this.scripts[name]);
          }
        } else {
          resolve({name: name, loaded: false, src: null});
        }
      });
    }
    return this.scripts[name].promise;
  }
}
