import {Injectable} from '@angular/core';
import {NgxSpinnerService} from 'ngx-spinner';

@Injectable({
  providedIn: 'root'
})
export class BlockScreenService {

  constructor(private spinner: NgxSpinnerService) {
  }

  state(loading: boolean): void {
    loading ? this.spinner.show() : this.spinner.hide();
  }
}
