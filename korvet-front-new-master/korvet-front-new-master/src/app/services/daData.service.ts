import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {DadataResponse} from '../models/dadata/dadata-response.models';
import {Observable} from 'rxjs';
import {SettingsService} from './settings.service';
import {Urls} from '../common/urls';

@Injectable({
  providedIn: 'root'
})
export class DaDataService  {
  token: string;
  constructor(private http: HttpClient,
              private settings: SettingsService) {
    if (this.token === undefined) {
      settings.getSetting('dadata.apiKey').subscribe(x => this.token = x.value);
    }
  }

  daDataFunc(value, parts): Observable<DadataResponse> {

    const httpOptions = {
      headers: new HttpHeaders({
        Accept: 'application/json',
        'Content-Type': 'application/json',
        Authorization: 'Token ' + this.token,
      })
    };
    const body = Object.assign(
      {},
      {
        query: value,
        parts: parts,
      },

    );
    return this.http.post<DadataResponse>(Urls.apiDaData, body, httpOptions);

  }

}
