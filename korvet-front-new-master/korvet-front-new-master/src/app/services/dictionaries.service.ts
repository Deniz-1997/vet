import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {Injectable} from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class DictionariesService {

  constructor(
    private http: HttpClient
  ) {}
  public getJSON(map): Observable<any> {
    return this.http.get(map);
  }
}
