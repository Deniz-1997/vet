import {Injectable} from '@angular/core';
import {ListFilterFieldInterface} from './list-filter.model';

@Injectable({
  providedIn: 'root'
})
export class ListFilterService {
  search: string;
  extended: boolean;
  filter: Array<Array<ListFilterFieldInterface>> = [];
  private filterBuffer: Array<Array<ListFilterFieldInterface>>;
  private filterLocation: string; // Для исключения применения фильтра к другой странице при нескольких переходах назад или вперед

  constructor() {
    window.addEventListener('popstate', _ => {
      this.GetFilterFromHash();
    });
  }

  SetFilterToHash(hash: Array<Array<ListFilterFieldInterface>>): void {
    if (hash) {
      this.filterBuffer = hash;
      this.filterLocation = window.location.pathname;
    } else {
      this.filterBuffer = null;
    }
  }

  GetFilterFromHash(): void {
    if (this.filterBuffer && this.filterLocation === window.location.pathname) {
      this.filter = this.filterBuffer;
      this.extended = true;
    }
  }
}
