import {Injectable} from '@angular/core';
import {MatSnackBar} from '@angular/material/snack-bar';
import {ErrorResponseInterface} from '../api/api-connector/api-connector.models';



@Injectable({
  providedIn: 'root'
})
export class SnackBarService {

  constructor(private snackBar: MatSnackBar) {
  }
  handleErrors(errors: Array<ErrorResponseInterface>): void {
    if (errors && errors.length) {
      errors.forEach(error => this.handleMessage(error.message, 'danger-snackBar', 10000));
    }
  }

   handleMessage(
     message: string,
     panelClass: 'success-snackBar' | 'warning-snackBar' | 'danger-snackBar' | 'info-snackBar',
     duration: number
  ): void {
    this.snackBar.open(message, '', {
      duration: duration,
      horizontalPosition: 'right',
      verticalPosition: 'top',
      panelClass: [panelClass],
    });
  }
}
